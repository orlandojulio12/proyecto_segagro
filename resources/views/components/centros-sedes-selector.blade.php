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
                <button class="close-btn" onclick="closeModal('centroModal')">&times;</button>
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
                <button class="close-btn" onclick="closeModal('sedeModal')">&times;</button>
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
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        backdrop-filter: blur(6px);
        background-color: rgba(0, 0, 0, 0.45);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }

    .custom-modal-content {
        background: #fff;
        border-radius: 12px;
        width: 95%;
        max-width: 900px;
        box-shadow: 0 5px 30px rgba(0, 0, 0, 0.2);
        animation: zoomIn 0.3s ease;
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

    .custom-modal-body {
        padding: 20px;
        max-height: 65vh;
        overflow-y: auto;
    }

    .search-box {
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
    }

    .search-box input {
        width: 100%;
        padding: 12px 40px 12px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 14px;
        outline: none;
        transition: all 0.3s ease;
        background: #f9f9f9;
    }

    .search-box input:focus {
        border-color: #43a047;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(67, 160, 71, 0.1);
    }

    .search-box .search-icon {
        position: absolute;
        right: 14px;
        font-size: 16px;
        color: #888;
        cursor: pointer;
        transition: color 0.3s;
    }

    .search-box input:focus + .search-icon {
        color: #43a047;
    }

    .close-btn {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #666;
    }

    .close-btn:hover {
        color: #000;
    }

    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 15px;
        gap: 5px;
    }

    .pagination button {
        background: #f1f3f5;
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.85rem;
        transition: background 0.2s;
    }

    .pagination button.active {
        background: #007bff;
        color: white;
        font-weight: bold;
    }

    .pagination button:hover {
        background: #e0e0e0;
    }

    .custom-modal.show {
    display: flex !important;
    }

    .custom-modal-content {
    will-change: transform;
    }


    @keyframes zoomIn {
        from {
            transform: scale(0.9);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    (function() {
        let currentPageCentros = 1;
        let currentPageSedes = 1;
        const rowsPerPage = 5;

        function renderTableCentros() {
            const rows = document.querySelectorAll('#listaCentros tr');
            const totalPages = Math.ceil(rows.length / rowsPerPage);

            rows.forEach((row, index) => {
                row.style.display = index >= (currentPageCentros - 1) * rowsPerPage && 
                                   index < currentPageCentros * rowsPerPage ? '' : 'none';
            });

            const pagination = document.getElementById('paginationCentros');
            pagination.innerHTML = '';
            for (let i = 1; i <= totalPages; i++) {
                let btn = document.createElement('button');
                btn.innerText = i;
                btn.classList.toggle('active', i === currentPageCentros);
                btn.onclick = () => {
                    currentPageCentros = i;
                    renderTableCentros();
                };
                pagination.appendChild(btn);
            }
        }

        function renderTableSedes() {
            const rows = document.querySelectorAll('#listaSedes tr');
            const totalPages = Math.ceil(rows.length / rowsPerPage);

            rows.forEach((row, index) => {
                row.style.display = index >= (currentPageSedes - 1) * rowsPerPage && 
                                   index < currentPageSedes * rowsPerPage ? '' : 'none';
            });

            const pagination = document.getElementById('paginationSedes');
            pagination.innerHTML = '';
            for (let i = 1; i <= totalPages; i++) {
                let btn = document.createElement('button');
                btn.innerText = i;
                btn.classList.toggle('active', i === currentPageSedes);
                btn.onclick = () => {
                    currentPageSedes = i;
                    renderTableSedes();
                };
                pagination.appendChild(btn);
            }
        }

        window.openModal = function(id) {
    const modal = document.getElementById(id);
    modal.classList.add('show');

    // Forzar repaint inmediato
    modal.offsetHeight;
};

window.closeModal = function(id) {
    document.getElementById(id).classList.remove('show');
};


        window.openModalSede = function() {
            const centroId = document.getElementById('centro_id').value;
            if (!centroId) {
                alert('Primero debe seleccionar un centro');
                return;
            }
            document.getElementById('sedeModal').style.display = 'flex';
            renderTableSedes();
        };

        document.addEventListener('DOMContentLoaded', function() {
            // Filtro centros
            document.getElementById('filtroCentro').addEventListener('keyup', function() {
                let filtro = this.value.toLowerCase();
                document.querySelectorAll('#listaCentros tr').forEach(row => {
                    row.style.display = row.innerText.toLowerCase().includes(filtro) ? '' : 'none';
                });
            });

            // Filtro sedes
            document.getElementById('filtroSede').addEventListener('keyup', function() {
                let filtro = this.value.toLowerCase();
                document.querySelectorAll('#listaSedes tr').forEach(row => {
                    row.style.display = row.innerText.toLowerCase().includes(filtro) ? '' : 'none';
                });
            });

            // Seleccionar centro
            document.querySelectorAll('.seleccionar-centro').forEach(boton => {
                boton.addEventListener('click', function() {
                    const centroId = this.dataset.id;
                    const centroNombre = this.dataset.nombre;

                    document.getElementById('centro_id').value = centroId;
                    document.getElementById('centroSeleccionado').value = centroNombre;

                    document.getElementById('sede_id').value = '';
                    document.getElementById('sedeSeleccionada').value = '';
                    document.getElementById('sedeSeleccionada').placeholder = 'Cargando sedes...';
                    document.getElementById('sedeSeleccionada').disabled = true;

                    closeModal('centroModal');

                    fetch(`/centros/${centroId}/sedes`)
                        .then(response => response.json())
                        .then(sedes => {
                            const listaSedes = document.getElementById('listaSedes');
                            listaSedes.innerHTML = '';

                            sedes.forEach(sede => {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                                    <td>${sede.nom_sede}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success seleccionar-sede"
                                            data-id="${sede.id}" data-nombre="${sede.nom_sede}">
                                            Seleccionar
                                        </button>
                                    </td>
                                `;
                                listaSedes.appendChild(tr);
                            });

                            document.querySelectorAll('.seleccionar-sede').forEach(btn => {
                                btn.addEventListener('click', function() {
                                    document.getElementById('sede_id').value = this.dataset.id;
                                    document.getElementById('sedeSeleccionada').value = this.dataset.nombre;
                                    closeModal('sedeModal');
                                });
                            });

                            document.getElementById('sedeSeleccionada').placeholder = 'Seleccione una sede...';
                            document.getElementById('sedeSeleccionada').disabled = false;
                            currentPageSedes = 1;
                            renderTableSedes();
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            document.getElementById('sedeSeleccionada').placeholder = 'Error al cargar sedes';
                        });
                });
            });
        });
    })();
</script>
@endpush
@endonce