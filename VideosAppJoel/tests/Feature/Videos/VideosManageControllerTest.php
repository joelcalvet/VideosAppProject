<?php

namespace Tests\Feature\Videos;

use App\Helpers\VideoHelper;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\PermissionSeeder;
use App\Helpers\UserHelper;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;

class VideosManageControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);
    }

    // Funcions auxiliars
    protected function loginAsVideoManager()
    {
        $user = UserHelper::create_video_manager_user();
        Permission::firstOrCreate(['name' => 'manage videos']);
        $user->givePermissionTo('manage videos');
        $this->actingAs($user);
        return $user;
    }

    protected function loginAsSuperAdmin()
    {
        $user = UserHelper::create_superadmin_user();
        Permission::firstOrCreate(['name' => 'manage videos']);
        $user->givePermissionTo('manage videos');
        $this->actingAs($user);
        return $user;
    }

    protected function loginAsRegularUser()
    {
        $user = UserHelper::create_regular_user();
        $this->actingAs($user);
        return $user;
    }

    // Prova que un usuari amb permisos pot veure la pàgina de gestió de vídeos
    public function test_user_with_permissions_can_manage_videos()
    {
        $user = $this->loginAsVideoManager();
        $this->assertTrue($user->hasPermissionTo('manage videos'));

        // Crear un vídeo utilitzant el model directament
        Video::create([
            'title' => 'Test Video 1',
            'description' => 'This is a test video description.',
            'url' => 'https://www.youtube.com/watch?v=abc123',
            'published_at' => Carbon::now(),
        ]);

        Video::create([
            'title' => 'Test Video 2',
            'description' => 'Another test video description.',
            'url' => 'https://www.youtube.com/watch?v=def456',
            'published_at' => Carbon::now(),
        ]);

        Video::create([
            'title' => 'Test Video 3',
            'description' => 'Yet another test video description.',
            'url' => 'https://www.youtube.com/watch?v=ghi789',
            'published_at' => Carbon::now(),
        ]);

        $this->assertDatabaseCount('videos', 3);

        $videos = Video::all();
        foreach ($videos as $video) {
            $this->assertDatabaseHas('videos', ['id' => $video->id]);
            $this->assertEquals('Tests\\Feature\\Videos\\VideosTest', $video->testedBy());
        }
    }

    // Prova que un usuari regular no pot gestionar vídeos
    public function test_regular_users_cannot_manage_videos()
    {
        $user = $this->loginAsRegularUser();
        $this->assertFalse($user->hasPermissionTo('manage videos'));

        $response = $this->actingAs($user)->get('/videos/manage');
        $response->assertStatus(403); // 403 Forbidden
    }

    // Prova que un usuari convidat no pot gestionar vídeos
    public function test_guest_users_cannot_manage_videos()
    {
        $this->assertGuest();
        $response = $this->get('/videos/manage');
        $response->assertRedirect('/login'); // Els convidats són redirigits al login
    }

    // Prova que un superadmin pot gestionar vídeos
    public function test_superadmins_can_manage_videos()
    {
        $user = $this->loginAsSuperAdmin();
        $this->assertTrue($user->hasPermissionTo('manage videos'));

        $response = $this->actingAs($user)->get('/videos/manage');
        $response->assertStatus(200);
    }

    // Prova que un usuari amb permisos pot accedir a la ruta de gestió de vídeos
    public function test_user_with_permissions_can_access_videos_manage_route()
    {
        $user = $this->loginAsVideoManager();
        $response = $this->actingAs($user)->get('/videos/manage');
        $response->assertStatus(200);
    }

    // Prova que un usuari amb permisos pot veure la pàgina per afegir vídeos
    public function test_user_with_permissions_can_see_add_videos()
    {
        $user = $this->loginAsVideoManager();
        $response = $this->actingAs($user)->get(route('videos.manage.create'));
        $response->assertStatus(200);
    }

    // Prova que un usuari sense permisos no pot veure la pàgina per afegir vídeos
    public function test_user_without_videos_manage_create_cannot_see_add_videos()
    {
        $user = $this->loginAsRegularUser();
        $response = $this->actingAs($user)->get(route('videos.manage.create'));
        $response->assertStatus(403); // 403 Forbidden
    }

    // Prova que un usuari amb permisos pot crear vídeos
    public function test_user_with_permissions_can_store_videos()
    {
        $user = $this->loginAsVideoManager();
        $videoData = [
            'title' => 'Test Video',
            'description' => 'This is a test video description.',
            'url' => 'https://www.youtube.com/watch?v=abc123',
            'published_at' => Carbon::now()->toDateString(),
        ];

        $response = $this->actingAs($user)->post(route('videos.manage.store'), $videoData);
        $response->assertRedirect(); // Redirigeix després de crear
        $this->assertDatabaseHas('videos', ['title' => 'Test Video']);

        $video = Video::where('title', 'Test Video')->first();
        $this->assertEquals('Tests\\Feature\\Videos\\VideosTest', $video->testedBy());
    }

    // Prova que un usuari sense permisos no pot crear vídeos
    public function test_user_without_permissions_cannot_store_videos()
    {
        $user = $this->loginAsRegularUser();
        $videoData = [
            'title' => 'Test Video',
            'description' => 'This is a test video description.',
            'url' => 'https://www.youtube.com/watch?v=abc123',
            'published_at' => Carbon::now()->toDateString(),
        ];

        $response = $this->actingAs($user)->post(route('videos.manage.store'), $videoData);
        $response->assertStatus(403); // 403 Forbidden
        $this->assertDatabaseMissing('videos', ['title' => 'Test Video']);
    }

    // Prova que un usuari amb permisos pot eliminar vídeos
    public function test_user_with_permissions_can_destroy_videos()
    {
        $user = $this->loginAsVideoManager();
        $video = Video::create([
            'title' => 'Video to Delete',
            'description' => 'This is a video to delete.',
            'url' => 'https://www.youtube.com/watch?v=xyz789',
            'published_at' => Carbon::now(),
        ]);

        $response = $this->actingAs($user)->delete(route('videos.manage.destroy', $video->id));
        $response->assertRedirect(); // Redirigeix després d'eliminar
        $this->assertDatabaseMissing('videos', ['id' => $video->id]);
    }

    // Prova que un usuari sense permisos no pot eliminar vídeos
    public function test_user_without_permissions_cannot_destroy_videos()
    {
        $user = $this->loginAsRegularUser();
        $video = Video::create([
            'title' => 'Video to Delete',
            'description' => 'This is a video to delete.',
            'url' => 'https://www.youtube.com/watch?v=xyz789',
            'published_at' => Carbon::now(),
        ]);

        $response = $this->actingAs($user)->delete(route('videos.manage.destroy', $video->id));
        $response->assertStatus(403); // 403 Forbidden
        $this->assertDatabaseHas('videos', ['id' => $video->id]);
    }

    // Prova que un usuari amb permisos pot veure la pàgina d'edició de vídeos
    public function test_user_with_permissions_can_see_edit_videos()
    {
        $user = $this->loginAsVideoManager();
        $video = Video::create([
            'title' => 'Video to Edit',
            'description' => 'This is a video to edit.',
            'url' => 'https://www.youtube.com/watch?v=edit123',
            'published_at' => Carbon::now(),
        ]);

        $response = $this->actingAs($user)->get(route('videos.manage.edit', $video->id));
        $response->assertStatus(200);
    }

    // Prova que un usuari sense permisos no pot veure la pàgina d'edició de vídeos
    public function test_user_without_permissions_cannot_see_edit_videos()
    {
        $user = $this->loginAsRegularUser();
        $video = Video::create([
            'title' => 'Video to Edit',
            'description' => 'This is a video to edit.',
            'url' => 'https://www.youtube.com/watch?v=edit123',
            'published_at' => Carbon::now(),
        ]);

        $response = $this->actingAs($user)->get(route('videos.manage.edit', $video->id));
        $response->assertStatus(403); // 403 Forbidden
    }

    // Prova que un usuari amb permisos pot actualitzar vídeos
    public function test_user_with_permissions_can_update_videos()
    {
        $user = $this->loginAsVideoManager();
        $video = Video::create([
            'title' => 'Original Title',
            'description' => 'Original description.',
            'url' => 'https://www.youtube.com/watch?v=orig123',
            'published_at' => Carbon::now(),
        ]);

        $updatedData = [
            'title' => 'Updated Title',
            'description' => $video->description,
            'url' => $video->url,
            'published_at' => $video->published_at->toDateString(),
        ];

        $response = $this->actingAs($user)->put(route('videos.manage.update', $video->id), $updatedData);
        $response->assertRedirect(); // Redirigeix després d'actualitzar
        $this->assertDatabaseHas('videos', ['id' => $video->id, 'title' => 'Updated Title']);
    }

    public function test_user_without_permissions_cannot_update_videos()
    {
        $user = $this->loginAsRegularUser();
        $video = Video::create([
            'title' => 'Original Title',
            'description' => 'Original description.',
            'url' => 'https://www.youtube.com/watch?v=orig123',
            'published_at' => Carbon::now(),
        ]);

        $updatedData = [
            'title' => 'Updated Title',
            'description' => $video->description,
            'url' => $video->url,
            'published_at' => $video->published_at->toDateString(),
        ];

        $response = $this->actingAs($user)->put(route('videos.manage.update', $video->id), $updatedData);
        $response->assertStatus(403); // 403 Forbidden
        $this->assertDatabaseHas('videos', ['id' => $video->id, 'title' => 'Original Title']);
    }
}
