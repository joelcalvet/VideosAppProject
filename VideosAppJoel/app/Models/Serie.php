<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Serie extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'user_name',
        'user_id',
        'user_photo_url',
        'published_at',
    ];

    public function testedBy()
    {
        return 'Tests\\Unit\\SerieTest';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function videos(): BelongsToMany
    {
        return $this->belongsToMany(Video::class, 'serie_video', 'serie_id', 'video_id');
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    public function getFormattedForHumansCreatedAtAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getCreatedAtTimestampAttribute()
    {
        return $this->created_at->timestamp;
    }
}
