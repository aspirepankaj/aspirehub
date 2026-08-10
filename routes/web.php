<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('dashboard', function () {
    $user = auth()->user();
    if ($user && $user->admin && $user->admin->is_active) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('client.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('profile', function () {
    $user = auth()->user();
    if ($user && $user->admin && $user->admin->is_active) {
        return redirect()->route('admin.profile');
    }
    return view('profile');
})->middleware(['auth'])->name('profile');

require __DIR__.'/auth.php';
