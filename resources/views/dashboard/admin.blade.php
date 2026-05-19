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
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <span class="admin-badge"><i class="bi bi-shield-fill"></i> Administrador</span>
        <span class="date-chip"><i class="bi bi-calendar3"></i> {{ now()->format('d M Y') }}</span>
    </div>
</div>

{{-- ── Stats del sistema (datos reales) ── --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="stat-num">{{ \App\Models\User::count() }}</div>
                <div class="stat-lbl">Usuarios registrados</div>
                <div class="stat-delta up">
                    <i class="bi bi-arrow-up-short"></i>
                    +{{ \App\Models\User::where('created_at', '>=', now()->subDays(7))->count() }} esta semana
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-box-seam-fill"></i></div>
            <div>
                <div class="stat-num">{{ \App\Models\Producto::where('estado', 'activo')->count() }}</div>
                <div class="stat-lbl">Publicaciones activas</div>
                <div class="stat-delta up">
                    <i class="bi bi-arrow-up-short"></i>
                    +{{ \App\Models\Producto::where('created_at', '>=', now()->subDays(30))->count() }} este mes
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="bi bi-hammer"></i></div>
            <div>
                <div class="stat-num">{{ \App\Models\Producto::where('tipo','subasta')->where('estado','activo')->count() }}</div>
                <div class="stat-lbl">Subastas en curso</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
            <div>
                <div class="stat-num">{{ \App\Models\User::where('status','activo')->count() }}</div>
                <div class="stat-lbl">Usuarios activos</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-slash-circle-fill"></i></div>
            <div>
                <div class="stat-num">{{ \App\Models\User::where('status','bloqueado')->count() }}</div>
                <div class="stat-lbl">Usuarios bloqueados</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="bi bi-envelope-exclamation-fill"></i></div>
            <div>
                <div class="stat-num">{{ \App\Models\User::whereNull('email_verified_at')->count() }}</div>
                <div class="stat-lbl">Sin verificar email</div>
            </div>
        </div>
    </div>
</div>

{{-- ── Acciones rápidas ── --}}
<div class="section-label">Acciones rápidas</div>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.usuarios') }}" class="admin-action-card h-100">
            <div class="aac-icon users"><i class="bi bi-people-fill"></i></div>
            <div class="aac-label">Gestionar usuarios</div>
            <div class="aac-sub">Ver, editar, bloquear</div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.publicaciones') }}" class="admin-action-card h-100">
            <div class="aac-icon pub"><i class="bi bi-box-seam-fill"></i></div>
            <div class="aac-label">Publicaciones</div>
            <div class="aac-sub">Editar, pausar, eliminar</div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('publicar.create') }}" class="admin-action-card h-100">
            <div class="aac-icon pub"><i class="bi bi-plus-circle-fill"></i></div>
            <div class="aac-label">Nueva publicación</div>
            <div class="aac-sub">Publicar herramienta</div>
        </a>
    </div>
</div>

{{-- ── Dos columnas: últimos usuarios + últimas publicaciones ── --}}
<div class="row g-3 mb-3">

    {{-- Usuarios recientes --}}
    <div class="col-12 col-xl-6">
        <div class="dash-card">
            <div class="card-header-row">
                <h3><i class="bi bi-people-fill me-2" style="color:#534AB7;"></i>Usuarios recientes</h3>
                <a href="{{ route('admin.usuarios') }}">Ver todos <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ultimosUsuarios as $u)
                        <tr>
                            <td>
                                <div class="user-row">
                                    <div class="user-avatar {{ $u->status === 'bloqueado' ? '' : '' }}"
                                         style="{{ $u->status === 'bloqueado' ? 'background:#fdecea;color:#e74c3c;' : '' }}">
                                        {{ strtoupper(substr($u->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="user-name">{{ $u->name }}</div>
                                        <div class="user-email">{{ $u->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge
                                    @if($u->rol === 'admin') bg-primary-subtle text-primary-emphasis
                                    @elseif($u->rol === 'gerente') bg-warning-subtle text-warning-emphasis
                                    @else bg-secondary-subtle text-secondary-emphasis
                                    @endif">
                                    {{ ucfirst($u->rol) }}
                                </span>
                            </td>
                            <td>
                                @if($u->status === 'bloqueado')
                                    <span class="badge-estado badge-pausado"><i class="bi bi-slash-circle dot"></i>Bloqueado</span>
                                @elseif($u->status === 'inactivo')
                                    <span class="badge-estado" style="background:#f5f5f5;color:#888;"><i class="bi bi-pause-circle dot"></i>Inactivo</span>
                                @else
                                    <span class="badge-estado badge-activo"><i class="bi bi-circle-fill dot"></i>Activo</span>
                                @endif
                            </td>
                            <td style="font-size:.75rem;color:var(--bs-secondary-color);">
                                {{ $u->created_at->format('d/m/Y') }}
                            </td>
                        </tr>
                        @endforeach
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
                <a href="{{ route('admin.publicaciones') }}">Ver todas <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Tipo</th>
                            <th>Precio</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ultimasPublicaciones as $p)
                        <tr>
                            <td>
                                <div class="user-row">
                                    <div class="pub-thumb"><i class="bi bi-gear"></i></div>
                                    <div>
                                        <div class="user-name" style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                            {{ $p->titulo }}
                                        </div>
                                        <div class="user-email">por {{ $p->user?->name ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-estado badge-{{ $p->tipo }}">{{ ucfirst($p->tipo) }}</span>
                            </td>
                            <td class="pub-price">${{ number_format($p->precio, 0) }}</td>
                            <td>
                                @if($p->estado === 'activo')
                                    <span class="badge-estado badge-activo"><i class="bi bi-circle-fill dot"></i>Activo</span>
                                @elseif($p->estado === 'pausado')
                                    <span class="badge-estado badge-pausado"><i class="bi bi-pause-circle-fill dot"></i>Pausado</span>
                                @elseif($p->estado === 'vendido')
                                    <span class="badge-estado" style="background:#e8f4fd;color:#1a6fa8;">Vendido</span>
                                @else
                                    <span class="badge-estado" style="background:#f5f5f5;color:#888;">{{ ucfirst($p->estado) }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- ── Actividad reciente del sistema (datos reales) ── --}}
<div class="dash-card">
    <div class="card-header-row">
        <h3><i class="bi bi-activity me-2" style="color:#e74c3c;"></i>Actividad reciente del sistema</h3>
    </div>

    <div class="activity-list">
        @forelse($actividad as $item)
        <div class="activity-item">
            <div class="act-icon {{ $item['color'] }}"><i class="bi {{ $item['icono'] }}"></i></div>
            <div class="act-body">
                <span class="act-msg">{!! $item['msg'] !!}</span>
                <span class="act-time">{{ $item['time'] }}</span>
            </div>
            @if($item['cta'])
                <a href="{{ $item['cta']['url'] }}" class="act-cta">{{ $item['cta']['label'] }}</a>
            @endif
        </div>
        @empty
        <div style="padding:2rem;text-align:center;color:var(--bs-secondary-color);font-size:.88rem;">
            Sin actividad reciente registrada.
        </div>
        @endforelse
    </div>
</div>

@endsection