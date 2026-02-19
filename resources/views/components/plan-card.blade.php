@props([
    'nombre'        => '',
    'precio'        => '',
    'periodo'       => '/mes',
    'descripcion'   => '',
    'caracteristicas' => [],
    'destacado'     => 'false',
    'badge'         => null,
])

<div class="plan-col">
    {{-- Badge wrapper — siempre ocupa espacio para alinear las cards --}}
    <div class="plan-badge-wrap">
        @if($badge)
            <span class="plan-badge">{{ $badge }}</span>
        @endif
    </div>

    <div class="plan-card-inner {{ $destacado === 'true' ? 'destacado' : '' }}">

        <div class="plan-header">
            <div class="plan-name {{ $destacado === 'true' ? 'destacado-text' : '' }}">
                {{ $nombre }}
            </div>
            <div class="plan-price-row">
                <span class="plan-price">{{ $precio }}</span>
                <span class="plan-period">{{ $periodo }}</span>
            </div>
            <p class="plan-desc">{{ $descripcion }}</p>
        </div>

        <ul class="plan-features">
            @foreach($caracteristicas as $item)
                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    {{ $item }}
                </li>
            @endforeach
        </ul>

        <div class="plan-footer">
            <button class="btn-plan {{ $destacado === 'true' ? 'destacado-btn' : '' }}">
                Seleccionar Plan
            </button>
        </div>

    </div>
</div>