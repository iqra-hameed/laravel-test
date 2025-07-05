<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserProfileTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    }

    /**
     * Test user can view their own profile.
     */
    public function test_user_can_view_own_profile(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/user/profile');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => ['id', 'name', 'email', 'phone', 'bio', 'avatar', 'role']
                 ])
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'id' => $user->id,
                         'email' => $user->email
                     ]
                 ]);
    }

    /**
     * Test user can update their own profile.
     */
    public function test_user_can_update_own_profile(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $updateData = [
            'name' => 'Updated Name',
            'phone' => '+1987654321',
            'bio' => 'Updated bio information',
            'avatar' => 'https://example.com/new-avatar.jpg'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson('/api/user/profile', $updateData);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Profile updated successfully'
                 ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'phone' => '+1987654321',
            'bio' => 'Updated bio information'
        ]);
    }

    /**
     * Test user can change their password.
     */
    public function test_user_can_change_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword123')
        ]);
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson('/api/user/password', [
            'current_password' => 'oldpassword123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Password updated successfully'
                 ]);

        // Verify old password no longer works
        $loginResponse = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'oldpassword123'
        ]);
        $loginResponse->assertStatus(401);

        // Verify new password works
        $newLoginResponse = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'newpassword123'
        ]);
        $newLoginResponse->assertStatus(200);
    }

    /**
     * Test admin can view all users.
     */
    public function test_admin_can_view_all_users(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $users = User::factory()->count(3)->create();
        $token = JWTAuth::fromUser($admin);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/admin/users');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'data' => [
                             '*' => ['id', 'name', 'email', 'role']
                         ],
                         'current_page',
                         'total'
                     ]
                 ])
                 ->assertJson([
                     'success' => true
                 ]);
    }

    /**
     * Test regular user cannot view all users.
     */
    public function test_regular_user_cannot_view_all_users(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/admin/users');

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Access denied. Admin privileges required.'
                 ]);
    }

    /**
     * Test admin can create new user.
     */
    public function test_admin_can_create_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $token = JWTAuth::fromUser($admin);

        $userData = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
            'phone' => '+1234567890',
            'bio' => 'New user bio'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/admin/users', $userData);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'User created successfully'
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'name' => 'New User'
        ]);
    }

    /**
     * Test admin can update any user.
     */
    public function test_admin_can_update_any_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($admin);

        $updateData = [
            'name' => 'Updated by Admin',
            'role' => 'admin'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/admin/users/{$user->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'User updated successfully'
                 ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated by Admin',
            'role' => 'admin'
        ]);
    }

    /**
     * Test admin can delete user.
     */
    public function test_admin_can_delete_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($admin);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/admin/users/{$user->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'User deleted successfully'
                 ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }

    /**
     * Test admin cannot delete themselves.
     */
    public function test_admin_cannot_delete_themselves(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $token = JWTAuth::fromUser($admin);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/admin/users/{$admin->id}");

        $response->assertStatus(400)
                 ->assertJson([
                     'success' => false,
                     'message' => 'You cannot delete your own account'
                 ]);

        $this->assertDatabaseHas('users', [
            'id' => $admin->id
        ]);
    }

    /**
     * Test regular user cannot access admin routes.
     */
    public function test_regular_user_cannot_access_admin_routes(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $token = JWTAuth::fromUser($user);

        $routes = [
            ['method' => 'get', 'url' => '/api/admin/users'],
            ['method' => 'post', 'url' => '/api/admin/users'],
            ['method' => 'put', 'url' => '/api/admin/users/1'],
            ['method' => 'delete', 'url' => '/api/admin/users/1']
        ];

        foreach ($routes as $route) {
            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->{$route['method'] . 'Json'}($route['url'], []);

            $response->assertStatus(403)
                     ->assertJson([
                         'success' => false,
                         'message' => 'Access denied. Admin privileges required.'
                     ]);
        }
    }
}
