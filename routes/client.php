<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Client\Dashboard\Livewire\ClientDashboard;
use App\Modules\Client\Dashboard\Livewire\ClientMyWebsites;
use App\Modules\Client\Dashboard\Livewire\ClientProfile;
use App\Modules\Client\Dashboard\Livewire\ClientDocuments;
use App\Modules\Client\Dashboard\Livewire\ClientMaintenanceReports;
// Client Portal Routing
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', ClientDashboard::class)->name('dashboard');
    Route::get('/websites', ClientMyWebsites::class)->name('websites');
    Route::get('/profile', ClientProfile::class)->name('profile');
    Route::get('/documents', ClientDocuments::class)->name('documents');
    Route::get('/maintenance', ClientMaintenanceReports::class)->name('maintenance');
    Route::get('/maintenance-reports/{id}', \App\Modules\Client\Dashboard\Livewire\ClientViewMaintenanceReport::class)->name('maintenance.view');
    Route::get('/maintenance-reports/{id}/pdf', [ClientMaintenanceReports::class, 'downloadPdf'])->name('maintenance.pdf');
});
