<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Core\Dashboard\Livewire\AdminDashboard;
use App\Modules\CRM\Clients\Livewire\ManageClients;
use App\Modules\CRM\Websites\Livewire\ManageWebsites;
use App\Modules\CRM\Staff\Livewire\ManageStaff;
Route::get('/dashboard', AdminDashboard::class)->name('dashboard');

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Clients page using custom Livewire component
Route::get('/clients', ManageClients::class)->name('clients');
Route::get('/clients/plans', \App\Modules\CRM\Clients\Livewire\ManagePlans::class)->name('clients.plans');

Route::get('/staff', ManageStaff::class)->name('staff');

Route::get('/websites/service-types', \App\Modules\CRM\Websites\Livewire\ManageServiceTypes::class)->name('websites.service-types');
Route::get('/websites', ManageWebsites::class)->name('websites');

Route::get('/marketing-reports', function () {
    return view('modules.core.placeholder', ['title' => 'Marketing Reports']);
})->name('marketing');

Route::get('/maintenance-reports', function () {
    return view('modules.core.placeholder', ['title' => 'Maintenance Reports']);
})->name('maintenance');

Route::get('/support-center', function () {
    return view('modules.core.placeholder', ['title' => 'Support Center']);
})->name('support');

Route::get('/integrations', function () {
    return view('modules.core.placeholder', ['title' => 'Integrations Panel']);
})->name('integrations');

Route::get('/analytics', function () {
    return view('modules.core.placeholder', ['title' => 'Analytics Dashboard']);
})->name('analytics');

Route::get('/revenue', function () {
    return view('modules.core.placeholder', ['title' => 'Revenue & Billing']);
})->name('revenue');

Route::get('/notifications', function () {
    return view('modules.core.placeholder', ['title' => 'Notifications Center']);
})->name('notifications');

Route::get('/documents', function () {
    return view('modules.core.placeholder', ['title' => 'Documents Library']);
})->name('documents');

use App\Modules\Core\Activity\Livewire\ManageActivityLogs;

Route::get('/automation', function () {
    return view('modules.core.placeholder', ['title' => 'Workflow Automation']);
})->name('automation');

Route::get('/settings', function () {
    return view('modules.core.placeholder', ['title' => 'Settings Center']);
})->name('settings');

Route::get('/profile', function () {
    return view('modules.core.profile');
})->name('profile');

Route::get('/activity-logs', ManageActivityLogs::class)->name('activity-logs');
