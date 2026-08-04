<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Modules\Core\Authentication\Models\Role;
use App\Modules\Core\Authentication\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private Role $adminRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::create([
            'name' => 'Administrator',
            'slug' => 'admin',
        ]);
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/adminadspnl/login');

        $response
            ->assertOk()
            ->assertSeeVolt('pages.admin-auth.login');
    }

    public function test_admins_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();
        Admin::create([
            'user_id' => $user->id,
            'role_id' => $this->adminRole->id,
            'is_active' => true,
        ]);

        $component = Volt::test('pages.admin-auth.login')
            ->set('form.email', $user->email)
            ->set('form.password', 'password');

        $component->call('login');

        $component
            ->assertHasNoErrors()
            ->assertRedirect(route('admin.dashboard', absolute: false));

        $this->assertAuthenticated();
    }

    public function test_non_admins_cannot_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $component = Volt::test('pages.admin-auth.login')
            ->set('form.email', $user->email)
            ->set('form.password', 'password');

        $component->call('login');

        $component
            ->assertHasErrors(['form.email'])
            ->assertNoRedirect();

        $this->assertGuest();
    }

    public function test_admins_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();
        Admin::create([
            'user_id' => $user->id,
            'role_id' => $this->adminRole->id,
            'is_active' => true,
        ]);

        $component = Volt::test('pages.admin-auth.login')
            ->set('form.email', $user->email)
            ->set('form.password', 'wrong-password');

        $component->call('login');

        $component
            ->assertHasErrors()
            ->assertNoRedirect();

        $this->assertGuest();
    }

    public function test_admins_can_logout(): void
    {
        $user = User::factory()->create();
        Admin::create([
            'user_id' => $user->id,
            'role_id' => $this->adminRole->id,
            'is_active' => true,
        ]);

        $this->actingAs($user);

        $component = Volt::test('layout.navigation-logout');

        $component->call('logout');

        $component
            ->assertHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
    }

    public function test_client_login_screen_blocks_admins(): void
    {
        $user = User::factory()->create();
        Admin::create([
            'user_id' => $user->id,
            'role_id' => $this->adminRole->id,
            'is_active' => true,
        ]);

        $component = Volt::test('pages.auth.login')
            ->set('form.email', $user->email)
            ->set('form.password', 'password');

        $component->call('login');

        $component
            ->assertHasErrors(['form.email'])
            ->assertNoRedirect();

        $this->assertGuest();
    }

    public function test_client_login_screen_allows_clients(): void
    {
        $user = User::factory()->create();
        \App\Modules\CRM\Clients\Models\Client::create([
            'user_id' => $user->id,
            'company_name' => 'Test Client Corp',
        ]);

        $component = Volt::test('pages.auth.login')
            ->set('form.email', $user->email)
            ->set('form.password', 'password');

        $component->call('login');

        $component
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
    }

    public function test_admin_login_screen_blocks_clients(): void
    {
        $user = User::factory()->create();
        \App\Modules\CRM\Clients\Models\Client::create([
            'user_id' => $user->id,
            'company_name' => 'Test Client Corp',
        ]);

        $component = Volt::test('pages.admin-auth.login')
            ->set('form.email', $user->email)
            ->set('form.password', 'password');

        $component->call('login');

        $component
            ->assertHasErrors(['form.email'])
            ->assertNoRedirect();

        $this->assertGuest();
    }
}
