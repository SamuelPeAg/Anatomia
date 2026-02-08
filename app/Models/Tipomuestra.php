<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoMuestra extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'prefijo', 'contador_actual', 'requiere_organo', 'descripcion', 'activo'];

    protected $casts = [
        'contador_actual' => 'integer',
        'requiere_organo' => 'boolean',
        'activo' => 'boolean',
    ];

    public function informes()
    {
        return $this->hasMany(Informe::class, 'tipo_id');
    }
}