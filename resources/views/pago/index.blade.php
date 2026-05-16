{{-- resources/views/pago/index.blade.php --}}
@extends('layouts.app')

@section('titulo_pagina', 'Pago seguro — Tools365')

@push('css')
<style>
/* ── Variables ─────────────────────────────────────────────────────────────── */
:root {
    --pg-accent:   #534AB7;
    --pg-accent2:  #7c6ef5;
    --pg-danger:   #e74c3c;
    --pg-green:    #1a7f4b;
    --pg-surface:  #ffffff;
    --pg-border:   #e2e8f0;
    --pg-muted:    #64748b;
    --pg-bg:       #f8fafc;
    --pg-radius:   14px;
    --pg-shadow:   0 4px 24px rgba(83,74,183,.1);
}
[data-theme="dark"] {
    --pg-surface: #1e293b;
    --pg-border:  #334155;
    --pg-muted:   #94a3b8;
    --pg-bg:      #0f172a;
    --pg-shadow:  0 4px 24px rgba(0,0,0,.35);
}

/* ── Layout ────────────────────────────────────────────────────────────────── */
.pg-wrap {
    min-height: 100vh;
    background: var(--pg-bg);
    padding: 120px 0 60px;
}
.pg-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 28px;
    align-items: start;
}
@media (max-width: 900px) {
    .pg-grid { grid-template-columns: 1fr; }
    .pg-sidebar { order: -1; }
}

/* ── Timer banner ───────────────────────────────────────────────────────────── */
.timer-banner {
    display: flex;
    align-items: center;
    gap: 14px;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    border: 1px solid rgba(255,255,255,.08);
    border-radius: var(--pg-radius);
    padding: 1rem 1.4rem;
    margin-bottom: 24px;
    color: #fff;
}
.timer-icon {
    width: 48px; height: 48px;
    border-radius: 50%;
    background: rgba(83,74,183,.3);
    border: 2px solid rgba(83,74,183,.5);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem;
    flex-shrink: 0;
}
.timer-text { flex: 1; }
.timer-text p { margin: 0; font-size: .82rem; opacity: .7; }
.timer-text h3 { margin: 0; font-size: 1rem; font-weight: 700; }
.timer-display {
    font-size: 2rem;
    font-weight: 900;
    letter-spacing: 2px;
    font-variant-numeric: tabular-nums;
    transition: color .3s;
}
.timer-display.danger { color: #ff6b6b; animation: pulse-danger .8s infinite; }
@keyframes pulse-danger {
    0%,100% { opacity: 1; }
    50%      { opacity: .6; }
}

/* ── Cards ──────────────────────────────────────────────────────────────────── */
.pg-card {
    background: var(--pg-surface);
    border: 1px solid var(--pg-border);
    border-radius: var(--pg-radius);
    box-shadow: var(--pg-shadow);
    overflow: hidden;
    margin-bottom: 20px;
}
.pg-card-header {
    padding: 1.1rem 1.4rem;
    border-bottom: 1px solid var(--pg-border);
    display: flex; align-items: center; gap: .7rem;
}
.pg-card-header h2 {
    font-size: 1rem; font-weight: 700; margin: 0;
    color: var(--color-text, #0f172a);
}
.pg-card-icon {
    width: 34px; height: 34px;
    border-radius: .5rem;
    background: #ede9ff;
    color: var(--pg-accent);
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem; flex-shrink: 0;
}
.pg-card-body { padding: 1.4rem; }

/* ── Tarjetas guardadas ─────────────────────────────────────────────────────── */
.saved-cards { display: flex; flex-direction: column; gap: 10px; margin-bottom: 18px; }

.saved-card-opt {
    display: flex; align-items: center; gap: 12px;
    padding: .85rem 1rem;
    border: 1.5px solid var(--pg-border);
    border-radius: 10px;
    cursor: pointer;
    transition: all .2s;
    position: relative;
}
.saved-card-opt:has(input:checked) {
    border-color: var(--pg-accent);
    background: rgba(83,74,183,.04);
}
.saved-card-opt input[type="radio"] {
    position: absolute; opacity: 0; width: 0;
}
.saved-card-radio {
    width: 18px; height: 18px;
    border-radius: 50%;
    border: 2px solid var(--pg-border);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; transition: all .2s;
}
.saved-card-opt:has(input:checked) .saved-card-radio {
    border-color: var(--pg-accent);
    background: var(--pg-accent);
}
.saved-card-opt:has(input:checked) .saved-card-radio::after {
    content: '';
    width: 6px; height: 6px;
    border-radius: 50%;
    background: #fff;
}
.card-brand-icon {
    width: 42px; height: 28px;
    border-radius: 5px;
    background: linear-gradient(135deg, #1a1a2e, #2d2d5e);
    display: flex; align-items: center; justify-content: center;
    font-size: .75rem; color: #fff; font-weight: 800;
    letter-spacing: .5px;
}
.card-brand-icon.visa       { background: linear-gradient(135deg, #1434CB, #1434CB); }
.card-brand-icon.mastercard { background: linear-gradient(135deg, #EB001B, #F79E1B); }
.card-brand-icon.amex       { background: linear-gradient(135deg, #007BC1, #007BC1); }

.saved-card-info { flex: 1; }
.saved-card-name { font-weight: 700; font-size: .88rem; color: var(--color-text,#0f172a); }
.saved-card-exp  { font-size: .75rem; color: var(--pg-muted); }
.badge-default {
    font-size: .68rem; font-weight: 700;
    padding: .15em .5em; border-radius: 999px;
    background: #ede9ff; color: var(--pg-accent);
}

.divider-nueva {
    display: flex; align-items: center; gap: 10px;
    margin: 18px 0;
    font-size: .78rem; font-weight: 600; color: var(--pg-muted);
}
.divider-nueva::before, .divider-nueva::after {
    content: ''; flex: 1; height: 1px;
    background: var(--pg-border);
}

/* ── Formulario tarjeta nueva ────────────────────────────────────────────────── */
.card-visual {
    width: 100%;
    aspect-ratio: 1.586;
    max-width: 340px;
    border-radius: 16px;
    background: linear-gradient(135deg, #1a1a2e 0%, #534AB7 60%, #7c6ef5 100%);
    padding: 1.4rem;
    color: #fff;
    position: relative;
    overflow: hidden;
    margin: 0 auto 20px;
    box-shadow: 0 12px 40px rgba(83,74,183,.35);
    transition: all .3s;
}
.card-visual::before {
    content: '';
    position: absolute;
    top: -40%; right: -20%;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,.06);
}
.card-visual::after {
    content: '';
    position: absolute;
    bottom: -30%; left: -10%;
    width: 160px; height: 160px;
    border-radius: 50%;
    background: rgba(255,255,255,.04);
}
.card-chip {
    width: 38px; height: 28px;
    background: linear-gradient(135deg, #f4d03f, #f39c12);
    border-radius: 5px;
    margin-bottom: 20px;
    position: relative; z-index: 1;
}
.card-number-display {
    font-size: 1.1rem; letter-spacing: 3px; font-weight: 600;
    margin-bottom: 16px; position: relative; z-index: 1;
    font-variant-numeric: tabular-nums;
    text-shadow: 0 1px 3px rgba(0,0,0,.3);
}
.card-bottom {
    display: flex; justify-content: space-between; align-items: flex-end;
    position: relative; z-index: 1;
}
.card-label { font-size: .65rem; opacity: .6; text-transform: uppercase; letter-spacing: .08em; }
.card-value { font-size: .88rem; font-weight: 700; }
.card-brand-display { font-size: 1.2rem; font-weight: 900; letter-spacing: -1px; }

/* ── Inputs ──────────────────────────────────────────────────────────────────── */
.pg-label {
    font-size: .78rem; font-weight: 700;
    color: var(--pg-muted); text-transform: uppercase; letter-spacing: .06em;
    display: block; margin-bottom: 6px;
}
.pg-input {
    width: 100%;
    padding: .7rem 1rem;
    border: 1.5px solid var(--pg-border);
    border-radius: 10px;
    font-size: .9rem;
    background: var(--pg-surface);
    color: var(--color-text, #0f172a);
    transition: border-color .2s, box-shadow .2s;
    outline: none;
}
.pg-input:focus {
    border-color: var(--pg-accent);
    box-shadow: 0 0 0 3px rgba(83,74,183,.12);
}
.pg-input.error { border-color: var(--pg-danger); }
.input-icon-wrap {
    position: relative;
}
.input-icon-wrap .pg-input { padding-left: 2.6rem; }
.input-icon-wrap .input-icon {
    position: absolute; left: .85rem; top: 50%; transform: translateY(-50%);
    color: var(--pg-muted); font-size: .9rem;
}
.pg-check {
    display: flex; align-items: center; gap: .5rem;
    font-size: .85rem; cursor: pointer;
    color: var(--color-text, #0f172a);
}
.pg-check input { accent-color: var(--pg-accent); width: 16px; height: 16px; }

/* ── Botón pagar ─────────────────────────────────────────────────────────────── */
.btn-pagar {
    width: 100%;
    padding: .95rem;
    border: none; border-radius: 12px;
    background: linear-gradient(135deg, var(--pg-accent), var(--pg-accent2));
    color: #fff;
    font-size: 1rem; font-weight: 800;
    display: flex; align-items: center; justify-content: center; gap: .6rem;
    cursor: pointer;
    transition: all .2s;
    box-shadow: 0 6px 20px rgba(83,74,183,.35);
    margin-top: 8px;
}
.btn-pagar:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(83,74,183,.45); }
.btn-pagar:active { transform: translateY(0); }
.btn-pagar:disabled {
    opacity: .5; cursor: not-allowed;
    transform: none; box-shadow: none;
}

.ssl-badge {
    display: flex; align-items: center; justify-content: center; gap: .4rem;
    font-size: .75rem; color: var(--pg-muted); margin-top: 10px;
}

/* ── Sidebar resumen ─────────────────────────────────────────────────────────── */
.resumen-item {
    display: flex; align-items: center; gap: 10px;
    padding: .7rem 0;
    border-bottom: 1px solid var(--pg-border);
}
.resumen-item:last-of-type { border-bottom: none; }
.resumen-thumb {
    width: 44px; height: 44px; border-radius: 8px;
    overflow: hidden; flex-shrink: 0;
    background: var(--pg-bg);
    border: 1px solid var(--pg-border);
    display: flex; align-items: center; justify-content: center;
}
.resumen-thumb img { width:100%;height:100%;object-fit:cover; }
.resumen-thumb i { font-size: 1.1rem; color: var(--pg-muted); }
.resumen-info { flex: 1; min-width: 0; }
.resumen-titulo { font-weight: 700; font-size: .83rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.resumen-tipo {
    font-size: .72rem; padding: .2em .55em; border-radius: 999px;
    display: inline-flex; align-items: center; gap: .25rem;
    font-weight: 600;
}
.resumen-tipo.comprar { background: #dcfce7; color: #166534; }
.resumen-tipo.rentar  { background: #dbeafe; color: #1d4ed8; }
.resumen-precio { font-weight: 800; font-size: .9rem; white-space: nowrap; }

.total-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: .5rem 0;
    font-size: .88rem;
}
.total-row.final {
    font-size: 1.1rem; font-weight: 800;
    border-top: 2px solid var(--pg-border);
    padding-top: .8rem; margin-top: .2rem;
    color: var(--pg-accent);
}

/* ── Seguridad chips ─────────────────────────────────────────────────────────── */
.security-chips {
    display: flex; flex-wrap: wrap; gap: 8px;
    margin-top: 14px;
}
.security-chip {
    display: inline-flex; align-items: center; gap: .35rem;
    font-size: .72rem; font-weight: 600;
    padding: .3em .7em; border-radius: 999px;
    background: #e6f9f0; color: #1a7f4b;
    border: 1px solid #c3f1d9;
}
</style>
@endpush

@section('contenido')

<div class="pg-wrap">
<div class="container">

    {{-- ── Timer banner ──────────────────────────────────────────────────────── --}}
    <div class="timer-banner mb-4">
        <div class="timer-icon"><i class="bi bi-clock-history"></i></div>
        <div class="timer-text">
            <h3>Completa tu pago antes de que expire el tiempo</h3>
            <p>Tu sesión de pago caduca en:</p>
        </div>
        <div class="timer-display" id="timerDisplay">03:00</div>
    </div>

    <div class="pg-grid">

        {{-- ╔══════════════ COLUMNA IZQUIERDA ══════════════╗ --}}
        <div class="pg-main">

            {{-- Errores de validación --}}
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-3">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Corrige los siguientes errores:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <form action="{{ route('pago.procesar') }}" method="POST" id="formPago">
            @csrf

            {{-- ── TARJETAS GUARDADAS ──────────────────────────────────────── --}}
            @if($tarjetas->isNotEmpty())
            <div class="pg-card">
                <div class="pg-card-header">
                    <div class="pg-card-icon"><i class="bi bi-wallet2"></i></div>
                    <h2>Mis tarjetas guardadas</h2>
                </div>
                <div class="pg-card-body">
                    <div class="saved-cards">
                        @foreach($tarjetas as $tc)
                        <label class="saved-card-opt">
                            <input type="radio" name="tarjeta_guardada_id"
                                   value="{{ $tc->id }}"
                                   id="tc_{{ $tc->id }}"
                                   {{ $tc->predeterminada && !old('tarjeta_guardada_id') ? 'checked' : '' }}
                                   {{ old('tarjeta_guardada_id') == $tc->id ? 'checked' : '' }}
                                   onchange="toggleNuevaForm(false)">
                            <div class="saved-card-radio"></div>
                            <div class="card-brand-icon {{ $tc->tipo }}">
                                {{ strtoupper(substr($tc->tipo, 0, 2)) }}
                            </div>
                            <div class="saved-card-info">
                                <div class="saved-card-name">
                                    •••• •••• •••• {{ $tc->ultimos_cuatro }}
                                    @if($tc->predeterminada)
                                        <span class="badge-default ms-1">Predeterminada</span>
                                    @endif
                                </div>
                                <div class="saved-card-exp">
                                    {{ $tc->titular }} &bull;
                                    Vence {{ str_pad($tc->exp_mes, 2,'0',STR_PAD_LEFT) }}/{{ $tc->exp_anio }}
                                    @if($tc->estaVencida()) <span class="text-danger ms-1">Vencida</span> @endif
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>

                    <div class="divider-nueva">
                        <label class="saved-card-opt" style="border-style:dashed;cursor:pointer;flex:1;">
                            <input type="radio" name="tarjeta_guardada_id" value=""
                                   id="tc_nueva" onchange="toggleNuevaForm(true)">
                            <div class="saved-card-radio"></div>
                            <div class="pg-card-icon"><i class="bi bi-plus-lg"></i></div>
                            <div class="saved-card-info">
                                <div class="saved-card-name">Usar otra tarjeta</div>
                                <div class="saved-card-exp">Ingresa los datos de una nueva tarjeta</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            @endif

            {{-- ── DATOS DE TARJETA NUEVA ─────────────────────────────────── --}}
            <div class="pg-card" id="sectionNuevaTarjeta"
                 style="{{ $tarjetas->isNotEmpty() ? 'display:none;' : '' }}">
                <div class="pg-card-header">
                    <div class="pg-card-icon"><i class="bi bi-credit-card-2-front"></i></div>
                    <h2>Datos de pago</h2>
                </div>
                <div class="pg-card-body">

                    {{-- Tarjeta visual animada --}}
                    <div class="card-visual" id="cardVisual">
                        <div class="card-chip"></div>
                        <div class="card-number-display" id="cvNumero">•••• •••• •••• ••••</div>
                        <div class="card-bottom">
                            <div>
                                <div class="card-label">Titular</div>
                                <div class="card-value" id="cvTitular">NOMBRE APELLIDO</div>
                            </div>
                            <div>
                                <div class="card-label">Vence</div>
                                <div class="card-value" id="cvVence">MM/AA</div>
                            </div>
                            <div class="card-brand-display" id="cvBrand">•</div>
                        </div>
                    </div>

                    <div class="row g-3">
                        {{-- Número --}}
                        <div class="col-12">
                            <label class="pg-label">Número de tarjeta</label>
                            <div class="input-icon-wrap">
                                <i class="bi bi-credit-card input-icon"></i>
                                <input type="tel" name="numero" id="inputNumero"
                                       class="pg-input @error('numero') error @enderror"
                                       placeholder="•••• •••• •••• ••••"
                                       maxlength="19" inputmode="numeric"
                                       autocomplete="cc-number">
                            </div>
                            @error('numero') <div class="text-danger" style="font-size:.78rem;margin-top:4px;">{{ $message }}</div> @enderror
                        </div>

                        {{-- Titular --}}
                        <div class="col-12">
                            <label class="pg-label">Nombre del titular (como aparece en la tarjeta)</label>
                            <div class="input-icon-wrap">
                                <i class="bi bi-person input-icon"></i>
                                <input type="text" name="titular" id="inputTitular"
                                       class="pg-input @error('titular') error @enderror"
                                       placeholder="JUAN PÉREZ LÓPEZ"
                                       maxlength="100"
                                       autocomplete="cc-name"
                                       value="{{ old('titular') }}"
                                       style="text-transform:uppercase;">
                            </div>
                            @error('titular') <div class="text-danger" style="font-size:.78rem;margin-top:4px;">{{ $message }}</div> @enderror
                        </div>

                        {{-- Mes / Año / CVV --}}
                        <div class="col-4">
                            <label class="pg-label">Mes exp.</label>
                            <input type="number" name="exp_mes" id="inputMes"
                                   class="pg-input @error('exp_mes') error @enderror"
                                   placeholder="MM" min="1" max="12"
                                   inputmode="numeric"
                                   value="{{ old('exp_mes') }}">
                            @error('exp_mes') <div class="text-danger" style="font-size:.78rem;margin-top:4px;">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-4">
                            <label class="pg-label">Año exp.</label>
                            <input type="number" name="exp_anio" id="inputAnio"
                                   class="pg-input @error('exp_anio') error @enderror"
                                   placeholder="{{ date('Y') }}" min="{{ date('Y') }}" max="{{ date('Y') + 20 }}"
                                   inputmode="numeric"
                                   value="{{ old('exp_anio') }}">
                            @error('exp_anio') <div class="text-danger" style="font-size:.78rem;margin-top:4px;">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-4">
                            <label class="pg-label">CVV</label>
                            <div class="input-icon-wrap">
                                <i class="bi bi-shield-lock input-icon"></i>
                                <input type="password" name="cvv" id="inputCvv"
                                       class="pg-input @error('cvv') error @enderror"
                                       placeholder="•••"
                                       maxlength="4" inputmode="numeric"
                                       autocomplete="cc-csc">
                            </div>
                            @error('cvv') <div class="text-danger" style="font-size:.78rem;margin-top:4px;">{{ $message }}</div> @enderror
                        </div>

                        {{-- Guardar tarjeta --}}
                        <div class="col-12">
                            <div class="p-3 rounded-3" style="background:var(--pg-bg);border:1px solid var(--pg-border);">
                                <label class="pg-check mb-2">
                                    <input type="checkbox" name="guardar_tarjeta" value="1"
                                           id="chkGuardar"
                                           {{ old('guardar_tarjeta') ? 'checked' : '' }}
                                           onchange="togglePredeterminada(this.checked)">
                                    Guardar esta tarjeta en mi perfil para pagos futuros
                                </label>
                                <div id="optPredeterminada" style="display:none;margin-left:24px;">
                                    <label class="pg-check">
                                        <input type="checkbox" name="predeterminada" value="1"
                                               {{ old('predeterminada') ? 'checked' : '' }}>
                                        Establecer como tarjeta predeterminada
                                    </label>
                                </div>
                                <p style="font-size:.75rem;color:var(--pg-muted);margin:.5rem 0 0 24px;">
                                    <i class="bi bi-shield-check me-1"></i>
                                    El CVV nunca se almacena por razones de seguridad.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── BOTÓN PAGAR ─────────────────────────────────────────────── --}}
            <button type="submit" class="btn-pagar" id="btnPagar">
                <i class="bi bi-lock-fill"></i>
                Pagar ${{ number_format($subtotal, 2) }} MXN
            </button>
            <div class="ssl-badge">
                <i class="bi bi-shield-lock-fill text-success"></i>
                Pago simulado · Entorno de prueba · SSL 256-bit
            </div>

            </form>
        </div>

        {{-- ╔══════════════ SIDEBAR RESUMEN ══════════════╗ --}}
        <div class="pg-sidebar">

            {{-- Resumen de orden --}}
            <div class="pg-card">
                <div class="pg-card-header">
                    <div class="pg-card-icon"><i class="bi bi-receipt"></i></div>
                    <h2>Resumen del pedido</h2>
                </div>
                <div class="pg-card-body">
                    <div style="margin-bottom:16px;">
                        @foreach($items as $item)
                        <div class="resumen-item">
                            <div class="resumen-thumb">
                                @php $img = $item->producto->imagenes->first(); @endphp
                                @if($img) <img src="{{ asset($img->ruta) }}" alt="">
                                @else <i class="bi bi-gear"></i> @endif
                            </div>
                            <div class="resumen-info">
                                <div class="resumen-titulo">{{ Str::limit($item->producto->titulo, 38) }}</div>
                                <span class="resumen-tipo {{ $item->tipo_accion }}">
                                    @if($item->tipo_accion === 'comprar')
                                        <i class="bi bi-bag-check-fill"></i> Compra
                                    @else
                                        <i class="bi bi-clock-history"></i> Renta
                                    @endif
                                </span>
                                @if($item->tipo_accion === 'rentar' && $item->fecha_inicio)
                                    <div style="font-size:.72rem;color:var(--pg-muted);margin-top:2px;">
                                        {{ \Carbon\Carbon::parse($item->fecha_inicio)->format('d/m') }}
                                        – {{ \Carbon\Carbon::parse($item->fecha_fin)->format('d/m/Y') }}
                                    </div>
                                @endif
                                @if($item->cantidad > 1)
                                    <div style="font-size:.72rem;color:var(--pg-muted);">x{{ $item->cantidad }}</div>
                                @endif
                            </div>
                            <div class="resumen-precio">${{ number_format($item->total_calculado, 2) }}</div>
                        </div>
                        @endforeach
                    </div>

                    <div class="total-row">
                        <span style="color:var(--pg-muted);">Subtotal</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="total-row">
                        <span style="color:var(--pg-muted);">Comisión de servicio</span>
                        <span class="text-success">$0.00</span>
                    </div>
                    <div class="total-row final">
                        <span>Total a pagar</span>
                        <span>${{ number_format($subtotal, 2) }} MXN</span>
                    </div>
                </div>
            </div>

            {{-- Folio --}}
            <div class="pg-card">
                <div class="pg-card-body" style="padding:.9rem 1.2rem;">
                    <div style="font-size:.72rem;color:var(--pg-muted);font-weight:700;text-transform:uppercase;letter-spacing:.06em;">Folio de pedido</div>
                    <div style="font-size:1.1rem;font-weight:800;color:var(--pg-accent);letter-spacing:1px;margin-top:2px;">
                        {{ $pedido->folio }}
                    </div>
                    <div style="font-size:.75rem;color:var(--pg-muted);margin-top:4px;">
                        <i class="bi bi-clock me-1"></i>Límite: {{ $pedido->pago_limite->format('H:i:s') }}
                    </div>
                </div>
            </div>

            {{-- Chips de seguridad --}}
            <div class="security-chips">
                <span class="security-chip"><i class="bi bi-shield-check-fill"></i> Pago simulado</span>
                <span class="security-chip"><i class="bi bi-lock-fill"></i> Encriptado</span>
                <span class="security-chip"><i class="bi bi-arrow-counterclockwise"></i> Reembolso 30 días</span>
            </div>

        </div>{{-- /sidebar --}}
    </div>{{-- /grid --}}
</div>
</div>

{{-- Modal expiración ──────────────────────────────────────────────────────── --}}
<div class="modal fade" id="modalExpirado" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;overflow:hidden;">
            <div style="background:linear-gradient(135deg,#c0392b,#e74c3c);padding:2rem;text-align:center;color:#fff;">
                <i class="bi bi-clock-history" style="font-size:3rem;"></i>
                <h4 class="fw-bold mt-3 mb-1">Tiempo agotado</h4>
                <p class="mb-0" style="opacity:.85;">El tiempo para completar tu pago ha expirado.</p>
            </div>
            <div class="modal-body text-center" style="padding:1.5rem;">
                <p style="color:var(--pg-muted);margin:0;">
                    Serás redirigido a tu carrito en <strong id="redireccionCount">5</strong> segundos para iniciar un nuevo proceso de pago.
                </p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ── Timer ─────────────────────────────────────────────────────────────────────
const pagoLimite = new Date('{{ $pedido->pago_limite->toIso8601String() }}');
const timerEl    = document.getElementById('timerDisplay');
const btnPagar   = document.getElementById('btnPagar');
let timerExpired = false;

function actualizarTimer() {
    const ahora    = new Date();
    const diffMs   = pagoLimite - ahora;

    if (diffMs <= 0 || timerExpired) {
        timerEl.textContent = '00:00';
        timerEl.classList.add('danger');
        if (!timerExpired) expirarTimer();
        return;
    }

    const mins  = Math.floor(diffMs / 60000);
    const segs  = Math.floor((diffMs % 60000) / 1000);
    timerEl.textContent = `${String(mins).padStart(2,'0')}:${String(segs).padStart(2,'0')}`;

    if (diffMs < 60000) timerEl.classList.add('danger');
    else                timerEl.classList.remove('danger');
}

function expirarTimer() {
    timerExpired = true;
    btnPagar.disabled = true;

    const modal = new bootstrap.Modal(document.getElementById('modalExpirado'));
    modal.show();

    let cuenta = 5;
    const contEl = document.getElementById('redireccionCount');
    const iv = setInterval(() => {
        cuenta--;
        if (contEl) contEl.textContent = cuenta;
        if (cuenta <= 0) {
            clearInterval(iv);
            window.location.href = '{{ route('carrito.index') }}';
        }
    }, 1000);
}

// Polling al servidor cada 10 segundos para sincronizar
async function checkTimerServidor() {
    if (timerExpired) return;
    try {
        const r = await fetch('{{ route('pago.timer.status') }}');
        const d = await r.json();
        if (d.expirado) expirarTimer();
    } catch(e) {}
}

actualizarTimer();
const iv1 = setInterval(actualizarTimer, 1000);
const iv2 = setInterval(checkTimerServidor, 10000);


// ── Tarjeta visual interactiva ────────────────────────────────────────────────
const inputNumero  = document.getElementById('inputNumero');
const inputTitular = document.getElementById('inputTitular');
const inputMes     = document.getElementById('inputMes');
const inputAnio    = document.getElementById('inputAnio');
const cvNumero     = document.getElementById('cvNumero');
const cvTitular    = document.getElementById('cvTitular');
const cvVence      = document.getElementById('cvVence');
const cvBrand      = document.getElementById('cvBrand');
const cardVisual   = document.getElementById('cardVisual');

function detectarBrand(num) {
    if (/^4/.test(num))      return { label: 'VISA', color: 'linear-gradient(135deg,#1a1a2e,#1434CB)' };
    if (/^5[1-5]/.test(num)) return { label: 'MC',   color: 'linear-gradient(135deg,#1a1a2e,#c0392b)' };
    if (/^3[47]/.test(num))  return { label: 'AMEX', color: 'linear-gradient(135deg,#1a1a2e,#007BC1)' };
    return { label: '•', color: 'linear-gradient(135deg,#1a1a2e,#534AB7)' };
}

function formatNumero(v) {
    const clean = v.replace(/\D/g,'').slice(0,16);
    return clean.replace(/(.{4})/g,'$1 ').trim();
}

if (inputNumero) {
    inputNumero.addEventListener('input', function() {
        const raw   = this.value.replace(/\D/g,'');
        this.value  = formatNumero(this.value);
        const brand = detectarBrand(raw);
        const disp  = this.value.padEnd(19, '•').replace(/ /g,'•').replace(/(.{4})/g,'$1 ').trim();
        cvNumero.textContent = this.value || '•••• •••• •••• ••••';
        cvBrand.textContent  = brand.label;
        cardVisual.style.background = brand.color + ' 100%';
    });
}
if (inputTitular) {
    inputTitular.addEventListener('input', function() {
        cvTitular.textContent = this.value.toUpperCase() || 'NOMBRE APELLIDO';
    });
}
function actualizarVence() {
    const m = inputMes?.value?.padStart(2,'0') || 'MM';
    const a = inputAnio?.value?.slice(-2) || 'AA';
    if (cvVence) cvVence.textContent = `${m}/${a}`;
}
inputMes?.addEventListener('input', actualizarVence);
inputAnio?.addEventListener('input', actualizarVence);


// ── Toggle nueva tarjeta vs guardada ─────────────────────────────────────────
function toggleNuevaForm(show) {
    const sec = document.getElementById('sectionNuevaTarjeta');
    if (sec) sec.style.display = show ? '' : 'none';

    // Si elige guardada, limpiar radio de nueva
    if (!show) {
        const radioNueva = document.getElementById('tc_nueva');
        if (radioNueva) radioNueva.checked = false;
    }
}

// Al cargar si hay tarjetas guardadas, ocultar la sección nueva por defecto
document.addEventListener('DOMContentLoaded', () => {
    const hayGuardadas = {{ $tarjetas->isNotEmpty() ? 'true' : 'false' }};
    if (hayGuardadas) {
        // Si ningún radio está checked, marcar la primera
        const radios = document.querySelectorAll('input[name="tarjeta_guardada_id"]');
        const alguno = Array.from(radios).some(r => r.checked && r.value !== '');
        if (!alguno) {
            const primero = Array.from(radios).find(r => r.value !== '');
            if (primero) primero.checked = true;
        }
    }
});


// ── Toggle guardar / predeterminada ─────────────────────────────────────────
function togglePredeterminada(show) {
    const opt = document.getElementById('optPredeterminada');
    if (opt) opt.style.display = show ? '' : 'none';
}

// ── Prevenir doble submit ────────────────────────────────────────────────────
document.getElementById('formPago')?.addEventListener('submit', function() {
    if (timerExpired) { event.preventDefault(); return; }
    btnPagar.disabled = true;
    btnPagar.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Procesando...';
});
</script>
@endpush