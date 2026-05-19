{{-- resources/views/dashboard/plan-checkout.blade.php --}}
@extends('layouts.dashboard')

@section('titulo_pagina', 'Pagar Plan ' . ucfirst($plan) . ' — Tools365')
@section('topbar_title', 'Suscripción')
@section('topbar_breadcrumb', 'Plan ' . ucfirst($plan))

@push('css')
<link rel="stylesheet" href="{{ asset('css/plan_dashboard.css') }}">
<style>
.checkout-wrap {
    max-width: 880px;
    margin: 0 auto;
    padding: 2rem 1rem;
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 1.5rem;
    align-items: start;
}
@media (max-width: 768px) {
    .checkout-wrap { grid-template-columns: 1fr; }
    .checkout-summary { order: -1; }
}

/* Summary Card */
.checkout-summary {
    background: var(--bs-body-bg, #fff);
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    overflow: hidden;
    position: sticky;
    top: 1.5rem;
}
.summary-hero { padding: 1.5rem; text-align: center; color: #fff; }
.summary-hero.plan-basico      { background: linear-gradient(135deg, #3b0764, #7c3aed); }
.summary-hero.plan-profesional { background: linear-gradient(135deg, #78350f, #d97706); }
.summary-emoji  { font-size: 3rem; line-height: 1; }
.summary-name   { font-size: 1.5rem; font-weight: 800; margin: .5rem 0 .25rem; }
.summary-tagline{ opacity: .8; font-size: .88rem; }
.summary-body   { padding: 1.25rem 1.5rem; }

.summary-period-toggle {
    display: flex;
    background: #f3f4f6;
    border-radius: 10px;
    padding: 4px;
    margin-bottom: 1rem;
}
.period-btn {
    flex: 1; border: none; background: transparent;
    padding: .45rem; border-radius: 8px;
    font-size: .82rem; font-weight: 600;
    cursor: pointer; color: #6b7280; transition: all .2s;
}
.period-btn.active { background: #fff; color: #1f2937; box-shadow: 0 1px 4px rgba(0,0,0,.12); }

.summary-price-row {
    display: flex; align-items: flex-end;
    justify-content: center; gap: .25rem; margin: 1rem 0 .5rem;
}
.price-currency { font-size: 1.1rem; font-weight: 700; color: #374151; padding-bottom: 4px; }
.price-amount   { font-size: 2.8rem; font-weight: 900; color: #111827; line-height: 1; }
.price-period   { font-size: .82rem; color: #6b7280; padding-bottom: 8px; }
.price-saving   {
    text-align: center; font-size: .78rem; color: #059669;
    font-weight: 600; margin-bottom: 1rem; min-height: 1.2rem;
}
.summary-features { list-style: none; padding: 0; margin: 1rem 0; }
.summary-features li {
    font-size: .83rem; color: #4b5563; padding: .35rem 0;
    display: flex; align-items: center; gap: .5rem;
    border-bottom: 1px solid #f3f4f6;
}
.summary-features li:last-child { border-bottom: none; }
.summary-features .fi { color: #10b981; font-size: .9rem; }
.summary-total {
    background: #f9fafb; border-radius: 10px;
    padding: .75rem 1rem; display: flex;
    justify-content: space-between; align-items: center;
    font-weight: 700; font-size: 1rem; color: #111827; margin-top: .5rem;
}
.badge-save {
    display: inline-block; background: #d1fae5;
    color: #065f46; font-size: .72rem; font-weight: 700;
    padding: 2px 8px; border-radius: 20px; margin-left: 6px;
}

/* Payment Form */
.checkout-form-card {
    background: var(--bs-body-bg, #fff);
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 1.75rem;
}
.form-section-title {
    font-size: .75rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .08em;
    color: #9ca3af; margin: 1.25rem 0 .75rem;
}
.form-section-title:first-of-type { margin-top: 0; }
.saved-card-list { display: flex; flex-direction: column; gap: .5rem; margin-bottom: 1rem; }
.saved-card-item {
    display: flex; align-items: center; gap: .75rem;
    border: 1.5px solid #e5e7eb; border-radius: 10px;
    padding: .75rem 1rem; cursor: pointer; transition: all .2s;
}
.saved-card-item:hover    { border-color: #6366f1; background: #f5f3ff; }
.saved-card-item.selected { border-color: #6366f1; background: #eef2ff; }
.saved-card-item input[type=radio] { accent-color: #6366f1; }
.card-chip {
    width: 36px; height: 36px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
.chip-visa       { background: #dbeafe; color: #1d4ed8; }
.chip-mastercard { background: #fee2e2; color: #b91c1c; }
.chip-amex       { background: #d1fae5; color: #065f46; }
.chip-otro       { background: #f3f4f6; color: #374151; }
.card-info-text { flex: 1; }
.card-info-text strong { display: block; font-size: .88rem; color: #1f2937; }
.card-info-text small  { color: #9ca3af; font-size: .78rem; }

.new-card-toggle {
    display: flex; align-items: center; gap: .5rem;
    font-size: .88rem; color: #6366f1; font-weight: 600;
    cursor: pointer; margin-bottom: 1rem;
}
.new-card-fields { display: none; }
.new-card-fields.show { display: block; }

.form-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: .75rem; }
.form-group { margin-bottom: .75rem; }
.form-group label {
    display: block; font-size: .78rem; font-weight: 600;
    color: #374151; margin-bottom: .3rem;
}
.form-group input, .form-group select {
    width: 100%; border: 1.5px solid #d1d5db;
    border-radius: 8px; padding: .6rem .85rem;
    font-size: .88rem; transition: border-color .2s;
    background: var(--bs-body-bg, #fff); color: var(--bs-body-color, #111);
}
.form-group input:focus, .form-group select:focus {
    outline: none; border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,.1);
}
.card-icon-wrap { position: relative; }
.card-type-badge {
    position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
    font-size: .75rem; font-weight: 700; color: #6b7280; pointer-events: none;
}
.check-option {
    display: flex; align-items: center; gap: .5rem;
    font-size: .82rem; color: #4b5563; cursor: pointer; margin-bottom: .5rem;
}
.check-option input[type=checkbox] { accent-color: #6366f1; }

.btn-pay {
    width: 100%; padding: .9rem; border: none; border-radius: 12px;
    font-size: 1rem; font-weight: 700; cursor: pointer; transition: all .2s;
    margin-top: .5rem; display: flex; align-items: center;
    justify-content: center; gap: .5rem; color: #fff;
}
.btn-pay.plan-basico      { background: linear-gradient(135deg, #4f46e5, #7c3aed); }
.btn-pay.plan-profesional { background: linear-gradient(135deg, #b45309, #d97706); }
.btn-pay:hover { filter: brightness(1.08); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(0,0,0,.15); }

.security-note {
    display: flex; align-items: center; justify-content: center;
    gap: .4rem; font-size: .75rem; color: #9ca3af; margin-top: .75rem;
}
.back-link {
    display: inline-flex; align-items: center; gap: .4rem;
    color: #6b7280; font-size: .85rem; text-decoration: none;
    margin-bottom: 1rem; transition: color .2s;
}
.back-link:hover { color: #374151; }
.field-error { font-size: .75rem; color: #dc2626; margin-top: .25rem; }
</style>
@endpush

@section('contenido')
@php
    // Precios desde PlanService — ya vienen en $precios desde el controlador
    $precioMensual  = $precios['mensual'];       // ej. 199
    $precioAnualEq  = $precios['mensual_eq'];    // ej. 159.xx  (mensual equivalente)
    $precioAnualTotal = $precios['anual_total']; // ej. 1912.xx (total anual)
    $ahorro         = $precios['ahorro_anual'];  // ej. 238.xx

    // Features por plan
    $features = [
        'basico' => [
            '20 publicaciones activas',
            '6 fotos por publicación',
            '2 publicaciones destacadas / mes',
            'Soporte por email (24 h)',
            'Comisión venta: 10% · renta: 12%',
        ],
        'profesional' => [
            'Publicaciones ilimitadas',
            '10 fotos por publicación',
            '10 publicaciones destacadas / mes',
            'Soporte prioritario 24/7',
            'Comisión venta: 8% · renta: 8%',
        ],
    ];
    $planFeatures = $features[$plan] ?? [];
@endphp

<div style="padding: 1.5rem 1.5rem 0;">
    <a href="{{ route('dashboard.plan') }}" class="back-link">
        <i class="bi bi-arrow-left"></i> Volver a mi plan
    </a>
</div>

<div class="checkout-wrap">

    {{-- ── FORMULARIO DE PAGO ─────────────────────────────────────────────── --}}
    <div class="checkout-form-card">
        <h2 style="font-size:1.25rem;font-weight:800;color:#111827;margin:0 0 1.5rem;">
            <i class="bi bi-lock-fill me-2" style="color:#6366f1;"></i>
            Completa tu suscripción
        </h2>

        @if ($errors->any())
            <div class="alert alert-danger py-2 mb-3" style="font-size:.83rem;border-radius:10px;">
                <i class="bi bi-exclamation-circle me-1"></i>
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger py-2 mb-3" style="font-size:.83rem;border-radius:10px;">
                <i class="bi bi-exclamation-circle me-1"></i>
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('dashboard.plan.pagar') }}" id="checkout-form">
            @csrf
            <input type="hidden" name="plan"    value="{{ $plan }}">
            <input type="hidden" name="periodo" id="input-periodo" value="mensual">

            {{-- Tarjetas guardadas --}}
            @if($tarjetas->count())
                <div class="form-section-title">Tarjeta guardada</div>
                <div class="saved-card-list">
                    @foreach($tarjetas as $t)
                    @php $chipClass = 'chip-' . ($t->tipo ?? 'otro'); @endphp
                    <label class="saved-card-item {{ $t->predeterminada ? 'selected' : '' }}"
                           id="card-label-{{ $t->id }}">
                        <input type="radio" name="tarjeta_guardada_id" value="{{ $t->id }}"
                               {{ $t->predeterminada ? 'checked' : '' }}
                               onchange="selectCard({{ $t->id }})">
                        <div class="card-chip {{ $chipClass }}">💳</div>
                        <div class="card-info-text">
                            <strong>•••• •••• •••• {{ $t->ultimos_cuatro }}</strong>
                            <small>{{ strtoupper($t->tipo) }} · {{ $t->titular }} · vence {{ str_pad($t->exp_mes, 2, '0', STR_PAD_LEFT) }}/{{ $t->exp_anio }}</small>
                        </div>
                        @if($t->predeterminada)
                            <span style="font-size:.7rem;background:#ede9fe;color:#7c3aed;padding:2px 8px;border-radius:20px;font-weight:700;">Principal</span>
                        @endif
                    </label>
                    @endforeach
                </div>

                <div class="new-card-toggle" onclick="toggleNewCard()">
                    <i class="bi bi-plus-circle" id="toggle-icon"></i>
                    <span id="toggle-text">Usar otra tarjeta</span>
                </div>
            @endif

            {{-- Nueva tarjeta --}}
            <div class="new-card-fields {{ $tarjetas->isEmpty() ? 'show' : '' }}" id="new-card-fields">

                <div class="form-section-title">
                    {{ $tarjetas->isNotEmpty() ? 'Nueva tarjeta' : 'Datos de pago' }}
                </div>

                <div class="form-group">
                    <label>Número de tarjeta</label>
                    <div class="card-icon-wrap">
                        <input type="text" name="numero" id="numero-tarjeta"
                               placeholder="1234 5678 9012 3456"
                               maxlength="23" autocomplete="cc-number"
                               oninput="formatCard(this); detectarTipo(this)">
                        <span class="card-type-badge" id="card-type-badge"></span>
                    </div>
                    @error('numero')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label>Nombre del titular</label>
                    <input type="text" name="titular" placeholder="Como aparece en la tarjeta"
                           autocomplete="cc-name" style="text-transform:uppercase"
                           value="{{ old('titular') }}">
                    @error('titular')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-row-3">
                    <div class="form-group">
                        <label>Mes</label>
                        <select name="exp_mes" autocomplete="cc-exp-month">
                            <option value="">MM</option>
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ old('exp_mes') == $m ? 'selected' : '' }}>
                                    {{ str_pad($m, 2, '0', STR_PAD_LEFT) }}
                                </option>
                            @endfor
                        </select>
                        @error('exp_mes')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Año</label>
                        <select name="exp_anio" autocomplete="cc-exp-year">
                            <option value="">AAAA</option>
                            @for($y = date('Y'); $y <= date('Y') + 12; $y++)
                                <option value="{{ $y }}" {{ old('exp_anio') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        @error('exp_anio')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>CVV</label>
                        <input type="password" name="cvv" placeholder="•••"
                               maxlength="4" autocomplete="cc-csc">
                        @error('cvv')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <label class="check-option">
                    <input type="checkbox" name="guardar_tarjeta" value="1" id="chk-guardar">
                    Guardar tarjeta para futuros pagos
                </label>
                <label class="check-option" id="chk-pred-wrap" style="display:none;padding-left:1.4rem;">
                    <input type="checkbox" name="predeterminada" value="1">
                    Establecer como tarjeta predeterminada
                </label>
            </div>

            {{-- Botón pagar --}}
            <button type="submit" class="btn-pay plan-{{ $plan }}" id="btn-pay">
                <i class="bi bi-shield-lock-fill"></i>
                <span id="btn-pay-text">
                    Suscribirse · $<span id="btn-amount">{{ number_format($precioMensual, 0, '.', ',') }}</span>/mes
                </span>
            </button>

            <div class="security-note">
                <i class="bi bi-lock-fill"></i>
                Pago seguro simulado · No se procesan datos reales
            </div>
        </form>
    </div>

    {{-- ── RESUMEN DEL PLAN ───────────────────────────────────────────────── --}}
    <div class="checkout-summary">
        <div class="summary-hero plan-{{ $plan }}">
            <div class="summary-emoji">{{ $infoPlan['emoji'] }}</div>
            <div class="summary-name">Plan {{ $infoPlan['label'] }}</div>
            <div class="summary-tagline">
                @if($plan === 'basico') Para vendedores y arrendadores activos
                @else Para empresas y alto volumen @endif
            </div>
        </div>

        <div class="summary-body">
            <div class="summary-period-toggle">
                <button class="period-btn active" onclick="setPeriodo('mensual')" id="btn-mensual" type="button">
                    Mensual
                </button>
                <button class="period-btn" onclick="setPeriodo('anual')" id="btn-anual" type="button">
                    Anual <span class="badge-save">-10%</span>
                </button>
            </div>

            <div class="summary-price-row">
                <span class="price-currency">$</span>
                <span class="price-amount" id="price-display">{{ number_format($precioMensual, 0, '.', ',') }}</span>
                <span class="price-period">MXN/mes</span>
            </div>
            <div class="price-saving" id="price-saving"></div>

            <ul class="summary-features">
                @foreach($planFeatures as $feature)
                    <li><i class="bi bi-check-lg fi"></i> {{ $feature }}</li>
                @endforeach
            </ul>

            <div class="summary-total">
                <span>Total hoy</span>
                <span>$<span id="total-display">{{ number_format($precioMensual, 0, '.', ',') }}</span> MXN</span>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
// Precios desde PHP (PlanService) — fuente única de verdad
const precioMensual     = {{ $precioMensual }};
const precioAnualEq     = {{ round($precioAnualEq) }};     // mensual equivalente al pagar anual
const precioAnualTotal  = {{ round($precioAnualTotal) }};  // total que se cobra hoy si elige anual
const ahorro            = {{ round($ahorro) }};

let periodoActual = 'mensual';

// ── Periodo toggle ────────────────────────────────────────────────────────────
function setPeriodo(periodo) {
    periodoActual = periodo;
    document.getElementById('input-periodo').value = periodo;

    document.getElementById('btn-mensual').classList.toggle('active', periodo === 'mensual');
    document.getElementById('btn-anual').classList.toggle('active', periodo === 'anual');

    const savingEl    = document.getElementById('price-saving');
    const displayEl   = document.getElementById('price-display');
    const totalEl     = document.getElementById('total-display');
    const btnAmountEl = document.getElementById('btn-amount');

    if (periodo === 'anual') {
        // Mostramos el precio mensual equivalente en el display
        displayEl.textContent   = precioAnualEq.toLocaleString('es-MX');
        // El total que se cobra hoy es el anual completo
        totalEl.textContent     = precioAnualTotal.toLocaleString('es-MX');
        btnAmountEl.textContent = precioAnualTotal.toLocaleString('es-MX');
        savingEl.textContent    = `Facturado anualmente · Ahorras $${ahorro.toLocaleString('es-MX')} al año`;

        // Cambiar el botón para indicar que es pago único anual
        document.getElementById('btn-pay-text').innerHTML =
            `Suscribirse · $<span id="btn-amount">${precioAnualTotal.toLocaleString('es-MX')}</span> MXN/año`;
    } else {
        displayEl.textContent   = precioMensual.toLocaleString('es-MX');
        totalEl.textContent     = precioMensual.toLocaleString('es-MX');
        savingEl.textContent    = '';
        document.getElementById('btn-pay-text').innerHTML =
            `Suscribirse · $<span id="btn-amount">${precioMensual.toLocaleString('es-MX')}</span>/mes`;
    }
}

// ── Tarjeta seleccionada ──────────────────────────────────────────────────────
function selectCard(id) {
    document.querySelectorAll('.saved-card-item').forEach(el => el.classList.remove('selected'));
    document.getElementById('card-label-' + id)?.classList.add('selected');
    document.querySelectorAll('[name="tarjeta_guardada_id"]').forEach(r => {
        if (r.value == id) r.checked = true;
    });
}

// ── Toggle nueva tarjeta ──────────────────────────────────────────────────────
function toggleNewCard() {
    const fields = document.getElementById('new-card-fields');
    const isShow = fields.classList.contains('show');
    fields.classList.toggle('show', !isShow);
    document.getElementById('toggle-icon').className = isShow ? 'bi bi-plus-circle' : 'bi bi-dash-circle';
    document.getElementById('toggle-text').textContent = isShow ? 'Usar otra tarjeta' : 'Cancelar';

    if (!isShow) {
        // Mostrar nueva tarjeta → deseleccionar radios
        document.querySelectorAll('[name="tarjeta_guardada_id"]').forEach(r => r.checked = false);
        document.querySelectorAll('.saved-card-item').forEach(el => el.classList.remove('selected'));
    } else {
        // Ocultar nueva tarjeta → reseleccionar la predeterminada
        const pred = document.querySelector('[name="tarjeta_guardada_id"]');
        if (pred) { pred.checked = true; selectCard(pred.value); }
    }
}

// ── Formatear número de tarjeta ───────────────────────────────────────────────
function formatCard(input) {
    let val = input.value.replace(/\D/g, '').substring(0, 19);
    input.value = val.replace(/(.{4})/g, '$1 ').trim();
}

// ── Detectar tipo de tarjeta ──────────────────────────────────────────────────
function detectarTipo(input) {
    const num   = input.value.replace(/\D/g, '');
    const badge = document.getElementById('card-type-badge');
    if      (/^4/.test(num))            badge.textContent = 'VISA';
    else if (/^5[1-5]/.test(num))       badge.textContent = 'MC';
    else if (/^3[47]/.test(num))        badge.textContent = 'AMEX';
    else                                badge.textContent = '';
}

// ── Mostrar/ocultar campo predeterminada ──────────────────────────────────────
document.getElementById('chk-guardar')?.addEventListener('change', function () {
    const wrap = document.getElementById('chk-pred-wrap');
    if (wrap) wrap.style.display = this.checked ? 'flex' : 'none';
});

// ── Deshabilitar botón al enviar ──────────────────────────────────────────────
document.getElementById('checkout-form').addEventListener('submit', function () {
    const btn = document.getElementById('btn-pay');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Procesando...';
});
</script>
@endpush