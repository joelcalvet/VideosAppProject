<?php

namespace App\Helpers;

use App\Models\Serie;

class SeriesHelper
{
    public static function create_series()
    {
        Serie::firstOrCreate([
            'title' => 'Sèrie 1',
            'description' => 'Descripció de la sèrie 1',
            'user_name' => 'Admin',
            'published_at' => now(),
        ]);

        Serie::firstOrCreate([
            'title' => 'Sèrie 2',
            'description' => 'Descripció de la sèrie 2',
            'user_name' => 'Admin',
            'published_at' => now(),
        ]);

        Serie::firstOrCreate([
            'title' => 'Sèrie 3',
            'description' => 'Descripció de la sèrie 3',
            'user_name' => 'Admin',
            'published_at' => now(),
        ]);
    }
}
