@props([
    'nombre', 
    'precio', 
    'descripcion', 
    'caracteristicas' => [], 
    'destacado' => 'false',
    'badge' => null,
    'periodo' => ''
])

<div class="card h-100 {{ $destacado === 'true' ? 'border-warning shadow-lg' : 'border' }}">
    @if($badge)
        <div class="position-absolute top-0 start-50 translate-middle">
            <span class="badge bg-warning text-dark px-3 py-2">{{ $badge }}</span>
        </div>
    @endif
    
    <div class="card-body {{ $badge ? 'pt-5' : '' }}">
        <h3 class="card-title fw-bold text-center mb-2">{{ $nombre }}</h3>
        <p class="text-center text-muted small mb-4">{{ $descripcion }}</p>
        
        <div class="text-center mb-4">
            <span class="display-4 fw-bold text-primary">{{ $precio }}</span>
            @if($periodo)
                <span class="text-muted">{{ $periodo }}</span>
            @endif
        </div>
        
        <ul class="list-unstyled">
            @foreach($caracteristicas as $caracteristica)
                <li class="mb-3">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    {{ $caracteristica }}
                </li>
            @endforeach
        </ul>
    </div>
    
    <div class="card-footer bg-transparent border-0 pb-4">
        <button class="btn {{ $destacado === 'true' ? 'btn-warning' : 'btn-outline-primary' }} w-100">
            Seleccionar Plan
        </button>
    </div>
</div>