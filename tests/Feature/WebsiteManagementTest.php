<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Core\Authentication\Models\Admin;
use App\Modules\Core\Authentication\Models\Role;
use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Websites\Livewire\ManageWebsites;
use App\Modules\CRM\Websites\Models\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Livewire\Livewire;
use Tests\TestCase;

class WebsiteManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;
    private User $regularUser;
    private User $inactiveAdminUser;
    private Client $client;

    private int $serviceTypeId;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create(['name' => 'Administrator', 'slug' => 'admin']);

        $this->adminUser = User::factory()->create(['name' => 'Admin User', 'email' => 'admin@test.com']);
        Admin::create(['user_id' => $this->adminUser->id, 'role_id' => $role->id, 'is_active' => true]);

        $this->regularUser = User::factory()->create(['email' => 'regular@test.com']);

        $this->inactiveAdminUser = User::factory()->create(['email' => 'inactive@test.com']);
        Admin::create(['user_id' => $this->inactiveAdminUser->id, 'role_id' => $role->id, 'is_active' => false]);

        // Create a client for website assignment
        $clientUser = User::factory()->create(['email' => 'client@test.com']);
        $this->client = Client::create([
            'user_id'      => $clientUser->id,
            'company_name' => 'Test Corp',
            'added_by'     => $this->adminUser->id,
        ]);

        // Fetch or create a default service type ID to use in test website creations
        $this->serviceTypeId = \App\Modules\CRM\Websites\Models\ServiceType::first()->id;
    }

    // ─── Access Control ──────────────────────────────────────────────────────────

    public function test_guests_cannot_access_websites_management(): void
    {
        $response = $this->get(route('admin.websites'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_regular_users_cannot_access_websites_management(): void
    {
        $response = $this->actingAs($this->regularUser)->get(route('admin.websites'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_inactive_admins_cannot_access_websites_management(): void
    {
        $response = $this->actingAs($this->inactiveAdminUser)->get(route('admin.websites'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_active_admins_can_access_websites_management(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.websites'));
        $response->assertStatus(200);
        $response->assertSee('Websites Management');
    }

    // ─── Add Website ─────────────────────────────────────────────────────────────

    public function test_admin_can_add_website_successfully(): void
    {
        $this->actingAs($this->adminUser);

        Livewire::test(ManageWebsites::class)
            ->set('client_id', $this->client->id)
            ->set('site_name', 'Test Website')
            ->set('url', 'https://testwebsite.com')
            ->set('service_type_id', $this->serviceTypeId)
            ->set('status', 'active')
            ->set('admin_url', 'https://testwebsite.com/wp-admin')
            ->set('admin_username', 'adminuser')
            ->set('admin_password', 'secret123')
            ->call('saveWebsite')
            ->assertHasNoErrors()
            ->assertDispatched('close-modal', name: 'add-website-modal');

        // Check it was saved to DB
        $this->assertDatabaseHas('adspv_websites', [
            'client_id'       => $this->client->id,
            'site_name'       => 'Test Website',
            'url'             => 'https://testwebsite.com',
            'service_type_id' => $this->serviceTypeId,
            'status'          => 'active',
            'admin_username'  => 'adminuser',
            'added_by'        => $this->adminUser->id,
        ]);

        // Verify password is encrypted in DB (not plain text)
        $website = Website::where('site_name', 'Test Website')->first();
        $this->assertNotNull($website);
        $this->assertNotEquals('secret123', $website->getRawOriginal('admin_password'));
        $this->assertEquals('secret123', Crypt::decryptString($website->getRawOriginal('admin_password')));
    }

    public function test_add_website_validates_required_fields(): void
    {
        $this->actingAs($this->adminUser);

        Livewire::test(ManageWebsites::class)
            ->set('client_id', '')
            ->set('site_name', '')
            ->set('url', '')
            ->call('saveWebsite')
            ->assertHasErrors(['client_id', 'site_name', 'url']);
    }

    public function test_add_website_validates_url_format(): void
    {
        $this->actingAs($this->adminUser);

        Livewire::test(ManageWebsites::class)
            ->set('client_id', $this->client->id)
            ->set('site_name', 'Bad URL Site')
            ->set('url', 'not-a-valid-url')
            ->set('service_type_id', $this->serviceTypeId)
            ->set('status', 'active')
            ->call('saveWebsite')
            ->assertHasErrors(['url']);
    }

    // ─── Edit Website ────────────────────────────────────────────────────────────

    public function test_admin_can_edit_website_without_changing_password(): void
    {
        $this->actingAs($this->adminUser);

        // Create a website
        $website = Website::create([
            'client_id'       => $this->client->id,
            'site_name'       => 'Original Site',
            'url'             => 'https://original.com',
            'service_type_id' => $this->serviceTypeId,
            'status'          => 'active',
            'admin_username'  => 'original_admin',
            'admin_password'  => 'original_secret',
            'added_by'        => $this->adminUser->id,
        ]);

        $rawPasswordBefore = $website->fresh()->getRawOriginal('admin_password');

        Livewire::test(ManageWebsites::class)
            ->call('editWebsite', $website->id)
            ->assertSet('site_name', 'Original Site')
            ->assertSet('admin_password', 'original_secret') // password is now decrypted and exposed
            ->set('site_name', 'Updated Site')
            ->set('url', 'https://updated.com')
            ->call('updateWebsite')
            ->assertHasNoErrors()
            ->assertDispatched('close-modal', name: 'edit-website-modal');

        // Name updated
        $this->assertDatabaseHas('adspv_websites', [
            'id'        => $website->id,
            'site_name' => 'Updated Site',
            'edited_by' => $this->adminUser->id,
        ]);

        // Password unchanged
        $this->assertEquals('original_secret', $website->fresh()->admin_password);
    }

    public function test_admin_can_change_password_on_edit(): void
    {
        $this->actingAs($this->adminUser);

        $website = Website::create([
            'client_id'       => $this->client->id,
            'site_name'       => 'Site With Password',
            'url'             => 'https://example.com',
            'service_type_id' => $this->serviceTypeId,
            'status'          => 'active',
            'admin_password'  => 'old_password',
            'added_by'        => $this->adminUser->id,
        ]);

        Livewire::test(ManageWebsites::class)
            ->call('editWebsite', $website->id)
            ->set('admin_password', 'new_secret_password')
            ->call('updateWebsite')
            ->assertHasNoErrors();

        // Verify new password is encrypted and decrypts correctly
        $fresh = $website->fresh();
        $this->assertNotEquals('new_secret_password', $fresh->getRawOriginal('admin_password'));
        $this->assertEquals('new_secret_password', Crypt::decryptString($fresh->getRawOriginal('admin_password')));
    }
}
