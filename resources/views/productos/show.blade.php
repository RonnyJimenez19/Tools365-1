@extends('layouts.app')

@section('titulo_pagina', $producto->titulo . ' - Tools365')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/show_producto.css') }}">
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
                        'subasta' => ['🔨 Subasta', 'info'],
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

            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-cart-plus me-2"></i>Agregar al carrito
            </button>
        </form>

@elseif($producto->tipo === 'subasta')
        @php
            $pujaActual      = $producto->pujaActual();
            $pujaMinima      = $producto->pujaMinima();
            $subastaActiva   = $producto->subastaActiva();
            $esDueno         = auth()->id() === $producto->user_id;
            $totalPujas      = $producto->pujas()->count();
            $miMejorPuja     = $producto->pujas()->where('user_id', auth()->id())->max('monto');
            $voyGanando      = $totalPujas > 0
                            && $producto->pujas()->orderByDesc('monto')->value('user_id') === auth()->id();
        @endphp
 
        {{-- ── Info de la subasta ── --}}
        <div class="subasta-info-box">
            <div class="row g-2 text-center">
                <div class="col-4">
                    <div class="si-label">Puja actual</div>
                    <div class="si-val text-primary" id="puja-actual-val">
                        ${{ number_format($pujaActual, 0, '.', ',') }}
                    </div>
                </div>
                <div class="col-4">
                    <div class="si-label">Mín. para pujar</div>
                    <div class="si-val text-success">${{ number_format($pujaMinima, 0, '.', ',') }}</div>
                </div>
                <div class="col-4">
                    <div class="si-label">Pujas</div>
                    <div class="si-val" id="total-pujas-val">{{ $totalPujas }}</div>
                </div>
            </div>
 
            @if($miMejorPuja)
                <div class="mt-2 text-center" style="font-size:.82rem; color:#6c757d;">
                    Tu mejor puja:
                    <strong>${{ number_format($miMejorPuja, 0, '.', ',') }}</strong>
                    &nbsp;
                    @if($voyGanando)
                        <span class="badge bg-success-subtle text-success">🏆 Vas ganando</span>
                    @else
                        <span class="badge bg-danger-subtle text-danger">⚠️ Fuiste superado</span>
                    @endif
                </div>
            @endif
        </div>
 
        @if($esDueno)
            <div class="alert alert-info py-2 mt-2" style="font-size:.88rem;">
                <i class="bi bi-info-circle me-1"></i>
                Esta es tu subasta. Gestiona las pujas desde
                <a href="{{ route('subastas.index') }}">Mis Subastas</a>.
            </div>
 
        @elseif(!$subastaActiva)
            <div class="alert alert-warning py-2 mt-2" style="font-size:.88rem;">
                <i class="bi bi-clock-history me-1"></i>
                @if($producto->estado === 'vendido')
                    Esta subasta ya fue adjudicada.
                @else
                    El tiempo de esta subasta ha expirado.
                @endif
            </div>
 
        @else
            {{-- ── Formulario de puja ── --}}
            <form method="POST"
                  action="{{ route('subastas.pujar', $producto) }}"
                  id="form-puja"
                  class="mt-2">
                @csrf
                <div class="puja-input-group">
                    <div class="puja-prefix">$</div>
                    <input type="number"
                           name="monto"
                           id="monto-puja"
                           class="puja-input form-control @error('monto') is-invalid @enderror"
                           placeholder="{{ number_format($pujaMinima, 0) }}"
                           min="{{ $pujaMinima }}"
                           step="1"
                           required>
                    <div class="puja-suffix">MXN</div>
                </div>
                <div class="puja-hint">
                    <i class="bi bi-info-circle me-1"></i>
                    Mínimo: <strong>${{ number_format($pujaMinima, 0, '.', ',') }}</strong>
                    (puja actual + ${{ number_format($producto->incremento_minimo ?? 50, 0) }} de incremento)
                </div>
 
                @error('monto')
                    <div class="alert alert-danger py-2 mt-1" style="font-size:.85rem;">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
 
                <button type="submit" class="btn btn-warning w-100 mt-2 fw-bold"
                        onclick="return validarPuja({{ $pujaMinima }})">
                    <i class="bi bi-hammer me-2"></i>Hacer oferta
                </button>
            </form>
        @endif
 
        {{-- Historial de pujas (accordion) --}}
        <div class="accordion mt-3" id="accordionPujas">
            <div class="accordion-item border-0 rounded-3 overflow-hidden"
                 style="border: 1px solid var(--color-border, #dee2e6) !important;">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed py-2 fw-semibold"
                            style="font-size:.88rem;"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapsePujas"
                            onclick="cargarPujasInline({{ $producto->id }})">
                        <i class="bi bi-list-ol me-2"></i>
                        Ver historial de pujas ({{ $totalPujas }})
                    </button>
                </h2>
                <div id="collapsePujas" class="accordion-collapse collapse">
                    <div class="accordion-body p-0" id="pujas-inline-body">
                        <div class="text-center py-3">
                            <div class="spinner-border spinner-border-sm text-primary"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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

// Validación client-side de puja
function validarPuja(minimo) {
    const campo = document.getElementById('monto-puja');
    if (!campo) return true;

    const val = parseFloat(campo.value);

    if (isNaN(val) || val < minimo) {
        campo.classList.add('is-invalid');

        let msg = document.getElementById('puja-error-msg');
        if (!msg) {
            msg = document.createElement('div');
            msg.id = 'puja-error-msg';
            msg.className = 'alert alert-danger py-2 mt-1';
            msg.style.fontSize = '.85rem';

            campo.closest('form').appendChild(msg);
        }

        msg.innerHTML = `
            <i class="bi bi-exclamation-circle me-1"></i>
            La puja mínima es <strong>$${minimo.toLocaleString('es-MX')}</strong> MXN.
        `;

        campo.focus();
        return false;
    }

    campo.classList.remove('is-invalid');
    const msg = document.getElementById('puja-error-msg');
    if (msg) msg.remove();

    return true;
}

document.addEventListener('DOMContentLoaded', () => {
    const campo = document.getElementById('monto-puja');

    if (campo) {
        campo.addEventListener('input', () => {
            campo.classList.remove('is-invalid');
            const msg = document.getElementById('puja-error-msg');
            if (msg) msg.remove();
        });
    }
});

let historialCargado = false;

async function cargarPujasInline(productoId) {
    if (historialCargado) return;

    const body = document.getElementById('pujas-inline-body');
    if (!body) return;

    try {
        const res = await fetch(`/subastas/${productoId}/historial`);
        const data = await res.json();

        if (!data.pujas.length) {
            body.innerHTML = `
                <p class="text-center text-muted py-3 mb-0">
                    Aún no hay pujas registradas.
                </p>
            `;
            historialCargado = true;
            return;
        }

        let html = '';

        data.pujas.forEach((p, i) => {
            html += `
                <div class="puja-fila ${i === 0 ? 'fw-bold' : ''}">
                    <div>
                        ${i === 0 ? '🏆 ' : ''}
                        <span class="pf-nombre">${p.nombre}</span>
                        <div class="pf-fecha">${p.fecha}</div>
                    </div>
                    <div class="pf-monto">
                        $${Number(p.monto).toLocaleString('es-MX')}
                    </div>
                </div>
            `;
        });

        body.innerHTML = html;
        historialCargado = true;

    } catch (e) {
        body.innerHTML = `
            <p class="text-danger text-center py-3 mb-0">
                Error al cargar historial.
            </p>
        `;
    }
}
</script>
@endpush