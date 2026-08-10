<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Livewire\Livewire::component('manage-clients', \App\Modules\CRM\Clients\Livewire\ManageClients::class);
        \Livewire\Livewire::component('manage-websites', \App\Modules\CRM\Websites\Livewire\ManageWebsites::class);
        \Livewire\Livewire::component('manage-activity-logs', \App\Modules\Core\Activity\Livewire\ManageActivityLogs::class);
        \Livewire\Livewire::component('manage-service-types', \App\Modules\CRM\Websites\Livewire\ManageServiceTypes::class);
        \Livewire\Livewire::component('manage-staff', \App\Modules\CRM\Staff\Livewire\ManageStaff::class);
        \Livewire\Livewire::component('manage-plans', \App\Modules\CRM\Clients\Livewire\ManagePlans::class);
        \Livewire\Livewire::component('manage-maintenance-reports', \App\Modules\CRM\Maintenance\Livewire\ManageMaintenanceReports::class);
        \Livewire\Livewire::component('create-maintenance-report', \App\Modules\CRM\Maintenance\Livewire\CreateMaintenanceReport::class);
        \Livewire\Livewire::component('edit-maintenance-report', \App\Modules\CRM\Maintenance\Livewire\EditMaintenanceReport::class);
        \Livewire\Livewire::component('view-maintenance-report', \App\Modules\CRM\Maintenance\Livewire\ViewMaintenanceReport::class);
        \Livewire\Livewire::component('manage-designations', \App\Modules\CRM\Staff\Livewire\ManageDesignations::class);
        \Livewire\Livewire::component('manage-admins', \App\Modules\Core\Authentication\Livewire\ManageAdmins::class);
        \Livewire\Livewire::component('manage-documents', \App\Modules\CRM\Documents\Livewire\ManageDocuments::class);
        \Livewire\Livewire::component('client-dashboard', \App\Modules\Client\Dashboard\Livewire\ClientDashboard::class);
        \Livewire\Livewire::component('client-websites', \App\Modules\Client\Dashboard\Livewire\ClientMyWebsites::class);


    }
}
