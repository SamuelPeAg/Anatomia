<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagenes extends Model
{
    use HasFactory;

    /**
     * Campos asignables en masa
     */
    protected $fillable = [
        'informe_id',
        'ruta',
        'tipo_imagen',
        'zoom',
        'descripcion',
    ];

    /**
     * Relación con Informe
     * Una imagen pertenece a un informe
     */
    public function informe()
    {
        return $this->belongsTo(Informe::class);
    }
}
