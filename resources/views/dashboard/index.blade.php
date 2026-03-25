{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.dashboard')

@section('titulo_pagina', 'Mi Panel – Tools365')
@section('topbar_title', 'Mi Panel')

@push('css')
<link rel="stylesheet" href="{{ asset('css/dashboard_index.css') }}">
@endpush

@section('contenido')

{{-- ── Saludo ── --}}
<div class="dash-page-header">
    <h1>¡Hola, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h1>
    <p>Aquí está el resumen de tu actividad en Tools365</p>
</div>

{{-- ── Banner plan ── --}}
<div class="plan-banner">
    <div class="plan-banner-icon">⚡</div>
    <div>
        <h3>Estás en el Plan Básico</h3>
        <p>Tienes 17 de 20 publicaciones disponibles este mes. ¡Actualiza para publicaciones ilimitadas!</p>
    </div>
    <a href="#" class="plan-banner-btn">
        <i class="bi bi-star-fill"></i> Ver planes
    </a>
</div>

{{-- ── Stats ── --}}
<div class="stat-grid">

    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-box-seam-fill"></i></div>
        <div>
            <div class="stat-num">3</div>
            <div class="stat-lbl">Publicaciones activas</div>
            <div class="stat-delta up"><i class="bi bi-arrow-up-short"></i> +1 este mes</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green"><i class="bi bi-bag-heart-fill"></i></div>
        <div>
            <div class="stat-num">8</div>
            <div class="stat-lbl">Compras realizadas</div>
            <div class="stat-delta up"><i class="bi bi-arrow-up-short"></i> +2 esta semana</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange"><i class="bi bi-hammer"></i></div>
        <div>
            <div class="stat-num">2</div>
            <div class="stat-lbl">Ofertas activas</div>
            <div class="stat-delta down"><i class="bi bi-arrow-down-short"></i> Cierra en 4h</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon red"><i class="bi bi-wallet2"></i></div>
        <div>
            <div class="stat-num">$4,280</div>
            <div class="stat-lbl">Ingresos este mes</div>
            <div class="stat-delta up"><i class="bi bi-arrow-up-short"></i> +18%</div>
        </div>
    </div>

</div>

{{-- ── Acciones rápidas ── --}}
<div class="section-label">Acciones rápidas</div>
<div class="actions-grid">

    <a href="#" class="action-card">
        <div class="action-icon pub"><i class="bi bi-plus-circle-fill"></i></div>
        <div>
            <div class="action-label">Publicar herramienta</div>
            <div class="action-sub">Vender o rentar</div>
        </div>
    </a>

    <a href="#" class="action-card">
        <div class="action-icon buy"><i class="bi bi-bag-check-fill"></i></div>
        <div>
            <div class="action-label">Explorar compras</div>
            <div class="action-sub">5,000+ productos</div>
        </div>
    </a>

    <a href="#" class="action-card">
        <div class="action-icon rent"><i class="bi bi-clock-history"></i></div>
        <div>
            <div class="action-label">Rentar equipo</div>
            <div class="action-sub">Por día o por mes</div>
        </div>
    </a>

    <a href="#" class="action-card">
        <div class="action-icon auction"><i class="bi bi-hammer"></i></div>
        <div>
            <div class="action-label">Subastas en vivo</div>
            <div class="action-sub">5 activas ahora</div>
        </div>
    </a>

    <a href="#" class="action-card">
        <div class="action-icon msg"><i class="bi bi-chat-dots-fill"></i></div>
        <div>
            <div class="action-label">Mensajes</div>
            <div class="action-sub">2 sin leer</div>
        </div>
    </a>

    <a href="#" class="action-card">
        <div class="action-icon wallet"><i class="bi bi-wallet2"></i></div>
        <div>
            <div class="action-label">Mi billetera</div>
            <div class="action-sub">$4,280 disponible</div>
        </div>
    </a>

    <a href="#" class="action-card">
        <div class="action-icon fav"><i class="bi bi-heart-fill"></i></div>
        <div>
            <div class="action-label">Favoritos</div>
            <div class="action-sub">12 guardados</div>
        </div>
    </a>

    <a href="#" class="action-card">
        <div class="action-icon plan"><i class="bi bi-star-fill"></i></div>
        <div>
            <div class="action-label">Cambiar plan</div>
            <div class="action-sub">Más publicaciones</div>
        </div>
    </a>

</div>

{{-- ── Dos columnas ── --}}
<div class="two-col">

    {{-- Mis publicaciones recientes --}}
    <div class="dash-card">
        <div class="card-header-row">
            <h3><i class="bi bi-box-seam me-2" style="color:#1F3A93;"></i>Mis publicaciones</h3>
            <a href="#">Ver todas <i class="bi bi-arrow-right"></i></a>
        </div>

        <table class="pub-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="pub-img-placeholder"><i class="bi bi-gear"></i></div>
                            <span style="font-weight:700;">Taladro Bosch 800W</span>
                        </div>
                    </td>
                    <td style="font-weight:800;color:#1F3A93;">$1,200</td>
                    <td><span class="badge-estado badge-venta">Venta</span></td>
                    <td><span class="badge-estado badge-activo"><i class="bi bi-circle-fill" style="font-size:.4rem;"></i>Activo</span></td>
                </tr>
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="pub-img-placeholder"><i class="bi bi-truck"></i></div>
                            <span style="font-weight:700;">Montacargas Yale 2T</span>
                        </div>
                    </td>
                    <td style="font-weight:800;color:#1F3A93;">$850<small style="font-weight:600;color:#a0aec0;">/día</small></td>
                    <td><span class="badge-estado badge-renta">Renta</span></td>
                    <td><span class="badge-estado badge-activo"><i class="bi bi-circle-fill" style="font-size:.4rem;"></i>Activo</span></td>
                </tr>
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="pub-img-placeholder" style="background:#fdecea;color:#e74c3c;"><i class="bi bi-hammer"></i></div>
                            <span style="font-weight:700;">Compresor 150 psi</span>
                        </div>
                    </td>
                    <td style="font-weight:800;color:#e74c3c;">$3,500</td>
                    <td><span class="badge-estado badge-subasta">Subasta</span></td>
                    <td><span class="badge-estado badge-activo"><i class="bi bi-circle-fill" style="font-size:.4rem;"></i>Activo</span></td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Subastas en las que participo --}}
    <div class="dash-card">
        <div class="card-header-row">
            <h3><i class="bi bi-hammer me-2" style="color:#e74c3c;"></i>Mis ofertas activas</h3>
            <a href="#">Ver todas <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="auction-item">
            <div class="auction-thumb"><i class="bi bi-buildings"></i></div>
            <div>
                <div class="auction-title">Excavadora CAT 320D</div>
                <div class="auction-meta">
                    <span class="timer-chip"><i class="bi bi-clock"></i> 3h 42m</span>
                </div>
            </div>
            <div class="auction-price">
                <strong>$48,500</strong>
                <span>tu puja</span>
            </div>
        </div>

        <div class="auction-item">
            <div class="auction-thumb"><i class="bi bi-wind"></i></div>
            <div>
                <div class="auction-title">Dron DJI Agras T40</div>
                <div class="auction-meta">
                    <span class="timer-chip"><i class="bi bi-clock"></i> 8h 15m</span>
                </div>
            </div>
            <div class="auction-price">
                <strong>$22,000</strong>
                <span>tu puja</span>
            </div>
        </div>

        <div class="auction-item">
            <div class="auction-thumb"><i class="bi bi-lightning-charge"></i></div>
            <div>
                <div class="auction-title">Generador Cummins 150kW</div>
                <div class="auction-meta">
                    <span class="timer-chip"><i class="bi bi-clock"></i> 1d 4h</span>
                </div>
            </div>
            <div class="auction-price">
                <strong>$85,000</strong>
                <span>tu puja</span>
            </div>
        </div>

        <div style="margin-top:16px;">
            <a href="#" style="display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;background:#fdecea;border-radius:10px;color:#e74c3c;font-weight:800;font-size:.85rem;text-decoration:none;transition:background .2s;"
               onmouseover="this.style.background='#fbd7d5'" onmouseout="this.style.background='#fdecea'">
                <i class="bi bi-hammer"></i>
                Ver todas las subastas activas
            </a>
        </div>
    </div>

</div>

@endsection