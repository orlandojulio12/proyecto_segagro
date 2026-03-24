@extends('layouts.dashboard')

@section('page-title', 'Gestión de Inventarios')

@section('dashboard-content')

    <div class="dashboard-wrapper">

        <div class="section-header mb-4">
            <div>
                <h2 class="fw-bold">Inventario General</h2>
                <p class="text-muted">Panel analítico del inventario institucional</p>
            </div>
        </div>

        {{-- KPI DASHBOARD --}}

        <div class="stats-grid">

            <div class="stat-card green">
                <div class="stat-icon"><i class="fas fa-warehouse"></i></div>
                <div class="stat-content">
                    <span class="stat-number counter" data-target="{{ $totalInventarios }}">0</span>
                    <span class="stat-label">Inventarios</span>

                    <div class="stat-growth {{ $growth >= 0 ? 'up' : 'down' }}">
                        <i class="fas {{ $growth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                        {{ number_format(abs($growth), 1) }} %
                    </div>
                </div>
            </div>


            <div class="stat-card blue">
                <div class="stat-icon"><i class="fas fa-box"></i></div>
                <div class="stat-content">
                    <span class="stat-number counter" data-target="{{ $totalMateriales }}">0</span>
                    <span class="stat-label">Materiales</span>
                </div>
            </div>


            <div class="stat-card orange">
                <div class="stat-icon"><i class="fas fa-paw"></i></div>
                <div class="stat-content">
                    <span class="stat-number counter" data-target="{{ $totalSemovientes }}">0</span>
                    <span class="stat-label">Semovientes</span>
                </div>
            </div>


            <div class="stat-card purple">
                <div class="stat-icon"><i class="fas fa-book"></i></div>
                <div class="stat-content">
                    <span class="stat-number counter" data-target="{{ $totalCatalogo }}">0</span>
                    <span class="stat-label">Productos catálogo</span>
                </div>
            </div>

        </div>


        {{-- GRID INVENTARIOS --}}

        <div class="inventarios-grid">

            @forelse($inventarios as $inventory)
                @php
                    $materiales = $inventory->materials->count();
                    $progress = min(100, $materiales * 10);
                @endphp

                <div class="inventory-card">

                    <div class="inventory-header">

                        <span class="inventory-id">
                            #{{ $inventory->id }}
                        </span>

                        <span class="inventory-date">
                            {{ $inventory->record_date ? $inventory->record_date->format('d/m/Y') : 'N/A' }}
                        </span>

                    </div>


                    <div class="inventory-body">

                        <div class="inventory-info">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $inventory->sede->nom_sede ?? 'N/A' }}
                        </div>

                        <div class="inventory-info">
                            <i class="fas fa-building"></i>
                            {{ $inventory->sede->centro->nom_centro ?? 'N/A' }}
                        </div>

                        <div class="inventory-info">
                            <i class="fas fa-user"></i>
                            {{ $inventory->staff->name ?? 'N/A' }}
                        </div>

                        <div class="inventory-badge">
                            <i class="fas fa-box"></i>
                            {{ $materiales }} materiales
                        </div>

                        <div class="inventory-progress">

                            <div class="progress-label">
                                Nivel inventario
                                <span>{{ $progress }}%</span>
                            </div>

                            <div class="progress-bar-bg">
                                <div class="progress-bar-fill" style="width:{{ $progress }}%">
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            @empty

                <div class="empty-wrapper">

                    <div class="empty-card">

                        <div class="empty-icon">
                            <i class="fas fa-warehouse"></i>
                        </div>

                        <h3>No existen inventarios</h3>

                        <p>Los inventarios registrados aparecerán automáticamente aquí.</p>

                    </div>

                </div>
            @endforelse
        </div>

    @endsection

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
        <style>
            /* DASHBOARD */

            .dashboard-wrapper {
                padding: 10px 5px;
            }

            /* KPI GRID */

            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                gap: 25px;
                margin-bottom: 35px;
            }

            /* KPI CARD */

            .stat-card {
                position: relative;
                display: flex;
                align-items: center;
                gap: 18px;
                padding: 22px;
                border-radius: 20px;
                background: linear-gradient(145deg, #ffffff, #e6e6e6);
                box-shadow:
                    8px 8px 16px rgba(0, 0, 0, .08),
                    -8px -8px 16px rgba(255, 255, 255, .9);
                transition: .35s;
                overflow: hidden;
            }

            .stat-card:hover {
                transform: translateY(-6px);
                box-shadow:
                    12px 12px 22px rgba(0, 0, 0, .12),
                    -10px -10px 20px rgba(255, 255, 255, 1);
            }

            .stat-card::before {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 4px;
                border-radius: 20px 20px 0 0;
                background: #e5e7eb;
            }

            .stat-card:hover::before {
                height: 6px;
                transition: .3s;
            }

            .green::before {
                background: linear-gradient(90deg, #22c55e, #4ade80);
            }

            .blue::before {
                background: linear-gradient(90deg, #3b82f6, #60a5fa);
            }

            .orange::before {
                background: linear-gradient(90deg, #f59e0b, #fbbf24);
            }

            .purple::before {
                background: linear-gradient(90deg, #8b5cf6, #a78bfa);
            }

            /* ICON */

            .stat-icon {
                font-size: 24px;
                width: 60px;
                height: 60px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 14px;
                color: white;
            }

            /* GRADIENTES */

            .green .stat-icon {
                background: linear-gradient(135deg, #22c55e, #16a34a);
            }

            .blue .stat-icon {
                background: linear-gradient(135deg, #3b82f6, #2563eb);
            }

            .orange .stat-icon {
                background: linear-gradient(135deg, #f59e0b, #d97706);
            }

            .purple .stat-icon {
                background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            }

            /* NUMERO */

            .stat-number {
                font-size: 30px;
                font-weight: 800;
                display: block;
            }

            .stat-label {
                font-size: 13px;
                color: #6b7280;
            }

            /* CRECIMIENTO */

            .stat-growth {
                font-size: 12px;
                margin-top: 3px;
            }

            .stat-growth.up {
                color: #16a34a;
            }

            .stat-growth.down {
                color: #ef4444;
            }


            /* INVENTARIOS GRID */

            .inventarios-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 22px;
            }

            /* INVENTORY CARD */

            .inventory-card {

                background: linear-gradient(145deg, #ffffff, #f1f1f1);
                padding: 22px;
                border-radius: 18px;

                box-shadow:
                    7px 7px 16px rgba(0, 0, 0, .08),
                    -7px -7px 16px rgba(255, 255, 255, .9);

                transition: .35s;
                position: relative;
                overflow: hidden;
            }

            .inventory-card:hover {

                transform: translateY(-8px);

                box-shadow:
                    12px 12px 25px rgba(0, 0, 0, .12),
                    -10px -10px 20px rgba(255, 255, 255, 1);

            }

            /* HEADER */

            .inventory-header {
                display: flex;
                justify-content: space-between;
                margin-bottom: 14px;
            }

            /* ID */

            .inventory-id {
                background: linear-gradient(135deg, #16a34a, #22c55e);
                color: white;
                padding: 4px 10px;
                border-radius: 8px;
                font-size: 13px;
                font-weight: 600;
            }

            /* DATE */

            .inventory-date {
                font-size: 13px;
                color: #6b7280;
            }

            /* BODY */

            .inventory-body {
                display: flex;
                flex-direction: column;
                gap: 9px;
            }

            /* INFO */

            .inventory-info {
                font-size: 14px;
                display: flex;
                gap: 8px;
                align-items: center;
            }

            .inventory-info i {
                color: #22c55e;
            }

            /* BADGE */

            .inventory-badge {
                margin-top: 10px;
                font-weight: 600;
                color: #16a34a;
            }

            /* PROGRESS */

            .inventory-progress {
                margin-top: 10px;
            }

            .progress-label {
                display: flex;
                justify-content: space-between;
                font-size: 12px;
                margin-bottom: 4px;
                color: #6b7280;
            }

            .progress-bar-bg {
                height: 8px;
                background: #e5e7eb;
                border-radius: 10px;
                overflow: hidden;
            }

            .progress-bar-fill {

                height: 100%;
                background: linear-gradient(90deg, #22c55e, #4ade80);

                border-radius: 10px;

                transition: width .6s ease;
            }

            /* EMPTY */

            .empty-wrapper {
                grid-column: 1/-1;
                display: flex;
                justify-content: center;
                padding: 80px;
            }

            .empty-card {

                background: linear-gradient(145deg, #ffffff, #f1f1f1);

                padding: 50px;

                border-radius: 22px;

                text-align: center;

                box-shadow:
                    8px 8px 20px rgba(0, 0, 0, .08),
                    -8px -8px 20px rgba(255, 255, 255, .9);

                animation: fadeUp .6s ease;
            }

            .empty-icon {
                font-size: 70px;
                color: #22c55e;
                margin-bottom: 18px;
                animation: floatIcon 4s ease infinite;
            }

            /* ANIMACIONES */

            @keyframes floatIcon {
                0% {
                    transform: translateY(0)
                }

                50% {
                    transform: translateY(-14px)
                }

                100% {
                    transform: translateY(0)
                }
            }

            @keyframes fadeUp {
                from {
                    opacity: 0;
                    transform: translateY(30px)
                }

                to {
                    opacity: 1;
                    transform: translateY(0)
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                const counters = document.querySelectorAll('.counter');

                counters.forEach(counter => {

                    let target = +counter.dataset.target;
                    let count = 0;

                    let speed = 20;

                    let update = () => {

                        let increment = Math.ceil(target / 80);

                        count += increment;

                        if (count > target) count = target;

                        counter.innerText = count.toLocaleString();

                        if (count < target) {
                            requestAnimationFrame(update);
                        }

                    }

                    update();

                });

            });
        </script>
    @endpush
