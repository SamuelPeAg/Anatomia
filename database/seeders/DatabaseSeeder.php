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
        // 0) USUARIO POR DEFECTO
        User::updateOrCreate(
            ['email' => 'admin@medac.es'],
            [
                'name' => 'admin',
                'password' => Hash::make('admin'),
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

            // Opcionales por fase (0-2)
            Imagen::factory()->count(rand(0,2))->create([
                'informe_id' => $informe->id,
                'fase' => 'recepcion',
                'zoom' => null,
                'obligatoria' => false,
            ]);

            Imagen::factory()->count(rand(0,2))->create([
                'informe_id' => $informe->id,
                'fase' => 'procesamiento',
                'zoom' => null,
                'obligatoria' => false,
            ]);

            Imagen::factory()->count(rand(0,2))->create([
                'informe_id' => $informe->id,
                'fase' => 'tincion',
                'zoom' => null,
                'obligatoria' => false,
            ]);

            // En algunos informes, metemos las 4 obligatorias del microscopio
            if (rand(0, 1) === 1) {
                Imagen::factory()->microsObligatoria('x4')->create(['informe_id' => $informe->id]);
                Imagen::factory()->microsObligatoria('x10')->create(['informe_id' => $informe->id]);
                Imagen::factory()->microsObligatoria('x40')->create(['informe_id' => $informe->id]);
                Imagen::factory()->microsObligatoria('x100')->create(['informe_id' => $informe->id]);
            } else {
                // Si no, alguna microscópica suelta
                Imagen::factory()->count(rand(0,2))->create([
                    'informe_id' => $informe->id,
                    'fase' => 'microscopio',
                ]);
            }
        }
    }
}
