@extends('layouts.dashboard')

@section('titulo_pagina', 'Pagar subasta ganada – Tools365')
@section('topbar_title', 'Pagar subasta')
@section('topbar_breadcrumb', $producto->titulo)

@push('css')
<style>
/* ══════════════════════════════════════════════════
   PAGO DE SUBASTA
══════════════════════════════════════════════════ */
.pago-sub-layout {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 2rem;
    align-items: start;
    padding: 2rem 0;
}
@media (max-width: 900px) {
    .pago-sub-layout { grid-template-columns: 1fr; }
}

/* Timer urgente */
.timer-24h {
    background: linear-gradient(135deg, #fff3cd, #ffeeba);
    border: 2px solid #ffc107;
    border-radius: 14px;
    padding: 1.2rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.timer-24h.critico {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    border-color: #dc3545;
}
.timer-24h-icon { font-size: 2rem; flex-shrink: 0; }
.timer-24h-label {
    font-size: .75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: #856404;
    margin-bottom: .1rem;
}
.timer-24h.critico .timer-24h-label { color: #991b1b; }
.timer-24h-val {
    font-size: 1.6rem;
    font-weight: 800;
    color: #856404;
    font-variant-numeric: tabular-nums;
    line-height: 1;
}
.timer-24h.critico .timer-24h-val { color: #dc3545; }
.timer-24h-desc { font-size: .82rem; color: #6c757d; margin-top: .2rem; }

/* Resumen del producto ganado */
.prod-ganado-card {
    border-radius: 14px;
    border: 1.5px solid #c7d2fe;
    background: linear-gradient(135deg, #f0f4ff, #e8f0fe);
    overflow: hidden;
    margin-bottom: 1.5rem;
}
[data-theme="dark"] .prod-ganado-card {
    background: linear-gradient(135deg, #1e2035, #1a1f3a);
    border-color: #3a3f6e;
}
.prod-ganado-inner {
    display: flex;
    gap: 1rem;
    padding: 1.1rem;
    align-items: center;
}
.prod-ganado-img {
    width: 80px;
    height: 64px;
    border-radius: 8px;
    object-fit: cover;
    flex-shrink: 0;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: #9fa8da;
}
.prod-ganado-img img {
    width: 80px;
    height: 64px;
    object-fit: cover;
    border-radius: 8px;
}
.prod-ganado-titulo {
    font-weight: 700;
    font-size: .97rem;
    line-height: 1.3;
    color: var(--color-text, #212529);
}
.prod-ganado-precio {
    font-size: 1.3rem;
    font-weight: 800;
    color: #0d6efd;
    margin-top: .2rem;
}
.prod-ganado-badge {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    background: #d1fae5;
    color: #065f46;
    font-size: .74rem;
    font-weight: 700;
    padding: .2rem .6rem;
    border-radius: 20px;
    margin-bottom: .4rem;
}

/* Panel de pago */
.pago-panel {
    background: var(--color-surface, #fff);
    border: 1.5px solid var(--color-border, #dee2e6);
    border-radius: 16px;
    padding: 1.8rem;
}
[data-theme="dark"] .pago-panel { background: #1e1e2e; border-color: #333; }

.pago-section-label {
    font-size: .73rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #999;
    margin-bottom: 1rem;
    padding-bottom: .4rem;
    border-bottom: 1px solid var(--color-border, #dee2e6);
}

/* Tarjetas guardadas */
.tarjeta-saved {
    display: flex;
    align-items: center;
    gap: .8rem;
    padding: .75rem 1rem;
    border: 2px solid var(--color-border, #dee2e6);
    border-radius: 10px;
    cursor: pointer;
    transition: border-color .15s, background .15s;
    margin-bottom: .5rem;
}
.tarjeta-saved:has(input:checked) {
    border-color: #0d6efd;
    background: rgba(13,110,253,.04);
}
.tarjeta-saved input[type=radio] { flex-shrink: 0; }
.tarjeta-icon { font-size: 1.4rem; }
.tarjeta-info { flex: 1; }
.tarjeta-numero { font-weight: 700; font-size: .92rem; }
.tarjeta-det    { font-size: .78rem; color: #6c757d; }

/* Inputs tarjeta nueva */
.input-card {
    border-radius: 8px;
    border: 1.5px solid var(--color-border, #dee2e6);
    padding: .55rem .9rem;
    font-size: .93rem;
    width: 100%;
    transition: border-color .2s, box-shadow .2s;
    background: var(--color-surface, #fff);
    color: var(--color-text, #212529);
}
.input-card:focus {
    outline: none;
    border-color: #0d6efd;
    box-shadow: 0 0 0 3px rgba(13,110,253,.18);
}

/* Resumen lateral */
.resumen-lateral {
    background: var(--color-surface, #fff);
    border: 1.5px solid var(--color-border, #dee2e6);
    border-radius: 16px;
    padding: 1.5rem;
    position: sticky;
    top: 1.5rem;
}
[data-theme="dark"] .resumen-lateral { background: #1e1e2e; border-color: #333; }
.resumen-fila {
    display: flex;
    justify-content: space-between;
    font-size: .88rem;
    padding: .4rem 0;
    border-bottom: 1px solid var(--color-border, #dee2e6);
}
.resumen-fila:last-child { border: none; }
.resumen-total-fila {
    display: flex;
    justify-content: space-between;
    font-size: 1.15rem;
    font-weight: 800;
    padding: .7rem 0 0;
    margin-top: .3rem;
}
.resumen-total-fila .val { color: #0d6efd; }
</style>
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