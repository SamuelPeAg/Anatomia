@props(['informe' => null])
<section class="fase" id="fase-4" data-fase="4">
  <form action="{{ $informe ? route('informes.update', $informe) : '#' }}" method="POST" enctype="multipart/form-data">
    @if(!$informe)
      <div class="alert alert-warning">
          Para guardar esta fase, primero debes completar y guardar la <strong>Fase 1 (Recepción)</strong>.
      </div>
    @endif
        @csrf
        @method('PUT')
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

            @php
                $imgsMicro = $informe ? $informe->imagenes->where('fase', 'microscopio') : collect(); 
                $obl = $imgsMicro->where('obligatoria', 1)->keyBy('zoom');
                $ext = $imgsMicro->where('obligatoria', 0);
            @endphp

            <div class="subtarjeta-cuerpo">
                <div class="lista-imagenes" id="listaImagenesObligatorias">
                    @foreach(['x4', 'x10', 'x40', 'x100'] as $zoom)
                        <div class="fila-imagen fila-obligatoria">
                            <div class="archivo-imagen">
                                <label class="etiqueta-campo">Imagen {{ $zoom }}</label>
                                <input class="control-campo" type="file" name="micros_required_img[{{ $zoom }}]" accept="image/*" />
                                @if($img = $obl->get($zoom))
                                    <div class="img-preview-container" style="margin-top: 10px;">
                                         <img src="{{ asset('storage/' . $img->ruta) }}" style="max-width: 300px; max-height: 300px; width: 100%; object-fit: contain; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                                         <div style="margin-top: 4px; font-size: 0.85em; color: #059669; display: flex; align-items: center; gap: 4px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            Guardada correctamente
                                         </div>
                                    </div>
                                @endif
                            </div>
                            <div class="zoom-imagen">
                                <label class="etiqueta-campo">Zoom</label>
                                <input class="control-campo" type="text" value="{{ $zoom }}" readonly />
                            </div>
                            <div class="descripcion-imagen">
                                <label class="etiqueta-campo">Descripción (opcional)</label>
                                <input class="control-campo" type="text" name="micros_required_desc[{{ $zoom }}]" 
                                       placeholder="Qué se ve..." 
                                       value="{{ $obl->get($zoom)->descripcion ?? '' }}" />
                            </div>
                        </div>
                    @endforeach
                </div>

                <h3 class="subtarjeta-titulo" style="margin-top:16px;">Imágenes extra (opcional)</h3>

                <!-- Imágenes Extra Guardadas -->
                @if($ext->count() > 0)
                    <div class="imagenes-existentes" style="margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 15px;">
                        @foreach($ext as $img)
                            <div class="imagen-item" style="border: 1px solid #e5e7eb; padding: 10px; border-radius: 8px; width: 320px;">
                                <div style="position: relative;">
                                    <img src="{{ asset('storage/' . $img->ruta) }}" 
                                         alt="Imagen extra" 
                                         style="width: 100%; height: 200px; object-fit: contain; border-radius: 4px; margin-bottom: 10px;">
                                </div>
                                <div class="info-img">
                                    <span style="display:inline-block; background: #e5e7eb; padding: 2px 8px; border-radius: 12px; font-size: 0.8em; margin-bottom: 4px;">{{ $img->zoom }}</span>
                                    <p style="font-size: 0.9em; color: #4b5563; margin-top: 4px;">{{ $img->descripcion ?: 'Sin descripción' }}</p>
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
                <button class="boton boton-fantasma" type="button" onclick="document.querySelector('.paso[data-paso=\'3\']').click()">Volver</button>
            </div>

            <div class="acciones-derecha">
                <input type="hidden" name="fase_origen" value="4">
                <input type="hidden" name="stay" value="1" id="stayFase4">
                <button class="boton boton-secundario" type="submit" onclick="document.getElementById('stayFase4').value='1'">
                    Guardar citodiagnóstico
                </button>
                <button class="boton boton-principal" type="submit" onclick="document.getElementById('stayFase4').value='0'">
                    Finalizar y enviar
                </button>
            </div>
        </div>
    </form>
</section>
