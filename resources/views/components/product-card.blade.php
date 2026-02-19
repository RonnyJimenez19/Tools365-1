@props([
    'icono'     => 'bi-tools',
    'iconoBg'   => 'linear-gradient(135deg, #e8eaf6, #c5cae9)',
    'iconoColor'=> '#9fa8da',
    'imagen'    => null,
    'badge'     => null,
    'badgeTipo' => 'primary',  // primary | success | warning | danger
    'titulo'    => '',
    'precio'    => '',
    'unidad'    => null,       // ej: '/día', '/mes'
    'ubicacion' => null,
    'timer'     => null,       // si viene, muestra countdown de subasta
])

<div class="col-lg-3 col-md-4 col-6">
    <div class="product-card">

        {{-- Imagen o ícono placeholder --}}
        @if($imagen)
            <img src="{{ asset($imagen) }}" alt="{{ $titulo }}" class="product-card-img" style="display:block;">
        @else
            <div class="product-card-img" style="background:{{ $iconoBg }}; color:{{ $iconoColor }};">
                <i class="bi {{ $icono }}"></i>
            </div>
        @endif

        <div class="product-card-body">

            {{-- Timer de subasta --}}
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

            <div class="product-card-title {{ $badge ? 'mt-1' : '' }}">{{ $titulo }}</div>

            <div class="product-card-price">
                {{ $precio }}
                @if($unidad)
                    <small>{{ $unidad }}</small>
                @endif
            </div>

            @if($ubicacion)
                <div class="product-card-location">
                    <i class="bi bi-geo-alt"></i> {{ $ubicacion }}
                </div>
            @endif
        </div>
    </div>
</div>