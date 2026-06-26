@extends('layouts.dashboard')

@section('page-title', 'Detalle de Traslado #' . $traslado->id)

@section('dashboard-content')
    @php
        $statusColors = [
            'pendiente'  => 'warning',
            'completada' => 'success',
            'cancelada'  => 'danger',
        ];
        $statusColor = $statusColors[$traslado->status ?? 'pendiente'] ?? 'secondary';
        $riesgoColors = ['bajo' => 'success', 'medio' => 'warning', 'alto' => 'danger'];
    @endphp

    <div class="sg-page-header">
        <div>
            <h2 class="sg-page-title">Traslado <span class="text-muted">#{{ $traslado->id }}</span></h2>
            <p class="sg-page-subtitle">{{ Str::limit($traslado->descripcion, 80) }}</p>
        </div>
        <div class="d-flex gap-2">
            @can('traslados.edit')
                <a href="{{ route('traslados.edit', $traslado->id) }}" class="sg-btn sg-btn-secondary">
                    <i class="fas fa-edit me-1"></i>Editar
                </a>
            @endcan
            <a href="{{ route('traslados.index') }}" class="sg-btn sg-btn-ghost">
                <i class="fas fa-arrow-left me-1"></i>Volver
            </a>
        </div>
    </div>

    {{-- Estado + fechas --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="sg-stat-card">
                <div class="sg-stat-label">Estado</div>
                <span class="sg-badge sg-badge-{{ $statusColor }}">
                    {{ ucfirst($traslado->status ?? 'pendiente') }}
                </span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="sg-stat-card">
                <div class="sg-stat-label">Fecha inicio</div>
                <div class="sg-stat-value">
                    {{ $traslado->fecha_inicio ? $traslado->fecha_inicio->format('d/m/Y') : '—' }}
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="sg-stat-card">
                <div class="sg-stat-label">Fecha fin</div>
                <div class="sg-stat-value">
                    {{ $traslado->fecha_fin ? $traslado->fecha_fin->format('d/m/Y') : '—' }}
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="sg-stat-card">
                <div class="sg-stat-label">Nivel de riesgo</div>
                @if($traslado->nivel_riesgo)
                    <span class="sg-badge sg-badge-{{ $riesgoColors[$traslado->nivel_riesgo] ?? 'secondary' }}">
                        {{ ucfirst($traslado->nivel_riesgo) }}
                    </span>
                @else
                    <span class="text-muted">—</span>
                @endif
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Información general --}}
        <div class="col-lg-6">
            <div class="sg-card">
                <div class="sg-card-header">
                    <i class="fas fa-info-circle me-2 text-primary"></i>Información General
                </div>
                <div class="sg-card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5 text-muted">Solicitante</dt>
                        <dd class="col-sm-7">{{ $traslado->user->name ?? '—' }}</dd>

                        <dt class="col-sm-5 text-muted">Unidad</dt>
                        <dd class="col-sm-7">{{ $traslado->unidad->name ?? '—' }}</dd>

                        <dt class="col-sm-5 text-muted">Descripción</dt>
                        <dd class="col-sm-7">{{ $traslado->descripcion ?? '—' }}</dd>

                        <dt class="col-sm-5 text-muted">Nivel complejidad</dt>
                        <dd class="col-sm-7">{{ $traslado->nivel_complejidad ? ucfirst($traslado->nivel_complejidad) : '—' }}</dd>

                        <dt class="col-sm-5 text-muted">Requiere personal</dt>
                        <dd class="col-sm-7">
                            <span class="sg-badge {{ $traslado->requiere_personal ? 'sg-badge-success' : 'sg-badge-secondary' }}">
                                {{ $traslado->requiere_personal ? 'Sí' : 'No' }}
                            </span>
                        </dd>

                        <dt class="col-sm-5 text-muted">Requiere materiales</dt>
                        <dd class="col-sm-7">
                            <span class="sg-badge {{ $traslado->requiere_materiales ? 'sg-badge-success' : 'sg-badge-secondary' }}">
                                {{ $traslado->requiere_materiales ? 'Sí' : 'No' }}
                            </span>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Origen y destino --}}
        <div class="col-lg-6">
            <div class="sg-card">
                <div class="sg-card-header">
                    <i class="fas fa-map-marker-alt me-2 text-success"></i>Origen y Destino
                </div>
                <div class="sg-card-body">
                    <div class="mb-3">
                        <div class="fw-semibold text-muted mb-1"><i class="fas fa-arrow-right me-1 text-danger"></i>Sede de origen</div>
                        <div>{{ $traslado->centroInicial->nom_centro ?? '—' }} &mdash; {{ $traslado->sedeInicial->nom_sede ?? '—' }}</div>
                    </div>
                    <hr class="my-2">
                    <div>
                        <div class="fw-semibold text-muted mb-1"><i class="fas fa-arrow-right me-1 text-success"></i>Sede de destino</div>
                        <div>{{ $traslado->centroFinal->nom_centro ?? '—' }} &mdash; {{ $traslado->sedeFinal->nom_sede ?? '—' }}</div>
                    </div>
                </div>
            </div>

            {{-- Presupuesto --}}
            <div class="sg-card mt-3">
                <div class="sg-card-header">
                    <i class="fas fa-dollar-sign me-2 text-warning"></i>Presupuesto
                </div>
                <div class="sg-card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-6 text-muted">Solicitado</dt>
                        <dd class="col-sm-6">
                            {{ $traslado->presupuesto_solicitado
                                ? '$' . number_format($traslado->presupuesto_solicitado, 0, ',', '.')
                                : '—' }}
                        </dd>
                        <dt class="col-sm-6 text-muted">Aceptado</dt>
                        <dd class="col-sm-6">
                            {{ $traslado->presupuesto_aceptado
                                ? '$' . number_format($traslado->presupuesto_aceptado, 0, ',', '.')
                                : '—' }}
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Personal asignado --}}
        @if($traslado->personal->isNotEmpty())
        <div class="col-12">
            <div class="sg-card">
                <div class="sg-card-header">
                    <i class="fas fa-users me-2 text-info"></i>Personal Asignado
                    <span class="sg-badge sg-badge-info ms-2">{{ $traslado->personal->count() }}</span>
                </div>
                <div class="sg-card-body p-0">
                    <table class="sg-table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Cargo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($traslado->personal as $persona)
                                <tr>
                                    <td>{{ $persona->name }}</td>
                                    <td>{{ $persona->email }}</td>
                                    <td>{{ $persona->pivot->cargo ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        {{-- Materiales --}}
        @if($traslado->materiales->isNotEmpty())
        <div class="col-12">
            <div class="sg-card">
                <div class="sg-card-header">
                    <i class="fas fa-boxes me-2 text-warning"></i>Materiales
                    <span class="sg-badge sg-badge-warning ms-2">{{ $traslado->materiales->count() }}</span>
                </div>
                <div class="sg-card-body p-0">
                    <table class="sg-table">
                        <thead>
                            <tr>
                                <th>Material</th>
                                <th>Tipo</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($traslado->materiales as $material)
                                <tr>
                                    <td>{{ $material->material_name }}</td>
                                    <td>{{ $material->pivot->tipo ?? '—' }}</td>
                                    <td>{{ $material->pivot->cantidad }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        {{-- Infraestructuras relacionadas --}}
        @if($traslado->infraestructuras->isNotEmpty())
        <div class="col-12">
            <div class="sg-card">
                <div class="sg-card-header">
                    <i class="fas fa-building me-2 text-secondary"></i>Infraestructuras Relacionadas
                    <span class="sg-badge sg-badge-secondary ms-2">{{ $traslado->infraestructuras->count() }}</span>
                </div>
                <div class="sg-card-body p-0">
                    <table class="sg-table">
                        <thead>
                            <tr>
                                <th>Descripción</th>
                                <th>Nivel de riesgo</th>
                                <th>Presupuesto solicitado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($traslado->infraestructuras as $infra)
                                <tr>
                                    <td>{{ Str::limit($infra->descripcion, 60) }}</td>
                                    <td>
                                        <span class="sg-badge sg-badge-{{ $riesgoColors[$infra->nivel_riesgo] ?? 'secondary' }}">
                                            {{ ucfirst($infra->nivel_riesgo ?? '—') }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $infra->presupuesto_solicitado
                                            ? '$' . number_format($infra->presupuesto_solicitado, 0, ',', '.')
                                            : '—' }}
                                    </td>
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
