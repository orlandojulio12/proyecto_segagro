@extends('layouts.dashboard')

@section('page-title', 'Configuración de Dependencias')

@section('dashboard-content')

    {{-- ================= HEADER ================= --}}
    <div class="section-header dependency-header-top">
        <div class="header-left">
            <div class="header-icon">
                <i class="fas fa-sitemap"></i>
            </div>
            <div>
                <h2>Dependencias</h2>
                <p>Estructura organizacional del sistema</p>
            </div>
        </div>

        <a href="{{ route('dependencies.create') }}" class="btn btn-add-dependency">
            <i class="fas fa-plus"></i>
            <span>Nueva Dependencia</span>
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success shadow-sm">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    {{-- ================= TREE ================= --}}
    <div class="dependency-tree">

        <div class="tree-header">
            <h5><i class="fas fa-sitemap me-2"></i>Estructura Organizacional</h5>
        </div>

        @forelse($dependencies as $dependency)
            <div class="tree-node">

                {{-- DEPENDENCY --}}
                <div class="dependency-header" data-toggle="tree">
                    <div class="dep-left">
                        <div class="dep-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <div>
                            <strong>{{ $dependency->short_name }}</strong>
                            <div class="text-muted small">{{ $dependency->full_name }}</div>
                        </div>
                    </div>

                    <div class="actions">
                        <a href="{{ route('dependencies.edit', $dependency) }}" class="action-icon edit" title="Editar">
                            <i class="fas fa-pen"></i>
                        </a>

                        <form method="POST" action="{{ route('dependencies.destroy', $dependency) }}"
                            onsubmit="return confirm('¿Eliminar dependencia?')">
                            @csrf @method('DELETE')
                            <button class="action-icon delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- SUBUNITS --}}
                <div class="subunit-list">
                    <div class="tree-line"></div>

                    @forelse($dependency->subunits as $subunit)
                        <div class="subunit-item">
                            <div class="sub-left">
                                <div class="sub-icon">
                                    <i class="fas fa-project-diagram"></i>
                                </div>
                                <span>{{ $subunit->name }}</span>
                                <span class="badge bg-info">{{ $subunit->subunit_code }}</span>
                            </div>

                            <form method="POST" action="{{ route('dependencies.subunit.destroy', $subunit) }}"
                                onsubmit="return confirm('¿Eliminar subdependencia?')">
                                @csrf @method('DELETE')
                                <button class="action-icon delete sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="empty-subunit">
                            <i class="fas fa-info-circle me-1"></i>No hay subdependencias
                        </div>
                    @endforelse

                    {{-- ADD --}}
                    <form method="POST" action="{{ route('dependencies.subunit.store', $dependency) }}"
                        class="add-subunit-form">
                        @csrf
                        <input type="text" name="subunit_code" placeholder="Código" required>
                        <input type="text" name="name" placeholder="Nombre" required>
                        <button class="action-icon add">
                            <i class="fas fa-plus"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-5 text-muted">
                <i class="fas fa-inbox fa-3x mb-3"></i>
                <h5>No hay dependencias registradas</h5>
            </div>
        @endforelse

    </div>
@endsection

{{-- ================= STYLES ================= --}}
@push('styles')
    <style>
        /* ===== HEADER ===== */
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
            border-width: 1;
            border-color: #2ecc71;
            box-shadow: 0 10px 24px rgba(76, 209, 55, .4)
        }

        /* ===== TREE ===== */
        .dependency-tree {
            background: #fff;
            padding: 34px;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08)
        }

        .tree-header {
            border-bottom: 3px solid #4cd137;
            padding-bottom: 14px;
            margin-bottom: 26px
        }

        /* ===== NODE ===== */
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

        /* ===== DEP HEADER ===== */
        .dependency-header {
            background: linear-gradient(135deg, #f8fff9, #eefaf0);
            padding: 22px 30px;
            display: flex;
            justify-content: space-between;
            cursor: pointer
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

        /* ===== ACTIONS ===== */
        .actions {
            display: flex;
            gap: 18px;
            padding-right: 14px
        }

        .action-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f3f5;
            transition: .3s
        }

        .action-icon.edit {
            color: #f39c12
        }

        .action-icon.delete {
            color: #e74c3c
        }

        .action-icon.add {
            color: #2ed573
        }

        .action-icon:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 18px rgba(0, 0, 0, .2)
        }

        .action-icon.sm {
            width: 34px;
            height: 34px
        }

        /* ===== SUB TREE ===== */
        .subunit-list {
            position: relative;
            padding: 26px 40px 26px 80px;
            display: none;
            animation: expand .35s ease forwards
        }

        .tree-line {
            position: absolute;
            left: 46px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #4cd137
        }

        .subunit-item {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0
        }

        .subunit-item::before {
            content: '';
            position: absolute;
            left: -34px;
            top: 50%;
            width: 30px;
            height: 2px;
            background: #4cd137
        }

        .sub-left {
            display: flex;
            gap: 12px;
            align-items: center
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

        /* ===== FORM ===== */
        .add-subunit-form {
            margin-top: 18px;
            display: flex;
            gap: 12px
        }

        .add-subunit-form input {
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid #ced4da
        }

        .empty-subunit {
            color: #888;
            font-size: .9rem;
            padding: 10px 0
        }

        /* ===== ANIM ===== */
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

{{-- ================= SCRIPTS ================= --}}
@push('scripts')
    <script>
        document.querySelectorAll('[data-toggle="tree"]').forEach(header => {
            header.addEventListener('click', () => {
                const content = header.nextElementSibling;
                const open = content.style.display === 'block';

                document.querySelectorAll('.subunit-list').forEach(el => {
                    el.style.display = 'none';
                });

                if (!open) {
                    content.style.display = 'block';
                }
            });
        });
    </script>
@endpush
