<?php

namespace Database\Seeders;

use App\Models\Expediente;
use App\Models\Imagenes;
use App\Models\Informe;
use App\Models\TipoMuestra;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       
        // Paso 1: Crear los tipos de muestra
        TipoMuestra::factory()->count(5)->create();

        // Paso 2: Crear los expedientes
        Expediente::factory()->count(5)->create();

        // Paso 3: Crear los informes (con relaciones correctas)
        Informe::factory()->count(10)->create();

        // Paso 4: Crear las imágenes asociadas a los informes
        Imagenes::factory()->count(5)->create();



        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
