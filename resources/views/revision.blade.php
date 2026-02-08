<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Revisión de Informes — Anatomía MEDAC</title>

    <!-- Estilos y Scripts con Vite -->
    @vite(['resources/css/principal.css', 'resources/css/revision.css', 'resources/css/alerts.css'])
</head>
<body>
    <x-header />

    <main>
        <div class="revision-container">
            <div class="revision-header">
                <h1 class="revision-title">Revisión de Informes</h1>
                <a href="{{ route('nuevo informe') }}" class="btn-premium">Nuevo Informe</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success mt-2 mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="reports-table-wrapper">
                @if($informes->isEmpty())
                    <div class="empty-state">
                        <p>No hay informes registrados todavía.</p>
                    </div>
                @else
                    <table class="reports-table">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Tipo</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($informes as $informe)
                                <tr>
                                    <td><strong>{{ $informe->codigo_identificador }}</strong></td>
                                    <td>{{ $informe->tipo->nombre ?? 'N/A' }}</td>
                                    <td>{{ $informe->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $informe->estado }}">
                                            {{ ucfirst($informe->estado) }}
                                        </span>
                                        @if($informe->estado == 'incompleto')
                                            <span class="next-phase">Pendiente: {{ $informe->siguiente_fase }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('informes.edit', $informe) }}?fase={{ $informe->fase_n }}" class="btn-action btn-edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            <span>Editar Fases</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
