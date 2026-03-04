@extends('layouts.app')

@section('titulo_pagina', 'Búsqueda' . ($termino ? ': ' . $termino : '') . ' - Tools365')

@push('css')
<style>
    .search-hero {
        background: linear-gradient(135deg, #1F3A93 0%, #2C3E50 100%);
        padding: 2.5rem 0 2rem;
        color: #fff;
    }
    .search-hero h1 { font-size: 1.6rem; font-weight: 900; margin-bottom: 0.25rem; }
    .search-hero p  { color: rgba(255,255,255,0.65); font-size: 0.92rem; margin: 0; }

    /* Barra de búsqueda grande dentro de la página */
    .search-bar-page { display: flex; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.25); max-width: 680px; height: 50px; margin-top: 1.5rem; }
    .search-bar-page input  { flex: 1; border: none; padding: 0 18px; font-size: 1rem; outline: none; }
    .search-bar-page button { background: var(--color-accent); border: none; padding: 0 28px; color: #fff; font-size: 1.1rem; cursor: pointer; transition: background .2s; }
    .search-bar-page button:hover { background: var(--color-accent-hover); }

    /* Filtros */
    .filters-bar { background: #fff; border-bottom: 1px solid #eee; padding: 12px 0; position: sticky; top: 128px; z-index: 100; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
    .filter-btn { border: 1.5px solid #ddd; background: #fff; border-radius: 20px; padding: 5px 16px; font-size: 0.82rem; font-weight: 700; color: #555; cursor: pointer; transition: all .2s; white-space: nowrap; }
    .filter-btn:hover, .filter-btn.active { background: var(--color-primary); border-color: var(--color-primary); color: #fff; }
    .filter-btn.active-renta   { background: #1F3A93; border-color: #1F3A93; color: #fff; }
    .filter-btn.active-venta   { background: #27ae60; border-color: #27ae60; color: #fff; }
    .filter-btn.active-subasta { background: #e74c3c; border-color: #e74c3c; color: #fff; }

    /* Resultados count */
    .results-meta { font-size: 0.87rem; color: #888; font-weight: 600; }
    .results-meta strong { color: #222; }

    /* Cards */
    .product-card { border: 1px solid #eee; border-radius: 10px; overflow: hidden; transition: all 0.25s ease; background: #fff; height: 100%; }
    .product-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); border-color: #ddd; }
    .product-card-img { height: 180px; object-fit: cover; width: 100%; display: block; }
    .product-card-img-placeholder { height: 180px; display: flex; align-items: center; justify-content: center; font-size: 3.5rem; }
    .product-card-body { padding: 14px 16px; }
    .product-card-title { font-size: 0.9rem; font-weight: 700; color: #222; margin-bottom: 6px; line-height: 1.35; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .product-card-price { font-size: 1.2rem; font-weight: 900; color: var(--color-primary); }
    .product-card-price small { font-size: 0.75rem; font-weight: 600; color: #888; }
    .product-card-location { font-size: 0.75rem; color: #999; margin-top: 6px; }
    .badge-tipo { font-size: 0.72rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; display: inline-block; margin-bottom: 6px; }
    .badge-renta   { background: #e8f0fe; color: #1F3A93; }
    .badge-venta   { background: #e6f9f0; color: #27ae60; }
    .badge-subasta { background: #fdecea; color: #e74c3c; }
    .auction-timer { background: #fff3e0; border: 1px solid #ffe0b2; border-radius: 6px; padding: 3px 10px; font-size: 0.76rem; font-weight: 700; color: #e65100; display: inline-flex; align-items: center; gap: 4px; margin-bottom: 6px; }

    /* Empty state */
    .empty-state { text-align: center; padding: 5rem 1rem; }
    .empty-state .empty-icon { font-size: 4rem; color: #ddd; margin-bottom: 1rem; }
    .empty-state h3 { font-weight: 800; color: #555; margin-bottom: 0.5rem; }
    .empty-state p  { color: #aaa; font-size: 0.92rem; }

    /* Highlight término buscado */
    mark { background: #fff3cd; padding: 0 2px; border-radius: 3px; font-weight: 700; }
</style>
@endpush

@section('contenido')

{{-- ===== HERO DE BÚSQUEDA ===== --}}
<section class="search-hero">
    <div class="container">
        @if($termino)
            <h1><i class="bi bi-search me-2"></i>Resultados para: <span class="text-accent">{{ $termino }}</span></h1>
            <p>Encontramos <strong style="color:#fff">{{ $productos->total() }}</strong> {{ $productos->total() === 1 ? 'resultado' : 'resultados' }} en toda la plataforma</p>
        @else
            <h1><i class="bi bi-grid me-2"></i>Todos los productos</h1>
            <p>Explora nuestra selección completa de herramientas y maquinaria</p>
        @endif
    </div>
</section>

{{-- ===== FILTROS ===== --}}
<div class="filters-bar">
    <div class="container">
        <div class="d-flex align-items-center gap-2 overflow-auto pb-1">
            <span class="text-muted fw-700 me-1" style="font-size:.82rem;white-space:nowrap;">Filtrar por:</span>

            <a href="{{ route('buscar', ['q' => $termino]) }}"
               class="filter-btn {{ !$tipo ? 'active' : '' }}">
                <i class="bi bi-grid me-1"></i>Todos
            </a>
            <a href="{{ route('buscar', ['q' => $termino, 'tipo' => 'renta']) }}"
               class="filter-btn {{ $tipo === 'renta' ? 'active-renta' : '' }}">
                <i class="bi bi-clock-history me-1"></i>Renta
            </a>
            <a href="{{ route('buscar', ['q' => $termino, 'tipo' => 'venta']) }}"
               class="filter-btn {{ $tipo === 'venta' ? 'active-venta' : '' }}">
                <i class="bi bi-bag-check me-1"></i>Compra
            </a>
            <a href="{{ route('buscar', ['q' => $termino, 'tipo' => 'subasta']) }}"
               class="filter-btn {{ $tipo === 'subasta' ? 'active-subasta' : '' }}">
                <i class="bi bi-hammer me-1"></i>Subasta
            </a>
        </div>
    </div>
</div>

{{-- ===== RESULTADOS ===== --}}
<section class="py-4">
    <div class="container">

        @if($productos->count() > 0)

            <div class="results-meta mb-3">
                Mostrando <strong>{{ $productos->firstItem() }}–{{ $productos->lastItem() }}</strong>
                de <strong>{{ $productos->total() }}</strong> resultados
                @if($termino)
                    para <strong>"{{ $termino }}"</strong>
                @endif
                @if($tipo)
                    &nbsp;·&nbsp; Tipo: <strong>{{ ucfirst($tipo) }}</strong>
                @endif
            </div>

            <div class="row g-3">
                @foreach($productos as $producto)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="product-card">

                            {{-- Imagen --}}
                            @if($producto->imagen)
                                <img src="{{ asset($producto->imagen) }}"
                                     alt="{{ $producto->titulo }}"
                                     class="product-card-img">
                            @else
                                <div class="product-card-img-placeholder"
                                     style="background: linear-gradient(135deg,#e8eaf6,#c5cae9); color:#9fa8da;">
                                    <i class="bi bi-tools"></i>
                                </div>
                            @endif

                            <div class="product-card-body">

                                {{-- Timer subasta --}}
                                @if($producto->tipo === 'subasta' && $producto->timer_fin)
                                    <div class="auction-timer">
                                        <i class="bi bi-clock"></i>
                                        {{ \Carbon\Carbon::parse($producto->timer_fin)->locale('es')->diffForHumans(['parts' => 2, 'short' => true]) }}

                                    </div>
                                @endif

                                {{-- Badge tipo --}}
                                <span class="badge-tipo badge-{{ $producto->tipo }}">
                                    @if($producto->tipo === 'renta') <i class="bi bi-clock-history me-1"></i>Renta
                                    @elseif($producto->tipo === 'venta') <i class="bi bi-bag-check me-1"></i>Venta
                                    @else <i class="bi bi-hammer me-1"></i>Subasta
                                    @endif
                                </span>

                                {{-- Título con highlight --}}
                                <div class="product-card-title">
                                    @if($termino)
                                        {!! preg_replace('/(' . preg_quote($termino, '/') . ')/iu', '<mark>$1</mark>', e($producto->titulo)) !!}
                                    @else
                                        {{ $producto->titulo }}
                                    @endif
                                </div>

                                {{-- Precio --}}
                                <div class="product-card-price">
                                    ${{ number_format($producto->precio, 0, '.', ',') }}
                                    @if($producto->unidad)
                                        <small>{{ $producto->unidad }}</small>
                                    @endif
                                </div>

                                {{-- Ubicación --}}
                                @if($producto->ubicacion)
                                    <div class="product-card-location">
                                        <i class="bi bi-geo-alt"></i> {{ $producto->ubicacion }}
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Paginación --}}
            @if($productos->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $productos->links() }}
                </div>
            @endif

        @else
            {{-- Estado vacío --}}
            <div class="empty-state">
                <div class="empty-icon"><i class="bi bi-search"></i></div>
                <h3>No encontramos resultados</h3>
                <p>
                    @if($termino)
                        No hay productos que coincidan con <strong>"{{ $termino }}"</strong>.
                    @else
                        No hay productos disponibles en este momento.
                    @endif
                </p>
                <div class="d-flex gap-2 justify-content-center mt-3 flex-wrap">
                    <a href="{{ route('buscar') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-grid me-1"></i>Ver todos los productos
                    </a>
                    <a href="{{ route('inicio') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-house me-1"></i>Volver al inicio
                    </a>
                </div>
            </div>
        @endif

    </div>
</section>

@endsection