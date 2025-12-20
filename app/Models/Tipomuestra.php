<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoMuestra extends Model
{
    use HasFactory;

    /**
     * Campos asignables en masa
     */
    protected $fillable = [
        'nombre',
        'prefijo',
        'contador_actual',
        'requiere_organo',
        'descripcion',
        'activo',
    ];

    /**
     * Relación con informes
     * Un tipo de muestra puede tener muchos informes
     */
    public function informes()
    {
        return $this->hasMany(Informe::class, 'tipo_id');
    }
}
 