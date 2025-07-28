<?php
/**
 * Copyright (c) Since 2024 InnoShop - All Rights Reserved
 *
 * @link       https://www.innoshop.com
 * @author     InnoShop <team@innoshop.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace InnoShop\Front\Controllers\Account;

use App\Http\Controllers\Controller;
use Exception;
use InnoShop\Common\Services\CartService;
use InnoShop\Front\Requests\RegisterRequest;
use InnoShop\Front\Services\AccountService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use Throwable;

class RegisterController extends Controller
{
    /**
     * @return mixed
     * @throws Exception
     */
    public function index(): mixed
    {
        if (current_customer()) {
            return redirect(front_route('account.index'));
        }

        return inno_view('account.register');
    }

    /**
     * @param  RegisterRequest  $request
     * @return mixed
     * @throws Throwable
     */
    public function store(RegisterRequest $request): mixed
    {
        try {
            $credentials = $request->only('email', 'password');
            $customer    = AccountService::getInstance()->register($credentials);

            // Membuat token dan link verifikasi
            $customer->verification_token = Str::random(60);
            $customer->save();

            // Mengirim email verifikasi
            $verificationUrl = front_route('email.verify.manual', ['token' => $customer->verification_token]);
            Mail::to($customer->email)->send(new VerifyEmail($verificationUrl));

            return json_success('Registrasi berhasil! Silakan cek email Anda untuk link verifikasi.');

        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
