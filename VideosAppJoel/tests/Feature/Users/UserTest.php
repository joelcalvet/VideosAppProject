<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Permission::create(['name' => 'manage users']);
    }

    public function test_user_without_permissions_can_see_default_users_page()
    {
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)->get('/users');

        $response->assertStatus(200);
    }

    public function test_user_with_permissions_can_see_default_users_page()
    {
        $user = User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => bcrypt('password'),
        ]);
        $user->givePermissionTo('manage users');

        $response = $this->actingAs($user)->get('/users');

        $response->assertStatus(200);
    }

    public function test_not_logged_users_cannot_see_default_users_page()
    {
        $response = $this->get('/users');

        $response->assertRedirect('/login');
    }

    public function test_user_without_permissions_can_see_user_show_page()
    {
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);
        $targetUser = User::create([
            'name' => 'Target User',
            'email' => 'target@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)->get("/users/{$targetUser->id}");

        $response->assertStatus(200);
    }

    public function test_user_with_permissions_can_see_user_show_page()
    {
        $user = User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => bcrypt('password'),
        ]);
        $user->givePermissionTo('manage users');
        $targetUser = User::create([
            'name' => 'Target User',
            'email' => 'target@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)->get("/users/{$targetUser->id}");

        $response->assertStatus(200);
    }

    public function test_not_logged_users_cannot_see_user_show_page()
    {
        $user = User::create([
            'name' => 'Target User',
            'email' => 'target@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->get("/users/{$user->id}");

        $response->assertRedirect('/login');
    }
}
