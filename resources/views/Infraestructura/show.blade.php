@extends('layouts.dashboard')

@section('page-title', 'Detalle de Infraestructura')

@section('dashboard-content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Detalle de Infraestructura</h4>
        <p class="text-muted mb-0">Necesidad #{{ $infraestructura->id }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('infraestructura.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
        @can('infraestructura.edit')
        <a href="{{ route('infraestructura.edit', $infraestructura) }}" class="btn btn-warning btn-sm">
            <i class="fas fa-edit me-1"></i> Editar
        </a>
        @endcan
        @can('infraestructura.delete')
        <form action="{{ route('infraestructura.destroy', $infraestructura) }}" method="POST" class="d-inline">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="fas fa-trash me-1"></i> Eliminar
            </button>
        </form>
        @endcan
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Banner de riesgo --}}
<div class="card shadow-sm mb-4 border-0" style="border-left: 5px solid
    {{ $infraestructura->nivel_riesgo == 3 ? '#dc3545' : ($infraestructura->nivel_riesgo == 2 ? '#ffc107' : '#198754') }} !important;">
    <div class="card-body d-flex align-items-center gap-3">
        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
            style="width:56px;height:56px;font-size:22px;background:
            {{ $infraestructura->nivel_riesgo == 3 ? '#dc3545' : ($infraestructura->nivel_riesgo == 2 ? '#ffc107' : '#198754') }}">
            {{ $infraestructura->nivel_riesgo }}
        </div>
        <div>
            <h5 class="mb-0 fw-bold">Nivel de Riesgo:
                <span class="badge" style="background:
                    {{ $infraestructura->nivel_riesgo == 3 ? '#dc3545' : ($infraestructura->nivel_riesgo == 2 ? '#ffc107' : '#198754') }}">
                    {{ $infraestructura->nivel_riesgo == 3 ? 'Alto' : ($infraestructura->nivel_riesgo == 2 ? 'Medio' : 'Bajo') }}
                </span>
            </h5>
            <span class="text-muted">Tipo: <strong>{{ $infraestructura->tipo_necesidad }}</strong></span>
        </div>
        <div class="ms-auto text-end">
            @if($infraestructura->requiere_traslado)
                <span class="badge bg-info fs-6"><i class="fas fa-truck me-1"></i>Requiere traslado</span>
            @else
                <span class="badge bg-secondary fs-6">Sin traslado</span>
            @endif
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- Información General --}}
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-success text-white">
                <i class="fas fa-info-circle me-2"></i>Información General
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <th class="text-muted" style="width:45%">Funcionario:</th>
                        <td>{{ $infraestructura->funcionario->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Unidad:</th>
                        <td>{{ $infraestructura->dependencia->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Tipo de necesidad:</th>
                        <td>{{ $infraestructura->tipo_necesidad }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Nivel de complejidad:</th>
                        <td>
                            <span class="badge bg-secondary">
                                {{ $infraestructura->nivel_complejidad == 3 ? 'Alta' : ($infraestructura->nivel_complejidad == 2 ? 'Media' : 'Baja') }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Ambiente / Salón:</th>
                        <td>{{ $infraestructura->room->name ?? 'N/A' }}</td>
                    </tr>
                    @if($infraestructura->fecha_inicio)
                    <tr>
                        <th class="text-muted">Fecha inicio:</th>
                        <td>{{ $infraestructura->fecha_inicio->format('d/m/Y') }}</td>
                    </tr>
                    @endif
                    @if($infraestructura->fecha_fin)
                    <tr>
                        <th class="text-muted">Fecha fin:</th>
                        <td>{{ $infraestructura->fecha_fin->format('d/m/Y') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th class="text-muted">Registrado:</th>
                        <td>{{ $infraestructura->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Ubicación --}}
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-success text-white">
                <i class="fas fa-map-marker-alt me-2"></i>Ubicación
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <th class="text-muted" style="width:45%">Centro inicial:</th>
                        <td>{{ $infraestructura->centro->nom_centro ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Sede inicial:</th>
                        <td>{{ $infraestructura->sede->nom_sede ?? 'N/A' }}</td>
                    </tr>
                    @if($infraestructura->requiere_traslado)
                    <tr><td colspan="2"><hr class="my-1"></td></tr>
                    <tr>
                        <th class="text-muted">Centro final:</th>
                        <td>{{ optional($infraestructura->needTransfers->first()?->centroFinal)->nom_centro ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Sede final:</th>
                        <td>{{ optional($infraestructura->needTransfers->first()?->sedeFinal)->nom_sede ?? 'N/A' }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    {{-- Descripción y motivo --}}
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <i class="fas fa-file-alt me-2"></i>Descripción y Motivo
            </div>
            <div class="card-body row g-3">
                <div class="col-md-6">
                    <h6 class="fw-semibold">Descripción</h6>
                    <p class="text-muted mb-0">{{ $infraestructura->descripcion }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-semibold">Motivo de Necesidad</h6>
                    <p class="text-muted mb-0">{{ $infraestructura->motivo_necesidad }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Presupuesto --}}
    @if($infraestructura->presupuesto_solicitado || $infraestructura->presupuesto_aceptado)
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <i class="fas fa-dollar-sign me-2"></i>Presupuesto
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    @if($infraestructura->presupuesto_solicitado)
                    <tr>
                        <th class="text-muted">Solicitado:</th>
                        <td class="text-primary fw-bold">
                            ${{ number_format($infraestructura->presupuesto_solicitado, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endif
                    @if($infraestructura->presupuesto_aceptado)
                    <tr>
                        <th class="text-muted">Aceptado:</th>
                        <td class="text-success fw-bold">
                            ${{ number_format($infraestructura->presupuesto_aceptado, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endif
                    @if($infraestructura->fuente_financiacion)
                    <tr>
                        <th class="text-muted">Fuente:</th>
                        <td>{{ $infraestructura->fuente_financiacion }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Imagen --}}
    @if($infraestructura->imagen)
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <i class="fas fa-image me-2"></i>Imagen
            </div>
            <div class="card-body text-center">
                <img src="{{ Storage::url($infraestructura->imagen) }}"
                    alt="Imagen de infraestructura"
                    class="img-fluid rounded shadow"
                    style="max-height: 300px; object-fit: cover;">
            </div>
        </div>
    </div>
    @endif

    {{-- Traslados relacionados --}}
    @if($infraestructura->needTransfers->isNotEmpty())
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <i class="fas fa-truck me-2"></i>Traslados Relacionados
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Estado</th>
                            <th>Centro Origen</th>
                            <th>Sede Origen</th>
                            <th>Centro Destino</th>
                            <th>Sede Destino</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($infraestructura->needTransfers as $traslado)
                        <tr>
                            <td>{{ $traslado->id }}</td>
                            <td>
                                <span class="badge
                                    {{ $traslado->status === 'completada' ? 'bg-success' :
                                       ($traslado->status === 'cancelada' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                    {{ ucfirst($traslado->status) }}
                                </span>
                            </td>
                            <td>{{ $traslado->centroInicial->nom_centro ?? 'N/A' }}</td>
                            <td>{{ $traslado->sedeInicial->nom_sede ?? 'N/A' }}</td>
                            <td>{{ $traslado->centroFinal->nom_centro ?? 'N/A' }}</td>
                            <td>{{ $traslado->sedeFinal->nom_sede ?? 'N/A' }}</td>
                            <td>{{ optional($traslado->fecha_inicio)->format('d/m/Y') ?? 'N/A' }}</td>
                            <td>{{ optional($traslado->fecha_fin)->format('d/m/Y') ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>

@endsection
