@extends('layouts.app')

@section('titulo_pagina', 'Mis publicaciones — Tools365')

@push('css')
<style>
/* ── Variables ─────────────────────────────────────────────────────── */
:root {
    --mp-accent:   #f97316;
    --mp-accent2:  #ea580c;
    --mp-dark:     #0f172a;
    --mp-surface:  #ffffff;
    --mp-border:   #e2e8f0;
    --mp-muted:    #64748b;
    --mp-radius:   14px;
    --mp-shadow:   0 4px 24px rgba(15,23,42,.07);
}
[data-theme="dark"] {
    --mp-surface: #1e293b;
    --mp-border:  #334155;
    --mp-muted:   #94a3b8;
    --mp-shadow:  0 4px 24px rgba(0,0,0,.3);
}

/* ── Page ──────────────────────────────────────────────────────────── */
.mp-page {
    padding-top: 160px;
    padding-bottom: 80px;
    min-height: 100vh;
    background: var(--color-bg, #f8fafc);
}
.mp-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 55%, #f97316 200%);
    padding-top: 196px;
    padding-bottom: 80px;
    margin-top: -160px;
    margin-bottom: -40px;
    position: relative;
    overflow: hidden;
}
.mp-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.mp-hero-content { position: relative; z-index: 1; }
.mp-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(249,115,22,.2); border: 1px solid rgba(249,115,22,.4);
    color: #fb923c; padding: 4px 14px; border-radius: 100px;
    font-size: .78rem; font-weight: 600; letter-spacing: .04em;
    text-transform: uppercase; margin-bottom: 14px;
}
.mp-hero h1 { color: #fff; font-weight: 800; font-size: clamp(1.6rem,4vw,2.3rem); margin-bottom: 8px; }
.mp-hero p  { color: rgba(255,255,255,.65); }

/* ── Tabs de estado ────────────────────────────────────────────────── */
.estado-tabs {
    display: flex; gap: 6px; flex-wrap: wrap;
    margin-bottom: 20px;
}
.estado-tab {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 16px; border-radius: 100px;
    font-size: .8rem; font-weight: 600;
    border: 1.5px solid var(--mp-border);
    background: var(--mp-surface);
    color: var(--mp-muted);
    text-decoration: none;
    transition: all .2s;
}
.estado-tab:hover { border-color: var(--mp-accent); color: var(--mp-accent); }
.estado-tab.activa {
    background: var(--mp-accent);
    border-color: var(--mp-accent);
    color: #fff;
}
.estado-tab .tab-count {
    background: rgba(255,255,255,.25);
    padding: 1px 7px; border-radius: 100px; font-size: .72rem;
}
.estado-tab:not(.activa) .tab-count {
    background: var(--mp-border);
    color: var(--mp-muted);
}

/* ── Toolbar ───────────────────────────────────────────────────────── */
.mp-toolbar {
    display: flex; gap: 10px; flex-wrap: wrap;
    align-items: center; margin-bottom: 20px;
}
.mp-search {
    flex: 1; min-width: 200px;
    display: flex; align-items: center;
    background: var(--mp-surface);
    border: 1.5px solid var(--mp-border);
    border-radius: 10px; overflow: hidden;
    padding: 0 14px;
    transition: border-color .2s;
}
.mp-search:focus-within { border-color: var(--mp-accent); }
.mp-search input {
    border: none; background: transparent;
    padding: 10px 8px; flex: 1; font-size: .88rem;
    color: var(--color-text, #0f172a);
    outline: none;
}
.mp-search i { color: var(--mp-muted); }
.mp-filter-select {
    padding: 10px 14px; border-radius: 10px;
    border: 1.5px solid var(--mp-border);
    background: var(--mp-surface);
    font-size: .85rem; color: var(--color-text, #0f172a);
    cursor: pointer;
    transition: border-color .2s;
}
.mp-filter-select:focus { outline: none; border-color: var(--mp-accent); }
.btn-nueva-pub {
    display: inline-flex; align-items: center; gap: 7px;
    background: linear-gradient(135deg, var(--mp-accent), #ea580c);
    color: #fff; border: none;
    padding: 10px 20px; border-radius: 10px;
    font-weight: 700; font-size: .85rem;
    text-decoration: none;
    transition: all .2s;
    box-shadow: 0 4px 12px rgba(249,115,22,.3);
    white-space: nowrap;
}
.btn-nueva-pub:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(249,115,22,.4); color:#fff; }

/* ── Tabla ─────────────────────────────────────────────────────────── */
.mp-card {
    background: var(--mp-surface);
    border: 1px solid var(--mp-border);
    border-radius: var(--mp-radius);
    box-shadow: var(--mp-shadow);
    overflow: hidden;
}
.mp-table { width: 100%; border-collapse: collapse; }
.mp-table thead th {
    padding: 12px 16px;
    font-size: .75rem; font-weight: 700;
    color: var(--mp-muted); text-transform: uppercase; letter-spacing: .06em;
    border-bottom: 1.5px solid var(--mp-border);
    background: var(--color-bg, #f8fafc);
    white-space: nowrap;
}
.mp-table tbody tr {
    border-bottom: 1px solid var(--mp-border);
    transition: background .15s;
}
.mp-table tbody tr:last-child { border-bottom: none; }
.mp-table tbody tr:hover { background: rgba(249,115,22,.03); }
.mp-table td { padding: 14px 16px; vertical-align: middle; }

/* ── Producto celda ────────────────────────────────────────────────── */
.prod-cell { display: flex; align-items: center; gap: 12px; }
.prod-thumb {
    width: 52px; height: 52px; border-radius: 10px;
    overflow: hidden; flex-shrink: 0;
    border: 1.5px solid var(--mp-border);
    background: var(--color-bg, #f1f5f9);
    display: flex; align-items: center; justify-content: center;
}
.prod-thumb img { width: 100%; height: 100%; object-fit: cover; }
.prod-thumb i { font-size: 1.3rem; color: var(--mp-muted); }
.prod-titulo { font-weight: 700; font-size: .9rem; color: var(--color-text, #0f172a); margin-bottom: 2px; }
.prod-meta { font-size: .75rem; color: var(--mp-muted); }

/* ── Badges ────────────────────────────────────────────────────────── */
.badge-tipo, .badge-estado-row {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 100px;
    font-size: .73rem; font-weight: 700;
}
.badge-renta   { background: #dbeafe; color: #1d4ed8; }
.badge-venta   { background: #dcfce7; color: #166534; }
.badge-subasta { background: #fef3c7; color: #92400e; }
.badge-activo  { background: #dcfce7; color: #166534; }
.badge-pausado { background: #fef3c7; color: #92400e; }
.badge-vendido { background: #ede9fe; color: #5b21b6; }
.badge-eliminado { background: #fee2e2; color: #991b1b; }

/* ── Precio ────────────────────────────────────────────────────────── */
.precio-cell { font-weight: 800; color: var(--mp-accent); font-size: .95rem; }
.precio-cell small { font-weight: 500; color: var(--mp-muted); font-size: .75rem; }

/* ── Acciones ──────────────────────────────────────────────────────── */
.acciones-cell { display: flex; align-items: center; gap: 6px; }
.btn-accion {
    display: inline-flex; align-items: center; justify-content: center;
    width: 34px; height: 34px; border-radius: 8px;
    border: 1.5px solid var(--mp-border);
    background: var(--mp-surface);
    color: var(--mp-muted);
    font-size: .85rem;
    text-decoration: none;
    transition: all .2s; cursor: pointer;
}
.btn-accion:hover { border-color: var(--mp-accent); color: var(--mp-accent); background: rgba(249,115,22,.06); }
.btn-accion.danger:hover { border-color: #ef4444; color: #ef4444; background: rgba(239,68,68,.06); }
.btn-accion.success:hover { border-color: #22c55e; color: #22c55e; background: rgba(34,197,94,.06); }
.btn-accion.warning:hover { border-color: #f59e0b; color: #f59e0b; background: rgba(245,158,11,.06); }

/* ── Empty state ───────────────────────────────────────────────────── */
.empty-state {
    text-align: center; padding: 60px 20px;
    color: var(--mp-muted);
}
.empty-state i { font-size: 3rem; margin-bottom: 14px; display: block; opacity: .4; }
.empty-state h4 { font-weight: 700; margin-bottom: 6px; color: var(--color-text, #0f172a); }
.empty-state p { font-size: .88rem; }

/* ── Pagination ────────────────────────────────────────────────────── */
.mp-pagination { padding: 16px 20px; border-top: 1px solid var(--mp-border); }
.mp-pagination .pagination { margin: 0; justify-content: center; }
.mp-pagination .page-link {
    border-radius: 8px !important; margin: 0 2px;
    border-color: var(--mp-border);
    color: var(--mp-muted);
}
.mp-pagination .page-item.active .page-link {
    background: var(--mp-accent); border-color: var(--mp-accent);
}

/* ── Modal confirmación ─────────────────────────────────────────────── */
.modal-confirm .modal-header { border-bottom: 1px solid var(--mp-border); padding: 20px 24px; }
.modal-confirm .modal-body   { padding: 24px; }
.modal-confirm .modal-footer { border-top: 1px solid var(--mp-border); padding: 16px 24px; }

@media (max-width: 768px) {
    .mp-table thead { display: none; }
    .mp-table tbody tr { display: block; padding: 12px 0; }
    .mp-table td { display: block; padding: 4px 16px; border: none; }
    .mp-table td::before {
        content: attr(data-label);
        font-size: .72rem; font-weight: 700;
        color: var(--mp-muted); display: block; margin-bottom: 2px;
    }
    .acciones-cell { flex-wrap: wrap; }
}
</style>
@endpush

@section('contenido')

{{-- Hero ──────────────────────────────────────────────────────────────────── --}}
<div class="mp-hero">
    <div class="container">
        <div class="mp-hero-content">
            <div class="mp-badge">
                <i class="bi bi-box-seam-fill"></i> Mis publicaciones
            </div>
            <h1>Gestiona tus herramientas</h1>
            <p>Administra, edita y monitorea el estado de todas tus publicaciones en un solo lugar.</p>
        </div>
    </div>
</div>

<div class="mp-page">
    <div class="container" style="margin-top: 20px;">

        {{-- Alertas flash ──────────────────────────────────────────────────── --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Tabs de estado ─────────────────────────────────────────────────── --}}
        @php
            $estadoActual = request('estado', 'todos');
            $tipoActual   = request('tipo', 'todos');
            $qActual      = request('q', '');
        @endphp
        <div class="estado-tabs">
            @foreach([
                'todos'     => ['label' => 'Todos',    'icon' => 'bi-grid'],
                'activo'    => ['label' => 'Activos',   'icon' => 'bi-circle-fill'],
                'pausado'   => ['label' => 'Pausados',  'icon' => 'bi-pause-circle'],
                'vendido'   => ['label' => 'Vendidos',  'icon' => 'bi-check-circle'],
                'eliminado' => ['label' => 'Eliminados','icon' => 'bi-trash3'],
            ] as $key => $info)
                <a href="{{ route('mis-publicaciones.index', array_merge(request()->query(), ['estado' => $key])) }}"
                   class="estado-tab {{ $estadoActual === $key ? 'activa' : '' }}">
                    <i class="bi {{ $info['icon'] }}" style="font-size:.7rem;"></i>
                    {{ $info['label'] }}
                    <span class="tab-count">{{ $conteos[$key] }}</span>
                </a>
            @endforeach
        </div>

        {{-- Toolbar ────────────────────────────────────────────────────────── --}}
        <form action="{{ route('mis-publicaciones.index') }}" method="GET">
            <input type="hidden" name="estado" value="{{ $estadoActual }}">
            <div class="mp-toolbar">
                <div class="mp-search">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" placeholder="Buscar por título..." value="{{ $qActual }}">
                </div>
                <select name="tipo" class="mp-filter-select" onchange="this.form.submit()">
                    <option value="todos" {{ $tipoActual === 'todos' ? 'selected' : '' }}>Todos los tipos</option>
                    <option value="renta"   {{ $tipoActual === 'renta'   ? 'selected' : '' }}>Renta</option>
                    <option value="venta"   {{ $tipoActual === 'venta'   ? 'selected' : '' }}>Venta</option>
                    <option value="subasta" {{ $tipoActual === 'subasta' ? 'selected' : '' }}>Subasta</option>
                </select>
                <button type="submit" class="btn-accion" title="Filtrar" style="width:auto;padding:0 14px;gap:6px;font-size:.85rem;">
                    <i class="bi bi-funnel"></i>
                </button>
                <a href="{{ route('publicar.create') }}" class="btn-nueva-pub">
                    <i class="bi bi-plus-lg"></i> Nueva publicación
                </a>
            </div>
        </form>

        {{-- Tabla ──────────────────────────────────────────────────────────── --}}
        <div class="mp-card">
            @if($productos->isEmpty())
                <div class="empty-state">
                    <i class="bi bi-box-seam"></i>
                    <h4>No hay publicaciones aquí</h4>
                    <p>Aún no tienes publicaciones con este estado.<br>¡Empieza publicando tu primera herramienta!</p>
                    <a href="{{ route('publicar.create') }}" class="btn-nueva-pub mt-3 d-inline-flex">
                        <i class="bi bi-plus-lg"></i> Publicar ahora
                    </a>
                </div>
            @else
                <table class="mp-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Tipo</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th>Publicado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $prod)
                        <tr>
                            {{-- Producto ──────────────────────────────────── --}}
                            <td data-label="Producto">
                                <div class="prod-cell">
                                    <div class="prod-thumb">
                                        @php $img = $prod->imagenes->first(); @endphp
                                        @if($img)
                                            <img src="{{ asset($img->ruta) }}" alt="{{ $prod->titulo }}">
                                        @else
                                            <i class="bi bi-gear"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="prod-titulo">{{ Str::limit($prod->titulo, 50) }}</div>
                                        <div class="prod-meta">
                                            <i class="bi bi-geo-alt me-1"></i>{{ $prod->ubicacion }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Tipo ─────────────────────────────────────── --}}
                            <td data-label="Tipo">
                                <span class="badge-tipo badge-{{ $prod->tipo }}">
                                    <i class="bi {{ $prod->tipo === 'renta' ? 'bi-clock-history' : ($prod->tipo === 'venta' ? 'bi-bag-check-fill' : 'bi-hammer') }}"></i>
                                    {{ ucfirst($prod->tipo) }}
                                </span>
                            </td>

                            {{-- Precio ───────────────────────────────────── --}}
                            <td data-label="Precio">
                                <div class="precio-cell">
                                    ${{ number_format($prod->precio, 2) }}
                                    @if($prod->unidad) <small>{{ $prod->unidad }}</small> @endif
                                </div>
                            </td>

                            {{-- Estado ───────────────────────────────────── --}}
                            <td data-label="Estado">
                                <span class="badge-estado-row badge-{{ $prod->estado }}">
                                    <i class="bi bi-circle-fill" style="font-size:.45rem;"></i>
                                    {{ ucfirst($prod->estado) }}
                                </span>
                            </td>

                            {{-- Fecha ─────────────────────────────────────── --}}
                            <td data-label="Publicado">
                                <span style="font-size:.82rem; color:var(--mp-muted);">
                                    {{ $prod->created_at->format('d/m/Y') }}
                                </span>
                            </td>

                            {{-- Acciones ─────────────────────────────────── --}}
                            <td data-label="Acciones">
                                <div class="acciones-cell">

                                    {{-- Ver detalle --}}
                                    <a href="{{ route('mis-publicaciones.show', $prod) }}"
                                       class="btn-accion" title="Ver detalle">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    {{-- Editar (no si está eliminado) --}}
                                    @if($prod->estado !== 'eliminado')
                                    <a href="{{ route('mis-publicaciones.edit', $prod) }}"
                                       class="btn-accion" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @endif

                                    {{-- Cambiar estado --}}
                                    @if($prod->estado === 'activo')
                                        <form action="{{ route('mis-publicaciones.estado', $prod) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="estado" value="pausado">
                                            <button type="submit" class="btn-accion warning" title="Pausar publicación">
                                                <i class="bi bi-pause-circle"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('mis-publicaciones.estado', $prod) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="estado" value="vendido">
                                            <button type="submit" class="btn-accion success" title="Marcar como vendido">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                    @elseif($prod->estado === 'pausado')
                                        <form action="{{ route('mis-publicaciones.estado', $prod) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="estado" value="activo">
                                            <button type="submit" class="btn-accion success" title="Reactivar publicación">
                                                <i class="bi bi-play-circle"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Eliminar (no si ya está eliminado) --}}
                                    @if($prod->estado !== 'eliminado')
                                    <button type="button" class="btn-accion danger"
                                            title="Eliminar"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEliminar"
                                            data-id="{{ $prod->id }}"
                                            data-titulo="{{ $prod->titulo }}">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                    @endif

                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Paginación --}}
                @if($productos->hasPages())
                <div class="mp-pagination">
                    {{ $productos->links() }}
                </div>
                @endif
            @endif
        </div>

    </div>
</div>

{{-- Modal confirmación de eliminación ──────────────────────────────────────── --}}
<div class="modal fade modal-confirm" id="modalEliminar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; border:1px solid var(--mp-border); background:var(--mp-surface);">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                    Eliminar publicación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p style="margin:0; color:var(--mp-muted);">
                    ¿Estás seguro de que quieres eliminar
                    <strong id="modal-titulo-pub" style="color:var(--color-text,#0f172a);"></strong>?
                    Esta acción ocultará la publicación del marketplace.
                </p>
            </div>
            <div class="modal-footer" style="gap:8px;">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                        data-bs-dismiss="modal">Cancelar</button>
                <form id="form-eliminar" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">
                        <i class="bi bi-trash3 me-1"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modalEl = document.getElementById('modalEliminar');
    modalEl.addEventListener('show.bs.modal', e => {
        const btn = e.relatedTarget;
        document.getElementById('modal-titulo-pub').textContent = '"' + btn.dataset.titulo + '"';
        document.getElementById('form-eliminar').action =
            '/mis-publicaciones/' + btn.dataset.id;
    });
});
</script>
@endpush