@extends('layouts.dashboard')

@section('page-title', 'Editar Usuario')

@section('dashboard-content')
    <div class="section-header">
        <div>
            <h2>Edición de Usuario</h2>
            <p>Modifica los datos del usuario seleccionado</p>
        </div>
    </div>

    <div class="content-card">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Información General -->
                <div class="col-md-6">
                    <h5 class="mb-3">Información General</h5>
                    <div class="form-group mb-3">
                        <label for="name">Nombre *</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="email">Correo electrónico *</label>
                        <input type="email" name="email" id="email" class="form-control"
                            value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="password">Contraseña (dejar vacío para no cambiar)</label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                </div>

                <!-- Información de Contacto -->
                <div class="col-md-6">
                    <h5 class="mb-3">Información de Contacto</h5>
                    <div class="form-group mb-3">
                        <label for="address">Dirección</label>
                        <input type="text" name="address" id="address" class="form-control"
                            value="{{ old('address', $user->address) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="phone">Teléfono</label>
                        <input type="text" name="phone" id="phone" class="form-control"
                            value="{{ old('phone', $user->phone) }}">
                    </div>

                    <!-- Campo Centro de Formación -->
                    <div class="form-group mb-3">
                        <label for="centro_id">Centro de Formación *</label>
                        <input type="hidden" name="centro_id" id="centro_id"
                            value="{{ old('centro_id', $user->centros->first()->id ?? '') }}">
                        <div class="search-box">
                            <input type="text" id="centroSeleccionado"
                                value="{{ old('centro_id', $user->centros->first()->nom_centro ?? '') }}"
                                placeholder="Seleccione un centro..." readonly onclick="openModal('centroModal')" />
                            <span class="search-icon" onclick="openModal('centroModal')">🔍</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('users.index') }}" class="btn-modern btn-cancel">Cancelar</a>
                <button type="submit" class="btn-modern btn-save">Actualizar</button>
            </div>
        </form>
    </div>

    <!-- Modal Selección de Centros -->
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
                        @foreach ($centros as $centro)
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
                <div id="pagination" class="pagination"></div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .content-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h5 {
            font-weight: bold;
            color: #333;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }

        .btn-modern {
            min-width: 140px;
            /* mismo tamaño en ambos */
            padding: 12px 28px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
        }

        .btn-save {
            background: #4cd137;
            /* verde moderno */
            color: #fff;
        }

        .btn-save:hover {
            background: #fff;
            color: #43a047;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(76, 209, 55, 0.4);
        }

        .btn-cancel {
            background-color: #fff;
            border: 1px solid #43a047;
            /* define borde completo */
            color: #43a047;
        }


        .btn-cancel:hover {
            background: #43a047;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(99, 110, 114, 0.3);
        }

        /* Fondo y modal igual que antes */
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
            max-width: 100%;
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
            /* morado elegante */
            background: #fff;
            box-shadow: 0 0 0 4px rgba(76, 209, 55, 0.4);
        }

        .search-box .search-icon {
            position: absolute;
            right: 14px;
            font-size: 16px;
            color: #888;
            pointer-events: none;
            transition: color 0.3s;
        }

        .search-box input:focus+.search-icon {
            color: #43a047;
        }

        /* Tabla moderna */
        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
        }

        .table thead {
            background: #f1f3f5;
            font-weight: bold;
        }

        .table th,
        .table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .table tbody tr:hover {
            background: #f9fafb;
            transition: background 0.2s ease;
        }

        /* Botones */
        .btn {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-success {
            background: #4caf50;
            color: white;
            border: none;
        }

        .btn-success:hover {
            background: #43a047;
        }

        .btn-primary {
            background: #007bff;
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background: #0069d9;
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

        /* Paginación */
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


        /* Animación */
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

        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let currentPage = 1;
        const rowsPerPage = 5;

        function renderTable() {
            const rows = document.querySelectorAll('#listaCentros tr');
            const totalPages = Math.ceil(rows.length / rowsPerPage);

            rows.forEach((row, index) => {
                row.style.display =
                    index >= (currentPage - 1) * rowsPerPage && index < currentPage * rowsPerPage ?
                    '' :
                    'none';
            });

            // Render paginación
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            for (let i = 1; i <= totalPages; i++) {
                let btn = document.createElement('button');
                btn.innerText = i;
                btn.classList.toggle('active', i === currentPage);
                btn.onclick = () => {
                    currentPage = i;
                    renderTable();
                };
                pagination.appendChild(btn);
            }
        }

        // Abrir modal
        function openModal(id) {
            document.getElementById(id).style.display = 'flex';
            renderTable();
        }

        // Cerrar modal
        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        // Filtro
        document.getElementById('filtroCentro').addEventListener('keyup', function() {
            let filtro = this.value.toLowerCase();
            document.querySelectorAll('#listaCentros tr').forEach(row => {
                let texto = row.innerText.toLowerCase();
                row.style.display = texto.includes(filtro) ? '' : 'none';
            });
        });

        // Selección de centro
        document.querySelectorAll('.seleccionar-centro').forEach(boton => {
            boton.addEventListener('click', function() {
                document.getElementById('centroSeleccionado').value = this.dataset.nombre;
                document.getElementById('centro_id').value = this.dataset.id; // ✅ aquí guardamos el ID
                closeModal('centroModal');
            });
        });
    </script>
@endpush
