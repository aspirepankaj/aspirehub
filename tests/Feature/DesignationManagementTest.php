<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\CRM\Staff\Models\Designation;
use App\Modules\CRM\Staff\Livewire\ManageDesignations;
use App\Modules\Core\Authentication\Models\Role;
use App\Modules\Core\Authentication\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DesignationManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        $role = Role::create([
            'name' => 'Administrator',
            'slug' => 'admin',
        ]);

        $this->adminUser = User::factory()->create(['name' => 'Admin User']);
        
        Admin::create([
            'user_id' => $this->adminUser->id,
            'role_id' => $role->id,
            'is_active' => true,
        ]);
    }

    public function test_designations_management_page_can_be_rendered()
    {
        $this->actingAs($this->adminUser);

        $response = $this->get(route('admin.staff.designations'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_designation()
    {
        $this->actingAs($this->adminUser);

        Livewire::test(ManageDesignations::class)
            ->set('name', 'Chief AI Engineer')
            ->set('description', 'Responsible for AI agent logic')
            ->call('saveDesignation')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('adspv_designations', [
            'name' => 'Chief AI Engineer',
            'description' => 'Responsible for AI agent logic',
            'added_by' => $this->adminUser->id,
        ]);

        // Verify activity log is written
        $this->assertDatabaseHas('adspv_activity_logs', [
            'user_id' => $this->adminUser->id,
            'action' => 'created',
            'loggable_type' => Designation::class,
            'description' => "Admin User added new designation: 'Chief AI Engineer'",
        ]);
    }

    public function test_admin_can_update_designation()
    {
        $this->actingAs($this->adminUser);

        $designation = Designation::create([
            'name' => 'Tech Lead',
            'description' => 'Original description',
            'added_by' => $this->adminUser->id,
        ]);

        Livewire::test(ManageDesignations::class)
            ->call('editDesignation', $designation->id)
            ->assertSet('name', 'Tech Lead')
            ->assertSet('description', 'Original description')
            ->set('name', 'Principal Architect')
            ->set('description', 'Updated description')
            ->call('updateDesignation')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('adspv_designations', [
            'id' => $designation->id,
            'name' => 'Principal Architect',
            'description' => 'Updated description',
            'edited_by' => $this->adminUser->id,
        ]);
    }

    public function test_admin_can_delete_designation()
    {
        $this->actingAs($this->adminUser);

        $designation = Designation::create([
            'name' => 'Temp Title',
            'added_by' => $this->adminUser->id,
        ]);

        Livewire::test(ManageDesignations::class)
            ->call('deleteDesignation', $designation->id);

        $this->assertDatabaseMissing('adspv_designations', [
            'id' => $designation->id,
        ]);
    }
}
