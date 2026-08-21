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

use App\Modules\CRM\Staff\Livewire\Portal\StaffClickUpTickets;

Route::get('/', StaffDashboard::class)->name('dashboard');
Route::get('/clients', StaffClients::class)->name('clients');
Route::get('/clients/ADSCL-{id}', StaffClients::class)->name('clients.detail');
Route::get('/clickup-tickets', StaffClickUpTickets::class)->name('clickup-tickets');
Route::get('/websites', StaffWebsites::class)->name('websites');
Route::get('/websites/ADSWS-{id}', StaffWebsites::class)->name('websites.detail');

Route::get('/maintenance', StaffMaintenance::class)->name('maintenance');
Route::get('/maintenance/create', StaffCreateMaintenanceReport::class)->name('maintenance.create');
Route::get('/maintenance/{id}/edit', StaffEditMaintenanceReport::class)->name('maintenance.edit');
Route::get('/maintenance/{id}', StaffViewMaintenanceReport::class)->name('maintenance.view');
Route::get('/maintenance/{id}/pdf', [StaffMaintenance::class, 'downloadPdf'])->name('maintenance.pdf');

use App\Modules\CRM\Media\Livewire\ManageMedia;

use App\Modules\CRM\Staff\Livewire\Portal\StaffSupport;

Route::get('/documents', StaffDocuments::class)->name('documents');
Route::get('/media', ManageMedia::class)->name('media');
Route::get('/profile', StaffProfile::class)->name('profile');
Route::get('/support', StaffSupport::class)->name('support');
Route::get('/support/ticket/{ticket}', StaffSupport::class)->name('support.detail');
Route::get('/notifications', \App\Livewire\UserNotificationsPage::class)->name('notifications');
