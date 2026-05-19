{{-- resources/views/dashboard/admin/publicaciones.blade.php --}}
@extends('layouts.dashboard')

@section('titulo_pagina', 'Gestión de Publicaciones – Tools365')
@section('topbar_title', 'Publicaciones')
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

/* ── MINI STATS ── */
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

/* ── FILTROS ── */
.filter-bar {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: .75rem;
    padding: 1rem 1.25rem;
    display: flex; flex-wrap: wrap; gap: .75rem; align-items: flex-end;
    margin-bottom: 1.25rem;
}
.filter-group { display: flex; flex-direction: column; gap: .3rem; min-width: 150px; flex: 1; }
.filter-group label { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--bs-secondary-color); }
.filter-group .form-control,
.filter-group .form-select {
    font-size: .85rem; border-radius: .5rem;
    border-color: var(--bs-border-color);
    background: var(--bs-body-bg); color: var(--bs-body-color);
    padding: .4rem .75rem;
}
.filter-group .form-control:focus,
.filter-group .form-select:focus {
    border-color: #27ae60;
    box-shadow: 0 0 0 .2rem rgba(39,174,96,.15);
}
.btn-filter { padding: .42rem 1rem; border-radius: .5rem; font-size: .85rem; font-weight: 600; cursor: pointer; transition: all .15s; white-space: nowrap; }
.btn-filter-apply  { background: #27ae60; color: #fff; border: none; }
.btn-filter-apply:hover { background: #219a52; }
.btn-filter-clear  { background: var(--bs-tertiary-bg); color: var(--bs-body-color); border: 1px solid var(--bs-border-color); }
.btn-filter-clear:hover { background: var(--bs-secondary-bg); }

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

.pub-row { display: flex; align-items: center; gap: .65rem; }
.pub-thumb-sm {
    width: 42px; height: 42px; border-radius: .5rem;
    background: var(--bs-tertiary-bg);
    border: 1px solid var(--bs-border-color);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; color: var(--bs-secondary-color);
    flex-shrink: 0; overflow: hidden;
}
.pub-thumb-sm img { width: 100%; height: 100%; object-fit: cover; }
.pub-titulo { font-weight: 600; font-size: .85rem; max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.pub-vendedor { font-size: .75rem; color: var(--bs-secondary-color); }

/* Badges tipo */
.badge-tipo {
    display: inline-flex; align-items: center;
    font-size: .72rem; font-weight: 700; padding: .25em .6em;
    border-radius: 999px; white-space: nowrap;
}
.tipo-venta   { background: #e8f4fd; color: #1a6fa8; }
.tipo-renta   { background: #e6f9f0; color: #1a7f4b; }
.tipo-subasta { background: #fff3e0; color: #e65c00; }

/* Badges estado */
.badge-estado {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .72rem; font-weight: 600; padding: .25em .6em;
    border-radius: 999px; white-space: nowrap;
}
.estado-activo   { background: #e6f9f0; color: #1a7f4b; }
.estado-pausado  { background: #fff3e0; color: #e65c00; }
.estado-vendido  { background: #e8f4fd; color: #1a6fa8; }
.estado-eliminado { background: #fdecea; color: #c0392b; }
.dot { font-size: .45rem; }

/* Botones tabla */
.tbl-actions { display: flex; gap: .3rem; }
.btn-tbl {
    width: 30px; height: 30px; border: none; border-radius: .375rem;
    display: flex; align-items: center; justify-content: center;
    font-size: .8rem; cursor: pointer; transition: background .15s;
}
.btn-tbl-edit    { background: #e8f4fd; color: #1a6fa8; }
.btn-tbl-edit:hover { background: #bee3f8; }
.btn-tbl-pause   { background: #fff3e0; color: #e65c00; }
.btn-tbl-pause:hover { background: #ffe0b2; }
.btn-tbl-del     { background: #fdecea; color: #c0392b; }
.btn-tbl-del:hover { background: #fbc4c0; }
.btn-tbl-restore { background: #e6f9f0; color: #1a7f4b; }
.btn-tbl-restore:hover { background: #c3f1d9; }

/* ── MODAL ── */
.modal-content { border-radius: .875rem; border: 1px solid var(--bs-border-color); box-shadow: 0 20px 60px rgba(0,0,0,.12); }
.modal-header { border-bottom: 1px solid var(--bs-border-color); padding: 1rem 1.25rem; }
.modal-title { font-size: 1rem; font-weight: 700; }
.modal-body  { padding: 1.25rem; }
.modal-footer { border-top: 1px solid var(--bs-border-color); padding: .875rem 1.25rem; }

.form-label-sm { font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: var(--bs-secondary-color); margin-bottom: .35rem; }
.form-select-sm-custom {
    font-size: .875rem; border-radius: .5rem;
    border: 1px solid var(--bs-border-color);
    background: var(--bs-body-bg); color: var(--bs-body-color);
    padding: .45rem .75rem; width: 100%;
}
.form-select-sm-custom:focus { outline: none; border-color: #27ae60; box-shadow: 0 0 0 .2rem rgba(39,174,96,.15); }

.pub-modal-card {
    display: flex; align-items: flex-start; gap: .85rem;
    background: var(--bs-tertiary-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: .625rem;
    padding: .875rem 1rem;
    margin-bottom: 1.25rem;
}
.pub-modal-icon {
    width: 44px; height: 44px; border-radius: .5rem;
    background: #e6f9f0; color: #27ae60;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; flex-shrink: 0;
}

.btn-save   { background: #27ae60; color: #fff; border: none; padding: .5rem 1.25rem; border-radius: .5rem; font-size: .875rem; font-weight: 600; cursor: pointer; transition: background .15s; }
.btn-save:hover { background: #219a52; }
.btn-cancel { background: var(--bs-tertiary-bg); color: var(--bs-body-color); border: 1px solid var(--bs-border-color); padding: .5rem 1rem; border-radius: .5rem; font-size: .875rem; cursor: pointer; transition: background .15s; }
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
.empty-state { text-align: center; padding: 3rem 1rem; color: var(--bs-secondary-color); }
.empty-state i { font-size: 2.5rem; margin-bottom: .75rem; display: block; }
.empty-state p { font-size: .9rem; margin: 0; }

/* Precio */
.pub-price { font-weight: 700; color: var(--bs-body-color); }
.pub-price small { font-weight: 400; color: var(--bs-secondary-color); font-size: .75rem; }
</style>
@endpush

@section('contenido')

{{-- ── PAGE HEADER ── --}}
<div class="dash-page-header">
    <div>
        <h1><i class="bi bi-box-seam-fill me-2" style="color:#27ae60;"></i>Gestión de Publicaciones</h1>
        <p>Administra, edita y modera las publicaciones de los usuarios en la plataforma.</p>
    </div>
    <span class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill"
          style="background:#e6f9f0;color:#1a7f4b;font-size:.78rem;font-weight:700;">
        <i class="bi bi-shield-fill"></i> Administrador
    </span>
</div>

{{-- ── MINI STATS ── --}}
<div class="mini-stats">
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#e8f4fd;color:#1a6fa8;"><i class="bi bi-box-seam-fill"></i></div>
        <div>
            <div class="mini-stat-num">{{ $stats['total'] }}</div>
            <div class="mini-stat-lbl">Total publicaciones</div>
        </div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#e6f9f0;color:#1a7f4b;"><i class="bi bi-check-circle-fill"></i></div>
        <div>
            <div class="mini-stat-num">{{ $stats['activos'] }}</div>
            <div class="mini-stat-lbl">Activas</div>
        </div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#fff3e0;color:#e65c00;"><i class="bi bi-pause-circle-fill"></i></div>
        <div>
            <div class="mini-stat-num">{{ $stats['pausados'] }}</div>
            <div class="mini-stat-lbl">Pausadas</div>
        </div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#e8f4fd;color:#1a6fa8;"><i class="bi bi-bag-check-fill"></i></div>
        <div>
            <div class="mini-stat-num">{{ $stats['vendidos'] }}</div>
            <div class="mini-stat-lbl">Vendidas</div>
        </div>
    </div>
</div>

{{-- ── FILTROS ── --}}
<form method="GET" action="{{ route('admin.publicaciones') }}" class="filter-bar">
    <div class="filter-group" style="max-width:280px;">
        <label>Buscar</label>
        <input type="text" name="buscar" class="form-control"
               placeholder="Título de la publicación..."
               value="{{ request('buscar') }}">
    </div>
    <div class="filter-group" style="max-width:150px;">
        <label>Tipo</label>
        <select name="tipo" class="form-select">
            <option value="">Todos</option>
            <option value="venta"   {{ request('tipo') === 'venta'   ? 'selected' : '' }}>Venta</option>
            <option value="renta"   {{ request('tipo') === 'renta'   ? 'selected' : '' }}>Renta</option>
            <option value="subasta" {{ request('tipo') === 'subasta' ? 'selected' : '' }}>Subasta</option>
        </select>
    </div>
    <div class="filter-group" style="max-width:150px;">
        <label>Estado</label>
        <select name="estado" class="form-select">
            <option value="">Todos</option>
            <option value="activo"   {{ request('estado') === 'activo'   ? 'selected' : '' }}>Activo</option>
            <option value="pausado"  {{ request('estado') === 'pausado'  ? 'selected' : '' }}>Pausado</option>
            <option value="vendido"  {{ request('estado') === 'vendido'  ? 'selected' : '' }}>Vendido</option>
        </select>
    </div>
    <div class="d-flex gap-2 align-items-end">
        <button type="submit" class="btn-filter btn-filter-apply">
            <i class="bi bi-search me-1"></i>Filtrar
        </button>
        @if(request()->hasAny(['buscar','tipo','estado']))
            <a href="{{ route('admin.publicaciones') }}" class="btn-filter btn-filter-clear">
                Limpiar
            </a>
        @endif
    </div>
</form>

{{-- ── TABLA ── --}}
<div class="dash-card">
    <div class="card-header-row">
        <h3><i class="bi bi-box-seam-fill me-2" style="color:#27ae60;"></i>Publicaciones</h3>
        <span class="results-count">
            @if($publicaciones->total() > 0)
                {{ $publicaciones->firstItem() }}–{{ $publicaciones->lastItem() }} de {{ $publicaciones->total() }} publicaciones
            @else
                0 publicaciones
            @endif
        </span>
    </div>

    <div class="table-responsive">
        @if($publicaciones->isEmpty())
            <div class="empty-state">
                <i class="bi bi-box-seam"></i>
                <p>No se encontraron publicaciones con los filtros aplicados.</p>
            </div>
        @else
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Publicación</th>
                    <th>Vendedor</th>
                    <th>Tipo</th>
                    <th>Precio</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($publicaciones as $pub)
                <tr>
                    {{-- ID --}}
                    <td class="text-muted" style="font-size:.78rem;">{{ $publicaciones->firstItem() + $loop->index }}</td>
                    

                    {{-- Publicación --}}
                    <td>
                        <div class="pub-row">
                            <div class="pub-thumb-sm">
                                @if($pub->imagenes && $pub->imagenes->first())
                                    <img src="{{ asset($pub->imagenes->first()->ruta) }}" alt="">
                                @else
                                    <i class="bi bi-gear"></i>
                                @endif
                            </div>
                            <div>
                                <div class="pub-titulo" title="{{ $pub->titulo }}">{{ $pub->titulo }}</div>
                                <div class="pub-vendedor">{{ $pub->ubicacion }}</div>
                            </div>
                        </div>
                    </td>

                    {{-- Vendedor --}}
                    <td style="font-size:.82rem;">
                        @if($pub->user)
                            <div style="font-weight:600;">{{ $pub->user->name }}</div>
                            <div style="font-size:.72rem;color:var(--bs-secondary-color);">{{ $pub->user->email }}</div>
                        @else
                            <span style="color:var(--bs-secondary-color);font-size:.78rem;">Sin usuario</span>
                        @endif
                    </td>

                    {{-- Tipo --}}
                    <td>
                        <span class="badge-tipo tipo-{{ $pub->tipo }}">{{ ucfirst($pub->tipo) }}</span>
                    </td>

                    {{-- Precio --}}
                    <td class="pub-price">
                        ${{ number_format($pub->precio, 0) }}
                        @if($pub->unidad && str_contains($pub->unidad, '/'))
                            <small>{{ $pub->unidad }}</small>
                        @endif
                    </td>

                    {{-- Estado --}}
                    <td>
                        @if($pub->estado === 'activo')
                            <span class="badge-estado estado-activo">
                                <i class="bi bi-circle-fill dot" style="font-size:.5rem;"></i> Activo
                            </span>
                        @elseif($pub->estado === 'pausado')
                            <span class="badge-estado estado-pausado">
                                <i class="bi bi-pause-circle-fill dot" style="font-size:.6rem;"></i> Pausado
                            </span>
                        @elseif($pub->estado === 'vendido')
                            <span class="badge-estado estado-vendido">
                                <i class="bi bi-bag-check-fill dot" style="font-size:.6rem;"></i> Vendido
                            </span>
                        @else
                            <span class="badge-estado estado-eliminado">
                                <i class="bi bi-x-circle-fill dot" style="font-size:.6rem;"></i> {{ ucfirst($pub->estado) }}
                            </span>
                        @endif
                    </td>

                    {{-- Fecha --}}
                    <td style="font-size:.75rem;color:var(--bs-secondary-color);">
                        {{ $pub->created_at->format('d/m/Y') }}
                    </td>

                    {{-- Acciones --}}
                    <td>
                        <div class="tbl-actions">
                            {{-- Ver publicación --}}
                            <a href="{{ route('productos.show', $pub) }}"
                               target="_blank"
                               class="btn-tbl btn-tbl-edit"
                               title="Ver publicación">
                                <i class="bi bi-eye-fill"></i>
                            </a>

                            {{-- Editar estado → abre modal --}}
                            <button type="button"
                                    class="btn-tbl btn-tbl-edit"
                                    title="Cambiar estado"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditarPub"
                                    data-id="{{ $pub->id }}"
                                    data-titulo="{{ $pub->titulo }}"
                                    data-estado="{{ $pub->estado }}"
                                    data-tipo="{{ $pub->tipo }}">
                                <i class="bi bi-pencil-fill"></i>
                            </button>

                            {{-- Toggle pausar/activar --}}
                            @if($pub->estado === 'activo')
                                <form method="POST"
                                      action="{{ route('admin.publicaciones.update', $pub) }}"
                                      style="display:inline;"
                                      onsubmit="return confirm('¿Pausar la publicación «{{ addslashes($pub->titulo) }}»?')">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="estado" value="pausado">
                                    <button type="submit" class="btn-tbl btn-tbl-pause" title="Pausar">
                                        <i class="bi bi-pause-fill"></i>
                                    </button>
                                </form>
                            @elseif($pub->estado === 'pausado')
                                <form method="POST"
                                      action="{{ route('admin.publicaciones.update', $pub) }}"
                                      style="display:inline;"
                                      onsubmit="return confirm('¿Reactivar la publicación «{{ addslashes($pub->titulo) }}»?')">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="estado" value="activo">
                                    <button type="submit" class="btn-tbl btn-tbl-restore" title="Reactivar">
                                        <i class="bi bi-play-fill"></i>
                                    </button>
                                </form>
                            @endif

                            {{-- Eliminar --}}
                            <form method="POST"
                                  action="{{ route('admin.publicaciones.destroy', $pub) }}"
                                  style="display:inline;"
                                  onsubmit="return confirm('¿Eliminar permanentemente «{{ addslashes($pub->titulo) }}»? Esta acción no se puede deshacer.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-tbl btn-tbl-del" title="Eliminar">
                                    <i class="bi bi-trash-fill"></i>
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

    {{-- Paginación --}}
    @if($publicaciones->hasPages())
    <div class="pagination-wrap">
        <span class="pagination-info">
            Página {{ $publicaciones->currentPage() }} de {{ $publicaciones->lastPage() }}
        </span>
        {{ $publicaciones->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

{{-- ══════════════════════════════════════════════════════
     MODAL – Editar estado de publicación
══════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalEditarPub" tabindex="-1" aria-labelledby="modalEditarPubLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarPubLabel">
                    <i class="bi bi-pencil-fill me-2" style="color:#27ae60;"></i>Editar publicación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formEditarPub" method="POST" action="">
                @csrf
                @method('PATCH')

                <div class="modal-body">
                    {{-- Tarjeta de la publicación --}}
                    <div class="pub-modal-card">
                        <div class="pub-modal-icon"><i class="bi bi-box-seam-fill"></i></div>
                        <div>
                            <div style="font-weight:700;font-size:.9rem;" id="modalPubTitulo"></div>
                            <div style="font-size:.78rem;color:var(--bs-secondary-color);" id="modalPubTipo"></div>
                        </div>
                    </div>

                    {{-- Estado --}}
                    <div class="mb-0">
                        <label class="form-label-sm">Estado</label>
                        <select name="estado" id="modalPubEstado" class="form-select-sm-custom">
                            <option value="activo">Activo</option>
                            <option value="pausado">Pausado</option>
                            <option value="vendido">Vendido</option>
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
    document.getElementById('modalEditarPub').addEventListener('show.bs.modal', function (e) {
        const btn    = e.relatedTarget;
        const id     = btn.dataset.id;
        const titulo = btn.dataset.titulo;
        const estado = btn.dataset.estado;
        const tipo   = btn.dataset.tipo;

        document.getElementById('formEditarPub').action = `/dashboard/admin/publicaciones/${id}`;
        document.getElementById('modalPubTitulo').textContent = titulo;
        document.getElementById('modalPubTipo').textContent   = tipo.charAt(0).toUpperCase() + tipo.slice(1);
        document.getElementById('modalPubEstado').value       = estado;
    });
</script>
@endpush