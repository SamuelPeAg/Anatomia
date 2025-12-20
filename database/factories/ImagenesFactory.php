<?php

namespace Database\Factories;

use App\Models\Imagenes;
use App\Models\Informe;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImagenesFactory extends Factory
{
    protected $model = Imagenes::class;

    public function definition(): array
    {
        return [
            // Relación con un informe
            'informe_id' => Informe::inRandomOrder()->first()->id,

            // Ruta de la imagen
            'ruta' => $this->faker->imageUrl(640, 480, 'animals', true, 'cats'),

            // Tipo de imagen: puede ser 'general' o 'microscopica'
            'tipo_imagen' => $this->faker->randomElement(['general', 'microscopica']),

            // Zoom (solo para microscópicas)
            'zoom' => $this->faker->randomElement(['x4', 'x10', 'x40', 'x100']),

            // Descripción de la imagen (opcional)
            'descripcion' => $this->faker->optional()->sentence(),
        ];
    }
}
