<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagenes extends Model
{
    use HasFactory;

    protected $table = 'imagenes';

    protected $fillable = [
        'informe_id',
        'fase',         // recepcion | procesamiento | tincion | microscopio
        'ruta',         // path en storage
        'descripcion',  // nullable
        'zoom',         // x4 | x10 | x40 | x100 (nullable)
        'obligatoria',  // bool
    ];

    protected $casts = [
        'obligatoria' => 'boolean',
    ];

    /**
     * Una imagen pertenece a un informe
     */
    public function informe()
    {
        return $this->belongsTo(Informe::class, 'informe_id');
    }
}
