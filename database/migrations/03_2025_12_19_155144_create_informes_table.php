<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('informes', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->foreignId('expediente_id')
                  ->nullable()
                  ->constrained('expedientes')
                  ->nullOnDelete();

            $table->foreignId('tipo_id')
                  ->constrained('tipo_muestras');

            // Identificación
            $table->string('codigo_identificador')->unique();

            // Datos del informe
            $table->string('organo')->nullable();
            $table->string('formato_recibido');
            $table->text('descripcion_recogida');
            $table->text('descripcion_citologica');

            // Estado del informe
            $table->enum('estado', [
                'incompleto',
                'completo',
                'revisado'
            ])->default('incompleto');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informes');
    }
};

