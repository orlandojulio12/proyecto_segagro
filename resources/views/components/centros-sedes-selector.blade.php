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
    border-radius: 14px;
    width: 92%;
    max-width: 780px;
    max-height: 82vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 0 8px 40px rgba(0,0,0,.25);
    animation: zoomIn 0.25s ease;
}
.custom-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 22px;
    border-bottom: 1.5px solid #d1fae5;
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    flex-shrink: 0;
    border-radius: 14px 14px 0 0;
}
.custom-modal-header h5 { margin: 0; font-size: 15px; font-weight: 700; color: #111827; }
.custom-modal-body { padding: 18px 22px; overflow-y: auto; flex: 1; }
.search-box { position: relative; display: flex; align-items: center; width: 100%; }
.search-box input { width: 100%; padding: 10px 42px 10px 16px; border: 1.5px solid #d1d5db; border-radius: 10px; font-size: 13.5px; outline: none; background: #f9fafb; transition: all 0.2s; box-sizing: border-box; }
.search-box input:focus { border-color: #22c55e; background: #fff; box-shadow: 0 0 0 3px rgba(34,197,94,.12); }
.search-box .search-icon { position: absolute; right: 14px; font-size: 15px; color: #9ca3af; cursor: pointer; user-select: none; }
.close-btn { background: none; border: 1.5px solid #d1d5db; width: 30px; height: 30px; border-radius: 7px; cursor: pointer; color: #6b7280; font-size: 16px; display:flex; align-items:center; justify-content:center; transition: all .2s; }
.close-btn:hover { background: #fee2e2; border-color: #fca5a5; color: #dc2626; }
/* Table inside modal — full width, no Bootstrap required */
.custom-modal-body table { width: 100%; border-collapse: collapse; margin-top: 14px; }
.custom-modal-body table thead tr { background: linear-gradient(135deg, #16a34a, #22c55e); }
.custom-modal-body table thead th { padding: 10px 14px; font-size: 12px; font-weight: 700; color: #fff; text-align: left; border: none; }
.custom-modal-body table tbody tr { border-bottom: 1px solid #f3f4f6; transition: background .15s; }
.custom-modal-body table tbody tr:hover { background: #f0fdf4; }
.custom-modal-body table tbody td { padding: 10px 14px; font-size: 13px; color: #374151; vertical-align: middle; border: none; }
.custom-modal-body table tbody td:last-child { width: 120px; text-align: center; }
/* Seleccionar buttons inside modal */
.seleccionar-centro, .seleccionar-sede { background: linear-gradient(135deg, #16a34a, #22c55e); color: white !important; border: none; padding: 6px 14px; border-radius: 7px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all .2s; }
.seleccionar-centro:hover, .seleccionar-sede:hover { transform: translateY(-1px); box-shadow: 0 3px 8px rgba(22,163,74,.35); }
/* Pagination inside modal */
.centros-sedes-component .pagination { display: flex; justify-content: center; margin-top: 14px; gap: 5px; list-style: none; padding: 0; }
.centros-sedes-component .pagination button { background: white; border: 1.5px solid #e5e7eb; padding: 5px 11px; border-radius: 6px; cursor: pointer; font-size: 12.5px; transition: all 0.2s; color: #374151; }
.centros-sedes-component .pagination button.active { background: linear-gradient(135deg, #16a34a, #22c55e); border-color: #16a34a; color: white; font-weight: 600; }
.centros-sedes-component .pagination button:hover:not(.active) { background: #f0fdf4; border-color: #22c55e; color: #16a34a; }
@keyframes zoomIn { from { transform: scale(0.92); opacity: 0; } to { transform: scale(1); opacity: 1; } }
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