<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\TipoMuestra;
use App\Models\Expediente;
use App\Models\Informe;
use App\Models\Imagen;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
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

        // 1) TIPOS FIJOS (catalogo) -> sin duplicados de prefijo
        TipoMuestra::query()->delete(); // por si ya hay algo

        TipoMuestra::insert([
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

        // 2) EXPEDIENTES (si los estás usando)
        Expediente::factory()->count(10)->create();

        // 3) INFORMES
        //    Ojo: tu InformeFactory debe usar 'prefijo' (no 'codigo')
        Informe::factory()->count(20)->create();

        // 4) IMÁGENES (colgando de informes)
        //    Creamos algunas fotos por fases y para algunos informes las 4 obligatorias del microscopio
        $informes = Informe::all();

        foreach ($informes as $informe) {

            // Opcionales por fase (0-1)
            Imagen::factory()->count(rand(0,1))->create([
                'informe_id' => $informe->id,
                'fase' => 'recepcion',
                'zoom' => null,
                'obligatoria' => false,
            ]);

            Imagen::factory()->count(rand(0,1))->create([
                'informe_id' => $informe->id,
                'fase' => 'procesamiento',
                'zoom' => null,
                'obligatoria' => false,
            ]);

            // Microscopio: En el 70% de los casos creamos las 4 obligatorias
            if (rand(1, 100) <= 70) {
                foreach (['x4', 'x10', 'x40', 'x100'] as $z) {
                    Imagen::factory()->create([
                        'informe_id' => $informe->id,
                        'fase' => 'microscopio',
                        'zoom' => $z,
                        'obligatoria' => true,
                        'descripcion' => "Vista microscópica $z"
                    ]);
                }
            }
        }
    }
}
