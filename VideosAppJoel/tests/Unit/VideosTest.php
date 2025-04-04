<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Helpers\VideoHelper;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VideosTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function can_get_formatted_published_at_date()
    {
        // Crear un usuari per associar-lo al vídeo
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Crear un vídeo amb una data específica utilitzant Carbon
        $publishedAt = Carbon::create(2025, 1, 13, 12, 0, 0);
        $video = VideoHelper::createCustomVideo(
            title: 'El Rubius OMG',
            description: 'OMG el Rubius',
            url: 'https://www.youtube.com/embed/NOtKSK2k2OU?si=JbaJUZ43Sa1ChHr7',
            userId: $user->id,
            publishedAt: $publishedAt // Afegim el paràmetre userId
        );

        // Assert que retorna la data en el format esperat
        $this->assertEquals($publishedAt->format('jS \o\f F, Y'), $video->formatted_published_at);

        // Assert que retorna la data relativa
        $this->assertEquals(
            round($publishedAt->diffInWeeks(Carbon::now()), 2),
            round($video->published_at->diffInWeeks(Carbon::now()), 2)
        );

        // Assert que retorna el Unix timestamp
        $this->assertEquals($publishedAt->timestamp, $video->published_at_timestamp);
    }

    #[Test]
    public function can_get_formatted_published_at_date_when_not_published()
    {
        // Crear un usuari per associar-lo al vídeo
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test2@example.com', // Email diferent per evitar duplicats
            'password' => bcrypt('password'),
        ]);

        // Crear un vídeo sense data de publicació
        $video = VideoHelper::createCustomVideo(
            title: 'Test Video',
            description: 'Test description',
            url: 'https://example.com',
            userId: $user->id,
            publishedAt: null // Afegim el paràmetre userId
        );

        // Assert que els accessors retornen null
        $this->assertNull($video->formatted_published_at);
        $this->assertNull($video->formatted_for_humans_published_at);
        $this->assertNull($video->published_at_timestamp);
    }
}
