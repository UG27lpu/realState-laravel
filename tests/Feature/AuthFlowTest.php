<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_a_visitor_can_register_as_a_buyer(): void
    {
        $response = $this->post(route('register.store'), [
            'name'  => 'Maya Iyer',
            'email' => 'maya@example.com',
            'password' => 'Secret123',
            'password_confirmation' => 'Secret123',
            'role'  => 'user',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'maya@example.com']);

        $user = User::where('email', 'maya@example.com')->firstOrFail();
        $this->assertTrue($user->hasRole('user'));
    }

    public function test_an_agent_registration_requires_an_agency_name(): void
    {
        $response = $this->from(route('register'))->post(route('register.store'), [
            'name'  => 'Sam Patel',
            'email' => 'sam@example.com',
            'password' => 'Secret123',
            'password_confirmation' => 'Secret123',
            'role'  => 'agent',
            'agency_name' => '',
            'terms' => '1',
        ]);

        $response->assertSessionHasErrors('agency_name');
    }

    public function test_login_and_logout(): void
    {
        $user = User::factory()->buyer()->create([
            'email' => 'login@example.com',
        ]);

        $this->post(route('login.store'), [
            'email'    => 'login@example.com',
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))->assertRedirect(route('home'));
        $this->assertGuest();
    }

    public function test_admin_login_rejects_non_admin_users(): void
    {
        User::factory()->buyer()->create(['email' => 'not-admin@example.com']);

        $this->from(route('admin.login'))->post(route('admin.login.store'), [
            'email'    => 'not-admin@example.com',
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_admin_can_reach_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create(['email' => 'boss@example.com']);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    public function test_non_admin_cannot_reach_admin_dashboard(): void
    {
        $user = User::factory()->buyer()->create();

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }
}
