<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Core\Authentication\Models\Role;
use App\Modules\Core\Authentication\Models\Admin;
use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Clients\Livewire\ManageClients;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ClientManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;
    private User $regularUser;
    private User $inactiveAdminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create([
            'name' => 'Administrator',
            'slug' => 'admin',
        ]);

        // Active Admin
        $this->adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@aspirehub.com',
        ]);
        Admin::create([
            'user_id' => $this->adminUser->id,
            'role_id' => $role->id,
            'is_active' => true,
        ]);

        // Regular User
        $this->regularUser = User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@aspirehub.com',
        ]);

        // Inactive Admin
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

    public function test_guests_cannot_access_client_management()
    {
        $response = $this->get(route('admin.clients'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_regular_users_cannot_access_client_management()
    {
        $response = $this->actingAs($this->regularUser)->get(route('admin.clients'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_inactive_admins_cannot_access_client_management()
    {
        $response = $this->actingAs($this->inactiveAdminUser)->get(route('admin.clients'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_active_admins_can_access_client_management()
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.clients'));
        $response->assertStatus(200);
    }

    public function test_admin_can_add_client_successfully()
    {
        $this->actingAs($this->adminUser);

        Livewire::test(ManageClients::class)
            ->set('name', 'Test Client')
            ->set('email', 'client@test.com')
            ->set('password', 'password123')
            ->set('company_name', 'Acme Corp')
            ->set('phone', '1234567890')
            ->set('status', 'active')
            ->set('notes', 'Some client notes here.')
            ->call('saveClient')
            ->assertHasNoErrors()
            ->assertDispatched('close-modal', name: 'add-client-modal');

        // Verify User was created
        $this->assertDatabaseHas('users', [
            'name' => 'Test Client',
            'email' => 'client@test.com',
        ]);

        // Verify Client details were created
        $user = User::where('email', 'client@test.com')->first();
        $this->assertNotNull($user);

        $this->assertDatabaseHas('adspv_clients', [
            'user_id' => $user->id,
            'company_name' => 'Acme Corp',
            'phone' => '1234567890',
            'status' => 'active',
            'notes' => 'Some client notes here.',
            'added_by' => $this->adminUser->id,
            'edited_by' => null,
        ]);
    }

    public function test_admin_adding_client_validates_required_fields()
    {
        $this->actingAs($this->adminUser);

        Livewire::test(ManageClients::class)
            ->set('name', '')
            ->set('email', '')
            ->set('password', '')
            ->call('saveClient')
            ->assertHasErrors(['name', 'email', 'password']);
    }

    public function test_admin_adding_client_validates_unique_email()
    {
        $this->actingAs($this->adminUser);

        // Pre-create a user with this email
        User::factory()->create(['email' => 'duplicate@example.com']);

        Livewire::test(ManageClients::class)
            ->set('name', 'New Client')
            ->set('email', 'duplicate@example.com')
            ->set('password', 'password123')
            ->call('saveClient')
            ->assertHasErrors(['email' => 'unique']);
    }

    public function test_admin_can_edit_client_successfully_without_changing_password()
    {
        $this->actingAs($this->adminUser);

        // Pre-create a client
        $clientUser = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@client.com',
            'password' => bcrypt('originalpassword'),
        ]);
        $client = Client::create([
            'user_id' => $clientUser->id,
            'company_name' => 'Original Corp',
            'phone' => '1111111111',
            'status' => 'active',
            'notes' => 'Original notes.',
            'added_by' => $this->adminUser->id,
        ]);

        Livewire::test(ManageClients::class)
            ->call('editClient', $client->id)
            ->assertSet('name', 'Original Name')
            ->assertSet('email', 'original@client.com')
            ->assertSet('password', '') // Password should load empty
            ->set('name', 'Updated Name')
            ->set('company_name', 'Updated Corp')
            ->call('updateClient')
            ->assertHasNoErrors()
            ->assertDispatched('close-modal', name: 'edit-client-modal');

        // Assert database updated
        $this->assertDatabaseHas('users', [
            'id' => $clientUser->id,
            'name' => 'Updated Name',
            'email' => 'original@client.com',
        ]);

        $this->assertDatabaseHas('adspv_clients', [
            'id' => $client->id,
            'company_name' => 'Updated Corp',
            'edited_by' => $this->adminUser->id,
        ]);
    }

    public function test_admin_editing_client_ignores_own_email_on_unique_validation()
    {
        $this->actingAs($this->adminUser);

        // Pre-create a client
        $clientUser = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@client.com',
        ]);
        $client = Client::create([
            'user_id' => $clientUser->id,
            'company_name' => 'Original Corp',
            'added_by' => $this->adminUser->id,
        ]);

        Livewire::test(ManageClients::class)
            ->call('editClient', $client->id)
            ->set('name', 'Updated Name')
            // Save email unchanged (it should ignore itself and not fail unique check)
            ->set('email', 'original@client.com')
            ->call('updateClient')
            ->assertHasNoErrors();
    }
}
