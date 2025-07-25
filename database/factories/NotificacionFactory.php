<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\notificacion>
 */
class NotificacionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'tipo' => $this->faker->randomElement(['postulacion_aceptada', 'postulacion_rechazada', 'nuevo_mensaje']),
            'mensaje' => $this->faker->sentence,
            'leida' => false,
        ];
    }
}
