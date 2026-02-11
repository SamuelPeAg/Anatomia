<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Informe extends Model
{
    use HasFactory;

    protected $fillable = [
        'expediente_id', 'tipo_id', 'anio', 'correlativo', 'codigo_identificador', 'estado',
        'recepcion_formato_recibido', 'recepcion_observaciones', 'recepcion_organo',
        'procesamiento_tipo', 'procesamiento_otro', 'procesamiento_observaciones',
        'tincion_tipo', 'tincion_observaciones', 'citodiagnostico'
    ];

    protected $casts = ['anio' => 'integer', 'correlativo' => 'integer'];

    public function expediente() { return $this->belongsTo(Expediente::class); }
    public function tipo() { return $this->belongsTo(TipoMuestra::class, 'tipo_id'); }
    public function imagenes() { return $this->hasMany(Imagen::class, 'informe_id'); }
}
