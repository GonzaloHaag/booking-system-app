<?php

use App\Models\Employee;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'staff'])->group(function () {
    Route::view('/staff/dashboard', 'staff.dashboard')->name('staff.dashboard');
    Route::view('/staff/customers', 'staff.customers')->name('staff.customers');
    Route::view('/staff/employees', 'staff.employees')->name('staff.employees')->can('viewAny', Employee::class);
    Route::view('/staff/appointments', 'staff.appointments')->name('staff.appointments');
    Route::view('/staff/services', 'staff.services')->name('staff.services');
});
