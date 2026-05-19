@extends('layouts.app')

@section('titulo_pagina', 'Ofertas – Tools365')

@push('css')
<link rel="stylesheet" href="{{ asset('css/ofertas.css') }}">
@endpush

@section('contenido')

{{-- Hero ─────────────────────────────────────────────────────── --}}
<div class="ofertas-hero">
    <div class="container">
        <div class="ofertas-hero-content">
            <div class="hero-eyebrow">
                <i class="bi bi-fire"></i> Ofertas activas
            </div>
            <h1>Los mejores precios<br><span>del mercado hoy</span></h1>
            <p>Descuentos reales en maquinaria, herramientas y equipo industrial. Actualizado constantemente.</p>
            <div class="offers-meta">
                <span class="meta-chip"><i class="bi bi-tag-fill"></i> {{ $ofertas->total() }} productos en oferta</span>
                <span class="meta-chip"><i class="bi bi-lightning-fill"></i> Hasta 70% de descuento</span>
                <span class="meta-chip"><i class="bi bi-clock-fill"></i> Ofertas por tiempo limitado</span>
            </div>
        </div>
    </div>
</div>

<div class="ofertas-page">
    <div class="container" style="margin-top: 20px;">
        <div class="row g-4">

            {{-- ── Sidebar de filtros ──────────────────────── --}}
            <div class="col-lg-3">
                <form method="GET" action="{{ route('ofertas.index') }}" id="form-filtros">
                    <div class="filtro-card">
                        <div class="filtro-title">
                            <i class="bi bi-funnel-fill"></i> Filtrar ofertas
                        </div>

                        {{-- Tipo --}}
                        <div class="filtro-label">Tipo</div>
                        @foreach(['renta' => 'Renta', 'venta' => 'Venta', 'subasta' => 'Subasta'] as $val => $lab)
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="radio" name="tipo"
                                       id="tipo-{{ $val }}" value="{{ $val }}"
                                       {{ request('tipo') === $val ? 'checked' : '' }}>
                                <label class="form-check-label" for="tipo-{{ $val }}">{{ $lab }}</label>
                            </div>
                        @endforeach

                        {{-- Categoría --}}
                        <div class="filtro-label">Categoría</div>
                        <select name="categoria" class="form-select form-select-sm">
                            <option value="">Todas</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->slug }}" {{ request('categoria') === $cat->slug ? 'selected' : '' }}>
                                    {{ $cat->nombre }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Descuento mínimo --}}
                        <div class="filtro-label">Descuento mínimo</div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <input type="range" name="descuento_min" class="form-range descuento-range flex-1"
                                   min="0" max="70" step="5"
                                   value="{{ request('descuento_min', 0) }}"
                                   id="range-descuento"
                                   style="flex:1;">
                            <span class="range-value" id="range-label">{{ request('descuento_min', 0) }}%</span>
                        </div>

                        <button type="submit" class="btn-filtrar">
                            <i class="bi bi-funnel me-1"></i> Aplicar filtros
                        </button>
                        <a href="{{ route('ofertas.index') }}" class="btn-limpiar d-block text-center text-decoration-none">
                            Limpiar filtros
                        </a>
                    </div>
                </form>
            </div>

            {{-- ── Grid de productos ──────────────────────── --}}
            <div class="col-lg-9">

                <div class="sort-bar">
                    <div class="sort-bar-left">
                        Mostrando <strong>{{ $ofertas->firstItem() }}–{{ $ofertas->lastItem() }}</strong>
                        de <strong>{{ $ofertas->total() }}</strong> ofertas
                        @if(request('tipo') || request('categoria'))
                            <span class="badge bg-danger ms-2">Filtros activos</span>
                        @endif
                    </div>
                    <div>
                        <span style="font-size:.8rem; color:#64748b;">Ordenado por mayor descuento</span>
                    </div>
                </div>

                @if($ofertas->isEmpty())
                    <div class="empty-offers">
                        <i class="bi bi-tag-fill"></i>
                        <h5>No hay ofertas activas con estos filtros</h5>
                        <p>Intenta con otros criterios o revisa más tarde.</p>
                        <a href="{{ route('ofertas.index') }}" class="btn btn-outline-danger rounded-pill px-4 mt-2">
                            Ver todas las ofertas
                        </a>
                    </div>
                @else
                    <div class="oferta-grid">
                        @foreach($ofertas as $producto)
                            <div class="oferta-card">

                                {{-- Badge descuento --}}
                                @if($producto->descuento_porcentaje)
                                    <div class="badge-descuento">{{ $producto->descuento_porcentaje }}%</div>
                                @endif

                                {{-- Badge etiqueta libre --}}
                                @if($producto->oferta_etiqueta)
                                    <div class="badge-etiqueta">{{ $producto->oferta_etiqueta }}</div>
                                @endif

                                {{-- Imagen --}}
                                @if($producto->imagenPrincipal)
                                    <img src="{{ asset($producto->imagenPrincipal->ruta) }}"
                                         alt="{{ $producto->titulo }}"
                                         class="oferta-img"
                                         loading="lazy">
                                @else
                                    <div class="oferta-img-placeholder">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif

                                {{-- Expiración --}}
                                @if($producto->oferta_fin)
                                    <div class="oferta-expiry">
                                        <i class="bi bi-clock-fill"></i>
                                        Oferta termina: {{ $producto->oferta_fin->format('d M, H:i') }}
                                    </div>
                                @endif

                                <div class="oferta-body">
                                    <div class="oferta-categoria">
                                        {{ $producto->categoria?->nombre ?? '—' }}
                                        &nbsp;·&nbsp;
                                        {{ ucfirst($producto->tipo) }}
                                    </div>
                                    <div class="oferta-titulo">
                                        <a href="#">{{ $producto->titulo }}</a>
                                    </div>

                                    {{-- Precios --}}
                                    <div class="precio-bloque">
                                        <span class="precio-actual">
                                            ${{ number_format($producto->precio, 0, '.', ',') }}
                                        </span>
                                        @if($producto->precio_original)
                                            <span class="precio-anterior">
                                                ${{ number_format($producto->precio_original, 0, '.', ',') }}
                                            </span>
                                        @endif
                                        @if($producto->unidad)
                                            <span class="precio-unidad">{{ $producto->unidad }}</span>
                                        @endif
                                    </div>

                                    {{-- Ahorro --}}
                                    @if($producto->precio_original)
                                        @php $ahorro = $producto->precio_original - $producto->precio; @endphp
                                        <div class="ahorro-chip mb-2">
                                            <i class="bi bi-piggy-bank-fill"></i>
                                            Ahorras ${{ number_format($ahorro, 0, '.', ',') }} MXN
                                        </div>
                                    @endif
                                </div>

                                <div class="oferta-footer">
                                    <div class="oferta-ubicacion">
                                        @if($producto->ubicacion)
                                            <i class="bi bi-geo-alt"></i>
                                            {{ Str::limit($producto->ubicacion, 22) }}
                                        @endif
                                    </div>
                                    <a href="#" class="btn-ver-oferta">Ver oferta</a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Paginación --}}
                    <div class="d-flex justify-content-center mt-5">
                        <nav class="ofertas-pagination">
                            {{ $ofertas->links() }}
                        </nav>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Actualizar label del range de descuento
    const range = document.getElementById('range-descuento');
    const label = document.getElementById('range-label');
    if (range) {
        range.addEventListener('input', () => label.textContent = range.value + '%');
    }
</script>
@endpush