@props(['icon', 'titulo', 'descripcion'])

<div class="col-lg-3 col-md-6">
    <div class="card bg-transparent border-light h-100">
        <div class="card-body text-center">
            <div class="bg-accent rounded-3 d-inline-flex align-items-center justify-content-center mb-3" 
                 style="width: 60px; height: 60px;">
                <i class="bi {{ $icon }} fs-2 text-white"></i>
            </div>
            <h5 class="card-title fw-bold text-white">{{ $titulo }}</h5>
            <p class="card-text text-white-50">{{ $descripcion }}</p>
        </div>
    </div>
</div>