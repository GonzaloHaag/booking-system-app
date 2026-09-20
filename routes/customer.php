<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'customer'])->group(function () {
    Route::view('/customer/dashboard', 'customer.dashboard')->name('customer.dashboard');
});
