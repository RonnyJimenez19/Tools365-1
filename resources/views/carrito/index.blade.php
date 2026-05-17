@extends('layouts.app')

@section('titulo_pagina', 'Mi Carrito - Tools365')

@push('css')
<style>
/* ── Carrito ──────────────────────────────────────────────────────────── */
.carrito-hero {
    background: linear-gradient(135deg, var(--color-primary, #0d6efd) 0%, #0a58ca 100%);
    color: #fff;
    padding: 2.5rem 0 2rem;
    margin-bottom: 2rem;
}
.carrito-hero h1 { font-size: 1.9rem; font-weight: 700; margin-bottom: .25rem; }
.carrito-hero p  { opacity: .85; margin: 0; }

.carrito-tabla { background: var(--bs-body-bg, #fff); border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.07); }
.carrito-tabla thead th { background: var(--bs-tertiary-bg, #f8f9fa); font-size: .78rem; text-transform: uppercase; letter-spacing: .05em; color: var(--bs-secondary-color, #6c757d); border-bottom: 1px solid var(--bs-border-color, #dee2e6); padding: .75rem 1rem; }
.carrito-row td { vertical-align: middle; padding: .9rem 1rem; border-bottom: 1px solid var(--bs-border-color, #dee2e6); }
.carrito-row:last-child td { border-bottom: none; }

.item-img { width: 72px; height: 60px; object-fit: cover; border-radius: 8px; }
.item-titulo { font-weight: 600; font-size: .92rem; line-height: 1.3; }
.item-meta   { font-size: .78rem; color: var(--bs-secondary-color, #6c757d); }

.badge-tipo-comprar { background:#e7f5ff; color:#1971c2; }
.badge-tipo-rentar  { background:#fff3bf; color:#865a0b; }
[data-theme="dark"] .badge-tipo-comprar { background:#1864ab33; color:#74c0fc; }
[data-theme="dark"] .badge-tipo-rentar  { background:#e67d0033; color:#ffd43b; }

.qty-control { display:flex; align-items:center; gap:.3rem; }
.qty-btn  { width:28px; height:28px; border:1px solid var(--bs-border-color,#dee2e6); border-radius:6px; background:transparent; color:inherit; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:.15s; }
.qty-btn:hover { background:var(--bs-primary,#0d6efd); color:#fff; border-color:transparent; }
.qty-input { width:42px; text-align:center; border:1px solid var(--bs-border-color,#dee2e6); border-radius:6px; background:transparent; color:inherit; padding:.2rem .3rem; font-size:.9rem; }

.fechas-renta { display:flex; gap:.4rem; align-items:center; flex-wrap:wrap; }
.fecha-input  { border:1px solid var(--bs-border-color,#dee2e6); border-radius:6px; background:transparent; color:inherit; padding:.2rem .5rem; font-size:.82rem; }

.total-item   { font-weight: 700; font-size: 1rem; }
.btn-quitar   { background:transparent; border:none; color:var(--bs-danger,#dc3545); cursor:pointer; padding:.3rem .5rem; border-radius:6px; transition:.15s; }
.btn-quitar:hover { background:#dc354520; }

/* Resumen lateral */
.resumen-card { background:var(--bs-body-bg,#fff); border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,.07); padding:1.5rem; position:sticky; top:80px; }
.resumen-card h5 { font-weight:700; font-size:1rem; margin-bottom:1rem; }
.resumen-row { display:flex; justify-content:space-between; font-size:.9rem; margin-bottom:.5rem; }
.resumen-total { font-size:1.2rem; font-weight:800; color:var(--bs-primary,#0d6efd); border-top:1px solid var(--bs-border-color,#dee2e6); padding-top:.75rem; margin-top:.5rem; }
.btn-checkout { width:100%; padding:.75rem; font-weight:700; font-size:1rem; border-radius:10px; }

/* Empty state */
.empty-carrito { text-align:center; padding:4rem 1rem; }
.empty-carrito .icon { font-size:4rem; opacity:.25; display:block; margin-bottom:1rem; }
.empty-carrito h3  { font-weight:700; margin-bottom:.5rem; }
.empty-carrito p   { color:var(--bs-secondary-color,#6c757d); }

/* Toast */
#carritoToast { position:fixed; bottom:1.5rem; right:1.5rem; z-index:9999; min-width:260px; }
</style>
@endpush

@section('contenido')

{{-- Hero --}}
<section class="carrito-hero">
    <div class="container">
        <h1><i class="bi bi-cart3 me-2"></i>Mi Carrito</h1>
        <p>Revisa y ajusta tus productos antes de continuar</p>
    </div>
</section>

<div class="container pb-5">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($items->isEmpty())
    {{-- ── Carrito vacío ── --}}
    <div class="empty-carrito">
        <i class="bi bi-cart-x icon"></i>
        <h3>Tu carrito está vacío</h3>
        <p>Agrega herramientas de renta, venta o subasta para empezar.</p>
        <a href="{{ route('busqueda.avanzada') }}" class="btn btn-primary mt-2">
            <i class="bi bi-search me-1"></i> Explorar herramientas
        </a>
    </div>
    @else
    {{-- ── Layout dos columnas ── --}}
    <div class="row g-4 align-items-start">

        {{-- Tabla de ítems --}}
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted" style="font-size:.85rem;">
                    {{ $items->count() }} {{ $items->count() === 1 ? 'producto' : 'productos' }} en el carrito
                </span>
                <form method="POST" action="{{ route('carrito.vaciar') }}"
                      onsubmit="return confirm('¿Vaciar el carrito?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash me-1"></i>Vaciar carrito
                    </button>
                </form>
            </div>

            <div class="carrito-tabla">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th style="min-width:280px">Producto</th>
                            <th>Tipo</th>
                            <th>Detalle</th>
                            <th class="text-end">Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                    @php
                        $img = $item->producto->imagenes->first();
                        $imgSrc = $img
                            ? asset($img->ruta)
                            : asset('Imagenes/placeholder.png');
                    @endphp
                    <tr class="carrito-row" id="row-{{ $item->id }}">
                        {{-- Imagen + nombre --}}
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $imgSrc }}" alt="{{ $item->producto->titulo }}" class="item-img">
                                <div>
                                    <div class="item-titulo">
                                        <a href="{{ route('productos.show', $item->producto) }}"
                                           class="text-decoration-none text-body">
                                            {{ Str::limit($item->producto->titulo, 55) }}
                                        </a>
                                    </div>
                                    <div class="item-meta">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $item->producto->ubicacion }}
                                    </div>
                                    <div class="item-meta">
                                        Precio unit.: <strong>${{ number_format($item->precio_unitario, 2) }}</strong>
                                        @if($item->producto->unidad) {{ $item->producto->unidad }} @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Badge tipo --}}
                        <td>
                            <span class="badge rounded-pill badge-tipo-{{ $item->tipo_accion }}">
                                @if($item->tipo_accion === 'rentar')
                                    <i class="bi bi-calendar-range me-1"></i>Renta
                                @else
                                    <i class="bi bi-bag-check me-1"></i>Compra
                                @endif
                            </span>
                        </td>

                        {{-- Detalle editable --}}
                        <td>
                            @if($item->tipo_accion === 'rentar')
                            <form class="form-update-item" data-id="{{ $item->id }}">
                                @csrf @method('PATCH')
                                <div class="fechas-renta">
                                    <div>
                                        <div style="font-size:.7rem;color:var(--bs-secondary-color,#6c757d)">Inicio</div>
                                        <input type="date" name="fecha_inicio"
                                               class="fecha-input"
                                               value="{{ $item->fecha_inicio?->format('Y-m-d') }}"
                                               min="{{ date('Y-m-d') }}"
                                               required>
                                    </div>
                                    <i class="bi bi-arrow-right" style="font-size:.75rem;opacity:.5;margin-top:14px"></i>
                                    <div>
                                        <div style="font-size:.7rem;color:var(--bs-secondary-color,#6c757d)">Fin</div>
                                        <input type="date" name="fecha_fin"
                                               class="fecha-input"
                                               value="{{ $item->fecha_fin?->format('Y-m-d') }}"
                                               required>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-outline-primary mt-3"
                                            title="Actualizar fechas" style="padding:.2rem .5rem;">
                                        <i class="bi bi-check2"></i>
                                    </button>
                                </div>
                                @if($item->diasRenta())
                                    <div class="item-meta mt-1">
                                        {{ $item->diasRenta() }} {{ $item->diasRenta() === 1 ? 'día' : 'días' }}
                                    </div>
                                @endif
                            </form>
                            @else
                            <form class="form-update-item" data-id="{{ $item->id }}">
                                @csrf @method('PATCH')
                                <div class="qty-control">
                                    <button type="button" class="qty-btn btn-qty-minus"
                                            data-id="{{ $item->id }}">−</button>
                                    <input type="number" name="cantidad"
                                           class="qty-input qty-val"
                                           id="qty-{{ $item->id }}"
                                           value="{{ $item->cantidad }}"
                                           min="1" max="99">
                                    <button type="button" class="qty-btn btn-qty-plus"
                                            data-id="{{ $item->id }}">+</button>
                                </div>
                            </form>
                            @endif
                        </td>

                        {{-- Total --}}
                        <td class="text-end">
                            <span class="total-item" id="total-{{ $item->id }}">
                                ${{ number_format($item->total_calculado, 2) }}
                            </span>
                        </td>

                        {{-- Quitar --}}
                        <td>
                            <button class="btn-quitar btn-delete-item"
                                    data-id="{{ $item->id }}"
                                    title="Quitar del carrito">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <a href="{{ route('busqueda.avanzada') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Seguir comprando
                </a>
            </div>
        </div>

        {{-- Resumen --}}
        <div class="col-lg-4">
            <div class="resumen-card">
                <h5><i class="bi bi-receipt me-2"></i>Resumen del pedido</h5>

                @foreach($items as $item)
                <div class="resumen-row">
                    <span class="text-truncate me-2" style="max-width:180px;font-size:.82rem;">
                        {{ Str::limit($item->producto->titulo, 35) }}
                    </span>
                    <span class="resumen-item-total" id="resumen-total-{{ $item->id }}">
                        ${{ number_format($item->total_calculado, 2) }}
                    </span>
                </div>
                @endforeach

                <div class="resumen-row resumen-total mt-2">
                    <span>Subtotal</span>
                    <span id="subtotal-global">${{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="resumen-row" style="font-size:.78rem;color:var(--bs-secondary-color,#6c757d);">
                    <span>IVA y envío</span>
                    <span>Se calculan al confirmar</span>
                </div>

                <a href="{{ route('carrito.checkout') }}" class="btn btn-primary btn-checkout mt-3">
                    <i class="bi bi-credit-card me-2"></i>Ir a pagar
                </a>
            </div>
        </div>

    </div>
    @endif
</div>

{{-- Toast de feedback --}}
<div id="carritoToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="polite">
    <div class="d-flex">
        <div class="toast-body" id="toastMsg">Listo.</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    const toast      = new bootstrap.Toast(document.getElementById('carritoToast'), { delay: 2500 });
    const toastEl    = document.getElementById('carritoToast');
    const toastMsg   = document.getElementById('toastMsg');

    function showToast(msg, ok = true) {
        toastEl.className = `toast align-items-center text-bg-${ok ? 'success' : 'danger'} border-0`;
        toastMsg.textContent = msg;
        toast.show();
    }

    // ── Eliminar ítem ──────────────────────────────────────────────────────
    document.querySelectorAll('.btn-delete-item').forEach(btn => {
        btn.addEventListener('click', async function () {
            if (!confirm('¿Quitar este producto del carrito?')) return;
            const id  = this.dataset.id;
            const url = `/carrito/${id}`;
            const res = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        ?? '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            });
            const data = await res.json();
            if (data.ok) {
                document.getElementById(`row-${id}`)?.remove();
                document.getElementById(`resumen-total-${id}`)?.closest('.resumen-row')?.remove();
                document.getElementById('subtotal-global').textContent = `$${data.subtotal}`;
                actualizarBadge(data.conteo);
                showToast('Producto eliminado del carrito.');
                if (data.conteo === 0) location.reload();
            }
        });
    });

    // ── Cantidad ± ──────────────────────────────────────────────────────────
    document.querySelectorAll('.btn-qty-minus, .btn-qty-plus').forEach(btn => {
        btn.addEventListener('click', function () {
            const id    = this.dataset.id;
            const input = document.getElementById(`qty-${id}`);
            let val = parseInt(input.value) || 1;
            val = this.classList.contains('btn-qty-minus') ? Math.max(1, val - 1) : Math.min(99, val + 1);
            input.value = val;
            submitUpdate(id, { cantidad: val });
        });
    });

    // ── Formularios update (fechas y cantidad manual) ───────────────────────
    document.querySelectorAll('.form-update-item').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const id     = this.dataset.id;
            const data   = Object.fromEntries(new FormData(this));
            // Quitar _method y _token del body, ya van en headers
            delete data._method; delete data._token;
            submitUpdate(id, data);
        });
    });

    async function submitUpdate(id, body) {
        const res  = await fetch(`/carrito/${id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    ?? '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify(body),
        });
        const data = await res.json();
        if (data.ok) {
            document.getElementById(`total-${id}`).textContent         = `$${data.total_item}`;
            document.getElementById(`resumen-total-${id}`).textContent  = `$${data.total_item}`;
            document.getElementById('subtotal-global').textContent      = `$${data.subtotal}`;
            showToast('Carrito actualizado.');
        } else {
            showToast(data.mensaje ?? 'Error al actualizar.', false);
        }
    }

    function actualizarBadge(n) {
        document.querySelectorAll('.carrito-badge').forEach(el => {
            el.textContent = n;
            el.style.display = n > 0 ? '' : 'none';
        });
    }
})();
</script>
@endpush