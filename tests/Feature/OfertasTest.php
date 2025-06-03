<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Oferta;
use App\Models\Postulacion;
use Tests\TestCase;

class OfertasTest extends TestCase
{
    use RefreshDatabase;
    public function test_store(): void
    {
        $user = User::factory()->create();


        $payload = [
            'puesto' => 'Desarrollador Backend',
            'categoria' => 'Tecnología',
            'empresa' => 'TechCorp',
            'horario' => 'Tiempo completo',
            'sueldo' => 3000,
            'contrato' => 'Indefinido',
            'descripcion' => 'Responsable del desarrollo de APIs en Laravel',
        ];

        $response = $this->actingAs($user)->postJson('/api/oferta', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['puesto' => 'Desarrollador Backend']);

        $this->assertDatabaseHas('ofertas', [
            'user_id' => $user->id,
            'puesto' => 'Desarrollador Backend',
        ]);
    }
    public function test_index()
    {
        $user = User::factory()->create();

        Oferta::factory()->create([
            'user_id' => $user->id,
            'estado' => 'activo',
            'puesto' => 'Tester QA'
        ]);

        Oferta::factory()->create([
            'user_id' => $user->id,
            'estado' => 'inactivo',
            'puesto' => 'Administrador'
        ]);

        $response = $this->actingAs($user)->getJson('/api/ofertas');

        $response->assertStatus(200)
            ->assertJsonFragment(['puesto' => 'Tester QA'])
            ->assertJsonMissing(['puesto' => 'Administrador']);

    }
}
