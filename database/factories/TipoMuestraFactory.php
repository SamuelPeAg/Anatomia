<?php

namespace Database\Factories;

use App\Models\Tipomuestra;
use Illuminate\Database\Eloquent\Factories\Factory;

class TipomuestraFactory extends Factory
{
    protected $model = Tipomuestra::class;

    public function definition(): array
    {
        $tipos = [
            ['nombre' => 'Biopsia',               'prefijo' => 'B',  'requiere_organo' => true],
            ['nombre' => 'Biopsia veterinaria',   'prefijo' => 'BV', 'requiere_organo' => true],
            ['nombre' => 'Cavidad bucal',         'prefijo' => 'CB', 'requiere_organo' => false],
            ['nombre' => 'Citología vaginal',     'prefijo' => 'CV', 'requiere_organo' => false],
            ['nombre' => 'Extensión sanguínea',   'prefijo' => 'EX', 'requiere_organo' => false],
            ['nombre' => 'Orinas',                'prefijo' => 'O',  'requiere_organo' => false],
            ['nombre' => 'Esputo',                'prefijo' => 'E',  'requiere_organo' => false],
            ['nombre' => 'Semen',                 'prefijo' => 'ES', 'requiere_organo' => false],
            ['nombre' => 'Improntas',             'prefijo' => 'I',  'requiere_organo' => false],
            ['nombre' => 'Frotis',                'prefijo' => 'F',  'requiere_organo' => false],
        ];

        $t = $this->faker->randomElement($tipos);

        return [
            'nombre' => $t['nombre'],
            'prefijo' => $t['prefijo'],

            // Mejor empezar en 0 para que el primero sea 1 (más realista)
            // Si prefieres 1..100 como antes, cámbialo.
            'contador_actual' => 0,

            // Coherente con el tipo elegido
            'requiere_organo' => $t['requiere_organo'],

            // Casi siempre activo
            'activo' => $this->faker->boolean(95),
        ];
    }
}
