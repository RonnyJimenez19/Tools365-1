@props(['icon', 'titulo', 'cantidad', 'color' => 'primary'])

<div class="col-lg-3 col-md-4 col-sm-6">
    <div class="card h-100 border-0 shadow-sm hover-shadow">
        <div class="card-body text-center">
            <div class="bg-{{ $color }} text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                 style="width: 70px; height: 70px;">
                <i class="bi {{ $icon }} fs-3"></i>
            </div>
            <h5 class="card-title fw-bold">{{ $titulo }}</h5>
            <p class="card-text text-muted small">{{ $cantidad }}</p>
        </div>
    </div>
</div>

<style>
    .hover-shadow {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
</style>
