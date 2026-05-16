{{-- resources/views/pago/confirmacion.blade.php --}}
@extends('layouts.app')

@section('titulo_pagina', 'Pago confirmado — Tools365')

@push('css')
<style>
.conf-wrap {
    min-height: 100vh;
    background: var(--color-bg, #f8fafc);
    padding: 120px 0 80px;
}
.conf-hero {
    text-align: center;
    padding: 3rem 2rem;
    background: linear-gradient(135deg, #1a1a2e 0%, #0d5c36 100%);
    border-radius: 20px;
    color: #fff;
    margin-bottom: 32px;
    position: relative;
    overflow: hidden;
}
.conf-hero::before {
    content: '';
    position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.conf-check {
    width: 80px; height: 80px;
    border-radius: 50%;
    background: rgba(255,255,255,.15);
    border: 3px solid rgba(255,255,255,.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 2.2rem; color: #fff;
    margin: 0 auto 20px;
    animation: confCheckIn .5s ease;
}
@keyframes confCheckIn {
    from { transform: scale(0); opacity: 0; }
    to   { transform: scale(1); opacity: 1; }
}
.conf-hero h1 { font-size: 1.8rem; font-weight: 900; margin: 0 0 .4rem; }
.conf-hero p  { opacity: .8; margin: 0; }
.conf-folio {
    display: inline-block;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.2);
    padding: .4rem 1.2rem; border-radius: 999px;
    font-size: 1rem; font-weight: 800; letter-spacing: 2px;
    margin-top: 14px; position: relative; z-index: 1;
}

/* Cards */
.conf-card {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,.05);
}
.conf-card-hdr {
    padding: 1rem 1.4rem;
    border-bottom: 1px solid var(--bs-border-color);
    font-size: .95rem; font-weight: 700;
    display: flex; align-items: center; gap: .6rem;
}
.conf-card-hdr i { color: #534AB7; }
.conf-card-body { padding: 1.4rem; }

/* Items */
.conf-item {
    display: flex; align-items: center; gap: 12px;
    padding: .75rem 0;
    border-bottom: 1px solid var(--bs-border-color);
}
.conf-item:last-child { border-bottom: none; }
.conf-thumb {
    width: 50px; height: 50px; border-radius: 10px;
    overflow: hidden; flex-shrink: 0;
    border: 1px solid var(--bs-border-color);
    background: var(--bs-tertiary-bg);
    display: flex; align-items: center; justify-content: center;
}
.conf-thumb img { width:100%;height:100%;object-fit:cover; }
.conf-item-info { flex: 1; min-width: 0; }
.conf-item-titulo { font-weight: 700; font-size: .88rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.conf-item-sub    { font-size: .75rem; color: var(--bs-secondary-color); margin-top: 2px; }
.conf-item-precio { font-weight: 800; color: #534AB7; white-space: nowrap; }

/* Totales */
.conf-total-row {
    display: flex; justify-content: space-between;
    padding: .45rem 0; font-size: .88rem;
}
.conf-total-row.final {
    font-size: 1.1rem; font-weight: 800;
    border-top: 2px solid var(--bs-border-color);
    padding-top: .8rem; margin-top: .3rem;
    color: #534AB7;
}

/* Acciones */
.conf-actions { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 8px; }
.btn-conf-primary {
    flex: 1; min-width: 160px;
    display: inline-flex; align-items: center; justify-content: center; gap: .5rem;
    padding: .85rem 1.2rem;
    background: linear-gradient(135deg, #534AB7, #7c6ef5);
    color: #fff; border: none; border-radius: 12px;
    font-weight: 700; font-size: .9rem; text-decoration: none;
    transition: all .2s;
    box-shadow: 0 4px 16px rgba(83,74,183,.3);
}
.btn-conf-primary:hover { color:#fff; transform:translateY(-2px); box-shadow: 0 8px 24px rgba(83,74,183,.4); }
.btn-conf-secondary {
    flex: 1; min-width: 160px;
    display: inline-flex; align-items: center; justify-content: center; gap: .5rem;
    padding: .85rem 1.2rem;
    background: transparent;
    color: var(--bs-body-color);
    border: 1.5px solid var(--bs-border-color);
    border-radius: 12px;
    font-weight: 700; font-size: .9rem; text-decoration: none;
    transition: all .2s;
}
.btn-conf-secondary:hover { border-color: #534AB7; color: #534AB7; }
</style>
@endpush

@section('contenido')
<div class="conf-wrap">
<div class="container" style="max-width:700px;">

    {{-- Hero --}}
    <div class="conf-hero">
        <div class="conf-check"><i class="bi bi-check-lg"></i></div>
        <h1>¡Pago exitoso!</h1>
        <p>Tu pedido ha sido confirmado y el vendedor ha sido notificado.</p>
        <div class="conf-folio">{{ $pedido->folio }}</div>
    </div>

    {{-- Resumen de items --}}
    <div class="conf-card">
        <div class="conf-card-hdr">
            <i class="bi bi-bag-check-fill"></i> Productos del pedido
        </div>
        <div class="conf-card-body">
            @foreach($pedido->items as $item)
            <div class="conf-item">
                <div class="conf-thumb">
                    @php $img = $item->producto?->imagenes?->first(); @endphp
                    @if($img) <img src="{{ asset($img->ruta) }}" alt="">
                    @else <i class="bi bi-gear" style="color:var(--bs-secondary-color);"></i>
                    @endif
                </div>
                <div class="conf-item-info">
                    <div class="conf-item-titulo">{{ $item->titulo }}</div>
                    <div class="conf-item-sub">
                        @if($item->tipo_accion === 'rentar')
                            <i class="bi bi-clock-history me-1"></i>Renta
                            @if($item->periodoRenta()) · {{ $item->periodoRenta() }} @endif
                        @else
                            <i class="bi bi-bag-check me-1"></i>Compra
                            @if($item->cantidad > 1) · {{ $item->cantidad }} unidades @endif
                        @endif
                    </div>
                </div>
                <div class="conf-item-precio">${{ number_format($item->total_item, 2) }}</div>
            </div>
            @endforeach

            <div style="margin-top:12px;">
                <div class="conf-total-row">
                    <span style="color:var(--bs-secondary-color);">Subtotal</span>
                    <span>${{ number_format($pedido->subtotal, 2) }}</span>
                </div>
                <div class="conf-total-row final">
                    <span>Total pagado</span>
                    <span>${{ number_format($pedido->total, 2) }} MXN</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Info de pago --}}
    <div class="conf-card">
        <div class="conf-card-hdr">
            <i class="bi bi-credit-card-2-front-fill"></i> Método de pago
        </div>
        <div class="conf-card-body" style="padding:.9rem 1.4rem;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:40px;height:26px;border-radius:5px;
                    background:linear-gradient(135deg,#1a1a2e,#534AB7);
                    display:flex;align-items:center;justify-content:center;
                    font-size:.72rem;color:#fff;font-weight:800;">
                    {{ strtoupper(substr($pedido->tarjeta_tipo ?? 'otro', 0, 2)) }}
                </div>
                <span style="font-weight:700;">
                    @if($pedido->tarjeta_tipo)
                        {{ ucfirst($pedido->tarjeta_tipo) }}
                    @else Tarjeta @endif
                    •••• {{ $pedido->tarjeta_ultimos_cuatro }}
                </span>
                <span style="margin-left:auto;font-size:.78rem;color:var(--bs-secondary-color);">
                    {{ $pedido->pagado_at?->format('d/m/Y H:i') }}
                </span>
            </div>
        </div>
    </div>

    {{-- Acciones --}}
    <div class="conf-actions">
        <a href="{{ route('dashboard.compras') }}" class="btn-conf-primary">
            <i class="bi bi-bag-heart-fill"></i> Ver mis compras
        </a>
        <a href="{{ route('inicio') }}" class="btn-conf-secondary">
            <i class="bi bi-shop"></i> Seguir explorando
        </a>
    </div>

</div>
</div>
@endsection