@extends('layouts.dashboard')

@section('page-title', 'Usuarios')

@section('dashboard-content')

<div class="sg-page-header">
    <div>
        <h2>Gestión de Usuarios</h2>
        <p>Administra los usuarios del sistema y sus roles</p>
    </div>
    <button onclick="openDrawer('userDrawer')" class="sg-btn sg-btn-primary" type="button">
        <i class="fas fa-plus"></i> Nuevo Usuario
    </button>
</div>

{{-- ══ DRAWER EDITAR USUARIO ══ --}}
<div class="sg-drawer-overlay" id="userEditDrawerOverlay"></div>
<div class="sg-drawer" id="userEditDrawer">
    <div class="sg-drawer-header">
        <h5><i class="fas fa-user-edit drawer-icon"></i> Editar Usuario</h5>
        <button type="button" class="sg-drawer-close" onclick="closeDrawer('userEditDrawer')">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="sg-drawer-body">
        <form id="userEditForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" id="editUserId">
            <div class="mb-3">
                <label>Nombre <span class="text-danger">*</span></label>
                <input type="text" name="name" id="editUserName" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Correo electrónico <span class="text-danger">*</span></label>
                <input type="email" name="email" id="editUserEmail" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Rol</label>
                <select name="role" id="editUserRole" class="form-control">
                    <option value="">Sin cambiar rol</option>
                    @foreach($roles as $rol)
                    <option value="{{ $rol->name }}">{{ $rol->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Nueva Contraseña <small class="text-muted">(dejar vacío para no cambiar)</small></label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="mb-3">
                <label>Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
            <div class="mb-3">
                <label>Dirección</label>
                <input type="text" name="address" id="editUserAddress" class="form-control">
            </div>
            <div class="mb-3">
                <label>Teléfono</label>
                <input type="text" name="phone" id="editUserPhone" class="form-control">
            </div>
            <div class="mb-3">
                <label>Centro</label>
                <select id="editCentroSelect" class="form-control">
                    <option value="">Seleccione un centro</option>
                    @foreach($centros as $c)
                    <option value="{{ $c->id }}">{{ $c->nom_centro }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Sede <span class="text-danger">*</span></label>
                <select name="sede_id" id="editSedeSelect" class="form-control" required disabled>
                    <option value="">Seleccione una sede</option>
                    @foreach($sedes as $s)
                    <option value="{{ $s->id }}" data-centro="{{ $s->centro_id }}">{{ $s->nom_sede }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
    <div class="sg-drawer-footer">
        <button type="button" class="sg-btn sg-btn-secondary" onclick="closeDrawer('userEditDrawer')">
            <i class="fas fa-times"></i> Cancelar
        </button>
        <button type="submit" form="userEditForm" class="sg-btn sg-btn-primary">
            <i class="fas fa-save"></i> Actualizar Usuario
        </button>
    </div>
</div>

{{-- ══ DRAWER CREAR USUARIO ══ --}}
<div class="sg-drawer-overlay" id="userDrawerOverlay"></div>
<div class="sg-drawer" id="userDrawer">
    <div class="sg-drawer-header">
        <h5><i class="fas fa-user-plus drawer-icon"></i> Nuevo Usuario</h5>
        <button type="button" class="sg-drawer-close" onclick="closeDrawer('userDrawer')">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="sg-drawer-body">
        <form id="userCreateForm" action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Nombre <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
            </div>
            <div class="mb-3">
                <label>Correo electrónico <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
            </div>
            <div class="mb-3">
                <label>Rol <span class="text-danger">*</span></label>
                <select name="role" class="form-control" required>
                    <option value="">Seleccione un rol</option>
                    @foreach($roles as $rol)
                    <option value="{{ $rol->name }}">{{ $rol->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Contraseña <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Dirección</label>
                <input type="text" name="address" class="form-control" value="{{ old('address') }}">
            </div>
            <div class="mb-3">
                <label>Teléfono</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
            </div>
            {{-- Centro y Sede con el componente unificado --}}
            <x-centros-sedes-selector :centros="$centros" prefix="usr" />
            {{-- Adaptar el nombre del campo sede para el controlador --}}
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.body.addEventListener('click', function(e) {
                    if (e.target.classList.contains('seleccionar-sede') && e.target.dataset.prefix === 'usr') {
                        setTimeout(function() {
                            const sedeId = document.getElementById('usr_sede_id').value;
                            const hiddenSede = document.getElementById('user_sede_id_real');
                            if (hiddenSede) hiddenSede.value = sedeId;
                        }, 80);
                    }
                });
            });
            </script>
            <input type="hidden" name="sede_id" id="user_sede_id_real">
        </form>
    </div>
    <div class="sg-drawer-footer">
        <button type="button" class="sg-btn sg-btn-secondary" onclick="closeDrawer('userDrawer')">
            <i class="fas fa-times"></i> Cancelar
        </button>
        <button type="submit" form="userCreateForm" class="sg-btn sg-btn-primary">
            <i class="fas fa-save"></i> Guardar Usuario
        </button>
    </div>
</div>

<div class="sg-card">
    {{-- Skeleton de carga --}}
    <div id="usersSkeleton">
        <div class="sg-skeleton sg-skeleton-header"></div>
        @for($i = 0; $i < 6; $i++)
        <div class="sg-skeleton-row">
            <div class="sg-skeleton sg-skeleton-cell" style="width:40px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:160px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:200px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:120px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:130px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:130px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:70px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:80px"></div>
        </div>
        @endfor
    </div>

    <div class="sg-table-wrapper" id="usersTableWrapper" style="display:none">
        <table id="usersTable" class="sg-table">
            <thead>
                <tr>
                    <th><i class="fas fa-hashtag"></i> ID</th>
                    <th><i class="fas fa-user"></i> Nombre</th>
                    <th><i class="fas fa-envelope"></i> Email</th>
                    <th><i class="fas fa-phone"></i> Teléfono</th>
                    <th><i class="fas fa-map-marker-alt"></i> Sede</th>
                    <th><i class="fas fa-university"></i> Centro</th>
                    <th><i class="fas fa-circle"></i> Estado</th>
                    <th><i class="fas fa-cogs"></i> Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td><span class="sg-badge sg-badge-gray">#{{ $user->id }}</span></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:9px;">
                            <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#16a34a,#22c55e);
                                        color:white;display:flex;align-items:center;justify-content:center;
                                        font-weight:700;font-size:13px;flex-shrink:0;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span style="font-weight:600;color:#111827;">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td style="color:#6b7280;">{{ $user->email }}</td>
                    <td>{{ $user->phone ?? '—' }}</td>
                    <td>
                        @if($user->sedes->isNotEmpty())
                            {{ $user->sedes->pluck('nom_sede')->join(', ') }}
                        @else
                            <span style="color:#9ca3af;font-size:12px;">Sin sede</span>
                        @endif
                    </td>
                    <td>
                        @if($user->sedes->isNotEmpty())
                            {{ $user->sedes->pluck('centro.nom_centro')->unique()->join(', ') }}
                        @else
                            <span style="color:#9ca3af;font-size:12px;">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="sg-badge {{ $user->state ? 'sg-badge-green' : 'sg-badge-red' }}">
                            {{ $user->state ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <button type="button" class="sg-btn sg-btn-warning" title="Editar"
                                onclick="openUserEditDrawer({{ $user->id }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="sg-btn sg-btn-danger" title="Desactivar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('styles')
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <style>
        #DataTables_Table_0_wrapper .dataTables_filter,
        #usersTable_filter { margin-bottom: 0; }
        .dataTables_wrapper { padding: 16px; }
        .dataTables_wrapper .row:first-child { padding-bottom: 12px; }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            setTimeout(function () {
                $('#usersSkeleton').hide();
                $('#usersTableWrapper').show();
                $('#usersTable').DataTable({
                    language: { url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json' },
                    order: [[0, 'desc']],
                    pageLength: 25,
                    dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>rt<"row"<"col-sm-6"i><"col-sm-6"p>>',
                });
            }, 400);
        });

        function openUserEditDrawer(userId) {
            fetch('/users/' + userId, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.json())
                .then(u => {
                    document.getElementById('editUserId').value = u.id;
                    document.getElementById('editUserName').value    = u.name    ?? '';
                    document.getElementById('editUserEmail').value   = u.email   ?? '';
                    document.getElementById('editUserAddress').value = u.address ?? '';
                    document.getElementById('editUserPhone').value   = u.phone   ?? '';

                    // Set role (from roles relation or roles array)
                    const roleName = (u.roles && u.roles.length > 0) ? u.roles[0].name : '';
                    const roleSelect = document.getElementById('editUserRole');
                    for (let opt of roleSelect.options) { opt.selected = (opt.value === roleName); }

                    // Set form action
                    document.getElementById('userEditForm').action = '/users/' + u.id;

                    // Extract sede/centro from sedes relation
                    const firstSede   = (u.sedes && u.sedes.length > 0) ? u.sedes[0] : null;
                    const centroId    = firstSede?.centro_id ?? firstSede?.centro?.id ?? null;
                    const sedeId      = firstSede?.id ?? null;

                    const centroSel = document.getElementById('editCentroSelect');
                    for (let opt of centroSel.options) { opt.selected = (String(opt.value) === String(centroId)); }

                    filterEditSedes(centroId, sedeId);

                    openDrawer('userEditDrawer');
                })
                .catch(() => alert('Error al cargar los datos del usuario'));
        }

        function filterEditSedes(centroId, preselect) {
            const sedeSel = document.getElementById('editSedeSelect');
            const allOpts = Array.from(sedeSel.querySelectorAll('option'));
            allOpts.forEach(o => {
                if (!o.value) return;
                o.style.display = (!centroId || String(o.dataset.centro) === String(centroId)) ? '' : 'none';
                o.selected = preselect && String(o.value) === String(preselect);
            });
            sedeSel.disabled = false;
        }

        document.getElementById('editCentroSelect').addEventListener('change', function () {
            filterEditSedes(this.value, null);
        });
    </script>
@endpush
