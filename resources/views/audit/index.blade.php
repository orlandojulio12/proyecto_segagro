@extends('layouts.dashboard')

@section('page-title', 'Registro de Auditoría')

@section('dashboard-content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Registro de Auditoría</h4>
</div>

{{-- Filtros --}}
<form method="GET" action="{{ route('audit.index') }}" class="card p-3 mb-4 shadow-sm">
    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Usuario</label>
            <select name="user_id" class="form-select form-select-sm">
                <option value="">Todos</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Módulo</label>
            <select name="auditable_type" class="form-select form-select-sm">
                <option value="">Todos</option>
                @foreach ($modelTypes as $type)
                    <option value="{{ $type }}" @selected(request('auditable_type') == $type)>
                        {{ $type }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Acción</label>
            <select name="event" class="form-select form-select-sm">
                <option value="">Todas</option>
                <option value="created" @selected(request('event') === 'created')>Creado</option>
                <option value="updated" @selected(request('event') === 'updated')>Actualizado</option>
                <option value="deleted" @selected(request('event') === 'deleted')>Eliminado</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Desde</label>
            <input type="date" name="from" class="form-control form-control-sm" value="{{ request('from') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label">Hasta</label>
            <input type="date" name="to" class="form-control form-control-sm" value="{{ request('to') }}">
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button type="submit" class="btn btn-success btn-sm w-100">Filtrar</button>
        </div>
    </div>
</form>

{{-- Tabla --}}
<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Acción</th>
                    <th>Módulo</th>
                    <th>ID</th>
                    <th>IP</th>
                    <th>Cambios</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($audits as $audit)
                    <tr>
                        <td class="text-nowrap">{{ $audit->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $audit->user?->name ?? 'Sistema' }}</td>
                        <td>
                            @php
                                $badge = match($audit->event) {
                                    'created' => 'bg-success',
                                    'updated' => 'bg-warning text-dark',
                                    'deleted' => 'bg-danger',
                                    default   => 'bg-secondary',
                                };
                                $label = match($audit->event) {
                                    'created' => 'Creado',
                                    'updated' => 'Actualizado',
                                    'deleted' => 'Eliminado',
                                    default   => ucfirst($audit->event),
                                };
                            @endphp
                            <span class="badge {{ $badge }}">{{ $label }}</span>
                        </td>
                        <td>{{ class_basename($audit->auditable_type) }}</td>
                        <td>{{ $audit->auditable_id }}</td>
                        <td class="text-muted small">{{ $audit->ip_address }}</td>
                        <td>
                            @if ($audit->old_values || $audit->new_values)
                                <button class="btn btn-sm btn-outline-secondary py-0"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#audit-{{ $audit->id }}">
                                    Ver
                                </button>
                                <div class="collapse mt-1" id="audit-{{ $audit->id }}">
                                    <div class="d-flex gap-2">
                                        @if ($audit->old_values)
                                            <div class="flex-fill">
                                                <small class="text-danger fw-bold">Antes</small>
                                                <pre class="small mb-0">{{ json_encode($audit->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </div>
                                        @endif
                                        @if ($audit->new_values)
                                            <div class="flex-fill">
                                                <small class="text-success fw-bold">Después</small>
                                                <pre class="small mb-0">{{ json_encode($audit->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No hay registros de auditoría</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $audits->links() }}
    </div>
</div>

@endsection
