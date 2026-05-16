{{-- resources/views/dashboard/compras.blade.php --}}
@extends('layouts.dashboard')

@section('titulo_pagina', 'Mis compras — Tools365')
@section('topbar_title', 'Mis Compras & Rentas')

@push('css')
<style>
/* ── Stats ────────────────────────────────────────────────────────────────── */
.cp-stat {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: .75rem; padding: 1.1rem 1.25rem;
    display: flex; align-items: center; gap: 1rem;
}
.cp-stat-icon {
    width: 46px; height: 46px; border-radius: .625rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; flex-shrink: 0;
}
.cp-stat-icon.blue   { background: #e8f4fd; color: #1a6fa8; }
.cp-stat-icon.purple { background: #ede9ff; color: #534AB7; }
.cp-stat-num { font-size: 1.45rem; font-weight: 800; }
.cp-stat-lbl { font-size: .8rem; color: var(--bs-secondary-color); }

/* ── Tarjeta de pedido ────────────────────────────────────────────────────── */
.pedido-card {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: .75rem; overflow: hidden;
    margin-bottom: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,.04);
    transition: box-shadow .2s;
}
.pedido-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.08); }

.pedido-header {
    display: flex; align-items: center; flex-wrap: wrap; gap: .75rem;
    padding: .9rem 1.25rem;
    background: var(--bs-tertiary-bg);
    border-bottom: 1px solid var(--bs-border-color);
}
.pedido-folio {
    font-size: .9rem; font-weight: 800; color: #534AB7; letter-spacing: 1px;
}
.pedido-fecha { font-size: .78rem; color: var(--bs-secondary-color); }
.pedido-total {
    margin-left: auto;
    font-size: 1.05rem; font-weight: 800;
}

.badge-pagado {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .25em .65em; border-radius: 999px;
    font-size: .72rem; font-weight: 700;
    background: #dcfce7; color: #166534;
}

/* ── Items dentro del pedido ──────────────────────────────────────────────── */
.pedido-items { padding: .25rem 0; }
.pedido-item {
    display: flex; align-items: center; gap: 12px;
    padding: .8rem 1.25rem;
    border-bottom: 1px solid var(--bs-border-color);
}
.pedido-item:last-child { border-bottom: none; }
.pi-thumb {
    width: 48px; height: 48px; border-radius: 8px;
    overflow: hidden; flex-shrink: 0;
    border: 1px solid var(--bs-border-color);
    background: var(--bs-tertiary-bg);
    display: flex; align-items: center; justify-content: center;
}
.pi-thumb img { width:100%;height:100%;object-fit:cover; }
.pi-info { flex: 1; min-width: 0; }
.pi-titulo { font-weight: 700; font-size: .88rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.pi-sub    { font-size: .75rem; color: var(--bs-secondary-color); margin-top: 2px; }
.pi-price  { font-weight: 800; color: #534AB7; white-space: nowrap; }

.badge-accion {
    display: inline-flex; align-items: center; gap: .25rem;
    padding: .2em .55em; border-radius: 999px;
    font-size: .7rem; font-weight: 700;
}
.accion-compra { background: #dcfce7; color: #166534; }
.accion-renta  { background: #dbeafe; color: #1d4ed8; }

/* ── Pago info chip ───────────────────────────────────────────────────────── */
.pedido-footer {
    display: flex; align-items: center; gap: .75rem; flex-wrap: wrap;
    padding: .65rem 1.25rem;
    border-top: 1px solid var(--bs-border-color);
    background: var(--bs-tertiary-bg);
    font-size: .78rem; color: var(--bs-secondary-color);
}
.pago-chip {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: .2em .6em; border-radius: 999px;
    background: var(--bs-secondary-bg); color: var(--bs-secondary-color);
    font-weight: 600;
}

/* ── Empty ────────────────────────────────────────────────────────────────── */
.cp-empty { text-align:center; padding: 60px 20px; }
.cp-empty i { font-size: 3rem; display:block; margin-bottom:14px; opacity:.25; }
.cp-empty h4 { font-weight:700; margin-bottom:6px; }
.cp-empty p  { color:var(--bs-secondary-color); font-size:.88rem; }
</style>
@endpush

@section('contenido')

{{-- Header --}}
<div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-4">
    <div>
        <h1 style="font-size:1.5rem;font-weight:800;margin:0 0 .2rem;">Mis Compras & Rentas</h1>
        <p style="margin:0;color:var(--bs-secondary-color);font-size:.88rem;">
            Historial de todos tus pedidos en Tools365
        </p>
    </div>
    <a href="{{ route('inicio') }}" class="btn btn-primary d-flex align-items-center gap-2" style="font-size:.85rem;">
        <i class="bi bi-shop"></i> Explorar más herramientas
    </a>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6">
        <div class="cp-stat">
            <div class="cp-stat-icon blue"><i class="bi bi-receipt"></i></div>
            <div>
                <div class="cp-stat-num">{{ $totalPedidos }}</div>
                <div class="cp-stat-lbl">Pedidos realizados</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="cp-stat">
            <div class="cp-stat-icon purple"><i class="bi bi-wallet2"></i></div>
            <div>
                <div class="cp-stat-num">${{ number_format($totalGastado, 2) }}</div>
                <div class="cp-stat-lbl">Total invertido</div>
            </div>
        </div>
    </div>
</div>

{{-- Pedidos --}}
@if($pedidos->isEmpty())
    <div class="cp-empty">
        <i class="bi bi-bag-x"></i>
        <h4>Sin compras todavía</h4>
        <p>Aquí verás el historial de todos tus pedidos completados.</p>
        <a href="{{ route('inicio') }}" class="btn btn-primary btn-sm mt-2">
            <i class="bi bi-shop me-1"></i>Explorar herramientas
        </a>
    </div>
@else
    @foreach($pedidos as $pedido)
    <div class="pedido-card">

        {{-- Header del pedido --}}
        <div class="pedido-header">
            <span class="pedido-folio">{{ $pedido->folio }}</span>
            <span class="badge-pagado">
                <i class="bi bi-check-circle-fill"></i> Pagado
            </span>
            <span class="pedido-fecha">
                <i class="bi bi-calendar3 me-1"></i>
                {{ $pedido->pagado_at?->format('d/m/Y H:i') }}
            </span>
            <span class="pedido-total">${{ number_format($pedido->total, 2) }} MXN</span>
        </div>

        {{-- Items --}}
        <div class="pedido-items">
            @foreach($pedido->items as $item)
            <div class="pedido-item">
                <div class="pi-thumb">
                    @php $img = $item->producto?->imagenes?->first(); @endphp
                    @if($img) <img src="{{ asset($img->ruta) }}" alt="">
                    @else <i class="bi bi-gear" style="color:var(--bs-secondary-color);"></i>
                    @endif
                </div>
                <div class="pi-info">
                    <div class="pi-titulo">{{ $item->titulo }}</div>
                    <div class="pi-sub">
                        <span class="badge-accion {{ $item->tipo_accion === 'comprar' ? 'accion-compra' : 'accion-renta' }}">
                            <i class="bi {{ $item->tipo_accion === 'comprar' ? 'bi-bag-check' : 'bi-clock-history' }}"></i>
                            {{ $item->tipo_accion === 'comprar' ? 'Compra' : 'Renta' }}
                        </span>
                        @if($item->periodoRenta())
                            <span class="ms-2"><i class="bi bi-calendar3 me-1"></i>{{ $item->periodoRenta() }}</span>
                        @elseif($item->cantidad > 1)
                            <span class="ms-2">x{{ $item->cantidad }} unidades</span>
                        @endif
                    </div>
                </div>
                <div class="pi-price">${{ number_format($item->total_item, 2) }}</div>
            </div>
            @endforeach
        </div>

        {{-- Footer pago --}}
        <div class="pedido-footer">
            <span class="pago-chip">
                <i class="bi bi-credit-card-2-front"></i>
                {{ ucfirst($pedido->tarjeta_tipo ?? 'Tarjeta') }} •••• {{ $pedido->tarjeta_ultimos_cuatro }}
            </span>
            <span style="margin-left:auto;">
                {{ $pedido->items->count() }} {{ Str::plural('producto', $pedido->items->count()) }}
            </span>
        </div>

    </div>
    @endforeach

    @if($pedidos->hasPages())
        <div class="d-flex justify-content-center mt-2">
            {{ $pedidos->links() }}
        </div>
    @endif
@endif

@endsection