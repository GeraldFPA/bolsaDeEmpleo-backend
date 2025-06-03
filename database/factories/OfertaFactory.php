<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Oferta;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Oferta>
 */
class OfertaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Oferta::class;
    public function definition(): array
    {
         return [
            'user_id' => User::factory(), // crea un usuario automáticamente
            'puesto' => $this->faker->jobTitle,
            'categoria' => $this->faker->randomElement(['Tecnología', 'Marketing', 'Ventas']),
            'empresa' => $this->faker->company,
            'horario' => $this->faker->randomElement(['Tiempo completo', 'Medio tiempo', 'Turno nocturno']),
            'sueldo' => $this->faker->numberBetween(1000, 5000),
            'contrato' => $this->faker->randomElement(['Indefinido', 'Temporal', 'Prácticas']),
            'estado' => $this->faker->randomElement(['activa', 'inactiva']),
            'descripcion' => $this->faker->paragraph,
        ];
    }
}
