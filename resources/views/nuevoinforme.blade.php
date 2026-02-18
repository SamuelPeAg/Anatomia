

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $esEdicion ? 'Editar' : 'Nuevo' }} informe — DAVANTE</title>
    @vite(['resources/css/nuevoinforme.css', 'resources/css/principal.css', 'resources/css/alerts.css'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Estilo para SweetAlert desde la izquierda */
        .swal2-toast-left {
            margin-left: 1rem !important;
        }

        /* Mover el formulario hacia arriba */
        .pagina {
            padding-top: 1rem !important;
            margin-top: 0 !important;
        }
        .contenedor {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }
        .cabecera-pagina {
            margin-bottom: 1.5rem !important;
        }

        /* Color verde para fases completadas */
        .paso-completado {
            background-color: #10b981 !important; /* Verde Esmeralda */
            color: white !important;
            border-color: #059669 !important;
        }
        .paso-completado .paso-numero {
            background-color: rgba(255, 255, 255, 0.3) !important;
            color: white !important;
        }
        .paso-completado:hover {
            background-color: #059669 !important;
        }

        /* Asegurar que el texto sea visible y estilizado */
        .paso-completado .paso-texto,
        .paso-activo .paso-texto {
            display: inline-block !important;
            color: white !important;
            margin-left: 8px;
            font-weight: 600;
        }
        .oculto { display: none !important; }

        /* Autocomplete Styles */
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
            background-color: #f1f5f9; 
        }
        .autocomplete-items li strong {
            color: #0234AB;
        }
    </style>
</head>

<body class="{{ $esEdicion ? 'modo-continuar' : 'modo-crear' }}">
    <x-header />

    <main class="pagina">
        <section class="contenedor" id="informe-config"
            data-fase-inicial="{{ $faseActual }}"
            data-es-edicion="{{ $esEdicion ? 'true' : 'false' }}"
            data-fases-completas='@json($fasesCompletas)'>

            <header class="cabecera-pagina">
                <div class="cabecera-izquierda">
                    <h1 class="titulo-pagina">Registro de Informe</h1>
                    <p class="subtitulo-pagina">Completa todas las fases del proceso citológico.</p>
                </div>

                <div class="cabecera-derecha">
                    @if(isset($informe))
                        <span class="etiqueta etiqueta-edicion">Modo Edición</span>
                    @endif
                </div>
            </header>

            <nav class="pasos" aria-label="Progreso">
                <button class="paso {{ ($fasesCompletas[1] ?? false) ? 'paso-completado' : '' }} paso-activo" type="button" data-paso="1"><span class="paso-numero">1</span><span class="paso-texto">Recepción</span></button>
                <button class="paso {{ ($fasesCompletas[2] ?? false) ? 'paso-completado' : '' }}" type="button" data-paso="2"><span class="paso-numero">2</span><span class="paso-texto">Procesamiento</span></button>
                <button class="paso {{ ($fasesCompletas[3] ?? false) ? 'paso-completado' : '' }}" type="button" data-paso="3"><span class="paso-numero">3</span><span class="paso-texto">Tinción</span></button>
                <button class="paso {{ ($fasesCompletas[4] ?? false) ? 'paso-completado' : '' }}" type="button" data-paso="4"><span class="paso-numero">4</span><span class="paso-texto">Citodiagnóstico</span></button>
            </nav>

            <article class="tarjeta">
                <div class="tarjeta-cabecera">
                    <h2 class="tarjeta-titulo" id="tituloFase">Cargando fase...</h2>
                    <p class="tarjeta-ayuda">Los campos con <span class="obligatorio">*</span> son obligatorios.</p>
                </div>

                <div class="tarjeta-cuerpo {{ (isset($informe) && $informe->estado === 'revisado') ? 'modo-lectura' : '' }}">
                    @if(isset($informe) && $informe->estado === 'revisado')
                        <div class="alert alert-info lock-notice">
                            <strong><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>Informe Revisado</strong>: Este informe está bloqueado para ediciones.
                        </div>
                    @endif

                    @if(session('success'))
                        <script>
                            document.addEventListener('DOMContentLoaded', () => {
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-start',
                                    showConfirmButton: false,
                                    timer: 5000,
                                    timerProgressBar: true,
                                    customClass: { container: 'swal2-toast-left' }
                                });
                                Toast.fire({
                                    icon: 'success',
                                    title: "{{ session('success') }}"
                                });
                            });
                        </script>
                    @endif

                    @if($errors->any())
                        <script>
                            document.addEventListener('DOMContentLoaded', () => {
                                let errorList = '';
                                @foreach($errors->all() as $error)
                                    errorList += '• {{ $error }}\n';
                                @endforeach
                                
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Revisa los campos',
                                    text: errorList,
                                    confirmButtonColor: '#0234AB'
                                });
                            });
                        </script>
                    @endif

                    <x-fase-recepcion :informe="$informe" />
                    <x-fase-procesamiento :informe="$informe" />
                    <x-fase-tincion :informe="$informe" />
                    <x-fase-citodiagnostico :informe="$informe" :imagenes-extras="$imagenesMicroExtras ?? collect([])" />
                </div>
            </article>
        </section>
    </main>

    <x-modal-confirm />
    <div id="toast-container" class="toast-container"></div>
    <x-footer />

    @vite(['resources/js/formulario-ui.js', 'resources/js/formulario-acciones.js'])

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputNombre = document.getElementById('paciente_nombre');
            const list = document.getElementById('autocomplete-list');
            if (!inputNombre || !list) return;

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
                if(email && inputCorreo && !inputCorreo.value) inputCorreo.value = email;
                list.style.display = 'none';
                if(warningBox) warningBox.style.display = 'none';
            }

            // Manejar clic en sugerencia del warning
            if (suggestedNameParams) {
                suggestedNameParams.addEventListener('click', function() {
                    const name = this.textContent;
                    const found = currentResults.find(r => r.nombre === name);
                    selectPatient(name, found ? found.correo : '');
                });
            }

            inputNombre.addEventListener('input', function() {
                const term = this.value;
                if(warningBox) warningBox.style.display = 'none';
                
                if(term.length < 2) {
                    list.style.display = 'none';
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    fetch(`/api/expedientes/search?term=${encodeURIComponent(term)}`)
                        .then(response => response.json())
                        .then(data => {
                            currentResults = data;
                            list.innerHTML = '';
                            
                            if (data.length > 0) {
                                data.forEach(item => {
                                    const li = document.createElement('li');
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
        });
    </script>
</body>
</html>
