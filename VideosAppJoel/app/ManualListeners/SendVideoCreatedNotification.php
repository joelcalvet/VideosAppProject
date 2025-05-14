<?php

namespace App\ManualListeners;

use App\Events\VideoCreated;
use App\Models\User;
use App\Notifications\VideoCreatedNotification;
use Illuminate\Support\Facades\Notification;

class SendVideoCreatedNotification
{
    public function handle(VideoCreated $event)
    {
        // Obtenir els usuaris amb el rol de super-admin
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'super-admin');
        })->get();

        // Obtenir els usuaris sense el rol de super-admin
        $nonAdmins = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'super-admin');
        })->get();

        // Enviar la notificació a tots els admins, incloent el canal 'mail' i 'database'
        foreach ($admins as $admin) {
            $admin->notify(new VideoCreatedNotification($event->video, ['mail', 'database', 'broadcast']));
        }

        // Enviar la notificació als usuaris regulars, només al canal 'database' i 'broadcast'
        foreach ($nonAdmins as $nonAdmin) {
            $nonAdmin->notify(new VideoCreatedNotification($event->video, ['database', 'broadcast']));
        }
    }

}
