{{-- resources/views/dashboard/admin/rentas.blade.php --}}
@extends('layouts.dashboard')

@section('titulo_pagina', 'Rentas – Tools365')
@section('topbar_title', 'Rentas')
@section('topbar_breadcrumb', 'Administración')

@push('css')
<style>
.dash-page-header { display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem; }
.dash-page-header h1 { font-size:1.4rem; font-weight:800; margin:0 0 .2rem; }
.dash-page-header p  { margin:0; color:var(--bs-secondary-color); font-size:.88rem; }

.mini-stats { display:flex; gap:.75rem; flex-wrap:wrap; margin-bottom:1.25rem; }
.mini-stat { background:var(--bs-body-bg); border:1px solid var(--bs-border-color); border-radius:.625rem; padding:.7rem 1.1rem; display:flex; align-items:center; gap:.6rem; flex:1; min-width:130px; }
.mini-stat-icon { width:36px; height:36px; border-radius:.5rem; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
.mini-stat-num { font-size:1.2rem; font-weight:800; line-height:1; }
.mini-stat-lbl { font-size:.75rem; color:var(--bs-secondary-color); }

.filter-bar { background:var(--bs-body-bg); border:1px solid var(--bs-border-color); border-radius:.75rem; padding:1rem 1.25rem; display:flex; flex-wrap:wrap; gap:.75rem; align-items:flex-end; margin-bottom:1.25rem; }
.filter-group { display:flex; flex-direction:column; gap:.3rem; min-width:140px; flex:1; }
.filter-group label { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--bs-secondary-color); }
.filter-group .form-control, .filter-group .form-select { font-size:.85rem; border-radius:.5rem; border-color:var(--bs-border-color); background:var(--bs-body-bg); color:var(--bs-body-color); padding:.4rem .75rem; }
.btn-filter { padding:.42rem 1rem; border-radius:.5rem; font-size:.85rem; font-weight:600; cursor:pointer; transition:all .15s; white-space:nowrap; }
.btn-filter-apply  { background:#534AB7; color:#fff; border:none; }
.btn-filter-apply:hover { background:#453da0; }
.btn-filter-clear  { background:var(--bs-tertiary-bg); color:var(--bs-body-color); border:1px solid var(--bs-border-color); }

.dash-card { background:var(--bs-body-bg); border:1px solid var(--bs-border-color); border-radius:.75rem; overflow:hidden; }
.card-header-row { display:flex; align-items:center; justify-content:space-between; padding:1rem 1.25rem; border-bottom:1px solid var(--bs-border-color); }
.card-header-row h3 { font-size:.95rem; font-weight:700; margin:0; }
.results-count { font-size:.8rem; color:var(--bs-secondary-color); }

.admin-table { width:100%; border-collapse:collapse; font-size:.85rem; }
.admin-table thead th { padding:.6rem 1rem; font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--bs-secondary-color); border-bottom:1px solid var(--bs-border-color); white-space:nowrap; }
.admin-table tbody td { padding:.8rem 1rem; border-bottom:1px solid var(--bs-border-color); vertical-align:middle; }
.admin-table tbody tr:last-child td { border-bottom:none; }
.admin-table tbody tr:hover { background:var(--bs-tertiary-bg); }

.product-thumb { width:40px; height:40px; border-radius:.5rem; object-fit:cover; }
.product-thumb-ph { width:40px; height:40px; border-radius:.5rem; background:var(--bs-tertiary-bg); display:flex; align-items:center; justify-content:center; color:var(--bs-secondary-color); flex-shrink:0; }
.product-row { display:flex; align-items:center; gap:.6rem; }
.product-title { font-weight:600; font-size:.85rem; max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.product-folio { font-size:.72rem; color:var(--bs-secondary-color); }

.user-chip { display:flex; align-items:center; gap:.4rem; }
.user-chip-av { width:28px; height:28px; border-radius:50%; background:#ede9ff; color:#534AB7; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:.68rem; flex-shrink:0; }
.user-chip-name { font-size:.82rem; font-weight:600; }

.date-range { font-size:.78rem; }
.date-range .from { color:var(--bs-secondary-color); }
.date-range .to   { color:var(--bs-body-color); font-weight:600; }

.badge-renta { display:inline-flex; align-items:center; gap:.25rem; font-size:.72rem; font-weight:700; padding:.25em .6em; border-radius:999px; }
.renta-activa    { background:#e6f9f0; color:#1a7f4b; }
.renta-finalizada{ background:#e8f4fd; color:#1a6fa8; }
.renta-cancelada { background:#fdecea; color:#c0392b; }

.money-cell { font-weight:700; white-space:nowrap; }
.comision-cell { font-size:.78rem; color:#534AB7; font-weight:600; }

.empty-state { text-align:center; padding:3rem 1rem; color:var(--bs-secondary-color); }
.empty-state i { font-size:2.5rem; margin-bottom:.75rem; display:block; }

.pagination-wrap { padding:.875rem 1.25rem; border-top:1px solid var(--bs-border-color); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.5rem; }
.pagination-info { font-size:.8rem; color:var(--bs-secondary-color); }
</style>
@endpush

@section('contenido')

<div class="dash-page-header">
    <div>
        <h1><i class="bi bi-clock-history me-2" style="color:#534AB7;"></i>Rentas</h1>
        <p>Monitorea todas las rentas activas y finalizadas de la plataforma.</p>
    </div>
    <span class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill"
          style="background:#ede9ff;color:#534AB7;font-size:.78rem;font-weight:700;">
        <i class="bi bi-shield-fill"></i> Administrador
    </span>
</div>

{{-- Mini stats --}}
<div class="mini-stats">
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#e8f4fd;color:#1a6fa8;"><i class="bi bi-clock-history"></i></div>
        <div><div class="mini-stat-num">{{ $stats['total'] }}</div><div class="mini-stat-lbl">Total rentas</div></div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#e6f9f0;color:#1a7f4b;"><i class="bi bi-activity"></i></div>
        <div><div class="mini-stat-num">{{ $stats['activas'] }}</div><div class="mini-stat-lbl">En curso</div></div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#e8f4fd;color:#3b82f6;"><i class="bi bi-check2-circle"></i></div>
        <div><div class="mini-stat-num">{{ $stats['finalizadas'] }}</div><div class="mini-stat-lbl">Finalizadas</div></div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#e6f9f0;color:#1a7f4b;"><i class="bi bi-currency-dollar"></i></div>
        <div><div class="mini-stat-num">${{ number_format($stats['ingresos'], 0) }}</div><div class="mini-stat-lbl">Ingresos brutos</div></div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#ede9ff;color:#534AB7;"><i class="bi bi-percent"></i></div>
        <div><div class="mini-stat-num">${{ number_format($stats['comisiones'], 0) }}</div><div class="mini-stat-lbl">Comisiones</div></div>
    </div>
</div>

{{-- Filtros --}}
<form method="GET" action="{{ route('admin.rentas') }}" class="filter-bar">
    <div class="filter-group" style="max-width:260px;">
        <label>Buscar</label>
        <input type="text" name="buscar" class="form-control" placeholder="Título de herramienta..." value="{{ request('buscar') }}">
    </div>
    <div class="filter-group" style="max-width:160px;">
        <label>Estado pedido</label>
        <select name="estado" class="form-select">
            <option value="">Todos</option>
            <option value="pagado"    {{ request('estado') === 'pagado'    ? 'selected' : '' }}>Pagado</option>
            <option value="cancelado" {{ request('estado') === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
        </select>
    </div>
    <div class="d-flex gap-2 align-items-end">
        <button type="submit" class="btn-filter btn-filter-apply"><i class="bi bi-search me-1"></i>Filtrar</button>
        @if(request()->hasAny(['buscar','estado']))
            <a href="{{ route('admin.rentas') }}" class="btn-filter btn-filter-clear">Limpiar</a>
        @endif
    </div>
</form>

{{-- Tabla --}}
<div class="dash-card">
    <div class="card-header-row">
        <h3><i class="bi bi-clock-history me-2" style="color:#534AB7;"></i>Rentas</h3>
        <span class="results-count">{{ $rentas->total() }} rentas</span>
    </div>

    <div class="table-responsive">
        @if($rentas->isEmpty())
            <div class="empty-state">
                <i class="bi bi-clock-history"></i>
                <p>No se encontraron rentas con los filtros aplicados.</p>
            </div>
        @else
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Herramienta</th>
                    <th>Arrendador</th>
                    <th>Cliente</th>
                    <th>Período</th>
                    <th>Total</th>
                    <th>Comisión</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rentas as $index => $r)
                @php
                    $esActiva     = $r->fecha_fin && \Carbon\Carbon::parse($r->fecha_fin)->isFuture() && $r->pedido?->estado === 'pagado';
                    $esCancelada  = $r->pedido?->estado === 'cancelado';
                    $estadoLabel  = $esCancelada ? 'Cancelada' : ($esActiva ? 'En curso' : 'Finalizada');
                    $estadoClass  = $esCancelada ? 'renta-cancelada' : ($esActiva ? 'renta-activa' : 'renta-finalizada');
                @endphp
                <tr>
                    <td class="text-muted" style="font-size:.78rem;">{{ $rentas->firstItem() + $index }}</td>
                    <td>
                        <div class="product-row">
                            @if($r->producto?->imagenes?->first())
                                <img src="{{ asset($r->producto->imagenes->first()->ruta) }}" class="product-thumb" alt="">
                            @else
                                <div class="product-thumb-ph"><i class="bi bi-image"></i></div>
                            @endif
                            <div>
                                <div class="product-title" title="{{ $r->titulo }}">{{ $r->titulo }}</div>
                                <div class="product-folio">Pedido #{{ $r->pedido?->folio }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="user-chip">
                            <div class="user-chip-av">{{ strtoupper(substr($r->vendedor?->name ?? '?', 0, 2)) }}</div>
                            <div>
                                <div class="user-chip-name">{{ $r->vendedor?->name ?? '—' }}</div>
                                <div style="font-size:.68rem;color:var(--bs-secondary-color);">Plan: {{ $r->vendedor?->plan ?? 'free' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="user-chip">
                            <div class="user-chip-av" style="background:#e6f9f0;color:#1a7f4b;">{{ strtoupper(substr($r->pedido?->user?->name ?? '?', 0, 2)) }}</div>
                            <div class="user-chip-name">{{ $r->pedido?->user?->name ?? '—' }}</div>
                        </div>
                    </td>
                    <td>
                        <div class="date-range">
                            <div class="from">Inicio: {{ $r->fecha_inicio ? \Carbon\Carbon::parse($r->fecha_inicio)->format('d/m/Y') : '—' }}</div>
                            <div class="to">Fin: {{ $r->fecha_fin ? \Carbon\Carbon::parse($r->fecha_fin)->format('d/m/Y') : '—' }}</div>
                        </div>
                    </td>
                    <td class="money-cell">${{ number_format($r->total_item, 2) }}</td>
                    <td class="comision-cell">${{ number_format($r->comision_plataforma, 2) }}</td>
                    <td>
                        <span class="badge-renta {{ $estadoClass }}">
                            <i class="bi bi-circle-fill" style="font-size:.45rem;"></i>
                            {{ $estadoLabel }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    @if($rentas->hasPages())
        <div class="pagination-wrap">
            <span class="pagination-info">Página {{ $rentas->currentPage() }} de {{ $rentas->lastPage() }}</span>
            {{ $rentas->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

@endsection