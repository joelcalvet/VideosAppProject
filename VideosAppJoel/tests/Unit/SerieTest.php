<?php

namespace Tests\Unit;

use App\Models\Serie;
use App\Models\Video;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SerieTest extends TestCase
{
    use RefreshDatabase;

    public function test_serie_have_videos()
    {
        // Arrange: Crear un usuari, una sèrie i vídeos associats
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $serie = Serie::create([
            'title' => 'Test Series',
            'description' => 'A test series',
            'user_name' => 'Test User',
            'published_at' => now(),
        ]);

        $video1 = Video::create([
            'title' => 'Video 1',
            'description' => 'First video',
            'url' => 'https://example.com/video1',
            'user_id' => $user->id,
        ]);

        $video2 = Video::create([
            'title' => 'Video 2',
            'description' => 'Second video',
            'url' => 'https://example.com/video2',
            'user_id' => $user->id,
        ]);

        // Associar els vídeos a la sèrie mitjançant la taula pivot
        $serie->videos()->attach([$video1->id, $video2->id]);

        // Act: Carregar la relació vídeos
        $serie->load('videos');

        // Assert: Comprovar que la sèrie té els vídeos
        $this->assertCount(2, $serie->videos);
        $this->assertTrue($serie->videos->contains($video1));
        $this->assertTrue($serie->videos->contains($video2));
    }
}
