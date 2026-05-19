{{-- resources/views/dashboard/admin/opiniones.blade.php --}}
@extends('layouts.dashboard')

@section('titulo_pagina', 'Gestión de Opiniones – Tools365')
@section('topbar_title', 'Opiniones')
@section('topbar_breadcrumb', 'Administración')

@push('css')
<style>
.dash-page-header { display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem; }
.dash-page-header h1 { font-size:1.4rem; font-weight:800; margin:0 0 .2rem; color:var(--bs-body-color); }
.dash-page-header p  { margin:0; color:var(--bs-secondary-color); font-size:.88rem; }

.mini-stats { display:flex; gap:.75rem; flex-wrap:wrap; margin-bottom:1.25rem; }
.mini-stat { background:var(--bs-body-bg); border:1px solid var(--bs-border-color); border-radius:.625rem; padding:.7rem 1.1rem; display:flex; align-items:center; gap:.6rem; flex:1; min-width:130px; }
.mini-stat-icon { width:36px; height:36px; border-radius:.5rem; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
.mini-stat-num { font-size:1.2rem; font-weight:800; line-height:1; }
.mini-stat-lbl { font-size:.75rem; color:var(--bs-secondary-color); }

.filter-bar { background:var(--bs-body-bg); border:1px solid var(--bs-border-color); border-radius:.75rem; padding:1rem 1.25rem; display:flex; flex-wrap:wrap; gap:.75rem; align-items:flex-end; margin-bottom:1.25rem; }
.filter-group { display:flex; flex-direction:column; gap:.3rem; min-width:150px; flex:1; }
.filter-group label { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--bs-secondary-color); }
.filter-group .form-control, .filter-group .form-select { font-size:.85rem; border-radius:.5rem; border-color:var(--bs-border-color); background:var(--bs-body-bg); color:var(--bs-body-color); padding:.4rem .75rem; }
.filter-group .form-control:focus, .filter-group .form-select:focus { border-color:#534AB7; box-shadow:0 0 0 .2rem rgba(83,74,183,.15); }
.btn-filter { padding:.42rem 1rem; border-radius:.5rem; font-size:.85rem; font-weight:600; cursor:pointer; transition:all .15s; white-space:nowrap; }
.btn-filter-apply  { background:#534AB7; color:#fff; border:none; }
.btn-filter-apply:hover { background:#453da0; }
.btn-filter-clear  { background:var(--bs-tertiary-bg); color:var(--bs-body-color); border:1px solid var(--bs-border-color); }
.btn-filter-clear:hover { background:var(--bs-secondary-bg); }

.dash-card { background:var(--bs-body-bg); border:1px solid var(--bs-border-color); border-radius:.75rem; overflow:hidden; }
.card-header-row { display:flex; align-items:center; justify-content:space-between; padding:1rem 1.25rem; border-bottom:1px solid var(--bs-border-color); }
.card-header-row h3 { font-size:.95rem; font-weight:700; margin:0; }
.results-count { font-size:.8rem; color:var(--bs-secondary-color); }

/* Cards de comentarios */
.opinion-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(340px,1fr)); gap:1rem; padding:1.25rem; }
.opinion-card { background:var(--bs-body-bg); border:1px solid var(--bs-border-color); border-radius:.75rem; padding:1.1rem; display:flex; flex-direction:column; gap:.75rem; transition:box-shadow .15s; }
.opinion-card:hover { box-shadow:0 4px 16px rgba(0,0,0,.07); }

.opinion-card-header { display:flex; align-items:center; gap:.65rem; }
.opinion-avatar { width:40px; height:40px; border-radius:50%; background:#ede9ff; color:#534AB7; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:.9rem; flex-shrink:0; }
.opinion-autor   { font-weight:700; font-size:.88rem; }
.opinion-email   { font-size:.75rem; color:var(--bs-secondary-color); }
.opinion-date    { font-size:.72rem; color:var(--bs-secondary-color); margin-left:auto; white-space:nowrap; }

.stars { color:#f59e0b; font-size:.8rem; display:flex; gap:.15rem; }

.opinion-body { font-size:.85rem; color:var(--bs-body-color); line-height:1.55; display:-webkit-box; -webkit-line-clamp:4; -webkit-box-orient:vertical; overflow:hidden; }

.badge-estado-opinion { display:inline-flex; align-items:center; gap:.3rem; font-size:.72rem; font-weight:700; padding:.25em .65em; border-radius:999px; }
.op-aprobado  { background:#e6f9f0; color:#1a7f4b; }
.op-pendiente { background:#fff3e0; color:#e65c00; }
.op-rechazado { background:#fdecea; color:#c0392b; }

.badge-inicio { display:inline-flex; align-items:center; gap:.25rem; font-size:.72rem; font-weight:700; padding:.22em .6em; border-radius:999px; background:#ede9ff; color:#534AB7; }

.opinion-actions { display:flex; gap:.4rem; flex-wrap:wrap; margin-top:auto; padding-top:.5rem; border-top:1px solid var(--bs-border-color); }
.btn-op { border:none; border-radius:.4rem; font-size:.78rem; font-weight:600; padding:.35rem .75rem; cursor:pointer; transition:all .15s; display:inline-flex; align-items:center; gap:.3rem; }
.btn-op-aprobar   { background:#e6f9f0; color:#1a7f4b; }
.btn-op-aprobar:hover { background:#c3f1d9; }
.btn-op-rechazar  { background:#fff3e0; color:#e65c00; }
.btn-op-rechazar:hover { background:#fde8b0; }
.btn-op-inicio    { background:#ede9ff; color:#534AB7; }
.btn-op-inicio:hover { background:#d4cef5; }
.btn-op-quitinicio { background:var(--bs-tertiary-bg); color:var(--bs-secondary-color); border:1px solid var(--bs-border-color); }
.btn-op-quitinicio:hover { background:var(--bs-secondary-bg); }
.btn-op-delete    { background:#fdecea; color:#c0392b; margin-left:auto; }
.btn-op-delete:hover { background:#fbc4c0; }

.empty-state { text-align:center; padding:3rem 1rem; color:var(--bs-secondary-color); }
.empty-state i { font-size:2.5rem; margin-bottom:.75rem; display:block; }

.pagination-wrap { padding:.875rem 1.25rem; border-top:1px solid var(--bs-border-color); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.5rem; }
.pagination-info { font-size:.8rem; color:var(--bs-secondary-color); }
</style>
@endpush

@section('contenido')

<div class="dash-page-header">
    <div>
        <h1><i class="bi bi-chat-quote-fill me-2" style="color:#534AB7;"></i>Gestión de Opiniones</h1>
        <p>Modera los comentarios y decide cuáles aparecen en la página de inicio.</p>
    </div>
    <span class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill"
          style="background:#ede9ff;color:#534AB7;font-size:.78rem;font-weight:700;">
        <i class="bi bi-shield-fill"></i> Administrador
    </span>
</div>

{{-- Mini stats --}}
<div class="mini-stats">
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#e8f4fd;color:#1a6fa8;"><i class="bi bi-chat-dots-fill"></i></div>
        <div><div class="mini-stat-num">{{ $stats['total'] }}</div><div class="mini-stat-lbl">Total opiniones</div></div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#e6f9f0;color:#1a7f4b;"><i class="bi bi-check-circle-fill"></i></div>
        <div><div class="mini-stat-num">{{ $stats['aprobados'] }}</div><div class="mini-stat-lbl">Aprobadas</div></div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#fff3e0;color:#e65c00;"><i class="bi bi-clock-fill"></i></div>
        <div><div class="mini-stat-num">{{ $stats['pendientes'] }}</div><div class="mini-stat-lbl">Pendientes</div></div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#ede9ff;color:#534AB7;"><i class="bi bi-house-heart-fill"></i></div>
        <div><div class="mini-stat-num">{{ $stats['en_inicio'] }}</div><div class="mini-stat-lbl">En inicio</div></div>
    </div>
</div>

{{-- Filtros --}}
<form method="GET" action="{{ route('admin.opiniones') }}" class="filter-bar">
    <div class="filter-group" style="max-width:260px;">
        <label>Buscar</label>
        <input type="text" name="buscar" class="form-control" placeholder="Autor o contenido..." value="{{ request('buscar') }}">
    </div>
    <div class="filter-group" style="max-width:160px;">
        <label>Estado</label>
        <select name="estado" class="form-select">
            <option value="">Todos</option>
            <option value="aprobado"  {{ request('estado') === 'aprobado'  ? 'selected' : '' }}>Aprobado</option>
            <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
            <option value="rechazado" {{ request('estado') === 'rechazado' ? 'selected' : '' }}>Rechazado</option>
        </select>
    </div>
    <div class="filter-group" style="max-width:160px;">
        <label>En inicio</label>
        <select name="en_inicio" class="form-select">
            <option value="">Todos</option>
            <option value="1" {{ request('en_inicio') === '1' ? 'selected' : '' }}>Sí aparece</option>
            <option value="0" {{ request('en_inicio') === '0' ? 'selected' : '' }}>No aparece</option>
        </select>
    </div>
    <div class="d-flex gap-2 align-items-end">
        <button type="submit" class="btn-filter btn-filter-apply"><i class="bi bi-search me-1"></i>Filtrar</button>
        @if(request()->hasAny(['buscar','estado','en_inicio']))
            <a href="{{ route('admin.opiniones') }}" class="btn-filter btn-filter-clear">Limpiar</a>
        @endif
    </div>
</form>

{{-- Tabla/grid --}}
<div class="dash-card">
    <div class="card-header-row">
        <h3><i class="bi bi-chat-quote-fill me-2" style="color:#534AB7;"></i>Opiniones</h3>
        <span class="results-count">{{ $comentarios->total() }} opiniones</span>
    </div>

    @if($comentarios->isEmpty())
        <div class="empty-state">
            <i class="bi bi-chat-slash"></i>
            <p>No se encontraron opiniones con los filtros aplicados.</p>
        </div>
    @else
        <div class="opinion-grid">
            @foreach($comentarios as $c)
            <div class="opinion-card">
                {{-- Header --}}
                <div class="opinion-card-header">
                    <div class="opinion-avatar">{{ strtoupper(substr($c->autor_nombre, 0, 2)) }}</div>
                    <div>
                        <div class="opinion-autor">{{ $c->autor_nombre }}</div>
                        @if($c->autor_email)
                            <div class="opinion-email">{{ $c->autor_email }}</div>
                        @endif
                    </div>
                    <span class="opinion-date">{{ $c->created_at->format('d/m/Y') }}</span>
                </div>

                {{-- Estrellas --}}
                <div class="stars">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star{{ $i <= $c->calificacion ? '-fill' : '' }}"></i>
                    @endfor
                    <span style="font-size:.75rem;color:var(--bs-secondary-color);margin-left:.25rem;">{{ $c->calificacion }}/5</span>
                </div>

                {{-- Cuerpo --}}
                <p class="opinion-body">{{ $c->cuerpo }}</p>

                {{-- Badges --}}
                <div class="d-flex gap-2 flex-wrap">
                    <span class="badge-estado-opinion op-{{ $c->estado }}">
                        <i class="bi bi-circle-fill" style="font-size:.45rem;"></i>
                        {{ ucfirst($c->estado) }}
                    </span>
                    @if($c->en_inicio && $c->estado === 'aprobado')
                        <span class="badge-inicio"><i class="bi bi-house-heart-fill"></i> En inicio</span>
                    @endif
                    @if($c->user_id)
                        <span style="font-size:.72rem;background:var(--bs-tertiary-bg);color:var(--bs-secondary-color);padding:.22em .6em;border-radius:999px;font-weight:600;">
                            <i class="bi bi-person-fill"></i> Registrado
                        </span>
                    @endif
                </div>

                {{-- Acciones --}}
                <div class="opinion-actions">
                    @if($c->estado !== 'aprobado')
                        <form method="POST" action="{{ route('admin.opiniones.estado', $c) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="estado" value="aprobado">
                            <button type="submit" class="btn-op btn-op-aprobar"><i class="bi bi-check-lg"></i> Aprobar</button>
                        </form>
                    @endif

                    @if($c->estado !== 'rechazado')
                        <form method="POST" action="{{ route('admin.opiniones.estado', $c) }}" onsubmit="return confirm('¿Rechazar este comentario?')">
                            @csrf @method('PATCH')
                            <input type="hidden" name="estado" value="rechazado">
                            <button type="submit" class="btn-op btn-op-rechazar"><i class="bi bi-x-lg"></i> Rechazar</button>
                        </form>
                    @endif

                    @if($c->estado === 'aprobado')
                        <form method="POST" action="{{ route('admin.opiniones.inicio', $c) }}">
                            @csrf @method('PATCH')
                            @if($c->en_inicio)
                                <button type="submit" class="btn-op btn-op-quitinicio"><i class="bi bi-house-slash"></i> Quitar inicio</button>
                            @else
                                <button type="submit" class="btn-op btn-op-inicio"><i class="bi bi-house-heart-fill"></i> Poner en inicio</button>
                            @endif
                        </form>
                    @endif

                    <form method="POST" action="{{ route('admin.opiniones.destroy', $c) }}" onsubmit="return confirm('¿Eliminar esta opinión permanentemente?')" style="margin-left:auto;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-op btn-op-delete"><i class="bi bi-trash3-fill"></i></button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    @if($comentarios->hasPages())
        <div class="pagination-wrap">
            <span class="pagination-info">Página {{ $comentarios->currentPage() }} de {{ $comentarios->lastPage() }}</span>
            {{ $comentarios->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

@endsection