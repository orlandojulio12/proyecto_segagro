{{-- resources/views/budgets/index.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Gestión de Presupuestos')

@section('dashboard-content')
    <div class="section-header mb-4">
        <div>
            <p class="text-muted">Gestiona los presupuestos {{ $sedes->nom_sede ?? 'Centro' }}</p>
        </div>
        <a href="{{ route('budget.create') }}" class="btn btn-success shadow-sm">
            <i class="fas fa-plus me-2"></i>Nuevo Presupuesto
        </a>
        
    </div>
    <br>

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

    <!-- ESTADÍSTICAS -->
    <div class="container-fluid px-0">
        <!-- PRESUPUESTO TOTAL GRANDE -->
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="stat-card-large">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info flex-shrink-0">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <div class="stat-number">
                                ${{ number_format($budgets->sum('total_budget'), 0, ',', '.') }}
                            </div>
                            <div class="stat-label">Presupuesto Total</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3 TARJETAS PEQUEÑAS - FORZADO CON FLEX -->
        <div class="row g-3" style="display: flex; flex-wrap: wrap; margin-top: 16px; margin-bottom: 16px;">

            <div class="col-md-4" style="flex: 1 1 30%; max-width: 32%; ">
                <div class="stat-card-small h-100">
                    <div class="stat-icon bg-danger">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">${{ number_format($budgets->sum('spent_budget'), 0, ',', '.') }}</div>
                        <div class="stat-label">Presupuesto Ejecutado</div>
                    </div>
                </div>
            </div>

            <div class="col-md-4" style="flex: 1 1 30%; max-width: 32%;">
                <div class="stat-card-small h-100">
                    <div class="stat-icon bg-success">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">
                            ${{ number_format($budgets->sum('total_budget') - $budgets->sum('spent_budget'), 0, ',', '.') }}
                        </div>
                        <div class="stat-label">Presupuesto Disponible</div>
                    </div>
                </div>
            </div>

            <div class="col-md-4" style="flex: 1 1 30%; max-width: 32%;">
                <div class="stat-card-small h-100">
                    <div class="stat-icon bg-warning">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $budgets->count() }}</div>
                        <div class="stat-label">Vigencias Registradas</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLA -->
    <div class="content-card mt-5 budgets-index">
        <div class="table-header mb-3">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Listado de Presupuestos</h5>
        </div>

        <div class="table-responsive">
            <table class="table table-modern" id="budgetsTable">
                <thead>
                    <tr>
                        <th><i class="fas fa-calendar me-2"></i>Año</th>
                        <th><i class="fas fa-file-alt me-2"></i>Resolución</th>
                        <th><i class="fas fa-wallet me-2"></i>Presupuesto Total</th>
                        <th><i class="fas fa-money-bill-wave me-2"></i>Ejecutado</th>
                        <th><i class="fas fa-chart-line me-2"></i>Disponible</th>
                        <th><i class="fas fa-percentage me-2"></i>% Ejecución</th>
                        <th><i class="fas fa-user-tie me-2"></i>Responsable</th>
                        <th><i class="fas fa-sitemap me-2"></i>Dependencias</th>
                        <th><i class="fas fa-cogs me-2"></i>Acciones</th>                        
                    </tr>
                </thead>
                <tbody>
                    @forelse($budgets as $budget)
                        @php
                            $available = $budget->total_budget - $budget->spent_budget;
                            $percentage =
                                $budget->total_budget > 0 ? ($budget->spent_budget / $budget->total_budget) * 100 : 0;
                            $statusClass =
                                $percentage >= 90 ? 'bg-danger' : ($percentage >= 70 ? 'bg-warning' : 'bg-success');
                        @endphp
                        <tr>
                            <td>
                                <span class="text-muted">{{ $budget->year }}</span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $budget->resolution ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <strong class="text-primary">
                                    ${{ number_format($budget->total_budget, 0, ',', '.') }}
                                </strong>
                            </td>
                            <td>
                                <strong class="text-danger">
                                    ${{ number_format($budget->spent_budget, 0, ',', '.') }}
                                </strong>
                            </td>
                            <td>
                                <strong class="text-success">
                                    ${{ number_format($available, 0, ',', '.') }}
                                </strong>
                            </td>
                            <td>
                                <span class="badge {{ $statusClass }}">
                                    {{ number_format($percentage, 1) }}%
                                </span>
                            </td>
                            <td>
                                <div>{{ $budget->manager->name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $budget->manager->email ?? '' }}</small>
                            </td>
                            <td>
                                <span class="text-muted">
                                    {{ $budget->departmentBudgets->count() }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('budget.show', $budget) }}" class="btn btn-sm btn-info"
                                        title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('budget.edit', $budget) }}" class="btn btn-sm btn-warning"
                                        title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="deleteBudget({{ $budget->id }}, '{{ $budget->year }}')"
                                        class="btn btn-sm btn-danger" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-state">
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-wallet fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">No hay presupuestos registrados</h5>
                                <p class="text-muted mb-3">Comienza registrando el primer presupuesto</p>
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
        .budgets-index .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .budgets-index .section-header h2 {
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }

        .budgets-index .section-header p {
            color: #6c757d;
            margin: 5px 0 0 0;
            font-size: 0.95rem;
        }

        .budgets-index .content-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
        }

        .budgets-index .table-header {
            padding-bottom: 15px;
            border-bottom: 2px solid #4cd137;
        }

        .budgets-index .table-header h5 {
            color: #2c3e50;
            font-weight: 600;
        }

        .budgets-index .table-header h5 i {
            color: #4cd137;
        }

        .budgets-index .table-modern {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            margin-bottom: 0;
        }

        .budgets-index .table-modern thead {
            background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
            color: #fff;
        }

        .budgets-index .table-modern thead th {
            padding: 16px 12px;
            font-size: 13px;
            text-align: center;
            font-weight: 600;
            border: none;
            white-space: nowrap;
            vertical-align: middle;
        }

        .budgets-index .table-modern thead th i {
            color: #fff !important;
            margin-right: 8px;
        }

        .budgets-index .table-modern tbody tr {
            background: #fff;
            transition: all 0.2s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        .budgets-index .table-modern tbody tr:hover:not(.empty-state) {
            background: #f8fff9;
            transform: scale(1.002);
            box-shadow: 0 2px 8px rgba(76, 209, 55, 0.15);
        }

        .budgets-index .table-modern tbody td {
            padding: 14px 12px;
            text-align: center;
            vertical-align: middle;
            font-size: 0.9rem;
            color: #000 !important;
        }

        .budgets-index .table-modern tbody .badge {
            font-weight: 600;
            color: #fff !important;
        }

        .budgets-index .badge.bg-secondary {
            background-color: #7f8fa6 !important;
        }

        .budgets-index .badge.bg-info {
            background-color: #70a1ff !important;
        }

        .budgets-index .badge.bg-warning {
            background-color: #ffa502 !important;
        }

        .budgets-index .badge.bg-danger {
            background-color: #e84118 !important;
        }

        .budgets-index .badge.bg-success {
            background-color: #2ed573 !important;
        }

        .budgets-index .action-buttons {
            display: flex;
            gap: 6px;
        }

        .budgets-index .action-buttons .btn {
            padding: 4px 8px;
            border-radius: 6px;
            display: flex;
            align-items: center;
        }

        $statusClass =$percentage >=90 ? 'bg-danger' : // 🔴 Muy ejecutado
        ($percentage >=70 ? 'bg-warning' : // 🟡 Medio
            'bg-success'); // 🟢 Bien

        .budgets-index .badge {
            padding: 6px 10px;
            font-size: 0.85rem;
            border-radius: 8px;
        }

        .budgets-index .btn-info {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: #fff;
        }

        .budgets-index .btn-info:hover {
            background: linear-gradient(135deg, #2980b9 0%, #1f6391 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.4);
        }

        /* Warning */
        .budgets-index .btn-warning {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }

        .budgets-index .btn-warning:hover {
            background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(243, 156, 18, 0.4);
        }

        /* Danger */
        .budgets-index .btn-danger {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: #fff;
        }

        .budgets-index .btn-danger:hover {
            background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4);
        }

        /* Success general */
        .budgets-index .btn-success {
            background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
            padding: 10px 20px;
            color: white;
        }

        .budgets-index .btn-success:hover {
            background: linear-gradient(135deg, #3db32a 0%, #2d9e24 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 209, 55, 0.4);
        }

        /* --- BOTONES --- */
        .budgets-index .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
            align-items: center;
        }

        .budgets-index .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
        }

        /* Botón "success" */
        .budgets-index .btn-success {
            background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
            padding: 10px 20px;
            color: white;
        }

        .budgets-index .btn-success:hover {
            background: linear-gradient(135deg, #3db32a 0%, #2d9e24 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 209, 55, 0.4);
        }

        /* Tarjetas de estadísticas */
        .budgets-index .stat-card-large {
            background: #ebf3fd;
            border: 2px solid #3498db;
            border-radius: 16px;
            padding: 20px 24px;
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.15);
            transition: all 0.3s ease;
        }

        .budgets-index .stat-card-large:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 28px rgba(52, 152, 219, 0.25);
        }

        .budgets-index .stat-card-large .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            color: white;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            flex-shrink: 0;
        }

        .budgets-index .stat-card-large .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: #2c3e50;
            margin: 0;
            line-height: 1.1;
            white-space: nowrap;
        }

        .budgets-index .stat-card-large .stat-label {
            font-size: 1.1rem;
            font-weight: 600;
            color: #34495e;
            margin: 4px 0 0 0;
        }

        .budgets-index .stat-card-small {
            background: white;
            border-radius: 14px;
            padding: 18px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            gap: 14px;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }

        .budgets-index .stat-card-small:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .budgets-index .stat-card-small .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: white;
        }

        .budgets-index .stat-icon.bg-success {
            background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
        }

        .budgets-index .stat-icon.bg-warning {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }

        .budgets-index .stat-icon.bg-danger {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        }

        .budgets-index .stat-card-small .stat-number {
            font-size: 1.7rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }

        .budgets-index .stat-card-small .stat-label {
            font-size: 0.9rem;
            color: #6c757d;
            margin: 0;
        }

        .budgets-index .badge {
            padding: 8px 14px;
            color: white;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .action-buttons i {
            color: #fff !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.body.classList.add('budgets-index');

        function deleteBudget(id, name) {
            if (!confirm(`¿Estás seguro de eliminar el presupuesto de ${name}?\n\nEsta acción no se puede deshacer.`))
                return;

            fetch(`/budget/${id}/delete`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const alert = document.createElement('div');
                        alert.className = 'alert alert-success alert-dismissible fade show';
                        alert.innerHTML =
                            `<i class="fas fa-check-circle me-2"></i>Presupuesto eliminado exitosamente<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
                        document.querySelector('.section-header').after(alert);
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        alert('Error al eliminar');
                    }
                })
                .catch(() => alert('Error al eliminar'));
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (typeof jQuery !== 'undefined' && jQuery.fn.DataTable) {
                $('#budgetsTable').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                    },
                    order: [
                        [1, 'desc']
                    ],
                    pageLength: 10,
                    responsive: true,
                    columnDefs: [{
                        orderable: false,
                        targets: -1
                    }]
                });
            }
        });
    </script>
@endpush
