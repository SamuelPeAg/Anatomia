<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Informes — Anatomía MEDAC</title>
    @vite(['resources/css/principal.css', 'resources/css/revision.css', 'resources/css/alerts.css', 'resources/css/paciente-informes.css'])

</head>
<body>
    <x-header />

    <main class="pagina">
        <div class="contenedor">
            <header class="patient-header">
                <div class="patient-info">
                    <h1>Hola, {{ $expediente->nombre }}</h1>
                    <p>Historial de informes médicos asociados a {{ $expediente->correo }}</p>
                </div>
            </header>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <section class="lista-informes">
                @forelse($informes as $informe)
                    <div class="informe-card">
                        <div class="informe-main">
                            <h3>{{ $informe->tipo->nombre }} - #{{ $informe->codigo_identificador }}</h3>
                            <div class="informe-meta">
                                <span>Fecha: {{ $informe->created_at->format('d/m/Y') }}</span>
                                <span class="badge-estado {{ $informe->estado == 'completo' ? 'estado-completo' : 'estado-incompleto' }}">
                                    {{ ucfirst($informe->estado) }}
                                </span>
                            </div>
                        </div>
                        <div class="informe-acciones">
                            @if($informe->estado == 'completo')
                                <a href="#" class="boton-ver">Descargar Resultados</a>
                            @else
                                <span class="ayuda-campo ayuda-campo-texto-pequeno">En proceso...</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="vacio">
                        <p>No se encontraron informes para este expediente.</p>
                    </div>
                @endforelse
            </section>
        </div>
    </main>

    <x-footer />
</body>
</html>
