<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Oferta;
use App\Models\Postulacion;

class EnviarNotificacionTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_puede_aceptar_una_postulacion_y_crear_notificacion()
    {
        
        $user = User::factory()->create();

        
        $oferta = Oferta::factory()->create([
            'estado' => 'activa',
        ]);

        
        $postulante = User::factory()->create();

      
        $postulacion = Postulacion::factory()->create([
            'oferta_id' => $oferta->id,
            'user_id' => $postulante->id,
            'estado' => 'pendiente',
        ]);

        $response = $this->actingAs($user)->postJson("/api/postulaciones/aceptar/{$postulacion->id}/{$oferta->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Postulación aceptada correctamente.']);

        $this->assertDatabaseHas('postulacions', [
            'id' => $postulacion->id,
            'estado' => 'aceptada',
        ]);

        
        $this->assertDatabaseHas('ofertas', [
            'id' => $oferta->id,
            'estado' => 'inactiva',
        ]);

     
        $this->assertDatabaseHas('notificacions', [
            'user_id' => $postulante->id,
            'tipo' => 'Ha sido aceptado',
            'leida' => false,
        ]);
    }
}
