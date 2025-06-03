<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;



class AuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_user_can_register()
    {
        $response = $this->postJson('/register', [
            'name' => 'usuario1',
            'email' => 'usuario1@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'nombre' => 'Juan Pérez',
            'phone_number' => '88881234',
            'role'=> 'company', // o 'employee'  según tu lógica
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'usuario1@example.com']);
    }
    public function test_user_can_login_and_receive_token()
    {
        // Creamos un usuario en la base de datos
        $user = User::factory()->create([
            'email' => 'usuario1@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/login', [
            'email' => 'usuario1@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'user' => ['id', 'name', 'email'],
        ]);
    }
    public function test_user_can_logout()
    {
    // 1. Crear usuario
    $user = User::factory()->create();

    // 2. Generar token manualmente 
    $token = $user->createToken('test-token')->plainTextToken;

    // 3. Verificar que el token existe en DB
    $this->assertDatabaseHas('personal_access_tokens', [
        'tokenable_id' => $user->id,
        'name' => 'test-token'
    ]);

    // 4. Hacer logout
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson('api/logout');

    // 5. Verificar eliminación
    $this->assertDatabaseMissing('personal_access_tokens', [
        'tokenable_id' => $user->id,
        'name' => 'auth_token'
    ]);

    $response->assertNoContent();
    }


}
