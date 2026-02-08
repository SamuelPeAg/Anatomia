@props(['informe' => null])
<section class="fase" id="fase-2" data-fase="2">
  @if(!$informe)
    <div class="empty-state">
        <p>Debes guardar la recepción antes de poder procesar la muestra.</p>
    </div>
  @else
    <form action="{{ route('informes.update', $informe) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="campo">
            <label class="etiqueta-campo" for="tipo_procesamiento">
                Tipo de procesamiento <span class="obligatorio">*</span>
            </label>
            <select class="control-campo" id="tipo_procesamiento" name="tipo_procesamiento" required>
                <option value="">Selecciona…</option>
                <option value="CITOCENTRIFUGADO" {{ $informe && $informe->procesamiento_tipo == 'CITOCENTRIFUGADO' ? 'selected' : '' }}>Citocentrifugado</option>
                <option value="EXTENSION" {{ $informe && $informe->procesamiento_tipo == 'EXTENSION' ? 'selected' : '' }}>Extensión / Frotis</option>
                <option value="BLOQUE_CELULAR" {{ $informe && $informe->procesamiento_tipo == 'BLOQUE_CELULAR' ? 'selected' : '' }}>Bloque celular</option>
                <option value="FILTRADO" {{ $informe && $informe->procesamiento_tipo == 'FILTRADO' ? 'selected' : '' }}>Filtrado</option>
                <option value="OTRO" {{ $informe && $informe->procesamiento_tipo == 'OTRO' ? 'selected' : '' }}>Otro</option>
            </select>
        </div>

        <div class="campo {{ $informe && $informe->procesamiento_otro ? '' : 'oculto' }}" id="campoProcesamientoOtro">
            <label class="etiqueta-campo" for="procesamiento_otro">
                Especifica el procesamiento <span class="obligatorio">*</span>
            </label>
            <input class="control-campo" type="text" id="procesamiento_otro" name="procesamiento_otro" value="{{ $informe->procesamiento_otro ?? '' }}" />
        </div>

        <div class="campo">
            <label class="etiqueta-campo" for="observaciones_procesamiento">
                Observaciones del procesamiento
            </label>
            <textarea
                class="control-campo"
                id="observaciones_procesamiento"
                name="observaciones_procesamiento"
                rows="5"
                placeholder="Tiempo, técnica, incidencias, calidad de la preparación..."
            >{{ $informe->procesamiento_observaciones ?? '' }}</textarea>
        </div>

        <!-- IMÁGENES PROCESAMIENTO -->
        <div class="subtarjeta">
            <div class="subtarjeta-cabecera">
                <h3 class="subtarjeta-titulo">Imágenes del procesamiento (opcional)</h3>
                <p class="subtarjeta-ayuda">Puedes adjuntar 0 o más imágenes.</p>
            </div>

            <div class="subtarjeta-cuerpo">
                <div class="lista-imagenes" data-lista-imagenes="procesamiento">
                    <div class="fila-imagen">
                        <div class="archivo-imagen">
                            <label class="etiqueta-campo">Imagen</label>
                            <input class="control-campo" type="file" name="procesamiento_img[]" accept="image/*" />
                        </div>

                        <div class="descripcion-imagen">
                            <label class="etiqueta-campo">Descripción (opcional)</label>
                            <input class="control-campo" type="text" name="procesamiento_desc[]" placeholder="Descripción..." />
                        </div>

                        <div class="acciones-imagen">
                            <button class="boton boton-peligro" type="button" data-eliminar-fila>
                                Eliminar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="acciones-imagenes">
                    <button class="boton boton-secundario" type="button" data-anadir-fila="procesamiento">
                        Añadir imagen
                    </button>
                </div>

                <template id="plantilla-procesamiento">
                    <div class="fila-imagen">
                        <div class="archivo-imagen">
                            <label class="etiqueta-campo">Imagen</label>
                            <input class="control-campo" type="file" name="procesamiento_img[]" accept="image/*" />
                        </div>

                        <div class="descripcion-imagen">
                            <label class="etiqueta-campo">Descripción (opcional)</label>
                            <input class="control-campo" type="text" name="procesamiento_desc[]" placeholder="Descripción..." />
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
                <button class="boton boton-fantasma" type="button" onclick="document.querySelector('.paso[data-paso=\'1\']').click()">Volver</button>
            </div>

            <div class="acciones-derecha">
                <input type="hidden" name="stay" value="1" id="stayFase2">
                <button class="boton boton-secundario" type="submit" onclick="document.getElementById('stayFase2').value='1'">
                    Guardar procesamiento (incompleto)
                </button>
                <button class="boton boton-principal" type="submit" onclick="document.getElementById('stayFase2').value='0'">
                    Siguiente fase
                </button>
            </div>
        </div>
    </form>
  @endif
</section>
