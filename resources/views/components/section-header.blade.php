@props([
    'titulo'  => '',
    'icono'   => 'bi-grid',
    'color'   => 'primary',  // cualquier color de Bootstrap o CSS
    'verMas'  => '#',
    'verMasTexto' => 'Ver más',
])

<div class="section-title-bar">
    <h2>
        <i class="bi {{ $icono }}" style="color: var(--bs-{{ $color }}, currentColor);"></i>
        {{ $titulo }}
    </h2>
    <a href="{{ $verMas }}">{{ $verMasTexto }} <i class="bi bi-arrow-right"></i></a>
</div>