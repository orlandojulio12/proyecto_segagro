@extends('layouts.dashboard')

@section('page-title', 'Áreas')

@section('dashboard-content')

    <div class="section-header dependency-header-top">
        <div class="header-left">
            <div class="header-icon">
                <i class="fas fa-layer-group"></i>
            </div>
            <div>
                <h2>Áreas</h2>
                <p>Áreas registradas por sede</p>
            </div>
        </div>

        <a href="{{ route('areas.create') }}" class="btn btn-add-dependency">
            <i class="fas fa-plus"></i>
            <span>Nueva Área</span>
        </a>
    </div>

    {{-- FILTRO POR CENTRO --}}
    <div class="centro-filter">
        <div class="filter-icon">
            <i class="fas fa-filter"></i>
        </div>
        <select id="centroFilter" class="form-select form-select-lg">
            <option value="">Seleccione un centro</option>
            @foreach ($centros as $centro)
                <option value="{{ $centro->id }}">{{ $centro->nom_centro }}</option>
            @endforeach
        </select>
    </div>

    {{-- ÁRBOL / ESTADO VACÍO --}}
    <div class="dependency-tree">

        {{-- ESTADO VACÍO --}}
        <div id="emptyState" class="empty-state">
            <div class="empty-card">
                <div class="empty-icon">
                    <i class="fas fa-filter"></i>
                </div>
                <h4>Seleccione un centro</h4>
                <p>Para visualizar las <strong>sedes</strong> y sus <strong>áreas asociadas</strong>, primero debe elegir un centro.</p>
            </div>
        </div>

        {{-- CONTENIDO DEL ÁRBOL --}}
        <div id="treeContent" style="display:none">
            {{-- Aquí se cargará dinámicamente por AJAX --}}
        </div>

        {{-- NO RESULTADOS --}}
        <div id="noResults" class="text-center py-5 text-muted" style="display:none">
            <i class="fas fa-exclamation-circle fa-3x mb-3"></i>
            <h5>No hay sedes para este centro</h5>
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
.header-left { display: flex; gap: 18px; align-items: center }
.header-icon { width: 60px; height: 60px; border-radius: 16px; background: linear-gradient(135deg, #4cd137, #3db32a); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.8rem }
.btn-add-dependency { background: linear-gradient(135deg, #4cd137, #2ecc71); color: #fff; padding: 14px 26px; border-radius: 14px; display: flex; gap: 10px; align-items: center; border-width: 1px; border-color: #2ecc71; box-shadow: 0 10px 24px rgba(76, 209, 55, .4) }

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
.centro-filter .filter-icon { width: 56px; height: 56px; border-radius: 14px; background: linear-gradient(135deg, #4cd137, #2ecc71); color: #fff; font-size: 1.6rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.centro-filter select { border-radius: 14px; padding: 14px 18px; font-size: 1.05rem; border: 1px solid #ccebd6; }
.centro-filter select:focus { border-color: #4cd137; box-shadow: 0 0 0 4px rgba(76, 209, 55, .15); }
.centro-filter.active { box-shadow: 0 14px 36px rgba(76, 209, 55, .25); }

/* ==== TREE ==== */
.dependency-tree { background: #fff; padding: 34px; border-radius: 18px; box-shadow: 0 10px 30px rgba(0, 0, 0, .08) }
.tree-header { border-bottom: 3px solid #4cd137; padding-bottom: 14px; margin-bottom: 26px }
.empty-state { display: flex; justify-content: center; padding: 60px 20px }
.empty-card { max-width: 420px; text-align: center; padding: 40px 36px; border-radius: 22px; background: linear-gradient(135deg, #ffffff, #f7fdf9); box-shadow: 0 18px 40px rgba(0, 0, 0, .12); border: 1px dashed #4cd137; animation: fadeUp .4s ease; }
.empty-icon { width: 84px; height: 84px; margin: 0 auto 18px; border-radius: 22px; background: linear-gradient(135deg, #4cd137, #2ecc71); color: #fff; font-size: 2.4rem; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 24px rgba(76, 209, 55, .4); }
@keyframes fadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: none; } }

/* ==== NODE ==== */
.tree-node { position: relative; margin-bottom: 24px; border-radius: 16px; border: 1px solid #e9ecef; overflow: hidden; transition: .35s }
.tree-node:hover { transform: translateY(-3px); box-shadow: 0 14px 36px rgba(76, 209, 55, .18) }
.dependency-header { background: linear-gradient(135deg, #f8fff9, #eefaf0); padding: 22px 30px; display: flex; justify-content: space-between; cursor: pointer; align-items: center; }
.dep-left { display: flex; gap: 18px; align-items: center }
.dep-icon { width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #4cd137, #3db32a); color: #fff; font-size: 1.5rem; display: flex; align-items: center; justify-content: center }
.badge-area-count .badge { padding: 6px 10px; font-weight: 500; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
.subunit-list { position: relative; padding: 26px 40px 26px 80px; display: none; animation: expand .35s ease forwards }
.tree-line { position: absolute; left: 46px; top: 0; bottom: 0; width: 2px; background: #4cd137 }
.subunit-item { position: relative; display: flex; justify-content: space-between; align-items: center; padding: 12px 0 }
.subunit-item::before { content: ''; position: absolute; left: -34px; top: 50%; width: 30px; height: 2px; background: #4cd137 }
.sub-left { display: flex; gap: 12px; align-items: center }
.sub-icon { width: 32px; height: 32px; border-radius: 8px; background: #edf2f7; color: #6c757d; display: flex; align-items: center; justify-content: center }
.sub-left .badge.bg-secondary { background: #6c757d; color: #fff; font-size: 0.75rem; margin-left: 6px; padding: 3px 8px; }
@keyframes expand { from { opacity: 0; transform: translateY(-6px) } to { opacity: 1; transform: none } }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const filter = document.getElementById('centroFilter');
    const filterWrapper = document.querySelector('.centro-filter');
    const emptyState = document.getElementById('emptyState');
    const treeContent = document.getElementById('treeContent');
    const noResults = document.getElementById('noResults');

    filter.addEventListener('change', function() {
        const centroId = this.value;
        filterWrapper.classList.toggle('active', !!centroId);

        if (!centroId) {
            emptyState.style.display = 'flex';
            treeContent.style.display = 'none';
            noResults.style.display = 'none';
            return;
        }

        // AJAX: cargar sedes y áreas
        fetch(`/centros/${centroId}/sedes-areas`)
            .then(res => res.text())
            .then(html => {
                treeContent.innerHTML = html;
                treeContent.style.display = 'block';
                emptyState.style.display = 'none';
                noResults.style.display = html.trim() ? 'none' : 'block';

                // Toggle de sedes
                document.querySelectorAll('[data-toggle="tree"]').forEach(header => {
                    header.addEventListener('click', function() {
                        const node = this.closest('.tree-node');
                        const sublist = node.querySelector('.subunit-list');
                        const isOpen = sublist.style.display === 'block';

                        document.querySelectorAll('.subunit-list').forEach(list => list.style.display = 'none');
                        document.querySelectorAll('.tree-node').forEach(n => n.classList.remove('open'));

                        if (!isOpen) {
                            sublist.style.display = 'block';
                            node.classList.add('open');
                        }
                    });
                });
            });
    });
});
</script>
@endpush