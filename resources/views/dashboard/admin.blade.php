{{-- resources/views/dashboard/admin.blade.php --}}
@extends('layouts.dashboard')

@section('titulo_pagina', 'Panel Admin – Tools365')
@section('topbar_title', 'Panel de Administración')

@push('css')
<link rel="stylesheet" href="{{ asset('css/dashboard_admin.css') }}">
@endpush

@section('contenido')

{{-- ── Saludo ── --}}
<div class="dash-page-header">
    <div>
        <h1>¡Bienvenido, {{ explode(' ', auth()->user()->name)[0] }}! 🛡️</h1>
        <p>Panel de administración · Tools365</p>
    </div>
    <div class="header-meta">
        <span class="admin-badge"><i class="bi bi-shield-fill"></i> Administrador</span>
        <span class="date-chip"><i class="bi bi-calendar3"></i> {{ now()->format('d M Y') }}</span>
    </div>
</div>

{{-- ── Stats del sistema ── --}}
<div class="stat-grid">

    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num">1,248</div>
            <div class="stat-lbl">Usuarios registrados</div>
            <div class="stat-delta up"><i class="bi bi-arrow-up-short"></i> +34 esta semana</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green"><i class="bi bi-box-seam-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num">5,031</div>
            <div class="stat-lbl">Publicaciones activas</div>
            <div class="stat-delta up"><i class="bi bi-arrow-up-short"></i> +127 este mes</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange"><i class="bi bi-hammer"></i></div>
        <div class="stat-body">
            <div class="stat-num">48</div>
            <div class="stat-lbl">Subastas en curso</div>
            <div class="stat-delta down"><i class="bi bi-arrow-down-short"></i> 5 cierran hoy</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon red"><i class="bi bi-currency-dollar"></i></div>
        <div class="stat-body">
            <div class="stat-num">$142,800</div>
            <div class="stat-lbl">Ingresos del mes</div>
            <div class="stat-delta up"><i class="bi bi-arrow-up-short"></i> +22%</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon purple"><i class="bi bi-star-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num">312</div>
            <div class="stat-lbl">Suscripciones activas</div>
            <div class="stat-delta up"><i class="bi bi-arrow-up-short"></i> +18 este mes</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon teal"><i class="bi bi-chat-left-text-fill"></i></div>
        <div class="stat-body">
            <div class="stat-num">29</div>
            <div class="stat-lbl">Reportes pendientes</div>
            <div class="stat-delta down"><i class="bi bi-arrow-down-short"></i> 6 críticos</div>
        </div>
    </div>

</div>

{{-- ── Acciones rápidas admin ── --}}
<div class="section-label">Acciones rápidas</div>
<div class="admin-actions-grid">

    <a href="#" class="admin-action-card">
        <div class="aac-icon users"><i class="bi bi-people-fill"></i></div>
        <div class="aac-label">Gestionar usuarios</div>
        <div class="aac-sub">Ver, editar, bloquear</div>
    </a>

    <a href="{{ route('publicar.create') }}" class="admin-action-card">
        <div class="aac-icon pub"><i class="bi bi-plus-circle-fill"></i></div>
        <div class="aac-label">Nueva publicación</div>
        <div class="aac-sub">Publicar herramienta</div>
    </a>

    <a href="#" class="admin-action-card">
        <div class="aac-icon content"><i class="bi bi-file-earmark-text-fill"></i></div>
        <div class="aac-label">Contenido de la página</div>
        <div class="aac-sub">Banners, nav, texto</div>
    </a>

    <a href="#" class="admin-action-card">
        <div class="aac-icon reports"><i class="bi bi-bar-chart-fill"></i></div>
        <div class="aac-label">Reportes</div>
        <div class="aac-sub">Ventas y actividad</div>
    </a>

    <a href="#" class="admin-action-card">
        <div class="aac-icon config"><i class="bi bi-gear-fill"></i></div>
        <div class="aac-label">Configuración</div>
        <div class="aac-sub">Sistema y parámetros</div>
    </a>

    <a href="#" class="admin-action-card alert-card">
        <div class="aac-icon flagged"><i class="bi bi-flag-fill"></i></div>
        <div class="aac-label">Reportes de usuarios</div>
        <div class="aac-sub">29 pendientes</div>
        <span class="aac-badge">29</span>
    </a>

</div>

{{-- ── Dos columnas ── --}}
<div class="two-col">

    {{-- Usuarios recientes --}}
    <div class="dash-card">
        <div class="card-header-row">
            <h3><i class="bi bi-people-fill me-2" style="color:#534AB7;"></i>Usuarios recientes</h3>
            <a href="#">Ver todos <i class="bi bi-arrow-right"></i></a>
        </div>

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
                    <td><span class="role-chip invitado">Invitado</span></td>
                    <td><span class="plan-chip pro">Pro</span></td>
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
                    <td><span class="role-chip invitado">Invitado</span></td>
                    <td><span class="plan-chip basic">Básico</span></td>
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
                            <div class="user-avatar">CP</div>
                            <div>
                                <div class="user-name">Carlos Pérez</div>
                                <div class="user-email">carlos@email.com</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="role-chip gerente">Gerente</span></td>
                    <td><span class="plan-chip enterprise">Enterprise</span></td>
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
                            <div class="user-avatar" style="background:#fdecea;color:#e74c3c;">BL</div>
                            <div>
                                <div class="user-name">Bruno Lima</div>
                                <div class="user-email">bruno@email.com</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="role-chip invitado">Invitado</span></td>
                    <td><span class="plan-chip basic">Básico</span></td>
                    <td><span class="badge-estado badge-pausado"><i class="bi bi-slash-circle dot"></i>Bloqueado</span></td>
                    <td>
                        <div class="tbl-actions">
                            <button class="btn-tbl-edit" title="Editar"><i class="bi bi-pencil-fill"></i></button>
                            <button class="btn-tbl-restore" title="Restaurar"><i class="bi bi-arrow-counterclockwise"></i></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Publicaciones recientes --}}
    <div class="dash-card">
        <div class="card-header-row">
            <h3><i class="bi bi-box-seam-fill me-2" style="color:#27ae60;"></i>Publicaciones recientes</h3>
            <a href="#">Ver todas <i class="bi bi-arrow-right"></i></a>
        </div>

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