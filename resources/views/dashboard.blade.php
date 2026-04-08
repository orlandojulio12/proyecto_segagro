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
                <div id="dayEvents">
                    <p class="text-muted">Selecciona un día del calendario</p>
                </div>
                @forelse($eventosCalendario->sortBy('date')->take(6) as $evento)
                    <div class="event-item" style="border-left:4px solid {{ $evento['color'] }}">
                        <span class="event-date">
                            {{ \Carbon\Carbon::parse($evento['date'])->format('d M') }}
                        </span>
                        <span class="event-title">
                            {{ $evento['title'] }}
                            @if ($evento['type'] === 'pqr' && $evento['expired'])
                                <span class="badge bg-danger ms-2">Vencido</span>
                            @endif
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
            --blue: #3b82f6;
            --blue-dark: #1e40af;
            --orange: #f97316;
            --orange-dark: #c2410c;
            --red: #ef4444;
            --red-dark: #b91c1c;
            --bg: #edf2f7;
            --card: #f9fafc;
            --radius: 18px;
            --shadow-light: rgba(255, 255, 255, 0.6);
            --shadow-dark: rgba(0, 0, 0, 0.15);
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

        /* ================= STATS ================= */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.8rem;
            margin-bottom: 2.5rem;
        }

        .stat-card {
            background: var(--card);
            border-radius: var(--radius);
            padding: 2rem 1.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: 8px 8px 20px var(--shadow-dark), -8px -8px 20px var(--shadow-light);
            transition: all 0.4s ease;
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-6px) scale(1.03);
            box-shadow: 12px 12px 25px var(--shadow-dark), -12px -12px 25px var(--shadow-light);
        }

        .stat-card::before {
            content: "";
            position: absolute;
            inset: 0;
            opacity: 0.05;
            border-radius: var(--radius);
        }

        .stat-card.primary::before {
            background: linear-gradient(145deg, var(--blue), var(--blue-dark));
        }

        .stat-card.success::before {
            background: linear-gradient(145deg, var(--green), var(--green-dark));
        }

        .stat-card.info::before {
            background: linear-gradient(145deg, var(--orange), var(--orange-dark));
        }

        .stat-card.warning::before {
            background: linear-gradient(145deg, var(--red), var(--red-dark));
        }

        .stat-card .stat-icon {
            font-size: 2.8rem;
            margin-bottom: 0.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
            background: rgba(255, 255, 255, 0.1);
            padding: 10px;
            border-radius: 50%;
        }

        /* ICON COLORS BASED ON TYPE */
        .stat-card.primary .stat-icon {
            color: var(--blue);
        }

        .stat-card.success .stat-icon {
            color: var(--green);
        }

        .stat-card.info .stat-icon {
            color: var(--orange);
        }

        .stat-card.warning .stat-icon {
            color: var(--red);
        }

        .stat-number {
            font-size: 2.8rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.4rem;
        }

        .stat-label {
            font-size: 0.95rem;
            font-weight: 500;
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
            box-shadow: var(--shadow-dark) 0px 8px 20px inset, var(--shadow-dark) 0px 8px 20px;
            margin-bottom: 22px;
        }

        .chart-container canvas {
            height: 180px !important;
        }

        .calendar-container {
            background: var(--card);
            padding: 22px;
            border-radius: var(--radius);
            box-shadow: var(--shadow-dark) 0px 8px 20px inset, var(--shadow-dark) 0px 8px 20px;
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
            text-transform: capitalize;
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
            transition: background 0.2s;
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

        .calendar-day.has-event:hover {
            background: rgba(34, 197, 94, 0.2);
            border-radius: 50%;
        }

        .events-container {
            margin-top: 20px;
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
            min-width: 50px;
        }
    </style>
@endpush

{{-- ================= SCRIPTS ================= --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
            // Datos del backend
    window.calendarEvents = @json($eventosCalendario);
    window.currentDate = new Date();

    document.addEventListener('DOMContentLoaded', () => {
        renderCalendar();
        showUpcomingEvents();
    });

    /* ================= RENDER CALENDAR ================= */
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
            const dateStr = `${year}-${String(month + 1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
            const events = window.calendarEvents.filter(e => e.date === dateStr);
            const isToday = day === today.getDate() && month === today.getMonth() && year === today.getFullYear();

            let tooltip = '';
            if (events.length) {
                tooltip = 'title="' + events.map(e => `${e.title}${e.type==='tutela'? ' ('+e.hora_inicio+'-'+e.hora_fin+')':''}`).join('\n') + '"';
            }

            grid.innerHTML += `
                <div class="calendar-day ${isToday ? 'today' : ''} ${events.length ? 'has-event' : ''}"
                     onclick="showDayEvents('${dateStr}')"
                     ${tooltip}>
                    ${day}
                </div>
            `;
        }
    }

    /* ================= SHOW EVENTS OF SELECTED DAY ================= */
    function showDayEvents(date) {
        const container = document.getElementById('dayEvents');
        container.innerHTML = '';

        const events = window.calendarEvents.filter(e => e.date === date);

        if (!events.length) {
            container.innerHTML = `<p class="text-muted">No hay eventos este día</p>`;
            return;
        }

        events.forEach(e => {
            const div = document.createElement('div');
            div.className = 'event-item';
            div.style.borderLeft = `4px solid ${e.color}`;
            div.innerHTML = `
                <span class="event-date">${date}${e.type==='tutela'? ' '+e.hora_inicio+'-'+e.hora_fin : ''}</span>
                <span class="event-title">
                    ${e.title}
                    ${e.type === 'pqr' && e.expired
                        ? '<span class="badge bg-danger ms-2">Vencido</span>'
                        : ''}
                </span>
            `;
            container.appendChild(div);
        });
    }

    /* ================= SHOW NEXT 5 DAYS EVENTS ================= */
    function showUpcomingEvents() {
        const container = document.getElementById('dayEvents');
        container.innerHTML = '';

        const today = new Date();
        const next5Days = Array.from({length:5},(_,i)=>{
            const d = new Date(today);
            d.setDate(d.getDate()+i);
            return d.toISOString().split('T')[0];
        });

        let upcomingEvents = window.calendarEvents.filter(e => next5Days.includes(e.date));
        if (!upcomingEvents.length) {
            container.innerHTML = `<p class="text-muted">No hay eventos próximos en los próximos 5 días</p>`;
            return;
        }

        // Ordenar por fecha
        upcomingEvents.sort((a,b) => new Date(a.date) - new Date(b.date));

        upcomingEvents.forEach(e => {
            const div = document.createElement('div');
            div.className = 'event-item';
            div.style.borderLeft = `4px solid ${e.color}`;
            div.innerHTML = `
                <span class="event-date">${e.date}${e.type==='tutela'? ' '+e.hora_inicio+'-'+e.hora_fin : ''}</span>
                <span class="event-title">
                    ${e.title}
                    ${e.type === 'pqr' && e.expired
                        ? '<span class="badge bg-danger ms-2">Vencido</span>'
                        : ''}
                </span>
            `;
            container.appendChild(div);
        });
    }

    /* ================= NAVIGATION ================= */
    function prevMonth() {
        currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1, 1);
        renderCalendar();
        showUpcomingEvents();
    }

    function nextMonth() {
        currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 1);
        renderCalendar();
        showUpcomingEvents();
    }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            renderCalendar();

            /* ================= CHART 1: PRESUPUESTO ================= */
            const accountingCtx = document.getElementById('accountingChart');
            if (accountingCtx) {
                new Chart(accountingCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Enero', 'Febrero', 'Marzo', 'Abril'],
                        datasets: [{
                            label: 'Presupuesto',
                            data: [1200000, 950000, 1400000, 1100000],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true
                            }
                        }
                    }
                });
            }

            /* ================= CHART 2: BALANCE ================= */
            const balanceCtx = document.getElementById('balanceChart');
            if (balanceCtx) {
                new Chart(balanceCtx, {
                    type: 'line',
                    data: {
                        labels: ['Enero', 'Febrero', 'Marzo', 'Abril'],
                        datasets: [{
                            label: 'Balance',
                            data: [300000, 450000, 200000, 600000],
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true
                            }
                        }
                    }
                });
            }

        });
    </script>
@endpush
