<?php

namespace Tests\Feature\Series;

use App\Http\Controllers\SeriesManageController;
use App\Models\Serie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeriesManageControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    // Funcions d'ajuda per autenticació
    protected function loginAsVideoManager()
    {
        // Els "video managers" tenen nom "Admin" per tenir permís
        $user = User::create([
            'name' => 'Admin', // Coincideix amb SeriesHelper
            'email' => 'manager@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        return $user;
    }

    protected function loginAsSuperAdmin()
    {
        // Els "superadmins" també tenen nom "Admin" per tenir permís
        $user = User::create([
            'name' => 'Admin', // Coincideix amb SeriesHelper
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        return $user;
    }

    protected function loginAsRegularUser()
    {
        // Usuari regular amb un nom diferent a "Admin"
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        return $user;
    }

    // Tests per a la vista de creació
    public function test_user_with_permissions_can_see_add_series()
    {
        $this->loginAsVideoManager();
        $response = $this->get(route('series.manage.create'));
        $response->assertStatus(200);
        $response->assertViewIs('series.manage.create');
    }

    public function test_user_without_series_manage_create_cannot_see_add_series()
    {
        $this->loginAsRegularUser();
        $response = $this->get(route('series.manage.create'));
        $response->assertStatus(403);
    }

    // Tests per a emmagatzemar sèries
    public function test_user_with_permissions_can_store_series()
    {
        $user = $this->loginAsVideoManager();
        $response = $this->post(route('series.manage.store'), [
            'title' => 'New Series',
            'description' => 'A new series',
            'user_name' => $user->name,
        ]);

        $response->assertRedirect(route('series.manage.index'));
        $response->assertSessionHas('success', 'Sèrie creada correctament.');
        $this->assertDatabaseHas('series', ['title' => 'New Series']);
    }

    public function test_user_without_permissions_cannot_store_series()
    {
        $this->loginAsRegularUser();
        $response = $this->post(route('series.manage.store'), [
            'title' => 'New Series',
            'description' => 'A new series',
            'user_name' => 'Regular User',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('series', ['title' => 'New Series']);
    }

    // Tests per a eliminar sèries
    public function test_user_with_permissions_can_destroy_series()
    {
        $user = $this->loginAsVideoManager();
        $serie = Serie::create([
            'title' => 'Test Series',
            'description' => 'A test series',
            'user_name' => $user->name,
        ]);

        $response = $this->delete(route('series.manage.destroy', $serie->id));
        $response->assertRedirect(route('series.manage.index'));
        $response->assertSessionHas('success', 'Sèrie eliminada correctament.');
        $this->assertDatabaseMissing('series', ['id' => $serie->id]);
    }

    public function test_user_without_permissions_cannot_destroy_series()
    {
        $this->loginAsRegularUser();
        $serie = Serie::create([
            'title' => 'Test Series',
            'description' => 'A test series',
            'user_name' => 'Admin', // Simula una sèrie creada per un usuari amb permís
        ]);

        $response = $this->delete(route('series.manage.destroy', $serie->id));
        $response->assertStatus(403);
        $this->assertDatabaseHas('series', ['id' => $serie->id]);
    }

    // Tests per a la vista d'edició
    public function test_user_with_permissions_can_see_edit_series()
    {
        $user = $this->loginAsVideoManager();
        $serie = Serie::create([
            'title' => 'Test Series',
            'description' => 'A test series',
            'user_name' => $user->name,
        ]);

        $response = $this->get(route('series.manage.edit', $serie->id));
        $response->assertStatus(200);
        $response->assertViewIs('series.manage.edit');
        $response->assertViewHas('serie', $serie);
    }

    public function test_user_without_permissions_cannot_see_edit_series()
    {
        $this->loginAsRegularUser();
        $serie = Serie::create([
            'title' => 'Test Series',
            'description' => 'A test series',
            'user_name' => 'Admin',
        ]);

        $response = $this->get(route('series.manage.edit', $serie->id));
        $response->assertStatus(403);
    }

    // Tests per a actualitzar sèries
    public function test_user_with_permissions_can_update_series()
    {
        $user = $this->loginAsVideoManager();
        $serie = Serie::create([
            'title' => 'Old Series',
            'description' => 'An old series',
            'user_name' => $user->name,
        ]);

        $response = $this->put(route('series.manage.update', $serie->id), [
            'title' => 'Updated Series',
            'description' => 'An updated series',
            'user_name' => $user->name,
        ]);

        $response->assertRedirect(route('series.manage.index'));
        $response->assertSessionHas('success', 'Sèrie actualitzada correctament.');
        $this->assertDatabaseHas('series', ['title' => 'Updated Series']);
    }

    public function test_user_without_permissions_cannot_update_series()
    {
        $this->loginAsRegularUser();
        $serie = Serie::create([
            'title' => 'Old Series',
            'description' => 'An old series',
            'user_name' => 'Admin',
        ]);

        $response = $this->put(route('series.manage.update', $serie->id), [
            'title' => 'Updated Series',
            'description' => 'An updated series',
            'user_name' => 'Regular User',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('series', ['title' => 'Old Series']);
    }

    // Tests generals de gestió
    public function test_user_with_permissions_can_manage_series()
    {
        $this->loginAsVideoManager();
        $response = $this->get(route('series.manage.index'));
        $response->assertStatus(200);
        $response->assertViewIs('series.manage.index');
    }

    public function test_regular_users_cannot_manage_series()
    {
        $this->loginAsRegularUser();
        $response = $this->get(route('series.manage.index'));
        $response->assertStatus(403);
    }

    public function test_guest_users_cannot_manage_series()
    {
        $response = $this->get(route('series.manage.index'));
        $response->assertRedirect(route('login')); // Ajustat per esperar 302
    }

    public function test_videomanagers_can_manage_series()
    {
        $this->loginAsVideoManager();
        $response = $this->get(route('series.manage.index'));
        $response->assertStatus(200);
    }

    public function test_superadmins_can_manage_series()
    {
        $this->loginAsSuperAdmin();
        $response = $this->get(route('series.manage.index'));
        $response->assertStatus(200);
    }
}
