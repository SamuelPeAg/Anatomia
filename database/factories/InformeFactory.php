<?php

namespace Database\Factories;

use App\Models\Informe;
use App\Models\Tipomuestra;
use App\Models\Expediente;
use Illuminate\Database\Eloquent\Factories\Factory;

class InformeFactory extends Factory
{
    protected $model = Informe::class;

    public function definition(): array
    {
        // Asegura que exista un tipo
        $tipo = Tipomuestra::inRandomOrder()->first()
            ?? Tipomuestra::factory()->create();

        // Expediente opcional (si no quieres expediente aún, ponlo siempre null)
        $expedienteId = $this->faker->boolean(60)
            ? (Expediente::inRandomOrder()->value('id') ?? Expediente::factory()->create()->id)
            : null;

        $anio = (int) now()->format('Y'); // 2025
        $yy = now()->format('y');         // 25

        // Correlativo único para evitar colisiones en el seed
        $correlativo = $this->faker->unique()->numberBetween(1, 100000);
        
        $codigo = "{$tipo->prefijo}{$yy}{$correlativo}"; // Ej: B25120

        $estado = $this->faker->randomElement(['incompleto', 'completo', 'revisado']);

        return [
            // Identificación / control
            'expediente_id' => $expedienteId,
            'tipo_id' => $tipo->id,
            'anio' => $anio,
            'correlativo' => $correlativo,
            'codigo_identificador' => $codigo,
            'estado' => $estado,

            // Fase 1 - Recepción
            'recepcion_formato_recibido' => $this->faker->optional()->randomElement(['Fresco', 'Formol', 'Etanol 70%']),
            'recepcion_observaciones' => $this->faker->sentence(12),
            'recepcion_organo' => ($tipo->prefijo === 'B')
                ? $this->faker->randomElement(['Piel', 'Pulmón', 'Hígado', 'Colon', 'Mama'])
                : null,

            // Fase 2 - Procesamiento
            'procesamiento_tipo' => $this->faker->optional()->randomElement([
                'CITOCENTRIFUGADO',
                'EXTENSION',
                'BLOQUE_CELULAR',
                'FILTRADO',
                'OTRO',
            ]),
            'procesamiento_otro' => null,
            'procesamiento_observaciones' => $this->faker->optional()->sentence(10),

            // Fase 3 - Tinción
            'tincion_tipo' => $this->faker->optional()->randomElement([
                'Hematoxilina - Eosina (H/E)',
                'Giemsa',
                'PAS',
                'Papanicolaou (PAP)',
                'Gram',
            ]),
            'tincion_observaciones' => $this->faker->optional()->sentence(14),

            // Fase 4 - Citodiagnóstico
            'citodiagnostico' => $this->faker->optional()->paragraph(2),
        ];
    }

    /**
     * Estado incompleto típico (solo recepción).
     */
    public function soloRecepcion(): static
    {
        return $this->state(fn () => [
            'estado' => 'incompleto',
            'procesamiento_tipo' => null,
            'procesamiento_otro' => null,
            'procesamiento_observaciones' => null,
            'tincion_tipo' => null,
            'tincion_observaciones' => null,
            'citodiagnostico' => null,
        ]);
    }
}
