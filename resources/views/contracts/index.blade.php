{{-- resources/views/contracts/index.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Gestión de Contratos')

@section('dashboard-content')
    <div class="section-header mb-4">
        <div>
            <h2 class="fw-bold">Gestión de Contratación</h2>
            <p class="text-muted">Administra los contratos de la entidad</p>
        </div>
        <a href="{{ route('contracts.create') }}" class="btn btn-success shadow-sm">
            <i class="fas fa-plus me-2"></i>Nuevo Contrato
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <!-- Filtros rápidos -->
    <div class="row mt-4">
    <div class="col-md-6 mb-3">
        <div class="stat-card-mini">
            <div class="stat-icon bg-success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $contracts->where('is_active', true)->count() }}</div>
                <div class="stat-label">Contratos Activos</div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="stat-card-mini">
            <div class="stat-icon bg-warning">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $contracts->where('is_pending', true)->count() }}</div>
                <div class="stat-label">Pendientes</div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="stat-card-mini">
            <div class="stat-icon bg-danger">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $contracts->where('is_expired', true)->count() }}</div>
                <div class="stat-label">Vencidos</div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="stat-card-mini">
            <div class="stat-icon bg-info">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">${{ number_format($contracts->sum('total_value'), 0, ',', '.') }}</div>
                <div class="stat-label">Valor Total</div>
            </div>
        </div>
    </div>
</div>


    <div class="content-card">
        <div class="table-header mb-3">
            <h5 class="mb-0"><i class="fas fa-file-contract me-2"></i>Listado de Contratos</h5>
        </div>

        <div class="table-responsive">
            <table class="table table-modern" id="contractsTable">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag me-1"></i>N° Contrato</th>
                        <th><i class="fas fa-user-tie me-1"></i>Contratista</th>
                        <th><i class="fas fa-handshake me-1"></i>Modalidad</th>
                        <th><i class="fas fa-file-alt me-1"></i>Tipo</th>
                        <th><i class="fas fa-building me-1"></i>Sede</th>
                        <th><i class="fas fa-calendar me-1"></i>Inicio</th>
                        <th><i class="fas fa-calendar-check me-1"></i>Fin</th>
                        <th><i class="fas fa-dollar-sign me-1"></i>Valor Total</th>
                        <th><i class="fas fa-info-circle me-1"></i>Estado</th>
                        <th><i class="fas fa-cogs me-1"></i>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contracts as $contract)
                        <tr>
                            <td><strong class="text-primary">{{ $contract->contract_number }}</strong></td>
                            <td>
                                <div>{{ $contract->contractor_name }}</div>
                                <small class="text-muted">NIT: {{ $contract->contractor_nit }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $contract->hiringModality->modality_name ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <div>{{ $contract->contractType->type_name ?? 'N/A' }}</div>
                                <small
                                    class="text-muted">{{ $contract->contractType->dependencia->nombre ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <div>{{ $contract->sede->nom_sede ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $contract->sede->centro->nom_centro ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ $contract->start_date->format('d/m/Y') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ $contract->final_end_date->format('d/m/Y') }}
                                </span>
                            </td>
                            <td>
                                <strong class="text-success">
                                    ${{ number_format($contract->total_value, 2, ',', '.') }}
                                </strong>
                            </td>
                            <td>
                                <span class="badge {{ $contract->status_badge_class }}">
                                    {{ $contract->status }}
                                </span>
                                @if ($contract->is_active && $contract->days_remaining <= 30)
                                    <br><small class="text-warning">
                                        <i class="fas fa-clock"></i> {{ $contract->days_remaining }} días
                                    </small>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('contracts.show', $contract) }}" class="btn btn-sm btn-info"
                                        title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('contracts.edit', $contract) }}" class="btn btn-sm btn-warning"
                                        title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button
                                        onclick="deleteContract({{ $contract->id }}, '{{ $contract->contract_number }}')"
                                        class="btn btn-sm btn-danger" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-state">
                            <td colspan="10" class="text-center py-5">
                                <i class="fas fa-file-contract fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">No hay contratos registrados</h5>
                                <p class="text-muted mb-3">Comienza registrando el primer contrato de la entidad</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>



@endsection

@push('styles')
    <style>
        /* Estilos específicos para contracts - No afectan al layout */
        .contracts-index .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .contracts-index .section-header h2 {
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }

        .contracts-index .section-header p {
            color: #6c757d;
            margin: 5px 0 0 0;
            font-size: 0.95rem;
        }

        .contracts-index .content-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
        }

        .contracts-index .table-header {
            padding-bottom: 15px;
            border-bottom: 2px solid #4cd137;
        }

        .contracts-index .table-header h5 {
            color: #2c3e50;
            font-weight: 600;
        }

        .contracts-index .table-header h5 i {
            color: #4cd137;
        }

        /* Tabla moderna */
        .contracts-index .table-modern {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            margin-bottom: 0;
        }

        .contracts-index .table-modern thead {
            background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
            color: #fff;
        }

        .contracts-index .table-modern thead th {
            padding: 16px 12px;
            font-size: 13px;
            text-align: center;
            font-weight: 600;
            border: none;
            white-space: nowrap;
            vertical-align: middle;
        }

        .contracts-index .table-modern tbody tr {
            background: #fff;
            transition: all 0.2s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        .contracts-index .table-modern tbody tr:hover:not(.empty-state) {
            background: #f8fff9;
            transform: scale(1.002);
            box-shadow: 0 2px 8px rgba(76, 209, 55, 0.15);
        }

        .contracts-index .table-modern tbody td {
            padding: 14px 12px;
            text-align: center;
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .contracts-index .table-modern .empty-state {
            background: #fafafa;
        }

        .contracts-index .table-modern .empty-state:hover {
            background: #fafafa;
            transform: none;
            box-shadow: none;
        }

        /* Botones de acción - CON SPACING */
        .contracts-index .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
            align-items: center;
        }

        .contracts-index .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
        }

        .contracts-index .btn-sm {
            padding: 6px 12px;
            font-size: 0.85rem;
        }

        .contracts-index .btn-success {
            background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-left: 8px;
            text-decoration: none;
        }

        .contracts-index .btn-success:hover {
            background: linear-gradient(135deg, #3db32a 0%, #2d9e24 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 209, 55, 0.4);
        }

        .contracts-index .btn-info {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        }

        .contracts-index .btn-info:hover {
            background: linear-gradient(135deg, #2980b9 0%, #1f6391 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.4);
        }

        .contracts-index .btn-warning {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }

        .contracts-index .btn-warning:hover {
            background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(243, 156, 18, 0.4);
        }

        .contracts-index .btn-danger {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        }

        .contracts-index .btn-danger:hover {
            background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4);
        }

        /* Badges */
        .contracts-index .badge {
            padding: 8px 14px;
            color: white;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .contracts-index .badge.bg-info {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%) !important;
        }

        .contracts-index .badge.bg-success {
            background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%) !important;
        }

        .contracts-index .badge.bg-warning {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important;
        }

        .contracts-index .badge.bg-danger {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%) !important;
        }

        .contracts-index .badge.bg-secondary {
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%) !important;
        }

        /* Alerts */
        .contracts-index .alert {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
        }

        .contracts-index .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border-left: 4px solid #4cd137;
        }

        .contracts-index .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border-left: 4px solid #e74c3c;
        }

        /* Tarjetas de estadísticas */
        .contracts-index .stat-card-mini {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s ease;
            margin-bottom: 28px;
            margin-top: 22px;
        }

        .contracts-index .stat-card-mini:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        }

        .contracts-index .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .contracts-index .stat-icon.bg-success {
            background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
        }

        .contracts-index .stat-icon.bg-warning {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }

        .contracts-index .stat-icon.bg-danger {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        }

        .contracts-index .stat-icon.bg-info {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        }

        .contracts-index .stat-content {
            flex: 1;
        }

        .contracts-index .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2c3e50;
        }

        .contracts-index .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .contracts-index .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .contracts-index .action-buttons {
                flex-direction: column;
                gap: 5px;
            }

            .contracts-index .action-buttons .btn {
                width: 100%;
            }

            .contracts-index .stat-card-mini {
                margin-bottom: 15px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Agregar clase al body para scope de estilos
        document.body.classList.add('contracts-index');

        function deleteContract(id, contractNumber) {
            if (!confirm(
                `¿Estás seguro de eliminar el contrato ${contractNumber}?\n\n⚠️ Esta acción no se puede deshacer.`)) {
                return;
            }

            fetch(`/contracts/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const alert = document.createElement('div');
                        alert.className = 'alert alert-success alert-dismissible fade show';
                        alert.innerHTML = `
                    <i class="fas fa-check-circle me-2"></i>
                    Contrato eliminado exitosamente
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                        document.querySelector('.section-header').after(alert);

                        setTimeout(() => location.reload(), 1000);
                    } else {
                        alert('❌ Error al eliminar el contrato');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('❌ Error al eliminar el contrato');
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (typeof jQuery !== 'undefined' && jQuery.fn.DataTable) {
                $('#contractsTable').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                    },
                    order: [
                        [0, 'desc']
                    ],
                    pageLength: 10,
                    responsive: true,
                    columnDefs: [{
                            orderable: false,
                            targets: -1
                        } // Deshabilitar orden en columna de acciones
                    ]
                });
            }
        });
    </script>
@endpush
