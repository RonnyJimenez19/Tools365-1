<div class="col-lg-4 col-md-6">
    <div class="product-card">

        {{-- Imagen principal del producto --}}
        @if($producto->imagenPrincipal)
            <img src="{{ asset($producto->imagenPrincipal->ruta) }}"
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
                @if($producto->tipo === 'renta')     <i class="bi bi-clock-history me-1"></i>Renta
                @elseif($producto->tipo === 'venta') <i class="bi bi-bag-check me-1"></i>Venta
                @else                                <i class="bi bi-hammer me-1"></i>Subasta
                @endif
            </span>

            {{-- Categoría --}}
            @if($producto->categoria)
                <span class="badge-tipo" style="background:#f0f4ff; color:#1F3A93;">
                    <i class="bi {{ $producto->categoria->icono ?? 'bi-grid' }} me-1"></i>
                    {{ $producto->categoria->nombre }}
                </span>
            @endif

            {{-- Título --}}
            <div class="product-card-title mt-1">
                @if(isset($termino) && $termino)
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