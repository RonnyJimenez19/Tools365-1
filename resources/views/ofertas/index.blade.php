@extends('layouts.app')

@section('titulo_pagina', 'Ofertas – Tools365')

@push('css')
<style>
/* ── Layout ─────────────────────────────────────── */
.ofertas-page {
    padding-top: 160px;
    padding-bottom: 80px;
    min-height: 100vh;
}

/* ── Hero ───────────────────────────────────────── */
.ofertas-hero {
    background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 40%, #dc2626 100%);
    padding: 56px 0 80px;
    margin-top: -160px;
    padding-top: 200px;
    margin-bottom: -40px;
    position: relative;
    overflow: hidden;
}
.ofertas-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at 80% 50%, rgba(251,191,36,.15) 0%, transparent 60%);
}
.ofertas-hero::after {
    content: '🔥';
    position: absolute;
    right: 8%;
    top: 50%;
    transform: translateY(-50%);
    font-size: clamp(5rem, 12vw, 9rem);
    opacity: .12;
    pointer-events: none;
    filter: grayscale(1) brightness(2);
}
.ofertas-hero-content { position: relative; z-index: 1; }
.hero-eyebrow {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(251,191,36,.18); border: 1px solid rgba(251,191,36,.35);
    color: #fbbf24; padding: 5px 14px; border-radius: 100px;
    font-size: .76rem; font-weight: 700; letter-spacing: .06em;
    text-transform: uppercase; margin-bottom: 16px;
    animation: pulse-badge 2s infinite;
}
@keyframes pulse-badge {
    0%, 100% { box-shadow: 0 0 0 0 rgba(251,191,36,.3); }
    50%       { box-shadow: 0 0 0 8px rgba(251,191,36,0); }
}
.ofertas-hero h1 {
    color: #fff; font-size: clamp(1.8rem, 5vw, 3rem);
    font-weight: 900; line-height: 1.1; margin-bottom: 10px;
}
.ofertas-hero h1 span { color: #fbbf24; }
.ofertas-hero p { color: rgba(255,255,255,.7); max-width: 480px; }

/* ── Counter chips ──────────────────────────────── */
.offers-meta {
    display: flex; align-items: center; gap: 12px;
    flex-wrap: wrap; margin-top: 20px;
}
.meta-chip {
    background: rgba(255,255,255,.1); backdrop-filter: blur(6px);
    border: 1px solid rgba(255,255,255,.15);
    color: #fff; padding: 6px 14px; border-radius: 100px;
    font-size: .78rem; font-weight: 600;
    display: inline-flex; align-items: center; gap: 6px;
}
.meta-chip i { color: #fbbf24; }

/* ── Filtro sidebar ─────────────────────────────── */
.filtro-card {
    background: var(--color-surface, #fff);
    border: 1px solid var(--color-border, #e2e8f0);
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 2px 16px rgba(0,0,0,.06);
    position: sticky; top: 100px;
}
.filtro-title {
    font-weight: 700; font-size: .9rem;
    margin-bottom: 16px; display: flex; align-items: center; gap: 8px;
}
.filtro-title i { color: #dc2626; }
.filtro-label {
    font-size: .78rem; font-weight: 600;
    color: var(--color-muted, #64748b);
    text-transform: uppercase; letter-spacing: .06em;
    margin-bottom: 6px; margin-top: 16px;
}
.form-check-label { font-size: .84rem; }
.descuento-range {
    accent-color: #dc2626;
}
.range-value {
    background: #dc2626; color: #fff;
    padding: 2px 10px; border-radius: 100px;
    font-size: .75rem; font-weight: 700;
}
.btn-filtrar {
    background: #dc2626; color: #fff; border: none;
    padding: 10px; border-radius: 10px; width: 100%;
    font-weight: 700; font-size: .85rem; cursor: pointer;
    margin-top: 18px; transition: all .2s;
}
.btn-filtrar:hover { background: #b91c1c; }
.btn-limpiar {
    background: transparent; color: #64748b; border: 1px solid #e2e8f0;
    padding: 8px; border-radius: 10px; width: 100%;
    font-size: .82rem; cursor: pointer; margin-top: 6px;
    transition: all .2s;
}
.btn-limpiar:hover { border-color: #dc2626; color: #dc2626; }

/* ── Product grid ───────────────────────────────── */
.oferta-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 18px;
}

/* ── Oferta card ────────────────────────────────── */
.oferta-card {
    background: var(--color-surface, #fff);
    border: 1px solid var(--color-border, #e2e8f0);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,.05);
    transition: transform .2s, box-shadow .2s;
    position: relative;
}
.oferta-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 28px rgba(0,0,0,.1);
}

/* Badge de descuento */
.badge-descuento {
    position: absolute; top: 12px; left: 12px; z-index: 2;
    background: linear-gradient(135deg, #dc2626, #ef4444);
    color: #fff; font-weight: 900; font-size: .85rem;
    padding: 5px 12px; border-radius: 100px;
    box-shadow: 0 3px 10px rgba(220,38,38,.4);
    letter-spacing: -.01em;
}
.badge-descuento::before { content: '−'; margin-right: 1px; }

/* Badge etiqueta */
.badge-etiqueta {
    position: absolute; top: 12px; right: 12px; z-index: 2;
    background: rgba(251,191,36,.9); backdrop-filter: blur(4px);
    color: #78350f; font-weight: 700; font-size: .7rem;
    padding: 3px 10px; border-radius: 100px;
    text-transform: uppercase; letter-spacing: .04em;
}

/* Imagen */
.oferta-img {
    width: 100%; aspect-ratio: 4/3; object-fit: cover;
    background: #f1f5f9;
    display: block;
}
.oferta-img-placeholder {
    width: 100%; aspect-ratio: 4/3;
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    display: flex; align-items: center; justify-content: center;
    color: #cbd5e1; font-size: 2.5rem;
}

/* Fin oferta countdown */
.oferta-expiry {
    background: #fff7ed;
    border-top: 1px solid #fed7aa;
    padding: 6px 14px;
    font-size: .72rem; color: #c2410c; font-weight: 600;
    display: flex; align-items: center; gap: 5px;
}

/* Body */
.oferta-body { padding: 14px 16px 16px; }
.oferta-categoria {
    font-size: .7rem; color: #64748b; font-weight: 600;
    text-transform: uppercase; letter-spacing: .06em;
    margin-bottom: 4px;
}
.oferta-titulo {
    font-weight: 700; font-size: .92rem; line-height: 1.3;
    margin-bottom: 10px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.oferta-titulo a { color: inherit; text-decoration: none; }
.oferta-titulo a:hover { color: #dc2626; }

/* Precios */
.precio-bloque { display: flex; align-items: baseline; gap: 8px; margin-bottom: 10px; }
.precio-actual {
    font-size: 1.3rem; font-weight: 900; color: #dc2626; line-height: 1;
}
.precio-anterior {
    font-size: .85rem; color: #94a3b8;
    text-decoration: line-through; font-weight: 500;
}
.precio-unidad { font-size: .72rem; color: #94a3b8; font-weight: 500; }
.ahorro-chip {
    display: inline-flex; align-items: center; gap: 4px;
    background: #dcfce7; color: #15803d;
    padding: 3px 10px; border-radius: 100px;
    font-size: .72rem; font-weight: 700;
}

/* Footer card */
.oferta-footer {
    display: flex; align-items: center; justify-content: space-between;
    border-top: 1px solid var(--color-border, #f1f5f9);
    padding: 10px 16px;
}
.oferta-ubicacion { font-size: .72rem; color: #94a3b8; display: flex; align-items: center; gap: 4px; }
.btn-ver-oferta {
    background: #dc2626; color: #fff; border: none;
    padding: 6px 14px; border-radius: 8px;
    font-size: .75rem; font-weight: 700;
    cursor: pointer; transition: background .2s;
    text-decoration: none;
}
.btn-ver-oferta:hover { background: #b91c1c; color: #fff; }

/* ── Paginación custom ──────────────────────────── */
.ofertas-pagination .page-link {
    border-radius: 8px !important;
    border: 1.5px solid #e2e8f0;
    color: #374151; font-weight: 600;
    margin: 0 2px; transition: all .2s;
}
.ofertas-pagination .page-item.active .page-link {
    background: #dc2626; border-color: #dc2626; color: #fff;
}
.ofertas-pagination .page-link:hover {
    background: #fef2f2; border-color: #dc2626; color: #dc2626;
}

/* ── Empty state ────────────────────────────────── */
.empty-offers {
    text-align: center; padding: 80px 20px;
    color: #94a3b8;
}
.empty-offers i { font-size: 3.5rem; margin-bottom: 16px; display: block; }
.empty-offers h5 { color: #374151; font-weight: 700; margin-bottom: 8px; }

/* ── Sort bar ───────────────────────────────────── */
.sort-bar {
    display: flex; align-items: center;
    justify-content: space-between;
    flex-wrap: wrap; gap: 10px;
    margin-bottom: 20px;
}
.sort-bar-left { font-size: .85rem; color: #64748b; }
.sort-bar-left strong { color: var(--color-text, #0f172a); }

@media (max-width: 767px) {
    .oferta-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
    .filtro-card { position: static; margin-bottom: 20px; }
}
@media (max-width: 480px) {
    .oferta-grid { grid-template-columns: 1fr; }
}
</style>
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