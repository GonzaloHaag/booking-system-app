<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'staff'])->group(function () {
    Route::view('/staff/dashboard', 'staff.dashboard')->name('staff.dashboard');
    Route::view('/staff/customers', 'staff.customers')->name('staff.customers');
    Route::view('/staff/employees', 'staff.employees')->name('staff.employees');
    Route::view('/staff/appointments', 'staff.appointments')->name('staff.appointments');
});
