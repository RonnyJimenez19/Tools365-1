@props([
    'tipo'    => 'mision',  // 'mision' | 'vision'
    'icono'   => 'bi-bullseye',
    'imagen'  => null,
    'titulo'  => '',
    'texto'   => '',
    'valores' => [],
])

<div class="col-md-6">
    <div class="mv-card {{ $tipo }} {{ $imagen ? 'mv-card--con-imagen' : '' }}">

        {{-- Imagen opcional --}}
        @if($imagen)
            <div class="mv-imagen-wrap">
                <img src="{{ asset($imagen) }}" alt="{{ $titulo }}" class="mv-imagen">
                {{-- Ícono flotante sobre la imagen --}}
                <div class="mv-icon-flotante {{ $tipo }}">
                    <i class="bi {{ $icono }}"></i>
                </div>
            </div>
        @else
            {{-- Sin imagen: ícono normal --}}
            <div class="mv-icon {{ $tipo }}">
                <i class="bi {{ $icono }}"></i>
            </div>
        @endif

        <div class="mv-card-body">
            <h3>{{ $titulo }}</h3>
            <p>{{ $texto }}</p>
            @if(count($valores))
                <div class="mv-values">
                    @foreach($valores as $valor)
                        <span class="mv-value-tag {{ $tipo === 'vision' ? 'mv-value-tag--vision' : '' }}">
                            {{ $valor }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>