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
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                Dashboard
            </a>
            <a href="#" class="nav-item {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                <i class="fas fa-user"></i>
                Usuarios
            </a>
            <a href="{{ route('centros.index') }}" class="nav-item {{ request()->routeIs('centros.*') ? 'active' : '' }}">
                <i class="fas fa-building"></i>
                Centros
            </a>
             <a href="{{ route('sedes.index') }}" class="nav-item {{ request()->routeIs('sedes.*') ? 'active' : '' }}">
                <i class="fas fa-building"></i>
                Sedes
            </a> 
            <a href="{{ route('inventories.index') }}" class="nav-item {{ request()->routeIs('inventary.*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i>
                Inventario
            </a>
            <a href="#" class="nav-item {{ request()->routeIs('calendario.*') ? 'active' : '' }}">
                <i class="fas fa-calendar"></i>
                Calendario
            </a>
            <a href="#" class="nav-item {{ request()->routeIs('infraestructura.*') ? 'active' : '' }}">
                 <i class="fas fa-warehouse"></i>   
                Infraestructura
            </a>
            <a href="#" class="nav-item {{ request()->routeIs('contabilidad.*') ? 'active' : '' }}">
                 <i class="fas fa-chart-line"></i>
                Contabilidad
            </a>
            <a href="#" class="nav-item {{ request()->routeIs('quejas.*') ? 'active' : '' }}">
                <i class="fas fa-comment-dots"></i>
                Quejas
            </a>
            <a href="#" class="nav-item {{ request()->routeIs('planes.*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i>
                Planes de trabajo
            </a>
            <a href="#" class="nav-item {{ request()->routeIs('traslados.*') ? 'active' : '' }}">
                <i class="fas fa-truck"></i>
                Traslados
            </a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="nav-item" style="border: none; background: none; width: 100%; text-align: left;">
                    <i class="fas fa-sign-out-alt"></i>
                    Sign Out
                </button>
            </form>
        </nav>
        
        <div class="sidebar-footer">
            <div style="font-weight: bold; margin-bottom: 5px;">SEGAGRO</div>
            <div style="font-size: 12px; opacity: 0.8;">GESTIONA ACCESO AL ADMINISTRADOR</div>
            <div style="margin-top: 15px;">
                <button style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 8px 16px; border-radius: 6px; cursor: pointer;">
                    CONTACTO
                </button>
            </div>
        </div>
    </div>
    
    <div class="main-content">
        <header class="header">
            <h1>@yield('page-title', 'Dashboard')</h1>
            <div class="header-right">
                <div class="search-box">
                    <input type="text" placeholder="Buscador...">
                    <i class="fas fa-search"></i>
                </div>
                <div class="user-info">
                    <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                    <div>
                        <div style="font-weight: bold;">{{ Auth::user()->name }}</div>
                        <div style="font-size: 12px; color: #666;">Admin</div>
                    </div>
                    <i class="fas fa-chevron-down" style="margin-left: 10px; color: #666;"></i>
                </div>
            </div>
        </header>
        
        <div class="content">
            @yield('dashboard-content')
        </div>
    </div>
</div>
@endsection