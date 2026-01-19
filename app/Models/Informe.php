<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Informe extends Model
{
    use HasFactory;

    protected $fillable = [
        // Identificación / control
        'expediente_id',
        'tipo_id',
        'anio',
        'correlativo',
        'codigo_identificador',
        'estado',

        // Fase 1 - Recepcion
        'recepcion_formato_recibido',
        'recepcion_observaciones',
        'recepcion_organo',

        // Fase 2 - Procesamiento
        'procesamiento_tipo',
        'procesamiento_otro',
        'procesamiento_observaciones',

        // Fase 3 - Tincion
        'tincion_tipo',
        'tincion_observaciones',

        // Fase 4 - citodiagnostico
        'citodiagnostico',
    ];

    protected $casts = [
        'anio' => 'integer',
        'correlativo' => 'integer',
    ];

    public function expediente()
    {
        return $this->belongsTo(Expediente::class);
    }

    public function tipo()
    {
        return $this->belongsTo(TipoMuestra::class, 'tipo_id');
    }

    public function imagenes()
    {
        return $this->hasMany(Imagenes::class, 'informe_id');
    }
}
