@extends('layouts.app')

@section('content')
    <div class="dashboard active">
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <div class="logo-icon">SEG</div>
                    <div class="logo-text">AGRO</div>
                </div>
            </div>

            <nav class="sidebar-nav">

                {{-- DASHBOARD --}}
                <a href="{{ route('dashboard') }}" title="Dashboard"
                    class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span class="nav-text">Dashboard</span>
                </a>

                @role('SuperAdministrador')
                    <a href="{{ route('users.index') }}" title="Usuarios"
                        class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="fas fa-user"></i>
                        <span class="nav-text">Usuarios</span>
                    </a>
                @endrole

                @can('infraestructura.view')
                    <a href="{{ route('centros.index') }}"
                        class="nav-item {{ request()->routeIs('centros.*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i>
                        <span class="nav-text">Centros</span>
                    </a>

                    <a href="{{ route('sedes.index') }}" class="nav-item {{ request()->routeIs('sedes.*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i>
                        <span class="nav-text">Sedes</span>
                    </a>
                @endcan

                {{-- INVENTARIO --}}
                @can('inventario.view')
                    <div class="nav-group">
                        <a href="javascript:void(0)"
                            class="nav-item has-submenu {{ request()->routeIs('inventoriesGen.*') || request()->routeIs('ferreteria.*') ? 'open' : '' }}">
                            <i class="fas fa-clipboard-list"></i>
                            <span class="nav-text">Inventario</span>
                        </a>

                        <ul class="submenu">
                            <li>
                                <a href="{{ route('inventoriesGen.index') }}"
                                    class="{{ request()->routeIs('inventoriesGen.*') ? 'active' : '' }}">
                                    Inventario General
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('ferreteria.index') }}"
                                    class="{{ request()->routeIs('ferreteria.*') ? 'active' : '' }}">
                                    Ferretería
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('salida_ferreteria.index') }}"
                                    class="{{ request()->routeIs('salida_ferreteria.*') ? 'active' : '' }}">
                                    Salida de Ferretería
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('catalogo.index') }}"
                                    class="{{ request()->routeIs('catalogo.*') ? 'active' : '' }}">
                                    Catálogo
                                </a>
                            </li>
                        </ul>
                    </div>
                @endcan

                {{-- CALENDARIO --}}
                {{-- <a href="#" title="Calendario"
                class="nav-item {{ request()->routeIs('calendario.*') ? 'active' : '' }}">
                <i class="fas fa-calendar"></i>
                <span class="nav-text">Calendario</span>
            </a> --}}

                {{-- INFRAESTRUCTURA --}}
                @can('infraestructura.view')
                    <a href="{{ route('infraestructura.index') }}"
                        class="nav-item {{ request()->routeIs('infraestructura.*') ? 'active' : '' }}">
                        <i class="fas fa-warehouse"></i>
                        <span class="nav-text">Infraestructura</span>
                    </a>
                @endcan

                {{-- PRESUPUESTO (YA FUNCIONA) --}}
                @can('presupuesto.view')
                    <a href="{{ route('budget.index') }}"
                        class="nav-item {{ request()->routeIs('budget.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span class="nav-text">Presupuesto</span>
                    </a>
                @endcan

                {{-- PQR --}}
                @can('pqr.view')
                    <a href="{{ route('pqr.index') }}" class="nav-item {{ request()->routeIs('pqr.*') ? 'active' : '' }}">
                        <i class="fas fa-comment-dots"></i>
                        <span class="nav-text">Quejas / PQR</span>
                    </a>
                @endcan

                {{-- CONTRATOS --}}
                @can('contratos.view')
                    <a href="{{ route('contracts.index') }}"
                        class="nav-item {{ request()->routeIs('contracts.*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list"></i>
                        <span class="nav-text">Contrataciones</span>
                    </a>
                @endcan

                {{-- TRASLADOS --}}
                @can('traslados.view')
                    <a href="{{ route('traslados.index') }}"
                        class="nav-item {{ request()->routeIs('traslados.*') ? 'active' : '' }}">
                        <i class="fas fa-truck"></i>
                        <span class="nav-text">Traslados</span>
                    </a>
                @endcan

                {{-- CONFIGURACIÓN --}}
                @can('infraestructura.view')
                    <div class="nav-group">
                        <a href="javascript:void(0)" class="nav-item has-submenu">
                            <i class="fas fa-cogs"></i>
                            <span class="nav-text">Configuración</span>
                        </a>

                        <ul class="submenu">
                            <li><a href="{{ route('dependencies.index') }}">Dependencias</a></li>
                            <li><a href="{{ route('areas.index') }}">Áreas</a></li>
                            <li><a href="{{ route('rooms.index') }}">Salones</a></li>
                        </ul>
                    </div>
                @endcan

                {{-- LOGOUT --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-item" style="border:none;background:none;width:100%;text-align:left;">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="nav-text">Sign Out</span>
                    </button>
                </form>

            </nav>

            <div class="sidebar-footer">
                <div style="font-weight: bold;">SEGAGRO</div>
                <div style="font-size:12px;opacity:.8;">GESTIONA ACCESO AL ADMINISTRADOR</div>
            </div>
        </div>

        <div class="main-content">
            <header class="header">
                <div class="header-right">
                    <button id="toggleSidebar" class="sidebar-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </header>

            <div class="content">
                @yield('dashboard-content')
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // 🔓 Inicializar submenús abiertos por Blade
            document.querySelectorAll('.has-submenu').forEach(trigger => {
                const submenu = trigger.nextElementSibling;
                if (trigger.classList.contains('open') && submenu?.classList.contains('submenu')) {
                    submenu.style.display = 'block';
                }
            });

            // 🔁 Control manual de apertura / cierre
            document.querySelectorAll('.has-submenu').forEach(trigger => {

                trigger.addEventListener('click', e => {

                    // ✅ Permitir navegación normal
                    if (
                        trigger.tagName === 'A' &&
                        trigger.getAttribute('href') &&
                        trigger.getAttribute('href') !== 'javascript:void(0)'
                    ) {
                        return;
                    }

                    e.preventDefault();

                    const submenu = trigger.nextElementSibling;
                    if (!submenu || !submenu.classList.contains('submenu')) return;

                    const isOpen = submenu.style.display === 'block';

                    const container = trigger.parentElement;
                    container.querySelectorAll(':scope > .has-submenu').forEach(item => {
                        const sm = item.nextElementSibling;
                        if (sm?.classList.contains('submenu')) {
                            sm.style.display = 'none';
                            item.classList.remove('open');
                        }
                    });

                    if (!isOpen) {
                        submenu.style.display = 'block';
                        trigger.classList.add('open');
                    } else {
                        submenu.style.display = 'none';
                        trigger.classList.remove('open');
                    }
                });
            });

            const dashboard = document.querySelector('.dashboard');
            const toggleBtn = document.getElementById('toggleSidebar');

            // 🔁 Restaurar estado
            if (localStorage.getItem('sidebar-collapsed') === 'true') {
                dashboard.classList.add('collapsed');
            }

            toggleBtn.addEventListener('click', () => {
                dashboard.classList.toggle('collapsed');

                // 💾 Guardar estado
                localStorage.setItem(
                    'sidebar-collapsed',
                    dashboard.classList.contains('collapsed')
                );
            });

        });
    </script>
@endsection

{{-- ================= STYLES ================= --}}
@push('styles')
    <style>
        /* ================= VARIABLES ================= */
        :root {
            --primary: #16a34a;
            /* Verde principal */
            --primary-soft: #dcfce7;
            /* Verde claro */
            --primary-dark: #15803d;

            --sidebar-bg: #ffffff;
            --sidebar-border: #e5e7eb;
            --hover-bg: #f0fdf4;

            --text-main: #111827;
            --text-muted: #6b7280;

            --text-white: #fff;

            --bg-main: #f8fafc;
            --radius: 10px;
            --transition: 0.25s ease;
        }

        /* ================= SIDEBAR COLLAPSED ================= */

        .sidebar-toggle {
            background: none;
            border: none;
            font-size: 18px;
            color: var(--primary);
            cursor: pointer;
            margin-right: 15px;
        }

        .dashboard.collapsed .sidebar {
            width: 80px;
            min-width: 80px;
        }

        .dashboard.collapsed .logo-text,
        .dashboard.collapsed .submenu,
        .dashboard.collapsed .sidebar-footer {
            display: none !important;
        }

        .nav-text {
            white-space: nowrap;
            transition:
                opacity 0.2s ease,
                transform 0.2s ease,
                max-width 0.3s ease;
            opacity: 1;
            max-width: 200px;
            overflow: hidden;
        }

        .dashboard.collapsed .submenu {
            opacity: 0;
            pointer-events: none;
        }

        .submenu-arrow {
            transition: transform 0.25s ease, opacity 0.2s ease;
        }

        .dashboard.collapsed .submenu-arrow {
            opacity: 0;
        }

        .dashboard.collapsed .nav-item {
            justify-content: center;
        }

        .dashboard.collapsed .nav-item i {
            margin: 0;
        }

        .dashboard.collapsed .nav-text {
            opacity: 0;
            transform: translateX(-10px);
            max-width: 0;
        }

        /* Ajuste del contenido principal */
        .dashboard.collapsed .main-content {
            margin-left: 0;
        }

        /* Tooltip simple al hover */
        .dashboard.collapsed .nav-item {
            position: relative;
        }

        .dashboard.collapsed .nav-item::after {
            content: attr(title);
            position: absolute;
            left: 85px;
            background: #111827;
            color: white;
            padding: 6px 10px;
            font-size: 12px;
            border-radius: 6px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: 0.2s;
        }

        .dashboard.collapsed .nav-item:hover::after {
            opacity: 1;
        }

        /* ================= LAYOUT ================= */
        .dashboard {
            display: flex;
            min-height: 100vh;
            background: var(--bg-main);
            font-family: 'Inter', sans-serif;
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            width: 270px;
            min-width: 270px;
            transition: width 0.3s ease;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--sidebar-border);
        }

        /* HEADER */
        .sidebar-header {
            padding: 22px;
            border-bottom: 1px solid var(--sidebar-border);
        }

        .logo-icon {
            background: var(--primary);
            color: white;
        }

        .logo-text {
            color: var(--text-main);
        }

        /* ================= NAV ================= */
        .sidebar-nav {
            padding: 15px 10px;
            flex: 1;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--text-muted);
            border-radius: var(--radius);
            text-decoration: none;
            transition: var(--transition);
            font-size: 14px;
        }

        .nav-item {
            transition: padding 0.3s ease, justify-content 0.3s ease;
        }

        .dashboard.collapsed .nav-item {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }

        .nav-item i {
            transition: margin 0.3s ease;
        }

        .nav-item:hover {
            background: var(--hover-bg);
            color: var(--primary-dark);
        }

        .nav-item.active {
            background: var(--primary-soft);
            color: var(--primary-dark);
            font-weight: 600;
            box-shadow: inset 4px 0 0 var(--primary);
        }

        /* ================= SUBMENU ================= */

        .submenu-arrow {
            font-size: 11px;
            color: var(--text-muted);
            transition: var(--transition);
        }

        .has-submenu.open .submenu-arrow {
            transform: rotate(180deg);
        }

        .submenu {
            display: none;
            margin: 6px 0 10px 12px;
            padding-left: 12px;
            border-left: 2px solid var(--primary-soft);
        }

        .submenu a {
            display: block;
            padding: 9px 14px;
            color: var(--text-muted);
            font-size: 13px;
            border-radius: 8px;
            text-decoration: none;
            transition: var(--transition);
        }

        .submenu a:hover,
        .submenu a.active {
            background: var(--hover-bg);
            color: var(--primary-dark);
        }

        /* ================= FOOTER ================= */
        .sidebar-footer {
            padding: 18px;
            border-top: 1px solid var(--sidebar-border);
            text-align: center;
            font-size: 12px;
            color: var(--text-white);
        }

        .sidebar-footer button {
            background: var(--primary);
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
        }

        /* ================= MAIN CONTENT ================= */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* ================= HEADER ================= */
        /* HEADER layout */
        .header {
            display: flex;
            align-items: center;
            height: 70px;
            background: #ffffff;
            border-bottom: 1px solid var(--sidebar-border);
            padding: 0 25px;
        }

        /* Contenedor interno */
        .header-right {
            display: flex;
            align-items: center;
            width: 100%;
            gap: 15px;
        }

        /* Empuja el user-info totalmente a la derecha */
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-left: auto;
            /* 🔥 clave */
            cursor: pointer;
        }

        /* Avatar */
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* ================= CONTENT ================= */
        .content {
            padding: 25px;
        }
    </style>
@endpush
