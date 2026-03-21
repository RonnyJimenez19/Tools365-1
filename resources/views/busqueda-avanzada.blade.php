@extends('layouts.app')

@section('titulo_pagina', 'Búsqueda Avanzada - Tools365')

@push('css')
<link rel="stylesheet" href="{{ asset('css/busqueda-avanzada.css') }}">
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
                    <h3 style="color: black;">
                        <i class="bi bi-search me-1" style="color: black;"></i>
                        Últimos registros
                    </h3>
                    <span class="text-muted" style="font-size:0.82rem; font-weight:600;">
                        {{ $productos->count() }} productos recientes
                    </span>
                </div>

                @if($productos->count() > 0)
                    <div class="row g-3">
                        @foreach($productos as $producto)
                            <x-product-card :producto="$producto" />
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
                        <x-product-card :producto="$producto" />
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