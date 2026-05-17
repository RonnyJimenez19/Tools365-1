@props(['comentario'])

<div class="card h-100 border-0 shadow-sm rounded-4 p-4">

    {{-- Estrellas --}}
    <div class="mb-3">
        {!! $comentario->estrellasHtml() !!}
    </div>

    {{-- Cuerpo del comentario --}}
    <p class="text-muted flex-grow-1 mb-4" style="font-size:.95rem; line-height:1.6;">
        "{{ $comentario->cuerpo }}"
    </p>

    {{-- Autor --}}
    <div class="d-flex align-items-center gap-3 mt-auto">

        {{-- Avatar con iniciales --}}
        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0"
             style="width:44px; height:44px; background:var(--bs-primary); font-size:.85rem;">
            {{ $comentario->iniciales() }}
        </div>

        <div>
            <div class="fw-semibold" style="font-size:.95rem;">
                {{ $comentario->autor_nombre }}
            </div>
            <div class="text-muted" style="font-size:.8rem;">
                {{ $comentario->created_at->diffForHumans() }}
            </div>
        </div>

    </div>

</div>