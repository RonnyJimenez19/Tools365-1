@extends('layouts.dashboard')

@section('titulo_pagina', 'Pagar subasta ganada – Tools365')
@section('topbar_title', 'Pagar subasta')
@section('topbar_breadcrumb', $producto->titulo)

@push('css')
<link rel="stylesheet" href="{{ asset('css/pagar.css') }}">
@endpush

@section('contenido')
<div class="container py-3">

    {{-- Timer 24h --}}
    @php
        $segundos = $pedido->segundosRestantes();
        $horas    = floor($segundos / 3600);
        $critico  = $horas < 2;
    @endphp
    <div class="timer-24h {{ $critico ? 'critico' : '' }}" id="timer-24h-box">
        <div class="timer-24h-icon">{{ $critico ? '🚨' : '⏰' }}</div>
        <div>
            <div class="timer-24h-label">Tiempo restante para pagar</div>
            <div class="timer-24h-val" id="timer-24h-val">Calculando…</div>
            <div class="timer-24h-desc">
                Si el tiempo expira, pierdes la adjudicación y se notifica al vendedor.
            </div>
        </div>
    </div>

    <div class="pago-sub-layout">

        {{-- ══ COLUMNA IZQUIERDA: Producto + Formulario ══ --}}
        <div>

            {{-- Producto ganado --}}
            <div class="prod-ganado-card">
                <div class="prod-ganado-inner">
                    <div class="prod-ganado-img">
                        @if($producto->imagenes->isNotEmpty())
                            <img src="{{ asset($producto->imagenes->first()->ruta) }}" alt="{{ $producto->titulo }}">
                        @else
                            <i class="bi bi-tools"></i>
                        @endif
                    </div>
                    <div>
                        <div class="prod-ganado-badge">
                            🏆 Subasta ganada
                        </div>
                        <div class="prod-ganado-titulo">{{ $producto->titulo }}</div>
                        <div class="prod-ganado-precio">${{ number_format($pedido->total, 2) }} MXN</div>
                        <div style="font-size:.78rem; color:#6c757d; margin-top:.2rem;">
                            Vendedor: {{ $producto->user->name ?? 'N/A' }}
                            · {{ $producto->ubicacion }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Formulario de pago --}}
            <div class="pago-panel">

                <form method="POST" action="{{ route('subastas.pagar.procesar', $pedido) }}" id="form-pago-subasta">
                    @csrf

                    {{-- ── Tarjetas guardadas ── --}}
                    @if($tarjetas->isNotEmpty())
                        <div class="pago-section-label">
                            <i class="bi bi-credit-card me-1"></i>Tarjetas guardadas
                        </div>

                        @foreach($tarjetas as $t)
                            <label class="tarjeta-saved">
                                <input type="radio"
                                       name="tarjeta_guardada_id"
                                       value="{{ $t->id }}"
                                       {{ $t->predeterminada ? 'checked' : '' }}
                                       onchange="toggleNuevaTarjeta(false)">
                                <span class="tarjeta-icon">
                                    @if($t->tipo === 'visa') 💳
                                    @elseif($t->tipo === 'mastercard') 🟠
                                    @else 💳
                                    @endif
                                </span>
                                <div class="tarjeta-info">
                                    <div class="tarjeta-numero">
                                        {{ strtoupper($t->tipo) }} •••• {{ $t->ultimos_cuatro }}
                                        @if($t->predeterminada)
                                            <span class="badge bg-primary-subtle text-primary ms-1" style="font-size:.65rem;">Predeterminada</span>
                                        @endif
                                    </div>
                                    <div class="tarjeta-det">{{ $t->titular }} · {{ $t->exp_mes }}/{{ $t->exp_anio }}</div>
                                </div>
                            </label>
                        @endforeach

                        <div class="mt-2 mb-3">
                            <label class="tarjeta-saved">
                                <input type="radio" name="tarjeta_guardada_id" value=""
                                       onchange="toggleNuevaTarjeta(true)">
                                <span class="tarjeta-icon">➕</span>
                                <div class="tarjeta-info">
                                    <div class="tarjeta-numero">Usar nueva tarjeta</div>
                                </div>
                            </label>
                        </div>

                        <hr>
                    @endif

                    {{-- ── Datos de tarjeta nueva ── --}}
                    <div id="nueva-tarjeta-form" {{ $tarjetas->isNotEmpty() ? 'style=display:none' : '' }}>
                        <div class="pago-section-label">
                            <i class="bi bi-credit-card-2-front me-1"></i>Datos de tarjeta
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Número de tarjeta</label>
                            <input type="text" name="numero" class="input-card"
                                   placeholder="1234 5678 9012 3456" maxlength="23"
                                   oninput="formatearNumero(this)">
                            @error('numero')<div class="text-danger mt-1" style="font-size:.82rem;">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Titular</label>
                            <input type="text" name="titular" class="input-card"
                                   placeholder="NOMBRE APELLIDO" style="text-transform:uppercase;"
                                   value="{{ old('titular') }}">
                            @error('titular')<div class="text-danger mt-1" style="font-size:.82rem;">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-4">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">Mes exp.</label>
                                <input type="number" name="exp_mes" class="input-card"
                                       placeholder="MM" min="1" max="12" value="{{ old('exp_mes') }}">
                            </div>
                            <div class="col-4">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">Año exp.</label>
                                <input type="number" name="exp_anio" class="input-card"
                                       placeholder="{{ date('Y') }}" min="{{ date('Y') }}" value="{{ old('exp_anio') }}">
                            </div>
                            <div class="col-4">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">CVV</label>
                                <input type="password" name="cvv" class="input-card"
                                       placeholder="•••" maxlength="4">
                            </div>
                        </div>

                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="guardar_tarjeta"
                                   id="guardar_tarjeta" value="1">
                            <label class="form-check-label" style="font-size:.85rem;" for="guardar_tarjeta">
                                Guardar esta tarjeta para futuras compras
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="predeterminada"
                                   id="predeterminada" value="1">
                            <label class="form-check-label" style="font-size:.85rem;" for="predeterminada">
                                Marcar como predeterminada
                            </label>
                        </div>
                    </div>

                    {{-- Errores globales --}}
                    @if($errors->any() && !$errors->has('monto'))
                        <div class="alert alert-danger py-2" style="font-size:.85rem;">
                            <i class="bi bi-exclamation-circle me-1"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold fs-5 mt-1"
                            id="btn-pagar-subasta">
                        <i class="bi bi-shield-check me-2"></i>
                        Confirmar pago — ${{ number_format($pedido->total, 2) }} MXN
                    </button>

                    <div class="text-center mt-2" style="font-size:.78rem; color:#999;">
                        <i class="bi bi-lock-fill me-1"></i>Pago seguro simulado · Entorno de pruebas
                    </div>
                </form>
            </div>
        </div>

        {{-- ══ COLUMNA DERECHA: Resumen ══ --}}
        <div class="resumen-lateral">
            <div class="pago-section-label">Resumen del pedido</div>

            <div class="resumen-fila">
                <span>Pedido</span>
                <span class="fw-bold">{{ $pedido->folio }}</span>
            </div>
            <div class="resumen-fila">
                <span>Producto</span>
                <span class="fw-semibold" style="max-width:160px; text-align:right; word-break:break-word;">
                    {{ Str::limit($producto->titulo, 40) }}
                </span>
            </div>
            <div class="resumen-fila">
                <span>Tipo</span>
                <span>🔨 Subasta ganada</span>
            </div>
            <div class="resumen-fila">
                <span>Vendedor</span>
                <span>{{ $producto->user->name ?? 'N/A' }}</span>
            </div>
            <div class="resumen-fila">
                <span>Subtotal</span>
                <span>${{ number_format($pedido->subtotal, 2) }}</span>
            </div>
            <div class="resumen-total-fila">
                <span>Total</span>
                <span class="val">${{ number_format($pedido->total, 2) }} MXN</span>
            </div>

            <div class="mt-3 p-2 rounded-3 text-center"
                 style="background:#f0fdf4; border:1px solid #bbf7d0; font-size:.8rem; color:#15803d;">
                <i class="bi bi-check-circle-fill me-1"></i>
                Puja ganadora confirmada por el vendedor
            </div>

            <a href="{{ route('subastas.index') }}"
               class="btn btn-outline-secondary w-100 mt-3 btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Volver a Mis Subastas
            </a>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
// ── Countdown 24h ────────────────────────────────────
const PAGO_LIMITE = new Date('{{ $pedido->pago_limite->toISOString() }}');
const timerBox    = document.getElementById('timer-24h-box');
const timerVal    = document.getElementById('timer-24h-val');

function formatTiempo(seg) {
    if (seg <= 0) return '00:00:00';
    const h = Math.floor(seg / 3600);
    const m = Math.floor((seg % 3600) / 60);
    const s = seg % 60;
    return `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
}

function actualizarTimer() {
    const restante = Math.max(0, Math.floor((PAGO_LIMITE - Date.now()) / 1000));
    timerVal.textContent = formatTiempo(restante);

    if (restante < 7200) { // menos de 2 horas → rojo
        timerBox.classList.add('critico');
        timerBox.querySelector('.timer-24h-icon').textContent = '🚨';
    }

    if (restante === 0) {
        clearInterval(intervaloTimer);
        timerVal.textContent = 'EXPIRADO';
        document.getElementById('btn-pagar-subasta').disabled = true;
        document.getElementById('btn-pagar-subasta').textContent = '⏰ Tiempo expirado';
        setTimeout(() => location.href = '{{ route("subastas.index") }}', 3000);
    }
}

actualizarTimer();
const intervaloTimer = setInterval(actualizarTimer, 1000);

// Verificar server-side cada 60 seg
setInterval(async () => {
    try {
        const r = await fetch('{{ route("subastas.pagar.timer", $pedido) }}');
        const d = await r.json();
        if (d.expirado) {
            clearInterval(intervaloTimer);
            location.href = '{{ route("subastas.index") }}?expirado=1';
        }
    } catch(e) {}
}, 60000);

// ── Toggle nueva tarjeta ─────────────────────────────
function toggleNuevaTarjeta(mostrar) {
    document.getElementById('nueva-tarjeta-form').style.display = mostrar ? '' : 'none';
}

// ── Formatear número de tarjeta ──────────────────────
function formatearNumero(input) {
    let val = input.value.replace(/\D/g, '').substring(0, 16);
    input.value = val.replace(/(.{4})/g, '$1 ').trim();
}

// ── Confirmar envío ───────────────────────────────────
document.getElementById('form-pago-subasta').addEventListener('submit', function(e) {
    const btn = document.getElementById('btn-pagar-subasta');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Procesando…';
});
</script>
@endpush