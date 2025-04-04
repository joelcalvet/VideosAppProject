<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'series_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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
