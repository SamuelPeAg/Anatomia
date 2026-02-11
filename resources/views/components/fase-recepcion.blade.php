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
            <div class="autocomplete-wrapper" style="position: relative;">
                <input
                    class="control-campo"
                    type="text"
                    id="paciente_nombre"
                    name="paciente_nombre"
                    placeholder="Ej: Juan Pérez"
                    value="{{ $informe && $informe->expediente ? $informe->expediente->nombre : '' }}"
                    {{ $informe ? 'readonly' : '' }}
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
                {{ $informe ? 'readonly' : '' }}
            />
            <small class="ayuda-campo">Úsalo para vincular informes al mismo paciente.</small>
        </div>
    </div>

    <style>
        .autocomplete-items {
            position: absolute;
            border: 1px solid #d4d4d4;
            border-bottom: none;
            border-top: none;
            z-index: 99;
            top: 100%;
            left: 0;
            right: 0;
            background-color: #fff;
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 200px;
            overflow-y: auto;
            border-radius: 0 0 5px 5px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .autocomplete-items li {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #d4d4d4;
            font-size: 0.9rem;
        }
        .autocomplete-items li:hover {
            background-color: #e9e9e9; 
        }
        .autocomplete-items li strong {
            color: #2563eb;
        }
    </style>

    {{-- Script de Autocompletado --}}
    @if(!$informe)
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputNombre = document.getElementById('paciente_nombre');
            const list = document.getElementById('autocomplete-list');
            const inputCorreo = document.getElementById('paciente_correo');
            const warningBox = document.getElementById('similarity-warning');
            const suggestedNameParams = document.getElementById('suggested-name');
            let debounceTimer;
            let currentResults = [];

            // Cerrar lista al hacer clic fuera
            document.addEventListener('click', function(e) {
                if (e.target !== inputNombre && e.target !== list) {
                    list.style.display = 'none';
                }
            });

            // Función para rellenar datos
            function selectPatient(name, email) {
                inputNombre.value = name;
                if(email && !inputCorreo.value) inputCorreo.value = email;
                list.style.display = 'none';
                warningBox.style.display = 'none';
            }

            // Manejar clic en sugerencia del warning
            suggestedNameParams.addEventListener('click', function() {
                const name = this.textContent;
                // Buscar el correo en los resultados actuales si es posible, o hacer fetch
                const found = currentResults.find(r => r.nombre === name);
                selectPatient(name, found ? found.correo : '');
            });

            inputNombre.addEventListener('input', function() {
                const term = this.value;
                warningBox.style.display = 'none';
                
                if(term.length < 2) {
                    list.style.display = 'none';
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    fetch(`{{ route('expedientes.search') }}?term=${encodeURIComponent(term)}`)
                        .then(response => response.json())
                        .then(data => {
                            currentResults = data;
                            list.innerHTML = '';
                            
                            if (data.length > 0) {
                                data.forEach(item => {
                                    const li = document.createElement('li');
                                    // Resaltar coincidencia
                                    const regex = new RegExp(`(${term})`, "gi");
                                    const highlighted = item.nombre.replace(regex, "<strong>$1</strong>");
                                    
                                    li.innerHTML = highlighted;
                                    li.addEventListener('click', () => selectPatient(item.nombre, item.correo));
                                    list.appendChild(li);
                                });
                                list.style.display = 'block';
                            } else {
                                list.style.display = 'none';
                            }
                        });
                }, 300);
            });

            // Lógica de "Similitud" al perder foco
            inputNombre.addEventListener('blur', function() {
                setTimeout(() => { // Pequeño delay para permitir clic en la lista
                    const val = this.value;
                    if (!val || list.style.display === 'block') return; // Si sigue abierta o vacía, nada

                    // Si el valor NO coincide exactamente con ninguno de los resultados previos (si los hay)
                    // pero "se parece", mostramos warning.
                    // Como la búsqueda es "LIKE %term%", ya tenemos los similares en 'currentResults'.
                    
                    // 1. Buscamos si hay match exacto
                    const exactMatch = currentResults.find(r => r.nombre.toLowerCase() === val.toLowerCase());
                    if (exactMatch) return; // Todo bien, es uno existente

                    // 2. Si no hay match exacto, miramos si hay alguno en los resultados (que son 'similares' por el LIKE)
                    if (currentResults.length > 0) {
                        // Tomamos el primero como sugerencia más probable
                        suggestedNameParams.textContent = currentResults[0].nombre;
                        warningBox.style.display = 'block';
                    }
                }, 200);
            });
        });
    </script>
    @endif

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
                <button class="boton boton-secundario" type="submit" name="stay" value="1">
                    Guardar recepción (incompleto)
                </button>
                <button class="boton boton-principal" type="submit" name="stay" value="0">
                    Siguiente fase
                </button>
            </div>
    </div>
  </form>
</section>
