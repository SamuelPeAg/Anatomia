<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expediente extends Model
{
    use HasFactory;

    /**
     * Campos asignables en masa
     */
    protected $fillable = [
        'nombre',
        'correo',
        'observaciones',
    ];

    /**
     * Relación con informes
     * Un expediente puede tener muchos informes
     */
    public function informes()
    {
        return $this->hasMany(Informe::class);
    }
}

