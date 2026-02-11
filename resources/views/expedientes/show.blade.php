@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 2rem 5%;">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="h2 fw-bold text-secondary mb-1">Historia Clínica: {{ $expediente->nombre }}</h1>
            <p class="text-muted"><i class="bi bi-person-badge me-2"></i>Expediente #{{ $expediente->id }} • {{ $expediente->correo ?? 'Sin contacto' }}</p>
        </div>
        <a href="{{ route('expedientes.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver al Listado
        </a>
    </div>

    <!-- Timeline Layout -->
    <div class="timeline position-relative">
        @forelse($expediente->informes as $informe)
            <div class="row g-0 mb-4 position-relative">
                <!-- Timeline Marker -->
                <div class="col-auto text-center d-none d-lg-block position-relative" style="width: 50px;">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                         style="width: 32px; height: 32px; z-index: 2; position: relative;">
                         <i class="bi bi-file-text"></i>
                    </div>
                    @if(!$loop->last)
                        <div class="bg-light position-absolute top-0 bottom-0 start-50 translate-middle-x" style="width: 2px; z-index: 1;"></div>
                    @endif
                </div>

                <!-- Content Card -->
                <div class="col">
                    <div class="card border-0 shadow-sm rounded-4 ms-lg-3">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <h5 class="fw-bold text-primary mb-1">
                                        {{ $informe->tipo->nombre ?? 'Informe General' }}
                                    </h5>
                                    <span class="badge bg-light text-secondary border">
                                        #{{ $informe->codigo_identificador }}
                                    </span>
                                    <span class="text-muted small ms-2">
                                        {{ $informe->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                                <div>
                                    <span class="badge {{ $informe->estado == 'completo' ? 'bg-success' : 'bg-warning text-dark' }} rounded-pill px-3 py-2">
                                        {{ ucfirst($informe->estado) }}
                                    </span>
                                </div>
                            </div>

                            <p class="text-muted mb-3 small">
                                <strong>Órgano / Muestra:</strong> {{ $informe->recepcion_organo ?? 'N/A' }}
                                <br>
                                <strong>Observaciones:</strong> {{ Str::limit($informe->recepcion_observaciones, 100) ?: 'Sin observaciones' }}
                            </p>

                            <div class="d-flex gap-2">
                                <a href="{{ route('informes.edit', $informe) }}" class="btn btn-sm btn-outline-primary rounded-3 px-3">
                                    <i class="bi bi-pencil-square me-1"></i> Editar Informe
                                </a>
                                {{-- Aquí podrías añadir un botón para ver PDF si existiera --}}
                            </div>

                            <!-- Image Preview Strip -->
                            @if($informe->imagenes->where('fase', 'microscopio')->count() > 0)
                                <div class="mt-3 pt-3 border-top d-flex gap-2 overflow-auto pb-2">
                                    @foreach($informe->imagenes->where('fase', 'microscopio') as $img)
                                        <div class="rounded-3 overflow-hidden border" style="min-width: 60px; width: 60px; height: 60px;">
                                            <img src="{{ asset('storage/' . $img->ruta) }}" class="w-100 h-100 object-fit-cover" alt="Microscopio">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 opacity-50 mb-3 d-block"></i>
                Este expediente no tiene informes asociados.
            </div>
        @endforelse
    </div>
</div>
@endsection
