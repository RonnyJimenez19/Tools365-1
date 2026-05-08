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
    <div>
        <h1>¡Hola, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h1>
        <p>Aquí está el resumen de tu actividad en Tools365</p>
    </div>
    <a href="{{ route('publicar.create') }}" class="btn-primary-dash">
        <i class="bi bi-plus-lg"></i> Publicar herramienta
    </a>
</div>

{{-- ── Banner plan (solo usuarios invitados) ── --}}
@if(!auth()->user()->puedeEditar())
<div class="plan-banner">
    <div class="plan-banner-icon">⚡</div>
    <div class="plan-banner-text">
        <h3>Estás en el Plan Básico</h3>
        <p>17 de 20 publicaciones disponibles este mes. ¡Actualiza para publicaciones ilimitadas!</p>
    </div>
    <a href="{{ route('planes.index') }}" class="plan-banner-btn">
        <i class="bi bi-star-fill"></i> Ver planes
    </a>
</div>
@endif

{{-- ── Stats ── --}}
<div class="stat-grid">

    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-box-seam-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num">3</div>
            <div class="stat-lbl">Publicaciones activas</div>
            <div class="stat-delta up"><i class="bi bi-arrow-up-short"></i> +1 este mes</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green"><i class="bi bi-bag-heart-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num">8</div>
            <div class="stat-lbl">Compras realizadas</div>
            <div class="stat-delta up"><i class="bi bi-arrow-up-short"></i> +2 esta semana</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange"><i class="bi bi-hammer"></i></div>
        <div class="stat-body">
            <div class="stat-num">2</div>
            <div class="stat-lbl">Ofertas activas</div>
            <div class="stat-delta down"><i class="bi bi-arrow-down-short"></i> Cierra en 4h</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon red"><i class="bi bi-wallet2"></i></div>
        <div class="stat-body">
            <div class="stat-num">$4,280</div>
            <div class="stat-lbl">Ingresos este mes</div>
            <div class="stat-delta up"><i class="bi bi-arrow-up-short"></i> +18%</div>
        </div>
    </div>

</div>

{{-- ── Acciones rápidas ── --}}
<div class="section-label">Acciones rápidas</div>
<div class="actions-grid">

    <a href="{{ route('publicar.create') }}" class="action-card">
        <div class="action-icon pub"><i class="bi bi-plus-circle-fill"></i></div>
        <div>
            <div class="action-label">Publicar herramienta</div>
            <div class="action-sub">Vender o rentar</div>
        </div>
    </a>

    <a href="{{ route('mis-publicaciones.index') }}" class="action-card">
        <div class="action-icon box"><i class="bi bi-box-seam-fill"></i></div>
        <div>
            <div class="action-label">Mis publicaciones</div>
            <div class="action-sub">Gestionar anuncios</div>
        </div>
    </a>

    <a href="#" class="action-card">
        <div class="action-icon rent"><i class="bi bi-clock-history"></i></div>
        <div>
            <div class="action-label">Mis rentas</div>
            <div class="action-sub">2 activas ahora</div>
        </div>
    </a>

    <a href="#" class="action-card">
        <div class="action-icon auction"><i class="bi bi-hammer"></i></div>
        <div>
            <div class="action-label">Subastas</div>
            <div class="action-sub">5 activas ahora</div>
        </div>
    </a>

    <a href="#" class="action-card">
        <div class="action-icon msg"><i class="bi bi-chat-left-text-fill"></i></div>
        <div>
            <div class="action-label">Comentarios</div>
            <div class="action-sub">3 sin responder</div>
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

    <a href="{{ route('planes.index') }}" class="action-card">
        <div class="action-icon plan"><i class="bi bi-star-fill"></i></div>
        <div>
            <div class="action-label">Cambiar plan</div>
            <div class="action-sub">Más publicaciones</div>
        </div>
    </a>

</div>

{{-- ── Dos columnas: Mis pubs + Subastas ── --}}
<div class="two-col">

    {{-- Mis publicaciones recientes --}}
    <div class="dash-card">
        <div class="card-header-row">
            <h3><i class="bi bi-box-seam me-2 text-accent"></i>Mis publicaciones</h3>
            <a href="{{ route('mis-publicaciones.index') }}">
                Ver todas <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <table class="pub-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="pub-row">
                            <div class="pub-img-placeholder"><i class="bi bi-gear"></i></div>
                            <span class="pub-name">Taladro Bosch 800W</span>
                        </div>
                    </td>
                    <td class="pub-price">$1,200</td>
                    <td><span class="badge-estado badge-venta">Venta</span></td>
                    <td><span class="badge-estado badge-activo"><i class="bi bi-circle-fill dot"></i>Activo</span></td>
                    <td>
                        <div class="pub-actions">
                            <a href="#" class="btn-tbl-edit" title="Editar"><i class="bi bi-pencil-fill"></i></a>
                            <button class="btn-tbl-del" title="Eliminar"><i class="bi bi-trash-fill"></i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="pub-row">
                            <div class="pub-img-placeholder"><i class="bi bi-truck"></i></div>
                            <span class="pub-name">Montacargas Yale 2T</span>
                        </div>
                    </td>
                    <td class="pub-price">$850<small>/día</small></td>
                    <td><span class="badge-estado badge-renta">Renta</span></td>
                    <td><span class="badge-estado badge-activo"><i class="bi bi-circle-fill dot"></i>Activo</span></td>
                    <td>
                        <div class="pub-actions">
                            <a href="#" class="btn-tbl-edit" title="Editar"><i class="bi bi-pencil-fill"></i></a>
                            <button class="btn-tbl-del" title="Eliminar"><i class="bi bi-trash-fill"></i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="pub-row">
                            <div class="pub-img-placeholder red"><i class="bi bi-hammer"></i></div>
                            <span class="pub-name">Compresor 150 psi</span>
                        </div>
                    </td>
                    <td class="pub-price red">$3,500</td>
                    <td><span class="badge-estado badge-subasta">Subasta</span></td>
                    <td><span class="badge-estado badge-pausado"><i class="bi bi-pause-circle-fill dot"></i>Pausado</span></td>
                    <td>
                        <div class="pub-actions">
                            <a href="#" class="btn-tbl-edit" title="Editar"><i class="bi bi-pencil-fill"></i></a>
                            <button class="btn-tbl-del" title="Eliminar"><i class="bi bi-trash-fill"></i></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="card-footer-link">
            <a href="{{ route('publicar.create') }}" class="btn-card-footer-add">
                <i class="bi bi-plus-lg"></i> Nueva publicación
            </a>
        </div>
    </div>

    {{-- Subastas activas --}}
    <div class="dash-card">
        <div class="card-header-row">
            <h3><i class="bi bi-hammer me-2" style="color:#e74c3c;"></i>Mis ofertas activas</h3>
            <a href="#">Ver todas <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="auction-item">
            <div class="auction-thumb"><i class="bi bi-buildings"></i></div>
            <div class="auction-info">
                <div class="auction-title">Excavadora CAT 320D</div>
                <div class="auction-meta">
                    <span class="timer-chip winning"><i class="bi bi-clock"></i> 3h 42m · Ganando</span>
                </div>
            </div>
            <div class="auction-price">
                <strong>$48,500</strong>
                <span>tu puja</span>
                <a href="#" class="btn-puja">Subir puja</a>
            </div>
        </div>

        <div class="auction-item">
            <div class="auction-thumb"><i class="bi bi-wind"></i></div>
            <div class="auction-info">
                <div class="auction-title">Dron DJI Agras T40</div>
                <div class="auction-meta">
                    <span class="timer-chip losing"><i class="bi bi-clock"></i> 8h 15m · Superado</span>
                </div>
            </div>
            <div class="auction-price">
                <strong class="red">$22,000</strong>
                <span>mejor: $23,500</span>
                <a href="#" class="btn-puja red">Contraofertar</a>
            </div>
        </div>

        <div class="auction-item">
            <div class="auction-thumb"><i class="bi bi-lightning-charge"></i></div>
            <div class="auction-info">
                <div class="auction-title">Generador Cummins 150kW</div>
                <div class="auction-meta">
                    <span class="timer-chip neutral"><i class="bi bi-clock"></i> 1d 4h</span>
                </div>
            </div>
            <div class="auction-price">
                <strong>$85,000</strong>
                <span>tu puja</span>
                <a href="#" class="btn-puja">Ver subasta</a>
            </div>
        </div>

        <div class="card-footer-link">
            <a href="#" class="btn-card-footer-auction">
                <i class="bi bi-hammer"></i> Ver todas las subastas activas
            </a>
        </div>
    </div>

</div>

{{-- ── Comentarios recientes ── --}}
<div class="dash-card" style="margin-top: 0;">
    <div class="card-header-row">
        <h3><i class="bi bi-chat-left-text-fill me-2" style="color:#534AB7;"></i>Comentarios recientes</h3>
        <a href="#">Ver todos <i class="bi bi-arrow-right"></i></a>
    </div>

    <div class="review-list">

        <div class="review-item">
            <div class="review-avatar">JL</div>
            <div class="review-body">
                <div class="review-header">
                    <span class="review-name">Juan López</span>
                    <span class="review-product">Taladro Bosch 800W</span>
                    <span class="review-date">Hace 2h</span>
                </div>
                <div class="stars">★★★★★</div>
                <p class="review-text">Excelente herramienta, en perfectas condiciones. El vendedor fue muy amable y la entrega fue rápida.</p>
                <div class="review-reply">
                    <i class="bi bi-reply-fill"></i>
                    <span>Tu respuesta: ¡Muchas gracias Juan! Fue un placer.</span>
                </div>
            </div>
        </div>

        <div class="review-item unanswered">
            <div class="review-avatar">MR</div>
            <div class="review-body">
                <div class="review-header">
                    <span class="review-name">María Ruiz</span>
                    <span class="review-product">Montacargas Yale 2T</span>
                    <span class="review-date">Ayer</span>
                    <span class="badge-sin-responder">Sin responder</span>
                </div>
                <div class="stars">★★★★☆</div>
                <p class="review-text">Muy buen equipo, entrega puntual. Le quito una estrella por un detalle menor en el mástil.</p>
                <a href="#" class="btn-responder">
                    <i class="bi bi-reply-fill"></i> Responder
                </a>
            </div>
        </div>

    </div>
</div>

@endsection