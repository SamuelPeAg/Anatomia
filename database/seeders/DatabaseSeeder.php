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
        Tipomuestra::query()->delete();

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

        // 2) SEEDING DE DATOS DE PRUEBA (Expedientes e Informes)
        // Solo si no hay datos ya para no duplicar demasiado en cada seed (opcional)
        if (Informe::count() < 5) {
            $pacientes = [
                ['nombre' => 'Juan Pérez', 'correo' => 'juan@ejemplo.com'],
                ['nombre' => 'María García', 'correo' => 'maria@ejemplo.com'],
                ['nombre' => 'Carlos López', 'correo' => 'carlos@ejemplo.com'],
                ['nombre' => 'Ana Martínez', 'correo' => 'ana@ejemplo.com'],
                ['nombre' => 'Roberto Gómez', 'correo' => 'roberto@ejemplo.com'],
            ];

            foreach ($pacientes as $p) {
                $exp = Expediente::create($p);
                
                // Crear 2 informes por paciente
                for ($i = 1; $i <= 2; $i++) {
                    $tipo = Tipomuestra::inRandomOrder()->first();
                    $codigo = $tipo->prefijo . str_pad($tipo->contador_actual + 1, 5, '0', STR_PAD_LEFT);
                    
                    $informe = Informe::create([
                        'expediente_id' => $exp->id,
                        'tipo_id' => $tipo->id,
                        'anio' => now()->year,
                        'correlativo' => $tipo->contador_actual + 1,
                        'codigo_identificador' => $codigo,
                        'estado' => $i % 2 == 0 ? 'completo' : 'incompleto',
                        'recepcion_observaciones' => 'Observaciones de prueba para ' . $p['nombre'],
                    ]);

                    $tipo->increment('contador_actual');
                }
            }
        }
    }
}
