<?php

namespace App\Events;

use App\Models\Video;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VideoCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithBroadcasting, SerializesModels;

    public $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Defineix els canals on s'emet l'event.
     *
     * @return array
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('videos.user.' . $this->video->creator_id),
            new PrivateChannel('videos.admin'),
        ];
    }

    /**
     * Defineix el nom de l'event per broadcast.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'VideoCreated';
    }
}
