@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 2rem 5%;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-secondary mb-1">Expedientes de Pacientes</h1>
            <p class="text-muted">Gestión de historiales clínicos y muestras asociadas.</p>
        </div>
        <a href="{{ route('inicio') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver al Inicio
        </a>
    </div>

    @if($expedientes->count() > 0)
        <div class="row g-4">
            @foreach($expedientes as $expediente)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle me-3">
                                    <i class="bi bi-person-vcard fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="card-title fw-bold mb-0 text-truncate" style="max-width: 200px;" title="{{ $expediente->nombre }}">
                                        {{ $expediente->nombre }}
                                    </h5>
                                    <small class="text-muted">{{ $expediente->correo ?? 'Sin correo' }}</small>
                                </div>
                            </div>
                            
                            <hr class="my-3 opacity-10">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-files me-1"></i> {{ $expediente->informes_count }} Informes
                                </span>
                                <span class="text-xs text-muted">
                                    Último: {{ $expediente->updated_at->diffForHumans() }}
                                </span>
                            </div>

                            <a href="{{ route('expedientes.show', $expediente) }}" class="btn btn-primary w-100 mt-auto rounded-3">
                                Ver Historial Completo
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $expedientes->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-3 text-muted opacity-50">
                <i class="bi bi-inbox fs-1"></i>
            </div>
            <h3 class="fw-bold text-secondary">No hay expedientes registradas</h3>
            <p class="text-muted">Los expedientes se crean automáticamente al asignar un correo a un informe.</p>
        </div>
    @endif
</div>
@endsection
