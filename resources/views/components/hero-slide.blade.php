@props([
    'activo'    => false,
    'gradiente' => 'linear-gradient(135deg, #1F3A93 0%, #2C3E50 100%)',
    'imagen'    => null,
    'badge'     => null,
    'badgeColor'=> '#F39C12',
    'titulo'    => '',
    'acento'    => '',
    'descripcion'=> '',
    'btnTexto'  => 'Explorar',
    'btnIcono'  => 'bi-arrow-right',
    'btnTexto2' => null,
    'btnIcono2' => null,
])

<div class="carousel-item {{ $activo ? 'active' : '' }}">
    <div class="hero-slide" style="background: {{ $gradiente }};">
        @if($imagen)
            <img src="{{ asset($imagen) }}" alt="" class="hero-img">
        @endif
        <div class="container hero-content text-white">
            <div class="col-lg-7">
                @if($badge)
                    <span class="badge mb-3 px-3 py-2" style="background:{{ $badgeColor }}; font-size:0.85rem;">
                        {{ $badge }}
                    </span>
                @endif
                <h1 class="display-4 fw-bold mb-4">
                    {{ $titulo }} <span class="text-accent">{{ $acento }}</span>
                </h1>
                <p class="lead mb-4 text-white-75">{{ $descripcion }}</p>
                <div class="d-flex gap-3 flex-wrap">
                    <button class="btn btn-accent btn-lg px-4">
                        <i class="bi {{ $btnIcono }} me-2"></i>{{ $btnTexto }}
                    </button>
                    @if($btnTexto2)
                        <button class="btn btn-outline-light btn-lg px-4">
                            <i class="bi {{ $btnIcono2 }} me-2"></i>{{ $btnTexto2 }}
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>