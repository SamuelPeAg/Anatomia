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

            // =========================
            // Identificación / control
            // =========================
            $table->foreignId('expediente_id')
                ->nullable()
                ->constrained('expedientes')
                ->nullOnDelete();

            $table->foreignId('tipo_id')
                ->constrained('tipo_muestras')
                ->cascadeOnDelete();

            // Año y correlativo para el código (B2530)
            $table->unsignedSmallInteger('anio');      // 2025
            $table->unsignedInteger('correlativo');    // 30

            // Código final (compacto o con guiones)
            $table->string('codigo_identificador', 20)->unique();

            // Estado del informe
            $table->enum('estado', ['incompleto', 'completo', 'revisado'])
                ->default('incompleto');

            // =========================
            // Fase 1 - Recepción
            // =========================
            $table->string('recepcion_formato_recibido', 50)->nullable();
            $table->text('recepcion_observaciones')->nullable();
            $table->string('recepcion_organo', 120)->nullable();

            // =========================
            // Fase 2 - Procesamiento
            // =========================
            $table->string('procesamiento_tipo', 80)->nullable();
            $table->string('procesamiento_otro', 120)->nullable();
            $table->text('procesamiento_observaciones')->nullable();

            // =========================
            // Fase 3 - Tinción
            // =========================
            $table->string('tincion_tipo', 120)->nullable();
            $table->text('tincion_observaciones')->nullable();

            // =========================
            // Fase 4 - Citodiagnóstico
            // =========================
            $table->text('citodiagnostico')->nullable();

            $table->timestamps();

            // Para asegurar que no haya dos informes con el mismo (tipo, año, correlativo)
            $table->unique(['tipo_id', 'anio', 'correlativo'], 'uniq_informes_tipo_anio_correlativo');

            // Para filtrar rápido en revisión
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informes');
    }
};
