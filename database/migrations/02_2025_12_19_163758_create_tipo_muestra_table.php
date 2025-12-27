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

            $table->string('nombre', 80);

            // Prefijo (si ya tienes enum preparado con todos los valores, lo ponemos como enum)
            $table->enum('prefijo', ['B','BV','CB','CV','EX','O','E','ES','I','F','OTRO'])
                ->unique();

            // Contador para generar correlativos por tipo (lo normal: empieza en 0)
            $table->unsignedInteger('contador_actual')->default(0);

            // Si requiere órgano (biopsias, etc.)
            $table->boolean('requiere_organo')->default(false);

            // Descripción opcional del tipo
            $table->string('descripcion', 255)->nullable();

            // Activo/inactivo
            $table->boolean('activo')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_muestras');
    }
};
