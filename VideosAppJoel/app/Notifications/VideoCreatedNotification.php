<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VideoCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $video;
    public $channels;

    /**
     * Crea una nova instància de notificació.
     *
     * @param  mixed  $video
     * @param  array  $channels
     */
    public function __construct($video, $channels = ['database', 'broadcast'])
    {
        $this->video = $video;
        $this->channels = $channels;
    }

    /**
     * Defineix els canals pels quals s'enviarà la notificació.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $this->channels;
    }

    /**
     * Representació de la notificació per correu electrònic.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nou vídeo creat')
            ->greeting("Hola {$notifiable->name},")
            ->line("S'ha creat un nou vídeo: {$this->video->title}")
            ->action('Veure vídeo', url("/videos/{$this->video->id}"))
            ->line('Gràcies per utilitzar la nostra aplicació!');
    }

    /**
     * Representació de la notificació per a la base de dades.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'video_id' => $this->video->id,
            'title' => $this->video->title,
            'creator_name' => $this->video->user->name,
        ];
    }

    /**
     * Representació de la notificació per a broadcast.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toBroadcast($notifiable)
    {
        return [
            'video' => [
                'video_id' => $this->video->id,
                'title' => $this->video->title,
                'creator_name' => $this->video->user?->name ?? 'Desconegut',
            ]
        ];
    }
}
