<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_guest_cannot_access_books(): void
    {
        $response = $this->get('/books');

        $response->assertRedirect('/login');
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'petugas@example.com',
            'password' => 'password',
            'role' => 'petugas',
        ]);

        $response = $this->post('/login', [
            'email' => 'petugas@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_wrong_password(): void
    {
        User::factory()->create([
            'email' => 'petugas@example.com',
            'password' => 'password',
        ]);

        $this->post('/login', [
            'email' => 'petugas@example.com',
            'password' => 'salah',
        ]);

        $this->assertGuest();
    }

    public function test_admin_can_access_user_management(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/users');

        $response->assertOk();
    }

    public function test_petugas_cannot_access_user_management(): void
    {
        $petugas = User::factory()->create([
            'role' => 'petugas',
        ]);

        $response = $this->actingAs($petugas)->get('/users');

        $response->assertForbidden();
    }

    public function test_petugas_can_access_category_list(): void
    {
        $petugas = User::factory()->create([
            'role' => 'petugas',
        ]);

        $response = $this->actingAs($petugas)->get('/categories');

        $response->assertOk();
    }

    public function test_petugas_cannot_create_category(): void
    {
        $petugas = User::factory()->create([
            'role' => 'petugas',
        ]);

        $response = $this->actingAs($petugas)->get('/categories/create');

        $response->assertForbidden();
    }

    public function test_admin_can_access_category_creation(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/categories/create');

        $response->assertOk();
    }
}
