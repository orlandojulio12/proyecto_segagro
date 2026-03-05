@props([
    'centros' => [],
    'required' => true,
    'centroId' => null,
    'centroNombre' => ''
])

<div class="centro-selector">

    {{-- INPUT VISIBLE --}}
    <div class="centro-filter" onclick="openCentroModal()">
        <div class="filter-icon">
            <i class="fas fa-university"></i>
        </div>

        <input type="hidden" name="centro_id" id="centro_id" value="{{ $centroId }}">

        <input type="text"
               id="centroSeleccionado"
               class="form-control form-control-lg"
               placeholder="Seleccione un centro"
               value="{{ $centroNombre }}"
               readonly
               @if($required) required @endif>
    </div>

    {{-- MODAL --}}
    <div id="centroModal" class="custom-modal">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h5>Seleccionar Centro de Formación</h5>
                <button type="button" class="close-btn" onclick="closeCentroModal()">&times;</button>
            </div>

            <div class="custom-modal-body">

                <div class="search-box mb-3">
                    <input type="text" id="filtroCentro" placeholder="Buscar centro..." />
                    <span class="search-icon">🔍</span>
                </div>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Centro</th>
                            <th width="120">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="listaCentros">
                        @foreach ($centros as $centro)
                            <tr>
                                <td>{{ $centro->nom_centro }}</td>
                                <td>
                                    <button type="button"
                                            class="btn btn-sm btn-success seleccionar-centro"
                                            data-id="{{ $centro->id }}"
                                            data-nombre="{{ $centro->nom_centro }}">
                                        Seleccionar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div id="centroPagination" class="pagination"></div>
            </div>
        </div>
    </div>

</div>

@push('styles')
<style>
.centro-filter {
    position: relative;
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 18px 22px;
    background: linear-gradient(135deg, #f8fff9, #eefaf0);
    border-radius: 18px;
    border: 1px solid #d6f0df;
    box-shadow: 0 10px 26px rgba(0,0,0,.08);
    cursor: pointer;
    transition: all 0.25s ease;
}
.centro-filter:hover { box-shadow: 0 14px 34px rgba(76,209,55,.25); }

.filter-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    background: linear-gradient(135deg, #4cd137, #2ecc71);
    color: #fff;
    font-size: 1.6rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

#centroSeleccionado {
    border: none;
    background: transparent;
    font-size: 1.05rem;
    font-weight: 600;
    cursor: pointer;
}

.custom-modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.55);
    z-index: 99999;
    justify-content: center;
    align-items: center;
}

.custom-modal.show { display: flex; }
body.modal-open { overflow: hidden; }

.custom-modal-content {
    background: #fff;
    border-radius: 16px;
    width: 95%;
    max-width: 900px;
    max-height: 80vh;
    overflow: hidden;
    box-shadow: 0 20px 50px rgba(0,0,0,.35);
    animation: zoomIn .25s ease;
}

.custom-modal-header {
    padding: 18px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #f8fff9, #eefaf0);
    border-bottom: 1px solid #e5f4ea;
}

.custom-modal-body {
    padding: 22px;
    overflow-y: auto;
}

.search-box {
    position: relative;
    display: flex;
    align-items: center;
}
.search-box input {
    width: 100%;
    padding: 12px 44px 12px 16px;
    border-radius: 14px;
    border: 2px solid #e0e0e0;
    transition: all 0.25s ease;
}
.search-box input:focus {
    border-color: #43a047;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(67,160,71,0.1);
}
.search-box .search-icon {
    position: absolute;
    right: 14px;
    font-size: 16px;
    color: #888;
    cursor: pointer;
}

.table-hover tbody tr:hover { background: #f4fff7; }
.btn-success {
    background: linear-gradient(135deg, #4cd137, #2ecc71);
    border: none;
    border-radius: 10px;
    padding: 6px 14px;
    font-weight: 600;
    box-shadow: 0 6px 14px rgba(76,209,55,0.35);
    transition: all 0.25s ease;
}
.btn-success:hover {
    transform: translateY(-1px);
    box-shadow: 0 10px 22px rgba(76,209,55,0.45);
}

.pagination {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 18px;
}
.pagination button {
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    cursor: pointer;
    background: #f1f3f5;
    transition: all 0.2s ease;
}
.pagination button:hover { background: #e0e0e0; }
.pagination button.active {
    background: #4cd137;
    color: #fff;
    font-weight: 700;
}

@keyframes zoomIn {
    from { transform: scale(.9); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
</style>
@endpush

@push('scripts')
<script>
(function () {

    const rowsPerPage = 10; // ahora 5 por página

    function paginate(page = 1) {
        const rows = Array.from(document.querySelectorAll('#listaCentros tr'));
        // Solo filas visibles
        const visibleRows = rows.filter(row => row.style.display !== 'none');
        const totalPages = Math.ceil(visibleRows.length / rowsPerPage);

        // Mostrar solo las filas visibles en esta página
        rows.forEach(row => row.style.display = 'none'); // ocultar todas
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        visibleRows.slice(start, end).forEach(row => row.style.display = '');

        // Render de paginación
        const pagination = document.getElementById('centroPagination');
        pagination.innerHTML = '';
        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.innerText = i;
            btn.classList.toggle('active', i === page);
            btn.onclick = () => paginate(i);
            pagination.appendChild(btn);
        }
    }

    window.openCentroModal = () => {
        document.getElementById('centroModal').classList.add('show');
        document.body.classList.add('modal-open');
        paginate(1);
    };

    window.closeCentroModal = () => {
        document.getElementById('centroModal').classList.remove('show');
        document.body.classList.remove('modal-open');
    };

    document.addEventListener('DOMContentLoaded', () => {

        // Búsqueda dinámica
        document.getElementById('filtroCentro').addEventListener('keyup', function () {
            const val = this.value.toLowerCase();
            document.querySelectorAll('#listaCentros tr').forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(val) ? '' : 'none';
            });
            paginate(1); // ajustar paginación según resultados visibles
        });

        // Selección de centro
        document.querySelectorAll('.seleccionar-centro').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('centro_id').value = btn.dataset.id;
                document.getElementById('centroSeleccionado').value = btn.dataset.nombre;
                closeCentroModal();
            });
        });

        // render inicial
        paginate(1);
    });

})();
</script>
@endpush