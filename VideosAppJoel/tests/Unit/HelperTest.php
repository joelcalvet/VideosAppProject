<?php

namespace Tests\Unit;

use App\Helpers\UserHelper;
use App\Helpers\VideoHelper;
use App\Models\Video;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class HelperTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear permisos necessaris abans de cada test
        Permission::firstOrCreate(['name' => 'manage videos']);
        Permission::firstOrCreate(['name' => 'manage users']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_create_default_user_and_teacher()
    {
        // Arrange: Configuració inicial
        config(['users.default' => [
            'name' => 'Default User',
            'email' => 'user@example.com',
            'password' => 'password123', // Sense bcrypt aquí
        ]]);
        config(['users.teacher' => [
            'name' => 'Default Teacher',
            'email' => 'teacher@example.com',
            'password' => 'password123', // Sense bcrypt aquí
        ]]);

        // Act: Creació d'usuaris
        $defaultUser = UserHelper::createDefaultUser();
        $teacherUser = UserHelper::createDefaultTeacher();
        // Assert: Comprovacions
        $this->assertDatabaseHas('users', [
            'email' => 'user@example.com',
            'name' => 'Default User',
        ]);
        $this->assertTrue(password_verify('password123', $defaultUser->password));
        $this->assertNotNull($defaultUser->teams()->first());
        $this->assertTrue($defaultUser->teams()->first()->personal_team);

        $this->assertDatabaseHas('users', [
            'email' => 'teacher@example.com',
            'name' => 'Default Teacher',
        ]);
        $this->assertTrue(password_verify('password123', $teacherUser->password));
        $this->assertNotNull($teacherUser->teams()->first());
        $this->assertTrue($teacherUser->teams()->first()->personal_team);

        $this->assertDatabaseHas('teams', [
            'name' => 'Default User Team',
            'user_id' => $defaultUser->id,
            'personal_team' => true,
        ]);
        $this->assertDatabaseHas('teams', [
            'name' => 'Default Teacher Team',
            'user_id' => $teacherUser->id,
            'personal_team' => true,
        ]);
    }

    public function test_create_default_video()
    {
        // Arrange: Crear un usuari per associar-lo als vídeos
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Act: Cridar el mètode createDefaultVideo
        VideoHelper::createDefaultVideo();

        // Assert: Comprovar que s'han creat tres vídeos
        $this->assertDatabaseCount('videos', 3);

        // Comprovar que els vídeos tenen els valors esperats i el user_id correcte
        $this->assertDatabaseHas('videos', [
            'title' => 'Godzilla',
            'description' => 'Llangardaix enorme que destrueix tot a la seva passada.',
            'url' => 'https://www.youtube.com/embed/guPwQO9ww20?si=NW5hp55HNaY-DRsj',
            'user_id' => $user->id, // Afegim la comprovació del user_id
        ]);

        $this->assertDatabaseHas('videos', [
            'title' => 'Man Loses Temper with Printer',
            'description' => 'Persona en problemes de paciència amb una impressora.',
            'url' => 'https://www.youtube.com/embed/ZSljO3DqDDU?si=5BIruzHV9IJsXSf5',
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('videos', [
            'title' => 'Orangutan Driving Golf Cart',
            'description' => 'Orangutan conduint un carret de golf amb una destresa excepcional, maginífic.',
            'url' => 'https://www.youtube.com/embed/RZ_0ImDYrPY?si=WaDRzTE0nAJyq3ym',
            'user_id' => $user->id,
        ]);
    }
}

