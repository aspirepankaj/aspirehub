<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Core\Authentication\Models\Role;
use App\Modules\Core\Authentication\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;
    private User $regularUser;
    private User $inactiveAdminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create standard admin role
        $role = Role::create([
            'name' => 'Administrator',
            'slug' => 'admin',
        ]);

        // Create a valid active administrator
        $this->adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@aspirehub.com',
        ]);
        Admin::create([
            'user_id' => $this->adminUser->id,
            'role_id' => $role->id,
            'is_active' => true,
        ]);

        // Create a regular user without admin profile
        $this->regularUser = User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@aspirehub.com',
        ]);

        // Create an inactive administrator
        $this->inactiveAdminUser = User::factory()->create([
            'name' => 'Inactive Admin',
            'email' => 'inactive@aspirehub.com',
        ]);
        Admin::create([
            'user_id' => $this->inactiveAdminUser->id,
            'role_id' => $role->id,
            'is_active' => false,
        ]);
    }

    public function test_guests_cannot_access_admin_dashboard()
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_regular_users_cannot_access_admin_dashboard()
    {
        $response = $this->actingAs($this->regularUser)->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
        $this->assertFalse(\Auth::check());
    }

    public function test_inactive_admins_cannot_access_admin_dashboard()
    {
        $response = $this->actingAs($this->inactiveAdminUser)->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
        $this->assertFalse(\Auth::check());
    }

    public function test_active_admins_can_access_admin_dashboard()
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Total Clients');
    }

    public function test_active_admins_can_access_placeholder_routes()
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.clients'));
        $response->assertStatus(200);
        $response->assertSee('Clients Management');
    }
}
