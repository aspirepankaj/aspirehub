<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Volt::route('login', 'pages.admin-auth.login')
        ->name('login');

    Volt::route('forgot-password', 'pages.admin-auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'pages.admin-auth.reset-password')
        ->name('password.reset');
});
