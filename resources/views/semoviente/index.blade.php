@extends('layouts.dashboard')

@section('page-title', 'Semovientes')

@section('dashboard-content')

<div class="sg-page-header">
    <div>
        <h2>Gestión de Semovientes</h2>
        <p>Administra los registros de nacimientos y semovientes</p>
    </div>
    <a href="{{ route('semoviente.create') }}" class="sg-btn sg-btn-primary">
        <i class="fas fa-plus"></i> Nuevo Semoviente
    </a>
</div>

<div class="sg-card">
    {{-- Skeleton --}}
    <div id="semovienteSkeleton">
        <div class="sg-skeleton sg-skeleton-header"></div>
        @for($i = 0; $i < 7; $i++)
        <div class="sg-skeleton-row">
            <div class="sg-skeleton sg-skeleton-cell" style="width:40px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:130px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:160px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:150px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:120px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:80px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:80px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:70px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:80px"></div>
        </div>
        @endfor
    </div>

    <div class="sg-table-wrapper" id="semovienteTableWrapper" style="display:none">
        <table id="semovientesTable" class="sg-table">
            <thead>
                <tr>
                    <th><i class="fas fa-hashtag"></i> ID</th>
                    <th><i class="fas fa-map-marker-alt"></i> Sede</th>
                    <th><i class="fas fa-university"></i> Centro</th>
                    <th><i class="fas fa-user"></i> Funcionario</th>
                    <th><i class="fas fa-calendar"></i> Fecha Nacimiento</th>
                    <th><i class="fas fa-horse"></i> Tipo</th>
                    <th><i class="fas fa-dna"></i> Raza</th>
                    <th><i class="fas fa-circle"></i> Estado</th>
                    <th><i class="fas fa-cogs"></i> Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($semovientes as $semoviente)
                <tr>
                    <td><span class="sg-badge sg-badge-gray">#{{ $semoviente->id }}</span></td>
                    <td>{{ $semoviente->sede->nom_sede ?? '—' }}</td>
                    <td>{{ $semoviente->sede->centro->nom_centro ?? '—' }}</td>
                    <td>{{ $semoviente->staff->name ?? '—' }}</td>
                    <td>
                        @if($semoviente->birth_date && $semoviente->birth_time)
                            <span class="sg-badge sg-badge-blue">
                                {{ $semoviente->birth_date->format('d/m/Y') }}
                                {{ $semoviente->birth_time->format('H:i') }}
                            </span>
                        @else
                            <span style="color:#9ca3af">—</span>
                        @endif
                    </td>
                    <td>{{ $semoviente->animal_type }}</td>
                    <td>{{ $semoviente->breed }}</td>
                    <td>
                        <span class="sg-badge sg-badge-{{ $semoviente->estado_color ?? 'gray' }}">
                            {{ $semoviente->estado_texto }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('semoviente.edit', $semoviente) }}" class="sg-btn sg-btn-warning" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="sg-btn sg-btn-danger" onclick="deleteSemoviente({{ $semoviente->id }})" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('styles')
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            setTimeout(function () {
                $('#semovienteSkeleton').hide();
                $('#semovienteTableWrapper').show();
                $('#semovientesTable').DataTable({
                    language: { url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json' },
                    order: [[0, 'desc']],
                    dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>rt<"row"<"col-sm-6"i><"col-sm-6"p>>',
                });
            }, 400);
        });

        function deleteSemoviente(id) {
            Swal.fire({
                title: '¿Eliminar semoviente?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                borderRadius: '12px',
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/semoviente/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        Swal.fire({ title: 'Eliminado', text: data.message, icon: 'success', timer: 1500, showConfirmButton: false });
                        setTimeout(() => location.reload(), 1500);
                    });
                }
            });
        }
    </script>
@endpush
