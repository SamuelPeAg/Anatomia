

<div class="subtarjeta">
    <div class="subtarjeta-cabecera">
        <h3 class="subtarjeta-titulo">{{ $titulo }} @if($required) <span class="obligatorio">*</span> @endif</h3>
        <button type="button" class="boton boton-pequeno boton-secundario" onclick="document.getElementById('input-{{ $fase }}-{{ $zoom ?? 'gn' }}').click()">
            + Nueva Imagen
        </button>
    </div>

    <div class="subtarjeta-cuerpo">
        
        <!-- Lista de Imágenes Guardadas -->
        @if($imagenes->count() > 0)
            <div class="imagenes-existentes-grid">
                @foreach($imagenes as $img)
                    <div class="imagen-card">
                        <div class="imagen-card-thumb">
                            <img src="{{ asset('storage/' . $img->ruta) }}" alt="Imagen {{ $fase }}" loading="lazy">
                        </div>
                        <div class="imagen-card-info">
                            <p class="imagen-desc">{{ $img->descripcion ?: 'Sin descripción' }}</p>
                            <button type="button" class="btn-link-peligro" 
                                onclick="borrarImagen(event, '{{ route('imagen.destroy', $img->id) }}')">
                                Eliminar
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
               class="input-file-oculto"
               onchange="previsualizarNuevasImagenes(this, '{{ $fase }}', '{{ $zoom ?? 'gn' }}', '{{ $inputNameDesc }}')"
               style="display: none;">
               
        <small class="ayuda-campo">Formatos aceptados: JPG, PNG. Máx 5MB.</small>
    </div>
</div>

<script>
    if (typeof window.previsualizarNuevasImagenes === 'undefined') {
        window.previsualizarNuevasImagenes = function(input, fase, zoom, nameDesc) {
            const containerId = `container-${fase}-${zoom}`;
            const container = document.getElementById(containerId);
            
            if (input.files && input.files.length > 0) {
                const loteId = 'lote-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
                
                // Generar previews
                Array.from(input.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'nueva-imagen-fila';
                        div.dataset.lote = loteId;
                        // Vinculamos el input al elemento visual
                        div._inputSource = input;
                        
                        div.innerHTML = `
                            <div class="preview-thumb">
                                <img src="${e.target.result}" title="${file.name}">
                            </div>
                            <div class="preview-inputs">
                                <span class="badge-nueva">NUEVA</span>
                                <input type="text" 
                                       name="${nameDesc}" 
                                       placeholder="Descripción..." 
                                       class="control-campo control-sm">
                                <span class="nombre-archivo">${file.name}</span>
                            </div>
                            <button type="button" class="boton-icono" onclick="eliminarLoteImagenes(this)" title="Eliminar imagen (y su lote de subida)">
                                ✕
                            </button>
                        `;
                        container.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });

                // ROTACIÓN DE INPUTS
                // 1. Clonar input para crear el nuevo trigger vacío
                const newInput = input.cloneNode(true);
                newInput.value = ''; 
                
                // 2. Modificar input actual (lleno)
                input.removeAttribute('id'); // ID para el nuevo
                input.style.display = 'none';
                input.classList.add('input-lleno-oculto');
                input.dataset.lote = loteId; // Marcar input con el lote
                
                // 3. Insertar nuevo input en el DOM
                input.parentNode.insertBefore(newInput, input.nextSibling);
            }
        }

        window.eliminarLoteImagenes = function(btn) {
            const fila = btn.closest('.nueva-imagen-fila');
            const loteId = fila.dataset.lote;
            const input = fila._inputSource;
            
            if (confirm('¿Eliminar esta imagen? (Si subiste varias a la vez se eliminarán todas las de ese grupo)')) {
                // Borrar visualmente todas las del lote
                document.querySelectorAll(`.nueva-imagen-fila[data-lote="${loteId}"]`).forEach(el => el.remove());
                
                // Borrar el input del DOM
                if (input && input.parentNode) {
                    input.remove();
                }
            }
        }
    }
</script>

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
        background: none;
        border: none;
        padding: 0;
        font-size: 0.8rem;
        cursor: pointer;
        text-decoration: underline;
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
</style>
