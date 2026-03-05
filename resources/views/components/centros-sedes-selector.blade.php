@props([
    'centros' => [],
    'required' => true,
    'centroId' => null,
    'sedeId' => null,
    'centroNombre' => '',
    'sedeNombre' => '',
    'prefix' => 'inicial'  {{-- prefijo para diferenciar campos --}}
])

<div class="centros-sedes-component">
    <!-- Campo Centro -->
    <div class="form-group mb-3">
        <label>Centro de Formación @if($required)<span class="text-danger">*</span>@endif</label>
        <input type="hidden" name="{{ $prefix }}_centro_id" id="{{ $prefix }}_centro_id" value="{{ $centroId }}">
        <div class="search-box">
            <input type="text" id="{{ $prefix }}_centroSeleccionado"
                   value="{{ $centroNombre }}"
                   placeholder="Seleccione un centro..."
                   readonly
                   onclick="openModal('{{ $prefix }}_centroModal')"
                   @if($required) required @endif />
            <span class="search-icon" onclick="openModal('{{ $prefix }}_centroModal')">🔍</span>
        </div>
    </div>

    <!-- Campo Sede -->
    <div class="form-group mb-3">
        <label>Sede de Formación @if($required)<span class="text-danger">*</span>@endif</label>
        <input type="hidden" name="{{ $prefix }}_sede_id" id="{{ $prefix }}_sede_id" value="{{ $sedeId }}">
        <div class="search-box">
            <input type="text" id="{{ $prefix }}_sedeSeleccionada"
                   value="{{ $sedeNombre }}"
                   placeholder="Primero seleccione un centro..."
                   readonly
                   onclick="openModalSede('{{ $prefix }}')"
                   disabled />
            <span class="search-icon" onclick="openModalSede('{{ $prefix }}')">🔍</span>
        </div>
    </div>

    <!-- Modal Centros -->
    <div id="{{ $prefix }}_centroModal" class="custom-modal">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h5>Seleccionar Centro de Formación</h5>
                <button type="button" class="close-btn" onclick="closeModal('{{ $prefix }}_centroModal')">&times;</button>
            </div>
            <div class="custom-modal-body">
                <div class="search-box">
                    <input type="text" id="{{ $prefix }}_filtroCentro" placeholder="Buscar centro de formación..." />
                    <span class="search-icon">🔍</span>
                </div>
                <br>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nombre del Centro</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="{{ $prefix }}_listaCentros">
                        @foreach($centros as $centro)
                            <tr>
                                <td>{{ $centro->nom_centro }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-success seleccionar-centro"
                                        data-id="{{ $centro->id }}"
                                        data-nombre="{{ $centro->nom_centro }}"
                                        data-prefix="{{ $prefix }}">
                                        Seleccionar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div id="{{ $prefix }}_paginationCentros" class="pagination"></div>
            </div>
        </div>
    </div>

    <!-- Modal Sedes -->
    <div id="{{ $prefix }}_sedeModal" class="custom-modal">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h5>Seleccionar Sede de Formación</h5>
                <button type="button" class="close-btn" onclick="closeModal('{{ $prefix }}_sedeModal')">&times;</button>
            </div>
            <div class="custom-modal-body">
                <div class="search-box">
                    <input type="text" id="{{ $prefix }}_filtroSede" placeholder="Buscar sede de formación..." />
                    <span class="search-icon">🔍</span>
                </div>
                <br>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nombre de la Sede</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="{{ $prefix }}_listaSedes">
                        <!-- Se llenará dinámicamente -->
                    </tbody>
                </table>
                <div id="{{ $prefix }}_paginationSedes" class="pagination"></div>
            </div>
        </div>
    </div>
</div>

@once
@push('styles')
<style>
    .custom-modal {
    display: none;              /* oculto por defecto */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    z-index: 99999;             /* encima de todo */
    justify-content: center;
    align-items: center;        /* centra vertical y horizontal */
    overflow: hidden;           /* no scroll en el fondo del modal */
}

.custom-modal.show {
    display: flex !important;   /* se muestra al abrir */
}

body.modal-open {
    overflow: hidden;           /* bloquea scroll de la página mientras modal abierto */
}

.custom-modal-content {
    background: #fff;
    border-radius: 12px;
    width: 95%;
    max-width: 900px;
    max-height: 80vh;           /* limita altura para scroll interno */
    overflow-y: auto;           /* scroll solo dentro del modal si hay mucho contenido */
    box-shadow: 0 5px 30px rgba(0,0,0,0.3);
    animation: zoomIn 0.3s ease;
    will-change: transform;
}
    .custom-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #eee;
        background: #f8f9fa;
        border-radius: 12px 12px 0 0;
    }
    .custom-modal-body { padding: 20px; max-height: 65vh; overflow-y: auto; }
    .search-box { position: relative; display: flex; align-items: center; width: 100%; }
    .search-box input { width: 100%; padding: 12px 40px 12px 16px; border: 2px solid #e0e0e0; border-radius: 12px; font-size: 14px; outline: none; background: #f9f9f9; transition: all 0.3s ease; }
    .search-box input:focus { border-color: #43a047; background: #fff; box-shadow: 0 0 0 4px rgba(67,160,71,0.1); }
    .search-box .search-icon { position: absolute; right: 14px; font-size: 16px; color: #888; cursor: pointer; }
    .close-btn { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #666; }
    .close-btn:hover { color: #000; }
    .pagination { display: flex; justify-content: center; margin-top: 15px; gap: 5px; }
    .pagination button { background: #f1f3f5; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 0.85rem; transition: background 0.2s; }
    .pagination button.active { background: #007bff; color: white; font-weight: bold; }
    .pagination button:hover { background: #e0e0e0; }
    @keyframes zoomIn { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }
</style>
@endpush

@push('scripts')
<script>
(function() {
    function getRowsPerPage(totalRows) {
        if(totalRows <= 5) return totalRows;
        if(totalRows <= 10) return 5;
        return 10;
    }

    function renderTable(idRows, idPagination, currentPage) {
        const rows = document.querySelectorAll(`#${idRows} tr`);
        const rowsPerPage = getRowsPerPage(rows.length);
        const totalPages = Math.ceil(rows.length / rowsPerPage);

        rows.forEach((row,index)=>{
            row.style.display = (index >= (currentPage-1)*rowsPerPage && index < currentPage*rowsPerPage) ? '' : 'none';
        });

        const pagination = document.getElementById(idPagination);
        pagination.innerHTML = '';
        for(let i=1;i<=totalPages;i++){
            const btn = document.createElement('button');
            btn.innerText = i;
            btn.classList.toggle('active', i===currentPage);
            btn.onclick = () => renderTable(idRows, idPagination, i);
            pagination.appendChild(btn);
        }
    }

    window.openModal = id => {
        document.getElementById(id).classList.add('show');
        document.body.classList.add('modal-open');
    };

    window.closeModal = id => {
        document.getElementById(id).classList.remove('show');
        document.body.classList.remove('modal-open');
    };

    window.openModalSede = prefix => {
        const centroId = document.getElementById(prefix + '_centro_id').value;
        if(!centroId){ alert('Primero debe seleccionar un centro'); return; }
        openModal(prefix + '_sedeModal');
        renderTable(prefix + '_listaSedes', prefix + '_paginationSedes', 1);
    };

    document.addEventListener('DOMContentLoaded', () => {

        // Filtros
        document.querySelectorAll('[id$="_filtroCentro"]').forEach(input=>{
            input.addEventListener('keyup', function(){
                const prefix = this.id.split('_')[0];
                const filtro=this.value.toLowerCase();
                document.querySelectorAll(`#${prefix}_listaCentros tr`).forEach(row=>{
                    row.style.display = row.innerText.toLowerCase().includes(filtro)?'':'none';
                });
                renderTable(`${prefix}_listaCentros`, `${prefix}_paginationCentros`, 1);
            });
        });

        document.querySelectorAll('[id$="_filtroSede"]').forEach(input=>{
            input.addEventListener('keyup', function(){
                const prefix = this.id.split('_')[0];
                const filtro=this.value.toLowerCase();
                document.querySelectorAll(`#${prefix}_listaSedes tr`).forEach(row=>{
                    row.style.display = row.innerText.toLowerCase().includes(filtro)?'':'none';
                });
                renderTable(`${prefix}_listaSedes`, `${prefix}_paginationSedes`, 1);
            });
        });

        // Seleccionar centro
        document.querySelectorAll('.seleccionar-centro').forEach(btn=>{
            btn.addEventListener('click', function(e){
                e.preventDefault(); e.stopPropagation();
                const prefix = this.dataset.prefix;
                const centroIdInput = document.getElementById(prefix + '_centro_id');
                const centroSeleccionado = document.getElementById(prefix + '_centroSeleccionado');
                const sedeIdInput = document.getElementById(prefix + '_sede_id');
                const sedeSeleccionada = document.getElementById(prefix + '_sedeSeleccionada');

                centroIdInput.value = this.dataset.id;
                centroSeleccionado.value = this.dataset.nombre;

                // Limpiar sede
                sedeIdInput.value='';
                sedeSeleccionada.value='';
                sedeSeleccionada.placeholder='Cargando sedes...';
                sedeSeleccionada.disabled=true;

                closeModal(prefix + '_centroModal');

                // Traer sedes
                fetch(`/centros/${this.dataset.id}/sedes`).then(r=>r.json()).then(sedes=>{
                    const listaSedes=document.getElementById(prefix + '_listaSedes');
                    listaSedes.innerHTML='';
                    sedes.forEach(sede=>{
                        const tr=document.createElement('tr');
                        tr.innerHTML=`<td>${sede.nom_sede}</td>
                        <td><button type="button" class="btn btn-sm btn-success seleccionar-sede" data-id="${sede.id}" data-nombre="${sede.nom_sede}" data-prefix="${prefix}">Seleccionar</button></td>`;
                        listaSedes.appendChild(tr);
                    });
                    sedeSeleccionada.placeholder='Seleccione una sede...';
                    sedeSeleccionada.disabled=false;
                    renderTable(prefix + '_listaSedes', prefix + '_paginationSedes', 1);
                });
            });
        });

        // Delegación de eventos para sedes
        document.body.addEventListener('click', e=>{
            if(e.target && e.target.classList.contains('seleccionar-sede')){
                const prefix = e.target.dataset.prefix;
                document.getElementById(prefix + '_sede_id').value=e.target.dataset.id;
                document.getElementById(prefix + '_sedeSeleccionada').value=e.target.dataset.nombre;
                closeModal(prefix + '_sedeModal');
            }
        });

        // Render inicial de centros
        document.querySelectorAll('[id$="_listaCentros"]').forEach(tbody=>{
            const prefix = tbody.id.split('_')[0];
            renderTable(prefix + '_listaCentros', prefix + '_paginationCentros', 1);
        });
    });
})();
</script>
@endpush
@endonce