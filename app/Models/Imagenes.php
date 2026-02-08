<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagenes extends Model
{
    use HasFactory;

    protected $table = 'imagenes';
    protected $fillable = ['informe_id', 'fase', 'ruta', 'descripcion', 'zoom', 'obligatoria'];
    protected $casts = ['obligatoria' => 'boolean'];

    public function informe() { return $this->belongsTo(Informe::class, 'informe_id'); }
}
