@extends('layouts.dashboard')

@section('page-title', 'Necesidad de Infraestructura')

@section('dashboard-content')


<div class="content-card">
    <form action="{{ route('infraestructura.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row">
            <!-- Columna Izquierda -->
            <div class="col-md-6">
                <h5>Información General</h5>

                <div class="form-group mb-3">
                    <label>Dependencia Responsable <span class="text-danger">*</span></label>
                    <select name="dependencia_responsable" class="form-control" required>
                        <option value="">Seleccione una dependencia</option>
                        <option value="Infraestructura">Infraestructura</option>
                        <option value="Mantenimiento">Mantenimiento</option>
                        <option value="Planeación">Planeación</option>
                        <option value="Servicios Generales">Servicios Generales</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label>Funcionario Responsable <span class="text-danger">*</span></label>
                    <select name="staff_name" class="form-control" required>
                        <option value="">Seleccione un funcionario</option>
                        @foreach($users ?? [] as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label>Centro de Formación <span class="text-danger">*</span></label>
                    <input type="hidden" name="centro_id" id="centro_id">
                    <div class="search-box">
                        <input type="text" id="centroSeleccionado" placeholder="Seleccione un centro..." readonly
                            onclick="openModal('centroModal')" required />
                        <span class="search-icon" onclick="openModal('centroModal')">🔍</span>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label>Sede de Formación <span class="text-danger">*</span></label>
                    <input type="hidden" name="sede_id" id="sede_id">
                    <div class="search-box">
                        <input type="text" id="sedeSeleccionada" placeholder="Primero seleccione un centro..." readonly
                            onclick="openModalSede()" disabled />
                        <span class="search-icon" onclick="openModalSede()">🔍</span>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label>Área de la Necesidad <span class="text-danger">*</span></label>
                    <select name="area_necesidad" class="form-control" required>
                        <option value="">Seleccione un área</option>
                        <option value="Talleres">Talleres</option>
                        <option value="Laboratorios">Laboratorios</option>
                        <option value="Aulas">Aulas</option>
                        <option value="Áreas Comunes">Áreas Comunes</option>
                        <option value="Oficinas Administrativas">Oficinas Administrativas</option>
                        <option value="Exteriores">Exteriores</option>
                    </select>
                </div>
            </div>

            <!-- Columna Derecha -->
            <div class="col-md-6">
                <h5>Características de la Necesidad</h5>

                <div class="form-group mb-3">
                    <label>Tipo de Necesidad <span class="text-danger">*</span></label>
                    <select name="tipo_necesidad" class="form-control" required>
                        <option value="">Seleccione el tipo</option>
                        <option value="Eléctrica">Eléctrica</option>
                        <option value="Hidráulica">Hidráulica</option>
                        <option value="Refrigeración">Refrigeración</option>
                        <option value="Infraestructura Civil">Infraestructura Civil</option>
                        <option value="Mobiliario">Mobiliario</option>
                        <option value="Equipos">Equipos</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label>Motivo de la Necesidad <span class="text-danger">*</span></label>
                    <select name="motivo_necesidad" class="form-control" required>
                        <option value="">Seleccione el motivo</option>
                        <option value="Mantenimiento Preventivo">Mantenimiento Preventivo</option>
                        <option value="Mantenimiento Correctivo">Mantenimiento Correctivo</option>
                        <option value="Instalación Nueva">Instalación Nueva</option>
                        <option value="Reparación">Reparación</option>
                        <option value="Reemplazo">Reemplazo</option>
                        <option value="Mejora">Mejora</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label>Nivel de Riesgo <span class="text-danger">*</span></label>
                        <select name="nivel_riesgo" class="form-control" required>
                            <option value="">Seleccione</option>
                            <option value="Bajo">🟢 Bajo</option>
                            <option value="Medio">🟡 Medio</option>
                            <option value="Alto">🔴 Alto</option>
                        </select>
                        <small class="text-muted">Impacto en seguridad</small>
                    </div>

                    <div class="col-md-6 form-group mb-3">
                        <label>Nivel de Complejidad <span class="text-danger">*</span></label>
                        <select name="nivel_complejidad" class="form-control" required>
                            <option value="">Seleccione</option>
                            <option value="Bajo">Bajo</option>
                            <option value="Medio">Medio</option>
                            <option value="Alto">Alto</option>
                        </select>
                        <small class="text-muted">Recursos requeridos</small>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="requiere_traslado" id="requiereTraslado">
                        <label class="form-check-label" for="requiereTraslado">
                            ¿Requiere traslado de equipos o personal?
                        </label>
                    </div>
                </div>

                <div class="form-group mb-3" id="sedeSecundariaDiv" style="display: none;">
                    <label>Sede Destino del Traslado</label>
                    <select name="sede_formacion_secundaria" class="form-control">
                        <option value="">Seleccione la sede destino</option>
                    </select>
                </div>
            </div>
        </div>

        <hr class="my-4">

        <!-- Detalles -->
        <div class="row">
            <div class="col-12">
                <h5>Detalles de la Necesidad</h5>
            </div>

            <div class="col-md-6 form-group mb-3">
                <label>Evidencia Fotográfica</label>
                <input type="file" name="imagen" class="form-control" id="imagenInput" accept="image/*">
                <small class="text-muted">Adjunte una imagen que evidencie la necesidad</small>
                <div id="imagenPreview" class="mt-3"></div>
            </div>

            <div class="col-md-6 form-group mb-3">
                <label>Descripción Detallada <span class="text-danger">*</span></label>
                <textarea name="descripcion" rows="6" class="form-control" required 
                          placeholder="Describa con detalle la necesidad..."></textarea>
            </div>
        </div>

        <!-- Botones -->
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('infraestructura.index') }}" class="btn-modern btn-cancel">Cancelar</a>
            <button type="submit" class="btn-modern btn-save">Guardar Necesidad</button>
        </div>
    </form>
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
                    @foreach($centros ?? [] as $centro)
                    <tr>
                        <td>{{ $centro->nom_centro }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-success seleccionar-centro"
                                data-id="{{ $centro->id }}" data-nombre="{{ $centro->nom_centro }}">
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
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.content-card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

h5 {
    font-weight: bold;
    color: #333;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 8px;
    margin-bottom: 20px;
}

.form-control, .form-select {
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    padding: 10px 12px;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #43a047;
    box-shadow: 0 0 0 4px rgba(67,160,71,0.1);
    outline: none;
}

.btn-modern {
    min-width: 140px;
    padding: 12px 28px;
    font-size: 15px;
    font-weight: 600;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    text-align: center;
    display: inline-block;
}

.btn-save {
    background: #4cd137;
    color: #fff;
}

.btn-save:hover {
    background: #43a047;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(76,209,55,0.4);
}

.btn-cancel {
    background-color: #fff;
    border: 1px solid #43a047;
    color: #43a047;
}

.btn-cancel:hover {
    background: #43a047;
    color: #fff;
    transform: translateY(-2px);
}

.custom-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    backdrop-filter: blur(6px);
    background-color: rgba(0,0,0,0.45);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}

.custom-modal-content {
    background: #fff;
    border-radius: 12px;
    width: 95%;
    max-width: 900px;
    box-shadow: 0 5px 30px rgba(0,0,0,0.2);
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
    box-shadow: 0 0 0 4px rgba(67,160,71,0.1);
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

.table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.95rem;
}

.table thead {
    background: #f1f3f5;
    font-weight: bold;
}

.table th, .table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.table tbody tr:hover {
    background: #f9fafb;
    transition: background 0.2s ease;
}

.btn {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
}

.btn-success {
    background: #4caf50;
    color: white;
}

.btn-success:hover {
    background: #43a047;
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

#imagenPreview img {
    max-width: 100%;
    max-height: 250px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    object-fit: cover;
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
let currentPageCentros = 1;
let currentPageSedes = 1;
const rowsPerPage = 5;

function renderTableCentros() {
    const rows = document.querySelectorAll('#listaCentros tr');
    const totalPages = Math.ceil(rows.length / rowsPerPage);

    rows.forEach((row, index) => {
        row.style.display = 
            index >= (currentPageCentros - 1) * rowsPerPage && index < currentPageCentros * rowsPerPage ? '' : 'none';
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
        row.style.display = 
            index >= (currentPageSedes - 1) * rowsPerPage && index < currentPageSedes * rowsPerPage ? '' : 'none';
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

function openModal(id) {
    document.getElementById(id).style.display = 'flex';
    if(id === 'centroModal') renderTableCentros();
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

function openModalSede() {
    const centroId = document.getElementById('centro_id').value;
    if (!centroId) {
        alert('Primero debe seleccionar un centro');
        return;
    }
    document.getElementById('sedeModal').style.display = 'flex';
    renderTableSedes();
}

document.getElementById('filtroCentro').addEventListener('keyup', function() {
    let filtro = this.value.toLowerCase();
    document.querySelectorAll('#listaCentros tr').forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(filtro) ? '' : 'none';
    });
});

document.getElementById('filtroSede').addEventListener('keyup', function() {
    let filtro = this.value.toLowerCase();
    document.querySelectorAll('#listaSedes tr').forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(filtro) ? '' : 'none';
    });
});

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

document.getElementById('requiereTraslado').addEventListener('change', function() {
    document.getElementById('sedeSecundariaDiv').style.display = this.checked ? 'block' : 'none';
});

document.getElementById('imagenInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagenPreview');
    
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Vista previa">`;
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
});
</script>
@endpush