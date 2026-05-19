{{-- resources/views/dashboard/plan.blade.php --}}
@extends('layouts.dashboard')

@section('titulo_pagina', 'Mi Plan — Tools365')
@section('topbar_title', 'Mi Plan')

@push('css')
<link rel="stylesheet" href="{{ asset('css/plan_dashboard.css') }}">
@endpush

@section('contenido')
@php
    $user     = auth()->user();
    $plan     = $user->plan ?? 'basico';   // basico | pro | empresarial

    // Límites según plan (ajusta a tu lógica real)
    $limites = [
        'basico'      => ['publicaciones' => 5,  'rentas' => 10,  'precio' => 0],
        'pro'         => ['publicaciones' => 30, 'rentas' => 100, 'precio' => 299],
        'empresarial' => ['publicaciones' => 999,'rentas' => 999, 'precio' => 799],
    ];
    $limite = $limites[$plan];

    // Uso actual
    $pubsActivas = $user->productos()->where('estado','activo')->count();
    $rentasActivas = \App\Models\PedidoItem::where('vendedor_id',$user->id)
        ->where('tipo_accion','rentar')
        ->whereHas('pedido',fn($q)=>$q->where('estado','pagado'))
        ->count();
    $ventasTotal = \App\Models\PedidoItem::where('vendedor_id',$user->id)
        ->whereHas('pedido',fn($q)=>$q->where('estado','pagado'))
        ->count();

    // Fecha renovación simulada (en producción vendría de la BD)
    $renovacion = now()->addDays(18);
    $diasRestantes = now()->diffInDays($renovacion);
    $vencePronto = $diasRestantes <= 7;

    $planInfo = [
        'basico'      => ['emoji'=>'⚡','label'=>'Básico','desc'=>'Perfecto para empezar a publicar y rentar en Tools365.','color'=>'basico'],
        'pro'         => ['emoji'=>'🚀','label'=>'Pro','desc'=>'Más publicaciones, más rentas y soporte prioritario.','color'=>'pro'],
        'empresarial' => ['emoji'=>'👑','label'=>'Empresarial','desc'=>'Sin límites. Para empresas y arrendadoras profesionales.','color'=>'empresarial'],
    ];
    $info = $planInfo[$plan];

    $pctPubs  = $limite['publicaciones'] >= 999 ? 100 : min(100, round($pubsActivas/$limite['publicaciones']*100));
    $pctRents = $limite['rentas'] >= 999 ? 100 : min(100, round($rentasActivas/$limite['rentas']*100));
@endphp

<div class="container py-4">

    {{-- ══ HERO PLAN ACTIVO ══════════════════════════════════════════ --}}
    <div class="plan-hero">
        <div class="plan-hero-bg {{ $info['color'] }}"></div>
        <div class="plan-hero-content">
            <div>
                <div class="plan-hero-badge">Plan activo</div>
                <div class="plan-hero-name">{{ $info['emoji'] }} {{ $info['label'] }}</div>
                <p class="plan-hero-desc">{{ $info['desc'] }}</p>
                @if($plan !== 'basico')
                    <div class="plan-renew-pill {{ $vencePronto ? 'vence-pronto' : '' }}">
                        <i class="bi bi-arrow-repeat"></i>
                        @if($vencePronto)
                            ⚠️ Renueva en {{ $diasRestantes }} días — {{ $renovacion->format('d/m/Y') }}
                        @else
                            Se renueva el {{ $renovacion->format('d \d\e F, Y') }}
                        @endif
                    </div>
                @else
                    <div class="plan-renew-pill">
                        <i class="bi bi-infinity"></i> Plan gratuito — sin fecha de vencimiento
                    </div>
                @endif
            </div>
            <div class="plan-hero-icon">
                @if($plan==='basico') ⚡
                @elseif($plan==='pro') 🚀
                @else 👑 @endif
            </div>
        </div>
    </div>

    {{-- ══ STATS ══════════════════════════════════════════════════════ --}}
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
                @if($plan !== 'basico') Días restantes @else Días en Tools365 @endif
            </div>
            <div class="plan-stat-val">
                @if($plan !== 'basico') {{ $diasRestantes }}
                @else {{ $user->created_at->diffInDays(now()) }} @endif
            </div>
            <div class="plan-stat-sub">
                @if($plan !== 'basico') hasta renovación @else desde que te registraste @endif
            </div>
        </div>
    </div>

    {{-- ══ USO DEL PLAN ════════════════════════════════════════════════ --}}
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

    {{-- ══ COMPARATIVA / MEJORA ════════════════════════════════════════ --}}
    <h3 class="section-title"><i class="bi bi-layers"></i> Planes disponibles</h3>
    <div class="planes-grid">

        {{-- Básico --}}
        <div class="plan-card {{ $plan==='basico' ? 'actual' : '' }}">
            @if($plan==='basico') <div class="plan-card-badge badge-actual">Tu plan</div> @endif
            <div class="plan-card-icon">⚡</div>
            <div class="plan-card-name">Básico</div>
            <div class="plan-card-free">Gratis</div>
            <ul class="plan-features">
                <li><i class="bi bi-check-lg fi"></i> 5 publicaciones activas</li>
                <li><i class="bi bi-check-lg fi"></i> 10 rentas simultáneas</li>
                <li><i class="bi bi-check-lg fi"></i> Subastas habilitadas</li>
                <li><i class="bi bi-x-lg fx"></i> Soporte prioritario</li>
                <li><i class="bi bi-x-lg fx"></i> Estadísticas avanzadas</li>
                <li><i class="bi bi-x-lg fx"></i> Badge de vendedor verificado</li>
            </ul>
            <button class="btn-plan btn-plan-current" disabled>
                <i class="bi bi-check-circle-fill"></i>
                @if($plan==='basico') Plan actual @else Plan menor @endif
            </button>
        </div>

        {{-- Pro --}}
        <div class="plan-card {{ $plan==='pro' ? 'actual pro' : '' }}">
            <div class="plan-card-badge {{ $plan==='pro' ? 'badge-actual' : 'badge-popular' }}">
                {{ $plan==='pro' ? 'Tu plan' : 'Popular' }}
            </div>
            <div class="plan-card-icon">🚀</div>
            <div class="plan-card-name">Pro</div>
            <div class="plan-card-price">
                <span class="cur">$</span>299
                <span class="per">/mes</span>
            </div>
            <ul class="plan-features">
                <li><i class="bi bi-check-lg fi"></i> 30 publicaciones activas</li>
                <li><i class="bi bi-check-lg fi"></i> 100 rentas simultáneas</li>
                <li><i class="bi bi-check-lg fi"></i> Subastas habilitadas</li>
                <li><i class="bi bi-check-lg fi"></i> Soporte prioritario</li>
                <li><i class="bi bi-check-lg fi"></i> Estadísticas avanzadas</li>
                <li><i class="bi bi-x-lg fx"></i> Badge de vendedor verificado</li>
            </ul>
            @if($plan==='pro')
                <button class="btn-plan btn-plan-current" disabled>
                    <i class="bi bi-check-circle-fill"></i> Plan actual
                </button>
            @elseif($plan==='basico')
                <a href="#" class="btn-plan btn-plan-upgrade"
                   onclick="confirmarUpgrade('Pro','$299/mes')">
                    <i class="bi bi-arrow-up-circle-fill"></i> Mejorar a Pro
                </a>
            @else
                <button class="btn-plan btn-plan-current" disabled>
                    <i class="bi bi-arrow-down-circle"></i> Plan menor
                </button>
            @endif
        </div>

        {{-- Empresarial --}}
        <div class="plan-card {{ $plan==='empresarial' ? 'actual empresarial' : '' }}">
            <div class="plan-card-badge {{ $plan==='empresarial' ? 'badge-actual' : 'badge-top' }}">
                {{ $plan==='empresarial' ? 'Tu plan' : 'Premium' }}
            </div>
            <div class="plan-card-icon">👑</div>
            <div class="plan-card-name">Empresarial</div>
            <div class="plan-card-price">
                <span class="cur">$</span>799
                <span class="per">/mes</span>
            </div>
            <ul class="plan-features">
                <li><i class="bi bi-check-lg fi"></i> Publicaciones ilimitadas</li>
                <li><i class="bi bi-check-lg fi"></i> Rentas ilimitadas</li>
                <li><i class="bi bi-check-lg fi"></i> Subastas habilitadas</li>
                <li><i class="bi bi-check-lg fi"></i> Soporte 24/7 dedicado</li>
                <li><i class="bi bi-check-lg fi"></i> Estadísticas avanzadas</li>
                <li><i class="bi bi-check-lg fi"></i> Badge de vendedor verificado</li>
            </ul>
            @if($plan==='empresarial')
                <button class="btn-plan btn-plan-current" disabled>
                    <i class="bi bi-check-circle-fill"></i> Plan actual
                </button>
            @else
                <a href="#" class="btn-plan btn-plan-top"
                   onclick="confirmarUpgrade('Empresarial','$799/mes')">
                    <i class="bi bi-stars"></i> Mejorar a Empresarial
                </a>
            @endif
        </div>

    </div>

    {{-- ══ HISTORIAL DE PAGOS ══════════════════════════════════════════ --}}
    <div class="card-base">
        <h3 class="section-title mb-3">
            <i class="bi bi-receipt"></i> Historial de pagos del plan
        </h3>
        @php
            // En producción esto vendría de una tabla plan_pagos o suscripciones
            $historial = [
                ['tipo'=>'pagado',  'desc'=>'Renovación Plan Pro',      'fecha'=>now()->subMonth(),    'monto'=>'$299.00'],
                ['tipo'=>'upgrade', 'desc'=>'Upgrade Básico → Pro',      'fecha'=>now()->subMonths(2),  'monto'=>'$299.00'],
                ['tipo'=>'pagado',  'desc'=>'Plan Básico activado',      'fecha'=>now()->subMonths(6),  'monto'=>'Gratis'],
            ];
        @endphp
        @if(count($historial))
            @foreach($historial as $h)
            <div class="historial-item">
                <div class="historial-dot dot-{{ $h['tipo'] }}">
                    @if($h['tipo']==='pagado') <i class="bi bi-check-lg"></i>
                    @elseif($h['tipo']==='upgrade') <i class="bi bi-arrow-up-circle"></i>
                    @else <i class="bi bi-x-lg"></i> @endif
                </div>
                <div class="historial-info">
                    <strong>{{ $h['desc'] }}</strong>
                    <small>{{ \Carbon\Carbon::parse($h['fecha'])->format('d/m/Y \a \l\a\s H:i') }}</small>
                </div>
                <div class="historial-monto">{{ $h['monto'] }}</div>
            </div>
            @endforeach
        @else
            <p class="text-muted" style="font-size:.88rem;">Sin historial de pagos aún.</p>
        @endif
    </div>

    {{-- ══ ZONA DE PELIGRO ═════════════════════════════════════════════ --}}
    @if($plan !== 'basico')
    <div class="danger-zone">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h4><i class="bi bi-exclamation-triangle-fill me-1"></i> Cancelar suscripción</h4>
                <p>
                    Si cancelas, tu plan seguirá activo hasta el
                    <strong>{{ $renovacion->format('d/m/Y') }}</strong>.
                    Después regresarás al plan Básico gratuito y tus publicaciones
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

{{-- ══ MODAL UPGRADE ═══════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalUpgrade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;overflow:hidden;">
            <div style="background:linear-gradient(135deg,#3b0764,#7c3aed);padding:2rem;text-align:center;color:#fff;">
                <div style="font-size:3rem;">🚀</div>
                <h4 class="fw-bold mt-2 mb-1">¡Mejora tu plan!</h4>
                <p class="mb-0" style="opacity:.85;" id="upgradeSubtitle">Desbloquea más funcionalidades</p>
            </div>
            <div class="modal-body p-4">
                <p style="font-size:.88rem;color:var(--plan-muted);text-align:center;margin:0;">
                    Esta función está en desarrollo. Por ahora contacta al equipo de soporte para cambiar tu plan.
                </p>
                <div class="d-flex gap-2 mt-4">
                    <button class="btn btn-secondary flex-fill" data-bs-dismiss="modal">Cancelar</button>
                    <a href="{{ route('contacto.index') }}" class="btn btn-primary flex-fill">
                        <i class="bi bi-chat-dots me-1"></i> Contactar soporte
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ MODAL CANCELAR ══════════════════════════════════════════════════ --}}
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
                    <li>Al vencer, regresarás al plan Básico</li>
                    <li>Publicaciones que superen el límite serán pausadas</li>
                </ul>
                <div class="d-flex gap-2">
                    <button class="btn btn-secondary flex-fill" data-bs-dismiss="modal">
                        Mantener plan
                    </button>
                    <form method="POST" action="#" class="flex-fill">
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
const modalUpgrade  = new bootstrap.Modal(document.getElementById('modalUpgrade'));
const modalCancelar = new bootstrap.Modal(document.getElementById('modalCancelar'));

function confirmarUpgrade(nombre, precio) {
    event.preventDefault();
    document.getElementById('upgradeSubtitle').textContent =
        `Cambiar a Plan ${nombre} — ${precio}`;
    modalUpgrade.show();
}

function confirmarCancelacion() {
    modalCancelar.show();
}
</script>
@endpush