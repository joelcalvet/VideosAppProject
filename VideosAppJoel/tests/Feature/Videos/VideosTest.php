<?php
namespace Tests\Feature\Videos;

use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\PermissionSeeder;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;
use App\Helpers\VideoHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VideosTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);

        // Crear un usuari per defecte
        $user = User::create([
            'name' => 'Default User',
            'email' => 'default@example.com',
            'password' => bcrypt('password'),
        ]);

        // Crear vídeos per defecte amb el user_id
        VideoHelper::createDefaultVideo(); // Assegura't que usa user_id = 1 o $user->id
    }

    #[Test]
    public function users_can_view_videos()
    {
        // Crear un usuari per associar-lo al vídeo
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $video = VideoHelper::createCustomVideo(
            title: 'Vegeta 777',
            description: 'Es el mejor youtuber',
            url: 'https://www.youtube.com/embed/oM9fUlGET-w?si=mrCctV-Ilp3OXKbI',
            userId: $user->id,
            publishedAt: Carbon::now()
        );

        // Realitzar una petició per veure el vídeo
        $response = $this->get(route('videos.show', $video->id));

        // Comprovar que la petició va tenir èxit (status 200)
        $response->assertStatus(200);

        // Comprovar que la resposta conté dades del vídeo
        $response->assertSee($video->title);
        $response->assertSee($video->description);
        $response->assertSee($video->url);
        $this->assertEquals('Tests\\Feature\\Videos\\VideosTest', $video->testedBy());
    }

    #[Test]
    public function users_cannot_view_not_existing_videos()
    {
        // Intentar veure un vídeo que no existeix
        $response = $this->get(route('videos.show', 999));

        // Comprovar que la petició retorna una resposta 404 (not found)
        $response->assertStatus(404);
    }

    #[Test]
    public function user_without_permissions_can_see_default_videos_page()
    {
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);

        $response = $this->get('/videos');
        $response->assertStatus(200);
    }

    #[Test]
    public function user_with_permissions_can_see_default_videos_page()
    {
        $user = User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => bcrypt('password'),
        ]);

        $permission = Permission::findByName('manage videos');
        $user->givePermissionTo($permission);

        $this->actingAs($user);

        $response = $this->get('/videos');
        $response->assertStatus(200);
    }

    #[Test]
    public function not_logged_users_can_see_default_videos_page()
    {
        $response = $this->get('/videos');
        $response->assertStatus(200);
    }
}
