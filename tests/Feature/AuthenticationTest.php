<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    /**
     * Test user registration with valid data.
     */
    public function test_user_can_register_with_valid_data(): void
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '+1234567890',
            'bio' => $this->faker->sentence,
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'user' => ['id', 'name', 'email', 'role'],
                         'token',
                         'token_type',
                         'expires_in'
                     ]
                 ])
                 ->assertJson([
                     'success' => true,
                     'message' => 'User registered successfully'
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
            'name' => $userData['name']
        ]);
    }

    /**
     * Test user registration with invalid data.
     */
    public function test_user_cannot_register_with_invalid_data(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123',
            'password_confirmation' => '456'
        ]);

        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'errors'
                 ])
                 ->assertJson([
                     'success' => false,
                     'message' => 'Validation failed'
                 ]);
    }

    /**
     * Test user login with valid credentials.
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'user' => ['id', 'name', 'email', 'role'],
                         'token',
                         'token_type',
                         'expires_in'
                     ]
                 ])
                 ->assertJson([
                     'success' => true,
                     'message' => 'Login successful'
                 ]);
    }

    /**
     * Test user login with invalid credentials.
     */
    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Invalid credentials'
                 ]);
    }

    /**
     * Test authenticated user can access protected routes.
     */
    public function test_authenticated_user_can_access_protected_routes(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/auth/me');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => ['id', 'name', 'email', 'role']
                 ])
                 ->assertJson([
                     'success' => true
                 ]);
    }

    /**
     * Test unauthenticated user cannot access protected routes.
     */
    public function test_unauthenticated_user_cannot_access_protected_routes(): void
    {
        $response = $this->getJson('/api/auth/me');

        $response->assertStatus(401);
    }

    /**
     * Test user can logout successfully.
     */
    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Successfully logged out'
                 ]);
    }

    /**
     * Test user can refresh token.
     */
    public function test_user_can_refresh_token(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/auth/refresh');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'token',
                         'token_type',
                         'expires_in'
                     ]
                 ])
                 ->assertJson([
                     'success' => true
                 ]);
    }
}
