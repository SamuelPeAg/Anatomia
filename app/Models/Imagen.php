<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Imagen extends Model
{
    use HasFactory;
    
    protected $table = 'imagenes';

    protected $fillable = [
        'informe_id', 
        'fase', 
        'ruta', 
        'descripcion', 
        'zoom', 
        'obligatoria'
    ];

    public function informe()
    {
        return $this->belongsTo(Informe::class);
    }

    /**
     * Genera la URL de la imagen de forma segura.
     * Si no existe el enlace simbólico en producción, esto ayuda a depurar.
     */
    public function getUrlAttribute()
    {
        if (!$this->ruta) return asset('img/placeholder.png');
        
        // Forma infalible para Plesk: ruta relativa directa al enlace simbólico
        return '/storage/' . $this->ruta;
    }
}
