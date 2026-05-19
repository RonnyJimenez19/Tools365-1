{{-- resources/views/dashboard/admin/ingresos.blade.php --}}
@extends('layouts.dashboard')

@section('titulo_pagina', 'Ingresos de la plataforma – Tools365 Admin')
@section('topbar_title', 'Ingresos & Comisiones')

@push('css')
<style>
/* ── KPI cards ──────────────────────────────────────────────────────────────── */
.kpi-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
.kpi-card {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: .875rem; padding: 1.1rem 1.25rem;
    display: flex; flex-direction: column; gap: .25rem;
}
.kpi-card .kpi-icon {
    width: 40px; height: 40px; border-radius: .625rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; margin-bottom: .5rem;
}
.kpi-icon.green  { background: #e6f9f0; color: #1a7f4b; }
.kpi-icon.purple { background: #ede9ff; color: #534AB7; }
.kpi-icon.blue   { background: #e8f4fd; color: #1a6fa8; }
.kpi-icon.red    { background: #fff1f0; color: #c0392b; }
.kpi-icon.amber  { background: #fff8e1; color: #b45309; }
.kpi-label { font-size: .75rem; color: var(--bs-secondary-color); font-weight: 600; text-transform: uppercase; letter-spacing: .05em; }
.kpi-value { font-size: 1.4rem; font-weight: 800; }
.kpi-sub   { font-size: .75rem; color: var(--bs-secondary-color); }

/* ── Section cards ──────────────────────────────────────────────────────────── */
.sec-card {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: .875rem; overflow: hidden; margin-bottom: 1.5rem;
}
.sec-card-header {
    padding: .9rem 1.25rem;
    border-bottom: 1px solid var(--bs-border-color);
    background: var(--bs-tertiary-bg);
    font-weight: 700; font-size: .9rem;
    display: flex; align-items: center; gap: .5rem;
}

/* ── Tabla genérica ─────────────────────────────────────────────────────────── */
.admin-table { width: 100%; border-collapse: collapse; font-size: .85rem; }
.admin-table thead th {
    padding: .6rem 1rem;
    font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em;
    color: var(--bs-secondary-color);
    border-bottom: 1.5px solid var(--bs-border-color);
    background: var(--bs-tertiary-bg);
    white-space: nowrap;
}
.admin-table tbody tr { border-bottom: 1px solid var(--bs-border-color); }
.admin-table tbody tr:last-child { border-bottom: none; }
.admin-table tbody tr:hover { background: var(--bs-tertiary-bg); }
.admin-table td { padding: .8rem 1rem; vertical-align: middle; }

/* ── Badges ─────────────────────────────────────────────────────────────────── */
.badge-tipo {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .22em .65em; border-radius: 999px; font-size: .72rem; font-weight: 700;
}
.tipo-comprar { background: #dcfce7; color: #166534; }
.tipo-rentar  { background: #dbeafe; color: #1d4ed8; }
.tipo-subasta { background: #fef3c7; color: #92400e; }

.plan-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .22em .65em; border-radius: 999px; font-size: .72rem; font-weight: 700;
}
.plan-free        { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
.plan-basico      { background: #ede9fe; color: #5b21b6; border: 1px solid #ddd6fe; }
.plan-profesional { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }

.price-green { color: #1a7f4b; font-weight: 700; }
.price-red   { color: #c0392b; font-weight: 700; }

/* ── Periodo selector ───────────────────────────────────────────────────────── */
.periodo-pills { display: flex; gap: .5rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
.periodo-pills a {
    padding: .4rem 1rem; border-radius: 999px; font-size: .82rem; font-weight: 600;
    border: 1.5px solid var(--bs-border-color);
    color: var(--bs-body-color); text-decoration: none;
    transition: all .15s;
}
.periodo-pills a.active,
.periodo-pills a:hover {
    background: #534AB7; color: #fff; border-color: #534AB7;
}

/* ── Top vendedores ─────────────────────────────────────────────────────────── */
.rank-num {
    width: 28px; height: 28px; border-radius: 50%;
    background: var(--bs-tertiary-bg); border: 1.5px solid var(--bs-border-color);
    display: flex; align-items: center; justify-content: center;
    font-size: .78rem; font-weight: 800; flex-shrink: 0;
}
.rank-num.gold   { background: #fef3c7; border-color: #fbbf24; color: #92400e; }
.rank-num.silver { background: #f1f5f9; border-color: #94a3b8; color: #475569; }
.rank-num.bronze { background: #fff7ed; border-color: #fb923c; color: #c2410c; }
</style>
@endpush

@section('contenido')

{{-- Header ──────────────────────────────────────────────────────── --}}
<div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-3">
    <div>
        <h1 style="font-size:1.5rem;font-weight:800;margin:0 0 .2rem;">
            <i class="bi bi-graph-up-arrow me-2" style="color:#534AB7;"></i>Ingresos de la plataforma
        </h1>
        <p style="margin:0;color:var(--bs-secondary-color);font-size:.88rem;">
            Comisiones generadas por transacciones + suscripciones de plan
        </p>
    </div>
    <a href="{{ route('admin.usuarios') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Panel admin
    </a>
</div>

{{-- Selector de período ─────────────────────────────────────────── --}}
<div class="periodo-pills">
    @foreach([7 => 'Últimos 7 días', 30 => 'Últimos 30 días', 90 => 'Últimos 90 días', 365 => 'Este año'] as $dias => $label)
        <a href="{{ route('admin.ingresos', ['periodo' => $dias]) }}"
           class="{{ $periodo == $dias ? 'active' : '' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

{{-- KPI cards ───────────────────────────────────────────────────── --}}
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-icon green"><i class="bi bi-cash-coin"></i></div>
        <div class="kpi-label">Comisiones ganadas</div>
        <div class="kpi-value">${{ number_format($totalComision, 2) }}</div>
        <div class="kpi-sub">De ${{ number_format($totalBruto, 2) }} facturado</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon purple"><i class="bi bi-stars"></i></div>
        <div class="kpi-label">Suscripciones (plan)</div>
        <div class="kpi-value">${{ number_format($ingresosSubs, 2) }}</div>
        <div class="kpi-sub">{{ $cantidadSubs }} pagos de plan</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon blue"><i class="bi bi-wallet2"></i></div>
        <div class="kpi-label">Ingreso total plataforma</div>
        <div class="kpi-value">${{ number_format($totalComision + $ingresosSubs, 2) }}</div>
        <div class="kpi-sub">Comisiones + suscripciones</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon amber"><i class="bi bi-arrow-left-right"></i></div>
        <div class="kpi-label">Transacciones</div>
        <div class="kpi-value">{{ $totalTx }}</div>
        <div class="kpi-sub">En el período seleccionado</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon red"><i class="bi bi-percent"></i></div>
        <div class="kpi-label">Tasa promedio efectiva</div>
        <div class="kpi-value">
            @if($totalBruto > 0)
                {{ number_format(($totalComision / $totalBruto) * 100, 1) }}%
            @else 0% @endif
        </div>
        <div class="kpi-sub">Sobre el total facturado</div>
    </div>
</div>

{{-- Por tipo de transacción ─────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="sec-card">
            <div class="sec-card-header">
                <i class="bi bi-pie-chart-fill" style="color:#534AB7;"></i> Por tipo de transacción
            </div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                        <th>Bruto</th>
                        <th>Comisión</th>
                        <th>Neto vendedor</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($porTipo as $row)
                    <tr>
                        <td>
                            <span class="badge-tipo tipo-{{ $row->tipo_accion }}">
                                @if($row->tipo_accion === 'comprar')
                                    <i class="bi bi-bag-check-fill"></i> Venta
                                @elseif($row->tipo_accion === 'rentar')
                                    <i class="bi bi-clock-history"></i> Renta
                                @else
                                    <i class="bi bi-hammer"></i> Subasta
                                @endif
                            </span>
                        </td>
                        <td>{{ $row->cantidad }}</td>
                        <td>${{ number_format($row->bruto, 2) }}</td>
                        <td class="price-red">${{ number_format($row->comision, 2) }}</td>
                        <td class="price-green">${{ number_format($row->neto, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">Sin datos en este período</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="sec-card">
            <div class="sec-card-header">
                <i class="bi bi-layers-fill" style="color:#534AB7;"></i> Por plan del vendedor
            </div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Plan</th>
                        <th>Tx</th>
                        <th>Bruto</th>
                        <th>Comisión</th>
                        <th>Tasa real</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($porPlan as $row)
                    <tr>
                        <td>
                            <span class="plan-badge plan-{{ $row->plan ?? 'free' }}">
                                @if(($row->plan ?? 'free') === 'profesional') 💎
                                @elseif(($row->plan ?? 'free') === 'basico') ⚡
                                @else 🌱 @endif
                                {{ ucfirst($row->plan ?? 'free') }}
                            </span>
                        </td>
                        <td>{{ $row->cantidad }}</td>
                        <td>${{ number_format($row->bruto, 2) }}</td>
                        <td class="price-red">${{ number_format($row->comision, 2) }}</td>
                        <td>
                            <span style="font-weight:700;font-size:.82rem;">
                                @if($row->bruto > 0)
                                    {{ number_format(($row->comision / $row->bruto) * 100, 1) }}%
                                @else 0% @endif
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">Sin datos en este período</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Top vendedores ──────────────────────────────────────────────── --}}
<div class="sec-card">
    <div class="sec-card-header">
        <i class="bi bi-trophy-fill" style="color:#f59e0b;"></i> Top vendedores por comisión generada
    </div>
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Vendedor</th>
                <th>Plan</th>
                <th>Ventas</th>
                <th>Bruto</th>
                <th>Comisión para plataforma</th>
                <th>Neto vendedor</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topVendedores as $i => $v)
            <tr>
                <td>
                    <div class="rank-num {{ $i === 0 ? 'gold' : ($i === 1 ? 'silver' : ($i === 2 ? 'bronze' : '')) }}">
                        {{ $i + 1 }}
                    </div>
                </td>
                <td>
                    <div style="font-weight:700;font-size:.85rem;">{{ $v->vendedor?->name ?? '—' }}</div>
                    <div style="font-size:.75rem;color:var(--bs-secondary-color);">{{ $v->vendedor?->email }}</div>
                </td>
                <td>
                    <span class="plan-badge plan-{{ $v->vendedor?->plan ?? 'free' }}">
                        {{ ucfirst($v->vendedor?->plan ?? 'free') }}
                    </span>
                </td>
                <td>{{ $v->ventas }}</td>
                <td>${{ number_format($v->bruto, 2) }}</td>
                <td class="price-red">${{ number_format($v->comision, 2) }}</td>
                <td class="price-green">${{ number_format($v->neto, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center text-muted py-4">Sin transacciones en este período</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Últimas transacciones ───────────────────────────────────────── --}}
<div class="sec-card">
    <div class="sec-card-header">
        <i class="bi bi-clock-history" style="color:#534AB7;"></i> Últimas transacciones
    </div>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Pedido</th>
                <th>Producto</th>
                <th>Tipo</th>
                <th>Vendedor / Plan</th>
                <th>Bruto</th>
                <th>Comisión</th>
                <th>Neto</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ultimasTx as $tx)
            <tr>
                <td>
                    <span style="font-size:.8rem;font-weight:700;color:#534AB7;">
                        {{ $tx->pedido?->folio }}
                    </span>
                </td>
                <td style="max-width:180px;">
                    <div style="font-size:.82rem;font-weight:600;">{{ Str::limit($tx->titulo, 36) }}</div>
                </td>
                <td>
                    <span class="badge-tipo tipo-{{ $tx->tipo_accion }}">
                        @if($tx->tipo_accion === 'comprar') <i class="bi bi-bag-check-fill"></i> Venta
                        @elseif($tx->tipo_accion === 'rentar') <i class="bi bi-clock-history"></i> Renta
                        @else <i class="bi bi-hammer"></i> Subasta @endif
                    </span>
                </td>
                <td>
                    <div style="font-size:.82rem;font-weight:600;">{{ $tx->vendedor?->name ?? '—' }}</div>
                    <span class="plan-badge plan-{{ $tx->vendedor?->plan ?? 'free' }}" style="font-size:.68rem;">
                        {{ ucfirst($tx->vendedor?->plan ?? 'free') }}
                    </span>
                </td>
                <td>${{ number_format($tx->total_item, 2) }}</td>
                <td class="price-red">
                    –${{ number_format($tx->comision_plataforma, 2) }}
                    @if($tx->comision_pct > 0)
                        <span style="font-size:.68rem;opacity:.7;">({{ (int)($tx->comision_pct*100) }}%)</span>
                    @endif
                </td>
                <td class="price-green">${{ number_format($tx->neto_vendedor, 2) }}</td>
                <td style="font-size:.78rem;color:var(--bs-secondary-color);">
                    {{ $tx->created_at->format('d/m/Y H:i') }}
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted py-4">Sin transacciones en este período</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection