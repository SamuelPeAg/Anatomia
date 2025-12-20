<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Informe extends Model
{
    use HasFactory;

    protected $fillable = [
        'expediente_id',
        'tipo_id',
        'codigo_identificador',
        'organo',
        'formato_recibido',
        'descripcion_recogida',
        'descripcion_citologica',
        'estado',
    ];

    /**
     * Relación con Expediente
     * Un informe puede pertenecer a un expediente (o no)
     */
    public function expediente()
    {
        return $this->belongsTo(Expediente::class);
    }


    public function tipo()
    {
        return $this->belongsTo(TipoMuestra::class);
    }
}

