@extends('layouts.dashboard')

@section('titulo_pagina', 'Mi Plan — Tools365')
@section('topbar_title', 'Mi Plan')

@push('css')
<link rel="stylesheet" href="{{ asset('css/plan_dashboard.css') }}">
@endpush

@section('contenido')
@php
    use App\Services\PlanService;

    $user = auth()->user();
    $plan = $user->plan ?? 'free';

    // ── Límites alineados con PlanService ─────────────────────────────────────
    $limites = [
        'free'         => ['publicaciones' => 5,   'rentas' => 10,  'precio' => 0],
        'basico'       => ['publicaciones' => 20,  'rentas' => 50,  'precio' => 199],
        'profesional'  => ['publicaciones' => 999, 'rentas' => 999, 'precio' => 599],
    ];

    // Fallback seguro: si el plan en BD es un valor inesperado, usamos 'free'
    $limite = $limites[$plan] ?? $limites['free'];

    $pubsActivas   = $user->productos()->where('estado', 'activo')->count();
    $rentasActivas = \App\Models\PedidoItem::where('vendedor_id', $user->id)
        ->where('tipo_accion', 'rentar')
        ->whereHas('pedido', fn($q) => $q->where('estado', 'pagado'))
        ->count();
    $ventasTotal = \App\Models\PedidoItem::where('vendedor_id', $user->id)
        ->whereHas('pedido', fn($q) => $q->where('estado', 'pagado'))
        ->count();

    $renovacion    = now()->addDays(18);
    $diasRestantes = now()->diffInDays($renovacion);
    $vencePronto   = $diasRestantes <= 7;

    // ── Info visual por plan ───────────────────────────────────────────────────
    $planInfo = [
        'free'        => ['emoji' => '🌱', 'label' => 'Free',        'desc' => 'Plan gratuito para empezar en Tools365.',                          'color' => 'free'],
        'basico'      => ['emoji' => '⚡', 'label' => 'Básico',      'desc' => 'Perfecto para vendedores y arrendadores activos.',                  'color' => 'basico'],
        'profesional' => ['emoji' => '💎', 'label' => 'Profesional', 'desc' => 'Sin límites. Para empresas y arrendadoras de alto volumen.',        'color' => 'profesional'],
    ];
    $info = $planInfo[$plan] ?? $planInfo['free'];

    $pctPubs  = $limite['publicaciones'] >= 999 ? 100 : min(100, $limite['publicaciones'] > 0 ? round($pubsActivas  / $limite['publicaciones'] * 100) : 0);
    $pctRents = $limite['rentas']         >= 999 ? 100 : min(100, $limite['rentas']         > 0 ? round($rentasActivas / $limite['rentas']         * 100) : 0);
@endphp

<div class="container py-4">

    {{-- ╔═ HERO PLAN ACTIVO ══════════════════════════════════════════════════ --}}
    <div class="plan-hero">
        <div class="plan-hero-bg {{ $info['color'] }}"></div>
        <div class="plan-hero-content">
            <div>
                <div class="plan-hero-badge">Plan activo</div>
                <div class="plan-hero-name">{{ $info['emoji'] }} {{ $info['label'] }}</div>
                <p class="plan-hero-desc">{{ $info['desc'] }}</p>
                @if($plan === 'free')
                    <div class="plan-renew-pill">
                        <i class="bi bi-infinity"></i> Plan gratuito — sin fecha de vencimiento
                    </div>
                @else
                    <div class="plan-renew-pill {{ $vencePronto ? 'vence-pronto' : '' }}">
                        <i class="bi bi-arrow-repeat"></i>
                        @if($vencePronto)
                            ⚠️ Renueva en {{ $diasRestantes }} días — {{ $renovacion->format('d/m/Y') }}
                        @else
                            Se renueva el {{ $renovacion->format('d \d\e F, Y') }}
                        @endif
                    </div>
                @endif
            </div>
            <div class="plan-hero-icon">{{ $info['emoji'] }}</div>
        </div>
    </div>

    {{-- ╔═ STATS ══════════════════════════════════════════════════════════════ --}}
    <div class="plan-stats">
        <div class="plan-stat">
            <div class="plan-stat-icon icon-blue"><i class="bi bi-megaphone-fill"></i></div>
            <div class="plan-stat-label">Publicaciones activas</div>
            <div class="plan-stat-val">{{ $pubsActivas }}</div>
            <div class="plan-stat-sub">
                de {{ $limite['publicaciones'] >= 999 ? '∞' : $limite['publicaciones'] }} permitidas
            </div>
        </div>
        <div class="plan-stat">
            <div class="plan-stat-icon icon-purple"><i class="bi bi-clock-history"></i></div>
            <div class="plan-stat-label">Rentas gestionadas</div>
            <div class="plan-stat-val">{{ $rentasActivas }}</div>
            <div class="plan-stat-sub">
                de {{ $limite['rentas'] >= 999 ? '∞' : $limite['rentas'] }} permitidas
            </div>
        </div>
        <div class="plan-stat">
            <div class="plan-stat-icon icon-green"><i class="bi bi-bag-check-fill"></i></div>
            <div class="plan-stat-label">Ventas totales</div>
            <div class="plan-stat-val">{{ $ventasTotal }}</div>
            <div class="plan-stat-sub">desde que iniciaste</div>
        </div>
        <div class="plan-stat">
            <div class="plan-stat-icon icon-amber"><i class="bi bi-calendar3"></i></div>
            <div class="plan-stat-label">
                @if($plan !== 'free') Días restantes @else Días en Tools365 @endif
            </div>
            <div class="plan-stat-val">
                @if($plan !== 'free') {{ $diasRestantes }}
                @else {{ $user->created_at->diffInDays(now()) }} @endif
            </div>
            <div class="plan-stat-sub">
                @if($plan !== 'free') hasta renovación @else desde que te registraste @endif
            </div>
        </div>
    </div>

    {{-- ╔═ USO DEL PLAN ══════════════════════════════════════════════════════ --}}
    <div class="uso-wrap">
        <h3><i class="bi bi-bar-chart-fill me-2" style="color:var(--plan-muted)"></i>Uso del plan</h3>
        <div class="uso-row">
            <div class="uso-label">
                Publicaciones activas
                <span>{{ $pubsActivas }} / {{ $limite['publicaciones'] >= 999 ? '∞' : $limite['publicaciones'] }}</span>
            </div>
            <div class="uso-bar-bg">
                <div class="uso-bar-fill {{ $pctPubs >= 90 ? 'fill-danger' : 'fill-blue' }}"
                     style="width:{{ $pctPubs }}%"></div>
            </div>
        </div>
        <div class="uso-row">
            <div class="uso-label">
                Rentas activas
                <span>{{ $rentasActivas }} / {{ $limite['rentas'] >= 999 ? '∞' : $limite['rentas'] }}</span>
            </div>
            <div class="uso-bar-bg">
                <div class="uso-bar-fill {{ $pctRents >= 90 ? 'fill-danger' : 'fill-purple' }}"
                     style="width:{{ $pctRents }}%"></div>
            </div>
        </div>
        @if($pctPubs >= 80 || $pctRents >= 80)
        <div class="alert alert-warning mt-3 mb-0 rounded-3 py-2 px-3" style="font-size:.83rem;">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            Estás cerca del límite de tu plan. Considera mejorar tu plan para no interrumpir tu operación.
        </div>
        @endif
    </div>

    {{-- ╔═ PLANES DISPONIBLES ════════════════════════════════════════════════ --}}
    <h3 class="section-title"><i class="bi bi-layers"></i> Planes disponibles</h3>
    <div class="planes-grid">

        {{-- FREE --}}
        <div class="plan-card {{ $plan === 'free' ? 'actual' : '' }}">
            @if($plan === 'free') <div class="plan-card-badge badge-actual">Tu plan</div> @endif
            <div class="plan-card-icon">🌱</div>
            <div class="plan-card-name">Free</div>
            <div class="plan-card-free">Gratis</div>
            <ul class="plan-features">
                <li><i class="bi bi-check-lg fi"></i> 5 publicaciones activas</li>
                <li><i class="bi bi-check-lg fi"></i> 3 fotos por publicación</li>
                <li><i class="bi bi-check-lg fi"></i> Soporte por email (48 h)</li>
                <li><i class="bi bi-x-lg fx"></i> Comisión venta: 12%</li>
            </ul>
            <button class="btn-plan btn-plan-current" disabled>
                <i class="bi bi-check-circle-fill"></i>
                @if($plan === 'free') Plan actual @else Plan menor @endif
            </button>
        </div>

        {{-- BÁSICO --}}
        <div class="plan-card {{ $plan === 'basico' ? 'actual' : '' }}">
            <div class="plan-card-badge {{ $plan === 'basico' ? 'badge-actual' : 'badge-popular' }}">
                {{ $plan === 'basico' ? 'Tu plan' : 'Popular' }}
            </div>
            <div class="plan-card-icon">⚡</div>
            <div class="plan-card-name">Básico</div>
            <div class="plan-card-price">
                <span class="cur">$</span>199
                <span class="per">/mes</span>
            </div>
            <ul class="plan-features">
                <li><i class="bi bi-check-lg fi"></i> 20 publicaciones activas</li>
                <li><i class="bi bi-check-lg fi"></i> 6 fotos por publicación</li>
                <li><i class="bi bi-check-lg fi"></i> Soporte por email (24 h)</li>
                <li><i class="bi bi-check-lg fi"></i> Comisión venta: 10%</li>
            </ul>
            @if($plan === 'basico')
                <button class="btn-plan btn-plan-current" disabled>
                    <i class="bi bi-check-circle-fill"></i> Plan actual
                </button>
            @elseif($plan === 'free')
                <a href="{{ route('dashboard.plan.checkout', 'basico') }}" class="btn-plan btn-plan-upgrade">
                    <i class="bi bi-arrow-up-circle-fill"></i> Mejorar a Básico — $199/mes
                </a>
            @else
                <button class="btn-plan btn-plan-current" disabled>
                    <i class="bi bi-arrow-down-circle"></i> Plan menor
                </button>
            @endif
        </div>

        {{-- PROFESIONAL --}}
        <div class="plan-card {{ $plan === 'profesional' ? 'actual profesional' : '' }}">
            <div class="plan-card-badge {{ $plan === 'profesional' ? 'badge-actual' : 'badge-top' }}">
                {{ $plan === 'profesional' ? 'Tu plan' : 'Premium' }}
            </div>
            <div class="plan-card-icon">💎</div>
            <div class="plan-card-name">Profesional</div>
            <div class="plan-card-price">
                <span class="cur">$</span>599
                <span class="per">/mes</span>
            </div>
            <ul class="plan-features">
                <li><i class="bi bi-check-lg fi"></i> Publicaciones ilimitadas</li>
                <li><i class="bi bi-check-lg fi"></i> 10 fotos por publicación</li>
                <li><i class="bi bi-check-lg fi"></i> 10 publicaciones destacadas / mes</li>
                <li><i class="bi bi-check-lg fi"></i> Soporte prioritario 24/7</li>
            </ul>
            @if($plan === 'profesional')
                <button class="btn-plan btn-plan-current" disabled>
                    <i class="bi bi-check-circle-fill"></i> Plan actual
                </button>
            @else
                <a href="{{ route('dashboard.plan.checkout', 'profesional') }}" class="btn-plan btn-plan-top">
                    <i class="bi bi-stars"></i> Mejorar a Profesional — $599/mes
                </a>
            @endif
        </div>

    </div>

    {{-- ╔═ HISTORIAL DE PAGOS ════════════════════════════════════════════════ --}}
    <div class="card-base">
        <h3 class="section-title mb-3">
            <i class="bi bi-receipt"></i> Historial de pagos del plan
        </h3>
        @php
            $historialDB = \App\Models\Pedido::where('user_id', $user->id)
                ->where('folio', 'like', 'PLAN-%')
                ->orderByDesc('created_at')
                ->get();
        @endphp
        @if($historialDB->count())
            @foreach($historialDB as $h)
            <div class="historial-item">
                <div class="historial-dot dot-pagado">
                    <i class="bi bi-check-lg"></i>
                </div>
                <div class="historial-info">
                    <strong>Suscripción — {{ $h->folio }}</strong>
                    <small>{{ $h->created_at->format('d/m/Y \a \l\a\s H:i') }}</small>
                </div>
                <div class="historial-monto">${{ number_format($h->total, 2) }} MXN</div>
            </div>
            @endforeach
        @else
            <p class="text-muted" style="font-size:.88rem;">Sin historial de pagos aún.</p>
        @endif
    </div>

    {{-- ╔═ ZONA DE PELIGRO ═══════════════════════════════════════════════════ --}}
    @if($plan !== 'free')
    <div class="danger-zone">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h4><i class="bi bi-exclamation-triangle-fill me-1"></i> Cancelar suscripción</h4>
                <p>
                    Si cancelas, tu plan seguirá activo hasta el
                    <strong>{{ $renovacion->format('d/m/Y') }}</strong>.
                    Después regresarás al plan Free y tus publicaciones
                    podrían pausarse si superas el límite.
                </p>
            </div>
            <button class="btn-cancelar" onclick="confirmarCancelacion()">
                <i class="bi bi-x-circle me-1"></i> Cancelar suscripción
            </button>
        </div>
    </div>
    @endif

</div>

{{-- ╔═ MODAL CANCELAR ════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalCancelar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;overflow:hidden;">
            <div style="background:linear-gradient(135deg,#7f1d1d,#dc2626);padding:2rem;text-align:center;color:#fff;">
                <div style="font-size:2.5rem;">⚠️</div>
                <h4 class="fw-bold mt-2 mb-1">¿Cancelar suscripción?</h4>
                <p class="mb-0" style="opacity:.85;">Esta acción no se puede deshacer de inmediato</p>
            </div>
            <div class="modal-body p-4">
                <ul style="font-size:.86rem;color:var(--plan-muted);padding-left:1.2rem;margin:0 0 1.2rem;">
                    <li>Tu plan sigue activo hasta el <strong style="color:var(--plan-text)">{{ $renovacion->format('d/m/Y') }}</strong></li>
                    <li>No se realizará ningún cobro adicional</li>
                    <li>Al vencer, regresarás al plan Free</li>
                    <li>Publicaciones que superen el límite serán pausadas</li>
                </ul>
                <div class="d-flex gap-2">
                    <button class="btn btn-secondary flex-fill" data-bs-dismiss="modal">
                        Mantener plan
                    </button>
                    <form method="POST" action="{{ route('dashboard.plan.cancelar') }}" class="flex-fill">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-x-circle me-1"></i> Sí, cancelar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const modalCancelar = new bootstrap.Modal(document.getElementById('modalCancelar'));
function confirmarCancelacion() { modalCancelar.show(); }
</script>
@endpush