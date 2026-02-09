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
                                    <div class="img-preview-container img-preview-container-margin">
                                         <img src="{{ asset('storage/' . $img->ruta) }}" class="imagen-guardada-img">
                                         <div class="estado-guardada-container">
                                            <span>✓ Guardada</span>
                                            <button type="button" class="boton boton-peligro btn-eliminar-ajustado" onclick="borrarImagen(event, '{{ route('imagen.destroy', $img->id) }}')">Eliminar</button>
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

                <h3 class="subtarjeta-titulo titulo-extra">Imágenes extra (opcional)</h3>

                <!-- Imágenes Extra Guardadas -->
                @if($ext->count() > 0)
                    <div class="imagenes-existentes">
                        @foreach($ext as $img)
                            <div class="imagen-item">
                                <div class="wrapper-relativo">
                                    <img src="{{ asset('storage/' . $img->ruta) }}" 
                                         alt="Imagen extra" 
                                         class="imagen-guardada-img">
                                </div>
                                <div class="info-img">
                                    <span class="etiqueta-zoom">{{ $img->zoom }}</span>
                                    <p><strong>Descripción:</strong> {{ $img->descripcion ?: 'Sin descripción' }}</p>
                                </div>
                                <button type="button" class="boton boton-peligro btn-eliminar-ajustado" onclick="borrarImagen(event, '{{ route('imagen.destroy', $img->id) }}')">
                                    Eliminar
                                </button>
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
