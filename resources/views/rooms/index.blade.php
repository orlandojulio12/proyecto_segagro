@extends('layouts.dashboard')

@section('page-title', 'Rooms')

@section('dashboard-content')

    <div class="section-header dependency-header-top">
        <div class="header-left">
            <div class="header-icon"><i class="fas fa-chalkboard"></i></div>
            <div>
                <h2>Cuartos - Salones</h2>
                <p>Salones por área</p>
            </div>
        </div>

        <a href="{{ route('rooms.create') }}" class="btn btn-add-dependency">
            <i class="fas fa-plus"></i>
            <span>Nuevo Salon</span>
        </a>
    </div>

    {{-- ==== FILTROS CENTRO Y SEDE ==== --}}
    <div class="centro-filter">
        <div class="filter-icon"><i class="fas fa-building"></i></div>

        <select id="filtro_centro_id">
            <option value="">-- Seleccione un Centro --</option>
            @foreach ($centros as $centro)
                <option value="{{ $centro->id }}">{{ $centro->nom_centro }}</option>
            @endforeach
        </select>

        <select id="filtro_sede_id" disabled>
            <option value="">-- Seleccione una Sede --</option>
        </select>
    </div>

    {{-- Contenedor donde se va a renderizar el árbol --}}
    <div id="roomsTreeContainer" class="dependency-tree mt-4">
        <div class="empty-state">
            <div class="empty-card">
                <div class="empty-icon"><i class="fas fa-info-circle"></i></div>
                <h5>Seleccione un centro y una sede para ver las áreas</h5>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        /* ==== HEADER ==== */
        .dependency-header-top {
            background: linear-gradient(135deg, #f8fff9, #eefaf0);
            padding: 26px 32px;
            border-radius: 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08)
        }

        .header-left {
            display: flex;
            gap: 18px;
            align-items: center
        }

        .header-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            background: linear-gradient(135deg, #4cd137, #3db32a);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem
        }

        .btn-add-dependency {
            background: linear-gradient(135deg, #4cd137, #2ecc71);
            color: #fff;
            padding: 14px 26px;
            border-radius: 14px;
            display: flex;
            gap: 10px;
            align-items: center;
            border-width: 1px;
            border-color: #2ecc71;
            box-shadow: 0 10px 24px rgba(76, 209, 55, .4)
        }

        /* ==== CENTRO FILTER ==== */
        .centro-filter {
            position: relative;
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px 24px;
            background: linear-gradient(135deg, #f8fff9, #eefaf0);
            border-radius: 18px;
            box-shadow: 0 10px 26px rgba(0, 0, 0, .08);
            border: 1px solid #e6f4ea;
        }

        .centro-filter .filter-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: linear-gradient(135deg, #4cd137, #2ecc71);
            color: #fff;
            font-size: 1.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .centro-filter select {
            border-radius: 14px;
            padding: 14px 18px;
            font-size: 1.05rem;
            border: 1px solid #ccebd6;
        }

        .centro-filter select:focus {
            border-color: #4cd137;
            box-shadow: 0 0 0 4px rgba(76, 209, 55, .15);
        }

        .centro-filter.active {
            box-shadow: 0 14px 36px rgba(76, 209, 55, .25);
        }

        /* ==== TREE ==== */
        .dependency-tree {
            background: #fff;
            padding: 34px;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
            overflow: visible;
            /* permite que todo el árbol se vea */
        }

        .tree-header {
            border-bottom: 3px solid #4cd137;
            padding-bottom: 14px;
            margin-bottom: 26px
        }

        .empty-state {
            display: flex;
            justify-content: center;
            padding: 60px 20px
        }

        .empty-card {
            max-width: 420px;
            text-align: center;
            padding: 40px 36px;
            border-radius: 22px;
            background: linear-gradient(135deg, #ffffff, #f7fdf9);
            box-shadow: 0 18px 40px rgba(0, 0, 0, .12);
            border: 1px dashed #4cd137;
            animation: fadeUp .4s ease;
        }

        .empty-icon {
            width: 84px;
            height: 84px;
            margin: 0 auto 18px;
            border-radius: 22px;
            background: linear-gradient(135deg, #4cd137, #2ecc71);
            color: #fff;
            font-size: 2.4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 24px rgba(76, 209, 55, .4);
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        /* ==== NODE ==== */
        .tree-node {
            position: relative;
            margin-bottom: 24px;
            border-radius: 16px;
            border: 1px solid #e9ecef;
            overflow: hidden;
            transition: .35s
        }

        .tree-node:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 36px rgba(76, 209, 55, .18)
        }

        .dependency-header {
            background: linear-gradient(135deg, #f8fff9, #eefaf0);
            padding: 22px 30px;
            display: flex;
            justify-content: space-between;
            cursor: pointer;
            align-items: center;
        }

        .dep-left {
            display: flex;
            gap: 18px;
            align-items: center
        }

        .dep-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, #4cd137, #3db32a);
            color: #fff;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center
        }

        .badge-area-count .badge {
            padding: 6px 10px;
            font-weight: 500;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .subunit-list .tree-line {
            position: absolute;
            left: 30px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #4cd137;
        }

        .subunit-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            position: relative;
        }

        .subunit-item::before {
            content: '';
            position: absolute;
            left: -34px;
            top: 50%;
            width: 30px;
            height: 2px;
            background: #4cd137;
        }

        .subunit-list {
            padding-left: 60px;
            position: relative;
            display: block;
            /* para que se vea todo inicialmente */
        }

        .subunit-list .tree-line {
            position: absolute;
            left: 30px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #4cd137;
        }

        .tree-line {
            position: absolute;
            left: 46px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #4cd137
        }

        .sub-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sub-info {
            display: flex;
            flex-direction: column;
        }

        .sub-name {
            font-weight: 500;
        }

        .sub-capacity {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .sub-actions {
            flex-shrink: 0;
        }

        .toggle-icon {
            font-size: 0.9rem;
            color: #4cd137;
        }

        .sub-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #edf2f7;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center
        }

        .sub-left .badge.bg-secondary {
            background: #6c757d;
            color: #fff;
            font-size: 0.75rem;
            margin-left: 6px;
            padding: 3px 8px;
        }

        @keyframes expand {
            from {
                opacity: 0;
                transform: translateY(-6px)
            }

            to {
                opacity: 1;
                transform: none
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const centroSelect = document.getElementById('filtro_centro_id');
            const sedeSelect = document.getElementById('filtro_sede_id');
            const roomsContainer = document.getElementById('roomsTreeContainer');

            // Cuando cambie el centro, cargamos sus sedes
            centroSelect.addEventListener('change', function() {
                const centroId = this.value;
                sedeSelect.innerHTML = '<option value="">-- Seleccione una Sede --</option>';
                sedeSelect.disabled = true;

                if (!centroId) return;

                fetch(`/centros/${centroId}/sedes`) // Ruta que devuelve JSON de sedes
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(sede => {
                            const option = document.createElement('option');
                            option.value = sede.id;
                            option.textContent = sede.nom_sede;
                            sedeSelect.appendChild(option);
                        });
                        sedeSelect.disabled = false;
                    });

                roomsContainer.innerHTML = `
            <div class="empty-state">
                <div class="empty-card">
                    <div class="empty-icon"><i class="fas fa-info-circle"></i></div>
                    <h5>Seleccione una sede para ver las áreas</h5>
                </div>
            </div>`;
            });

            // Cuando cambie la sede, cargamos las áreas
            sedeSelect.addEventListener('change', function() {
                const centroId = centroSelect.value;
                const sedeId = this.value;

                if (!sedeId) {
                    roomsContainer.innerHTML = `
            <div class="empty-state">
                <div class="empty-card">
                    <div class="empty-icon"><i class="fas fa-info-circle"></i></div>
                    <h5>Seleccione una sede para ver las áreas</h5>
                </div>
            </div>`;
                    return;
                }

                fetch(`{{ route('rooms.filter') }}?centro_id=${centroId}&sede_id=${sedeId}`)
                    .then(res => res.text())
                    .then(html => {
                        roomsContainer.innerHTML = html;
                        initTreeToggle(roomsContainer);
                    });
            });

            // ==== Función para abrir/cerrar nodos del árbol ====
            function initTreeToggle(container) {
                container.querySelectorAll('.dependency-header[data-toggle="tree"]').forEach(header => {
                    const subunitList = header.nextElementSibling;
                    if (!subunitList) return;

                    header.addEventListener('click', () => {
                        const isOpen = subunitList.dataset.open === 'true';
                        const icon = header.querySelector('.toggle-icon i');

                        if (isOpen) {
                            subunitList.style.height = '0px';
                            subunitList.dataset.open = 'false';
                            if (icon) icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
                        } else {
                            const height = subunitList.scrollHeight + 'px';
                            subunitList.style.height = height;
                            subunitList.dataset.open = 'true';
                            if (icon) icon.classList.replace('fa-chevron-down', 'fa-chevron-up');

                            subunitList.addEventListener('transitionend', () => {
                                if (subunitList.dataset.open === 'true') subunitList.style
                                    .height = 'auto';
                            }, {
                                once: true
                            });
                        }
                    });

                    // Inicialmente abierto
                    if (subunitList.children.length > 0) {
                        subunitList.style.height = 'auto';
                        subunitList.dataset.open = 'true';
                        if (header.querySelector('.toggle-icon i')) header.querySelector('.toggle-icon i')
                            .classList.replace('fa-chevron-down', 'fa-chevron-up');
                    } else {
                        subunitList.style.height = '0px';
                        subunitList.dataset.open = 'false';
                    }
                });
            }

        });
    </script>
@endpush
