<?php

namespace Tests\Feature\Notificacions;

use App\Events\VideoCreated;
use App\Helpers\UserHelper;
use App\Models\User;
use App\Models\Video;
use App\Notifications\VideoCreatedNotification;
use Carbon\Carbon;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class VideoNotificationsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);
    }

    protected function loginAsSuperAdmin()
    {
        $user = UserHelper::create_superadmin_user();
        $this->actingAs($user);
        return $user;
    }

    protected function loginAsRegularUser()
    {
        $user = UserHelper::create_regular_user();
        $this->actingAs($user);
        return $user;
    }

    public function test_video_created_event_is_dispatched()
    {
        Event::fake();

        $user = $this->loginAsRegularUser();
        $video = Video::create([
            'title' => 'Test Video',
            'description' => 'This is a test video description.',
            'url' => 'https://www.youtube.com/watch?v=abc123',
            'published_at' => Carbon::now(),
            'user_id' => $user->id,
        ]);

        event(new VideoCreated($video));

        Event::assertDispatched(VideoCreated::class, function ($event) use ($video) {
            return $event->video->id === $video->id;
        });
    }

    public function test_push_notification_is_sent_when_video_is_created()
    {
        Notification::fake();
        Mail::fake();

        // Crear un super‑admin
        $admin = UserHelper::create_superadmin_user();
        $admin->assignRole('super-admin');

        // Crear el creator i un altre usuari “normal”
        $creator  = UserHelper::create_regular_user();
        $otherUser = UserHelper::create_regular_user();

        // Actuar com a creator quan creem el vídeo
        $this->actingAs($creator);

        $video = Video::create([
            'title'        => 'Test Video',
            'description'  => 'This is a test video description.',
            'url'          => 'https://www.youtube.com/watch?v=abc123',
            'published_at' => Carbon::now(),
            'user_id'      => $creator->id,
        ]);
        $video->load('user');

        // Disparar l'event
        event(new VideoCreated($video));

        // 1) L’admin rep mail + database
        Notification::assertSentTo(
            $admin,
            VideoCreatedNotification::class,
            function ($notification, $channels) use ($video) {
                return in_array('mail', $channels)
                    && in_array('database', $channels)
                    && $notification->video->id === $video->id;
            }
        );

        // 2) El creador rep només database
        Notification::assertSentTo(
            $creator,
            VideoCreatedNotification::class,
            function ($notification, $channels) use ($video) {
                return in_array('database', $channels)
                    && ! in_array('mail', $channels)
                    && $notification->video->id === $video->id;
            }
        );

        // 3) Altres usuaris “normal” (otherUser) també reben només database
        Notification::assertSentTo(
            $otherUser,
            VideoCreatedNotification::class,
            function ($notification, $channels) use ($video) {
                return in_array('database', $channels)
                    && ! in_array('mail', $channels)
                    && $notification->video->id === $video->id;
            }
        );
    }

}
