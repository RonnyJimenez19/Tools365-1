{{-- resources/views/comentarios/index.blade.php --}}
@extends('layouts.app')

@section('titulo_pagina', 'Opiniones de la comunidad – Tools365')

@push('css')
<link rel="stylesheet" href="{{ asset('css/comentario.css') }}">
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