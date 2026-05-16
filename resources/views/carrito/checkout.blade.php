@extends('layouts.app')

@section('titulo_pagina', 'Confirmar Pedido - Tools365')

@push('css')
<style>
.checkout-hero { background:linear-gradient(135deg,#198754,#157347); color:#fff; padding:2.5rem 0 2rem; margin-bottom:2rem; }
.checkout-hero h1 { font-size:1.9rem; font-weight:700; }

.checkout-card { background:var(--bs-body-bg,#fff); border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,.07); padding:1.5rem; margin-bottom:1.5rem; }
.checkout-card h5 { font-weight:700; margin-bottom:1rem; }

.item-row { display:flex; gap:1rem; align-items:flex-start; padding:.75rem 0; border-bottom:1px solid var(--bs-border-color,#dee2e6); }
.item-row:last-child { border-bottom:none; }
.item-row img { width:64px; height:54px; object-fit:cover; border-radius:8px; flex-shrink:0; }
.item-row-info { flex:1; min-width:0; }
.item-row-titulo { font-weight:600; font-size:.9rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.item-row-meta   { font-size:.78rem; color:var(--bs-secondary-color,#6c757d); }
.item-row-total  { font-weight:700; white-space:nowrap; }

.alerta-inactivo { background:#fff3cd; border:1px solid #ffc107; border-radius:8px; padding:.75rem 1rem; font-size:.85rem; margin-bottom:1rem; }
[data-theme="dark"] .alerta-inactivo { background:#3d2e0033; border-color:#ffc10766; }

.resumen-final { background:var(--bs-body-bg,#fff); border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,.07); padding:1.5rem; position:sticky; top:80px; }
.resumen-row { display:flex; justify-content:space-between; font-size:.9rem; margin-bottom:.5rem; }
.resumen-grand-total { font-size:1.3rem; font-weight:800; color:var(--bs-primary,#0d6efd); border-top:1px solid var(--bs-border-color,#dee2e6); padding-top:.75rem; margin-top:.5rem; }
.btn-confirmar { width:100%; padding:.8rem; font-weight:700; font-size:1rem; border-radius:10px; }
.note-checkout  { font-size:.78rem; color:var(--bs-secondary-color,#6c757d); text-align:center; margin-top:.75rem; line-height:1.4; }
</style>
@endpush

@section('contenido')

<section class="checkout-hero">
    <div class="container">
        <h1><i class="bi bi-credit-card me-2"></i>Confirmar Pedido</h1>
        <p>Revisa el resumen final antes de continuar</p>
    </div>
</section>

<div class="container pb-5">

    {{-- Aviso si hay productos inactivos --}}
    @if($inactivos->isNotEmpty())
    <div class="alerta-inactivo mb-3">
        <i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i>
        <strong>Atención:</strong> {{ $inactivos->count() }} producto(s) en tu carrito
        ya no están disponibles y no se incluirán en el pedido.
    </div>
    @endif

    <div class="row g-4 align-items-start">

        {{-- Lista de ítems --}}
        <div class="col-lg-8">
            <div class="checkout-card">
                <h5><i class="bi bi-list-check me-2"></i>Productos ({{ $items->count() }})</h5>

                @foreach($items as $item)
                @php
                    $img    = $item->producto->imagenes->first();
                    $imgSrc = $img ? asset($img->ruta) : asset('Imagenes/placeholder.png');
                    $activo = $item->producto->estado === 'activo';
                @endphp
                <div class="item-row {{ !$activo ? 'opacity-50' : '' }}">
                    <img src="{{ $imgSrc }}" alt="{{ $item->producto->titulo }}">
                    <div class="item-row-info">
                        <div class="item-row-titulo">{{ $item->producto->titulo }}</div>
                        <div class="item-row-meta">
                            @if($item->tipo_accion === 'rentar')
                                <i class="bi bi-calendar-range me-1"></i>
                                {{ $item->fecha_inicio?->format('d/m/Y') }} –
                                {{ $item->fecha_fin?->format('d/m/Y') }}
                                ({{ $item->diasRenta() }} {{ $item->diasRenta() === 1 ? 'día' : 'días' }})
                            @else
                                <i class="bi bi-bag me-1"></i>
                                Cantidad: {{ $item->cantidad }}
                            @endif
                        </div>
                        <div class="item-row-meta">
                            Precio unit.: ${{ number_format($item->precio_unitario, 2) }}
                            @if($item->producto->unidad) {{ $item->producto->unidad }} @endif
                        </div>
                        @if(!$activo)
                            <span class="badge bg-danger mt-1">No disponible</span>
                        @endif
                    </div>
                    <div class="item-row-total">
                        ${{ number_format($item->total_calculado, 2) }}
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Info de contacto --}}
            <div class="checkout-card">
                <h5><i class="bi bi-person-lines-fill me-2"></i>Datos de contacto</h5>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label" style="font-size:.85rem;font-weight:600;">Nombre completo</label>
                        <input type="text" class="form-control"
                               value="{{ Auth::user()->name }}" readonly>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" style="font-size:.85rem;font-weight:600;">Correo electrónico</label>
                        <input type="email" class="form-control"
                               value="{{ Auth::user()->email }}" readonly>
                    </div>
                    <div class="col-12">
                        <label class="form-label" style="font-size:.85rem;font-weight:600;">
                            Nota adicional <span class="text-muted">(opcional)</span>
                        </label>
                        <textarea class="form-control" rows="2"
                                  placeholder="Ej: horario de disponibilidad, dirección de entrega…"></textarea>
                    </div>
                </div>
                <p class="mt-3 mb-0" style="font-size:.8rem;color:var(--bs-secondary-color,#6c757d);">
                    <i class="bi bi-info-circle me-1"></i>
                    El vendedor se pondrá en contacto contigo para coordinar la entrega o renta.
                    Tools365 facilita el encuentro pero no procesa pagos directamente.
                </p>
            </div>
        </div>

        {{-- Resumen final --}}
        <div class="col-lg-4">
            <div class="resumen-final">
                <h5><i class="bi bi-receipt me-2"></i>Resumen</h5>

                @foreach($items as $item)
                <div class="resumen-row">
                    <span class="text-truncate me-2" style="max-width:170px;font-size:.82rem;">
                        {{ Str::limit($item->producto->titulo, 30) }}
                    </span>
                    <span>${{ number_format($item->total_calculado, 2) }}</span>
                </div>
                @endforeach

                <div class="resumen-row resumen-grand-total">
                    <span>Total estimado</span>
                    <span>${{ number_format($subtotal, 2) }} MXN</span>
                </div>

                {{-- En un proyecto real aquí iría la integración con pasarela de pago --}}
                <button class="btn btn-success btn-confirmar mt-3"
                        onclick="alert('Pedido registrado. El vendedor se pondrá en contacto contigo pronto. 🎉\n\n(Integra aquí tu pasarela de pago o sistema de pedidos.)')">
                    <i class="bi bi-check-circle me-2"></i>Confirmar pedido
                </button>

                <p class="note-checkout">
                    <i class="bi bi-shield-check me-1 text-success"></i>
                    Tus datos están protegidos. Al confirmar,
                    el vendedor recibirá tu solicitud de contacto.
                </p>

                <a href="{{ route('carrito.index') }}" class="btn btn-sm btn-outline-secondary w-100 mt-2">
                    <i class="bi bi-arrow-left me-1"></i>Volver al carrito
                </a>
            </div>
        </div>

    </div>
</div>
@endsection