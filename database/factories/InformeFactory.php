<?php

namespace Database\Factories;

use App\Models\Informe;
use App\Models\TipoMuestra;
use App\Models\Expediente;
use Illuminate\Database\Eloquent\Factories\Factory;

class InformeFactory extends Factory
{
    protected $model = Informe::class;

    public function definition(): array
    {
        return [
            // Relaciones
            'tipo_id' => TipoMuestra::inRandomOrder()->first()->id,
            'expediente_id' => $this->faker->boolean(60)
                ? Expediente::inRandomOrder()->first()?->id
                : null,

            // Identificación
            'codigo_identificador' => $this->faker->unique()
                ->bothify('INF-2025-###'),

            // Datos del informe
            'organo' => $this->faker->optional()->randomElement([
                'Pulmón', 'Piel', 'Colon', 'Mama'
            ]),

            'formato_recibido' => $this->faker->randomElement([
                'Fresco', 'Formol', 'Etanol 70%'
            ]),

            'descripcion_recogida' => $this->faker->sentence(10),

            'descripcion_citologica' => $this->faker->paragraph(3),

            // Estado
            'estado' => $this->faker->randomElement([
                'incompleto',
                'completo',
                'revisado'
            ]),
        ];
    }

    /**
    * Estado: incompleto
    */
    public function incompleto()
    {
        return $this->state(fn () => [
            'estado' => 'incompleto',
            'formato_recibido' => null,
            'descripcion_citologica' => null,
        ]);
    }

    /**
    * Estado: completo
    */
    public function completo()
    {
        return $this->state(fn () => [
            'estado' => 'completo',
        ]);
    }

    /**
    * Estado: revisado
    */
    public function revisado()
    {
        return $this->state(fn () => [
            'estado' => 'revisado',
        ]);
    }


}
