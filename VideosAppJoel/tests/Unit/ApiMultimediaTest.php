<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Helpers\MultimediaHelper;
use App\Models\User;
use App\Models\Multimedia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ApiMultimediaTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function authenticated_user_can_store_multimedia()
    {
        // Arrange: Crear un usuari manualment
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user, 'sanctum');

        // Arrange: Simular un arxiu amb Storage fake
        Storage::fake('public');
        $file = UploadedFile::fake()->create('video.mp4', 1000);

        // Act: Fer la petició POST
        $response = $this->postJson('/api/multimedia', [
            'file' => $file,
            'type' => 'video',
            'title' => 'Test Video',
            'description' => 'A test video',
        ]);

        // Assert: Comprovar resultats
        $response->assertStatus(201);
        $this->assertDatabaseHas('multimedia', [
            'user_id' => $user->id,
            'type' => 'video',
            'title' => 'Test Video',
            'description' => 'A test video',
        ]);
        Storage::disk('public')->assertExists($response->json('path'));
    }

    #[Test]
    public function unauthenticated_user_cannot_store_multimedia()
    {
        // Arrange: Simular un arxiu
        Storage::fake('public');
        $file = UploadedFile::fake()->create('video.mp4', 1000);

        // Act: Fer la petició sense autenticació
        $response = $this->postJson('/api/multimedia', [
            'file' => $file,
            'type' => 'video',
        ]);

        // Assert: Comprovar que retorna 401 (Unauthorized)
        $response->assertStatus(401);
    }

    #[Test]
    public function authenticated_user_can_list_multimedia()
    {
        // Arrange: Crear un usuari i multimèdia
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user, 'sanctum');

        MultimediaHelper::createCustomMultimedia(
            path: 'multimedia/video1.mp4',
            type: 'video',
            userId: $user->id,
            title: 'Video 1'
        );
        MultimediaHelper::createCustomMultimedia(
            path: 'multimedia/photo1.jpg',
            type: 'photo',
            userId: $user->id,
            title: 'Photo 1'
        );

        // Act: Fer la petició GET
        $response = $this->getJson('/api/multimedia');

        // Assert: Comprovar resultats
        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }

    #[Test]
    public function authenticated_user_can_list_own_multimedia()
    {
        // Arrange: Crear dos usuaris i multimèdia
        $user1 = User::create([
            'name' => 'Test User 1',
            'email' => 'test1@example.com',
            'password' => bcrypt('password'),
        ]);
        $user2 = User::create([
            'name' => 'Test User 2',
            'email' => 'test2@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user1, 'sanctum');

        MultimediaHelper::createCustomMultimedia(
            path: 'multimedia/video1.mp4',
            type: 'video',
            userId: $user1->id
        );
        MultimediaHelper::createCustomMultimedia(
            path: 'multimedia/photo1.jpg',
            type: 'photo',
            userId: $user2->id
        );

        // Act: Fer la petició GET
        $response = $this->getJson('/api/user/multimedia');

        // Assert: Comprovar que només veu el seu propi multimèdia
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['path' => 'multimedia/video1.mp4']);
    }

    #[Test]
    public function authenticated_user_can_destroy_multimedia()
    {
        // Arrange: Crear un usuari i multimèdia
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user, 'sanctum');

        Storage::fake('public');
        $multimedia = MultimediaHelper::createCustomMultimedia(
            path: 'multimedia/test.mp4',
            type: 'video',
            userId: $user->id
        );

        // Act: Fer la petició DELETE
        $response = $this->deleteJson("/api/multimedia/{$multimedia->id}");

        // Assert: Comprovar resultats
        $response->assertStatus(204);
        $this->assertDatabaseMissing('multimedia', ['id' => $multimedia->id]);
        Storage::disk('public')->assertMissing($multimedia->path);
    }
}
