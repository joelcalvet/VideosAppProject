<?php

namespace App\Helpers;

use App\Models\Multimedia;

class MultimediaHelper
{
    public static function createCustomMultimedia(
        string $path,
        string $type,
        int $userId,
        ?string $title = null,
        ?string $description = null
    ): Multimedia {
        return Multimedia::create([
            'path' => $path,
            'type' => $type,
            'user_id' => $userId,
            'title' => $title,
            'description' => $description,
        ]);
    }
}
