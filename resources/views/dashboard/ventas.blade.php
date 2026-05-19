{{-- resources/views/dashboard/ventas.blade.php --}}
@extends('layouts.dashboard')

@section('titulo_pagina', 'Mis ventas – Tools365')
@section('topbar_title', 'Mis Ventas')

@push('css')
<style>
/* ── Stats ─────────────────────────────────────────────────────────────────── */
.vt-stat {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: .75rem;
    padding: 1.1rem 1.25rem;
    display: flex; align-items: center; gap: 1rem;
}
.vt-stat-icon {
    width: 46px; height: 46px; border-radius: .625rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; flex-shrink: 0;
}
.icon-blue   { background: #e8f4fd; color: #1a6fa8; }
.icon-red    { background: #fff1f0; color: #c0392b; }
.icon-green  { background: #e6f9f0; color: #1a7f4b; }
.icon-purple { background: #ede9ff; color: #534AB7; }
.vt-stat-num { font-size: 1.4rem; font-weight: 800; }
.vt-stat-lbl { font-size: .78rem; color: var(--bs-secondary-color); margin-top: .1rem; }

/* ── Banner plan/comisión ──────────────────────────────────────────────────── */
.comision-banner {
    background: var(--bs-tertiary-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: .75rem;
    padding: .9rem 1.25rem;
    display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;
    margin-bottom: 1.5rem;
    font-size: .85rem;
}
.plan-chip {
    display: inline-flex; align-items: center; gap: .35rem;
    background: #534AB7; color: #fff;
    padding: .3em .9em; border-radius: 999px;
    font-size: .78rem; font-weight: 700;
}
.tasa-chip {
    display: inline-flex; align-items: center; gap: .3rem;
    background: #fff1f0; color: #c0392b;
    border: 1px solid #fecaca;
    padding: .28em .8em; border-radius: 999px;
    font-size: .78rem; font-weight: 700;
}
.upgrade-tip {
    margin-left: auto; font-size: .8rem;
    color: #534AB7; font-weight: 600; text-decoration: none;
}
.upgrade-tip:hover { text-decoration: underline; }

/* ── Resumen financiero ────────────────────────────────────────────────────── */
.fin-card {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: .75rem; padding: 1.1rem 1.4rem;
    margin-bottom: 1.5rem;
}
.fin-card h6 {
    font-size: .72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .06em;
    color: var(--bs-secondary-color); margin-bottom: .9rem;
}
.fin-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: .45rem 0; font-size: .87rem;
    border-bottom: 1px dashed var(--bs-border-color);
}
.fin-row:last-child { border-bottom: none; padding-bottom: 0; }
.fin-row .lbl  { color: var(--bs-secondary-color); }
.fin-row .val  { font-weight: 700; }
.fin-row.neto  { font-size: .95rem; }
.fin-row.neto .lbl { color: var(--bs-body-color); font-weight: 700; }
.fin-row.neto .val { color: #1a7f4b; font-size: 1.05rem; }
.fin-row.com  .val { color: #c0392b; }

/* ── Toolbar ───────────────────────────────────────────────────────────────── */
.vt-toolbar { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; margin-bottom: 18px; }
.vt-search {
    flex: 1; min-width: 180px;
    display: flex; align-items: center;
    background: var(--bs-body-bg);
    border: 1.5px solid var(--bs-border-color);
    border-radius: 10px; padding: 0 12px; gap: .5rem;
    transition: border-color .2s;
}
.vt-search:focus-within { border-color: #534AB7; }
.vt-search input {
    border: none; background: transparent; outline: none;
    padding: .6rem .4rem; font-size: .85rem; flex: 1;
    color: var(--bs-body-color);
}
.vt-filter {
    padding: .6rem 1rem; border-radius: 10px;
    border: 1.5px solid var(--bs-border-color);
    background: var(--bs-body-bg);
    font-size: .85rem; color: var(--bs-body-color); cursor: pointer;
}

/* ── Tabla ─────────────────────────────────────────────────────────────────── */
.vt-card {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: .75rem; overflow: hidden;
    box-shadow: 0 2px 16px rgba(0,0,0,.04);
}
.vt-table { width: 100%; border-collapse: collapse; font-size: .85rem; }
.vt-table thead th {
    padding: .65rem 1rem;
    font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em;
    color: var(--bs-secondary-color);
    border-bottom: 1.5px solid var(--bs-border-color);
    background: var(--bs-tertiary-bg); white-space: nowrap;
}
.vt-table tbody tr { border-bottom: 1px solid var(--bs-border-color); transition: background .15s; }
.vt-table tbody tr:last-child { border-bottom: none; }
.vt-table tbody tr:hover { background: var(--bs-tertiary-bg); }
.vt-table td { padding: .9rem 1rem; vertical-align: middle; }

.vt-prod-cell { display: flex; align-items: center; gap: 10px; }
.vt-thumb {
    width: 44px; height: 44px; border-radius: 8px;
    overflow: hidden; flex-shrink: 0;
    border: 1px solid var(--bs-border-color);
    background: var(--bs-tertiary-bg);
    display: flex; align-items: center; justify-content: center;
}
.vt-thumb img { width: 100%; height: 100%; object-fit: cover; }
.vt-titulo { font-weight: 700; font-size: .85rem; }
.vt-meta   { font-size: .75rem; color: var(--bs-secondary-color); }

.badge-tipo {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .25em .65em; border-radius: 999px; font-size: .72rem; font-weight: 700;
}
.tipo-comprar { background: #dcfce7; color: #166534; }
.tipo-rentar  { background: #dbeafe; color: #1d4ed8; }

.badge-comprador {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .78rem; font-weight: 600;
    padding: .25em .65em; border-radius: 999px;
    background: #ede9ff; color: #534AB7;
}

/* ── Precios en tabla ──────────────────────────────────────────────────────── */
.p-bruto    { font-weight: 600; font-size: .88rem; }
.p-comision { color: #c0392b; font-weight: 600; font-size: .85rem; }
.p-pct      {
    display: inline-block; padding: .1em .45em; border-radius: 999px;
    font-size: .68rem; font-weight: 700;
    background: #fff1f0; color: #c0392b; border: 1px solid #fecaca;
    margin-left: .25rem;
}
.p-neto     { color: #1a7f4b; font-weight: 800; font-size: .92rem; }

/* ── Empty ─────────────────────────────────────────────────────────────────── */
.vt-empty { text-align: center; padding: 60px 20px; color: var(--bs-secondary-color); }
.vt-empty i { font-size: 3rem; display: block; margin-bottom: 14px; opacity: .3; }
.vt-empty h4 { font-weight: 700; color: var(--bs-body-color); margin-bottom: 6px; }
</style>
@endpush

@section('contenido')

{{-- Header ─────────────────────────────────────────────────────── --}}
<div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-4">
    <div>
        <h1 style="font-size:1.5rem;font-weight:800;margin:0 0 .2rem;">Mis Ventas & Rentas</h1>
        <p style="margin:0;color:var(--bs-secondary-color);font-size:.88rem;">
            Historial con desglose de comisiones e ingresos netos
        </p>
    </div>
    <a href="{{ route('mis-publicaciones.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2" style="font-size:.85rem;">
        <i class="bi bi-box-seam"></i> Ver publicaciones
    </a>
</div>

{{-- Banner plan ─────────────────────────────────────────────────── --}}
<div class="comision-banner">
    <span class="plan-chip">
        <i class="bi bi-star-fill"></i> Plan {{ ucfirst($planActual) }}
    </span>
    <span class="tasa-chip">
        <i class="bi bi-percent"></i> Comisión: {{ $tasaActual }} sobre cada transacción
    </span>
    @if($planActual !== 'profesional')
        <a href="{{ route('dashboard.plan') }}" class="upgrade-tip">
            <i class="bi bi-arrow-up-circle me-1"></i>Mejorar plan para pagar menos comisión
        </a>
    @endif
</div>

{{-- Stats ───────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="vt-stat">
            <div class="vt-stat-icon icon-blue"><i class="bi bi-cash-stack"></i></div>
            <div>
                <div class="vt-stat-num">${{ number_format($totalBruto, 2) }}</div>
                <div class="vt-stat-lbl">Total bruto</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="vt-stat">
            <div class="vt-stat-icon icon-red"><i class="bi bi-percent"></i></div>
            <div>
                <div class="vt-stat-num">${{ number_format($totalComision, 2) }}</div>
                <div class="vt-stat-lbl">Comisión plataforma</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="vt-stat">
            <div class="vt-stat-icon icon-green"><i class="bi bi-wallet2"></i></div>
            <div>
                <div class="vt-stat-num">${{ number_format($totalNeto, 2) }}</div>
                <div class="vt-stat-lbl">Tu ingreso neto</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="vt-stat">
            <div class="vt-stat-icon icon-purple"><i class="bi bi-bag-check-fill"></i></div>
            <div>
                <div class="vt-stat-num">{{ $totalVentas + $totalRentas }}</div>
                <div class="vt-stat-lbl">{{ $totalVentas }} ventas · {{ $totalRentas }} rentas</div>
            </div>
        </div>
    </div>
</div>

{{-- Resumen financiero ───────────────────────────────────────────── --}}
@if($totalBruto > 0)
<div class="fin-card">
    <h6><i class="bi bi-calculator me-1"></i>Resumen financiero global</h6>
    <div class="fin-row">
        <span class="lbl">Total facturado (bruto)</span>
        <span class="val">${{ number_format($totalBruto, 2) }} MXN</span>
    </div>
    <div class="fin-row com">
        <span class="lbl">
            <i class="bi bi-dash-circle me-1"></i>Comisión plataforma
            <small class="ms-1" style="opacity:.65;">({{ $tasaActual }} plan {{ ucfirst($planActual) }})</small>
        </span>
        <span class="val">–${{ number_format($totalComision, 2) }} MXN</span>
    </div>
    <div class="fin-row neto">
        <span class="lbl"><i class="bi bi-wallet-fill me-1"></i>Tu ingreso neto</span>
        <span class="val">${{ number_format($totalNeto, 2) }} MXN</span>
    </div>
</div>
@endif

{{-- Toolbar ──────────────────────────────────────────────────────── --}}
<form action="{{ route('dashboard.ventas') }}" method="GET">
    <div class="vt-toolbar">
        <div class="vt-search">
            <i class="bi bi-search" style="color:var(--bs-secondary-color);"></i>
            <input type="text" name="q" placeholder="Buscar producto..." value="{{ request('q') }}">
        </div>
        <select name="tipo" class="vt-filter" onchange="this.form.submit()">
            <option value="">Todos los tipos</option>
            <option value="comprar" {{ request('tipo') === 'comprar' ? 'selected' : '' }}>Solo ventas</option>
            <option value="rentar"  {{ request('tipo') === 'rentar'  ? 'selected' : '' }}>Solo rentas</option>
        </select>
        <button type="submit" class="btn btn-outline-secondary" style="font-size:.85rem;">
            <i class="bi bi-funnel"></i>
        </button>
    </div>
</form>

{{-- Tabla ────────────────────────────────────────────────────────── --}}
<div class="vt-card">
    @if($ventas->isEmpty())
        <div class="vt-empty">
            <i class="bi bi-bag-x"></i>
            <h4>Sin ventas todavía</h4>
            <p>Cuando alguien compre o rente tus herramientas aparecerá aquí.</p>
            <a href="{{ route('publicar.create') }}" class="btn btn-primary btn-sm mt-2">
                <i class="bi bi-plus-lg me-1"></i>Publicar herramienta
            </a>
        </div>
    @else
        <div style="overflow-x:auto;">
        <table class="vt-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Tipo</th>
                    <th>Comprador</th>
                    <th>Bruto</th>
                    <th>Comisión</th>
                    <th style="color:#1a7f4b;">Neto tuyo</th>
                    <th>Fecha</th>
                    <th>Pedido</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ventas as $venta)
                @php
                    // Si la transacción es antigua (comision_pct = 0) calculamos on-the-fly
                    if ($venta->comision_pct > 0) {
                        $pct      = $venta->comision_pct;
                        $comision = $venta->comision_plataforma;
                        $neto     = $venta->neto_vendedor;
                    } else {
                        $planVendedor = $venta->vendedor?->plan ?? 'free';
                        $pct          = \App\Services\PlanService::comision($planVendedor);
                        $comision     = round($venta->total_item * $pct, 2);
                        $neto         = round($venta->total_item - $comision, 2);
                    }
                @endphp
                <tr>
                    {{-- Producto --}}
                    <td>
                        <div class="vt-prod-cell">
                            <div class="vt-thumb">
                                @php $img = $venta->producto?->imagenes?->first(); @endphp
                                @if($img)
                                    <img src="{{ asset($img->ruta) }}" alt="">
                                @else
                                    <i class="bi bi-gear" style="color:var(--bs-secondary-color);"></i>
                                @endif
                            </div>
                            <div>
                                <div class="vt-titulo">{{ Str::limit($venta->titulo, 38) }}</div>
                                @if($venta->tipo_accion === 'rentar' && $venta->fecha_inicio)
                                    <div class="vt-meta">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {{ \Carbon\Carbon::parse($venta->fecha_inicio)->format('d/m/Y') }}
                                        –
                                        {{ \Carbon\Carbon::parse($venta->fecha_fin)->format('d/m/Y') }}
                                    </div>
                                @elseif($venta->cantidad > 1)
                                    <div class="vt-meta">x{{ $venta->cantidad }} unidades</div>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Tipo --}}
                    <td>
                        <span class="badge-tipo {{ $venta->tipo_accion === 'comprar' ? 'tipo-comprar' : 'tipo-rentar' }}">
                            <i class="bi {{ $venta->tipo_accion === 'comprar' ? 'bi-bag-check-fill' : 'bi-clock-history' }}"></i>
                            {{ $venta->tipo_accion === 'comprar' ? 'Venta' : 'Renta' }}
                        </span>
                    </td>

                    {{-- Comprador --}}
                    <td>
                        <span class="badge-comprador">
                            <i class="bi bi-person-fill"></i>
                            {{ explode(' ', $venta->pedido->user->name)[0] }}
                        </span>
                    </td>

                    {{-- Bruto --}}
                    <td>
                        <span class="p-bruto">${{ number_format($venta->total_item, 2) }}</span>
                    </td>

                    {{-- Comisión --}}
                    <td>
                        <span class="p-comision">–${{ number_format($comision, 2) }}</span>
                        <span class="p-pct">{{ (int)($pct * 100) }}%</span>
                    </td>

                    {{-- Neto --}}
                    <td>
                        <span class="p-neto">${{ number_format($neto, 2) }}</span>
                    </td>

                    {{-- Fecha --}}
                    <td>
                        <span style="font-size:.82rem;color:var(--bs-secondary-color);">
                            {{ $venta->pedido->pagado_at?->format('d/m/Y') }}
                        </span>
                    </td>

                    {{-- Folio --}}
                    <td>
                        <span style="font-size:.8rem;font-weight:700;color:#534AB7;">
                            {{ $venta->pedido->folio }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        @if($ventas->hasPages())
            <div style="padding:14px 20px;border-top:1px solid var(--bs-border-color);">
                {{ $ventas->links() }}
            </div>
        @endif
    @endif
</div>

@endsection