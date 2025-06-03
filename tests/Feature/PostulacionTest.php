<?php

namespace Tests\Feature;

use App\Models\Postulacion;
use App\Models\User;
use App\Models\Oferta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostulacionTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_crear_postulacion(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $oferta = Oferta::factory()->create([
            'user_id' => $user->id,
        ]);

        // Simula el archivo CV
        $archivo = UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf');
        $response = $this->actingAs($user)->postJson('/api/postular', [
            'oferta_id' => $oferta->id,
            'nombre' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'telefono' => '123456789',
            'comentario' => 'Estoy interesado en la oferta.',
            'cv' => $archivo,
        ]);

        
        $response->assertStatus(201);
        // Genera el nombre del archivo como se hace en el controlador
        $storedFilename = time() . '_' . $archivo->getClientOriginalName();


        // Verifica que el archivo se haya guardado en el storage simulado
        Storage::disk('public')->assertExists('cv_postulaciones/' . $storedFilename);

        
        // Verifica que se haya creado la postulación en la base de datos
        $this->assertDatabaseHas('postulacions', [
            'nombre' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'telefono' => '123456789',
            'cv_path' => 'cv_postulaciones/' . $storedFilename,
        ]);

    }
}
