{{-- resources/views/budgets/show.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Detalle del Presupuesto')

@section('dashboard-content')
    <div class="section-header mb-4">
        <div>
            <p class="text-muted">Información completa del presupuesto de
                <strong>{{ $budget->sede->nom_sede ?? 'N/A' }} - {{ $budget->year }}</strong>
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('budget.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
            <a href="{{ route('budget.edit', $budget) }}" class="btn btn-warning shadow-sm">
                <i class="fas fa-edit me-2"></i>Editar
            </a>
        </div>
    </div>
    <br>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $percentage = $budget->total_budget > 0 ? ($budget->spent_budget / $budget->total_budget) * 100 : 0;
        $statusClass = $percentage >= 90 ? 'bg-danger' : ($percentage >= 70 ? 'bg-warning' : 'bg-success');
        $statusIcon =
            $percentage >= 90 ? 'fa-exclamation-triangle' : ($percentage >= 70 ? 'fa-clock' : 'fa-check-circle');
        $statusText = $percentage >= 90 ? 'Crítico' : ($percentage >= 70 ? 'Alerta' : 'Normal');
    @endphp

    <!-- Tarjetas de Resumen - 4 columnas -->
    <div class="budget-stats-grid">

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-wallet"></i></div>
            <div class="stat-value">${{ number_format($budget->total_budget, 0, ',', '.') }}</div>
            <div class="stat-label">Presupuesto Total</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
            <div class="stat-value">${{ number_format($budget->spent_budget, 0, ',', '.') }}</div>
            <div class="stat-label">Ejecutado</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
            <div class="stat-value">${{ number_format($budget->total_budget - $budget->spent_budget, 0, ',', '.') }}</div>
            <div class="stat-label">Disponible</div>
        </div>

        <div class="stat-card stat-card-status {{ $statusClass }}">
            <div class="stat-icon"><i class="fas {{ $statusIcon }}"></i></div>
            <div class="stat-value">{{ number_format($percentage, 1) }}%</div>
            <div class="stat-label">Estado: {{ $statusText }}</div>
        </div>
    </div>
    <br>

    <div class="budgets-show">
        <div class="row budgets-two-columns">
            <!-- Información General -->
            <div class="col-md-6">
                <div class="content-card">
                    <h5 class="section-title">
                        <i class="fas fa-info-circle"></i> Información General
                    </h5>

                    <div class="detail-group">
                        <div class="detail-item">
                            <span class="detail-label">
                                <i class="fas fa-building text-success"></i> Centro:
                            </span>
                            <span class="detail-value">
                                {{ $budget->sede->nom_sede ?? 'N/A' }}
                            </span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">
                                <i class="fas fa-calendar text-success"></i> Año:
                            </span>
                            <span class="detail-value">
                                <span class="badge" style="background-color: #2ed573; color:#fff;">{{ $budget->year }}</span>

                            </span>
                        </div>                        

                        <div class="detail-item">
                            <span class="detail-label">
                                <i class="fas fa-file-alt text-success"></i> Resolución:
                            </span>
                            <span class="detail-value">
                                {{ $budget->resolution ?? 'N/A' }}
                            </span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">
                                <i class="fas fa-user-tie text-success"></i> Responsable:
                            </span>
                            <span class="detail-value">
                                <div>{{ $budget->manager->name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $budget->manager->email ?? '' }}</small>
                            </span>
                        </div>
                        <h5 class="section-title">
                            <i class="fas fa-history"></i> Auditoría
                        </h5>
                        <div class="detail-group">
                            <div class="detail-item">
                                <span class="detail-label">
                                    <i class="fas fa-calendar-plus text-success"></i> Creado:
                                </span>
                                <span class="detail-value">
                                    {{ $budget->created_at->format('d/m/Y H:i') }}
                                </span>
                            </div>

                            <div class="detail-item">
                                <span class="detail-label">
                                    <i class="fas fa-calendar-plus text-success"></i> Actualizado:
                                </span>
                                <span class="detail-value">
                                    {{ $budget->updated_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Gráfico de Ejecución - Ancho completo -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="content-card">
                        <h5 class="section-title"><i class="fas fa-chart-pie"></i> Ejecución del Presupuesto</h5>

                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="chart-container">
                                    <canvas id="budgetChart"></canvas>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="progress-info">
                                    <div class="progress"
                                        style="height: 30px; background-color: #e9ecef; border-radius: 8px;">
                                        <div class="progress-bar" id="progressBar" role="progressbar"
                                            style="width: 0%; height: 100%; border-radius: 8px; background-color: #4cd137;
                                               display: flex; align-items: center; justify-content: center; color: white;">
                                            0%
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <br>
    </div>
    <br>
    <!-- Presupuestos por Dependencia - Ancho completo -->
    <div class="row">
        <div class="col-12">
            <div class="content-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="section-title mb-0"><i class="fas fa-sitemap"></i> Presupuestos por Dependencia</h5>
                    <a href="{{ route('budget.edit', $budget) }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus me-2"></i>Agregar Dependencia
                    </a>
                </div>
                <br>

                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Unidad</th>
                                <th>Subunidades</th>
                                <th>Presupuesto</th>
                                <th>Ejecutado</th>
                                <th>Disponible</th>
                                <th>%</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($budget->departmentBudgets as $dept)
                                @php
                                    $dp =
                                        $dept->total_budget > 0 ? ($dept->spent_budget / $dept->total_budget) * 100 : 0;

                                    $dpClass = $dp >= 90 ? 'bg-danger' : ($dp >= 70 ? 'bg-warning' : 'bg-success');
                                @endphp

                                <tr>
                                    <td class="col-small">
                                        <strong>{{ $dept->SubUnit->dependencyUnit->short_name }}</strong>
                                    </td>

                                    <td class="col-small">
                                        @if ($dept->SubUnit->dependencyUnit->subunits->count())
                                            <ul class="mb-0">
                                                <li>{{ $dept->SubUnit->name }}</li>
                                            </ul>
                                        @else
                                            <span class="text-muted">Sin subunidades</span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="text-primary">
                                            ${{ number_format($dept->total_budget, 0, ',', '.') }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="text-danger">
                                            ${{ number_format($dept->spent_budget, 0, ',', '.') }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="text-success">
                                            ${{ number_format($dept->total_budget - $dept->spent_budget, 0, ',', '.') }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge {{ $dpClass }}">{{ number_format($dp, 1) }}%</span>
                                    </td>
                                </tr>


                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                        No hay dependencias asignadas
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        $statusClass =$percentage >=90 ? 'bg-danger' : // 🔴 Muy ejecutado
        ($percentage >=70 ? 'bg-warning' : // 🟡 Medio
            'bg-success'); // 🟢 Bien


        .budgets-show .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .budgets-show .section-header h2 {
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }

        .budgets-show .section-header p {
            color: #6c757d;
            margin: 5px 0 0 0;
            font-size: 0.95rem;
        }

        .budgets-two-columns {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            /* 2 columnas */
            gap: 25px;
            /* separación entre tarjetas */
        }

        /* Responsive */
        @media (max-width: 768px) {
            .budgets-two-columns {
                grid-template-columns: 1fr;
                /* 1 columna en pantallas pequeñas */
            }
        }

        .budgets-show .content-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
        }

        .budgets-show .section-title {
            color: #2c3e50;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4cd137;
            display: flex;
            align-items: center;
        }

        .budgets-show .section-title i {
            color: #4cd137;
            margin-right: 8px;
        }

        /* Estilos para items */
        .detail-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .detail-label {
            font-weight: 600;
            color: #2c3e50;
        }

        .detail-value {
            text-align: right;
        }


        /* Contenedor grid */
        .budget-stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }


        /* Responsive */
        @media (max-width: 992px) {
            .budget-stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .budget-stats-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Todas las tarjetas */
        .stat-card,
        .stat-card-status {
            background: white;
            /* por defecto todas blancas */
            padding: 22px 18px;
            border-radius: 14px;
            text-align: center;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
            min-height: 170px;

            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;

            color: #2c3e50;
            /* texto oscuro */
        }

        .stat-icon {
            font-size: 2.8rem;
            margin-bottom: 12px;
            opacity: 0.8;
        }

        /* Valor */
        .stat-value {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 6px;
        }

        /* Descripción */
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* 🔵 La cuarta tarjeta SÍ tiene color dinámico */
        .stat-card-status.bg-success {
            background: linear-gradient(135deg, #4cd137, #3db32a);
            color: white;
        }

        .stat-card-status.bg-warning {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: white;
        }

        .stat-card-status.bg-danger {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }


        /* Detalles */
        .budgets-show .detail-group {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .budgets-show .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: start;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .budgets-show .detail-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }

        .budgets-show .detail-label {
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
            min-width: 180px;
        }

        .budgets-show .detail-label i {
            margin-right: 5px;
        }

        .budgets-show .detail-value {
            color: #2c3e50;
            font-size: 0.95rem;
            text-align: right;
            flex: 1;
        }

        /* Tabla */
        /* Tabla moderna */
        .budgets-show .table-modern {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            margin-bottom: 0;
            table-layout: auto;
            /* Ajusta columnas al contenido */
        }

        .budgets-show .badge {
            font-weight: 600;
            color: #fff;
            padding: 5px 10px;
            border-radius: 12px;
        }

        /* Colores de badges */
        .budgets-show .badge.bg-success {
            background-color: #2ed573 !important;
        }

        .budgets-show .badge.bg-warning {
            background-color: #ffa502 !important;
        }

        .budgets-show .badge.bg-danger {
            background-color: #e84118 !important;
        }

        /* Encabezado */
        .budgets-show .table-modern thead {
            background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
            color: #fff;
        }

        .budgets-show .table-modern thead th {
            padding: 16px 12px;
            font-size: 14px;
            text-align: center;
            font-weight: 600;
            border: none;
            white-space: nowrap;
            vertical-align: middle;
        }

        /* Iconos del header */
        .budgets-show .table-modern thead th i {
            margin-right: 6px;
            /* Espacio entre icono y texto */
            color: #fff;
        }

        /* Filas */
        .budgets-show .table-modern tbody tr {
            background: #fff;
            transition: all 0.2s ease;
            border-bottom: 1px solid #e9ecef;
        }

        .budgets-show .table-modern tbody tr:hover {
            background: #f8fff9;
            transform: scale(1.002);
            box-shadow: 0 2px 8px rgba(76, 209, 55, 0.15);
        }

        /* Celdas */
        .budgets-show .table-modern tbody td {
            padding: 14px 12px;
            text-align: center;
            vertical-align: middle;
            font-size: 0.95rem;
            color: #2c3e50;
        }

        /* Badges de porcentaje */
        .budgets-show .table-modern tbody .badge {
            font-weight: 600;
            color: #fff;
            padding: 5px 10px;
            border-radius: 12px;
        }

        /* Colores de badges */
        .budgets-show .badge.bg-success {
            background-color: #2ed573 !important;
        }

        .budgets-show .badge.bg-warning {
            background-color: #ffa502 !important;
        }

        .budgets-show .badge.bg-danger {
            background-color: #e84118 !important;
        }

        .budgets-show .table-modern tbody td.col-small {
            font-size: 0.78rem;
            /* más pequeño */
            line-height: 1.15;
            /* contenido más compacto */
            max-width: 180px;
            /* evita que se estire demasiado */
            white-space: normal;
            /* permite salto de línea */
            word-wrap: break-word;
        }


        /* Responsivo */
        @media (max-width: 768px) {
            .budgets-show .table-modern thead {
                display: none;
                /* Se puede ocultar el header y usar tarjetas en móvil */
            }

            .budgets-show .table-modern tbody td {
                display: block;
                text-align: right;
                padding: 10px;
            }

            .budgets-show .table-modern tbody td::before {
                content: attr(data-label);
                float: left;
                font-weight: 600;
                text-transform: uppercase;
                color: #2c3e50;
            }
        }


        .budgets-show .badge {
            padding: 8px 14px;
            color: white;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .budgets-show .chart-container {
            position: relative;
            height: 335px;
        }

        /* Botones con estilos mejorados */
        .budgets-show .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 10px 24px;
            transition: all 0.3s ease;
            border: none;
        }

        .budgets-show .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white;
            text-decoration: none;
            margin-right: 5px
        }

        .budgets-show .btn-secondary:hover {
            background: linear-gradient(135deg, #5a6268 0%, #495057 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.4);
        }

        .budgets-show .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #000;
            text-decoration: none;
        }

        .budgets-show .btn-warning:hover {
            background: linear-gradient(135deg, #e0a800 0%, #c69500 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.4);
        }

        .budgets-show .btn-success {
            background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
            color: white;
        }

        .budgets-show .btn-success:hover {
            background: linear-gradient(135deg, #3db32a 0%, #2d9e24 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 209, 55, 0.4);
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.body.classList.add('budgets-show');
        // Obtener datos de las dependencias desde Blade
        const departments = @json($budget->departmentBudgets);

        // Calcular porcentaje ponderado total
        let totalSpent = 0;
        let totalBudget = 0;

        departments.forEach(dept => {
            totalSpent += dept.spent_budget;
            totalBudget += dept.total_budget;
        });

        const executedPercent = totalBudget > 0 ? (totalSpent / totalBudget) * 100 : 0;

        // Configurar gráfico general
        const ctx = document.getElementById('budgetChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Ejecutado', 'Disponible'],
                datasets: [{
                    data: [executedPercent, 100 - executedPercent],
                    backgroundColor: ['#e74c3c', '#4cd137'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.raw.toFixed(1) + '%';
                            }
                        }
                    }
                }
            }
        });

        const progressBar = document.getElementById('progressBar');
        progressBar.style.width = executedPercent + '%';
        progressBar.innerText = executedPercent.toFixed(1) + '%';

        // Cambiar color según porcentaje
        if (executedPercent >= 90) {
            progressBar.style.backgroundColor = '#e74c3c';
        } else if (executedPercent >= 70) {
            progressBar.style.backgroundColor = '#f39c12';
        } else {
            progressBar.style.backgroundColor = '#4cd137';
        }
    </script>
@endpush
