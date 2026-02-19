<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class UploadImagenes extends Component
{
    public $informe;
    public $fase;
    public $titulo;
    public $zoom;
    public $required;
    public $inputName;
    public $inputNameDesc;
    public $imagenes;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $informe = null,
        string $fase = '',
        string $titulo = 'Imágenes adjuntas',
        $zoom = null,
        bool $required = false,
        $inputName = null,
        $inputNameDesc = null
    ) {
        $this->informe = $informe;
        $this->fase = $fase;
        $this->titulo = $titulo;
        $this->zoom = $zoom;
        $this->required = $required;
        
        // Lógica de filtrado de imágenes (movida desde Blade)
        $this->imagenes = collect([]);
        
        if ($this->informe && $this->informe->relationLoaded('imagenes')) {
            // Usamos la colección ya cargada en memoria, no una nueva query
            // Nota: where en colecciones retorna nueva colección
            $filtered = $this->informe->imagenes->where('fase', $this->fase);
            
            if ($this->zoom !== null) {
                $filtered = $filtered->where('zoom', $this->zoom);
            }
            
            $this->imagenes = $filtered;
        } else if ($this->informe) {
             // Fallback si no está cargada (aunque debería estarlo por eager loading)
             $this->imagenes = $this->informe->imagenes()->where('fase', $this->fase);
             if ($this->zoom !== null) {
                 $this->imagenes->where('zoom', $this->zoom);
             }
             $this->imagenes = $this->imagenes->get();
        }

        // Definición de nombres de inputs
        // Si no se pasa inputName explícito, se genera uno por defecto con []
        $this->inputName = $inputName ?? ($this->fase . '_img[]');
        $this->inputNameDesc = $inputNameDesc ?? ($this->fase . '_desc[]');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|\Closure|string
     */
    public function render()
    {
        return view('components.upload-imagenes');
    }
}
