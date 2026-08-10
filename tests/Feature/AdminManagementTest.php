<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Core\Authentication\Models\Admin;
use App\Modules\Core\Authentication\Models\Role;
use App\Modules\Core\Authentication\Livewire\ManageAdmins;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected Role $adminRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::create(['name' => 'Administrator', 'slug' => 'admin']);
        $this->adminUser = User::factory()->create();
        
        Admin::create([
            'user_id' => $this->adminUser->id,
            'role_id' => $this->adminRole->id,
            'is_active' => true,
        ]);
    }

    public function test_can_view_admins_management_page(): void
    {
        $this->actingAs($this->adminUser);

        $response = $this->get(route('admin.admins'));
        $response->assertStatus(200);
    }

    public function test_can_create_new_administrator(): void
    {
        $this->actingAs($this->adminUser);

        Livewire::test(ManageAdmins::class)
            ->set('name', 'Second Admin')
            ->set('email', 'second@example.com')
            ->set('password', 'secretpassword')
            ->set('role_id', $this->adminRole->id)
            ->set('phone', '+123456789')
            ->call('saveAdmin');

        $this->assertDatabaseHas('users', ['email' => 'second@example.com']);
        $this->assertDatabaseHas('adspv_admins', ['phone' => '+123456789']);
    }

    public function test_can_edit_admin_details(): void
    {
        $this->actingAs($this->adminUser);

        $anotherUser = User::factory()->create(['name' => 'Initial Name', 'email' => 'initial@example.com']);
        $anotherAdmin = Admin::create([
            'user_id' => $anotherUser->id,
            'role_id' => $this->adminRole->id,
            'phone' => '111222',
        ]);

        Livewire::test(ManageAdmins::class)
            ->call('editAdmin', $anotherAdmin->id)
            ->assertSet('name', 'Initial Name')
            ->assertSet('phone', '111222')
            ->set('name', 'Updated Name')
            ->set('phone', '333444')
            ->call('updateAdmin');

        $this->assertDatabaseHas('users', ['id' => $anotherUser->id, 'name' => 'Updated Name']);
        $this->assertDatabaseHas('adspv_admins', ['id' => $anotherAdmin->id, 'phone' => '333444']);
    }

    public function test_cannot_delete_self(): void
    {
        $this->actingAs($this->adminUser);
        $myAdminId = $this->adminUser->admin->id;

        Livewire::test(ManageAdmins::class)
            ->call('deleteAdmin', $myAdminId);

        $this->assertDatabaseHas('adspv_admins', ['id' => $myAdminId]);
    }
}
