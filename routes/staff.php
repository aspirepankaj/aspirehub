<?php

use Illuminate\Support\Facades\Route;
use App\Modules\CRM\Staff\Livewire\Portal\StaffDashboard;
use App\Modules\CRM\Staff\Livewire\Portal\StaffClients;
use App\Modules\CRM\Staff\Livewire\Portal\StaffWebsites;
use App\Modules\CRM\Staff\Livewire\Portal\StaffMaintenance;
use App\Modules\CRM\Staff\Livewire\Portal\StaffCreateMaintenanceReport;
use App\Modules\CRM\Staff\Livewire\Portal\StaffEditMaintenanceReport;
use App\Modules\CRM\Staff\Livewire\Portal\StaffViewMaintenanceReport;
use App\Modules\CRM\Staff\Livewire\Portal\StaffDocuments;
use App\Modules\CRM\Staff\Livewire\Portal\StaffProfile;

Route::get('/', StaffDashboard::class)->name('dashboard');
Route::get('/clients', StaffClients::class)->name('clients');
Route::get('/clients/ADSCL-{id}', StaffClients::class)->name('clients.detail');
Route::get('/websites', StaffWebsites::class)->name('websites');

Route::get('/maintenance', StaffMaintenance::class)->name('maintenance');
Route::get('/maintenance/create', StaffCreateMaintenanceReport::class)->name('maintenance.create');
Route::get('/maintenance/{id}/edit', StaffEditMaintenanceReport::class)->name('maintenance.edit');
Route::get('/maintenance/{id}', StaffViewMaintenanceReport::class)->name('maintenance.view');
Route::get('/maintenance/{id}/pdf', [StaffMaintenance::class, 'downloadPdf'])->name('maintenance.pdf');

Route::get('/documents', StaffDocuments::class)->name('documents');
Route::get('/profile', StaffProfile::class)->name('profile');
