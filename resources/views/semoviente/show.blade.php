@extends('layouts.dashboard')

@section('page-title', 'Detalle Semoviente')

@section('dashboard-content')

<div class="section-header mb-4">
    <div>
        <h2 class="fw-bold">Semoviente #{{ $semoviente->id }}</h2>
        <p class="text-muted">Información detallada del animal registrado</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('semoviente.edit', $semoviente) }}" class="btn btn-warning shadow-sm">
            <i class="fas fa-edit"></i> Editar
        </a>
        <a href="{{ route('semoviente.index') }}" class="btn btn-outline-secondary shadow-sm">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row g-4">

    <div class="col-md-6">
        <div class="content-card h-100">
            <h5 class="section-title"><i class="fas fa-paw"></i> Información General</h5>
            <table class="detail-table">
                <tr>
                    <th>Tipo de Animal</th>
                    <td>{{ $semoviente->animal_type ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Raza</th>
                    <td>{{ $semoviente->breed ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Género</th>
                    <td>
                        @if($semoviente->gender === 'M')
                            <span class="sg-badge sg-badge-blue">Macho</span>
                        @elseif($semoviente->gender === 'F')
                            <span class="sg-badge sg-badge-gray">Hembra</span>
                        @else
                            {{ $semoviente->gender ?? '—' }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Color</th>
                    <td>{{ $semoviente->color ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Peso</th>
                    <td>{{ $semoviente->weight ? $semoviente->weight . ' kg' : '—' }}</td>
                </tr>
                <tr>
                    <th>Estado</th>
                    <td>
                        <span class="sg-badge {{ $semoviente->status === 'activo' ? 'sg-badge-green' : 'sg-badge-red' }}">
                            {{ ucfirst($semoviente->status ?? 'desconocido') }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Valor Estimado</th>
                    <td>{{ $semoviente->estimated_value ? '$' . number_format($semoviente->estimated_value, 0, ',', '.') : '—' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="col-md-6">
        <div class="content-card h-100">
            <h5 class="section-title"><i class="fas fa-birthday-cake"></i> Nacimiento</h5>
            <table class="detail-table">
                <tr>
                    <th>Fecha de Nacimiento</th>
                    <td>{{ $semoviente->birth_date ? \Carbon\Carbon::parse($semoviente->birth_date)->format('d/m/Y') : '—' }}</td>
                </tr>
                <tr>
                    <th>Hora de Nacimiento</th>
                    <td>{{ $semoviente->birth_time ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Tipo de Parto</th>
                    <td>{{ $semoviente->birth_type ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Área de Nacimiento</th>
                    <td>{{ $semoviente->birth_area ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Paquete Madre</th>
                    <td>{{ $semoviente->mother_package ?? '—' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="col-md-6">
        <div class="content-card h-100">
            <h5 class="section-title"><i class="fas fa-map-marker-alt"></i> Ubicación y Responsable</h5>
            <table class="detail-table">
                <tr>
                    <th>Departamento Responsable</th>
                    <td>{{ $semoviente->responsible_department ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Centro</th>
                    <td>{{ $semoviente->centro->nom_centro ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Sede</th>
                    <td>{{ $semoviente->sede->nom_sede ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Ambiente de Entrenamiento</th>
                    <td>{{ $semoviente->training_environment ?? '—' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="col-md-6">
        <div class="content-card h-100">
            <h5 class="section-title"><i class="fas fa-history"></i> Registro</h5>
            <table class="detail-table">
                <tr>
                    <th>Creado</th>
                    <td>{{ $semoviente->created_at?->format('d/m/Y H:i') ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Actualizado</th>
                    <td>{{ $semoviente->updated_at?->format('d/m/Y H:i') ?? '—' }}</td>
                </tr>
            </table>
        </div>
    </div>

</div>

@endsection

@push('styles')
<style>
    .section-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:0; }
    .section-header h2 { font-size:24px; font-weight:700; color:#111827; margin:0; }
    .section-header p { font-size:13px; color:#6b7280; margin:4px 0 0; }
    .content-card { background:#fff; padding:24px 28px; border-radius:14px; box-shadow:0 2px 10px rgba(0,0,0,.07); border:1px solid #f0fdf4; }
    .section-title { font-size:15px; font-weight:700; color:#16a34a; margin-bottom:18px; }
    .detail-table { width:100%; border-collapse:collapse; }
    .detail-table tr { border-bottom:1px solid #f3f4f6; }
    .detail-table tr:last-child { border-bottom:none; }
    .detail-table th { padding:10px 0; font-size:13px; color:#6b7280; font-weight:600; width:50%; vertical-align:top; }
    .detail-table td { padding:10px 0; font-size:13.5px; color:#111827; font-weight:500; }
</style>
@endpush
