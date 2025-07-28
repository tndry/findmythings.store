<?php
/**
 * Copyright (c) Since 2024 InnoShop - All Rights Reserved
 *
 * @link       https://www.innoshop.com
 * @author     InnoShop <team@innoshop.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubmissionController;

Route::middleware(['front', 'customer_auth:customer', 'verified'])->group(function () {
    Route::get('/titip-jual', [\App\Http\Controllers\SubmissionController::class, 'create'])->name('submission.create');
    Route::post('/titip-jual', [\App\Http\Controllers\SubmissionController::class, 'store'])->name('submission.store');
});