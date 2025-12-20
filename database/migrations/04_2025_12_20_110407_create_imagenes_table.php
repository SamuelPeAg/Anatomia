<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('imagenes', function (Blueprint $table) {
            $table->id();

            // Relación con informe
            $table->foreignId('informe_id')
                  ->constrained('informes')
                  ->cascadeOnDelete();

            // Ruta del archivo de imagen
            $table->string('ruta');

            // Tipo de imagen: general o microscopica
            $table->enum('tipo_imagen', [
                'general',
                'microscopica'
            ]);

            // Zoom del microscopio (solo para microscópicas)
            $table->enum('zoom', [
                'x4',
                'x10',
                'x40',
                'x100'
            ])->nullable();

            // Descripción opcional
            $table->text('descripcion')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imagenes');
    }
};
