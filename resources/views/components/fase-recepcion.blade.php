@props(['tiposMuestra', 'informe' => null])

<section class="fase fase-activa" id="fase-1" data-fase="1">
  <form action="{{ $informe ? route('informes.update', $informe) : route('guardar_informe') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($informe) @method('PUT') @endif
    
    {{-- El código ahora se muestra en el header de la página. Lo mantenemos como input oculto para el envío del form --}}
    <input type="hidden" id="codigo_identificador" name="codigo_identificador" value="{{ $informe->codigo_identificador ?? '' }}">

    <div class="rejilla">
        <div class="campo">
            <label class="etiqueta-campo" for="tipo_muestra">
                Tipo de muestra <span class="obligatorio">*</span>
            </label>
            <select class="control-campo" id="tipo_muestra" name="tipo_muestra" required data-url-patron="{{ route('tipos.siguienteCodigo', 'PREFIX') }}">
                <option value="">Selecciona un tipo</option>
                <option value="B" {{ $informe && $informe->tipo && $informe->tipo->prefijo == 'B' ? 'selected' : '' }}>Biopsia</option>
                <option value="E" {{ $informe && $informe->tipo && $informe->tipo->prefijo == 'E' ? 'selected' : '' }}>Esputo</option>
                <option value="CB" {{ $informe && $informe->tipo && $informe->tipo->prefijo == 'CB' ? 'selected' : '' }}>Cavidad bucal</option>
                <option value="CV" {{ $informe && $informe->tipo && $informe->tipo->prefijo == 'CV' ? 'selected' : '' }}>Citología vaginal</option>
                <option value="EX" {{ $informe && $informe->tipo && $informe->tipo->prefijo == 'EX' ? 'selected' : '' }}>Extensión sanguínea</option>
                <option value="O" {{ $informe && $informe->tipo && $informe->tipo->prefijo == 'O' ? 'selected' : '' }}>Orinas</option>
                <option value="ES" {{ $informe && $informe->tipo && $informe->tipo->prefijo == 'ES' ? 'selected' : '' }}>Semen</option>
                <option value="I" {{ $informe && $informe->tipo && $informe->tipo->prefijo == 'I' ? 'selected' : '' }}>Improntas</option>
                <option value="F" {{ $informe && $informe->tipo && $informe->tipo->prefijo == 'F' ? 'selected' : '' }}>Frotis</option>
                <option value="OTRO" {{ $informe && $informe->tipo && $informe->tipo->prefijo == 'OTRO' ? 'selected' : '' }}>Otro</option>
            </select>
        </div>

        <div class="campo {{ $informe && $informe->recepcion_organo ? '' : 'oculto' }}" id="campoOrgano">
            <label class="etiqueta-campo" for="organo">
                Órgano (solo biopsia) <span class="obligatorio">*</span>
            </label>
            <input class="control-campo" type="text" id="organo" name="organo" value="{{ $informe->recepcion_organo ?? '' }}" />
        </div>
    </div>

    <div class="campo">
        <label class="etiqueta-campo" for="observaciones_llegada">
            Observaciones de la llegada <span class="obligatorio">*</span>
        </label>
        <textarea
            class="control-campo"
            id="observaciones_llegada"
            name="observaciones_llegada"
            rows="4"
            placeholder="Estado del recipiente, incidencias, cantidad, etc."
            required
        >{{ $informe->recepcion_observaciones ?? '' }}</textarea>
    </div>

    <div class="rejilla">
        <div class="campo">
            <label class="etiqueta-campo" for="paciente_nombre">
                Nombre del Paciente <span class="opcional">(Opcional)</span>
            </label>
            <div class="autocomplete-wrapper" style="position: relative;">
                <input
                    class="control-campo"
                    type="text"
                    id="paciente_nombre"
                    name="paciente_nombre"
                    placeholder="Ej: Juan Pérez"
                    value="{{ $informe && $informe->expediente ? $informe->expediente->nombre : '' }}"
                    autocomplete="off"
                />
                <ul id="autocomplete-list" class="autocomplete-items" style="display: none;"></ul>
                <div id="similarity-warning" class="alert alert-warning mt-2 p-2" style="display: none; font-size: 0.9rem;">
                    <i class="bi bi-exclamation-triangle-fill text-warning me-1"></i>
                    ¿Quizás quisiste decir <strong id="suggested-name" style="cursor: pointer; text-decoration: underline;"></strong>?
                    <button type="button" class="btn-close ms-2 float-end" style="font-size: 0.7rem;" onclick="this.parentElement.style.display='none'"></button>
                </div>
            </div>
        </div>

        <div class="campo">
            <label class="etiqueta-campo" for="paciente_correo">
                Correo del Paciente <span class="opcional">(Opcional)</span>
            </label>
            <input
                class="control-campo"
                type="email"
                id="paciente_correo"
                name="paciente_correo"
                placeholder="paciente@ejemplo.com"
                value="{{ $informe && $informe->expediente ? $informe->expediente->correo : '' }}"
            />
            <small class="ayuda-campo">Vínculo de informes al mismo paciente.</small>
        </div>
    </div>

    <!-- IMÁGENES RECEPCIÓN -->
    <x-upload-imagenes 
        :informe="$informe" 
        fase="recepcion" 
        titulo="Imágenes de recepción (opcional)"
    />

    <div class="acciones">
        <div class="acciones-izquierda">
            <a class="enlace" href="{{ route('revision') }}">Volver al listado</a>
        </div>

            <div class="acciones-derecha">
                <input type="hidden" name="fase_origen" value="1">
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
