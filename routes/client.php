<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Client\Dashboard\Livewire\ClientDashboard;
use App\Modules\Client\Dashboard\Livewire\ClientMyWebsites;
use App\Modules\Client\Dashboard\Livewire\ClientProfile;
// Client Portal Routing
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', ClientDashboard::class)->name('dashboard');
    Route::get('/websites', ClientMyWebsites::class)->name('websites');
    Route::get('/profile', ClientProfile::class)->name('profile');
});
