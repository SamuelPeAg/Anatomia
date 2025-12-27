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

            // Relación: una imagen pertenece a un informe
            $table->foreignId('informe_id')
                ->constrained('informes')
                ->cascadeOnDelete();

            // Para clasificar en qué bloque del formulario está
            $table->enum('fase', ['recepcion', 'procesamiento', 'tincion', 'microscopio']);

            // Ruta/archivo guardado (Storage)
            $table->string('ruta', 255);

            // Descripción opcional
            $table->string('descripcion', 255)->nullable();

            // Solo para microscopio (si no es microscopio, null)
            $table->enum('zoom', ['x4', 'x10', 'x40', 'x100'])->nullable();

            // Solo para microscopio: true para las 4 obligatorias, false para extras
            $table->boolean('obligatoria')->default(false);

            $table->timestamps();

            // Índices útiles (revisión/carga rápida)
            $table->index(['informe_id', 'fase']);
            $table->index(['informe_id', 'fase', 'zoom']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imagenes');
    }
};
