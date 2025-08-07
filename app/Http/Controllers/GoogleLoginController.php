<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use InnoShop\Common\Repositories\Customer\SocialRepo;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;

class GoogleLoginController extends Controller
{
    /**
     * Redirect to Google OAuth dengan domain restriction untuk IPB
     */
    public function redirect(): RedirectResponse
    {
        try {
            // Initialize social config dari InnoShop
            SocialRepo::getInstance()->initSocialConfig();
            
            // Redirect ke Google dengan hosted domain restriction untuk IPB
            return Socialite::driver('google')
                ->with(['hd' => 'apps.ipb.ac.id'])
                ->redirect();
        } catch (Exception $e) {
            Log::error('Google OAuth Redirect Error: ' . $e->getMessage());
            return redirect(front_route('login.index'))->withErrors([
                'email' => 'Terjadi kesalahan saat menghubungkan ke Google. Silakan coba lagi.'
            ]);
        }
    }

    /**
     * Handle Google OAuth callback dengan validasi IPB domain
     */
    public function callback()
    {
        try {
            // Initialize social config dari InnoShop
            SocialRepo::getInstance()->initSocialConfig();
            
            $user = Socialite::driver('google')->user();
            
            // Validasi domain IPB - hanya email @apps.ipb.ac.id yang diizinkan
            if (!str_ends_with($user->getEmail(), '@apps.ipb.ac.id')) {
                return redirect(front_route('login.index'))->withErrors([
                    'email' => 'Login gagal. Hanya email mahasiswa/dosen IPB (@apps.ipb.ac.id) yang diizinkan.'
                ]);
            }

            // Siapkan data user sesuai format InnoShop
            $userData = [
                'uid'    => $user->getId(),
                'email'  => $user->getEmail(),
                'name'   => $user->getName(),
                'avatar' => $user->getAvatar(),
                'token'  => $user->token,
                'raw'    => $user->getRaw(),
            ];

            // Buat customer menggunakan SocialRepo InnoShop
            $customer = SocialRepo::getInstance()->createCustomer('google', $userData);
            
            // Login menggunakan customer guard InnoShop
            auth('customer')->login($customer);

            // Redirect ke account page dengan pesan sukses
            return redirect(front_route('account.index'))->with('success', 'Login berhasil! Selamat datang di FindMyThings.');

        } catch (Exception $e) {
            // Log error untuk debugging
            Log::error('Google OAuth Callback Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect(front_route('login.index'))->withErrors([
                'email' => 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi. Error: ' . $e->getMessage()
            ]);
        }
    }
}
