<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Daftar Akun Baru');
        $response->assertSee('Nama Lengkap');
        $response->assertSee('Alamat Email');
        $response->assertSee('Password');
        $response->assertSee('Konfirmasi Password');
    }

    public function test_authenticated_user_is_redirected_from_registration_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/register');

        $response->assertRedirect(route('dashboard'));
    }

    public function test_new_user_can_register_via_web(): void
    {
        $response = $this->post('/register', [
            'name' => 'Budi Pratama',
            'username' => 'budipratama',
            'email' => 'budipratama@example.com',
            'password' => 'secretPassword123',
            'password_confirmation' => 'secretPassword123',
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'name' => 'Budi Pratama',
            'username' => 'budipratama',
            'email' => 'budipratama@example.com',
        ]);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('status');
    }

    public function test_registration_validation_fails_with_invalid_email_and_mismatched_password(): void
    {
        $response = $this->post('/register', [
            'name' => 'Budi Pratama',
            'username' => 'budi',
            'email' => 'bukan-email-valid',
            'password' => 'short',
            'password_confirmation' => 'different',
        ]);

        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }

    public function test_registration_validation_fails_with_duplicate_email(): void
    {
        User::factory()->create([
            'email' => 'duplicate@example.com',
        ]);

        $response = $this->post('/register', [
            'name' => 'New User',
            'username' => 'newuser',
            'email' => 'duplicate@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_new_user_can_register_via_api(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Ahmad Santoso',
            'username' => 'ahmadsantoso',
            'email' => 'ahmad@example.com',
            'password' => 'passwordSecret123',
            'password_confirmation' => 'passwordSecret123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'token',
                'token_type',
                'user' => [
                    'id',
                    'name',
                    'username',
                    'email',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'ahmad@example.com',
            'username' => 'ahmadsantoso',
        ]);
    }

    public function test_api_registration_fails_with_validation_errors(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123',
            'password_confirmation' => '456',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ])
            ->assertJsonStructure([
                'errors' => [
                    'name',
                    'email',
                    'password',
                ],
            ]);
    }
}
