<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Volt::route('login', 'pages.staff-auth.login')
        ->name('login');

    Volt::route('forgot-password', 'pages.staff-auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'pages.staff-auth.reset-password')
        ->name('password.reset');
});
