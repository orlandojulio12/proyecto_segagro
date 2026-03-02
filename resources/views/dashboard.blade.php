@extends('layouts.dashboard')

@section('page-title', 'Dashboard')

@section('dashboard-content')

<!-- ================= HEADER ================= -->
<div class="section-header">
    <div>
        {{-- <h2>Resumen</h2>
        <p>Panel de resumen general</p> --}}
    </div>
    <button class="export-btn">
        <i class="fas fa-download me-2"></i> Exportar
    </button>
</div>

<!-- ================= STATS ================= -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">📋</div>
        <div class="stat-number">{{ $totalSolicitudes ?? 30 }}</div>
        <div class="stat-label">Solicitudes</div>
        <div class="stat-change positive">+8%</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">📄</div>
        <div class="stat-number">{{ $totalContratos ?? 20 }}</div>
        <div class="stat-label">Contratos</div>
        <div class="stat-change positive">+5%</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">⚠️</div>
        <div class="stat-number">{{ $totalQuejas ?? 5 }}</div>
        <div class="stat-label">Quejas</div>
        <div class="stat-change negative">-1%</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-number">{{ $nuevosUsuarios ?? 8 }}</div>
        <div class="stat-label">Usuarios nuevos</div>
        <div class="stat-change positive">+0.5%</div>
    </div>
</div>

<!-- ================= GRID ================= -->
<div class="dashboard-grid">

    <!-- IZQUIERDA -->
    <div>
        <div class="chart-container">
            <div class="chart-header">
                <h3>Presupuesto</h3>
                <div class="chart-legend">
                    <span><span class="dot green"></span>Ingresos</span>
                    <span><span class="dot light-green"></span>Egresos</span>
                </div>
            </div>
            <canvas id="accountingChart"></canvas>
        </div>

        <div class="chart-container">
            <h3>Balance total</h3>
            <canvas id="balanceChart"></canvas>
        </div>

        <div class="modules-container">
            <h3>Principales módulos</h3>

            @php
                $modules = [
                    ['Infraestructura', $percentInfraestructura ?? 70],
                    ['Gestión usuarios', $percentUsuarios ?? 55],
                    ['Contratos', $percentContratos ?? 80],
                    ['Traslados', $percentTraslados ?? 40],
                ];
            @endphp

            @foreach($modules as $i => $module)
                <div class="module-item">
                    <div class="module-number">{{ str_pad($i+1,2,'0',STR_PAD_LEFT) }}</div>
                    <div class="module-info">
                        <div class="module-name">{{ $module[0] }}</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $module[1] }}%"></div>
                        </div>
                        <span class="progress-percent">{{ $module[1] }}%</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- DERECHA -->
    <div class="calendar-container">
        <h3>{{ now()->translatedFormat('F Y') }}</h3>
        <div class="calendar-placeholder">
            Calendario
        </div>
    </div>

</div>
@endsection

{{-- ================= STYLES ================= --}}
@push('styles')
<style>
:root{
    --green:#22c55e;
    --green-soft:#86efac;
    --green-dark:#16a34a;
    --bg:#f6f8fc;
    --card:#ffffff;
    --radius:18px;
    --shadow:0 18px 35px rgba(0,0,0,.08);
}

/* BASE */
body{ background:var(--bg); }

/* HEADER */
.section-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
    animation:fadeUp .6s ease;
}
.export-btn{
    background:linear-gradient(135deg,var(--green),var(--green-dark));
    color:#fff;
    padding:10px 22px;
    border:none;
    border-radius:14px;
    font-weight:600;
    box-shadow:var(--shadow);
    transition:.3s;
}
.export-btn:hover{ transform:translateY(-3px); }

/* STATS */
.stats-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    margin-bottom:30px;
}
.stat-card{
    background:var(--card);
    padding:22px;
    border-radius:var(--radius);
    box-shadow:var(--shadow);
    transition:.3s;
}
.stat-card:hover{ transform:translateY(-6px); }
.stat-icon{ font-size:26px; }
.stat-number{ font-size:30px;font-weight:700; }
.stat-label{ color:#6b7280; }
.stat-change.positive{ color:var(--green-dark); }
.stat-change.negative{ color:#dc2626; }

/* GRID */
.dashboard-grid{
    display:grid;
    grid-template-columns:2.1fr 1fr;
    gap:25px;
}

/* CHARTS */
.chart-container{
    background:var(--card);
    border-radius:var(--radius);
    padding:18px 20px 22px;
    box-shadow:var(--shadow);
    margin-bottom:22px;
}
.chart-container canvas{
    height:210px !important;
}
.chart-header{
    display:flex;
    justify-content:space-between;
    margin-bottom:10px;
}
.chart-legend span{
    font-size:12px;
    margin-left:10px;
}
.dot{
    width:9px;height:9px;border-radius:50%;
    display:inline-block;margin-right:5px;
}
.dot.green{ background:var(--green-dark); }
.dot.light-green{ background:var(--green-soft); }

/* MODULES */
.modules-container{
    background:var(--card);
    padding:22px;
    border-radius:var(--radius);
    box-shadow:var(--shadow);
}
.module-item{
    display:flex;
    gap:15px;
    margin-bottom:16px;
}
.module-number{
    font-weight:700;
    color:var(--green-dark);
}
.progress-bar{
    background:#e5e7eb;
    height:7px;
    border-radius:8px;
    overflow:hidden;
}
.progress-fill{
    height:100%;
    background:linear-gradient(90deg,var(--green),var(--green-dark));
    animation:grow 1.2s ease;
}
.progress-percent{
    font-size:13px;
    color:#6b7280;
}

/* CALENDAR */
.calendar-container{
    background:var(--card);
    padding:22px;
    border-radius:var(--radius);
    box-shadow:var(--shadow);
}
.calendar-placeholder{
    height:240px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#9ca3af;
}

/* ANIMATIONS */
@keyframes fadeUp{
    from{ opacity:0; transform:translateY(20px); }
    to{ opacity:1; transform:translateY(0); }
}
@keyframes grow{
    from{ width:0; }
}
</style>
@endpush

{{-- ================= SCRIPTS ================= --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    new Chart(document.getElementById('accountingChart'), {
        type:'line',
        data:{
            labels:['Ene','Feb','Mar','Abr','May','Jun'],
            datasets:[
                {
                    data:[12,19,15,22,28,30],
                    borderColor:'#22c55e',
                    backgroundColor:'rgba(34,197,94,.25)',
                    fill:true,
                    tension:.45,
                    borderWidth:2,
                    pointRadius:3
                },
                {
                    data:[10,14,12,18,20,23],
                    borderColor:'#86efac',
                    backgroundColor:'rgba(134,239,172,.3)',
                    fill:true,
                    tension:.45,
                    borderWidth:2,
                    pointRadius:3
                }
            ]
        },
        options:{
            responsive:true,
            maintainAspectRatio:false,
            plugins:{ legend:{ display:false }},
            animation:{ duration:900 }
        }
    });

    new Chart(document.getElementById('balanceChart'), {
        type:'bar',
        data:{
            labels:['Ene','Feb','Mar','Abr','May','Jun'],
            datasets:[{
                data:[2,5,3,4,8,7],
                backgroundColor:'#22c55e',
                borderRadius:10,
                barThickness:26
            }]
        },
        options:{
            responsive:true,
            maintainAspectRatio:false,
            plugins:{ legend:{ display:false }},
            animation:{ duration:900 }
        }
    });

});
</script>
@endpush