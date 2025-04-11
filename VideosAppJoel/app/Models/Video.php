<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Video extends Model
{
    use HasFactory;

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected $fillable = [
        'title',
        'description',
        'url',
        'published_at',
        'previous',
        'next',
        'serie_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function series(): BelongsToMany
    {
        return $this->belongsToMany(Serie::class, 'serie_video', 'serie_id', 'video_id');
    }

    public function getFormattedPublishedAtAttribute()
    {
        return $this->published_at
            ? Carbon::parse($this->published_at)->format('jS \o\f F, Y') // Exemple: "13th of January, 2025"
            : null;
    }

    public function getFormattedForHumansPublishedAtAttribute()
    {
        return $this->published_at ? Carbon::parse($this->published_at)->diffForHumans() : null;
    }

    public function getPublishedAtTimestampAttribute()
    {
        return $this->published_at ? Carbon::parse($this->published_at)->timestamp : null;
    }

    public function testedBy()
    {
        // Retorna el nom de la classe del test directament
        return 'Tests\\Feature\\Videos\\VideosTest';
    }
}
