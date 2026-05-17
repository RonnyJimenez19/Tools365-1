{{-- resources/views/dashboard/admin.blade.php --}}
@extends('layouts.dashboard')

@section('titulo_pagina', 'Panel Admin – Tools365')
@section('topbar_title', 'Panel de Administración')

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
    color: var(--bs-body-color);
}
.dash-page-header p { margin: 0; color: var(--bs-secondary-color); font-size: .9rem; }

.admin-badge {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: .3rem .75rem;
    border-radius: 999px;
    background: #ede9ff;
    color: #534AB7;
    font-size: .78rem;
    font-weight: 700;
}
.date-chip {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: .3rem .75rem;
    border-radius: 999px;
    background: var(--bs-secondary-bg);
    color: var(--bs-secondary-color);
    font-size: .78rem;
}

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
    font-size: 1.2rem;
    flex-shrink: 0;
}
.stat-icon.blue   { background: #e8f4fd; color: #1a6fa8; }
.stat-icon.green  { background: #e6f9f0; color: #1a7f4b; }
.stat-icon.orange { background: #fff3e0; color: #e65c00; }
.stat-icon.red    { background: #fdecea; color: #c0392b; }
.stat-icon.purple { background: #ede9ff; color: #534AB7; }
.stat-icon.teal   { background: #e0f7f5; color: #00796b; }

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

/* ── ADMIN ACTION CARDS ── */
.admin-action-card {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: .75rem;
    padding: 1.1rem 1rem;
    text-decoration: none;
    color: var(--bs-body-color);
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: .4rem;
    transition: box-shadow .15s, border-color .15s, transform .15s;
    position: relative;
}
.admin-action-card:hover {
    border-color: #534AB7;
    box-shadow: 0 4px 16px rgba(83,74,183,.12);
    transform: translateY(-2px);
    color: var(--bs-body-color);
}
.admin-action-card.alert-card:hover { border-color: #e74c3c; box-shadow: 0 4px 16px rgba(231,76,60,.12); }

.aac-icon {
    width: 40px; height: 40px;
    border-radius: .5rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
}
.aac-icon.users   { background: #e8f4fd; color: #1a6fa8; }
.aac-icon.pub     { background: #e6f9f0; color: #1a7f4b; }
.aac-icon.content { background: #fff3e0; color: #e65c00; }
.aac-icon.reports { background: #ede9ff; color: #534AB7; }
.aac-icon.config  { background: var(--bs-secondary-bg); color: var(--bs-secondary-color); }
.aac-icon.flagged { background: #fdecea; color: #c0392b; }

.aac-label { font-size: .875rem; font-weight: 700; }
.aac-sub   { font-size: .775rem; color: var(--bs-secondary-color); }
.aac-badge {
    position: absolute; top: .75rem; right: .75rem;
    background: #e74c3c; color: #fff;
    font-size: .68rem; font-weight: 700;
    padding: .15em .45em; border-radius: 999px;
}

/* ── DASH CARD ── */
.dash-card {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: .75rem;
    overflow: hidden;
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

/* ── ADMIN TABLE ── */
.admin-table {
    width: 100%; border-collapse: collapse; font-size: .85rem;
}
.admin-table thead th {
    padding: .6rem 1rem;
    font-size: .72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .06em;
    color: var(--bs-secondary-color);
    border-bottom: 1px solid var(--bs-border-color);
    white-space: nowrap;
}
.admin-table tbody td {
    padding: .75rem 1rem;
    border-bottom: 1px solid var(--bs-border-color);
    vertical-align: middle;
}
.admin-table tbody tr:last-child td { border-bottom: none; }
.admin-table tbody tr:hover { background: var(--bs-tertiary-bg); }

.user-row { display: flex; align-items: center; gap: .6rem; }
.user-avatar {
    width: 34px; height: 34px; border-radius: 50%;
    background: #ede9ff; color: #534AB7;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .78rem; flex-shrink: 0;
}
.user-name  { font-weight: 600; font-size: .85rem; }
.user-email { font-size: .75rem; color: var(--bs-secondary-color); }

.pub-thumb {
    width: 34px; height: 34px; border-radius: .375rem;
    background: #ede9ff; color: #534AB7;
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem; flex-shrink: 0;
}
.pub-thumb.red { background: #fdecea; color: #c0392b; }
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

/* Botones de tabla */
.tbl-actions { display: flex; gap: .3rem; }
.btn-tbl-edit, .btn-tbl-del, .btn-tbl-restore {
    width: 30px; height: 30px;
    border: none; border-radius: .375rem;
    display: flex; align-items: center; justify-content: center;
    font-size: .8rem; cursor: pointer; transition: background .15s;
}
.btn-tbl-edit    { background: #e8f4fd; color: #1a6fa8; }
.btn-tbl-edit:hover { background: #bee3f8; }
.btn-tbl-del     { background: #fdecea; color: #c0392b; }
.btn-tbl-del:hover { background: #fbc4c0; }
.btn-tbl-restore { background: #e6f9f0; color: #1a7f4b; }
.btn-tbl-restore:hover { background: #c3f1d9; }

/* ── ACTIVITY LIST ── */
.activity-list { padding: .25rem 0; }
.activity-item {
    display: flex; align-items: center; gap: .85rem;
    padding: .8rem 1.25rem;
    border-bottom: 1px solid var(--bs-border-color);
    transition: background .15s;
}
.activity-item:last-child { border-bottom: none; }
.activity-item:hover { background: var(--bs-tertiary-bg); }

.act-icon {
    width: 34px; height: 34px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem; flex-shrink: 0;
}
.act-icon.green  { background: #e6f9f0; color: #1a7f4b; }
.act-icon.blue   { background: #e8f4fd; color: #1a6fa8; }
.act-icon.orange { background: #fff3e0; color: #e65c00; }
.act-icon.red    { background: #fdecea; color: #c0392b; }
.act-icon.purple { background: #ede9ff; color: #534AB7; }
.act-icon.teal   { background: #e0f7f5; color: #00796b; }

.act-body { flex: 1; }
.act-msg  { font-size: .85rem; display: block; }
.act-time { font-size: .75rem; color: var(--bs-secondary-color); }
.act-cta  {
    font-size: .78rem; font-weight: 600;
    color: #534AB7; text-decoration: none;
    padding: .3rem .65rem;
    border-radius: .375rem;
    border: 1px solid #534AB7;
    white-space: nowrap;
    transition: background .15s;
}
.act-cta:hover { background: #ede9ff; }
</style>
@endpush

@section('contenido')

{{-- ── Saludo ── --}}
<div class="dash-page-header">
    <div>
        <h1>¡Bienvenido, {{ explode(' ', auth()->user()->name)[0] }}! 🛡️</h1>
        <p>Panel de administración · Tools365</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <span class="admin-badge"><i class="bi bi-shield-fill"></i> Administrador</span>
        <span class="date-chip"><i class="bi bi-calendar3"></i> {{ now()->format('d M Y') }}</span>
    </div>
</div>

{{-- ── Stats del sistema ── --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="stat-num">1,248</div>
                <div class="stat-lbl">Usuarios registrados</div>
                <div class="stat-delta up"><i class="bi bi-arrow-up-short"></i> +34 esta semana</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-box-seam-fill"></i></div>
            <div>
                <div class="stat-num">5,031</div>
                <div class="stat-lbl">Publicaciones activas</div>
                <div class="stat-delta up"><i class="bi bi-arrow-up-short"></i> +127 este mes</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="bi bi-hammer"></i></div>
            <div>
                <div class="stat-num">48</div>
                <div class="stat-lbl">Subastas en curso</div>
                <div class="stat-delta down"><i class="bi bi-arrow-down-short"></i> 5 cierran hoy</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-currency-dollar"></i></div>
            <div>
                <div class="stat-num">$142,800</div>
                <div class="stat-lbl">Ingresos del mes</div>
                <div class="stat-delta up"><i class="bi bi-arrow-up-short"></i> +22%</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-star-fill"></i></div>
            <div>
                <div class="stat-num">312</div>
                <div class="stat-lbl">Suscripciones activas</div>
                <div class="stat-delta up"><i class="bi bi-arrow-up-short"></i> +18 este mes</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card">
            <div class="stat-icon teal"><i class="bi bi-chat-left-text-fill"></i></div>
            <div>
                <div class="stat-num">29</div>
                <div class="stat-lbl">Reportes pendientes</div>
                <div class="stat-delta down"><i class="bi bi-arrow-down-short"></i> 6 críticos</div>
            </div>
        </div>
    </div>
</div>

{{-- ── Acciones rápidas ── --}}
<div class="section-label">Acciones rápidas</div>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <a href="#" class="admin-action-card h-100">
            <div class="aac-icon users"><i class="bi bi-people-fill"></i></div>
            <div class="aac-label">Gestionar usuarios</div>
            <div class="aac-sub">Ver, editar, bloquear</div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('publicar.create') }}" class="admin-action-card h-100">
            <div class="aac-icon pub"><i class="bi bi-plus-circle-fill"></i></div>
            <div class="aac-label">Nueva publicación</div>
            <div class="aac-sub">Publicar herramienta</div>
        </a>
    </div>
    {{--<div class="col-6 col-md-4 col-lg-2">
        <a href="#" class="admin-action-card h-100">
            <div class="aac-icon content"><i class="bi bi-file-earmark-text-fill"></i></div>
            <div class="aac-label">Contenido de la página</div>
            <div class="aac-sub">Banners, nav, texto</div>
        </a>
    </div>--}}
    {{--<div class="col-6 col-md-4 col-lg-2">
        <a href="#" class="admin-action-card h-100">
            <div class="aac-icon reports"><i class="bi bi-bar-chart-fill"></i></div>
            <div class="aac-label">Reportes</div>
            <div class="aac-sub">Ventas y actividad</div>
        </a>
    </div>--}}
    {{--<div class="col-6 col-md-4 col-lg-2">
        <a href="#" class="admin-action-card h-100">
            <div class="aac-icon config"><i class="bi bi-gear-fill"></i></div>
            <div class="aac-label">Configuración</div>
            <div class="aac-sub">Sistema y parámetros</div>
        </a>
    </div>--}}
    {{--<div class="col-6 col-md-4 col-lg-2">
        <a href="#" class="admin-action-card alert-card h-100">
            <div class="aac-icon flagged"><i class="bi bi-flag-fill"></i></div>
            <div class="aac-label">Reportes de usuarios</div>
            <div class="aac-sub">29 pendientes</div>
            <span class="aac-badge">29</span>
        </a>
    </div>--}}
</div>

{{-- ── Dos columnas ── --}}
<div class="row g-3 mb-3">

    {{-- Usuarios recientes --}}
    <div class="col-12 col-xl-6">
        <div class="dash-card">
            <div class="card-header-row">
                <h3><i class="bi bi-people-fill me-2" style="color:#534AB7;"></i>Usuarios recientes</h3>
                <a href="#">Ver todos <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Plan</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="user-row">
                                    <div class="user-avatar">JL</div>
                                    <div>
                                        <div class="user-name">Juan López</div>
                                        <div class="user-email">juan@email.com</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-secondary-subtle text-secondary-emphasis">Invitado</span></td>
                            <td><span class="badge bg-primary-subtle text-primary-emphasis">Pro</span></td>
                            <td><span class="badge-estado badge-activo"><i class="bi bi-circle-fill dot"></i>Activo</span></td>
                            <td>
                                <div class="tbl-actions">
                                    <button class="btn-tbl-edit" title="Editar"><i class="bi bi-pencil-fill"></i></button>
                                    <button class="btn-tbl-del" title="Bloquear"><i class="bi bi-slash-circle-fill"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-row">
                                    <div class="user-avatar">MR</div>
                                    <div>
                                        <div class="user-name">María Ruiz</div>
                                        <div class="user-email">maria@email.com</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-secondary-subtle text-secondary-emphasis">Invitado</span></td>
                            <td><span class="badge bg-secondary-subtle text-secondary-emphasis">Básico</span></td>
                            <td><span class="badge-estado badge-activo"><i class="bi bi-circle-fill dot"></i>Activo</span></td>
                            <td>
                                <div class="tbl-actions">
                                    <button class="btn-tbl-edit"><i class="bi bi-pencil-fill"></i></button>
                                    <button class="btn-tbl-del"><i class="bi bi-slash-circle-fill"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-row">
                                    <div class="user-avatar">CP</div>
                                    <div>
                                        <div class="user-name">Carlos Pérez</div>
                                        <div class="user-email">carlos@email.com</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-warning-subtle text-warning-emphasis">Gerente</span></td>
                            <td><span class="badge bg-success-subtle text-success-emphasis">Enterprise</span></td>
                            <td><span class="badge-estado badge-activo"><i class="bi bi-circle-fill dot"></i>Activo</span></td>
                            <td>
                                <div class="tbl-actions">
                                    <button class="btn-tbl-edit"><i class="bi bi-pencil-fill"></i></button>
                                    <button class="btn-tbl-del"><i class="bi bi-slash-circle-fill"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-row">
                                    <div class="user-avatar" style="background:#fdecea;color:#e74c3c;">BL</div>
                                    <div>
                                        <div class="user-name">Bruno Lima</div>
                                        <div class="user-email">bruno@email.com</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-secondary-subtle text-secondary-emphasis">Invitado</span></td>
                            <td><span class="badge bg-secondary-subtle text-secondary-emphasis">Básico</span></td>
                            <td><span class="badge-estado badge-pausado"><i class="bi bi-slash-circle dot"></i>Bloqueado</span></td>
                            <td>
                                <div class="tbl-actions">
                                    <button class="btn-tbl-edit"><i class="bi bi-pencil-fill"></i></button>
                                    <button class="btn-tbl-restore"><i class="bi bi-arrow-counterclockwise"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Publicaciones recientes --}}
    <div class="col-12 col-xl-6">
        <div class="dash-card">
            <div class="card-header-row">
                <h3><i class="bi bi-box-seam-fill me-2" style="color:#27ae60;"></i>Publicaciones recientes</h3>
                <a href="#">Ver todas <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Tipo</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="user-row">
                                    <div class="pub-thumb"><i class="bi bi-gear"></i></div>
                                    <div>
                                        <div class="user-name">Taladro Bosch 800W</div>
                                        <div class="user-email">por Juan López</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge-estado badge-venta">Venta</span></td>
                            <td class="pub-price">$1,200</td>
                            <td><span class="badge-estado badge-activo"><i class="bi bi-circle-fill dot"></i>Activo</span></td>
                            <td>
                                <div class="tbl-actions">
                                    <button class="btn-tbl-edit"><i class="bi bi-pencil-fill"></i></button>
                                    <button class="btn-tbl-del"><i class="bi bi-trash-fill"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-row">
                                    <div class="pub-thumb"><i class="bi bi-truck"></i></div>
                                    <div>
                                        <div class="user-name">Montacargas Yale 2T</div>
                                        <div class="user-email">por María Ruiz</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge-estado badge-renta">Renta</span></td>
                            <td class="pub-price">$850<small>/día</small></td>
                            <td><span class="badge-estado badge-activo"><i class="bi bi-circle-fill dot"></i>Activo</span></td>
                            <td>
                                <div class="tbl-actions">
                                    <button class="btn-tbl-edit"><i class="bi bi-pencil-fill"></i></button>
                                    <button class="btn-tbl-del"><i class="bi bi-trash-fill"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-row">
                                    <div class="pub-thumb red"><i class="bi bi-hammer"></i></div>
                                    <div>
                                        <div class="user-name">Compresor 150 psi</div>
                                        <div class="user-email">por Carlos Pérez</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge-estado badge-subasta">Subasta</span></td>
                            <td class="pub-price red">$3,500</td>
                            <td><span class="badge-estado badge-pausado"><i class="bi bi-pause-circle-fill dot"></i>Pausado</span></td>
                            <td>
                                <div class="tbl-actions">
                                    <button class="btn-tbl-edit"><i class="bi bi-pencil-fill"></i></button>
                                    <button class="btn-tbl-del"><i class="bi bi-trash-fill"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- ── Actividad reciente del sistema ── --}}
<div class="dash-card">
    <div class="card-header-row">
        <h3><i class="bi bi-activity me-2" style="color:#e74c3c;"></i>Actividad reciente del sistema</h3>
        <a href="#">Ver todo <i class="bi bi-arrow-right"></i></a>
    </div>

    <div class="activity-list">
        <div class="activity-item">
            <div class="act-icon green"><i class="bi bi-person-check-fill"></i></div>
            <div class="act-body">
                <span class="act-msg"><strong>Juan López</strong> se registró en la plataforma</span>
                <span class="act-time">Hace 5 min</span>
            </div>
        </div>
        <div class="activity-item">
            <div class="act-icon blue"><i class="bi bi-box-seam-fill"></i></div>
            <div class="act-body">
                <span class="act-msg"><strong>María Ruiz</strong> publicó "Generador Honda 5kW"</span>
                <span class="act-time">Hace 18 min</span>
            </div>
        </div>
        <div class="activity-item">
            <div class="act-icon orange"><i class="bi bi-hammer"></i></div>
            <div class="act-body">
                <span class="act-msg"><strong>Carlos Pérez</strong> realizó una puja de $52,000 en Excavadora CAT</span>
                <span class="act-time">Hace 34 min</span>
            </div>
        </div>
        <div class="activity-item">
            <div class="act-icon red"><i class="bi bi-flag-fill"></i></div>
            <div class="act-body">
                <span class="act-msg">Publicación <strong>"Compresor usado"</strong> fue reportada por contenido inapropiado</span>
                <span class="act-time">Hace 1h</span>
            </div>
            <a href="#" class="act-cta">Revisar</a>
        </div>
        <div class="activity-item">
            <div class="act-icon purple"><i class="bi bi-star-fill"></i></div>
            <div class="act-body">
                <span class="act-msg"><strong>Empresa MX SA</strong> contrató el plan Enterprise</span>
                <span class="act-time">Hace 2h</span>
            </div>
        </div>
        <div class="activity-item">
            <div class="act-icon teal"><i class="bi bi-chat-left-text-fill"></i></div>
            <div class="act-body">
                <span class="act-msg"><strong>3 nuevas reseñas</strong> esperan moderación</span>
                <span class="act-time">Hace 3h</span>
            </div>
            <a href="#" class="act-cta">Moderar</a>
        </div>
    </div>
</div>

@endsection