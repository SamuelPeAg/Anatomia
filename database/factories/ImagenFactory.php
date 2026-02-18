<?php

namespace Database\Factories;


use App\Models\Imagen;
use App\Models\Informe;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImagenFactory extends Factory
{
    protected $model = Imagen::class;

    public function definition(): array
    {
        $fase = $this->faker->randomElement(['recepcion', 'procesamiento', 'tincion', 'microscopio']);
        $esMicro = $fase === 'microscopio';

        return [
            'informe_id' => Informe::inRandomOrder()->value('id')
                ?? Informe::factory()->create()->id,

            'fase' => $fase,

            // Ruta fake para seed (en real usarás Storage::putFile / store)
            'ruta' => 'imagenes/demo/' . $this->faker->uuid() . '.jpg',

            'descripcion' => $this->faker->optional(0.7)->sentence(8),

            // Solo microscopio tiene zoom
            'zoom' => $esMicro ? $this->faker->randomElement(['x4', 'x10', 'x40', 'x100']) : null,

            // Solo microscopio puede ser obligatoria
            'obligatoria' => $esMicro ? $this->faker->boolean(40) : false,
        ];
    }

    /**
     * Estado: imagen obligatoria de microscopio con un zoom concreto (x4/x10/x40/x100).
     */
    public function microsObligatoria(string $zoom): static
    {
        return $this->state(fn () => [
            'fase' => 'microscopio',
            'zoom' => $zoom,
            'obligatoria' => true,
        ]);
    }
}
