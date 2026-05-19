{{-- resources/views/dashboard/admin/usuarios.blade.php --}}
@extends('layouts.dashboard')

@section('titulo_pagina', 'Gestión de Usuarios — Tools365')
@section('topbar_title', 'Usuarios')
@section('topbar_breadcrumb', 'Administración')

@push('css')
<style>
/* ── PAGE HEADER ── */
.dash-page-header {
    display: flex; align-items: flex-start; justify-content: space-between;
    flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;
}
.dash-page-header h1 {
    font-size: 1.4rem; font-weight: 800; margin: 0 0 .2rem;
    color: var(--bs-body-color);
}
.dash-page-header p { margin: 0; color: var(--bs-secondary-color); font-size: .88rem; }

/* ── FILTROS ── */
.filter-bar {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: .75rem;
    padding: 1rem 1.25rem;
    display: flex; flex-wrap: wrap; gap: .75rem; align-items: flex-end;
    margin-bottom: 1.25rem;
}
.filter-group { display: flex; flex-direction: column; gap: .3rem; min-width: 160px; flex: 1; }
.filter-group label { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--bs-secondary-color); }
.filter-group .form-control,
.filter-group .form-select {
    font-size: .85rem;
    border-radius: .5rem;
    border-color: var(--bs-border-color);
    background: var(--bs-body-bg);
    color: var(--bs-body-color);
    padding: .4rem .75rem;
}
.filter-group .form-control:focus,
.filter-group .form-select:focus {
    border-color: #534AB7;
    box-shadow: 0 0 0 .2rem rgba(83,74,183,.15);
}
.btn-filter {
    padding: .42rem 1rem; border-radius: .5rem; font-size: .85rem;
    font-weight: 600; cursor: pointer; transition: all .15s; white-space: nowrap;
}
.btn-filter-apply  { background: #534AB7; color: #fff; border: none; }
.btn-filter-apply:hover { background: #453da0; }
.btn-filter-clear  { background: var(--bs-tertiary-bg); color: var(--bs-body-color); border: 1px solid var(--bs-border-color); }
.btn-filter-clear:hover { background: var(--bs-secondary-bg); }

/* ── STATS ROW ── */
.mini-stats { display: flex; gap: .75rem; flex-wrap: wrap; margin-bottom: 1.25rem; }
.mini-stat {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: .625rem;
    padding: .7rem 1.1rem;
    display: flex; align-items: center; gap: .6rem;
    flex: 1; min-width: 140px;
}
.mini-stat-icon {
    width: 36px; height: 36px; border-radius: .5rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.mini-stat-num  { font-size: 1.2rem; font-weight: 800; line-height: 1; }
.mini-stat-lbl  { font-size: .75rem; color: var(--bs-secondary-color); }

/* ── TABLE CARD ── */
.dash-card {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: .75rem;
    overflow: hidden;
}
.card-header-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--bs-border-color);
}
.card-header-row h3 { font-size: .95rem; font-weight: 700; margin: 0; display: flex; align-items: center; }
.results-count { font-size: .8rem; color: var(--bs-secondary-color); }

/* ── ADMIN TABLE ── */
.admin-table { width: 100%; border-collapse: collapse; font-size: .85rem; }
.admin-table thead th {
    padding: .6rem 1rem;
    font-size: .72rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .06em; color: var(--bs-secondary-color);
    border-bottom: 1px solid var(--bs-border-color); white-space: nowrap;
    background: var(--bs-body-bg);
}
.admin-table tbody td {
    padding: .8rem 1rem;
    border-bottom: 1px solid var(--bs-border-color);
    vertical-align: middle;
}
.admin-table tbody tr:last-child td { border-bottom: none; }
.admin-table tbody tr:hover { background: var(--bs-tertiary-bg); }

.user-row { display: flex; align-items: center; gap: .65rem; }
.user-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: #ede9ff; color: #534AB7;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .8rem; flex-shrink: 0;
}
.user-avatar.bloqueado { background: #fdecea; color: #c0392b; }
.user-name  { font-weight: 600; font-size: .85rem; }
.user-email { font-size: .75rem; color: var(--bs-secondary-color); }
.user-since { font-size: .75rem; color: var(--bs-secondary-color); }

/* Badges de rol */
.badge-rol {
    display: inline-flex; align-items: center; gap: .25rem;
    font-size: .72rem; font-weight: 700; padding: .25em .6em;
    border-radius: 999px; white-space: nowrap;
}
.rol-admin    { background: #ede9ff; color: #534AB7; }
.rol-gerente  { background: #fff3e0; color: #e65c00; }
.rol-invitado { background: var(--bs-secondary-bg); color: var(--bs-secondary-color); }

/* Badges de plan */
.badge-plan {
    display: inline-flex; align-items: center;
    font-size: .72rem; font-weight: 700; padding: .25em .6em;
    border-radius: 999px; white-space: nowrap;
}
.plan-basico     { background: var(--bs-secondary-bg); color: var(--bs-secondary-color); }
.plan-pro        { background: #e8f4fd; color: #1a6fa8; }
.plan-enterprise { background: #e6f9f0; color: #1a7f4b; }

/* Badges de estado */
.badge-estado {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .72rem; font-weight: 600; padding: .25em .6em;
    border-radius: 999px; white-space: nowrap;
}
.estado-activo    { background: #e6f9f0; color: #1a7f4b; }
.estado-bloqueado { background: #fdecea; color: #c0392b; }
.estado-pendiente { background: #fff3e0; color: #e65c00; }
.dot { font-size: .45rem; }

/* Botones de tabla */
.tbl-actions { display: flex; gap: .3rem; }
.btn-tbl {
    width: 30px; height: 30px; border: none; border-radius: .375rem;
    display: flex; align-items: center; justify-content: center;
    font-size: .8rem; cursor: pointer; transition: background .15s;
}
.btn-tbl-edit    { background: #e8f4fd; color: #1a6fa8; }
.btn-tbl-edit:hover { background: #bee3f8; }
.btn-tbl-block   { background: #fdecea; color: #c0392b; }
.btn-tbl-block:hover { background: #fbc4c0; }
.btn-tbl-restore { background: #e6f9f0; color: #1a7f4b; }
.btn-tbl-restore:hover { background: #c3f1d9; }

/* ── MODAL ── */
.modal-content {
    border-radius: .875rem;
    border: 1px solid var(--bs-border-color);
    box-shadow: 0 20px 60px rgba(0,0,0,.12);
}
.modal-header {
    border-bottom: 1px solid var(--bs-border-color);
    padding: 1rem 1.25rem;
}
.modal-title { font-size: 1rem; font-weight: 700; }
.modal-body  { padding: 1.25rem; }
.modal-footer {
    border-top: 1px solid var(--bs-border-color);
    padding: .875rem 1.25rem;
}

.form-label-sm { font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: var(--bs-secondary-color); margin-bottom: .35rem; }
.form-select-sm-custom {
    font-size: .875rem;
    border-radius: .5rem;
    border: 1px solid var(--bs-border-color);
    background: var(--bs-body-bg);
    color: var(--bs-body-color);
    padding: .45rem .75rem;
    width: 100%;
}
.form-select-sm-custom:focus {
    outline: none;
    border-color: #534AB7;
    box-shadow: 0 0 0 .2rem rgba(83,74,183,.15);
}

.user-modal-card {
    display: flex; align-items: center; gap: .85rem;
    background: var(--bs-tertiary-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: .625rem;
    padding: .875rem 1rem;
    margin-bottom: 1.25rem;
}
.modal-user-avatar {
    width: 44px; height: 44px; border-radius: 50%;
    background: #ede9ff; color: #534AB7;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 1rem; flex-shrink: 0;
}

.btn-save {
    background: #534AB7; color: #fff; border: none;
    padding: .5rem 1.25rem; border-radius: .5rem;
    font-size: .875rem; font-weight: 600; cursor: pointer;
    transition: background .15s;
}
.btn-save:hover { background: #453da0; }
.btn-cancel {
    background: var(--bs-tertiary-bg); color: var(--bs-body-color);
    border: 1px solid var(--bs-border-color);
    padding: .5rem 1rem; border-radius: .5rem;
    font-size: .875rem; cursor: pointer; transition: background .15s;
}
.btn-cancel:hover { background: var(--bs-secondary-bg); }

/* ── PAGINATION ── */
.pagination-wrap {
    padding: .875rem 1.25rem;
    border-top: 1px solid var(--bs-border-color);
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: .5rem;
}
.pagination-info { font-size: .8rem; color: var(--bs-secondary-color); }

/* ── EMPTY STATE ── */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: var(--bs-secondary-color);
}
.empty-state i { font-size: 2.5rem; margin-bottom: .75rem; display: block; }
.empty-state p { font-size: .9rem; margin: 0; }
</style>
@endpush

@section('contenido')

{{-- ── PAGE HEADER ── --}}
<div class="dash-page-header">
    <div>
        <h1><i class="bi bi-people-fill me-2" style="color:#534AB7;"></i>Gestión de Usuarios</h1>
        <p>Administra roles, planes y estados de los usuarios de la plataforma.</p>
    </div>
    <span class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill"
          style="background:#ede9ff;color:#534AB7;font-size:.78rem;font-weight:700;">
        <i class="bi bi-shield-fill"></i> Administrador
    </span>
</div>

{{-- ── MINI STATS ── --}}
<div class="mini-stats">
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#e8f4fd;color:#1a6fa8;"><i class="bi bi-people-fill"></i></div>
        <div>
            <div class="mini-stat-num">{{ $usuarios->total() }}</div>
            <div class="mini-stat-lbl">Total usuarios</div>
        </div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#ede9ff;color:#534AB7;"><i class="bi bi-shield-fill"></i></div>
        <div>
            <div class="mini-stat-num">{{ \App\Models\User::where('rol','admin')->count() }}</div>
            <div class="mini-stat-lbl">Administradores</div>
        </div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#e6f9f0;color:#1a7f4b;"><i class="bi bi-check-circle-fill"></i></div>
        <div>
            <div class="mini-stat-num">{{ \App\Models\User::where('status', 'activo')->count() }}</div>
            <div class="mini-stat-lbl">Activos</div>
        </div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#fdecea;color:#c0392b;"><i class="bi bi-slash-circle-fill"></i></div>
        <div>
            <div class="mini-stat-num">{{ \App\Models\User::where('status', 'bloqueado')->count() }}</div>
            <div class="mini-stat-lbl">Bloqueados</div>
        </div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#fff3e0;color:#e65c00;"><i class="bi bi-envelope-exclamation-fill"></i></div>
        <div>
            <div class="mini-stat-num">{{ \App\Models\User::whereNull('email_verified_at')->count() }}</div>
            <div class="mini-stat-lbl">Sin verificar</div>
        </div>
    </div>
</div>

{{-- ── FILTROS ── --}}
<form method="GET" action="{{ route('admin.usuarios') }}" class="filter-bar">
    <div class="filter-group" style="max-width:260px;">
        <label>Buscar</label>
        <input type="text" name="buscar" class="form-control"
               placeholder="Nombre o email..."
               value="{{ request('buscar') }}">
    </div>
    <div class="filter-group" style="max-width:150px;">
        <label>Rol</label>
        <select name="rol" class="form-select">
            <option value="">Todos</option>
            <option value="admin"    {{ request('rol') === 'admin'    ? 'selected' : '' }}>Admin</option>
            <option value="gerente"  {{ request('rol') === 'gerente'  ? 'selected' : '' }}>Gerente</option>
            <option value="invitado" {{ request('rol') === 'invitado' ? 'selected' : '' }}>Invitado</option>
        </select>
    </div>
    <div class="filter-group" style="max-width:150px;">
        <label>Plan</label>
        <select name="plan" class="form-select">
            <option value="">Todos</option>
            <option value="basico"     {{ request('plan') === 'basico'     ? 'selected' : '' }}>Básico</option>
            <option value="pro"        {{ request('plan') === 'pro'        ? 'selected' : '' }}>Pro</option>
            <option value="enterprise" {{ request('plan') === 'enterprise' ? 'selected' : '' }}>Enterprise</option>
        </select>
    </div>
    <div class="filter-group" style="max-width:150px;">
        <label>Estado</label>
        <select name="estado" class="form-select">
            <option value="">Todos</option>
            <option value="activo"    {{ request('estado') === 'activo'    ? 'selected' : '' }}>Activo</option>
            <option value="bloqueado" {{ request('estado') === 'bloqueado' ? 'selected' : '' }}>Bloqueado</option>
        </select>
    </div>
    <div class="d-flex gap-2 align-items-end">
        <button type="submit" class="btn-filter btn-filter-apply">
            <i class="bi bi-search me-1"></i>Filtrar
        </button>
        @if(request()->hasAny(['buscar','rol','plan','estado']))
            <a href="{{ route('admin.usuarios') }}" class="btn-filter btn-filter-clear">
                Limpiar
            </a>
        @endif
    </div>
</form>

{{-- ── TABLA ── --}}
<div class="dash-card">
    <div class="card-header-row">
        <h3><i class="bi bi-people-fill me-2" style="color:#534AB7;"></i>Usuarios</h3>
        <span class="results-count">
            {{ $usuarios->firstItem() }}–{{ $usuarios->lastItem() }} de {{ $usuarios->total() }} usuarios
        </span>
    </div>

    <div class="table-responsive">
        @if($usuarios->isEmpty())
            <div class="empty-state">
                <i class="bi bi-person-slash"></i>
                <p>No se encontraron usuarios con los filtros aplicados.</p>
            </div>
        @else
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Usuario</th>
                    <th>Verificado</th>
                    <th>Rol</th>
                    <th>Plan</th>
                    <th>Estado</th>
                    <th>Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuarios as $user)
                <tr>
                    {{-- ID --}}
                    <td class="text-muted" style="font-size:.78rem;">
    {{ $loop->iteration }}
</td>

                    {{-- Usuario --}}
                    <td>
                        <div class="user-row">
                            <div class="user-avatar {{ $user->status === 'bloqueado' ? 'bloqueado' : '' }}">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="user-name">
                                    {{ $user->name }}
                                    @if($user->id === auth()->id())
                                        <span style="font-size:.68rem;color:#534AB7;font-weight:700;">(tú)</span>
                                    @endif
                                </div>
                                <div class="user-email">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>

                    {{-- Verificado --}}
                    <td>
                        @if($user->email_verified_at)
                            <span class="badge-estado estado-activo">
                                <i class="bi bi-check-circle-fill dot" style="font-size:.7rem;"></i> Sí
                            </span>
                        @else
                            <span class="badge-estado estado-pendiente">
                                <i class="bi bi-clock dot" style="font-size:.7rem;"></i> Pendiente
                            </span>
                        @endif
                    </td>

                    {{-- Rol --}}
                    <td>
                        <span class="badge-rol rol-{{ $user->rol }}">
                            @if($user->rol === 'admin')    <i class="bi bi-shield-fill"></i>
                            @elseif($user->rol === 'gerente') <i class="bi bi-briefcase-fill"></i>
                            @else <i class="bi bi-person-fill"></i>
                            @endif
                            {{ ucfirst($user->rol) }}
                        </span>
                    </td>

                    {{-- Plan --}}
                    <td>
                        <span class="badge-plan plan-{{ $user->plan ?? 'basico' }}">
                            {{ ucfirst($user->plan ?? 'Básico') }}
                        </span>
                    </td>

                    {{-- Estado --}}
                    <td>
                        @if($user->status === 'bloqueado')
                            <span class="badge-estado estado-bloqueado">
                                <i class="bi bi-slash-circle-fill dot" style="font-size:.6rem;"></i> Bloqueado
                            </span>
                        @else
                            <span class="badge-estado estado-activo">
                                <i class="bi bi-circle-fill dot" style="font-size:.6rem;"></i> Activo
                            </span>
                        @endif
                    </td>

                    {{-- Fecha de registro --}}
                    <td class="user-since">{{ $user->created_at->format('d/m/Y') }}</td>

                    {{-- Acciones --}}
                    <td>
                        <div class="tbl-actions">
                            {{-- Botón editar → abre modal --}}
                            <button type="button"
                                    class="btn-tbl btn-tbl-edit"
                                    title="Editar usuario"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditarUsuario"
                                    data-id="{{ $user->id }}"
                                    data-name="{{ $user->name }}"
                                    data-email="{{ $user->email }}"
                                    data-rol="{{ $user->rol }}"
                                    data-plan="{{ $user->plan ?? 'basico' }}"
                                    data-status="{{ $user->status }}">
                                <i class="bi bi-pencil-fill"></i>
                            </button>

{{-- Botón toggle bloqueo rápido --}}
@if($user->id !== auth()->id())
    <form method="POST"
          action="{{ route('admin.usuarios.toggle', $user) }}"
          style="display:inline;"
          onsubmit="return confirm('{{ $user->status === 'bloqueado' ? '¿Desbloquear' : '¿Bloquear' }} a {{ addslashes($user->name) }}?')">
        @csrf
        @method('PATCH')
        <button type="submit"
                class="btn-tbl {{ $user->status === 'bloqueado' ? 'btn-tbl-restore' : 'btn-tbl-block' }}"
                title="{{ $user->status === 'bloqueado' ? 'Desbloquear' : 'Bloquear' }}">
            <i class="bi bi-{{ $user->status === 'bloqueado' ? 'arrow-counterclockwise' : 'slash-circle-fill' }}"></i>
        </button>
    </form>
@endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    {{-- Paginación --}}
    @if($usuarios->hasPages())
    <div class="pagination-wrap">
        <span class="pagination-info">
            Página {{ $usuarios->currentPage() }} de {{ $usuarios->lastPage() }}
        </span>
        {{ $usuarios->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

{{-- ══════════════════════════════════════════════════════════
     MODAL — Editar usuario
═══════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarUsuarioLabel">
                    <i class="bi bi-pencil-fill me-2" style="color:#534AB7;"></i>Editar usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formEditarUsuario" method="POST" action="">
                @csrf
                @method('PATCH')

                <div class="modal-body">
                    {{-- Tarjeta del usuario --}}
                    <div class="user-modal-card">
                        <div class="modal-user-avatar" id="modalAvatar"></div>
                        <div>
                            <div style="font-weight:700;font-size:.95rem;" id="modalNombre"></div>
                            <div style="font-size:.8rem;color:var(--bs-secondary-color);" id="modalEmail"></div>
                        </div>
                    </div>

                    {{-- Rol --}}
                    <div class="mb-3">
                        <label class="form-label-sm">Rol</label>
                        <select name="rol" id="modalRol" class="form-select-sm-custom">
                            <option value="invitado">Invitado</option>
                            <option value="gerente">Gerente</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>

                    {{-- Plan --}}
                    <div class="mb-3">
                        <label class="form-label-sm">Plan</label>
                        <select name="plan" id="modalPlan" class="form-select-sm-custom">
                            <option value="basico">Básico</option>
                            <option value="pro">Pro</option>
                            <option value="enterprise">Enterprise</option>
                        </select>
                    </div>

                    {{-- Estado --}}
                    <div class="mb-0">
                        <label class="form-label-sm">Estado</label>
                        <select name="status" id="modalBloqueado" class="form-select-sm-custom">
                            <option value="activo">Activo</option>
                            <option value="bloqueado">Bloqueado</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-save">
                        <i class="bi bi-check-lg me-1"></i>Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Inyectar datos del usuario al abrir el modal
    document.getElementById('modalEditarUsuario').addEventListener('show.bs.modal', function (e) {
        const btn = e.relatedTarget;

        const id       = btn.dataset.id;
        const name     = btn.dataset.name;
        const email    = btn.dataset.email;
        const rol      = btn.dataset.rol;
        const plan     = btn.dataset.plan;
        const status = btn.dataset.status;

        // Actualizar form action dinámicamente
        document.getElementById('formEditarUsuario').action = `/dashboard/admin/usuarios/${id}`;

        // Tarjeta de usuario
        document.getElementById('modalAvatar').textContent = name.substring(0, 2).toUpperCase();
        document.getElementById('modalNombre').textContent  = name;
        document.getElementById('modalEmail').textContent   = email;

        // Selects
        document.getElementById('modalRol').value      = rol;
        document.getElementById('modalPlan').value     = plan;
        document.getElementById('modalBloqueado').value = status;
    });
</script>
@endpush