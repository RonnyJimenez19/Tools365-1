@extends('layouts.app')

@section('titulo_pagina', 'Búsqueda' . ($termino ? ': ' . $termino : '') . ' - Tools365')

@push('css')
<link rel="stylesheet" href="{{ asset('css/busqueda.css') }}">
@endpush

@section('contenido')

{{-- ===== HERO DE BÚSQUEDA ===== --}}
<section class="search-hero">
    <div class="container">
        @if($termino)
            <h1><i class="bi bi-search me-2"></i>Resultados para: <span class="text-accent">{{ $termino }}</span></h1>
            @if($buscando && $productos)
                <p>Encontramos <strong style="color:#fff">{{ $productos->total() }}</strong> {{ $productos->total() === 1 ? 'resultado' : 'resultados' }} en toda la plataforma</p>
            @endif
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
            <span class="text-muted fw-bold me-1" style="font-size:.82rem;white-space:nowrap;">Filtrar por:</span>
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
 
        @if(!$buscando)
            {{-- Sin búsqueda activa --}}
            <div class="empty-state">
                <div class="empty-icon"><i class="bi bi-search"></i></div>
                <h3>Escribe algo para buscar</h3>
                <p>Usa la barra de búsqueda o los filtros de arriba para encontrar herramientas y maquinaria.</p>
                <a href="{{ route('busqueda.avanzada') }}" class="btn btn-primary btn-sm mt-2">
                    <i class="bi bi-sliders me-1"></i>Búsqueda avanzada
                </a>
            </div>
 
        @elseif($productos && $productos->count() > 0)
            {{-- Con resultados --}}
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
                    <x-product-card :producto="$producto" :termino="$termino" />
                @endforeach
            </div>
 
            {{-- Paginación --}}
            @if($productos->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $productos->links() }}
                </div>
            @endif
 
        @else
            {{-- Sin resultados --}}
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