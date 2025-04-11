<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class UsersManageControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear permisos si no existeixen
        Permission::firstOrCreate(['name' => 'manage users']);
        Permission::firstOrCreate(['name' => 'manage videos']);
    }

    // Helpers per loguejar diferents tipus d’usuaris
    protected function loginAsVideoManager()
    {
        $user = User::create([
            'name' => 'Video Manager',
            'email' => 'video@example.com',
            'password' => bcrypt('password'),
        ]);
        $user->givePermissionTo('manage videos');
        return $user; // Retornem l’usuari sense autenticar-lo aquí
    }

    protected function loginAsSuperAdmin()
    {
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password'),
            'super_admin' => true,
        ]);
        $user->givePermissionTo('manage users');
        return $user; // Retornem l’usuari sense autenticar-lo aquí
    }

    protected function loginAsRegularUser()
    {
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'regular@example.com',
            'password' => bcrypt('password'),
        ]);
        return $user; // Retornem l’usuari sense autenticar-lo aquí
    }

    public function test_user_with_permissions_can_see_add_users()
    {
        $user = $this->loginAsSuperAdmin();
        $response = $this->actingAs($user)->get('users/manage/create');

        $response->assertStatus(200);
    }

    public function test_user_without_users_manage_create_cannot_see_add_users()
    {
        $user = $this->loginAsRegularUser();
        $response = $this->actingAs($user)->get('users/manage/create');

        $response->assertStatus(403);
    }

    public function test_user_with_permissions_can_store_users()
    {
        $user = $this->loginAsSuperAdmin();
        $response = $this->actingAs($user)->post('users/manage', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'permissions' => ['manage users'],
        ]);

        $response->assertRedirect(route('users.manage.index'));
        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
        $newUser = User::where('email', 'newuser@example.com')->first();
        $this->assertDatabaseHas('teams', ['user_id' => $newUser->id, 'name' => 'New User Team', 'personal_team' => true]);
        $this->assertTrue($newUser->hasPermissionTo('manage users'));
    }

    public function test_user_without_permissions_cannot_store_users()
    {
        $user = $this->loginAsRegularUser();
        $response = $this->actingAs($user)->post('users/manage', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('users', ['email' => 'newuser@example.com']);
    }

    public function test_user_with_permissions_can_destroy_users()
    {
        $user = $this->loginAsSuperAdmin();
        $targetUser = User::create([
            'name' => 'Target User',
            'email' => 'target@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)->delete("users/manage/{$targetUser->id}");

        $response->assertRedirect(route('users.manage.index'));
        $this->assertDatabaseMissing('users', ['id' => $targetUser->id]);
    }

    public function test_user_without_permissions_cannot_destroy_users()
    {
        $user = $this->loginAsRegularUser();
        $targetUser = User::create([
            'name' => 'Target User',
            'email' => 'target@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)->delete("users/manage/{$targetUser->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('users', ['id' => $targetUser->id]);
    }

    public function test_user_with_permissions_can_see_edit_users()
    {
        $user = $this->loginAsSuperAdmin();
        $targetUser = User::create([
            'name' => 'Target User',
            'email' => 'target@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)->get("users/manage/{$targetUser->id}/edit");

        $response->assertStatus(200);
    }

    public function test_user_without_permissions_cannot_see_edit_users()
    {
        $user = $this->loginAsRegularUser();
        $targetUser = User::create([
            'name' => 'Target User',
            'email' => 'target@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)->get("users/manage/{$targetUser->id}/edit");

        $response->assertStatus(403);
    }

    public function test_user_with_permissions_can_update_users()
    {
        $user = $this->loginAsSuperAdmin();
        $targetUser = User::create([
            'name' => 'Target User',
            'email' => 'target@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)->put("users/manage/{$targetUser->id}", [
            'name' => 'Updated Name',
            'email' => 'target@example.com',
            'permissions' => ['manage videos'],
        ]);

        $response->assertRedirect(route('users.manage.index'));
        $this->assertDatabaseHas('users', ['id' => $targetUser->id, 'name' => 'Updated Name']);
        $this->assertTrue($targetUser->fresh()->hasPermissionTo('manage videos'));
    }

    public function test_user_without_permissions_cannot_update_users()
    {
        $user = $this->loginAsRegularUser();
        $targetUser = User::create([
            'name' => 'Target User',
            'email' => 'target@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)->put("users/manage/{$targetUser->id}", [
            'name' => 'Updated Name',
            'email' => 'target@example.com',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('users', ['name' => 'Updated Name']);
    }

    public function test_user_with_permissions_can_manage_users()
    {
        $user = $this->loginAsSuperAdmin();
        $response = $this->actingAs($user)->get('users/manage');

        $response->assertStatus(200);
    }

    public function test_regular_users_cannot_manage_users()
    {
        $user = $this->loginAsRegularUser();
        $response = $this->actingAs($user)->get('users/manage');

        $response->assertStatus(403);
    }

    public function test_guest_users_cannot_manage_users()
    {
        $response = $this->get('users/manage');

        $response->assertRedirect('/login');
    }

    public function test_superadmins_can_manage_users()
    {
        $user = $this->loginAsSuperAdmin();
        $response = $this->actingAs($user)->get('users/manage');

        $response->assertStatus(200);
    }
}
