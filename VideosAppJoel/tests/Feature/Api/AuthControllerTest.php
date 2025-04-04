<?php

namespace Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Configuració inicial per a les peticions API
        $this->withHeaders([
            'Accept' => 'application/json',
        ]);
    }

    // Helper per crear un usuari regular
    protected function createRegularUser()
    {
        return User::create([
            'name' => 'Regular User',
            'email' => 'regular@example.com',
            'password' => Hash::make('password'),
        ]);
    }

    #[Test]
    public function user_can_login_with_valid_credentials()
    {
        // Arrange: Crear un usuari
        $user = $this->createRegularUser();

        // Act: Fer la petició de login amb credencials vàlides
        $response = $this->postJson('/api/login', [
            'email' => 'regular@example.com',
            'password' => 'password',
        ]);

        // Assert: Comprovar que retorna un token amb codi 200
        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
        $this->assertNotEmpty($response->json('token'));
    }

    #[Test]
    public function user_cannot_login_with_invalid_password()
    {
        // Arrange: Crear un usuari
        $user = $this->createRegularUser();

        // Act: Fer la petició de login amb contrasenya incorrecta
        $response = $this->postJson('/api/login', [
            'email' => 'regular@example.com',
            'password' => 'wrongpassword',
        ]);

        // Assert: Comprovar error 401 i missatge
        $response->assertStatus(401);
        $response->assertJson(['message' => 'Credencials invàlides']);
    }

    #[Test]
    public function user_cannot_login_with_invalid_email()
    {
        // Arrange: Crear un usuari
        $user = $this->createRegularUser();

        // Act: Fer la petició de login amb email incorrecte
        $response = $this->postJson('/api/login', [
            'email' => 'wrong@example.com',
            'password' => 'password',
        ]);

        // Assert: Comprovar error 401 i missatge
        $response->assertStatus(401);
        $response->assertJson(['message' => 'Credencials invàlides']);
    }

    #[Test]
    public function user_can_register_with_valid_data()
    {
        // Act: Fer la petició de registre
        $response = $this->postJson('/api/register', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
        ]);

        // Assert: Comprovar que es crea l'usuari i retorna un token amb codi 201
        $response->assertStatus(201); // Canvi de 200 a 201
        $response->assertJsonStructure(['token']);
        $this->assertNotEmpty($response->json('token'));
        $this->assertDatabaseHas('users', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
        ]);
    }
}
