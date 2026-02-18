@props(['informe' => null, 'imagenesExtras' => collect([])])
<section class="fase" id="fase-4" data-fase="4">
  <form action="{{ $informe ? route('informes.update', $informe) : route('informes.sin-fase') }}" method="POST" enctype="multipart/form-data">
    @if(!$informe)
      <div class="alert alert-warning">
          Para guardar esta fase, primero debes completar y guardar la <strong>Fase 1 (Recepción)</strong>.
      </div>
    @endif
        @csrf
        @if($informe) @method('PUT') @endif
        <div class="campo">
            <label class="etiqueta-campo" for="citodiagnostico">
                Citodiagnóstico <span class="obligatorio">*</span>
            </label>
            <textarea
                class="control-campo"
                id="citodiagnostico"
                name="citodiagnostico"
                rows="6"
                placeholder="Descripción diagnóstica, hallazgos citológicos / tisulares..."
            >{{ $informe->citodiagnostico ?? '' }}</textarea>
        </div>

        <div class="subtarjeta">
            <div class="subtarjeta-cabecera">
                <h3 class="subtarjeta-titulo">Imágenes microscópicas obligatorias</h3>
                <p class="subtarjeta-ayuda">Debes adjuntar 4 imágenes: x4, x10, x40 y x100.</p>
            </div>

            <div class="subtarjeta-cuerpo">
                <x-upload-imagenes :informe="$informe" fase="microscopio" zoom="x4" titulo="Aumento 4x (Obligatorio)" :required="true" input-name="micro_x4_img[]" input-name-desc="micro_x4_desc[]" />
                
                <x-upload-imagenes :informe="$informe" fase="microscopio" zoom="x10" titulo="Aumento 10x (Obligatorio)" :required="true" input-name="micro_x10_img[]" input-name-desc="micro_x10_desc[]" />
                
                <x-upload-imagenes :informe="$informe" fase="microscopio" zoom="x40" titulo="Aumento 40x (Obligatorio)" :required="true" input-name="micro_x40_img[]" input-name-desc="micro_x40_desc[]" />
                
                <x-upload-imagenes :informe="$informe" fase="microscopio" zoom="x100" titulo="Aumento 100x (Obligatorio)" :required="true" input-name="micro_x100_img[]" input-name-desc="micro_x100_desc[]" />

                <h3 class="subtarjeta-titulo titulo-extra">Imágenes extra (opcional)</h3>

                <!-- Imágenes Extra Guardadas -->
                @if($imagenesExtras->count() > 0)
                    <div class="imagenes-existentes-grid">
                        @foreach($imagenesExtras as $img)
                            <div class="imagen-card">
                                <div class="imagen-card-thumb">
                                    <img src="{{ asset('storage/' . $img->ruta) }}" alt="Imagen extra">
                                </div>
                                <div class="imagen-card-info">
                                    <div style="font-weight:bold; font-size:0.8em; margin-bottom:2px; color:#4b5563;">
                                        Zoom: {{ $img->zoom }}
                                    </div>
                                    <p class="imagen-desc" title="{{ $img->descripcion }}">{{ $img->descripcion ?: 'Sin descripción' }}</p>
                                    <button type="button" class="btn-link-peligro" 
                                        data-borrar-imagen-url="{{ route('imagen.destroy', $img->id) }}">
                                        Eliminar Imagen
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="lista-imagenes" data-lista-imagenes="micro-extra">
                    <div class="fila-imagen">
                        <div class="archivo-imagen">
                            <label class="etiqueta-campo">Imagen</label>
                            <input class="control-campo" type="file" name="micros_extra_img[]" accept="image/*" />
                        </div>

                        <div class="zoom-imagen">
                            <label class="etiqueta-campo">Zoom</label>
                            <select class="control-campo" name="micros_extra_zoom[]">
                                <option value="">—</option>
                                <option value="x4">x4</option>
                                <option value="x10">x10</option>
                                <option value="x40">x40</option>
                                <option value="x100">x100</option>
                            </select>
                        </div>

                        <div class="descripcion-imagen">
                            <label class="etiqueta-campo">Descripción</label>
                            <input class="control-campo" type="text" name="micros_extra_desc[]" />
                        </div>

                        <div class="acciones-imagen">
                            <button class="boton boton-peligro" type="button" data-eliminar-fila>
                                Eliminar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="acciones-imagenes">
                    <button class="boton boton-secundario" type="button" data-anadir-fila="micro-extra">
                        Añadir otra imagen
                    </button>
                </div>

                <template id="plantilla-micro-extra">
                    <div class="fila-imagen">
                        <div class="archivo-imagen">
                            <label class="etiqueta-campo">Imagen</label>
                            <input class="control-campo" type="file" name="micros_extra_img[]" accept="image/*" />
                        </div>

                        <div class="zoom-imagen">
                            <label class="etiqueta-campo">Zoom</label>
                            <select class="control-campo" name="micros_extra_zoom[]">
                                <option value="">—</option>
                                <option value="x4">x4</option>
                                <option value="x10">x10</option>
                                <option value="x40">x40</option>
                                <option value="x100">x100</option>
                            </select>
                        </div>

                        <div class="descripcion-imagen">
                            <label class="etiqueta-campo">Descripción</label>
                            <input class="control-campo" type="text" name="micros_extra_desc[]" />
                        </div>

                        <div class="acciones-imagen">
                            <button class="boton boton-peligro" type="button" data-eliminar-fila>
                                Eliminar
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="acciones">
            <div class="acciones-izquierda">
                <button class="boton boton-fantasma" type="button" data-volver-paso="3">Volver</button>
            </div>

            <div class="acciones-derecha">
                <input type="hidden" name="fase_origen" value="4">
                <button class="boton boton-secundario" type="submit" name="stay" value="2">
                    Revisión
                </button>
                <button class="boton boton-principal btn-finalizar-informe" type="submit" name="stay" value="0">
                    Finalizar y enviar
                </button>
            </div>
        </div>
    </form>
    <script>
        function validarFase4() {
            const zooms = ['x4', 'x10', 'x40', 'x100'];
            let faltantes = [];

            zooms.forEach(zoom => {
                // Buscamos el input por su name exacto
                const input = document.querySelector(`input[name="micro_${zoom}_img[]"]`);
                if (input) {
                    const container = input.closest('.subtarjeta-cuerpo');
                    if (container) {
                        // Contamos imágenes existentes (.imagen-card) y nuevas (.nueva-imagen-fila)
                        const count = container.querySelectorAll('.imagen-card, .nueva-imagen-fila').length;
                        if (count === 0) {
                            faltantes.push(zoom);
                        }
                    }
                }
            });

            if (faltantes.length > 0) {
                alert('Es obligatorio adjuntar imágenes para los aumentos: ' + faltantes.join(', ') + '. \n\nPor favor, añade al menos una imagen por aumento.');
                return false;
            }
            return true;
        }
    </script>
</section>
