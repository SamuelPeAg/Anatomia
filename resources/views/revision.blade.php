<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Revisión de Informes — Anatomía MEDAC</title>

    <!-- Estilos y Scripts con Vite -->
    @vite(['resources/css/principal.css', 'resources/css/revision.css', 'resources/css/alerts.css'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .swal2-toast-left { margin-left: 1rem !important; }
        
        /* Arreglo de emergencia para botones amontonados */
        .acciones-horizontal {
            display: flex !important;
            flex-direction: row !important;
            gap: 10px !important;
            align-items: center !important;
            justify-content: flex-end !important;
        }
        .btn-icon {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 32px !important;
            height: 32px !important;
            border-radius: 8px !important;
            background: #f1f5f9 !important;
            border: 1px solid #e2e8f0 !important;
            padding: 0 !important;
            cursor: pointer !important;
        }
        .btn-validar-mini {
            display: inline-flex !important;
            align-items: center !important;
            gap: 5px !important;
            background: #eef2ff !important;
            color: #4338ca !important;
            border: 1px solid #c7d2fe !important;
            padding: 4px 8px !important;
            font-size: 11px !important;
            font-weight: bold !important;
            border-radius: 4px !important;
            cursor: pointer !important;
            margin-top: 5px !important;
        }

        /* Arreglo para paginación */
        .pagination-premium {
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0.5rem !important;
            background: white !important;
            padding: 0.5rem !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1) !important;
            border: 1px solid #e2e8f0 !important;
            margin-top: 2rem !important;
        }
        .pagination-premium .page-numbers {
            display: flex !important;
            flex-direction: row !important;
            gap: 0.25rem !important;
        }
        .pagination-premium .page-link {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 36px !important;
            height: 36px !important;
            border-radius: 8px !important;
            text-decoration: none !important;
            color: #1e293b !important;
            font-weight: 600 !important;
            font-size: 0.85rem !important;
            border: 1px solid transparent !important;
        }
        .pagination-premium .page-link.active {
            background: #0234AB !important;
            color: white !important;
        }
        .pagination-premium a.page-link:hover {
            background: #f1f5f9 !important;
        }
    </style>
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

            <!-- Barra de Filtros -->
            <div class="filters-bar">
                <form action="{{ route('revision') }}" method="GET" class="filters-form">
                    <div class="filter-group">
                        <label for="fecha" class="filters-label">Fecha:</label>
                        <input type="date" name="fecha" id="fecha" 
                               value="{{ request('fecha', \Carbon\Carbon::now()->format('Y-m-d')) }}" 
                               class="filters-input">
                    </div>
                    
                    <div class="filter-group">
                        <label for="search" class="filters-label">Buscador:</label>
                        <input type="text" name="search" id="search" 
                               value="{{ request('search') }}" 
                               placeholder="Nombre, ID o Código..."
                               class="filters-input">
                    </div>

                    <button type="submit" class="filters-btn">
                        Filtrar
                    </button>
                </form>

                <div class="filters-divider"></div>

                @if(!request('mostrar_todos'))
                    <a href="{{ route('revision', ['mostrar_todos' => 1]) }}" class="filters-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        Ver Historial Completo
                    </a>
                @else
                    <a href="{{ route('revision') }}" class="filters-link active">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 14 4 9 9 4"></polyline><path d="M20 20v-7a4 4 0 0 0-4-4H4"></path></svg>
                        Volver a Hoy
                    </a>
                @endif
            </div>

            <div class="reports-table-wrapper">
                @if($informes->isEmpty())
                    <div class="empty-state">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 1rem;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                        <p>No hay informes que coincidan con los filtros.</p>
                        <a href="{{ route('nuevo informe') }}" class="btn-premium">Crear Nuevo Informe</a>
                    </div>
                @else
                    <table class="reports-table">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Paciente</th>
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
                                    <td>
                                        <div style="font-weight: 600;">{{ $informe->expediente->nombre ?? 'Anónimo' }}</div>
                                        <div style="font-size: 0.8rem; color: #64748b;">ID: {{ $informe->expediente->id ?? 'N/A' }}</div>
                                    </td>
                                    <td>{{ $informe->tipo->nombre ?? 'N/A' }}</td>
                                    <td>{{ $informe->created_at->format('d/m/Y') }}</td>
                                    <td class="estado-celda">
                                        <div class="status-wrapper">
                                            <span class="status-badge status-{{ $informe->estado }}">
                                                {{ ucfirst($informe->estado) }}
                                            </span>
                                            
                                            @if(auth()->user()->isAdmin() && $informe->estado === 'completo')
                                                <form action="{{ route('informes.revisar', $informe) }}" method="POST" class="form-revisar inline-form">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="btn-validar-mini" title="Validar y cerrar informe">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                                        <span>Validar</span>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                        @if($informe->estado == 'incompleto')
                                            <span class="next-phase">Pendiente: {{ $informe->siguiente_fase }}</span>
                                        @endif
                                    </td>
                                    <td class="acciones-celda">
                                        <div class="acciones-horizontal">
                                            @if($informe->estado !== 'revisado')
                                                <a href="{{ route('informes.edit', $informe) }}?fase={{ $informe->fase_n }}" class="btn-icon btn-edit" title="Editar fases">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                </a>
                                            @else
                                                <a href="{{ route('informes.edit', $informe) }}?fase=1" class="btn-icon btn-view" title="Ver informe (Lectura)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                </a>
                                            @endif

                                            @if(auth()->user()->isAdmin())
                                                <form action="{{ route('informes.destroy', $informe) }}" method="POST" class="form-borrar inline-form">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn-icon btn-delete" title="Borrar informe">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6m5 0V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"></path></svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <div class="pagination-wrapper">
                {{ $informes->links('vendor.pagination.premium') }}
            </div>
        </div>
    </main>

    <x-footer />

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Confirmación para VALIDAR
            document.querySelectorAll('.form-revisar').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Validar este informe?',
                        text: "Una vez validado, el informe quedará cerrado y no podrá ser editado.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#0234AB',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Sí, validar ahora',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Confirmación para BORRAR
            document.querySelectorAll('.form-borrar').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Eliminar informe?',
                        text: "Esta acción no se puede deshacer.",
                        icon: 'error',
                        showCancelButton: true,
                        confirmButtonColor: '#DC2626',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
