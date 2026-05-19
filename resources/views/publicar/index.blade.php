@extends('layouts.app')

@section('titulo_pagina', 'Mis publicaciones — Tools365')

@push('css')
<link rel="stylesheet" href="{{ asset('css/index_publicar.css') }}">
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