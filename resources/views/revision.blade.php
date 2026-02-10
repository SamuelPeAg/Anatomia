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

            <!-- Barra de Filtros -->
            <div class="filters-bar" style="margin-bottom: 1.5rem; display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; background: #f9fafb; padding: 1rem; border-radius: 0.5rem; border: 1px solid #e5e7eb;">
                <form action="{{ route('revision') }}" method="GET" style="display: flex; gap: 0.75rem; align-items: center;">
                    <label for="fecha" style="font-weight: 600; color: #374151; font-size: 0.95rem;">Filtrar por fecha:</label>
                    <input type="date" name="fecha" id="fecha" 
                           value="{{ request('fecha', \Carbon\Carbon::now()->format('Y-m-d')) }}" 
                           style="padding: 0.4rem 0.6rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-family: inherit;">
                    <button type="submit" style="background-color: #2563eb; color: white; padding: 0.4rem 1rem; border: none; border-radius: 0.375rem; cursor: pointer; font-weight: 500;">
                        Ver día
                    </button>
                </form>

                <div style="width: 1px; height: 24px; background: #d1d5db; margin: 0 0.5rem;"></div>

                @if(!request('mostrar_todos'))
                    <a href="{{ route('revision', ['mostrar_todos' => 1]) }}" 
                       style="text-decoration: none; color: #4b5563; font-weight: 500; font-size: 0.95rem; display: flex; align-items: center; gap: 0.3rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        Ver Historial Completo
                    </a>
                @else
                    <a href="{{ route('revision') }}" 
                       style="text-decoration: none; color: #2563eb; font-weight: 600; font-size: 0.95rem; display: flex; align-items: center; gap: 0.3rem;">
                        ← Volver a Informes de Hoy
                    </a>
                @endif
            </div>

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
