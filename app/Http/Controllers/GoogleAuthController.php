<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use InnoShop\Common\Repositories\Customer\SocialRepo;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth dengan domain restriction untuk IPB
     *
     * @return RedirectResponse
     * @throws Exception
     */
    public function redirect(): RedirectResponse
    {
        // Log untuk debugging
        \Log::info('Google OAuth Redirect Started', [
            'referer' => request()->headers->get('referer'),
            'user_agent' => request()->headers->get('user-agent'),
            'ip' => request()->ip(),
            'timestamp' => now()
        ]);

        // Setup Google Socialite config langsung (bypass SocialRepo yang bermasalah)
        \Illuminate\Support\Facades\Config::set('services.google', [
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'redirect' => url('/google-ipb/callback') // URI independent
        ]);
        
        // Simpan context apakah dari register atau login
        $referer = request()->headers->get('referer');
        $context = 'login'; // default
        
        if ($referer && (str_contains($referer, '/register') || str_contains($referer, '/registrasi'))) {
            $context = 'register';
        }
        
        session(['google_oauth_context' => $context]);
        
        \Log::info('Google OAuth Context Set', [
            'context' => $context,
            'referer' => $referer,
            'session_id' => session()->getId()
        ]);
        
        try {
            // Redirect ke Google dengan hosted domain restriction untuk IPB
            return Socialite::driver('google')
                ->with(['hd' => 'apps.ipb.ac.id'])
                ->redirect();
            
        } catch (Exception $e) {
            \Log::error('Google OAuth Redirect Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect(front_route('login.index'))->withErrors([
                'google_oauth' => 'Gagal mengarahkan ke Google OAuth: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Handle Google OAuth callback dengan validasi IPB domain
     *
     * @return mixed
     * @throws Throwable
     */
    public function callback(): mixed
    {
        \Log::info('Google OAuth Callback Started', [
            'query_params' => request()->all(),
            'session_id' => session()->getId(),
            'timestamp' => now()
        ]);

        try {
            // Setup Google Socialite config langsung (bypass SocialRepo yang bermasalah)
            \Illuminate\Support\Facades\Config::set('services.google', [
                'client_id' => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
                'redirect' => url('/google-ipb/callback') // URI independent
            ]);
            
            $user = Socialite::driver('google')->user();
            
            \Log::info('Google OAuth User Retrieved', [
                'email' => $user->getEmail(),
                'name' => $user->getName(),
                'id' => $user->getId()
            ]);
            
            // Validasi domain IPB - hanya email @apps.ipb.ac.id yang diizinkan
            if (!str_ends_with($user->getEmail(), '@apps.ipb.ac.id')) {
                \Log::warning('Google OAuth Invalid Domain', [
                    'email' => $user->getEmail(),
                    'expected_domain' => '@apps.ipb.ac.id'
                ]);
                
                return redirect(front_route('login.index'))->withErrors([
                    'email' => 'Login gagal. Hanya email mahasiswa/dosen IPB (@apps.ipb.ac.id) yang diizinkan.'
                ]);
            }

            // Cek context apakah dari register atau login
            $context = session('google_oauth_context', 'login');
            session()->forget('google_oauth_context');
            
            \Log::info('Google OAuth Context Retrieved', [
                'context' => $context,
                'email' => $user->getEmail()
            ]);
            
            // Cek apakah customer sudah exist
            $existingCustomer = \InnoShop\Common\Models\Customer::where('email', $user->getEmail())->first();
            
            \Log::info('Customer Check Result', [
                'email' => $user->getEmail(),
                'customer_exists' => $existingCustomer ? true : false,
                'customer_id' => $existingCustomer ? $existingCustomer->id : null,
                'context' => $context
            ]);
            
            if ($context === 'register') {
                // Flow untuk REGISTRASI
                if ($existingCustomer) {
                    \Log::info('Registration Attempt - User Already Exists', [
                        'email' => $user->getEmail(),
                        'existing_customer_id' => $existingCustomer->id
                    ]);
                    
                    // User sudah ada, redirect ke login dengan pesan
                    return redirect(front_route('login.index'))->withErrors([
                        'email' => 'Email ' . $user->getEmail() . ' sudah terdaftar. Silakan gunakan halaman login.'
                    ]);
                }
                
                \Log::info('Creating New Customer via Registration', [
                    'email' => $user->getEmail(),
                    'name' => $user->getName()
                ]);
                
                // Buat customer baru
                $userData = [
                    'uid'    => $user->getId(),
                    'email'  => $user->getEmail(),
                    'name'   => $user->getName(),
                    'avatar' => $user->getAvatar(),
                    'token'  => $user->token,
                    'raw'    => $user->getRaw(),
                ];

                $customer = SocialRepo::getInstance()->createCustomer('google', $userData);
                
                \Log::info('New Customer Created', [
                    'customer_id' => $customer->id,
                    'email' => $customer->email,
                    'name' => $customer->name
                ]);
                
                // Pastikan email terverifikasi untuk Google OAuth users
                if (!$customer->hasVerifiedEmail()) {
                    $customer->email_verified_at = now();
                    $customer->save();
                    \Log::info('Customer Email Verified', ['customer_id' => $customer->id]);
                }
                
                // Login setelah registrasi
                auth('customer')->login($customer);
                
                \Log::info('Customer Logged In After Registration', [
                    'customer_id' => $customer->id,
                    'auth_check' => auth('customer')->check()
                ]);

                return redirect(front_route('account.index'))->with('success', 
                    'Registrasi berhasil! Selamat datang di FindMyThings, ' . $customer->name . '! Akun Anda sudah terverifikasi.');
                
            } else {
                // Flow untuk LOGIN
                if (!$existingCustomer) {
                    \Log::info('Login Attempt - User Not Found', [
                        'email' => $user->getEmail()
                    ]);
                    
                    // User belum ada, redirect ke register dengan pesan
                    return redirect(front_route('register.index'))->withErrors([
                        'email' => 'Email ' . $user->getEmail() . ' belum terdaftar. Silakan registrasi terlebih dahulu.'
                    ]);
                }
                
                \Log::info('Logging In Existing Customer', [
                    'customer_id' => $existingCustomer->id,
                    'email' => $existingCustomer->email
                ]);
                
                // Login user yang sudah ada
                auth('customer')->login($existingCustomer);

                return redirect(front_route('account.index'))->with('success', 
                    'Login berhasil! Selamat datang kembali, ' . $existingCustomer->name . '.');
            }

        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Google OAuth Callback Error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'context' => session('google_oauth_context', 'unknown'),
                'query_params' => request()->all()
            ]);
            
            // Bersihkan session context
            session()->forget('google_oauth_context');

            $context = session('google_oauth_context', 'login');
            $redirectRoute = ($context === 'register') ? 'register.index' : 'login.index';
            
            return redirect(front_route($redirectRoute))->withErrors([
                'google_oauth' => 'Terjadi kesalahan saat proses Google OAuth. Silakan coba lagi atau gunakan metode login biasa.'
            ]);
        }
    }
}
