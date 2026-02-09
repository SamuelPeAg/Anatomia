@php
    $esEdicion = isset($informe);
    $faseActual = request('fase') ?? ($numeroFase ?? 1);
    $fasesCompletas = [
        1 => $esEdicion && !empty($informe->recepcion_observaciones),
        2 => $esEdicion && !empty($informe->procesamiento_tipo),
        3 => $esEdicion && !empty($informe->tincion_tipo),
        4 => $esEdicion && !empty($informe->citodiagnostico)
    ];
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $esEdicion ? 'Editar' : 'Nuevo' }} informe — DAVANTE</title>
    @vite(['resources/css/nuevoinforme.css', 'resources/css/principal.css', 'resources/css/alerts.css'])
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
                    <h1 class="titulo-pagina">
                        @if(isset($informe))
                            Continuar Informe <span class="codigo-referencia">#{{ $informe->codigo_identificador }}</span>
                        @else
                            Nuevo Informe
                        @endif
                    </h1>
                    <p class="subtitulo-pagina">
                        @if(isset($informe))
                            Completando el registro iniciado el {{ $informe->created_at->format('d/m/Y') }}.
                        @else
                            Inicia un nuevo registro secuencial.
                        @endif
                    </p>
                </div>

                <div class="cabecera-derecha">
                    @if(isset($informe))
                        <span class="etiqueta etiqueta-edicion">Modo Edición</span>
                    @else
                        <span class="etiqueta etiqueta-aviso">Borrador Nuevo</span>
                    @endif
                </div>
            </header>

            <nav class="pasos" aria-label="Progreso">
                <button class="paso paso-activo" type="button" data-paso="1"><span class="paso-numero">1</span><span class="paso-texto">Recepción</span></button>
                <button class="paso" type="button" data-paso="2"><span class="paso-numero">2</span><span class="paso-texto">Procesamiento</span></button>
                <button class="paso" type="button" data-paso="3"><span class="paso-numero">3</span><span class="paso-texto">Tinción</span></button>
                <button class="paso" type="button" data-paso="4"><span class="paso-numero">4</span><span class="paso-texto">Citodiagnóstico</span></button>
            </nav>

            <article class="tarjeta">
                <div class="tarjeta-cabecera">
                    <h2 class="tarjeta-titulo" id="tituloFase">Cargando fase...</h2>
                    <p class="tarjeta-ayuda">Los campos con <span class="obligatorio">*</span> son obligatorios.</p>
                </div>

                <div class="tarjeta-cuerpo">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                    <x-fase-recepcion :informe="$informe" />
                    <x-fase-procesamiento :informe="$informe" />
                    <x-fase-tincion :informe="$informe" />
                    <x-fase-citodiagnostico :informe="$informe" />
                </div>
            </article>
        </section>
    </main>

    <x-modal-confirm />
    <div id="toast-container" class="toast-container"></div>
    <x-footer />

    @vite(['resources/js/formulario.js'])
</body>
</html>
