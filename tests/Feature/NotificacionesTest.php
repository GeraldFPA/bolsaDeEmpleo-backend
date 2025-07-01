<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Notificacion;
class NotificacionesTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_obtiene_solo_notificaciones_no_leidas()
    {
        $user = User::factory()->create();

        Notificacion::factory()->count(2)->create([
            'user_id' => $user->id,
            'leida' => false,
        ]);

        Notificacion::factory()->create([
            'user_id' => $user->id,
            'leida' => true,
        ]);

        $response = $this->actingAs($user)->getJson('/api/notificaciones/noleidas');

        $response->assertStatus(200);
        $response->assertJsonCount(2); 
        $response->assertJsonFragment(['leida' => 0]);
    }

    public function test_marca_notificaciones_como_leidas()
    {
        $user = User::factory()->create();

        Notificacion::factory()->count(3)->create([
            'user_id' => $user->id,
            'leida' => false,
        ]);

        $response = $this->actingAs($user)->postJson('/api/notificaciones/marcar-leidas');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Notificaciones marcadas como leídas']);

        $this->assertDatabaseMissing('notificacions', [
            'user_id' => $user->id,
            'leida' => false,
        ]);
    }
}
