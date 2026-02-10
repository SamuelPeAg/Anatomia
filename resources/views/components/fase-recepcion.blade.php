@props(['tiposMuestra', 'informe' => null])

<section class="fase fase-activa" id="fase-1" data-fase="1">
  <form action="{{ $informe ? route('informes.update', $informe) : route('guardar_informe') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($informe) @method('PUT') @endif
    
    <div class="rejilla">
        <div class="campo">
            <label class="etiqueta-campo" for="paciente_nombre">
                Nombre del Paciente <span class="opcional">(Opcional)</span>
            </label>
            <input
                class="control-campo"
                type="text"
                id="paciente_nombre"
                name="paciente_nombre"
                placeholder="Ej: Juan Pérez"
                value="{{ $informe && $informe->expediente ? $informe->expediente->nombre : '' }}"
                {{ $informe ? 'readonly' : '' }}
            />
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
                {{ $informe ? 'readonly' : '' }}
            />
            <small class="ayuda-campo">Úsalo para vincular informes al mismo paciente.</small>
        </div>
    </div>

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

        <div class="campo">
            <label class="etiqueta-campo" for="codigo_identificador">
                Código identificador (auto)
            </label>
            <input
                class="control-campo"
                type="text"
                id="codigo_identificador"
                name="codigo_identificador"
                placeholder="Ej: B2530"
                value="{{ $informe->codigo_identificador ?? '' }}"
                readonly
            />
            <small class="ayuda-campo" id="ayudaCodigo">
                Se genera automáticamente al seleccionar el tipo.
            </small>
        </div>
    </div>

    <div class="campo">
        <label class="etiqueta-campo" for="observaciones_llegada">
            Observaciones de la llegada (Opcional)
        </label>
        <textarea
            class="control-campo"
            id="observaciones_llegada"
            name="observaciones_llegada"
            rows="5"
            placeholder="Estado del recipiente, incidencias, cantidad, etc."
        >{{ $informe->recepcion_observaciones ?? '' }}</textarea>
    </div>

    <div class="campo {{ $informe && $informe->recepcion_organo ? '' : 'oculto' }}" id="campoOrgano">
        <label class="etiqueta-campo" for="organo">
            Órgano (solo biopsia) <span class="obligatorio">*</span>
        </label>
        <input class="control-campo" type="text" id="organo" name="organo" value="{{ $informe->recepcion_organo ?? '' }}" />
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
            <input type="hidden" name="stay" value="1" id="stayFase1">
            <button class="boton boton-secundario" type="submit" onclick="document.getElementById('stayFase1').value='1'">
                Guardar recepción (incompleto)
            </button>
            <button class="boton boton-principal" type="submit" onclick="document.getElementById('stayFase1').value='0'">
                Siguiente fase
            </button>
        </div>
    </div>
  </form>
</section>
