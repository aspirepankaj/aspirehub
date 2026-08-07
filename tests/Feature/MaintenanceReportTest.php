<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Core\Authentication\Models\Admin;
use App\Modules\Core\Authentication\Models\Role;
use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Websites\Models\Website;
use App\Modules\CRM\Maintenance\Models\MaintenanceReport;
use App\Modules\CRM\Maintenance\Livewire\CreateMaintenanceReport;
use App\Modules\CRM\Maintenance\Livewire\ManageMaintenanceReports;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MaintenanceReportTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;
    private User $regularUser;
    private Client $client;
    private Website $website;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create(['name' => 'Administrator', 'slug' => 'admin']);

        $this->adminUser = User::factory()->create(['name' => 'Admin User', 'email' => 'admin@test.com']);
        Admin::create(['user_id' => $this->adminUser->id, 'role_id' => $role->id, 'is_active' => true]);

        $this->regularUser = User::factory()->create(['email' => 'regular@test.com']);

        $clientUser = User::factory()->create(['email' => 'client@test.com']);
        $this->client = Client::create([
            'user_id'      => $clientUser->id,
            'company_name' => 'Test Corp',
            'added_by'     => $this->adminUser->id,
        ]);

        $this->website = Website::create([
            'client_id' => $this->client->id,
            'site_name' => 'Test Site',
            'url' => 'https://example.com',
            'hosting_provider' => 'Hostinger',
        ]);
    }

    public function test_guests_cannot_access_maintenance_reports(): void
    {
        $response = $this->get(route('admin.maintenance'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_active_admins_can_access_maintenance_reports_list(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.maintenance'));
        $response->assertOk();
    }

    public function test_admin_can_create_maintenance_report(): void
    {
        Livewire::actingAs($this->adminUser)
            ->test(CreateMaintenanceReport::class)
            ->set('client_id', $this->client->id)
            ->set('website_id', $this->website->id)
            ->set('maintenance_month', 'August 2026')
            ->set('maintenance_date', '2026-08-06')
            ->set('wp_version_current', '6.5.0')
            ->set('wp_version_latest', '6.6.0')
            ->set('wp_updated', true)
            ->set('plugins', [
                [
                    'plugin_name' => 'Elementor Pro',
                    'old_version' => '3.19.0',
                    'new_version' => '3.20.0',
                    'status' => 'updated',
                    'notes' => 'Updated successfully',
                ]
            ])
            ->call('saveReport')
            ->assertHasNoErrors()
            ->assertRedirect(route('admin.maintenance'));

        $this->assertDatabaseHas('adspv_maintenance_reports', [
            'client_id' => $this->client->id,
            'website_id' => $this->website->id,
            'maintenance_month' => 'August 2026',
        ]);

        $this->assertDatabaseHas('adspv_maintenance_report_plugins', [
            'plugin_name' => 'Elementor Pro',
            'status' => 'updated',
        ]);
    }

    public function test_admin_can_download_maintenance_report_pdf(): void
    {
        $report = MaintenanceReport::create([
            'client_id' => $this->client->id,
            'website_id' => $this->website->id,
            'developer_id' => $this->adminUser->id,
            'maintenance_month' => 'August 2026',
            'maintenance_date' => '2026-08-06',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->adminUser)->get(route('admin.maintenance.pdf', $report->id));
        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }
}
