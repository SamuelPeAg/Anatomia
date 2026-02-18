<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tipomuestra;
use App\Models\Expediente;
use App\Models\Informe;
use App\Models\Imagen;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 0) USUARIOS POR DEFECTO
        User::updateOrCreate(
            ['email' => 'javier.ruiz@doc.medac.es'],
            [
                'name' => 'Javi Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@medac.es'],
            [
                'name' => 'Admin Medac',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@medac.es'],
            [
                'name' => 'Usuario Normal',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        // 1) TIPOS FIJOS (catalogo)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Tipomuestra::query()->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Tipomuestra::insert([
            ['nombre'=>'Biopsia',               'prefijo'=>'B',   'contador_actual'=>0, 'requiere_organo'=>1, 'descripcion'=>null, 'activo'=>1, 'created_at'=>now(), 'updated_at'=>now()],
            ['nombre'=>'Biopsia veterinaria',   'prefijo'=>'BV',  'contador_actual'=>0, 'requiere_organo'=>1, 'descripcion'=>null, 'activo'=>1, 'created_at'=>now(), 'updated_at'=>now()],
            ['nombre'=>'Cavidad bucal',         'prefijo'=>'CB',  'contador_actual'=>0, 'requiere_organo'=>0, 'descripcion'=>null, 'activo'=>1, 'created_at'=>now(), 'updated_at'=>now()],
            ['nombre'=>'Citología vaginal',     'prefijo'=>'CV',  'contador_actual'=>0, 'requiere_organo'=>0, 'descripcion'=>null, 'activo'=>1, 'created_at'=>now(), 'updated_at'=>now()],
            ['nombre'=>'Extensión sanguínea',   'prefijo'=>'EX',  'contador_actual'=>0, 'requiere_organo'=>0, 'descripcion'=>null, 'activo'=>1, 'created_at'=>now(), 'updated_at'=>now()],
            ['nombre'=>'Orinas',                'prefijo'=>'O',   'contador_actual'=>0, 'requiere_organo'=>0, 'descripcion'=>null, 'activo'=>1, 'created_at'=>now(), 'updated_at'=>now()],
            ['nombre'=>'Esputo',                'prefijo'=>'E',   'contador_actual'=>0, 'requiere_organo'=>0, 'descripcion'=>null, 'activo'=>1, 'created_at'=>now(), 'updated_at'=>now()],
            ['nombre'=>'Semen',                 'prefijo'=>'ES',  'contador_actual'=>0, 'requiere_organo'=>0, 'descripcion'=>null, 'activo'=>1, 'created_at'=>now(), 'updated_at'=>now()],
            ['nombre'=>'Improntas',             'prefijo'=>'I',   'contador_actual'=>0, 'requiere_organo'=>0, 'descripcion'=>null, 'activo'=>1, 'created_at'=>now(), 'updated_at'=>now()],
            ['nombre'=>'Frotis',                'prefijo'=>'F',   'contador_actual'=>0, 'requiere_organo'=>0, 'descripcion'=>null, 'activo'=>1, 'created_at'=>now(), 'updated_at'=>now()],
            ['nombre'=>'Otro',                  'prefijo'=>'OTRO','contador_actual'=>0, 'requiere_organo'=>0, 'descripcion'=>null, 'activo'=>1, 'created_at'=>now(), 'updated_at'=>now()],
        ]);

        // 2) SEEDING MASIVO CON FACTORÍAS (30 Pacientes con Informes e Imágenes)

        Expediente::factory()
            ->count(30)
            ->create()
            ->each(function ($expediente) {
                // Crear entre 1 y 3 informes por cada paciente
                $numInformes = rand(1, 3);
                
                Informe::factory()
                    ->count($numInformes)
                    ->create([
                        'expediente_id' => $expediente->id
                    ])
                    ->each(function ($informe) {
                        // Añadir algunas imágenes aleatorias (2-5 por informe)
                        Imagen::factory()
                            ->count(rand(2, 5))
                            ->create([
                                'informe_id' => $informe->id
                            ]);
                    });
            });

    }
}
