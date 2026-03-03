@extends('layouts.dashboard')

@section('page-title', 'Dashboard')

@section('dashboard-content')

    <!-- ================= HEADER ================= -->
    <div class="section-header">
        <div></div>
        <button class="export-btn">
            <i class="fas fa-download me-2"></i> Exportar
        </button>
    </div>

    <!-- ================= STATS ================= -->
    <div class="stats-grid">

        <div class="stat-card primary">
            <div class="stat-icon">
                <i class="fas fa-building"></i>
            </div>
            <div class="stat-number">{{ $totalNecesidades }}</div>
            <div class="stat-label">Necesidades (Infraestructura + Traslados)</div>
        </div>

        <div class="stat-card success">
            <div class="stat-icon">
                <i class="fas fa-file-contract"></i>
            </div>
            <div class="stat-number">{{ $totalContratos }}</div>
            <div class="stat-label">Contratos</div>
        </div>

        <div class="stat-card info">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-number">{{ $totalUsuarios }}</div>
            <div class="stat-label">Usuarios</div>
        </div>

        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="fas fa-headset"></i>
            </div>
            <div class="stat-number">{{ $totalPqr }}</div>
            <div class="stat-label">PQR Registradas</div>
        </div>

    </div>

    <!-- ================= GRID ================= -->
    <div class="dashboard-grid">

        <!-- IZQUIERDA -->
        <div>

            <!-- PRESUPUESTO -->
            <div class="chart-container">
                <div class="chart-header">
                    <h3>Presupuesto</h3>
                </div>
                <canvas id="accountingChart"></canvas>
            </div>

            <!-- BALANCE -->
            <div class="chart-container">
                <h3>Balance total</h3>
                <canvas id="balanceChart"></canvas>
            </div>

        </div>

        <!-- DERECHA -->
        <div class="calendar-container">

            <div class="calendar-header">
                <button class="calendar-nav" onclick="prevMonth()">
                    <i class="fas fa-chevron-left"></i>
                </button>

                <h3 id="calendarTitle"></h3>

                <button class="calendar-nav" onclick="nextMonth()">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <div class="calendar-weekdays">
                <span>Lun</span><span>Mar</span><span>Mié</span>
                <span>Jue</span><span>Vie</span><span>Sáb</span><span>Dom</span>
            </div>

            <div class="calendar-grid" id="calendarGrid"></div>

            <!-- EVENTOS -->
            <div class="events-container">
                <h4>📌 Próximos eventos</h4>

                @forelse($eventosCalendario->sortBy('date')->take(6) as $evento)
                    <div class="event-item">
                        <span class="event-date">
                            {{ \Carbon\Carbon::parse($evento['date'])->format('d M') }}
                        </span>
                        <span class="event-title">
                            {{ $evento['title'] }}
                        </span>
                    </div>
                @empty
                    <p class="text-muted">No hay eventos próximos</p>
                @endforelse
            </div>

        </div>
    </div>

@endsection

{{-- ================= STYLES ================= --}}
@push('styles')
    <style>
        :root {
            --green: #22c55e;
            --green-dark: #16a34a;
            --bg: #f6f8fc;
            --card: #fff;
            --radius: 18px;
            --shadow: 0 18px 35px rgba(0, 0, 0, .08);
        }

        body {
            background: var(--bg);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .export-btn {
            background: linear-gradient(135deg, var(--green), var(--green-dark));
            color: #fff;
            padding: 10px 22px;
            border: none;
            border-radius: 14px;
            font-weight: 600;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 18px;
            padding: 1.8rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, .08);
            transition: all .35s ease;
        }

        .stat-card::before {
            content: "";
            position: absolute;
            inset: 0;
            opacity: .08;
        }

        .stat-card.primary::before {
            background: #2563eb;
        }

        .stat-card.success::before {
            background: #16a34a;
        }

        .stat-card.info::before {
            background: #0ea5e9;
        }

        .stat-card.warning::before {
            background: #f59e0b;
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 45px rgba(0, 0, 0, .15);
        }

        .stat-icon {
            font-size: 2.4rem;
            margin-bottom: .6rem;
            color: inherit;
        }

        .stat-card.primary .stat-icon {
            color: #2563eb;
        }

        .stat-card.success .stat-icon {
            color: #16a34a;
        }

        .stat-card.info .stat-icon {
            color: #0ea5e9;
        }

        .stat-card.warning .stat-icon {
            color: #f59e0b;
        }

        .stat-number {
            font-size: 2.4rem;
            font-weight: 800;
            color: #111827;
        }

        .stat-label {
            font-size: .95rem;
            color: #6b7280;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 25px;
        }

        .chart-container {
            background: var(--card);
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--shadow);
            margin-bottom: 22px;
        }

        .chart-container canvas {
            height: 180px !important
        }

        .calendar-container {
            background: var(--card);
            padding: 22px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .calendar-nav {
            background: linear-gradient(135deg, var(--green), var(--green-dark));
            border: none;
            color: #fff;
            width: 34px;
            height: 34px;
            border-radius: 10px;
        }

        #calendarTitle {
            font-weight: 700;
            text-transform: capitalize
        }

        .calendar-weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            text-align: center;
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
        }

        .calendar-day {
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #f9fafb;
            position: relative;
        }

        .calendar-day.today {
            background: linear-gradient(135deg, var(--green), var(--green-dark));
            color: #fff;
            font-weight: 700;
        }

        .calendar-day.has-event::after {
            content: '';
            width: 6px;
            height: 6px;
            background: var(--green-dark);
            border-radius: 50%;
            position: absolute;
            bottom: 5px;
        }

        .events-container {
            margin-top: 20px
        }

        .event-item {
            display: flex;
            gap: 12px;
            padding: 10px;
            border-radius: 12px;
            background: #f9fafb;
            margin-bottom: 8px;
        }

        .event-date {
            font-weight: 700;
            color: var(--green-dark);
            min-width: 50px
        }
    </style>
@endpush

{{-- ================= SCRIPTS ================= --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        window.calendarEvents = @json($eventosCalendario);
        window.currentDate = window.currentDate || new Date();

        document.addEventListener('DOMContentLoaded', () => {
            initCharts();
            renderCalendar();
        });

        /* ===== CHARTS ===== */
        function initCharts() {

            new Chart(document.getElementById('accountingChart'), {
                type: 'line',
                data: {
                    labels: ['Solicitado', 'Aceptado'],
                    datasets: [{
                        data: [{{ $presupuestoSolicitado }}, {{ $presupuestoAceptado }}],
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34,197,94,.25)',
                        fill: true,
                        tension: .4
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    maintainAspectRatio: false
                }
            });

            new Chart(document.getElementById('balanceChart'), {
                type: 'bar',
                data: {
                    labels: ['Balance'],
                    datasets: [{
                        data: [{{ $balance }}],
                        backgroundColor: '#16a34a',
                        borderRadius: 10
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    maintainAspectRatio: false
                }
            });
        }

        /* ===== CALENDAR ===== */
        function renderCalendar() {

            const grid = document.getElementById('calendarGrid');
            const title = document.getElementById('calendarTitle');
            grid.innerHTML = '';

            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            title.textContent = currentDate.toLocaleDateString('es-ES', {
                month: 'long',
                year: 'numeric'
            });

            const firstDay = new Date(year, month, 1).getDay() || 7;
            const lastDate = new Date(year, month + 1, 0).getDate();
            const today = new Date();

            for (let i = 1; i < firstDay; i++) {
                grid.innerHTML += `<div></div>`;
            }

            for (let day = 1; day <= lastDate; day++) {
                const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
                const hasEvent = calendarEvents.some(e => e.date === dateStr);
                const isToday = day === today.getDate() && month === today.getMonth() && year === today.getFullYear();

                grid.innerHTML += `
            <div class="calendar-day ${isToday?'today':''} ${hasEvent?'has-event':''}">
                ${day}
            </div>`;
            }
        }

        function prevMonth() {
            currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1, 1);
            renderCalendar();
        }

        function nextMonth() {
            currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 1);
            renderCalendar();
        }
    </script>
@endpush
