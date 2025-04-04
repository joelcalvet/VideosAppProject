<?php

namespace App\Helpers;

use App\Models\Video;
use Carbon\Carbon;

class VideoHelper
{
    public static function createDefaultVideo()
    {
        Video::create([
            'title' => 'Godzilla',
            'description' => 'Llangardaix enorme que destrueix tot a la seva passada.',
            'url' => 'https://www.youtube.com/embed/guPwQO9ww20?si=NW5hp55HNaY-DRsj',
            'published_at' => Carbon::now(),
            'previous' => null,
            'next' => null,
            'series_id' => null,
            'user_id' => 1,
        ]);

        Video::create([
            'title' => 'Man Loses Temper with Printer',
            'description' => 'Persona en problemes de paciència amb una impressora.',
            'url' => 'https://www.youtube.com/embed/ZSljO3DqDDU?si=5BIruzHV9IJsXSf5',
            'published_at' => Carbon::now(),
            'previous' => null,
            'next' => null,
            'series_id' => null,
            'user_id' => 1,
        ]);

        Video::create([
            'title' => 'Orangutan Driving Golf Cart',
            'description' => 'Orangutan conduint un carret de golf amb una destresa excepcional, maginífic.',
            'url' => 'https://www.youtube.com/embed/RZ_0ImDYrPY?si=WaDRzTE0nAJyq3ym',
            'published_at' => Carbon::now(),
            'previous' => null,
            'next' => null,
            'series_id' => null,
            'user_id' => 1,
        ]);
    }


    public static function createCustomVideo($title, $description, $url, $userId,
                                             $publishedAt = null, $previous = null, $next = null, $seriesId = null)
    {
        return Video::create([
            'title' => $title,
            'description' => $description,
            'url' => $url,
            'published_at' => $publishedAt ? Carbon::parse($publishedAt) : null,
            'previous' => $previous,
            'next' => $next,
            'series_id' => $seriesId,
            'user_id' => $userId,
        ]);
    }
}
