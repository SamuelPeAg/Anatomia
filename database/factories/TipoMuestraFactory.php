<?php

namespace Database\Factories;

use App\Models\TipoMuestra;
use Illuminate\Database\Eloquent\Factories\Factory;

class TipoMuestraFactory extends Factory
{
    protected $model = TipoMuestra::class;

    public function definition(): array
    {
        return [
            // Nombre del tipo de muestra
            'nombre' => $this->faker->randomElement([
                'Biopsia',
                'Biopsia veterinaria',
                'Cavidad bucal',
                'Citología vaginal',
                'Extensión sanguínea',
                'Orinas',
                'Esputo',
                'Semen',
                'Improntas',
                'Frotis',
            ]),

            // Prefijo correspondiente al tipo de muestra
            'prefijo' => $this->faker->randomElement([
                'B', 'BV', 'CB', 'CV', 'EX', 'O', 'E', 'ES', 'I', 'F'
            ]),

            // Contador para los informes
            'contador_actual' => $this->faker->numberBetween(1, 100),

            // Si este tipo requiere el campo "órgano"
            'requiere_organo' => $this->faker->boolean(),

            // Si el tipo está activo
            'activo' => $this->faker->boolean(true),
        ];
    }
}
