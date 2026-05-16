{{-- resources/views/dashboard/ventas.blade.php --}}
@extends('layouts.dashboard')

@section('titulo_pagina', 'Mis ventas — Tools365')
@section('topbar_title', 'Mis Ventas')

@push('css')
<style>
/* ── Stat cards ───────────────────────────────────────────────────────────── */
.vt-stat {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: .75rem;
    padding: 1.1rem 1.25rem;
    display: flex; align-items: center; gap: 1rem;
}
.vt-stat-icon {
    width: 46px; height: 46px; border-radius: .625rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; flex-shrink: 0;
}
.vt-stat-icon.green  { background: #e6f9f0; color: #1a7f4b; }
.vt-stat-icon.purple { background: #ede9ff; color: #534AB7; }
.vt-stat-icon.blue   { background: #e8f4fd; color: #1a6fa8; }
.vt-stat-num { font-size: 1.45rem; font-weight: 800; }
.vt-stat-lbl { font-size: .8rem; color: var(--bs-secondary-color); }

/* ── Toolbar ──────────────────────────────────────────────────────────────── */
.vt-toolbar {
    display: flex; gap: 10px; flex-wrap: wrap; align-items: center;
    margin-bottom: 18px;
}
.vt-search {
    flex: 1; min-width: 180px;
    display: flex; align-items: center;
    background: var(--bs-body-bg);
    border: 1.5px solid var(--bs-border-color);
    border-radius: 10px; padding: 0 12px; gap: .5rem;
    transition: border-color .2s;
}
.vt-search:focus-within { border-color: #534AB7; }
.vt-search input {
    border: none; background: transparent; outline: none;
    padding: .6rem .4rem; font-size: .85rem; flex: 1;
    color: var(--bs-body-color);
}
.vt-filter {
    padding: .6rem 1rem; border-radius: 10px;
    border: 1.5px solid var(--bs-border-color);
    background: var(--bs-body-bg);
    font-size: .85rem; color: var(--bs-body-color);
    cursor: pointer;
}

/* ── Tabla ventas ─────────────────────────────────────────────────────────── */
.vt-card {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: .75rem; overflow: hidden;
    box-shadow: 0 2px 16px rgba(0,0,0,.04);
}
.vt-table { width:100%; border-collapse:collapse; font-size:.85rem; }
.vt-table thead th {
    padding: .65rem 1rem;
    font-size: .72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .06em;
    color: var(--bs-secondary-color);
    border-bottom: 1.5px solid var(--bs-border-color);
    background: var(--bs-tertiary-bg);
    white-space: nowrap;
}
.vt-table tbody tr { border-bottom: 1px solid var(--bs-border-color); transition: background .15s; }
.vt-table tbody tr:last-child { border-bottom: none; }
.vt-table tbody tr:hover { background: var(--bs-tertiary-bg); }
.vt-table td { padding: .9rem 1rem; vertical-align: middle; }

.vt-prod-cell { display: flex; align-items: center; gap: 10px; }
.vt-thumb {
    width: 44px; height: 44px; border-radius: 8px;
    overflow: hidden; flex-shrink: 0;
    border: 1px solid var(--bs-border-color);
    background: var(--bs-tertiary-bg);
    display: flex; align-items: center; justify-content: center;
}
.vt-thumb img { width:100%;height:100%;object-fit:cover; }
.vt-titulo { font-weight: 700; font-size: .85rem; }
.vt-meta   { font-size: .75rem; color: var(--bs-secondary-color); }

.badge-tipo-vt {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .25em .65em; border-radius: 999px;
    font-size: .72rem; font-weight: 700;
}
.tipo-comprar { background: #dcfce7; color: #166534; }
.tipo-rentar  { background: #dbeafe; color: #1d4ed8; }

.vt-price { font-weight: 800; color: #1a7f4b; }

.badge-comprador {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .78rem; font-weight: 600;
    padding: .25em .65em; border-radius: 999px;
    background: #ede9ff; color: #534AB7;
}

/* ── Empty ────────────────────────────────────────────────────────────────── */
.vt-empty { text-align:center; padding: 60px 20px; color: var(--bs-secondary-color); }
.vt-empty i { font-size: 3rem; display:block; margin-bottom:14px; opacity:.3; }
.vt-empty h4 { font-weight:700; color:var(--bs-body-color); margin-bottom:6px; }
</style>
@endpush

@section('contenido')

{{-- Header --}}
<div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-4">
    <div>
        <h1 style="font-size:1.5rem;font-weight:800;margin:0 0 .2rem;">Mis Ventas & Rentas</h1>
        <p style="margin:0;color:var(--bs-secondary-color);font-size:.88rem;">
            Historial de adquisiciones de tus herramientas publicadas
        </p>
    </div>
    <a href="{{ route('mis-publicaciones.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2" style="font-size:.85rem;">
        <i class="bi bi-box-seam"></i> Ver publicaciones
    </a>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="vt-stat">
            <div class="vt-stat-icon green"><i class="bi bi-cash-coin"></i></div>
            <div>
                <div class="vt-stat-num">${{ number_format($totalIngresos, 2) }}</div>
                <div class="vt-stat-lbl">Ingresos totales</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="vt-stat">
            <div class="vt-stat-icon purple"><i class="bi bi-bag-check-fill"></i></div>
            <div>
                <div class="vt-stat-num">{{ $totalVentas }}</div>
                <div class="vt-stat-lbl">Ventas realizadas</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="vt-stat">
            <div class="vt-stat-icon blue"><i class="bi bi-clock-history"></i></div>
            <div>
                <div class="vt-stat-num">{{ $totalRentas }}</div>
                <div class="vt-stat-lbl">Rentas realizadas</div>
            </div>
        </div>
    </div>
</div>

{{-- Toolbar --}}
<form action="{{ route('dashboard.ventas') }}" method="GET">
    <div class="vt-toolbar">
        <div class="vt-search">
            <i class="bi bi-search" style="color:var(--bs-secondary-color);"></i>
            <input type="text" name="q" placeholder="Buscar producto..." value="{{ request('q') }}">
        </div>
        <select name="tipo" class="vt-filter" onchange="this.form.submit()">
            <option value="">Todos los tipos</option>
            <option value="comprar" {{ request('tipo') === 'comprar' ? 'selected' : '' }}>Solo ventas</option>
            <option value="rentar"  {{ request('tipo') === 'rentar'  ? 'selected' : '' }}>Solo rentas</option>
        </select>
        <button type="submit" class="btn btn-outline-secondary" style="font-size:.85rem;">
            <i class="bi bi-funnel"></i>
        </button>
    </div>
</form>

{{-- Tabla --}}
<div class="vt-card">
    @if($ventas->isEmpty())
        <div class="vt-empty">
            <i class="bi bi-bag-x"></i>
            <h4>Sin ventas todavía</h4>
            <p>Cuando alguien compre o rente tus herramientas aparecerá aquí.</p>
            <a href="{{ route('publicar.create') }}" class="btn btn-primary btn-sm mt-2">
                <i class="bi bi-plus-lg me-1"></i>Publicar herramienta
            </a>
        </div>
    @else
        <table class="vt-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Tipo</th>
                    <th>Comprador</th>
                    <th>Total</th>
                    <th>Fecha</th>
                    <th>Pedido</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ventas as $venta)
                <tr>
                    {{-- Producto --}}
                    <td>
                        <div class="vt-prod-cell">
                            <div class="vt-thumb">
                                @php $img = $venta->producto?->imagenes?->first(); @endphp
                                @if($img) <img src="{{ asset($img->ruta) }}" alt="">
                                @else <i class="bi bi-gear" style="color:var(--bs-secondary-color);"></i>
                                @endif
                            </div>
                            <div>
                                <div class="vt-titulo">{{ Str::limit($venta->titulo, 42) }}</div>
                                @if($venta->tipo_accion === 'rentar' && $venta->fecha_inicio)
                                    <div class="vt-meta">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {{ $venta->fecha_inicio->format('d/m/Y') }} – {{ $venta->fecha_fin->format('d/m/Y') }}
                                    </div>
                                @elseif($venta->cantidad > 1)
                                    <div class="vt-meta">x{{ $venta->cantidad }} unidades</div>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Tipo --}}
                    <td>
                        <span class="badge-tipo-vt {{ $venta->tipo_accion === 'comprar' ? 'tipo-comprar' : 'tipo-rentar' }}">
                            <i class="bi {{ $venta->tipo_accion === 'comprar' ? 'bi-bag-check-fill' : 'bi-clock-history' }}"></i>
                            {{ $venta->tipo_accion === 'comprar' ? 'Venta' : 'Renta' }}
                        </span>
                    </td>

                    {{-- Comprador --}}
                    <td>
                        <span class="badge-comprador">
                            <i class="bi bi-person-fill"></i>
                            {{ explode(' ', $venta->pedido->user->name)[0] }}
                        </span>
                    </td>

                    {{-- Total --}}
                    <td><span class="vt-price">${{ number_format($venta->total_item, 2) }}</span></td>

                    {{-- Fecha --}}
                    <td>
                        <span style="font-size:.82rem;color:var(--bs-secondary-color);">
                            {{ $venta->pedido->pagado_at?->format('d/m/Y') }}
                        </span>
                    </td>

                    {{-- Folio --}}
                    <td>
                        <span style="font-size:.8rem;font-weight:700;color:#534AB7;">
                            {{ $venta->pedido->folio }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($ventas->hasPages())
            <div style="padding:14px 20px;border-top:1px solid var(--bs-border-color);">
                {{ $ventas->links() }}
            </div>
        @endif
    @endif
</div>

@endsection