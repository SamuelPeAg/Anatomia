@props(['informe' => null])
<section class="fase" id="fase-2" data-fase="2">
  <form action="{{ $informe ? route('informes.update', $informe) : route('informes.sin-fase') }}" method="POST" enctype="multipart/form-data">
    @if(!$informe)
      <div class="alert alert-warning">
          Para guardar esta fase, primero debes completar y guardar la <strong>Fase 1 (Recepción)</strong>.
      </div>
    @endif
        @csrf
        @if($informe) @method('PUT') @endif
        <div class="campo">
            <label class="etiqueta-campo" for="tipo_procesamiento">
                Tipo de procesamiento <span class="obligatorio">*</span>
            </label>
            <select class="control-campo" id="tipo_procesamiento" name="tipo_procesamiento">
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
                Especifica el procesamiento (Opcional)
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
        <x-upload-imagenes 
            :informe="$informe" 
            fase="procesamiento" 
            titulo="Imágenes del procesamiento (opcional)"
        />

        <div class="acciones">
            <div class="acciones-izquierda">
                <button class="boton boton-fantasma" type="button" data-volver-paso="1">Volver</button>
            </div>

            <div class="acciones-derecha">
                <input type="hidden" name="fase_origen" value="2">
                <button class="boton boton-secundario" type="submit" name="stay" value="2">
                    Revisión
                </button>
                <button class="boton boton-principal" type="submit" name="stay" value="0">
                    Siguiente fase
                </button>
            </div>
        </div>
    </form>
</section>
