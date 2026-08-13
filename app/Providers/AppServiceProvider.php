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
        \Illuminate\Auth\Notifications\ResetPassword::createUrlUsing(function ($user, string $token) {
            if ($user->staff()->exists()) {
                return url(route('staff.password.reset', [
                    'token' => $token,
                    'email' => $user->getEmailForPasswordReset(),
                ], false));
            }
            return url(route('password.reset', [
                'token' => $token,
                'email' => $user->getEmailForPasswordReset(),
            ], false));
        });

        \Livewire\Livewire::component('manage-clients', \App\Modules\CRM\Clients\Livewire\ManageClients::class);
        \Livewire\Livewire::component('manage-websites', \App\Modules\CRM\Websites\Livewire\ManageWebsites::class);
        \Livewire\Livewire::component('manage-activity-logs', \App\Modules\Core\Activity\Livewire\ManageActivityLogs::class);
        \Livewire\Livewire::component('manage-email-logs', \App\Modules\Core\Activity\Livewire\ManageEmailLogs::class);
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
        \Livewire\Livewire::component('manage-media', \App\Modules\CRM\Media\Livewire\ManageMedia::class);
        \Livewire\Livewire::component('media-picker', \App\Modules\CRM\Media\Livewire\MediaPicker::class);
        \Livewire\Livewire::component('client-dashboard', \App\Modules\Client\Dashboard\Livewire\ClientDashboard::class);
        \Livewire\Livewire::component('client-websites', \App\Modules\Client\Dashboard\Livewire\ClientMyWebsites::class);
        \Livewire\Livewire::component('client-profile', \App\Modules\Client\Dashboard\Livewire\ClientProfile::class);
        \Livewire\Livewire::component('client-documents', \App\Modules\Client\Dashboard\Livewire\ClientDocuments::class);
        \Livewire\Livewire::component('client-maintenance', \App\Modules\Client\Dashboard\Livewire\ClientMaintenanceReports::class);
        \Livewire\Livewire::component('client-view-maintenance-report', \App\Modules\Client\Dashboard\Livewire\ClientViewMaintenanceReport::class);

        \Livewire\Livewire::component('staff-dashboard', \App\Modules\CRM\Staff\Livewire\Portal\StaffDashboard::class);
        \Livewire\Livewire::component('staff-clients', \App\Modules\CRM\Staff\Livewire\Portal\StaffClients::class);
        \Livewire\Livewire::component('staff-websites', \App\Modules\CRM\Staff\Livewire\Portal\StaffWebsites::class);
        \Livewire\Livewire::component('staff-maintenance', \App\Modules\CRM\Staff\Livewire\Portal\StaffMaintenance::class);
        \Livewire\Livewire::component('staff-create-maintenance-report', \App\Modules\CRM\Staff\Livewire\Portal\StaffCreateMaintenanceReport::class);
        \Livewire\Livewire::component('staff-edit-maintenance-report', \App\Modules\CRM\Staff\Livewire\Portal\StaffEditMaintenanceReport::class);
        \Livewire\Livewire::component('staff-view-maintenance-report', \App\Modules\CRM\Staff\Livewire\Portal\StaffViewMaintenanceReport::class);
        \Livewire\Livewire::component('staff-documents', \App\Modules\CRM\Staff\Livewire\Portal\StaffDocuments::class);
        \Livewire\Livewire::component('staff-profile', \App\Modules\CRM\Staff\Livewire\Portal\StaffProfile::class);
    }
}
