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
use InnoShop\Common\Models\Address;
use InnoShop\Common\Models\Customer\Favorite;
use InnoShop\Common\Models\Order;
use App\Models\Submission;

class AccountController extends Controller
{
    /**
     * @return mixed
     */
    public function index(): mixed
    {
        $customer   = current_customer();
        $customerID = $customer->id;
        $data       = [
            'customer'                   => $customer,
            // Menghitung jumlah titipan berdasarkan statusnya
            'pending_submissions_count'  => Submission::where('user_id', $customerID)->where('status', 'pending')->count(),
            'revision_submissions_count' => Submission::where('user_id', $customerID)->where('status', 'revision_needed')->count(),
            'approved_submissions_count' => Submission::where('user_id', $customerID)->where('status', 'approved')->count(),
            // Mengambil 5 titipan terakhir untuk ditampilkan di tabel
            'latest_submissions'         => Submission::where('user_id', $customerID)->latest()->take(5)->get(),
        ];

        return inno_view('account.home', $data);
    }
}
