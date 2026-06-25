@extends('layouts.app')

@section('content')
<div class="dashboard active">

    {{-- ══════════════ SIDEBAR ══════════════ --}}
    <aside class="sidebar" id="sidebar">

        {{-- Logo --}}
        <div class="sidebar-header">
            <a href="{{ route('dashboard') }}" class="sidebar-logo-link">
                <img src="{{ asset('assets/Logos/SegagroSidebar.png') }}" alt="SegAgro" class="sidebar-logo-img sidebar-logo-full">
                <img src="{{ asset('assets/Logos/SegagroIcon.png') }}" alt="SegAgro" class="sidebar-logo-img sidebar-logo-icon">
            </a>
        </div>

        {{-- Nav --}}
        <nav class="sidebar-nav" id="sidebarNav">

            {{-- ── Principal ── --}}
            <a href="{{ route('dashboard') }}" title="Dashboard"
               class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span class="nav-text">Dashboard</span>
            </a>

            @role('SuperAdministrador')
            <a href="{{ route('users.index') }}" title="Usuarios"
               class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span class="nav-text">Usuarios</span>
            </a>
            @endrole

            {{-- ── Categoría: Gestión ── --}}
            <div class="nav-category"><span>Gestión</span></div>

            @can('pqr.view')
            <a href="{{ route('pqr.index') }}" title="PQR"
               class="nav-item {{ request()->routeIs('pqr.*') ? 'active' : '' }}">
                <i class="fas fa-comment-dots"></i>
                <span class="nav-text">Quejas / PQR</span>
            </a>
            @endcan

            @can('infraestructura.view')
            <a href="{{ route('infraestructura.index') }}" title="Infraestructura"
               class="nav-item {{ request()->routeIs('infraestructura.*') ? 'active' : '' }}">
                <i class="fas fa-warehouse"></i>
                <span class="nav-text">Infraestructura</span>
            </a>
            @endcan

            @can('presupuesto.view')
            <a href="{{ route('budget.index') }}" title="Presupuesto"
               class="nav-item {{ request()->routeIs('budget.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span class="nav-text">Presupuesto</span>
            </a>
            @endcan

            @can('contratos.view')
            <a href="{{ route('contracts.index') }}" title="Contratos"
               class="nav-item {{ request()->routeIs('contracts.*') ? 'active' : '' }}">
                <i class="fas fa-file-signature"></i>
                <span class="nav-text">Contratos</span>
            </a>
            @endcan

            @can('traslados.view')
            <a href="{{ route('traslados.index') }}" title="Traslados"
               class="nav-item {{ request()->routeIs('traslados.*') ? 'active' : '' }}">
                <i class="fas fa-truck"></i>
                <span class="nav-text">Traslados</span>
            </a>
            @endcan

            @can('semoviente.view')
            <a href="{{ route('semoviente.index') }}" title="Semoviente"
               class="nav-item {{ request()->routeIs('semoviente.*') ? 'active' : '' }}">
                <i class="fas fa-horse"></i>
                <span class="nav-text">Semoviente</span>
            </a>
            @endcan

            {{-- ── Categoría: Académico ── --}}
            <div class="nav-category"><span>Académico</span></div>

            <a href="{{ route('instructores.index') }}" title="Instructores"
               class="nav-item {{ request()->routeIs('instructores.*') ? 'active' : '' }}">
                <i class="fas fa-user-tie"></i>
                <span class="nav-text">Instructores</span>
            </a>

            <a href="{{ route('fichas.index') }}" title="Fichas"
               class="nav-item {{ request()->routeIs('fichas.*') ? 'active' : '' }}">
                <i class="fas fa-id-card"></i>
                <span class="nav-text">Fichas</span>
            </a>

            <a href="{{ route('horarios.index') }}" title="Horarios"
               class="nav-item {{ request()->routeIs('horarios.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i>
                <span class="nav-text">Horarios</span>
            </a>

            {{-- ── Categoría: Inventario ── --}}
            @can('inventario.view')
            <div class="nav-category"><span>Inventario</span></div>

            @php
                $inventarioOpen = request()->routeIs('inventoriesGen.*')
                    || request()->routeIs('ferreteria.*')
                    || request()->routeIs('salida_ferreteria.*')
                    || request()->routeIs('catalogo.*');
            @endphp

            <div class="nav-group">
                <a href="javascript:void(0)" title="Inventario"
                   class="nav-item has-submenu {{ $inventarioOpen ? 'open' : '' }}">
                    <i class="fas fa-boxes"></i>
                    <span class="nav-text">Inventario</span>
                    <i class="fas fa-chevron-down submenu-arrow ms-auto"></i>
                </a>
                <ul class="submenu {{ $inventarioOpen ? 'submenu-open' : '' }}">
                    <li>
                        <a href="{{ route('inventoriesGen.index') }}"
                           class="{{ request()->routeIs('inventoriesGen.*') ? 'active' : '' }}">
                            <i class="fas fa-list-alt"></i> Inventario General
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('ferreteria.index') }}"
                           class="{{ request()->routeIs('ferreteria.*') && !request()->routeIs('salida_ferreteria.*') && !request()->routeIs('catalogo.*') ? 'active' : '' }}">
                            <i class="fas fa-tools"></i> Ferretería
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('salida_ferreteria.index') }}"
                           class="{{ request()->routeIs('salida_ferreteria.*') ? 'active' : '' }}">
                            <i class="fas fa-sign-out-alt"></i> Salida de Ferretería
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('catalogo.index') }}"
                           class="{{ request()->routeIs('catalogo.*') ? 'active' : '' }}">
                            <i class="fas fa-tag"></i> Catálogo
                        </a>
                    </li>
                </ul>
            </div>
            @endcan

            {{-- ── Categoría: Configuración ── --}}
            @can('infraestructura.view')
            <div class="nav-category"><span>Configuración</span></div>

            @php
                $configOpen = request()->routeIs('dependencies.*')
                    || request()->routeIs('areas.*')
                    || request()->routeIs('rooms.*')
                    || request()->routeIs('centros.*')
                    || request()->routeIs('sedes.*');
            @endphp

            <div class="nav-group">
                <a href="javascript:void(0)" title="Configuración"
                   class="nav-item has-submenu {{ $configOpen ? 'open' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span class="nav-text">Configuración</span>
                    <i class="fas fa-chevron-down submenu-arrow ms-auto"></i>
                </a>
                <ul class="submenu {{ $configOpen ? 'submenu-open' : '' }}">
                    <li>
                        <a href="{{ route('centros.index') }}"
                           class="{{ request()->routeIs('centros.*') ? 'active' : '' }}">
                            <i class="fas fa-university"></i> Centros
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('sedes.index') }}"
                           class="{{ request()->routeIs('sedes.*') ? 'active' : '' }}">
                            <i class="fas fa-map-marker-alt"></i> Sedes
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dependencies.index') }}"
                           class="{{ request()->routeIs('dependencies.*') ? 'active' : '' }}">
                            <i class="fas fa-sitemap"></i> Dependencias
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('areas.index') }}"
                           class="{{ request()->routeIs('areas.*') ? 'active' : '' }}">
                            <i class="fas fa-layer-group"></i> Áreas
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('rooms.index') }}"
                           class="{{ request()->routeIs('rooms.*') ? 'active' : '' }}">
                            <i class="fas fa-door-open"></i> Salones
                        </a>
                    </li>
                </ul>
            </div>
            @endcan

            {{-- ── Cerrar sesión ── --}}
            <div class="nav-category"><span>Cuenta</span></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-item nav-item-btn" title="Cerrar sesión">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="nav-text">Cerrar sesión</span>
                </button>
            </form>

        </nav>
    </aside>

    {{-- ══════════════ MAIN ══════════════ --}}
    <div class="main-content">

        {{-- Header --}}
        <header class="header">
            <div class="header-right">
                <button id="toggleSidebar" class="sidebar-toggle" title="Colapsar menú">
                    <i class="fas fa-bars"></i>
                </button>

                {{-- Notificaciones --}}
                @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                <a href="{{ route('notifications.index') }}"
                   class="header-icon-btn {{ $unreadCount > 0 ? 'has-badge' : '' }}"
                   data-count="{{ $unreadCount > 99 ? '99+' : $unreadCount }}"
                   title="Notificaciones">
                    <i class="fas fa-bell"></i>
                </a>

                {{-- Usuario --}}
                <div class="user-info">
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div class="user-detail">
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-role">{{ auth()->user()->getRoleNames()->first() ?? 'Usuario' }}</div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Contenido --}}
        <div class="content">
            @yield('dashboard-content')
        </div>
    </div>
</div>

{{-- ══════════════ SCRIPT ══════════════ --}}
<script>
document.addEventListener('DOMContentLoaded', () => {

    // ── Abrir submenús marcados con .submenu-open en el servidor ──
    document.querySelectorAll('.submenu.submenu-open').forEach(sm => {
        sm.style.display = 'block';
    });

    // ── Toggle manual de submenús ──
    document.querySelectorAll('.has-submenu').forEach(trigger => {
        trigger.addEventListener('click', e => {
            if (trigger.getAttribute('href') && trigger.getAttribute('href') !== 'javascript:void(0)') return;
            e.preventDefault();

            const submenu = trigger.nextElementSibling;
            if (!submenu?.classList.contains('submenu')) return;

            const isOpen = submenu.style.display === 'block';
            submenu.style.display = isOpen ? 'none' : 'block';
            trigger.classList.toggle('open', !isOpen);
        });
    });

    // ── Toggle sidebar collapse ──
    const dashboard = document.querySelector('.dashboard');
    const toggleBtn = document.getElementById('toggleSidebar');

    if (localStorage.getItem('sidebar-collapsed') === 'true') {
        dashboard.classList.add('collapsed');
    }

    toggleBtn.addEventListener('click', () => {
        dashboard.classList.toggle('collapsed');
        localStorage.setItem('sidebar-collapsed', dashboard.classList.contains('collapsed'));
    });

    // ── Global Drawer functions ──
    window.openDrawer = function(id) {
        document.getElementById(id).classList.add('open');
        document.getElementById(id + 'Overlay').classList.add('open');
        document.body.style.overflow = 'hidden';
    };
    window.closeDrawer = function(id) {
        document.getElementById(id).classList.remove('open');
        document.getElementById(id + 'Overlay').classList.remove('open');
        document.body.style.overflow = '';
    };
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('sg-drawer-overlay')) {
            const overlayId = e.target.id;
            const drawerId = overlayId.replace('Overlay', '');
            closeDrawer(drawerId);
        }
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.sg-drawer.open').forEach(d => closeDrawer(d.id));
        }
    });
});
</script>
@endsection

{{-- ══════════════ STYLES ══════════════ --}}
@push('styles')
<style>
/* ── Variables ── */
:root {
    --primary:      #16a34a;
    --primary-soft: #dcfce7;
    --primary-dark: #15803d;
    --sidebar-bg:   #ffffff;
    --sidebar-w:    268px;
    --text-main:    #111827;
    --text-muted:   #6b7280;
    --bg-main:      #f8fafc;
    --radius:       10px;
    --transition:   0.25s ease;
    --border:       #e5e7eb;
}

/* ── Layout ── */
.dashboard { display: flex; min-height: 100vh; background: var(--bg-main); font-family: 'Segoe UI', sans-serif; }

/* ═══════════════════════ SIDEBAR ═══════════════════════ */
.sidebar {
    width: var(--sidebar-w);
    min-width: var(--sidebar-w);
    background: var(--sidebar-bg);
    border-right: 1px solid var(--border);
    display: flex;
    flex-direction: column;
    transition: width .3s ease, min-width .3s ease;
    overflow: hidden;
}

/* Header */
.sidebar-header {
    padding: 12px 16px;
    border-bottom: 1px solid var(--border);
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    display: flex;
    align-items: center;
}
.sidebar-logo-link { display: block; line-height: 0; }
.sidebar-logo-full { height: 46px; width: auto; display: block; object-fit: contain; }
.sidebar-logo-icon { height: 36px; width: 36px; display: none; object-fit: contain; border-radius: 8px; }

/* Nav area */
.sidebar-nav {
    flex: 1;
    overflow-y: auto;
    padding: 10px 8px 20px;
    scrollbar-width: thin;
    scrollbar-color: var(--border) transparent;
}
.sidebar-nav::-webkit-scrollbar { width: 4px; }
.sidebar-nav::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

/* Category label */
.nav-category {
    padding: 16px 10px 4px;
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
    overflow: hidden;
}
.nav-category span {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: var(--text-muted);
    opacity: .7;
}
.nav-category::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--border);
    min-width: 10px;
}

/* Nav item */
.nav-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    color: var(--text-muted);
    text-decoration: none;
    border-radius: 8px;
    font-size: 13.5px;
    transition: background .2s, color .2s;
    white-space: nowrap;
    overflow: hidden;
    cursor: pointer;
    width: 100%;
    border: none;
    background: transparent;
    text-align: left;
}
.nav-item:hover { background: var(--primary-soft); color: var(--primary-dark); }
.nav-item.active {
    background: var(--primary-soft);
    color: var(--primary-dark);
    font-weight: 600;
    box-shadow: inset 3px 0 0 var(--primary);
}
.nav-item i { font-size: 15px; flex-shrink: 0; width: 18px; text-align: center; }
.nav-item-btn { cursor: pointer; }

/* Nav text animation */
.nav-text { transition: opacity .2s, max-width .3s; opacity: 1; max-width: 200px; }

/* Submenu arrow */
.submenu-arrow { font-size: 10px !important; transition: transform .25s; }
.has-submenu.open .submenu-arrow { transform: rotate(180deg); }

/* Submenu */
.submenu {
    display: none;
    margin: 4px 0 6px 8px;
    padding-left: 10px;
    border-left: 2px solid var(--primary-soft);
    list-style: none;
}
.submenu li a {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    color: var(--text-muted);
    font-size: 13px;
    border-radius: 7px;
    text-decoration: none;
    transition: background .2s, color .2s;
    white-space: nowrap;
    overflow: hidden;
}
.submenu li a i { font-size: 12px; width: 14px; text-align: center; }
.submenu li a:hover,
.submenu li a.active {
    background: var(--primary-soft);
    color: var(--primary-dark);
    font-weight: 500;
}
.submenu li a.active { box-shadow: inset 3px 0 0 var(--primary); }

/* ══ COLLAPSED ══ */
.dashboard.collapsed .sidebar { width: 68px; min-width: 68px; }
.dashboard.collapsed .sidebar-logo-full { display: none; }
.dashboard.collapsed .sidebar-logo-icon { display: block; }
.dashboard.collapsed .nav-category,
.dashboard.collapsed .submenu { display: none !important; }
.dashboard.collapsed .nav-text { opacity: 0; max-width: 0; }
.dashboard.collapsed .submenu-arrow { display: none; }
.dashboard.collapsed .nav-item { justify-content: center; padding: 11px 0; }
.dashboard.collapsed .nav-item { position: relative; }
.dashboard.collapsed .nav-item::after {
    content: attr(title);
    position: absolute;
    left: 74px;
    background: #1f2937;
    color: white;
    padding: 5px 10px;
    font-size: 12px;
    border-radius: 6px;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: opacity .2s;
    z-index: 100;
}
.dashboard.collapsed .nav-item:hover::after { opacity: 1; }

/* ═══════════════════════ HEADER ═══════════════════════ */
.main-content { flex: 1; display: flex; flex-direction: column; min-width: 0; }

.header {
    height: 64px;
    background: white;
    border-bottom: 1px solid var(--border);
    padding: 0 24px;
    display: flex;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 50;
}
.header-right { display: flex; align-items: center; width: 100%; gap: 12px; }

.sidebar-toggle {
    background: none;
    border: none;
    color: var(--primary);
    font-size: 18px;
    cursor: pointer;
    padding: 6px;
    border-radius: 6px;
    transition: background .2s;
}
.sidebar-toggle:hover { background: var(--primary-soft); }

/* Notification bell */
.header-icon-btn {
    position: relative;
    color: var(--text-muted);
    font-size: 18px;
    text-decoration: none;
    padding: 6px;
    border-radius: 8px;
    transition: background .2s, color .2s;
    margin-left: auto;
}
.header-icon-btn:hover { background: var(--primary-soft); color: var(--primary-dark); }
.header-icon-btn.has-badge::after {
    content: attr(data-count);
    position: absolute;
    top: -4px; right: -4px;
    background: #dc2626;
    color: white;
    font-size: 9px;
    font-weight: 700;
    min-width: 16px;
    height: 16px;
    border-radius: 99px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 3px;
    border: 2px solid white;
}

/* User info */
.user-info { display: flex; align-items: center; gap: 10px; cursor: pointer; }
.user-avatar {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 15px;
    flex-shrink: 0;
}
.user-name  { font-size: 13px; font-weight: 600; color: var(--text-main); }
.user-role  { font-size: 11px; color: var(--text-muted); }

/* ═══════════════════════ CONTENT ═══════════════════════ */
.content { padding: 24px; flex: 1; }
</style>
@endpush
