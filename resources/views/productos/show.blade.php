@extends('layouts.app')

@section('titulo_pagina', $producto->titulo . ' - Tools365')

@push('css')
<style>
/* ══════════════════════════════════════════════
   PÁGINA DE DETALLE DE PRODUCTO
══════════════════════════════════════════════ */

/* ── Hero breadcrumb ── */
.detalle-breadcrumb {
    background: var(--color-surface, #f8f9fa);
    border-bottom: 1px solid var(--color-border, #dee2e6);
    padding: 0.65rem 0;
    font-size: 0.82rem;
}
.detalle-breadcrumb a {
    color: var(--color-accent, #0d6efd);
    text-decoration: none;
}
.detalle-breadcrumb a:hover { text-decoration: underline; }
.detalle-breadcrumb .separator { margin: 0 0.4rem; color: #aaa; }

/* ── Layout principal ── */
.detalle-layout {
    display: grid;
    grid-template-columns: 1fr 420px;
    gap: 2.5rem;
    padding: 2.5rem 0 3rem;
}
@media (max-width: 991px) {
    .detalle-layout { grid-template-columns: 1fr; gap: 1.5rem; }
}

/* ── Galería ── */
.galeria-principal {
    border-radius: 14px;
    overflow: hidden;
    background: #000;
    aspect-ratio: 4/3;
    position: relative;
}
.galeria-principal img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: opacity 0.25s ease;
}
.galeria-thumbs {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.75rem;
    overflow-x: auto;
    padding-bottom: 4px;
}
.galeria-thumbs::-webkit-scrollbar { height: 4px; }
.galeria-thumbs::-webkit-scrollbar-thumb { background: #ccc; border-radius: 2px; }

.thumb-btn {
    flex-shrink: 0;
    width: 72px;
    height: 56px;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid transparent;
    cursor: pointer;
    padding: 0;
    background: none;
    transition: border-color 0.15s;
}
.thumb-btn img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.thumb-btn.active,
.thumb-btn:hover { border-color: var(--color-accent, #0d6efd); }

/* Placeholder sin imagen */
.galeria-placeholder {
    width: 100%;
    aspect-ratio: 4/3;
    border-radius: 14px;
    background: linear-gradient(135deg, #e8eaf6, #c5cae9);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 5rem;
    color: #9fa8da;
}

/* ── Panel info (derecha) ── */
.info-panel {
    display: flex;
    flex-direction: column;
    gap: 1.1rem;
}

.info-badges { display: flex; gap: 0.4rem; flex-wrap: wrap; }

.info-titulo {
    font-size: 1.55rem;
    font-weight: 700;
    line-height: 1.25;
    color: var(--color-text, #212529);
    margin: 0;
}

/* Precio */
.precio-bloque { display: flex; align-items: baseline; gap: 0.6rem; flex-wrap: wrap; }
.precio-actual {
    font-size: 2rem;
    font-weight: 800;
    color: var(--color-accent, #0d6efd);
    line-height: 1;
}
.precio-unidad {
    font-size: 0.9rem;
    color: #6c757d;
    font-weight: 500;
}
.precio-original-tachado {
    font-size: 1.1rem;
    color: #aaa;
    text-decoration: line-through;
}
.oferta-badge {
    background: #dc3545;
    color: #fff;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
    letter-spacing: 0.02em;
}

/* Timer subasta */
.timer-bloque {
    background: linear-gradient(135deg, #fff3cd, #ffeaa7);
    border: 1.5px solid #ffc107;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    font-weight: 600;
    color: #856404;
    font-size: 0.9rem;
}
.timer-bloque i { font-size: 1.1rem; }

/* Ubicación */
.ubicacion-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.85rem;
    color: #6c757d;
}

/* Descripción */
.descripcion-bloque {
    background: var(--color-surface, #f8f9fa);
    border-radius: 10px;
    padding: 1rem 1.1rem;
    font-size: 0.93rem;
    line-height: 1.65;
    color: var(--color-text, #212529);
    border: 1px solid var(--color-border, #dee2e6);
}
.descripcion-label {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #999;
    margin-bottom: 0.4rem;
}

/* Detalles / especificaciones */
.specs-tabla {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.88rem;
}
.specs-tabla tr:not(:last-child) td {
    border-bottom: 1px solid var(--color-border, #dee2e6);
}
.specs-tabla td { padding: 0.45rem 0.5rem; vertical-align: middle; }
.specs-tabla td:first-child {
    color: #6c757d;
    font-weight: 600;
    white-space: nowrap;
    width: 42%;
}
.specs-tabla td:last-child { color: var(--color-text, #212529); }

/* Sección publicado por */
.vendedor-bloque {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.85rem 1rem;
    background: var(--color-surface, #f8f9fa);
    border-radius: 10px;
    border: 1px solid var(--color-border, #dee2e6);
    font-size: 0.88rem;
}
.vendedor-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--color-accent, #0d6efd);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1rem;
    font-weight: 700;
    flex-shrink: 0;
}

/* ── CTA Buttons ── */
.cta-group { display: flex; gap: 0.6rem; flex-direction: column; }
.btn-cta-primary {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.8rem 1.2rem;
    border-radius: 10px;
    font-weight: 700;
    font-size: 1rem;
    border: none;
    cursor: pointer;
    transition: filter 0.15s, transform 0.1s;
    text-decoration: none;
}
.btn-cta-primary:hover { filter: brightness(1.08); transform: translateY(-1px); }
.btn-cta-primary:active { transform: translateY(0); }

/* ── Relacionados ── */
.relacionados-section { padding: 2.5rem 0 3rem; border-top: 1px solid var(--color-border, #dee2e6); }
.relacionados-titulo {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 1.25rem;
    color: var(--color-text, #212529);
}

/* Dark mode */
[data-theme="dark"] .detalle-breadcrumb { background: #1a1a2e; border-color: #333; }
[data-theme="dark"] .descripcion-bloque,
[data-theme="dark"] .vendedor-bloque { background: #1e1e2e; border-color: #333; }
[data-theme="dark"] .galeria-placeholder { background: linear-gradient(135deg, #1e1e3a, #2a2a4a); }
[data-theme="dark"] .timer-bloque { background: #2a2000; border-color: #8a6d00; color: #ffd95e; }
[data-theme="dark"] .info-titulo { color: #f1f1f1; }
[data-theme="dark"] .specs-tabla td:last-child { color: #e0e0e0; }
[data-theme="dark"] .relacionados-titulo { color: #f1f1f1; }
[data-theme="dark"] .relacionados-section { border-color: #333; }
</style>
@endpush

@section('contenido')



{{-- ── Layout principal ── --}}
<div class="container">
    <div class="detalle-layout">

        {{-- ══ COLUMNA IZQUIERDA: Galería ══ --}}
        <div>
            @if($producto->imagenes->isEmpty())
                <div class="galeria-placeholder">
                    <i class="bi bi-tools"></i>
                </div>
            @else
                {{-- Imagen principal --}}
                <div class="galeria-principal">
                    <img id="imgPrincipal"
                         src="{{ asset($producto->imagenes->first()->ruta) }}"
                         alt="{{ $producto->imagenes->first()->descripcion ?? $producto->titulo }}">
                </div>

                {{-- Thumbnails (solo si hay más de 1) --}}
                @if($producto->imagenes->count() > 1)
                    <div class="galeria-thumbs">
                        @foreach($producto->imagenes as $i => $img)
                            <button class="thumb-btn {{ $i === 0 ? 'active' : '' }}"
                                    onclick="cambiarImagen(this, '{{ asset($img->ruta) }}', '{{ $img->descripcion ?? $producto->titulo }}')"
                                    title="{{ $img->descripcion ?? 'Imagen ' . ($i + 1) }}">
                                <img src="{{ asset($img->ruta) }}" alt="{{ $img->descripcion ?? '' }}">
                            </button>
                        @endforeach
                    </div>
                @endif
            @endif

            {{-- Detalles / Especificaciones --}}
            @if($producto->detalles->isNotEmpty())
                <div class="mt-4">
                    <div class="descripcion-label"><i class="bi bi-list-ul me-1"></i>Especificaciones</div>
                    <table class="specs-tabla">
                        @foreach($producto->detalles as $detalle)
                            <tr>
                                <td>
                                    @if($detalle->icono)
                                        <i class="bi {{ $detalle->icono }} me-1"></i>
                                    @endif
                                    {{ $detalle->clave }}
                                </td>
                                <td>{{ $detalle->valor }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endif
        </div>

        {{-- ══ COLUMNA DERECHA: Info + CTA ══ --}}
        <div class="info-panel">

            {{-- Badges --}}
            <div class="info-badges">
                @php
                    [$badge, $badgeTipo] = match($producto->tipo) {
                        'renta'   => ['🕐 Renta',   'primary'],
                        'venta'   => ['🛒 Venta',   'success'],
                        'subasta' => ['🔨 Subasta', 'danger'],
                        default   => [$producto->tipo, 'secondary'],
                    };
                @endphp
                <span class="badge bg-{{ $badgeTipo }}-subtle text-{{ $badgeTipo }} fs-6 px-3 py-2">
                    {{ $badge }}
                </span>
                @if($producto->categoria)
                    <span class="badge bg-secondary-subtle text-secondary fs-6 px-3 py-2">
                        <i class="bi bi-grid me-1"></i>{{ $producto->categoria->nombre }}
                    </span>
                @endif
            </div>

            {{-- Título --}}
            <h1 class="info-titulo">{{ $producto->titulo }}</h1>

            {{-- Precio --}}
            <div class="precio-bloque">
                <span class="precio-actual">${{ number_format($producto->precio, 0, '.', ',') }}</span>
                @if($producto->unidad)
                    <span class="precio-unidad">{{ $producto->unidad }}</span>
                @endif
                @if($producto->ofertaVigente() && $producto->precio_original)
                    <span class="precio-original-tachado">${{ number_format($producto->precio_original, 0, '.', ',') }}</span>
                    <span class="oferta-badge">-{{ $producto->descuento_porcentaje }}%</span>
                @endif
            </div>

            @auth
{{-- ─── Agregar al carrito ─────────────────────────────── --}}
<div class="mt-3">
    @if($producto->estado !== 'activo')
        <div class="alert alert-warning py-2">
            <i class="bi bi-exclamation-triangle me-1"></i>
            Este producto no está disponible actualmente.
        </div>

    @elseif($producto->tipo === 'renta')
        {{-- Formulario para RENTA --}}
        <form method="POST" action="{{ route('carrito.store') }}" id="form-agregar-carrito">
            @csrf
            <input type="hidden" name="producto_id" value="{{ $producto->id }}">
            <div class="row g-2 mb-3">
                <div class="col-sm-5">
                    <label class="form-label fw-semibold" style="font-size:.85rem;">Fecha inicio</label>
                    <input type="date" name="fecha_inicio" class="form-control form-control-sm"
                           min="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-sm-5">
                    <label class="form-label fw-semibold" style="font-size:.85rem;">Fecha fin</label>
                    <input type="date" name="fecha_fin" class="form-control form-control-sm" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-cart-plus me-2"></i>Agregar al carrito
            </button>
        </form>

    @elseif($producto->tipo === 'venta')
        {{-- Formulario para COMPRA --}}
        <form method="POST" action="{{ route('carrito.store') }}" id="form-agregar-carrito">
            @csrf
            <input type="hidden" name="producto_id" value="{{ $producto->id }}">
            <div class="d-flex align-items-center gap-3 mb-3">
                <label class="fw-semibold" style="font-size:.85rem;">Cantidad</label>
                <div class="input-group" style="width:130px;">
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                            onclick="let i=document.getElementById('qty-show');i.value=Math.max(1,+i.value-1)">−</button>
                    <input type="number" id="qty-show" name="cantidad"
                           value="1" min="1" max="99"
                           class="form-control form-control-sm text-center">
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                            onclick="let i=document.getElementById('qty-show');i.value=Math.min(99,+i.value+1)">+</button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-cart-plus me-2"></i>Agregar al carrito
            </button>
        </form>

    @elseif($producto->tipo === 'subasta')
        {{-- Subasta: botón va a la lógica de oferta, no al carrito --}}
        <a href="#" class="btn btn-warning w-100">
            <i class="bi bi-hammer me-2"></i>Hacer una oferta
        </a>
    @endif

    {{-- Feedback de sesión --}}
    @if(session('success'))
        <div class="alert alert-success mt-2 py-2">
            <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mt-2 py-2">
            <i class="bi bi-exclamation-circle me-1"></i>{{ session('error') }}
        </div>
    @endif
</div>
@else
{{-- Usuario no autenticado --}}
<a href="{{ route('login') }}" class="btn btn-outline-primary w-100 mt-3">
    <i class="bi bi-person me-2"></i>Inicia sesión para agregar al carrito
</a>
@endauth

            {{-- Etiqueta oferta --}}
            @if($producto->ofertaVigente() && $producto->oferta_etiqueta)
                <div class="alert alert-danger py-2 px-3 mb-0" style="border-radius:8px; font-size:.88rem;">
                    <i class="bi bi-tag-fill me-1"></i>{{ $producto->oferta_etiqueta }}
                </div>
            @endif

            {{-- Timer subasta --}}
            @if($producto->tipo === 'subasta' && $producto->timer_fin)
                <div class="timer-bloque">
                    <i class="bi bi-alarm"></i>
                    <div>
                        <div style="font-size:.75rem; font-weight:600; letter-spacing:.04em; text-transform:uppercase;">Tiempo restante</div>
                        <div id="countdown" style="font-size:1.1rem;">
                            {{ \Carbon\Carbon::parse($producto->timer_fin)->locale('es')->diffForHumans(['parts' => 3]) }}
                        </div>
                    </div>
                </div>
            @endif

            {{-- Ubicación --}}
            @if($producto->ubicacion)
                <div class="ubicacion-tag">
                    <i class="bi bi-geo-alt-fill text-danger"></i>
                    {{ $producto->ubicacion }}
                </div>
            @endif

            {{-- Descripción --}}
            @if($producto->descripcion)
                <div class="descripcion-bloque">
                    <div class="descripcion-label"><i class="bi bi-file-text me-1"></i>Descripción</div>
                    {{ $producto->descripcion }}
                </div>
            @endif

            {{-- Publicado por --}}
            @if($producto->user)
                <div class="vendedor-bloque">
                    <div class="vendedor-avatar">
                        {{ strtoupper(substr($producto->user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-size:.75rem; color:#999; font-weight:600; text-transform:uppercase; letter-spacing:.04em;">Publicado por</div>
                        <div style="font-weight:600;">{{ $producto->user->name ?? 'Usuario' }}</div>
                        <div style="font-size:.78rem; color:#999;">
                            {{ $producto->created_at->locale('es')->diffForHumans() }}
                        </div>
                    </div>
                </div>
            @endif

            {{-- CTA --}}
            <div class="cta-group">
                @if($producto->tipo === 'subasta')
                    <a href="#" class="btn-cta-primary text-white"
                       style="background: linear-gradient(135deg, #dc3545, #b02a37);">
                        <i class="bi bi-hammer"></i> Realizar oferta
                    </a>
                @elseif($producto->tipo === 'renta')
                    <a href="#" class="btn-cta-primary text-white"
                       style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
                        <i class="bi bi-calendar-check"></i> Solicitar renta
                    </a>
                @else
                    <a href="#" class="btn-cta-primary text-white"
                       style="background: linear-gradient(135deg, #198754, #146c43);">
                        <i class="bi bi-bag-check"></i> Comprar ahora
                    </a>
                @endif

               <a href="https://wa.me/529991234567?text=Hola,%20me%20interesa%20este%20producto" target="_blank"
                class="btn-cta-primary"
                style="background: var(--color-surface, #f8f9fa); border: 1.5px solid var(--color-border, #dee2e6); color: var(--color-text, #212529);">
                <i class="bi bi-whatsapp" style="color:#25d366;"></i>Contactar por WhatsApp</a>

            {{-- Compartir --}}
            <div style="font-size:.82rem; color:#999; text-align:center;">
                <i class="bi bi-share me-1"></i>
                <a href="#" onclick="navigator.clipboard.writeText(window.location.href); this.textContent='¡Enlace copiado!'; setTimeout(()=>this.textContent='Copiar enlace',2000); return false;"
                   style="color:inherit;">Copiar enlace</a>
            </div>

        </div>
    </div>
</div>

{{-- ── Productos relacionados ── --}}
@if($relacionados->isNotEmpty())
    <div class="container">
        <div class="relacionados-section">
            <div class="relacionados-titulo">
                <i class="bi bi-grid me-2"></i>También te puede interesar
            </div>
            <div class="row g-3">
                @foreach($relacionados as $rel)
                    <x-product-card :producto="$rel" />
                @endforeach
            </div>
        </div>
    </div>
@endif

@endsection

@push('scripts')
<script>
// ── Galería: cambiar imagen principal ──
function cambiarImagen(btn, src, alt) {
    const img = document.getElementById('imgPrincipal');
    if (img) {
        img.style.opacity = '0';
        setTimeout(() => {
            img.src = src;
            img.alt = alt;
            img.style.opacity = '1';
        }, 150);
    }
    document.querySelectorAll('.thumb-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}
</script>
@endpush