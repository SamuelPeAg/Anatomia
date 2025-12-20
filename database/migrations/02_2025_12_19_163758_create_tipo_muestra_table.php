<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipo_muestras', function (Blueprint $table) {
            $table->id();

            // Nombre del tipo de muestra (Biopsia, Esputo, Otro...)
            $table->string('nombre');

            // Prefijo para el código identificador (BIO, ESP, OTR...)
            $table->enum('prefijo', [
                'B',    // Biopsia
                'BV',   // Biopsia veterinaria
                'CB',   // Cavidad bucal
                'CV',   // Citología vaginal
                'EX',   // Extensión sanguínea
                'O',    // Orinas
                'E',    // Esputo
                'ES',   // Semen
                'I',    // Improntas
                'F'     // Frotis
            ]);

            // Contador para generar el número correlativo por tipo
            $table->unsignedInteger('contador_actual')->default(0);

            // Indica si este tipo requiere el campo "órgano"
            $table->boolean('requiere_organo')->default(false);


            // Permite activar/desactivar tipos sin borrarlos
            $table->boolean('activo')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_muestras');
    }
};
