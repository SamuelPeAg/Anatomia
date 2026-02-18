<div class="subtarjeta">
    <div class="subtarjeta-cabecera">
        <div class="subtarjeta-info-grupo">
            <h3 class="subtarjeta-titulo">{{ $titulo }} @if($required) <span class="obligatorio">*</span> @endif</h3>
            <span class="contador-imagenes {{ $imagenes->count() >= 6 ? 'limite-alcanzado' : '' }}">
                {{ $imagenes->count() }} / 6
            </span>
        </div>
        <button type="button" 
                class="boton boton-pequeno boton-secundario {{ $imagenes->count() >= 6 ? 'boton-deshabilitado' : '' }}" 
                data-trigger-upload="input-{{ $fase }}-{{ $zoom ?? 'gn' }}"
                {{ $imagenes->count() >= 6 ? 'disabled' : '' }}>
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

