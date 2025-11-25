@extends('layouts.dashboard')

@section('page-title', 'Dashboard')

@section('dashboard-content')
<!-- Panel de Resumen -->
<div class="section-header">
    <div>
        <h2>Resumen</h2>
        <p>Panel de resumen</p>
    </div>
    <button class="export-btn">
        <i class="fas fa-download"></i> Exportar
    </button>
</div>

<div class="stats-grid">
    <div class="stat-card pink">
        <div class="stat-icon">📋</div>
        <div class="stat-number">{{ $totalSolicitudes ?? 30 }}</div>
        <div class="stat-label">Total solicitudes</div>
        <div class="stat-change positive">+8% from yesterday</div>
    </div>
    <div class="stat-card orange">
        <div class="stat-icon">📄</div>
        <div class="stat-number">{{ $totalContratos ?? 20 }}</div>
        <div class="stat-label">Total de contratos</div>
        <div class="stat-change positive">+5% from yesterday</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon">✅</div>
        <div class="stat-number">{{ $totalQuejas ?? 5 }}</div>
        <div class="stat-label">Total de quejas</div>
        <div class="stat-change negative">-1% from yesterday</div>
    </div>
    <div class="stat-card purple">
        <div class="stat-icon">👥</div>
        <div class="stat-number">{{ $nuevosUsuarios ?? 8 }}</div>
        <div class="stat-label">Nuevos usuarios</div>
        <div class="stat-change positive">0.5% from yesterday</div>
    </div>
</div>

<!-- Área de dos columnas -->
<div class="dashboard-grid">
    <!-- Columna izquierda - Gráficos -->
    <div class="left-column">
        <div class="chart-container">
            <div class="chart-header">
                <h3>Presupuesto</h3>
                <div class="chart-legend">
                    <span class="legend-item">
                        <span class="legend-color red"></span> Egresos
                    </span>
                    <span class="legend-item">
                        <span class="legend-color green"></span> Ingresos
                    </span>
                </div>
            </div>
            <div class="chart-area">
                <canvas id="accountingChart" width="400" height="200"></canvas>
            </div>
        </div>
        
        <div class="chart-container">
            <div class="chart-header">
                <h3>Balance total</h3>
            </div>
            <div class="chart-area">
                <canvas id="balanceChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Principales módulos -->
        <div class="modules-container">
            <h3>Principales módulos</h3>
            <div class="modules-grid">
                <div class="module-item">
                    <div class="module-number">01</div>
                    <div class="module-info">
                        <div class="module-name">Infraestructura</div>
                        <div class="module-progress">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $percentInfraestructura }}%; background:#4a90e2;"></div>
                            </div>
                            <span class="progress-percent">{{ $percentInfraestructura }}%</span>
                        </div>
                    </div>
                </div>
                <div class="module-item">
                    <div class="module-number">02</div>
                    <div class="module-info">
                        <div class="module-name">Gestión de usuario</div>
                        <div class="module-progress">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $percentUsuarios }}%; background:#26d0ce;"></div>
                            </div>
                            <span class="progress-percent">{{ $percentUsuarios }}%</span>
                        </div>
                    </div>
                </div>
                <div class="module-item">
                    <div class="module-number">03</div>
                    <div class="module-info">
                        <div class="module-name">Contratos y seguimiento</div>
                        <div class="module-progress">
                            <div class="progress-bar">
                               <div class="progress-fill" style="width: {{ $percentContratos }}%; background:#a855f7;"></div>
                            </div>
                            <span class="progress-percent">{{ $percentContratos }}%</span>
                        </div>
                    </div>
                </div>
                <div class="module-item">
                    <div class="module-number">04</div>
                    <div class="module-info">
                        <div class="module-name">Traslados</div>
                        <div class="module-progress">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $percentTraslados }}%; background:#f59e0b;"></div>
                            </div>
                            <span class="progress-percent">{{ $percentTraslados }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Columna derecha - Calendario -->
    <div class="right-column">
        <div class="calendar-container">
            <div class="calendar-header">
                <button class="calendar-nav" onclick="previousMonth()">&lt;</button>
                <h3 id="currentMonth">{{ now()->translatedFormat('F Y') }}</h3>
                <button class="calendar-nav" onclick="nextMonth()">&gt;</button>
            </div>
            <div class="calendar-grid" id="calendarGrid">
                <!-- Generado por JavaScript -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        initializeCharts();
        generateCalendar();
    });
</script>
@endpush