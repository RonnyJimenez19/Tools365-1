{{-- resources/views/dashboard/admin/herramientas.blade.php --}}
@extends('layouts.dashboard')

@section('titulo_pagina', 'Todas las Herramientas – Tools365')
@section('topbar_title', 'Herramientas')
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
.filter-group { display:flex; flex-direction:column; gap:.3rem; min-width:140px; flex:1; }
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

.admin-table { width:100%; border-collapse:collapse; font-size:.85rem; }
.admin-table thead th { padding:.6rem 1rem; font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--bs-secondary-color); border-bottom:1px solid var(--bs-border-color); white-space:nowrap; }
.admin-table tbody td { padding:.8rem 1rem; border-bottom:1px solid var(--bs-border-color); vertical-align:middle; }
.admin-table tbody tr:last-child td { border-bottom:none; }
.admin-table tbody tr:hover { background:var(--bs-tertiary-bg); }

.product-thumb { width:44px; height:44px; border-radius:.5rem; object-fit:cover; background:var(--bs-tertiary-bg); flex-shrink:0; }
.product-thumb-placeholder { width:44px; height:44px; border-radius:.5rem; background:var(--bs-tertiary-bg); display:flex; align-items:center; justify-content:center; color:var(--bs-secondary-color); flex-shrink:0; }
.product-row { display:flex; align-items:center; gap:.65rem; }
.product-title { font-weight:600; font-size:.85rem; max-width:220px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.product-meta  { font-size:.72rem; color:var(--bs-secondary-color); }

.badge-tipo { display:inline-flex; align-items:center; gap:.25rem; font-size:.72rem; font-weight:700; padding:.25em .6em; border-radius:999px; white-space:nowrap; }
.tipo-renta   { background:#e8f4fd; color:#1a6fa8; }
.tipo-venta   { background:#e6f9f0; color:#1a7f4b; }
.tipo-subasta { background:#fff3e0; color:#e65c00; }

.badge-estado-prod { display:inline-flex; align-items:center; gap:.25rem; font-size:.72rem; font-weight:700; padding:.25em .6em; border-radius:999px; white-space:nowrap; }
.ep-activo    { background:#e6f9f0; color:#1a7f4b; }
.ep-pausado   { background:#fff3e0; color:#e65c00; }
.ep-vendido   { background:#e8f4fd; color:#1a6fa8; }
.ep-eliminado { background:#fdecea; color:#c0392b; }

.tbl-actions { display:flex; gap:.3rem; }
.btn-tbl { width:30px; height:30px; border:none; border-radius:.375rem; display:flex; align-items:center; justify-content:center; font-size:.8rem; cursor:pointer; transition:background .15s; }
.btn-tbl-view    { background:#e8f4fd; color:#1a6fa8; }
.btn-tbl-view:hover { background:#bee3f8; }
.btn-tbl-edit    { background:#ede9ff; color:#534AB7; }
.btn-tbl-edit:hover { background:#d4cef5; }
.btn-tbl-delete  { background:#fdecea; color:#c0392b; }
.btn-tbl-delete:hover { background:#fbc4c0; }

/* Modal */
.modal-content { border-radius:.875rem; border:1px solid var(--bs-border-color); box-shadow:0 20px 60px rgba(0,0,0,.12); }
.modal-header  { border-bottom:1px solid var(--bs-border-color); padding:1rem 1.25rem; }
.modal-title   { font-size:1rem; font-weight:700; }
.modal-body    { padding:1.25rem; }
.modal-footer  { border-top:1px solid var(--bs-border-color); padding:.875rem 1.25rem; }
.form-label-sm { font-size:.78rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:var(--bs-secondary-color); margin-bottom:.35rem; }
.form-select-sm-custom { font-size:.875rem; border-radius:.5rem; border:1px solid var(--bs-border-color); background:var(--bs-body-bg); color:var(--bs-body-color); padding:.45rem .75rem; width:100%; }
.form-select-sm-custom:focus { outline:none; border-color:#534AB7; box-shadow:0 0 0 .2rem rgba(83,74,183,.15); }
.btn-save   { background:#534AB7; color:#fff; border:none; padding:.5rem 1.25rem; border-radius:.5rem; font-size:.875rem; font-weight:600; cursor:pointer; transition:background .15s; }
.btn-save:hover { background:#453da0; }
.btn-cancel { background:var(--bs-tertiary-bg); color:var(--bs-body-color); border:1px solid var(--bs-border-color); padding:.5rem 1rem; border-radius:.5rem; font-size:.875rem; cursor:pointer; transition:background .15s; }
.btn-cancel:hover { background:var(--bs-secondary-bg); }

.pagination-wrap { padding:.875rem 1.25rem; border-top:1px solid var(--bs-border-color); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.5rem; }
.pagination-info { font-size:.8rem; color:var(--bs-secondary-color); }
.empty-state { text-align:center; padding:3rem 1rem; color:var(--bs-secondary-color); }
.empty-state i { font-size:2.5rem; margin-bottom:.75rem; display:block; }
</style>
@endpush

@section('contenido')

<div class="dash-page-header">
    <div>
        <h1><i class="bi bi-wrench-adjustable-circle-fill me-2" style="color:#534AB7;"></i>Todas las Herramientas</h1>
        <p>Administra rentas y ventas de todos los usuarios de la plataforma.</p>
    </div>
    <span class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill"
          style="background:#ede9ff;color:#534AB7;font-size:.78rem;font-weight:700;">
        <i class="bi bi-shield-fill"></i> Administrador
    </span>
</div>

{{-- Mini stats --}}
<div class="mini-stats">
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#e8f4fd;color:#1a6fa8;"><i class="bi bi-box-seam-fill"></i></div>
        <div><div class="mini-stat-num">{{ $stats['total'] }}</div><div class="mini-stat-lbl">Total</div></div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#e6f9f0;color:#1a7f4b;"><i class="bi bi-check-circle-fill"></i></div>
        <div><div class="mini-stat-num">{{ $stats['activos'] }}</div><div class="mini-stat-lbl">Activas</div></div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#fff3e0;color:#e65c00;"><i class="bi bi-pause-circle-fill"></i></div>
        <div><div class="mini-stat-num">{{ $stats['pausados'] }}</div><div class="mini-stat-lbl">Pausadas</div></div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#e8f4fd;color:#1a6fa8;"><i class="bi bi-clock-history"></i></div>
        <div><div class="mini-stat-num">{{ $stats['rentas'] }}</div><div class="mini-stat-lbl">Rentas activas</div></div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#e6f9f0;color:#1a7f4b;"><i class="bi bi-tag-fill"></i></div>
        <div><div class="mini-stat-num">{{ $stats['ventas'] }}</div><div class="mini-stat-lbl">Ventas activas</div></div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#fdecea;color:#c0392b;"><i class="bi bi-bag-check-fill"></i></div>
        <div><div class="mini-stat-num">{{ $stats['vendidos'] }}</div><div class="mini-stat-lbl">Vendidas</div></div>
    </div>
</div>

{{-- Filtros --}}
<form method="GET" action="{{ route('admin.herramientas') }}" class="filter-bar">
    <div class="filter-group" style="max-width:240px;">
        <label>Buscar</label>
        <input type="text" name="buscar" class="form-control" placeholder="Título o ubicación..." value="{{ request('buscar') }}">
    </div>
    <div class="filter-group" style="max-width:140px;">
        <label>Tipo</label>
        <select name="tipo" class="form-select">
            <option value="">Todos</option>
            <option value="renta" {{ request('tipo') === 'renta' ? 'selected' : '' }}>Renta</option>
            <option value="venta" {{ request('tipo') === 'venta' ? 'selected' : '' }}>Venta</option>
        </select>
    </div>
    <div class="filter-group" style="max-width:140px;">
        <label>Estado</label>
        <select name="estado" class="form-select">
            <option value="">Todos</option>
            <option value="activo"    {{ request('estado') === 'activo'    ? 'selected' : '' }}>Activo</option>
            <option value="pausado"   {{ request('estado') === 'pausado'   ? 'selected' : '' }}>Pausado</option>
            <option value="vendido"   {{ request('estado') === 'vendido'   ? 'selected' : '' }}>Vendido</option>
            <option value="eliminado" {{ request('estado') === 'eliminado' ? 'selected' : '' }}>Eliminado</option>
        </select>
    </div>
    <div class="filter-group" style="max-width:180px;">
        <label>Categoría</label>
        <select name="categoria_id" class="form-select">
            <option value="">Todas</option>
            @foreach($categorias as $cat)
                <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-group" style="max-width:180px;">
        <label>Usuario</label>
        <select name="usuario_id" class="form-select">
            <option value="">Todos</option>
            @foreach($usuarios as $u)
                <option value="{{ $u->id }}" {{ request('usuario_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="d-flex gap-2 align-items-end">
        <button type="submit" class="btn-filter btn-filter-apply"><i class="bi bi-search me-1"></i>Filtrar</button>
        @if(request()->hasAny(['buscar','tipo','estado','categoria_id','usuario_id']))
            <a href="{{ route('admin.herramientas') }}" class="btn-filter btn-filter-clear">Limpiar</a>
        @endif
    </div>
</form>

{{-- Tabla --}}
<div class="dash-card">
    <div class="card-header-row">
        <h3><i class="bi bi-wrench-adjustable-circle-fill me-2" style="color:#534AB7;"></i>Herramientas</h3>
        <span class="results-count">{{ $herramientas->firstItem() }}–{{ $herramientas->lastItem() }} de {{ $herramientas->total() }}</span>
    </div>

    <div class="table-responsive">
        @if($herramientas->isEmpty())
            <div class="empty-state">
                <i class="bi bi-box-seam"></i>
                <p>No se encontraron herramientas con los filtros aplicados.</p>
            </div>
        @else
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Herramienta</th>
                    <th>Tipo</th>
                    <th>Precio</th>
                    <th>Propietario</th>
                    <th>Categoría</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($herramientas as $index => $h)
                <tr>
                    <td class="text-muted" style="font-size:.78rem;">{{ $herramientas->firstItem() + $index }}</td>
                    <td>
                        <div class="product-row">
                            @if($h->imagenes->first())
                                <img src="{{ asset($h->imagenes->first()->ruta) }}" class="product-thumb" alt="">
                            @else
                                <div class="product-thumb-placeholder"><i class="bi bi-image"></i></div>
                            @endif
                            <div>
                                <div class="product-title" title="{{ $h->titulo }}">{{ $h->titulo }}</div>
                                <div class="product-meta"><i class="bi bi-geo-alt-fill"></i> {{ $h->ubicacion ?? '—' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge-tipo tipo-{{ $h->tipo }}">
                            <i class="bi bi-{{ $h->tipo === 'renta' ? 'clock-history' : 'tag-fill' }}"></i>
                            {{ ucfirst($h->tipo) }}
                        </span>
                    </td>
                    <td style="font-weight:700;white-space:nowrap;">${{ number_format($h->precio, 2) }}</td>
                    <td>
                        <div style="font-size:.82rem;font-weight:600;">{{ $h->user?->name ?? '—' }}</div>
                        <div style="font-size:.72rem;color:var(--bs-secondary-color);">Plan: {{ $h->user?->plan ?? 'free' }}</div>
                    </td>
                    <td style="font-size:.8rem;">{{ $h->categoria?->nombre ?? '—' }}</td>
                    <td>
                        <span class="badge-estado-prod ep-{{ $h->estado }}">
                            <i class="bi bi-circle-fill" style="font-size:.45rem;"></i>
                            {{ ucfirst($h->estado) }}
                        </span>
                    </td>
                    <td style="font-size:.75rem;color:var(--bs-secondary-color);">{{ $h->created_at->format('d/m/Y') }}</td>
                    <td>
                        <div class="tbl-actions">
                            <a href="{{ route('productos.show', $h) }}" class="btn-tbl btn-tbl-view" title="Ver producto" target="_blank">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <button type="button" class="btn-tbl btn-tbl-edit" title="Cambiar estado"
                                    data-bs-toggle="modal" data-bs-target="#modalEditarHerramienta"
                                    data-id="{{ $h->id }}"
                                    data-titulo="{{ $h->titulo }}"
                                    data-estado="{{ $h->estado }}">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.herramientas.destroy', $h) }}"
                                  onsubmit="return confirm('¿Eliminar permanentemente \"{{ addslashes($h->titulo) }}\"?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-tbl btn-tbl-delete" title="Eliminar">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    @if($herramientas->hasPages())
        <div class="pagination-wrap">
            <span class="pagination-info">Página {{ $herramientas->currentPage() }} de {{ $herramientas->lastPage() }}</span>
            {{ $herramientas->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

{{-- Modal cambiar estado --}}
<div class="modal fade" id="modalEditarHerramienta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:380px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil-fill me-2" style="color:#534AB7;"></i>Cambiar estado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditarHerramienta" method="POST" action="">
                @csrf @method('PATCH')
                <div class="modal-body">
                    <div style="background:var(--bs-tertiary-bg);border:1px solid var(--bs-border-color);border-radius:.625rem;padding:.875rem;margin-bottom:1.1rem;">
                        <div id="hModalTitulo" style="font-weight:700;font-size:.9rem;"></div>
                    </div>
                    <label class="form-label-sm">Estado</label>
                    <select name="estado" id="hModalEstado" class="form-select-sm-custom">
                        <option value="activo">Activo</option>
                        <option value="pausado">Pausado</option>
                        <option value="vendido">Vendido</option>
                        <option value="eliminado">Eliminado</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-save"><i class="bi bi-check-lg me-1"></i>Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('modalEditarHerramienta').addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    document.getElementById('formEditarHerramienta').action = `/dashboard/admin/herramientas/${btn.dataset.id}`;
    document.getElementById('hModalTitulo').textContent = btn.dataset.titulo;
    document.getElementById('hModalEstado').value = btn.dataset.estado;
});
</script>
@endpush