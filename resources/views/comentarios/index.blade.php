{{-- resources/views/comentarios/index.blade.php --}}
@extends('layouts.app')

@section('titulo_pagina', 'Opiniones de la comunidad – Tools365')

@push('css')
<style>
/* ── Hero ─────────────────────────────────────────── */
.opiniones-hero {
    background: linear-gradient(135deg,#1F3A93 0%,#2C3E50 100%);
    padding: 64px 0 48px;
    color: #fff;
    text-align: center;
}
.opiniones-hero h1 { font-size: clamp(26px,5vw,42px); font-weight: 900; }
.opiniones-hero p  { opacity: .75; font-size: 16px; margin-top: 8px; }
.hero-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.12); border-radius: 50px;
    padding: 6px 18px; font-size: 13px; font-weight: 600;
    margin-bottom: 20px; border: 1px solid rgba(255,255,255,.2);
}

/* ── Grid de comentarios ──────────────────────────── */
.comentarios-grid { padding: 56px 0; }

/* ── Formulario ───────────────────────────────────── */
.form-section {
    background: #f8f9fc;
    padding: 56px 0;
    border-top: 1px solid #eef0f8;
}
.form-card {
    background: #fff;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 4px 24px rgba(31,58,147,.08);
    border: 1px solid #eef0f8;
    max-width: 700px;
    margin: 0 auto;
}
.form-card h2 {
    font-weight: 800;
    font-size: 22px;
    color: #1F3A93;
    margin-bottom: 6px;
}
.form-card .subtitle {
    color: #888;
    font-size: 14px;
    margin-bottom: 28px;
}
.form-label-custom {
    font-weight: 600;
    font-size: 14px;
    color: #333;
    margin-bottom: 6px;
    display: block;
}
.form-control-custom {
    width: 100%;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px 16px;
    font-size: 15px;
    color: #1a202c;
    background: #fafbff;
    transition: border-color .2s, box-shadow .2s;
    outline: none;
    font-family: inherit;
}
.form-control-custom:focus {
    border-color: #1F3A93;
    box-shadow: 0 0 0 3px rgba(31,58,147,.10);
    background: #fff;
}
.form-control-custom.is-invalid { border-color: #dc2626; }
.invalid-text {
    color: #dc2626; font-size: 12px; margin-top: 4px;
}

/* ── Star rating input ────────────────────────────── */
.star-rating { display: flex; gap: 6px; flex-direction: row-reverse; justify-content: flex-end; }
.star-rating input { display: none; }
.star-rating label {
    font-size: 28px;
    color: #d1d5db;
    cursor: pointer;
    transition: color .15s, transform .15s;
}
.star-rating input:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label {
    color: #f6c90e;
    transform: scale(1.15);
}

.btn-submit {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #1F3A93 0%, #2563eb 100%);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    margin-top: 8px;
    transition: opacity .2s, transform .15s;
    display: flex; align-items: center; justify-content: center; gap: 8px;
}
.btn-submit:hover { opacity: .92; transform: translateY(-1px); }

/* ── Alert box ────────────────────────────────────── */
.alert-success-custom {
    background: #f0fdf4; border: 1.5px solid #86efac;
    border-radius: 12px; padding: 14px 18px;
    color: #166534; font-size: 14px;
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 24px;
}
.alert-error-custom {
    background: #fef2f2; border: 1.5px solid #fca5a5;
    border-radius: 12px; padding: 14px 18px;
    color: #991b1b; font-size: 14px;
    display: flex; align-items: flex-start; gap: 10px;
    margin-bottom: 24px;
}
</style>
@endpush

@section('contenido')

{{-- ── Hero ──────────────────────────────────────────── --}}
<div class="opiniones-hero">
    <div class="container">
        <div class="hero-badge">
            <i class="bi bi-chat-square-quote-fill"></i>
            Comunidad Tools365
        </div>
        <h1>Lo que dice nuestra comunidad</h1>
        <p>Opiniones reales de usuarios que confían en Tools365 cada día.</p>
    </div>
</div>

{{-- ── Grid de comentarios ───────────────────────────── --}}
<section class="comentarios-grid">
    <div class="container">

        @if($comentarios->count())
            <div class="row g-4 mb-4">
                @foreach($comentarios as $c)
                    <div class="col-md-6 col-lg-4">
                        <x-comentario-card :comentario="$c" />
                    </div>
                @endforeach
            </div>

            {{-- Paginación --}}
            <div class="d-flex justify-content-center mt-2">
                {{ $comentarios->links() }}
            </div>
        @else
            <div class="text-center text-muted py-5">
                <i class="bi bi-chat-dots fs-1 d-block mb-3" style="color:#1F3A93;opacity:.3;"></i>
                <p class="mb-0">Aún no hay opiniones aprobadas.<br>¡Sé el primero en compartir tu experiencia!</p>
            </div>
        @endif

    </div>
</section>

{{-- ── Formulario ────────────────────────────────────── --}}
<section class="form-section">
    <div class="container">
        <div class="form-card">
            <h2><i class="bi bi-pencil-square me-2"></i>Deja tu opinión</h2>
            <p class="subtitle">
                @auth
                    Estás comentando como <strong>{{ Auth::user()->name }}</strong>.
                @else
                    Tu comentario será revisado antes de publicarse.
                @endauth
            </p>

            {{-- Alertas --}}
            @if(session('success'))
                <div class="alert-success-custom">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert-error-custom">
                    <i class="bi bi-exclamation-triangle-fill fs-5 flex-shrink-0"></i>
                    <div>
                        @foreach($errors->all() as $e)
                            <div>{{ $e }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <form action="{{ route('comentarios.store') }}" method="POST" novalidate>
                @csrf

                {{-- Nombre (solo invitados) --}}
                @guest
                <div class="mb-4">
                    <label class="form-label-custom" for="autor_nombre">
                        Tu nombre <span style="color:#dc2626">*</span>
                    </label>
                    <input type="text" id="autor_nombre" name="autor_nombre"
                           class="form-control-custom {{ $errors->has('autor_nombre') ? 'is-invalid' : '' }}"
                           value="{{ old('autor_nombre') }}"
                           placeholder="Ej. Juan Pérez"
                           required maxlength="100">
                    @error('autor_nombre')
                        <div class="invalid-text"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label-custom" for="autor_email">
                        Correo (opcional)
                    </label>
                    <input type="email" id="autor_email" name="autor_email"
                           class="form-control-custom {{ $errors->has('autor_email') ? 'is-invalid' : '' }}"
                           value="{{ old('autor_email') }}"
                           placeholder="tucorreo@ejemplo.com"
                           maxlength="180">
                    @error('autor_email')
                        <div class="invalid-text"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                    @enderror
                </div>
                @endguest

                {{-- Calificación --}}
<div class="mb-4">
    <label class="form-label-custom">
        Calificación <span style="color:#dc2626">*</span>
    </label>
    <span style="display:block; font-size:13px; color:#888; margin-bottom:8px;">
        <i class="bi bi-info-circle me-1"></i>Haz clic en las estrellas para seleccionar tu calificación.
    </span>
    <div class="star-rating">
                        @for($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star{{ $i }}" name="calificacion"
                                   value="{{ $i }}"
                                   {{ old('calificacion', 5) == $i ? 'checked' : '' }}
                                   required>
                            <label for="star{{ $i }}">&#9733;</label>
                        @endfor
                    </div>
                    @error('calificacion')
                        <div class="invalid-text mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                    @enderror
                </div>

                {{-- Comentario --}}
                <div class="mb-5">
                    <label class="form-label-custom" for="cuerpo">
                        Tu opinión <span style="color:#dc2626">*</span>
                    </label>
                    <textarea id="cuerpo" name="cuerpo" rows="4"
                              class="form-control-custom {{ $errors->has('cuerpo') ? 'is-invalid' : '' }}"
                              placeholder="Cuéntanos tu experiencia con Tools365..."
                              required minlength="10" maxlength="1000">{{ old('cuerpo') }}</textarea>
                    @error('cuerpo')
                        <div class="invalid-text"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">
                    <i class="bi bi-send-fill"></i>
                    Enviar opinión
                </button>
            </form>
        </div>
    </div>
</section>

@endsection