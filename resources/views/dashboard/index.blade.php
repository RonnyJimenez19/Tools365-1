{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.dashboard')

@section('titulo_pagina', 'Mi Panel – Tools365')
@section('topbar_title', 'Mi Panel')

@push('css')
<style>
/* ── HEADER ── */
.dash-page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.dash-page-header h1 {
    font-size: 1.5rem;
    font-weight: 800;
    margin: 0 0 .2rem;
}
.dash-page-header p { margin: 0; color: var(--bs-secondary-color); font-size: .9rem; }

/* ── PLAN BANNER ── */
.plan-banner {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: linear-gradient(135deg, #534AB7 0%, #7c6ef5 100%);
    border-radius: .75rem;
    padding: 1.1rem 1.25rem;
    margin-bottom: 1.5rem;
    color: #fff;
    flex-wrap: wrap;
}
.plan-banner-icon { font-size: 1.6rem; flex-shrink: 0; }
.plan-banner-text { flex: 1; min-width: 180px; }
.plan-banner-text h3 { font-size: 1rem; font-weight: 700; margin: 0 0 .2rem; }
.plan-banner-text p  { font-size: .82rem; margin: 0; opacity: .85; }
.plan-banner-btn {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .5rem 1rem;
    border-radius: .5rem;
    background: #fff;
    color: #534AB7;
    font-weight: 700;
    font-size: .82rem;
    text-decoration: none;
    white-space: nowrap;
    transition: opacity .15s;
}
.plan-banner-btn:hover { opacity: .9; color: #534AB7; }

/* ── STAT CARDS ── */
.stat-card {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: .75rem;
    padding: 1.1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}
.stat-icon {
    width: 46px; height: 46px;
    border-radius: .625rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; flex-shrink: 0;
}
.stat-icon.blue   { background: #e8f4fd; color: #1a6fa8; }
.stat-icon.green  { background: #e6f9f0; color: #1a7f4b; }
.stat-icon.orange { background: #fff3e0; color: #e65c00; }
.stat-icon.red    { background: #fdecea; color: #c0392b; }
.stat-icon.purple { background: #ede9ff; color: #534AB7; }

.stat-num { font-size: 1.45rem; font-weight: 800; line-height: 1; }
.stat-lbl { font-size: .8rem; color: var(--bs-secondary-color); margin: .2rem 0; }
.stat-delta { font-size: .75rem; display: flex; align-items: center; gap: .1rem; }
.stat-delta.up   { color: #1a7f4b; }
.stat-delta.down { color: #c0392b; }

/* ── SECTION LABEL ── */
.section-label {
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: var(--bs-secondary-color);
    margin: 1.5rem 0 .75rem;
}

/* ── ACTION CARDS ── */
.action-card {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: .75rem;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: .85rem;
    text-decoration: none;
    color: var(--bs-body-color);
    transition: box-shadow .15s, border-color .15s, transform .15s;
}
.action-card:hover {
    border-color: #534AB7;
    box-shadow: 0 4px 16px rgba(83,74,183,.12);
    transform: translateY(-2px);
    color: var(--bs-body-color);
}
.action-icon {
    width: 40px; height: 40px;
    border-radius: .5rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.action-icon.pub    { background: #e6f9f0; color: #1a7f4b; }
.action-icon.box    { background: #e8f4fd; color: #1a6fa8; }
.action-icon.rent   { background: #fff3e0; color: #e65c00; }
.action-icon.auction{ background: #fdecea; color: #c0392b; }
.action-icon.msg    { background: #ede9ff; color: #534AB7; }
.action-icon.wallet { background: #e6f9f0; color: #1a7f4b; }
.action-icon.fav    { background: #fdecea; color: #c0392b; }
.action-icon.plan   { background: #fff3e0; color: #e65c00; }

.action-label { font-size: .875rem; font-weight: 700; line-height: 1.2; }
.action-sub   { font-size: .775rem; color: var(--bs-secondary-color); }

/* ── DASH CARD ── */
.dash-card {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: .75rem;
    overflow: hidden;
    margin-bottom: 1rem;
}
.card-header-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--bs-border-color);
}
.card-header-row h3 {
    font-size: .95rem; font-weight: 700; margin: 0;
    display: flex; align-items: center;
}
.card-header-row a {
    font-size: .8rem; color: #534AB7; text-decoration: none;
    display: flex; align-items: center; gap: .25rem;
}
.card-header-row a:hover { text-decoration: underline; }

/* ── PUB TABLE ── */
.pub-table {
    width: 100%; border-collapse: collapse; font-size: .85rem;
}
.pub-table thead th {
    padding: .6rem 1rem;
    font-size: .72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .06em;
    color: var(--bs-secondary-color);
    border-bottom: 1px solid var(--bs-border-color);
    white-space: nowrap;
}
.pub-table tbody td {
    padding: .75rem 1rem;
    border-bottom: 1px solid var(--bs-border-color);
    vertical-align: middle;
}
.pub-table tbody tr:last-child td { border-bottom: none; }
.pub-table tbody tr:hover { background: var(--bs-tertiary-bg); }

.pub-row { display: flex; align-items: center; gap: .6rem; }
.pub-img-placeholder {
    width: 34px; height: 34px; border-radius: .375rem;
    background: #ede9ff; color: #534AB7;
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem; flex-shrink: 0;
}
.pub-img-placeholder.red { background: #fdecea; color: #c0392b; }
.pub-name  { font-weight: 600; font-size: .85rem; }
.pub-price { font-weight: 700; }
.pub-price.red { color: #e74c3c; }
.pub-price small { font-weight: 400; color: var(--bs-secondary-color); }

/* Badges de estado */
.badge-estado {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .72rem; font-weight: 600; padding: .25em .6em;
    border-radius: 999px; white-space: nowrap;
}
.badge-activo  { background: #e6f9f0; color: #1a7f4b; }
.badge-pausado { background: #fff3e0; color: #e65c00; }
.badge-venta   { background: #e8f4fd; color: #1a6fa8; }
.badge-renta   { background: #e6f9f0; color: #1a7f4b; }
.badge-subasta { background: #fdecea; color: #c0392b; }
.dot { font-size: .5rem; }

.pub-actions { display: flex; gap: .3rem; }
.btn-tbl-edit, .btn-tbl-del {
    width: 30px; height: 30px;
    border: none; border-radius: .375rem;
    display: flex; align-items: center; justify-content: center;
    font-size: .8rem; cursor: pointer; transition: background .15s;
    text-decoration: none;
}
.btn-tbl-edit     { background: #e8f4fd; color: #1a6fa8; }
.btn-tbl-edit:hover { background: #bee3f8; }
.btn-tbl-del      { background: #fdecea; color: #c0392b; }
.btn-tbl-del:hover  { background: #fbc4c0; }

.card-footer-link {
    padding: .75rem 1.25rem;
    border-top: 1px solid var(--bs-border-color);
}
.btn-card-footer-add, .btn-card-footer-auction {
    display: inline-flex; align-items: center; gap: .4rem;
    font-size: .82rem; font-weight: 600; text-decoration: none;
    padding: .4rem .85rem;
    border-radius: .5rem;
    transition: background .15s;
}
.btn-card-footer-add     { color: #1a7f4b; background: #e6f9f0; }
.btn-card-footer-add:hover { background: #c3f1d9; color: #1a7f4b; }
.btn-card-footer-auction { color: #c0392b; background: #fdecea; }
.btn-card-footer-auction:hover { background: #fbc4c0; color: #c0392b; }

/* ── AUCTION ITEMS ── */
.auction-item {
    display: flex;
    align-items: center;
    gap: .85rem;
    padding: .85rem 1.25rem;
    border-bottom: 1px solid var(--bs-border-color);
    transition: background .15s;
}
.auction-item:last-of-type { border-bottom: none; }
.auction-item:hover { background: var(--bs-tertiary-bg); }

.auction-thumb {
    width: 40px; height: 40px; border-radius: .5rem;
    background: #ede9ff; color: #534AB7;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.auction-title { font-weight: 600; font-size: .875rem; }
.auction-meta  { margin-top: .2rem; }

.timer-chip {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .72rem; font-weight: 600;
    padding: .2em .55em; border-radius: 999px;
}
.timer-chip.winning { background: #e6f9f0; color: #1a7f4b; }
.timer-chip.losing  { background: #fdecea; color: #c0392b; }
.timer-chip.neutral { background: var(--bs-secondary-bg); color: var(--bs-secondary-color); }

.auction-price { margin-left: auto; text-align: right; }
.auction-price strong { display: block; font-size: .95rem; font-weight: 800; }
.auction-price strong.red { color: #e74c3c; }
.auction-price span { font-size: .72rem; color: var(--bs-secondary-color); }

.btn-puja {
    display: inline-flex; align-items: center;
    margin-top: .3rem;
    padding: .3rem .65rem;
    border-radius: .375rem;
    font-size: .75rem; font-weight: 700;
    text-decoration: none;
    background: #534AB7; color: #fff;
    transition: background .15s;
}
.btn-puja:hover { background: #453da0; color: #fff; }
.btn-puja.red   { background: #e74c3c; }
.btn-puja.red:hover { background: #c0392b; }

/* ── REVIEWS ── */
.review-list { padding: .25rem 0; }
.review-item {
    display: flex; gap: .85rem;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--bs-border-color);
}
.review-item:last-child { border-bottom: none; }
.review-item.unanswered { background: var(--bs-tertiary-bg); }

.review-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: #ede9ff; color: #534AB7;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .8rem; flex-shrink: 0;
}
.review-body { flex: 1; }
.review-header {
    display: flex; align-items: center; flex-wrap: wrap; gap: .4rem;
    margin-bottom: .25rem;
}
.review-name    { font-weight: 700; font-size: .85rem; }
.review-product { font-size: .78rem; color: var(--bs-secondary-color); }
.review-date    { font-size: .75rem; color: var(--bs-secondary-color); margin-left: auto; }
.badge-sin-responder {
    font-size: .7rem; font-weight: 700;
    padding: .15em .5em; border-radius: 999px;
    background: #fdecea; color: #c0392b;
}
.stars { color: #f39c12; font-size: .9rem; margin-bottom: .3rem; }
.review-text { font-size: .83rem; margin: 0 0 .5rem; color: var(--bs-body-color); }
.review-reply {
    display: flex; align-items: flex-start; gap: .4rem;
    font-size: .8rem; color: var(--bs-secondary-color);
    padding: .5rem .75rem;
    background: var(--bs-tertiary-bg);
    border-radius: .5rem;
    border-left: 3px solid #534AB7;
}
.btn-responder {
    display: inline-flex; align-items: center; gap: .35rem;
    font-size: .8rem; font-weight: 600;
    padding: .35rem .75rem; border-radius: .375rem;
    background: #ede9ff; color: #534AB7;
    text-decoration: none; transition: background .15s;
}
.btn-responder:hover { background: #d9d3ff; color: #534AB7; }
</style>
@endpush

@section('contenido')

{{-- ── Saludo ── --}}
<div class="dash-page-header">
    <div>
        <h1>¡Hola, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h1>
        <p>Aquí está el resumen de tu actividad en Tools365</p>
    </div>
    <a href="{{ route('publicar.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i> Publicar herramienta
    </a>
</div>

{{-- ── Banner plan ── --}}
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
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-box-seam-fill"></i></div>
            <div>
                <div class="stat-num">3</div>
                <div class="stat-lbl">Publicaciones activas</div>
                <div class="stat-delta up"><i class="bi bi-arrow-up-short"></i> +1 este mes</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-bag-heart-fill"></i></div>
            <div>
                <div class="stat-num">8</div>
                <div class="stat-lbl">Compras realizadas</div>
                <div class="stat-delta up"><i class="bi bi-arrow-up-short"></i> +2 esta semana</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="bi bi-hammer"></i></div>
            <div>
                <div class="stat-num">2</div>
                <div class="stat-lbl">Ofertas activas</div>
                <div class="stat-delta down"><i class="bi bi-arrow-down-short"></i> Cierra en 4h</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-wallet2"></i></div>
            <div>
                <div class="stat-num">$4,280</div>
                <div class="stat-lbl">Ingresos este mes</div>
                <div class="stat-delta up"><i class="bi bi-arrow-up-short"></i> +18%</div>
            </div>
        </div>
    </div>
</div>

{{-- ── Acciones rápidas ── --}}
<div class="section-label">Acciones rápidas</div>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3 col-lg-3">
        <a href="{{ route('publicar.create') }}" class="action-card h-100">
            <div class="action-icon pub"><i class="bi bi-plus-circle-fill"></i></div>
            <div>
                <div class="action-label">Publicar herramienta</div>
                <div class="action-sub">Vender o rentar</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3 col-lg-3">
        <a href="{{ route('mis-publicaciones.index') }}" class="action-card h-100">
            <div class="action-icon box"><i class="bi bi-box-seam-fill"></i></div>
            <div>
                <div class="action-label">Mis publicaciones</div>
                <div class="action-sub">Gestionar anuncios</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3 col-lg-3">
        <a href="#" class="action-card h-100">
            <div class="action-icon rent"><i class="bi bi-clock-history"></i></div>
            <div>
                <div class="action-label">Mis rentas</div>
                <div class="action-sub">2 activas ahora</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3 col-lg-3">
        <a href="#" class="action-card h-100">
            <div class="action-icon auction"><i class="bi bi-hammer"></i></div>
            <div>
                <div class="action-label">Subastas</div>
                <div class="action-sub">5 activas ahora</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3 col-lg-3">
        <a href="#" class="action-card h-100">
            <div class="action-icon msg"><i class="bi bi-chat-left-text-fill"></i></div>
            <div>
                <div class="action-label">Comentarios</div>
                <div class="action-sub">3 sin responder</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3 col-lg-3">
        <a href="#" class="action-card h-100">
            <div class="action-icon wallet"><i class="bi bi-wallet2"></i></div>
            <div>
                <div class="action-label">Mi billetera</div>
                <div class="action-sub">$4,280 disponible</div>
            </div>
        </a>
    </div>

    <div class="col-6 col-md-3 col-lg-3">
        <a href="{{ route('planes.index') }}" class="action-card h-100">
            <div class="action-icon plan"><i class="bi bi-star-fill"></i></div>
            <div>
                <div class="action-label">Cambiar plan</div>
                <div class="action-sub">Más publicaciones</div>
            </div>
        </a>
    </div>
</div>


</div>

@endsection