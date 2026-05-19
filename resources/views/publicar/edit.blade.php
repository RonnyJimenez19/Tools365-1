@extends('layouts.app')

@section('titulo_pagina', 'Editar publicación – Tools365')

@push('css')
<link rel="stylesheet" href="{{ asset('css/create_pub.css') }}">
<link rel="stylesheet" href="{{ asset('css/edit_pub.css') }}">
@endpush

@section('contenido')

<div class="pub-hero">
    <div class="container">
        <div class="pub-hero-content">
            <div class="pub-badge">
                <i class="bi bi-pencil-fill"></i> Editar publicación
            </div>
            <h1>Edita tu herramienta</h1>
            <p>Actualiza la información, fotos y especificaciones de tu publicación.</p>
        </div>
    </div>
</div>

<div class="pub-page">
    <div class="container" style="margin-top: 20px;">

        @if ($errors->any())
            <div class="alert alert-danger rounded-3 mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Revisa los siguientes errores:</strong>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('mis-publicaciones.update', $producto) }}"
              method="POST" enctype="multipart/form-data" id="form-publicar">
            @csrf
            @method('PUT')

            <div class="row g-4">

                {{-- Columna principal --}}
                <div class="col-lg-8">

                    {{-- PASO 1: Información básica --}}
                    <div class="pub-card mb-4">
                        <div class="pub-card-header">
                            <div class="step-badge">1</div>
                            <h5>Información básica</h5>
                        </div>
                        <div class="pub-card-body">

                            {{-- Tipo --}}
                            <div class="mb-4">
                                <label class="form-label">Tipo de publicación *</label>
                                <div class="tipo-grid">
                                    @foreach(['renta' => ['bi-clock-history','Renta'], 'venta' => ['bi-bag-check-fill','Venta'], 'subasta' => ['bi-hammer','Subasta']] as $val => [$icon, $label])
                                    <div>
                                        <input type="radio" name="tipo" id="tipo-{{ $val }}" value="{{ $val }}"
                                               class="tipo-option"
                                               {{ old('tipo', $producto->tipo) === $val ? 'checked' : '' }}>
                                        <label for="tipo-{{ $val }}" class="tipo-label">
                                            <i class="bi {{ $icon }}"></i>{{ $label }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                @error('tipo') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            {{-- Título --}}
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título del anuncio *</label>
                                <input type="text" name="titulo" id="titulo"
                                       class="form-control @error('titulo') is-invalid @enderror"
                                       value="{{ old('titulo', $producto->titulo) }}"
                                       maxlength="200">
                                @error('titulo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Descripción --}}
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción *</label>
                                <textarea name="descripcion" id="descripcion" rows="5"
                                          class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $producto->descripcion) }}</textarea>
                                @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Categoría + Ubicación --}}
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="categoria_id" class="form-label">Categoría *</label>
                                    <select name="categoria_id" id="categoria_id"
                                            class="form-select @error('categoria_id') is-invalid @enderror">
                                        <option value="">Selecciona una categoría</option>
                                        @foreach($categorias as $cat)
                                            <option value="{{ $cat->id }}"
                                                {{ old('categoria_id', $producto->categoria_id) == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('categoria_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="ubicacion" class="form-label">Ubicación *</label>
                                    <input type="text" name="ubicacion" id="ubicacion"
                                           class="form-control @error('ubicacion') is-invalid @enderror"
                                           value="{{ old('ubicacion', $producto->ubicacion) }}">
                                    @error('ubicacion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- PASO 2: Precio --}}
                    <div class="pub-card mb-4">
                        <div class="pub-card-header">
                            <div class="step-badge">2</div>
                            <h5>Precio</h5>
                        </div>
                        <div class="pub-card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="precio" class="form-label">Precio (MXN) *</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="border-radius:10px 0 0 10px; border:1.5px solid var(--pub-border); background:transparent;">$</span>
                                        <input type="number" name="precio" id="precio"
                                               class="form-control @error('precio') is-invalid @enderror"
                                               style="border-radius:0 10px 10px 0;"
                                               value="{{ old('precio', $producto->precio) }}"
                                               min="0" step="0.01">
                                        @error('precio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="unidad" class="form-label">Unidad / Periodo *</label>
                                    <select name="unidad" id="unidad" class="form-select">
                                        <option value="">Sin especificar</option>
                                        @foreach(['/día', '/semana', '/mes', 'precio fijo', 'puja actual'] as $u)
                                            <option value="{{ $u }}" {{ old('unidad', $producto->unidad) == $u ? 'selected' : '' }}>{{ $u }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div id="subasta-fields" class="mt-3" style="display:none;">
                                <label for="timer_fin" class="form-label">Fecha y hora de cierre de subasta</label>
                                <input type="datetime-local" name="timer_fin" id="timer_fin"
                                       class="form-control @error('timer_fin') is-invalid @enderror"
                                       value="{{ old('timer_fin', $producto->timer_fin ? \Carbon\Carbon::parse($producto->timer_fin)->format('Y-m-d\TH:i') : '') }}">
                                @error('timer_fin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- PASO 3: Imágenes --}}
                    <div class="pub-card mb-4">
                        <div class="pub-card-header">
                            <div class="step-badge">3</div>
                            <h5>Fotos del producto</h5>
                        </div>
                        <div class="pub-card-body">

                            {{-- Imágenes existentes --}}
                            @if($producto->imagenes->isNotEmpty())
                                <div class="form-hint mb-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Haz clic en una foto para marcarla y eliminarla al guardar.
                                </div>
                                <div class="img-existente-grid">
                                    @foreach($producto->imagenes as $i => $img)
                                        <div class="img-existente-item">
                                            <img src="{{ asset($img->ruta) }}" alt="">
                                            @if($i === 0)
                                                <span class="badge-principal">Principal</span>
                                            @endif
                                            <input type="checkbox"
                                                   name="eliminar_imagenes[]"
                                                   value="{{ $img->id }}"
                                                   id="del-img-{{ $img->id }}">
                                            <label for="del-img-{{ $img->id }}" title="Clic para eliminar"></label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Subir nuevas --}}
                            <div class="upload-zone" id="upload-zone">
                                <input type="file" name="imagenes[]" id="imagenes" accept="image/*" multiple>
                                <div class="upload-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                                <div style="font-weight:600; margin-bottom:4px;">Agregar más fotos</div>
                                <div class="form-hint">JPG, PNG, WEBP · máx. 4 MB c/u · hasta 10 fotos en total</div>
                                <div class="upload-preview" id="upload-preview"></div>
                            </div>

                            @error('imagenes') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- PASO 4: Especificaciones --}}
                    <div class="pub-card mb-4">
                        <div class="pub-card-header">
                            <div class="step-badge">4</div>
                            <h5>Especificaciones técnicas <span style="font-weight:400; color:var(--pub-muted);">(opcional)</span></h5>
                        </div>
                        <div class="pub-card-body">
                            <div id="detalles-container">
                                @if(old('detalles'))
                                    @foreach(old('detalles') as $i => $det)
                                        <div class="detalle-row">
                                            <input type="text" name="detalles[{{ $i }}][clave]" class="form-control" placeholder="Ej: Marca" value="{{ $det['clave'] ?? '' }}">
                                            <input type="text" name="detalles[{{ $i }}][valor]" class="form-control" placeholder="Ej: Caterpillar" value="{{ $det['valor'] ?? '' }}">
                                            <button type="button" class="btn-remove-detalle"><i class="bi bi-trash3"></i></button>
                                        </div>
                                    @endforeach
                                @elseif($producto->detalles->isNotEmpty())
                                    @foreach($producto->detalles as $i => $det)
                                        <div class="detalle-row">
                                            <input type="text" name="detalles[{{ $i }}][clave]" class="form-control" placeholder="Ej: Marca" value="{{ $det->clave }}">
                                            <input type="text" name="detalles[{{ $i }}][valor]" class="form-control" placeholder="Ej: Caterpillar" value="{{ $det->valor }}">
                                            <button type="button" class="btn-remove-detalle"><i class="bi bi-trash3"></i></button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="detalle-row">
                                        <input type="text" name="detalles[0][clave]" class="form-control" placeholder="Ej: Marca">
                                        <input type="text" name="detalles[0][valor]" class="form-control" placeholder="Ej: Caterpillar">
                                        <button type="button" class="btn-remove-detalle"><i class="bi bi-trash3"></i></button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" id="btn-add-detalle">
                                <i class="bi bi-plus-lg me-1"></i> Agregar especificación
                            </button>
                            <div class="form-hint mt-2">Ejemplos: Año de fabricación, Capacidad de carga, Combustible…</div>
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <button type="submit" class="btn-publicar">
                            <i class="bi bi-check-circle-fill"></i>
                            Guardar cambios
                        </button>
                        <a href="{{ route('mis-publicaciones.index') }}"
                           class="btn btn-outline-secondary rounded-pill px-4">
                            Cancelar
                        </a>
                    </div>

                </div>

                {{-- Sidebar --}}
                <div class="col-lg-4">
                    <div class="tip-card">
                        <h6><i class="bi bi-lightbulb-fill text-warning"></i> Consejos para editar</h6>
                        <div class="tip-item">
                            <i class="bi bi-image"></i>
                            <span>Haz clic en las fotos existentes para marcarlas y eliminarlas. Puedes subir nuevas desde la zona de carga.</span>
                        </div>
                        <div class="tip-item">
                            <i class="bi bi-pencil-square"></i>
                            <span>Actualiza el título y descripción si cambió el estado o condición del equipo.</span>
                        </div>
                        <div class="tip-item">
                            <i class="bi bi-currency-dollar"></i>
                            <span>Puedes ajustar el precio en cualquier momento sin afectar el historial.</span>
                        </div>
                        <hr style="margin: 16px 0; opacity:.15;">
                        <div style="font-size:.78rem; color:var(--pub-muted);">
                            <i class="bi bi-shield-check text-success me-1"></i>
                            Los cambios se aplican de inmediato al guardar.
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // Tipo / subasta
    const tipoRadios = document.querySelectorAll('input[name="tipo"]');
    const subastaBox = document.getElementById('subasta-fields');
    const unidadSel  = document.getElementById('unidad');

    function actualizarTipo() {
        const val = document.querySelector('input[name="tipo"]:checked')?.value;
        subastaBox.style.display = (val === 'subasta') ? 'block' : 'none';
        if (val === 'subasta' && unidadSel.value === '') unidadSel.value = 'puja actual';
    }
    tipoRadios.forEach(r => r.addEventListener('change', actualizarTipo));
    actualizarTipo();

    // Nuevas imágenes
    const inputImg = document.getElementById('imagenes');
    const preview  = document.getElementById('upload-preview');
    const zone     = document.getElementById('upload-zone');
    let selectedFiles = [];

    function renderPreviews() {
        preview.innerHTML = '';
        selectedFiles.forEach((f, i) => {
            const item = document.createElement('div');
            item.className = 'preview-item';
            item.innerHTML = `
                <img src="${URL.createObjectURL(f)}" alt="">
                <button type="button" class="remove-img" data-idx="${i}">×</button>
            `;
            preview.appendChild(item);
        });
        syncInput();
        preview.querySelectorAll('.remove-img').forEach(btn => {
            btn.addEventListener('click', () => {
                selectedFiles.splice(+btn.dataset.idx, 1);
                renderPreviews();
            });
        });
    }

    function syncInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        inputImg.files = dt.files;
    }

    inputImg.addEventListener('change', () => {
        Array.from(inputImg.files).forEach(f => {
            if (f instanceof File && f.size > 0 && selectedFiles.length < 10)
                selectedFiles.push(f);
        });
        renderPreviews();
    });

    zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('drag-over'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
    zone.addEventListener('drop', e => {
        e.preventDefault(); zone.classList.remove('drag-over');
        Array.from(e.dataTransfer.files).forEach(f => {
            if (f instanceof File && f.size > 0 && selectedFiles.length < 10)
                selectedFiles.push(f);
        });
        renderPreviews();
    });

    // Especificaciones
    const detallesContainer = document.getElementById('detalles-container');
    const btnAdd = document.getElementById('btn-add-detalle');
    let detalleIdx = detallesContainer.querySelectorAll('.detalle-row').length;

    btnAdd.addEventListener('click', () => {
        const row = document.createElement('div');
        row.className = 'detalle-row';
        row.innerHTML = `
            <input type="text" name="detalles[${detalleIdx}][clave]" class="form-control" placeholder="Ej: Capacidad">
            <input type="text" name="detalles[${detalleIdx}][valor]" class="form-control" placeholder="Ej: 500 kg">
            <button type="button" class="btn-remove-detalle"><i class="bi bi-trash3"></i></button>
        `;
        detallesContainer.appendChild(row);
        detalleIdx++;
        row.querySelector('.btn-remove-detalle').addEventListener('click', () => row.remove());
    });

    detallesContainer.querySelectorAll('.btn-remove-detalle').forEach(btn => {
        btn.addEventListener('click', () => btn.closest('.detalle-row').remove());
    });
});
</script>
@endpush