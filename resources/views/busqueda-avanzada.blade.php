@extends('layouts.app')

@section('titulo_pagina', 'Búsqueda Avanzada - Tools365')

@push('css')
<style>
    /* ===== HERO ===== */
    .search-hero {
        background: linear-gradient(135deg, #1F3A93 0%, #2C3E50 100%);
        padding: 2.5rem 0 2rem;
        color: #fff;
    }
    .search-hero h1 { font-size: 1.6rem; font-weight: 900; margin-bottom: 0.25rem; }
    .search-hero p  { color: rgba(255,255,255,0.65); font-size: 0.92rem; margin: 0; }

    /* ===== LAYOUT DOS COLUMNAS ===== */
    .avanzada-layout {
        display: flex;
        gap: 1.5rem;
        align-items: flex-start;
        padding: 2rem 0;
    }

    /* ===== PANEL FILTROS (izquierda) ===== */
    .filtros-panel {
        width: 280px;
        flex-shrink: 0;
        background: #fff;
        border-radius: 14px;
        border: 1px solid #eee;
        box-shadow: 0 4px 18px rgba(0,0,0,0.07);
        position: sticky;
        top: 148px;
        max-height: calc(100vh - 168px);
        overflow-y: auto;
    }
    .filtros-header {
        background: linear-gradient(135deg, #1F3A93, #2C3E50);
        color: #fff;
        padding: 1rem 1.25rem;
        font-weight: 900;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .filtros-body { padding: 1.25rem; }
    .filtro-grupo { margin-bottom: 1.25rem; }
    .filtro-label {
        font-size: 0.78rem; font-weight: 800; color: #1F3A93;
        text-transform: uppercase; letter-spacing: 0.5px;
        margin-bottom: 0.4rem; display: block;
    }
    .filtro-select, .filtro-input {
        width: 100%; border: 1.5px solid #ddd; border-radius: 8px;
        padding: 8px 12px; font-size: 0.88rem; color: #333;
        outline: none; transition: border-color 0.2s; background: #fafafa;
        font-family: 'Nunito', sans-serif;
    }
    .filtro-select:focus, .filtro-input:focus { border-color: #1F3A93; background: #fff; }
    .filtro-precio-row { display: flex; gap: 8px; }
    .filtro-precio-row .filtro-input { width: 50%; }

    .btn-filtrar {
        width: 100%; background: var(--color-accent); color: #fff;
        border: none; border-radius: 8px; padding: 11px; font-weight: 800;
        font-size: 0.95rem; cursor: pointer; transition: background 0.2s;
        display: flex; align-items: center; justify-content: center; gap: 6px;
    }
    .btn-filtrar:hover { background: var(--color-accent-hover); }
    .btn-limpiar {
        width: 100%; background: transparent; color: #888;
        border: 1.5px solid #ddd; border-radius: 8px; padding: 8px;
        font-weight: 700; font-size: 0.82rem; cursor: pointer;
        transition: all 0.2s; margin-top: 8px;
        font-family: 'Nunito', sans-serif; text-align: center; display: block;
        text-decoration: none;
    }
    .btn-limpiar:hover { border-color: #e74c3c; color: #e74c3c; background: #fdecea; }

    /* ===== COLUMNA RESULTADOS ===== */
    .resultados-col { flex: 1; min-width: 0; }

    /* Encabezado de sección de resultados por default */
    .default-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 1.2rem; padding-bottom: 0.6rem;
        border-bottom: 3px solid var(--color-accent);
    }
    .default-header h3 {
        font-size: 1.1rem; font-weight: 900; margin: 0;
        display: flex; align-items: center; gap: 6px;
    }

    /* Empty state */
    .empty-state { text-align: center; padding: 4rem 1rem; background: #fff; border-radius: 14px; border: 1px solid #eee; }
    .empty-state .empty-icon { font-size: 4rem; color: #ddd; margin-bottom: 1rem; }
    .empty-state h3 { font-weight: 800; color: #555; margin-bottom: 0.5rem; }
    .empty-state p  { color: #aaa; font-size: 0.92rem; }

    /* Cards */
    .product-card { border: 1px solid #eee; border-radius: 10px; overflow: hidden; transition: all 0.25s ease; background: #fff; height: 100%; }
    .product-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); border-color: #ddd; }
    .product-card-img { height: 160px; object-fit: cover; width: 100%; display: block; }
    .product-card-img-placeholder { height: 160px; display: flex; align-items: center; justify-content: center; font-size: 3rem; }
    .product-card-body { padding: 12px 14px; }
    .product-card-title { font-size: 0.88rem; font-weight: 700; color: #222; margin-bottom: 4px; line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .product-card-price { font-size: 1.1rem; font-weight: 900; color: var(--color-primary); }
    .product-card-price small { font-size: 0.75rem; font-weight: 600; color: #888; }
    .product-card-location { font-size: 0.75rem; color: #999; margin-top: 5px; }
    .badge-tipo { font-size: 0.72rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; display: inline-block; margin-bottom: 5px; }
    .badge-renta   { background: #e8f0fe; color: #1F3A93; }
    .badge-venta   { background: #e6f9f0; color: #27ae60; }
    .badge-subasta { background: #fdecea; color: #e74c3c; }
    .auction-timer { background: #fff3e0; border: 1px solid #ffe0b2; border-radius: 6px; padding: 3px 10px; font-size: 0.76rem; font-weight: 700; color: #e65100; display: inline-flex; align-items: center; gap: 4px; margin-bottom: 5px; }

    /* Results meta */
    .results-meta { font-size: 0.87rem; color: #888; font-weight: 600; margin-bottom: 1rem; }
    .results-meta strong { color: #222; }

    /* Filtros activos chips */
    .filtros-activos { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 1rem; }
    .chip-filtro { background: #e8f0fe; color: #1F3A93; border-radius: 20px; padding: 4px 12px; font-size: 0.78rem; font-weight: 700; display: inline-flex; align-items: center; gap: 5px; }

    mark { background: #fff3cd; padding: 0 2px; border-radius: 3px; font-weight: 700; }

    @media (max-width: 767px) {
        .avanzada-layout { flex-direction: column; }
        .filtros-panel { width: 100%; position: static; }
    }
</style>
@endpush

@section('contenido')

{{-- ===== HERO ===== --}}
<section class="search-hero">
    <div class="container">
        <h1><i class="bi bi-sliders me-2"></i>Búsqueda Avanzada</h1>
        <p>Filtra por categoría, tipo, precio y más para encontrar exactamente lo que necesitas</p>
    </div>
</section>

{{-- ===== LAYOUT: FILTROS + RESULTADOS ===== --}}
<div class="container">
    <div class="avanzada-layout">

        {{-- ==============================
             PANEL DE FILTROS (izquierda)
        ============================== --}}
        <aside class="filtros-panel">
            <div class="filtros-header">
                <i class="bi bi-funnel-fill"></i> Filtros de búsqueda
            </div>
            <div class="filtros-body">
                <form action="{{ route('busqueda.avanzada') }}" method="GET" id="formFiltros">

                    {{-- Texto libre --}}
                    <div class="filtro-grupo">
                        <label class="filtro-label"><i class="bi bi-search me-1"></i>Palabra clave</label>
                        <input type="text" name="q" class="filtro-input"
                               placeholder="Ej: excavadora, taladro..."
                               value="{{ $termino }}">
                    </div>

                    {{-- Categoría (desde BD) --}}
                    <div class="filtro-grupo">
                        <label class="filtro-label"><i class="bi bi-grid me-1"></i>Categoría</label>
                        <select name="categoria" class="filtro-select">
                            <option value="">-- Todas las categorías --</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->slug }}"
                                    {{ $categoriaSlug === $cat->slug ? 'selected' : '' }}>
                                    {{ $cat->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tipo de publicación --}}
                    <div class="filtro-grupo">
                        <label class="filtro-label"><i class="bi bi-tag me-1"></i>Tipo</label>
                        <select name="tipo" class="filtro-select">
                            <option value="">-- Todos los tipos --</option>
                            <option value="renta"   {{ $tipo === 'renta'   ? 'selected' : '' }}>🕐 Renta</option>
                            <option value="venta"   {{ $tipo === 'venta'   ? 'selected' : '' }}>🛍️ Venta</option>
                            <option value="subasta" {{ $tipo === 'subasta' ? 'selected' : '' }}>🔨 Subasta</option>
                        </select>
                    </div>

                    {{-- Rango de precio --}}
                    <div class="filtro-grupo">
                        <label class="filtro-label"><i class="bi bi-currency-dollar me-1"></i>Rango de precio (MXN)</label>
                        <div class="filtro-precio-row">
                            <input type="number" name="precio_min" class="filtro-input"
                                   placeholder="Mínimo" min="0" value="{{ $precioMin }}">
                            <input type="number" name="precio_max" class="filtro-input"
                                   placeholder="Máximo" min="0" value="{{ $precioMax }}">
                        </div>
                    </div>

                    <button type="submit" class="btn-filtrar">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                    <a href="{{ route('busqueda.avanzada') }}" class="btn-limpiar">
                        <i class="bi bi-x-circle me-1"></i> Limpiar filtros
                    </a>

                </form>
            </div>
        </aside>

        {{-- ==============================
             COLUMNA RESULTADOS (derecha)
        ============================== --}}
        <section class="resultados-col">

            @if(!$buscando)
                {{-- ── DEFAULT: últimos productos registrados ── --}}
                <div class="default-header">
                    <h3>
                        <i class="bi bi-clock-history text-accent"></i>
                        Últimos registros
                    </h3>
                    <span class="text-muted" style="font-size:0.82rem; font-weight:600;">
                        {{ $productos->count() }} productos recientes
                    </span>
                </div>

                @if($productos->count() > 0)
                    <div class="row g-3">
                        @foreach($productos as $producto)
                            @include('partials.producto-card-avanzada', ['producto' => $producto])
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon"><i class="bi bi-box-seam"></i></div>
                        <h3>Sin productos registrados</h3>
                        <p>Aún no hay productos en la plataforma.</p>
                    </div>
                @endif

            @elseif($productos && $productos->count() > 0)
                {{-- ── RESULTADOS CON FILTROS ── --}}

                {{-- Chips de filtros activos --}}
                <div class="filtros-activos">
                    @if($termino)
                        <span class="chip-filtro"><i class="bi bi-search"></i> "{{ $termino }}"</span>
                    @endif
                    @if($categoriaSlug)
                        @php $catNombre = $categorias->firstWhere('slug', $categoriaSlug)?->nombre ?? $categoriaSlug; @endphp
                        <span class="chip-filtro"><i class="bi bi-grid"></i> {{ $catNombre }}</span>
                    @endif
                    @if($tipo)
                        <span class="chip-filtro"><i class="bi bi-tag"></i> {{ ucfirst($tipo) }}</span>
                    @endif
                    @if($precioMin || $precioMax)
                        <span class="chip-filtro">
                            <i class="bi bi-currency-dollar"></i>
                            ${{ $precioMin ?: '0' }} – ${{ $precioMax ?: '∞' }}
                        </span>
                    @endif
                </div>

                <div class="results-meta">
                    Mostrando <strong>{{ $productos->firstItem() }}–{{ $productos->lastItem() }}</strong>
                    de <strong>{{ $productos->total() }}</strong> resultados
                </div>

                <div class="row g-3">
                    @foreach($productos as $producto)
                        @include('partials.producto-card-avanzada', ['producto' => $producto])
                    @endforeach
                </div>

                @if($productos->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $productos->links() }}
                    </div>
                @endif

            @else
                {{-- ── SIN RESULTADOS ── --}}
                <div class="empty-state">
                    <div class="empty-icon"><i class="bi bi-search"></i></div>
                    <h3>Sin resultados</h3>
                    <p>No encontramos productos con los filtros seleccionados.<br>Intenta con otros parámetros.</p>
                    <a href="{{ route('busqueda.avanzada') }}" class="btn btn-outline-primary btn-sm mt-2">
                        <i class="bi bi-x-circle me-1"></i> Limpiar filtros
                    </a>
                </div>
            @endif

        </section>

    </div>
</div>

@endsection