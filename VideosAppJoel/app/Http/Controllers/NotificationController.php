<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('super-admin')) {
            // Admins: totes les notificacions, agrupades per video_id
            $notifications = DatabaseNotification::query()
                ->where('notifiable_type', \App\Models\User::class)
                ->latest()
                ->get()
                ->unique(function ($notification) {
                    return $notification->data['video_id'] ?? null;
                });
        } else {
            // 1. Obtenim els IDs dels vídeos creats per aquest usuari
            $videoIds = Video::where('user_id', $user->id)->pluck('id')->toArray();

            // 2. Notificacions relacionades amb aquests vídeos
            $notifications = $user->notifications()
                ->latest()
                ->get()
                ->filter(function ($notification) use ($videoIds) {
                    return in_array($notification->data['video_id'] ?? null, $videoIds);
                });
        }

        return view('notificacions', compact('notifications'));
    }



    public function markAsRead($id)
    {
        $notification = DatabaseNotification::findOrFail($id);

        if (Gate::denies('view-notification', $notification)) {
            abort(403, 'No tens permís per marcar aquesta notificació.');
        }

        $notification->markAsRead();
        return redirect()->back()->with('success', 'Notificació marcada com a llegida.');
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'Totes les notificacions marcades com a llegides.');
    }
}


