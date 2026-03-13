{{--
    Props del componente:
    - producto   : instancia de Producto (con relación imagenes cargada)
    - termino    : palabra buscada para highlight (opcional, solo en búsquedas)
    - icono      : clase Bootstrap Icon fallback
    - iconoBg    : gradiente CSS para placeholder
    - iconoColor : color del ícono placeholder
    - badge      : texto del badge (Renta, Venta, Subasta)
    - badgeTipo  : primary | success | danger
    - timer      : string de tiempo para subasta
--}}
@props([
    'producto'   => null,
    'termino'    => null,
    'icono'      => 'bi-tools',
    'iconoBg'    => 'linear-gradient(135deg, #e8eaf6, #c5cae9)',
    'iconoColor' => '#9fa8da',
    'badge'      => null,
    'badgeTipo'  => 'primary',
    'timer'      => null,
])

@php
    // Si viene un modelo Producto, extraer sus datos; si no, usar props sueltos (compatibilidad)
    if ($producto) {
        $titulo    = $producto->titulo;
        $precio    = '$' . number_format($producto->precio, 0, '.', ',');
        $unidad    = $producto->unidad;
        $ubicacion = $producto->ubicacion;
        $imagenes  = $producto->relationLoaded('imagenes')
                        ? $producto->imagenes
                        : $producto->imagenes()->orderBy('orden')->get();
        // Badge y timer dinámicos según tipo
        if (!$badge) {
            $badge = match($producto->tipo) {
                'renta'   => 'Renta',
                'venta'   => 'Venta',
                'subasta' => 'Subasta',
                default   => null,
            };
            $badgeTipo = match($producto->tipo) {
                'renta'   => 'primary',
                'venta'   => 'success',
                'subasta' => 'danger',
                default   => 'secondary',
            };
        }
        if (!$timer && $producto->tipo === 'subasta' && $producto->timer_fin) {
            $timer = \Carbon\Carbon::parse($producto->timer_fin)
                        ->locale('es')
                        ->diffForHumans(['parts' => 2, 'short' => true]);
        }
    } else {
        $titulo    = $attributes->get('titulo', '');
        $precio    = $attributes->get('precio', '');
        $unidad    = $attributes->get('unidad');
        $ubicacion = $attributes->get('ubicacion');
        $imagenes  = collect();
        // Si viene prop imagen directa (legado)
        $imagenLegado = $attributes->get('imagen');
        if ($imagenLegado) {
            $imagenes = collect([(object)['ruta' => $imagenLegado, 'descripcion' => $titulo]]);
        }
    }

    $carouselId = 'carousel-' . ($producto?->id ?? uniqid());
    $tieneVariasImagenes = $imagenes->count() > 1;
@endphp

<div class="col-lg-3 col-md-4 col-6">
    <div class="product-card">

        {{-- ── IMAGEN / CARRUSEL ── --}}
        @if($imagenes->isEmpty())
            {{-- Sin imagen: placeholder --}}
            <div class="product-card-img-placeholder"
                 style="height:160px; background:{{ $iconoBg }}; color:{{ $iconoColor }};
                        display:flex; align-items:center; justify-content:center; font-size:3rem;">
                <i class="bi {{ $icono }}"></i>
            </div>

        @elseif($tieneVariasImagenes)
            {{-- Carrusel de múltiples ángulos --}}
            <div id="{{ $carouselId }}" class="carousel slide product-carousel" data-bs-ride="false">

                {{-- Indicadores (puntitos) --}}
                <div class="carousel-indicators product-carousel-indicators">
                    @foreach($imagenes as $i => $img)
                        <button type="button"
                                data-bs-target="#{{ $carouselId }}"
                                data-bs-slide-to="{{ $i }}"
                                {{ $i === 0 ? 'class=active aria-current=true' : '' }}
                                aria-label="Imagen {{ $i + 1 }}">
                        </button>
                    @endforeach
                </div>

                <div class="carousel-inner">
                    @foreach($imagenes as $i => $img)
                        <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                            <img src="{{ asset($img->ruta) }}"
                                 alt="{{ $img->descripcion ?? $titulo }}"
                                 class="product-card-img">
                            @if($img->descripcion)
                                <div class="carousel-caption product-carousel-caption">
                                    {{ $img->descripcion }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Controles prev/next --}}
                <button class="carousel-control-prev product-carousel-btn" type="button"
                        data-bs-target="#{{ $carouselId }}" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" style="width:16px; height:16px;"></span>
                </button>
                <button class="carousel-control-next product-carousel-btn" type="button"
                        data-bs-target="#{{ $carouselId }}" data-bs-slide="next">
                    <span class="carousel-control-next-icon" style="width:16px; height:16px;"></span>
                </button>

            </div>

        @else
            {{-- Una sola imagen --}}
            <img src="{{ asset($imagenes->first()->ruta) }}"
                 alt="{{ $imagenes->first()->descripcion ?? $titulo }}"
                 class="product-card-img"
                 style="height:160px; object-fit:cover; width:100%; display:block;">
        @endif

        {{-- ── CUERPO DE LA CARD ── --}}
        <div class="product-card-body">

            {{-- Timer subasta --}}
            @if($timer)
                <div class="auction-timer mb-2">
                    <i class="bi bi-clock"></i> {{ $timer }}
                </div>
            @endif

            {{-- Badge tipo --}}
            @if($badge)
                <span class="product-card-badge badge bg-{{ $badgeTipo }}-subtle text-{{ $badgeTipo }}">
                    {{ $badge }}
                </span>
            @endif

            <div class="product-card-title {{ $badge ? 'mt-1' : '' }}">
                @if($termino)
                    {!! preg_replace('/(' . preg_quote($termino, '/') . ')/iu', '<mark>$1</mark>', e($titulo)) !!}
                @else
                    {{ $titulo }}
                @endif
            </div>

            <div class="product-card-price">
                {{ $precio }}
                @if($unidad ?? false)
                    <small>{{ $unidad }}</small>
                @endif
            </div>

            @if($ubicacion ?? false)
                <div class="product-card-location">
                    <i class="bi bi-geo-alt"></i> {{ $ubicacion }}
                </div>
            @endif

        </div>
    </div>
</div>

{{-- Estilos del carrusel (se inyectan una sola vez gracias al stack) --}}
@once
@push('css')
<style>
    /* ── Carrusel dentro de la card ── */
    .product-carousel { position: relative; }
    .product-card-img { height: 160px; object-fit: cover; width: 100%; display: block; }

    /* Puntitos pequeños */
    .product-carousel-indicators {
        bottom: 4px; margin: 0;
    }
    .product-carousel-indicators button {
        width: 6px !important; height: 6px !important;
        border-radius: 50% !important;
        background-color: rgba(255,255,255,0.6) !important;
        border: none !important;
        margin: 0 2px !important;
        opacity: 1 !important;
    }
    .product-carousel-indicators button.active {
        background-color: var(--color-accent) !important;
    }

    /* Botones prev/next compactos */
    .product-carousel-btn {
        width: 24px;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .product-card:hover .product-carousel-btn { opacity: 1; }
    .product-carousel-btn.carousel-control-prev { left: 2px; }
    .product-carousel-btn.carousel-control-next { right: 2px; }

    /* Caption de ángulo */
    .product-carousel-caption {
        bottom: 18px; padding: 2px 8px;
        font-size: 0.68rem; font-weight: 700;
        background: rgba(0,0,0,0.45); border-radius: 4px;
        left: 50%; transform: translateX(-50%);
        white-space: nowrap; width: auto;
    }
</style>
@endpush
@endonce