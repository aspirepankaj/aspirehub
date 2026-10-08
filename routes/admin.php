<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Core\Dashboard\Livewire\AdminDashboard;
use App\Modules\CRM\Clients\Livewire\ManageClients;
use App\Modules\CRM\Clients\Livewire\AdminClientReport;
use App\Modules\CRM\Websites\Livewire\ManageWebsites;
use App\Modules\CRM\Staff\Livewire\ManageStaff;
use App\Modules\CRM\Maintenance\Livewire\ManageMaintenanceReports;
use App\Modules\CRM\Maintenance\Livewire\CreateMaintenanceReport;
use App\Modules\CRM\Maintenance\Livewire\EditMaintenanceReport;
use App\Modules\CRM\Maintenance\Livewire\ViewMaintenanceReport;
Route::get('/dashboard', AdminDashboard::class)->name('dashboard');

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Clients page using custom Livewire component
Route::get('/clients', ManageClients::class)->name('clients');
Route::get('/clients/ADSCL-{id}', ManageClients::class)->name('clients.detail');
Route::get('/clients/ADSCL-{id}/report/{integration}', AdminClientReport::class)->name('clients.report');


use App\Modules\CRM\Staff\Livewire\ManageDesignations;
use App\Modules\Core\Authentication\Livewire\ManageAdmins;

Route::get('/staff', ManageStaff::class)->name('staff');
Route::get('/staff/ADSTM-{id}', ManageStaff::class)->name('staff.detail');

Route::get('/admins', ManageAdmins::class)->name('admins');


Route::get('/websites', ManageWebsites::class)->name('websites');
Route::get('/websites/ADSWS-{id}', ManageWebsites::class)->name('websites.detail');

use App\Modules\CRM\Reports\Livewire\ManageMarketingReports;

Route::get('/marketing-reports', ManageMarketingReports::class)->name('marketing');

Route::get('/maintenance-reports', ManageMaintenanceReports::class)->name('maintenance');
Route::get('/maintenance-reports/create', CreateMaintenanceReport::class)->name('maintenance.create');
Route::get('/maintenance-reports/{id}/edit', EditMaintenanceReport::class)->name('maintenance.edit');
Route::get('/maintenance-reports/{id}', ViewMaintenanceReport::class)->name('maintenance.view');
Route::get('/maintenance-reports/{id}/pdf', [ManageMaintenanceReports::class, 'downloadPdf'])->name('maintenance.pdf');

use App\Modules\Support\Livewire\ManageSupportTickets;

Route::get('/support-center', ManageSupportTickets::class)->name('support');
Route::get('/support-center/ticket/{ticket}', ManageSupportTickets::class)->name('support.detail');

Route::get('/integrations', function () {
    return view('modules.core.placeholder', ['title' => 'Integrations Panel']);
})->name('integrations');

Route::get('/analytics', function () {
    return view('modules.core.placeholder', ['title' => 'Analytics Dashboard']);
})->name('analytics');

Route::get('/revenue', function () {
    return view('modules.core.placeholder', ['title' => 'Revenue & Billing']);
})->name('revenue');

Route::get('/notifications', \App\Livewire\UserNotificationsPage::class)->name('notifications');

use App\Modules\CRM\Documents\Livewire\ManageDocuments;
use App\Modules\CRM\Media\Livewire\ManageMedia;

Route::get('/documents', ManageDocuments::class)->name('documents');
Route::get('/media', ManageMedia::class)->name('media');

use App\Modules\Core\Activity\Livewire\ManageActivityLogs;

Route::get('/automation', function () {
    return view('modules.core.placeholder', ['title' => 'Workflow Automation']);
})->name('automation');

use App\Livewire\ManageSettings;
Route::get('/settings', ManageSettings::class)->name('settings');

Route::get('/profile', function () {
    return view('modules.core.profile');
})->name('profile');

Route::get('/activity-logs', ManageActivityLogs::class)->name('activity-logs');
Route::get('/email-logs', \App\Modules\Core\Activity\Livewire\ManageEmailLogs::class)->name('email-logs');

use App\Modules\CRM\Clients\Controllers\GoogleIntegrationController;

Route::get('/integrations/google/redirect/{id}', [GoogleIntegrationController::class, 'redirect'])->name('integrations.google.redirect');
