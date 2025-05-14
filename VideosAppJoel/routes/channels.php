<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

Broadcast::channel('videos.admin', function (User $user) {
    return $user->hasRole('admin');
});

Broadcast::channel('videos.user.{userId}', function (User $user, $userId) {
    return (int) $user->id === (int) $userId;
});

