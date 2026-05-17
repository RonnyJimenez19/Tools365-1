@extends('layouts.app')

@section('titulo_pagina', 'Editar publicación – Tools365')

@push('css')
{{-- Reutiliza exactamente los mismos estilos de create --}}
<style>
:root {
    --pub-accent:   #f97316;
    --pub-accent2:  #fb923c;
    --pub-dark:     #0f172a;
    --pub-surface:  #ffffff;
    --pub-border:   #e2e8f0;
    --pub-muted:    #64748b;
    --pub-radius:   14px;
    --pub-shadow:   0 4px 24px rgba(15,23,42,.08);
}
[data-theme="dark"] {
    --pub-surface: #1e293b;
    --pub-border:  #334155;
    --pub-muted:   #64748b;
    --pub-shadow:  0 4px 24px rgba(0,0,0,.3);
}
.pub-page { padding-top: 140px; padding-bottom: 80px; min-height: 100vh; background: var(--color-bg, #f8fafc); }
.pub-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 60%, #f97316 200%);
    padding: 56px 0 80px; margin-top: -140px; padding-top: 190px;
    margin-bottom: -40px; position: relative; overflow: hidden;
}
.pub-hero::before {
    content: ''; position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.pub-hero-content { position: relative; z-index: 1; }
.pub-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(249,115,22,.2); border: 1px solid rgba(249,115,22,.4);
    color: #fb923c; padding: 4px 14px; border-radius: 100px;
    font-size: .78rem; font-weight: 600; letter-spacing: .04em;
    text-transform: uppercase; margin-bottom: 14px;
}
.pub-hero h1 { color: #fff; font-weight: 800; font-size: clamp(1.6rem,4vw,2.4rem); margin-bottom: 8px; }
.pub-hero p  { color: rgba(255,255,255,.65); max-width: 520px; }
.pub-card { background: var(--pub-surface); border-radius: var(--pub-radius); box-shadow: var(--pub-shadow); border: 1px solid var(--pub-border); overflow: hidden; }
.pub-card-header {
    padding: 20px 28px; border-bottom: 1px solid var(--pub-border);
    display: flex; align-items: center; gap: 12px;
    background: linear-gradient(135deg, rgba(249,115,22,.06) 0%, transparent 70%);
}
.pub-card-header .step-badge {
    width: 32px; height: 32px; border-radius: 50%;
    background: var(--pub-accent); color: #fff; font-weight: 700; font-size: .85rem;
    display: grid; place-items: center; flex-shrink: 0;
}
.pub-card-header h5 { margin: 0; font-weight: 700; font-size: 1rem; color: #0f172a; }
.pub-card-body { padding: 28px; }
.form-label { font-size: .82rem; font-weight: 600; color: var(--color-text, #0f172a); letter-spacing: .02em; margin-bottom: 6px; }
.form-control, .form-select { border: 1.5px solid var(--pub-border); border-radius: 10px; padding: 10px 14px; font-size: .9rem; background: var(--pub-surface); color: var(--color-text, #0f172a); transition: border-color .2s, box-shadow .2s; }
.form-control:focus, .form-select:focus { border-color: var(--pub-accent); box-shadow: 0 0 0 3px rgba(249,115,22,.15); outline: none; }
.form-control.is-invalid, .form-select.is-invalid { border-color: #ef4444; }
.invalid-feedback { font-size: .78rem; color: #ef4444; }
.form-hint { font-size: .78rem; color: #64748b; margin-top: 4px; }
.tipo-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 10px; }
.tipo-option { display: none; }
.tipo-label { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px; padding: 18px 10px; border: 2px solid var(--pub-border); border-radius: 12px; cursor: pointer; font-weight: 600; font-size: .82rem; transition: all .2s; color: var(--pub-muted); text-align: center; }
.tipo-label i { font-size: 1.5rem; }
.tipo-option:checked + .tipo-label { border-color: var(--pub-accent); background: rgba(249,115,22,.07); color: var(--pub-accent); }
.tipo-label:hover { border-color: var(--pub-accent2); color: var(--pub-accent2); }

/* Imágenes existentes */
.img-existente-grid { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 16px; }
.img-existente-item {
    position: relative; width: 90px; height: 90px;
    border-radius: 10px; overflow: hidden;
    border: 2px solid var(--pub-border);
}
.img-existente-item img { width: 100%; height: 100%; object-fit: cover; }
.img-existente-item .badge-principal {
    position: absolute; bottom: 3px; left: 3px;
    background: rgba(249,115,22,.9); color: #fff;
    font-size: .6rem; font-weight: 700; padding: 1px 5px; border-radius: 4px;
}
.img-existente-item label {
    position: absolute; inset: 0;
    display: flex; align-items: center; justify-content: center;
    background: rgba(239,68,68,0); cursor: pointer;
    transition: background .2s;
}
.img-existente-item input[type="checkbox"] { display: none; }
.img-existente-item input[type="checkbox"]:checked ~ label {
    background: rgba(239,68,68,.65);
}
.img-existente-item input[type="checkbox"]:checked ~ label::after {
    content: '✕'; color: #fff; font-size: 1.6rem; font-weight: 900;
}
.img-existente-item label:hover { background: rgba(239,68,68,.3); }

.upload-zone { border: 2px dashed var(--pub-border); border-radius: 12px; padding: 30px 20px; text-align: center; cursor: pointer; transition: all .2s; position: relative; }
.upload-zone:hover, .upload-zone.drag-over { border-color: var(--pub-accent); background: rgba(249,115,22,.04); }
.upload-zone input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; }
.upload-icon { font-size: 2rem; color: var(--pub-muted); margin-bottom: 8px; }
.upload-preview { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 12px; }
.preview-item { position: relative; width: 80px; height: 80px; border-radius: 8px; overflow: hidden; border: 2px solid var(--pub-border); }
.preview-item img { width: 100%; height: 100%; object-fit: cover; }
.preview-item .remove-img { position: absolute; top: 2px; right: 2px; background: rgba(239,68,68,.9); color: #fff; border: none; border-radius: 50%; width: 20px; height: 20px; font-size: .7rem; cursor: pointer; display: grid; place-items: center; }

.detalle-row { display: grid; grid-template-columns: 1fr 1fr auto; gap: 8px; align-items: center; margin-bottom: 8px; }
.detalle-row .btn-remove-detalle { background: none; border: 1.5px solid #ef4444; color: #ef4444; border-radius: 8px; padding: 8px 10px; cursor: pointer; transition: all .2s; }
.detalle-row .btn-remove-detalle:hover { background: #ef4444; color: #fff; }
#btn-add-detalle { border: 1.5px dashed var(--pub-accent); background: transparent; color: var(--pub-accent); border-radius: 10px; padding: 8px 18px; font-weight: 600; font-size: .84rem; cursor: pointer; transition: all .2s; }
#btn-add-detalle:hover { background: rgba(249,115,22,.08); }

.btn-publicar { background: linear-gradient(135deg, var(--pub-accent), #ea580c); color: #fff; border: none; padding: 14px 40px; border-radius: 12px; font-size: 1rem; font-weight: 700; letter-spacing: .02em; cursor: pointer; transition: all .25s; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(249,115,22,.35); }
.btn-publicar:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(249,115,22,.45); }

.tip-card { background: var(--pub-surface); border: 1px solid var(--pub-border); border-radius: var(--pub-radius); padding: 20px; box-shadow: var(--pub-shadow); position: sticky; top: 100px; }
.tip-card h6 { font-weight: 700; font-size: .9rem; margin-bottom: 14px; display: flex; align-items: center; gap: 8px; }
.tip-item { display: flex; align-items: flex-start; gap: 10px; font-size: .82rem; color: var(--color-text,.374151); margin-bottom: 12px; line-height: 1.5; }
.tip-item i { color: var(--pub-accent); flex-shrink: 0; margin-top: 2px; }

@media (max-width: 767px) {
    .tipo-grid { grid-template-columns: 1fr 1fr; }
    .detalle-row { grid-template-columns: 1fr 1fr auto; }
}
</style>
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