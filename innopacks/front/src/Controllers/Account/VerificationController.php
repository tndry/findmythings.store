<?php

namespace InnoShop\Front\Controllers\Account;

use App\Http\Controllers\Controller;
use InnoShop\Common\Models\Customer;

class VerificationController extends Controller
{
    public function verifyEmail($token)
    {
        $customer = Customer::query()->where('verification_token', $token)->first();

        if (!$customer) {
            return redirect(front_route('login.index'))->withErrors(['email' => trans('front/auth.token_invalid')]);
        }

        $customer->email_verified_at = now();
        $customer->verification_token = null;
        $customer->active = 1;
        $customer->save();

        auth('customer')->login($customer);

        return redirect(front_route('account.index'))->with('status', trans('front/auth.verification_success'));
    }
}