

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $esEdicion ? 'Editar' : 'Nuevo' }} informe — DAVANTE</title>
    @vite(['resources/css/nuevoinforme.css', 'resources/css/principal.css', 'resources/css/alerts.css'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="{{ $esEdicion ? 'modo-continuar' : 'modo-crear' }}"
    data-success="{{ session('success') }}"
    data-error="{{ $errors->any() ? implode('\n', $errors->all()) : '' }}">
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


                    <x-fase-recepcion :informe="$informe" />
                    <x-fase-procesamiento :informe="$informe" />
                    <x-fase-tincion :informe="$informe" />
                    <x-fase-citodiagnostico :informe="$informe" :imagenes-extras="$imagenesMicroExtras ?? collect([])" />
                </div>
            </article>
        </section>
    </main>


    <div id="toast-container" class="toast-container"></div>
    <x-footer />

    @vite(['resources/js/app-alerts.js', 'resources/js/formulario-ui.js', 'resources/js/formulario-acciones.js', 'resources/js/fase-citodiagnostico.js', 'resources/js/autocomplete.js'])
</body>
</html>
