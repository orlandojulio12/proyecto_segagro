@props([
    'centros' => [],
    'required' => true,
    'centroId' => null,
    'sedeId' => null,
    'centroNombre' => '',
    'sedeNombre' => ''
])

<div class="centros-sedes-component">
    <!-- Campo Centro -->
    <div class="form-group mb-3">
        <label>Centro de Formación @if($required)<span class="text-danger">*</span>@endif</label>
        <input type="hidden" name="centro_id" id="centro_id" value="{{ $centroId }}">
        <div class="search-box">
            <input type="text" id="centroSeleccionado" 
                   value="{{ $centroNombre }}"
                   placeholder="Seleccione un centro..." 
                   readonly
                   onclick="openModal('centroModal')" 
                   @if($required) required @endif />
            <span class="search-icon" onclick="openModal('centroModal')">🔍</span>
        </div>
    </div>

    <!-- Campo Sede -->
    <div class="form-group mb-3">
        <label>Sede de Formación @if($required)<span class="text-danger">*</span>@endif</label>
        <input type="hidden" name="sede_id" id="sede_id" value="{{ $sedeId }}">
        <div class="search-box">
            <input type="text" id="sedeSeleccionada" 
                   value="{{ $sedeNombre }}"
                   placeholder="Primero seleccione un centro..."
                   readonly 
                   onclick="openModalSede()" 
                   disabled />
            <span class="search-icon" onclick="openModalSede()">🔍</span>
        </div>
    </div>

    <!-- Modal Centros -->
    <div id="centroModal" class="custom-modal">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h5>Seleccionar Centro de Formación</h5>
                <button type="button" class="close-btn" onclick="closeModal('centroModal')">&times;</button>
            </div>
            <div class="custom-modal-body">
                <div class="search-box">
                    <input type="text" id="filtroCentro" placeholder="Buscar centro de formación..." />
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
                    <tbody id="listaCentros">
                        @foreach($centros as $centro)
                            <tr>
                                <td>{{ $centro->nom_centro }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-success seleccionar-centro"
                                        data-id="{{ $centro->id }}" 
                                        data-nombre="{{ $centro->nom_centro }}">
                                        Seleccionar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div id="paginationCentros" class="pagination"></div>
            </div>
        </div>
    </div>

    <!-- Modal Sedes -->
    <div id="sedeModal" class="custom-modal">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h5>Seleccionar Sede de Formación</h5>
                <button type="button" class="close-btn" onclick="closeModal('sedeModal')">&times;</button>
            </div>
            <div class="custom-modal-body">
                <div class="search-box">
                    <input type="text" id="filtroSede" placeholder="Buscar sede de formación..." />
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
                    <tbody id="listaSedes">
                        <!-- Se llenará dinámicamente -->
                    </tbody>
                </table>
                <div id="paginationSedes" class="pagination"></div>
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
    let currentPageCentros = 1, currentPageSedes = 1;

    function getRowsPerPage(totalRows) {
        // Ajusta el máximo de filas por página
        if(totalRows <= 5) return totalRows;   // si hay <=5 filas, mostrar todas en una página
        if(totalRows <= 10) return 5;         // entre 6-10 filas, 5 por página
        return 10;                             // más de 10 filas, máximo 10 por página
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
            btn.onclick = () => { 
                if(idRows==='listaCentros'){ currentPageCentros=i; renderTable('listaCentros','paginationCentros',currentPageCentros);}
                else { currentPageSedes=i; renderTable('listaSedes','paginationSedes',currentPageSedes);}
            };
            pagination.appendChild(btn);
        }
    }

window.openModal = id => {
    const modal = document.getElementById(id);
    modal.classList.add('show');         // muestra el modal
    document.body.classList.add('modal-open'); // bloquea scroll en el body
};

window.closeModal = id => {
    const modal = document.getElementById(id);
    modal.classList.remove('show');      // oculta el modal
    document.body.classList.remove('modal-open'); // permite scroll de nuevo
};

    window.openModalSede = () => {
        const centroId = document.getElementById('centro_id').value;
        if(!centroId){ alert('Primero debe seleccionar un centro'); return; }
        document.getElementById('sedeModal').classList.add('show');
        renderTable('listaSedes','paginationSedes',currentPageSedes);
    };

    document.addEventListener('DOMContentLoaded', () => {

        // Filtros
        document.getElementById('filtroCentro').addEventListener('keyup', function(){
            const filtro=this.value.toLowerCase();
            document.querySelectorAll('#listaCentros tr').forEach(row=>{
                row.style.display = row.innerText.toLowerCase().includes(filtro)?'':'none';
            });
            currentPageCentros = 1;
            renderTable('listaCentros','paginationCentros',currentPageCentros);
        });

        document.getElementById('filtroSede').addEventListener('keyup', function(){
            const filtro=this.value.toLowerCase();
            document.querySelectorAll('#listaSedes tr').forEach(row=>{
                row.style.display = row.innerText.toLowerCase().includes(filtro)?'':'none';
            });
            currentPageSedes = 1;
            renderTable('listaSedes','paginationSedes',currentPageSedes);
        });

        // Seleccionar centro
        document.querySelectorAll('.seleccionar-centro').forEach(btn=>{
            btn.addEventListener('click', function(e){
                e.preventDefault(); e.stopPropagation();
                document.getElementById('centro_id').value = this.dataset.id;
                document.getElementById('centroSeleccionado').value = this.dataset.nombre;

                // Limpiar sede
                document.getElementById('sede_id').value='';
                document.getElementById('sedeSeleccionada').value='';
                document.getElementById('sedeSeleccionada').placeholder='Cargando sedes...';
                document.getElementById('sedeSeleccionada').disabled=true;

                closeModal('centroModal');

                // Traer sedes
                fetch(`/centros/${this.dataset.id}/sedes`).then(r=>r.json()).then(sedes=>{
                    const listaSedes=document.getElementById('listaSedes');
                    listaSedes.innerHTML='';
                    sedes.forEach(sede=>{
                        const tr=document.createElement('tr');
                        tr.innerHTML=`<td>${sede.nom_sede}</td>
                        <td><button type="button" class="btn btn-sm btn-success seleccionar-sede" data-id="${sede.id}" data-nombre="${sede.nom_sede}">Seleccionar</button></td>`;
                        listaSedes.appendChild(tr);
                    });
                    document.getElementById('sedeSeleccionada').placeholder='Seleccione una sede...';
                    document.getElementById('sedeSeleccionada').disabled=false;
                    currentPageSedes = 1;
                    renderTable('listaSedes','paginationSedes',currentPageSedes);
                });
            });
        });

        // Delegación de eventos para sedes
        document.getElementById('listaSedes').addEventListener('click', e=>{
            if(e.target && e.target.classList.contains('seleccionar-sede')){
                e.preventDefault(); e.stopPropagation();
                document.getElementById('sede_id').value=e.target.dataset.id;
                document.getElementById('sedeSeleccionada').value=e.target.dataset.nombre;
                closeModal('sedeModal');
            }
        });

        renderTable('listaCentros','paginationCentros',currentPageCentros);
    });

})();
</script>
@endpush
@endonce