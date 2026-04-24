@extends('layouts.app')

@section('titulo_pagina', 'Publicar herramienta – Tools365')

@push('css')
<style>
/* ── Variables ─────────────────────────────────── */
:root {
    --pub-accent:   #f97316;
    --pub-accent2:  #fb923c;
    --pub-dark:     #0f172a;
    --pub-surface:  #ffffff;
    --pub-border:   #e2e8f0;
    --pub-muted:    #94a3b8;
    --pub-radius:   14px;
    --pub-shadow:   0 4px 24px rgba(15,23,42,.08);
}
[data-theme="dark"] {
    --pub-surface: #1e293b;
    --pub-border:  #334155;
    --pub-muted:   #64748b;
    --pub-shadow:  0 4px 24px rgba(0,0,0,.3);
}

/* ── Page layout ───────────────────────────────── */
.pub-page {
    padding-top: 140px;
    padding-bottom: 80px;
    min-height: 100vh;
    background: var(--color-bg, #f8fafc);
}
.pub-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 60%, #f97316 200%);
    padding: 56px 0 80px;
    margin-top: -140px;
    padding-top: 190px;
    margin-bottom: -40px;
    position: relative;
    overflow: hidden;
}
.pub-hero::before {
    content: '';
    position: absolute;
    inset: 0;
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

/* ── Card wrapper ──────────────────────────────── */
.pub-card {
    background: var(--pub-surface);
    border-radius: var(--pub-radius);
    box-shadow: var(--pub-shadow);
    border: 1px solid var(--pub-border);
    overflow: hidden;
}
.pub-card-header {
    padding: 20px 28px;
    border-bottom: 1px solid var(--pub-border);
    display: flex; align-items: center; gap: 12px;
    background: linear-gradient(135deg, rgba(249,115,22,.06) 0%, transparent 70%);
}
.pub-card-header .step-badge {
    width: 32px; height: 32px;
    border-radius: 50%;
    background: var(--pub-accent);
    color: #fff; font-weight: 700; font-size: .85rem;
    display: grid; place-items: center; flex-shrink: 0;
}
.pub-card-header h5 { margin: 0; font-weight: 700; font-size: 1rem; }
.pub-card-body { padding: 28px; }

/* ── Form controls ─────────────────────────────── */
.form-label {
    font-size: .82rem; font-weight: 600;
    color: var(--color-text, #0f172a);
    letter-spacing: .02em; margin-bottom: 6px;
}
.form-control, .form-select {
    border: 1.5px solid var(--pub-border);
    border-radius: 10px;
    padding: 10px 14px;
    font-size: .9rem;
    background: var(--pub-surface);
    color: var(--color-text, #0f172a);
    transition: border-color .2s, box-shadow .2s;
}
.form-control:focus, .form-select:focus {
    border-color: var(--pub-accent);
    box-shadow: 0 0 0 3px rgba(249,115,22,.15);
    outline: none;
}
.form-control.is-invalid, .form-select.is-invalid {
    border-color: #ef4444;
}
.invalid-feedback { font-size: .78rem; color: #ef4444; }
.form-hint { font-size: .78rem; color: var(--pub-muted); margin-top: 4px; }

/* ── Tipo selector ─────────────────────────────── */
.tipo-grid {
    display: grid; grid-template-columns: repeat(3,1fr); gap: 10px;
}
.tipo-option { display: none; }
.tipo-label {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 6px; padding: 18px 10px;
    border: 2px solid var(--pub-border);
    border-radius: 12px; cursor: pointer;
    font-weight: 600; font-size: .82rem;
    transition: all .2s;
    color: var(--pub-muted);
    text-align: center;
}
.tipo-label i { font-size: 1.5rem; }
.tipo-option:checked + .tipo-label {
    border-color: var(--pub-accent);
    background: rgba(249,115,22,.07);
    color: var(--pub-accent);
}
.tipo-label:hover { border-color: var(--pub-accent2); color: var(--pub-accent2); }

/* ── Upload zone ───────────────────────────────── */
.upload-zone {
    border: 2px dashed var(--pub-border);
    border-radius: 12px; padding: 40px 20px;
    text-align: center; cursor: pointer;
    transition: all .2s; position: relative;
}
.upload-zone:hover, .upload-zone.drag-over {
    border-color: var(--pub-accent);
    background: rgba(249,115,22,.04);
}
.upload-zone input[type="file"] {
    position: absolute; inset: 0; opacity: 0; cursor: pointer;
}
.upload-icon { font-size: 2.4rem; color: var(--pub-muted); margin-bottom: 10px; }
.upload-preview {
    display: flex; flex-wrap: wrap; gap: 10px; margin-top: 16px;
}
.preview-item {
    position: relative; width: 80px; height: 80px;
    border-radius: 8px; overflow: hidden;
    border: 2px solid var(--pub-border);
}
.preview-item img { width: 100%; height: 100%; object-fit: cover; }
.preview-item .remove-img {
    position: absolute; top: 2px; right: 2px;
    background: rgba(239,68,68,.9); color: #fff;
    border: none; border-radius: 50%;
    width: 20px; height: 20px; font-size: .7rem;
    cursor: pointer; display: grid; place-items: center;
    line-height: 1;
}
.preview-badge {
    position: absolute; bottom: 2px; left: 2px;
    background: rgba(249,115,22,.9); color: #fff;
    font-size: .6rem; font-weight: 700;
    padding: 1px 5px; border-radius: 4px;
}

/* ── Detalles dinámicos ────────────────────────── */
.detalle-row {
    display: grid; grid-template-columns: 1fr 1fr auto;
    gap: 8px; align-items: center; margin-bottom: 8px;
}
.detalle-row .btn-remove-detalle {
    background: none; border: 1.5px solid #ef4444;
    color: #ef4444; border-radius: 8px; padding: 8px 10px;
    cursor: pointer; transition: all .2s;
}
.detalle-row .btn-remove-detalle:hover {
    background: #ef4444; color: #fff;
}
#btn-add-detalle {
    border: 1.5px dashed var(--pub-accent);
    background: transparent; color: var(--pub-accent);
    border-radius: 10px; padding: 8px 18px;
    font-weight: 600; font-size: .84rem;
    cursor: pointer; transition: all .2s;
}
#btn-add-detalle:hover { background: rgba(249,115,22,.08); }

/* ── Submit button ─────────────────────────────── */
.btn-publicar {
    background: linear-gradient(135deg, var(--pub-accent), #ea580c);
    color: #fff; border: none;
    padding: 14px 40px; border-radius: 12px;
    font-size: 1rem; font-weight: 700;
    letter-spacing: .02em;
    cursor: pointer; transition: all .25s;
    display: inline-flex; align-items: center; gap: 8px;
    box-shadow: 0 4px 14px rgba(249,115,22,.35);
}
.btn-publicar:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(249,115,22,.45);
}
.btn-publicar:active { transform: translateY(0); }

/* ── Tips sidebar ──────────────────────────────── */
.tip-card {
    background: var(--pub-surface);
    border: 1px solid var(--pub-border);
    border-radius: var(--pub-radius);
    padding: 20px;
    box-shadow: var(--pub-shadow);
    position: sticky; top: 100px;
}
.tip-card h6 {
    font-weight: 700; font-size: .9rem; margin-bottom: 14px;
    display: flex; align-items: center; gap: 8px;
}
.tip-item {
    display: flex; align-items: flex-start; gap: 10px;
    font-size: .82rem; color: var(--color-text,.374151);
    margin-bottom: 12px; line-height: 1.5;
}
.tip-item i { color: var(--pub-accent); flex-shrink: 0; margin-top: 2px; }

/* ── Pricing notice ────────────────────────────── */
.precio-ayuda {
    background: rgba(249,115,22,.07);
    border: 1px solid rgba(249,115,22,.2);
    border-radius: 8px; padding: 10px 14px;
    font-size: .8rem; color: var(--color-text, #374151);
    margin-top: 8px;
    display: none;
}
.precio-ayuda.visible { display: block; }

@media (max-width: 767px) {
    .tipo-grid { grid-template-columns: 1fr 1fr; }
    .detalle-row { grid-template-columns: 1fr 1fr auto; }
}
</style>
@endpush

@section('contenido')

{{-- Hero ─────────────────────────────────────────────────────────── --}}
<div class="pub-hero">
    <div class="container">
        <div class="pub-hero-content">
            <div class="pub-badge">
                <i class="bi bi-plus-circle-fill"></i> Nueva publicación
            </div>
            <h1>Publica tu herramienta</h1>
            <p>Miles de compradores y arrendatarios activos en toda la república te esperan. Publica en minutos.</p>
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

        <form action="{{ route('publicar.store') }}" method="POST" enctype="multipart/form-data" id="form-publicar">
            @csrf

            <div class="row g-4">

                {{-- ── Columna principal ────────────────────────── --}}
                <div class="col-lg-8">

                    {{-- PASO 1: Información básica --}}
                    <div class="pub-card mb-4">
<div class="pub-card-header" style="color: black;">
                            <div class="step-badge">1</div>
                            <h5>Información básica</h5>
                        </div>
                        <div class="pub-card-body">

                            {{-- Tipo de publicación --}}
                            <div class="mb-4">
                                <label class="form-label">Tipo de publicación *</label>
                                <div class="tipo-grid">
                                    <div>
                                        <input type="radio" name="tipo" id="tipo-renta" value="renta"
                                               class="tipo-option" {{ old('tipo','renta') === 'renta' ? 'checked' : '' }}>
                                        <label for="tipo-renta" class="tipo-label">
                                            <i class="bi bi-clock-history"></i>
                                            Renta
                                        </label>
                                    </div>
                                    <div>
                                        <input type="radio" name="tipo" id="tipo-venta" value="venta"
                                               class="tipo-option" {{ old('tipo') === 'venta' ? 'checked' : '' }}>
                                        <label for="tipo-venta" class="tipo-label">
                                            <i class="bi bi-bag-check-fill"></i>
                                            Venta
                                        </label>
                                    </div>
                                    <div>
                                        <input type="radio" name="tipo" id="tipo-subasta" value="subasta"
                                               class="tipo-option" {{ old('tipo') === 'subasta' ? 'checked' : '' }}>
                                        <label for="tipo-subasta" class="tipo-label">
                                            <i class="bi bi-hammer"></i>
                                            Subasta
                                        </label>
                                    </div>
                                </div>
                                @error('tipo') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            {{-- Título --}}
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título del anuncio *</label>
                                <input type="text" name="titulo" id="titulo"
                                       class="form-control @error('titulo') is-invalid @enderror"
                                       value="{{ old('titulo') }}"
                                       placeholder="Ej: Excavadora CAT 320 disponible para renta mensual"
                                       maxlength="200">
                                @error('titulo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <div class="form-hint">Un título descriptivo recibe hasta 3× más contactos.</div>
                            </div>

                            {{-- Descripción --}}
<div class="mb-3">
    <label for="descripcion" class="form-label">
        Descripción <span style="color:#ef4444">*</span>
    </label>
    <span style="display:block;font-size:.78rem;color:#94a3b8;margin-bottom:6px;">
        <i class="bi bi-info-circle me-1"></i>Mínimo 20 caracteres. Describe estado, características y condiciones de uso.
    </span>
                                <textarea name="descripcion" id="descripcion" rows="5"
                                          class="form-control @error('descripcion') is-invalid @enderror"
                                          placeholder="Describe el estado, características principales, condiciones de uso...">{{ old('descripcion') }}</textarea>
                                @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Categoría + Ubicación --}}
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="categoria_id" class="form-label">
    Categoría <span style="color:#ef4444">*</span>
</label>
                                    <select name="categoria_id" id="categoria_id"
                                            class="form-select @error('categoria_id') is-invalid @enderror">
                                        <option value="">Selecciona una categoría</option>
                                        @foreach($categorias as $cat)
                                            <option value="{{ $cat->id }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('categoria_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="ubicacion" class="form-label">
    Ubicación <span style="color:#ef4444">*</span>
</label>
                                    <input type="text" name="ubicacion" id="ubicacion"
                                           class="form-control @error('ubicacion') is-invalid @enderror"
                                           value="{{ old('ubicacion') }}"
                                           placeholder="Ej: Mérida, Yucatán">
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
                                    <label for="precio" class="form-label">
    Precio (MXN) <span style="color:#ef4444">*</span>
</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="border-radius:10px 0 0 10px; border:1.5px solid var(--pub-border); background:transparent;">$</span>
                                        <input type="number" name="precio" id="precio"
                                               class="form-control @error('precio') is-invalid @enderror"
                                               style="border-radius:0 10px 10px 0;"
                                               value="{{ old('precio') }}"
                                               placeholder="0.00" min="0" step="0.01">
                                        @error('precio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="unidad" class="form-label">
    Unidad / Periodo <span style="color:#ef4444">*</span>
</label>
                                    <select name="unidad" id="unidad" class="form-select" id="select-unidad">
                                        <option value="">Sin especificar</option>
                                        <option value="/día"   {{ old('unidad') == '/día'   ? 'selected':'' }}>/día</option>
                                        <option value="/semana" {{ old('unidad') == '/semana' ? 'selected':'' }}>/semana</option>
                                        <option value="/mes"   {{ old('unidad') == '/mes'   ? 'selected':'' }}>/mes</option>
                                        <option value="precio fijo" {{ old('unidad') == 'precio fijo' ? 'selected':'' }}>precio fijo</option>
                                        <option value="puja actual" {{ old('unidad') == 'puja actual' ? 'selected':'' }}>puja actual</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Fecha fin subasta --}}
                            <div id="subasta-fields" class="mt-3" style="display:none;">
                                <label for="timer_fin" class="form-label">Fecha y hora de cierre de subasta</label>
                                <input type="datetime-local" name="timer_fin" id="timer_fin"
                                       class="form-control @error('timer_fin') is-invalid @enderror"
                                       value="{{ old('timer_fin') }}">
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
    <span style="display:block;font-size:.82rem;color:#94a3b8;margin-bottom:12px;">
        <i class="bi bi-info-circle me-1"></i>Sube <strong>mínimo 3 fotos</strong> del producto desde distintos ángulos. Formatos: JPG, PNG, WEBP · máx. 4 MB c/u · hasta 10 fotos.
    </span>
    <div class="upload-zone" id="upload-zone">
        <input type="file" name="imagenes[]" id="imagenes" accept="image/*" multiple>
        <div class="upload-icon"><i class="bi bi-cloud-arrow-up"></i></div>
        <div style="font-weight:600; margin-bottom:4px;">Arrastra tus fotos aquí</div>
        <div class="form-hint">o haz clic para seleccionar</div>
        <div class="upload-preview" id="upload-preview"></div>
    </div>

    {{-- Contador fotos --}}
    <div id="fotos-counter" style="
        margin-top: 10px; padding: 10px 14px; border-radius: 8px;
        font-size: .82rem; font-weight: 600;
        display: flex; align-items: center; gap: 8px;
        background: #fef2f2; border: 1.5px solid #fca5a5; color: #991b1b;
        transition: all .3s;
    ">
        <i class="bi bi-exclamation-circle-fill" id="fotos-icon"></i>
        <span id="fotos-text">Aún no hay fotos: sube al menos 3 para poder publicar.</span>
    </div>

    @error('imagenes') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror
    @error('imagenes.*') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror
</div>
                    </div>

                    {{-- PASO 4: Detalles técnicos --}}
                    <div class="pub-card mb-4">
                        <div class="pub-card-header">
                            <div class="step-badge">4</div>
                            <h5>Especificaciones técnicas <span style="font-weight:400; color:var(--pub-muted);">(opcional)</span></h5>
                        </div>
                        <div class="pub-card-body">
                            <div id="detalles-container">
                                {{-- Rows dinámicos --}}
                                @if(old('detalles'))
                                    @foreach(old('detalles') as $i => $det)
                                    <div class="detalle-row">
                                        <input type="text" name="detalles[{{ $i }}][clave]"
                                               class="form-control" placeholder="Ej: Marca"
                                               value="{{ $det['clave'] ?? '' }}">
                                        <input type="text" name="detalles[{{ $i }}][valor]"
                                               class="form-control" placeholder="Ej: Caterpillar"
                                               value="{{ $det['valor'] ?? '' }}">
                                        <button type="button" class="btn-remove-detalle" title="Eliminar">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="detalle-row">
                                        <input type="text" name="detalles[0][clave]"
                                               class="form-control" placeholder="Ej: Marca">
                                        <input type="text" name="detalles[0][valor]"
                                               class="form-control" placeholder="Ej: Caterpillar">
                                        <button type="button" class="btn-remove-detalle" title="Eliminar">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" id="btn-add-detalle">
                                <i class="bi bi-plus-lg me-1"></i> Agregar especificación
                            </button>
<div class="form-hint mt-2">Ejemplos: Año de fabricación, Capacidad de carga, Combustible, Peso…</div>
<div id="detalles-counter" style="
    margin-top: 10px;
    padding: 10px 14px;
    border-radius: 8px;
    font-size: .82rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    background: #fef2f2;
    border: 1.5px solid #fca5a5;
    color: #991b1b;
    transition: all .3s;
">
    <i class="bi bi-exclamation-circle-fill" id="counter-icon"></i>
    <span id="counter-text">Faltan especificaciones: agrega al menos 2 para poder publicar.</span>
</div>                        </div>
                    </div>

                    {{-- Botón submit --}}
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <button type="submit" class="btn-publicar">
                            <i class="bi bi-check-circle-fill"></i>
                            Publicar herramienta
                        </button>
                        <a href="{{ route('inicio') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            Cancelar
                        </a>
                    </div>

                </div>

                {{-- ── Sidebar de consejos ───────────────────────── --}}
                <div class="col-lg-4">
                    <div class="tip-card">
                        <h6><i class="bi bi-lightbulb-fill text-warning"></i> Consejos para publicar mejor</h6>

                        <div class="tip-item">
                            <i class="bi bi-camera-fill"></i>
                            <span>Sube al menos 3 fotos desde distintos ángulos. Los anuncios con fotos reciben <strong>5× más contactos</strong>.</span>
                        </div>
                        <div class="tip-item">
                            <i class="bi bi-pencil-square"></i>
                            <span>Incluye marca, modelo y año en el título. Ej: <em>"Andamio multidireccional Layher 6 m 2022"</em>.</span>
                        </div>
                        <div class="tip-item">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>Especifica la ubicación exacta para aparecer en búsquedas locales.</span>
                        </div>
                        <div class="tip-item">
                            <i class="bi bi-list-check"></i>
                            <span>Llena las especificaciones técnicas: los compradores filtran por capacidad, combustible, etc.</span>
                        </div>
                        <div class="tip-item">
                            <i class="bi bi-currency-dollar"></i>
                            <span>Establece un precio competitivo. Puedes editarlo en cualquier momento desde tu panel.</span>
                        </div>

                        <hr style="margin: 16px 0; opacity:.15;">
                        <div style="font-size:.78rem; color:var(--pub-muted);">
                            <i class="bi bi-shield-check text-success me-1"></i>
                            Tu publicación será revisada y activada en menos de 24 horas.
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

    // ── Tipo publicación / subasta ────────────────────────────────────────
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

    // ── Imágenes ──────────────────────────────────────────────────────────
    const inputImg    = document.getElementById('imagenes');
    const preview     = document.getElementById('upload-preview');
    const zone        = document.getElementById('upload-zone');
    const fotosCounter = document.getElementById('fotos-counter');
    const fotosIcon    = document.getElementById('fotos-icon');
    const fotosText    = document.getElementById('fotos-text');
    let selectedFiles  = [];

    function actualizarFotos() {
        const n = selectedFiles.length;
        if (n >= 3) {
            fotosCounter.style.background = '#f0fdf4';
            fotosCounter.style.border     = '1.5px solid #86efac';
            fotosCounter.style.color      = '#166534';
            fotosIcon.className           = 'bi bi-check-circle-fill';
            fotosText.textContent         = `✓ ${n} foto${n > 1 ? 's' : ''} seleccionada${n > 1 ? 's' : ''}. ¡Listo!`;
        } else {
            const faltan = 3 - n;
            fotosCounter.style.background = '#fef2f2';
            fotosCounter.style.border     = '1.5px solid #fca5a5';
            fotosCounter.style.color      = '#991b1b';
            fotosIcon.className           = 'bi bi-exclamation-circle-fill';
            fotosText.textContent         = n === 0
                ? 'Aún no hay fotos: sube al menos 3 para poder publicar.'
                : `${n} foto${n > 1 ? 's' : ''} seleccionada${n > 1 ? 's' : ''} — falta${faltan > 1 ? 'n' : ''} ${faltan} más.`;
        }
        validarFormulario();
    }

    function renderPreviews() {
        preview.innerHTML = '';
        selectedFiles.forEach((f, i) => {
            const url  = URL.createObjectURL(f);
            const item = document.createElement('div');
            item.className = 'preview-item';
            item.innerHTML = `
                <img src="${url}" alt="">
                ${i === 0 ? '<span class="preview-badge">Principal</span>' : ''}
                <button type="button" class="remove-img" data-idx="${i}" title="Eliminar">×</button>
            `;
            preview.appendChild(item);
        });
        syncInput();
        preview.querySelectorAll('.remove-img').forEach(btn => {
            btn.addEventListener('click', () => {
                selectedFiles.splice(+btn.dataset.idx, 1);
                renderPreviews();
                actualizarFotos();
            });
        });
        actualizarFotos();
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

    // ── Especificaciones ──────────────────────────────────────────────────
    const detallesContainer = document.getElementById('detalles-container');
    const btnAdd            = document.getElementById('btn-add-detalle');
    const detallesCounter   = document.getElementById('detalles-counter');
    const counterIcon       = document.getElementById('counter-icon');
    const counterText       = document.getElementById('counter-text');
    let detalleIdx = detallesContainer.querySelectorAll('.detalle-row').length;

    function actualizarContador() {
        const rows = detallesContainer.querySelectorAll('.detalle-row');
        let llenas = 0;
        rows.forEach(row => {
            const inputs = row.querySelectorAll('input[type="text"]');
            if (inputs[0]?.value.trim() && inputs[1]?.value.trim()) llenas++;
        });
        const faltan = Math.max(0, 2 - llenas);
        if (faltan === 0) {
            detallesCounter.style.background = '#f0fdf4';
            detallesCounter.style.border     = '1.5px solid #86efac';
            detallesCounter.style.color      = '#166534';
            counterIcon.className            = 'bi bi-check-circle-fill';
            counterText.textContent          = `✓ ${llenas} especificación${llenas > 1 ? 'es' : ''} registrada${llenas > 1 ? 's' : ''}. ¡Listo!`;
        } else {
            detallesCounter.style.background = '#fef2f2';
            detallesCounter.style.border     = '1.5px solid #fca5a5';
            detallesCounter.style.color      = '#991b1b';
            counterIcon.className            = 'bi bi-exclamation-circle-fill';
            counterText.textContent          = `Faltan ${faltan} especificación${faltan > 1 ? 'es' : ''}: agrega al menos 2 para poder publicar.`;
        }
        validarFormulario();
    }

    function bindDetalleInputs() {
        detallesContainer.querySelectorAll('input[type="text"]').forEach(input => {
            input.removeEventListener('input', actualizarContador);
            input.addEventListener('input', actualizarContador);
        });
    }

    btnAdd.addEventListener('click', () => {
        const row = document.createElement('div');
        row.className = 'detalle-row';
        row.innerHTML = `
            <input type="text" name="detalles[${detalleIdx}][clave]" class="form-control" placeholder="Ej: Capacidad">
            <input type="text" name="detalles[${detalleIdx}][valor]" class="form-control" placeholder="Ej: 500 kg">
            <button type="button" class="btn-remove-detalle" title="Eliminar"><i class="bi bi-trash3"></i></button>
        `;
        detallesContainer.appendChild(row);
        detalleIdx++;
        row.querySelector('.btn-remove-detalle').addEventListener('click', () => {
            row.remove();
            actualizarContador();
        });
        setTimeout(() => { bindDetalleInputs(); actualizarContador(); }, 50);
    });

    detallesContainer.querySelectorAll('.btn-remove-detalle').forEach(btn => {
        btn.addEventListener('click', () => { btn.closest('.detalle-row').remove(); actualizarContador(); });
    });

    // ── Validación global del botón submit ────────────────────────────────
    const btnPublicar = document.querySelector('.btn-publicar');

    function validarFormulario() {
        const fotosOk     = selectedFiles.length >= 3;
        const rows        = detallesContainer.querySelectorAll('.detalle-row');
        let llenas = 0;
        rows.forEach(row => {
            const inputs = row.querySelectorAll('input[type="text"]');
            if (inputs[0]?.value.trim() && inputs[1]?.value.trim()) llenas++;
        });
        const detallesOk  = llenas >= 2;
        const ok          = fotosOk && detallesOk;

        btnPublicar.disabled      = !ok;
        btnPublicar.style.opacity = ok ? '1' : '.5';
        btnPublicar.style.cursor  = ok ? 'pointer' : 'not-allowed';
    }

    // ── Init ──────────────────────────────────────────────────────────────
    bindDetalleInputs();
    actualizarContador();
    actualizarFotos();
});
</script>
@endpush