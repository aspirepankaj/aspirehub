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
    }
}
