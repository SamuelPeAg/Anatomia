<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Informes — Anatomía MEDAC</title>
    @vite(['resources/css/principal.css', 'resources/css/revision.css', 'resources/css/alerts.css'])
    <style>
        .patient-header {
            background: white;
            padding: 2rem;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .patient-info h1 {
            font-size: 1.5rem;
            color: #1e293b;
            margin: 0;
        }
        .patient-info p {
            color: #64748b;
            margin: 0.25rem 0 0 0;
        }
        .informe-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .informe-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .informe-main h3 {
            font-size: 1.125rem;
            margin: 0;
            color: #1e293b;
        }
        .informe-meta {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: #64748b;
        }
        .badge-estado {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .estado-completo { background: #dcfce7; color: #166534; }
        .estado-incompleto { background: #fef9c3; color: #854d0e; }
        .boton-ver {
            background: #f1f5f9;
            color: #475569;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
        }
        .boton-ver:hover {
            background: #e2e8f0;
        }
        .vacio {
            text-align: center;
            padding: 4rem 2rem;
            color: #64748b;
        }
    </style>
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
                                <span class="ayuda-campo" style="font-size: 0.75rem;">En proceso...</span>
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
