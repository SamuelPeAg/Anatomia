<div class="subtarjeta">
    <div class="subtarjeta-cabecera">
        <div class="subtarjeta-info-grupo">
            <h3 class="subtarjeta-titulo">{{ $titulo }} @if($required) <span class="obligatorio">*</span> @endif</h3>
            <span class="contador-imagenes {{ $imagenes->count() >= 12 ? 'limite-alcanzado' : '' }}">
                {{ $imagenes->count() }} / 12
            </span>
        </div>
        <button type="button" 
                class="boton boton-pequeno boton-secundario {{ $imagenes->count() >= 12 ? 'boton-deshabilitado' : '' }}" 
                data-trigger-upload="input-{{ $fase }}-{{ $zoom ?? 'gn' }}"
                {{ $imagenes->count() >= 12 ? 'disabled' : '' }}>
            + Nueva Imagen
        </button>
    </div>

    <div class="subtarjeta-cuerpo">
        
        <!-- Lista de Imágenes Guardadas -->
        @if($imagenes->count() > 0)
            <div class="imagenes-existentes-grid">
                @foreach($imagenes as $img)
                    <div class="imagen-card" id="imagen-{{ $img->id }}">
                        <div class="imagen-card-thumb">
                            <img src="{{ $img->url }}" alt="Imagen {{ $fase }}" loading="lazy">
                        </div>
                        <div class="imagen-card-info">
                            <p class="imagen-desc">{{ $img->descripcion ?: 'Sin descripción' }}</p>
                                <button type="button" class="btn-link-peligro" 
                                    data-borrar-imagen-url="{{ route('imagen.destroy', $img->id) }}">
                                    Eliminar Imagen
                                </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="texto-vacio">No hay imágenes guardadas.</p>
        @endif

        <!-- Área de Nuevas Imágenes (Oculta hasta que se añaden) -->
        <div class="nuevas-imagenes-container" id="container-{{ $fase }}-{{ $zoom ?? 'gn' }}">
            <!-- Las filas se añadirán aquí dinámicamente con JS -->
        </div>

        <!-- Input oculto para añadir (trigger) -->
        <input type="file" 
               id="input-{{ $fase }}-{{ $zoom ?? 'gn' }}" 
               name="{{ $inputName }}" 
               accept="image/*" 
               multiple
               class="input-file-oculto input-previsualizable"
               data-fase="{{ $fase }}"
               data-zoom="{{ $zoom ?? 'gn' }}"
               data-name-desc="{{ $inputNameDesc }}"
               style="display: none;">
               
        <small class="ayuda-campo">Formatos aceptados: JPG, PNG. Máx 5MB.</small>
    </div>
</div>

<style>
    /* Estilos encapsulados para el componente */
    .imagenes-existentes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }
    .imagen-card {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        background: white;
    }
    .imagen-card-thumb {
        height: 120px;
        overflow: hidden;
        background: #f9fafb;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .imagen-card-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .imagen-card-info {
        padding: 8px;
        font-size: 0.85rem;
    }
    .imagen-desc {
        margin: 0 0 5px;
        color: #4b5563;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .btn-link-peligro {
        color: #ef4444;
        background: #fee2e2;
        border: 1px solid #fecaca;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
        font-weight: 500;
        margin-top: 5px;
    }
    .btn-link-peligro:hover {
        background: #ef4444;
        color: white;
        border-color: #ef4444;
    }
    
    .nueva-imagen-fila {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        background: #eff6ff;
        border: 1px dashed #3b82f6;
        border-radius: 6px;
        margin-top: 10px;
    }
    .preview-thumb img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
    }
    .preview-inputs {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .badge-nueva {
        font-size: 0.65rem;
        background: #3b82f6;
        color: white;
        padding: 2px 6px;
        border-radius: 4px;
        width: fit-content;
    }
    .boton-icono {
        background: none;
        border: none;
        font-weight: bold;
        color: #6b7280;
        cursor: pointer;
    }
    .texto-vacio {
        color: #9ca3af;
        font-style: italic;
        text-align: center;
        padding: 20px;
        border: 1px dashed #e5e7eb;
        border-radius: 8px;
    }
    .subtarjeta-info-grupo {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .contador-imagenes {
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        background: #f1f5f9;
        padding: 2px 8px;
        border-radius: 999px;
    }
    .contador-imagenes.limite-alcanzado {
        color: #ef4444;
        background: #fee2e2;
    }
    .boton-deshabilitado {
        opacity: 0.5;
        cursor: not-allowed !important;
        pointer-events: none;
    }
</style>
