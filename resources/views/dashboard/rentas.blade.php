{{-- resources/views/dashboard/rentas.blade.php --}}
@extends('layouts.dashboard')

@section('titulo_pagina', 'Mis Rentas — Tools365')
@section('topbar_title', 'Mis Rentas')

@push('css')
<style>
/* ══ VARIABLES ══════════════════════════════════════════════════════ */
:root {
    --rt-accent:   #0ea5e9;
    --rt-accent2:  #38bdf8;
    --rt-green:    #16a34a;
    --rt-amber:    #d97706;
    --rt-red:      #dc2626;
    --rt-surface:  #ffffff;
    --rt-border:   #e5e7eb;
    --rt-bg:       #f9fafb;
    --rt-text:     #111827;
    --rt-muted:    #6b7280;
    --rt-radius:   14px;
    --rt-shadow:   0 4px 20px rgba(0,0,0,.06);
}
[data-theme="dark"] {
    --rt-surface: #1e293b;
    --rt-border:  #334155;
    --rt-bg:      #0f172a;
    --rt-text:    #f1f5f9;
    --rt-muted:   #94a3b8;
    --rt-shadow:  0 4px 20px rgba(0,0,0,.35);
}

/* ══ HERO ═════════════════════════════════════════════════════════════ */
.rt-hero {
    background: linear-gradient(135deg, #0c4a6e 0%, #0369a1 60%, #0ea5e9 100%);
    border-radius: var(--rt-radius);
    padding: 1.8rem 2.2rem;
    color: #fff;
    margin-bottom: 1.8rem;
    display: flex; align-items: center; gap: 1.5rem;
    flex-wrap: wrap;
}
.rt-hero-icon { font-size: 2.8rem; opacity: .8; }
.rt-hero h2   { margin: 0; font-size: 1.5rem; font-weight: 800; }
.rt-hero p    { margin: .25rem 0 0; opacity: .75; font-size: .88rem; }
.rt-hero-stats {
    margin-left: auto; display: flex; gap: 2rem; flex-wrap: wrap;
}
.rt-hero-stat { text-align: center; }
.rt-hero-stat .val { font-size: 1.8rem; font-weight: 900; line-height: 1; }
.rt-hero-stat .lbl { font-size: .72rem; opacity: .7; text-transform: uppercase; letter-spacing: .05em; }

/* ══ TABS ═════════════════════════════════════════════════════════════ */
.rt-tabs {
    display: flex; gap: .5rem;
    border-bottom: 2px solid var(--rt-border);
    margin-bottom: 1.5rem;
}
.rt-tab {
    padding: .65rem 1.4rem;
    border: none; background: transparent;
    font-weight: 600; font-size: .9rem;
    color: var(--rt-muted); cursor: pointer;
    border-bottom: 2px solid transparent;
    position: relative; bottom: -2px;
    border-radius: 8px 8px 0 0;
    transition: color .15s;
}
.rt-tab.active { color: var(--rt-accent); border-bottom-color: var(--rt-accent); }
.rt-tab .badge { font-size: .68rem; margin-left: .3rem; vertical-align: middle; }

/* ══ FILTROS ══════════════════════════════════════════════════════════ */
.rt-filters {
    display: flex; gap: .6rem; flex-wrap: wrap;
    margin-bottom: 1.2rem;
}
.rt-filter-input {
    flex: 1; min-width: 180px;
    padding: .55rem .9rem;
    border: 1.5px solid var(--rt-border);
    border-radius: 10px;
    font-size: .85rem;
    background: var(--rt-surface);
    color: var(--rt-text);
    outline: none;
    transition: border-color .2s;
}
.rt-filter-input:focus { border-color: var(--rt-accent); }
.rt-filter-select {
    padding: .55rem .9rem;
    border: 1.5px solid var(--rt-border);
    border-radius: 10px;
    font-size: .85rem;
    background: var(--rt-surface);
    color: var(--rt-text);
    outline: none;
    cursor: pointer;
    transition: border-color .2s;
}
.rt-filter-select:focus { border-color: var(--rt-accent); }

/* ══ TARJETA DE RENTA ═════════════════════════════════════════════════ */
.rt-card {
    background: var(--rt-surface);
    border: 1.5px solid var(--rt-border);
    border-radius: var(--rt-radius);
    overflow: hidden;
    transition: box-shadow .2s, transform .15s;
    box-shadow: var(--rt-shadow);
    margin-bottom: 14px;
}
.rt-card:hover { box-shadow: 0 8px 28px rgba(0,0,0,.10); transform: translateY(-1px); }

.rt-card-inner {
    display: flex; align-items: stretch; gap: 0;
}
.rt-card-thumb {
    width: 100px; flex-shrink: 0;
    background: var(--rt-bg);
    display: flex; align-items: center; justify-content: center;
    font-size: 2.2rem; color: #94a3b8;
    overflow: hidden;
}
.rt-card-thumb img { width: 100%; height: 100%; object-fit: cover; }

.rt-card-body {
    flex: 1; padding: 1rem 1.2rem;
    display: flex; flex-direction: column; justify-content: space-between;
    min-width: 0;
}
.rt-card-title {
    font-weight: 700; font-size: .92rem;
    color: var(--rt-text); margin: 0 0 .3rem;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.rt-card-meta {
    font-size: .78rem; color: var(--rt-muted);
    display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: .5rem;
}
.rt-card-meta span { display: flex; align-items: center; gap: .25rem; }

/* Status pills */
.rt-status {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .72rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .04em; padding: .2rem .65rem; border-radius: 999px;
}
.st-activa    { background: #dcfce7; color: #166534; }
.st-finalizada{ background: #e0e7ff; color: #3730a3; }
.st-pausada   { background: #fef3c7; color: #92400e; }
.st-cancelada { background: #fee2e2; color: #991b1b; }
.st-pendiente { background: #fef9c3; color: #713f12; }

/* Timeline de fechas */
.rt-timeline {
    display: flex; align-items: center; gap: .5rem;
    font-size: .78rem; color: var(--rt-muted);
    margin-top: .3rem;
}
.rt-timeline .date-pill {
    background: var(--rt-bg);
    border: 1px solid var(--rt-border);
    border-radius: 6px; padding: .15rem .5rem;
    font-weight: 600; font-size: .75rem;
    color: var(--rt-text);
}
.rt-timeline .arrow { color: var(--rt-muted); }
.rt-timeline .dias-pill {
    background: #dbeafe; color: #1d4ed8;
    border-radius: 6px; padding: .15rem .5rem;
    font-weight: 700; font-size: .72rem;
}

.rt-card-right {
    padding: 1rem 1.2rem;
    display: flex; flex-direction: column;
    align-items: flex-end; justify-content: space-between;
    gap: .5rem; flex-shrink: 0;
    border-left: 1px solid var(--rt-border);
    min-width: 130px;
}
.rt-card-price {
    font-size: 1.2rem; font-weight: 900;
    color: var(--rt-accent); text-align: right;
}
.rt-card-price small { font-size: .72rem; color: var(--rt-muted); font-weight: 400; display: block; }

.rt-card-actions { display: flex; gap: .4rem; flex-direction: column; width: 100%; }
.rt-btn {
    font-size: .78rem; font-weight: 600; padding: .35rem .75rem;
    border-radius: 8px; border: none; cursor: pointer;
    transition: all .15s; white-space: nowrap; text-align: center;
    text-decoration: none; display: inline-flex; align-items: center;
    justify-content: center; gap: .3rem;
}
.rt-btn-outline {
    background: transparent;
    border: 1.5px solid var(--rt-border);
    color: var(--rt-text);
}
.rt-btn-outline:hover { border-color: var(--rt-accent); color: var(--rt-accent); }
.rt-btn-primary { background: var(--rt-accent); color: #fff; }
.rt-btn-primary:hover { background: #0284c7; color: #fff; }
.rt-btn-danger  { background: transparent; border: 1.5px solid #fca5a5; color: #dc2626; }
.rt-btn-danger:hover { background: #fef2f2; }

/* ══ PROGRESS BAR RENTA ══════════════════════════════════════════════ */
.renta-progress-wrap { margin-top: .4rem; }
.renta-progress-label {
    display: flex; justify-content: space-between;
    font-size: .7rem; color: var(--rt-muted); margin-bottom: .25rem;
}
.renta-progress-bg {
    height: 5px; background: var(--rt-border);
    border-radius: 999px; overflow: hidden;
}
.renta-progress-fill {
    height: 100%; border-radius: 999px;
    background: linear-gradient(90deg, var(--rt-accent), var(--rt-accent2));
    transition: width .6s ease;
}
.renta-progress-fill.done    { background: linear-gradient(90deg,#22c55e,#4ade80); }
.renta-progress-fill.warning { background: linear-gradient(90deg,#f59e0b,#fbbf24); }

/* ══ EMPTY STATE ═════════════════════════════════════════════════════ */
.rt-empty {
    text-align: center; padding: 3rem 1rem; color: var(--rt-muted);
}
.rt-empty i { font-size: 3rem; display: block; margin-bottom: .8rem; }

/* ══ RESPONSIVE ══════════════════════════════════════════════════════ */
@media (max-width: 600px) {
    .rt-card-inner { flex-direction: column; }
    .rt-card-thumb { width: 100%; height: 140px; }
    .rt-card-right {
        flex-direction: row; align-items: center;
        border-left: none; border-top: 1px solid var(--rt-border);
        min-width: 0;
    }
    .rt-card-actions { flex-direction: row; width: auto; }
    .rt-hero-stats { margin-left: 0; }
}

/* ══ DARK ═════════════════════════════════════════════════════════════ */
[data-theme="dark"] .rt-card { background: #1e293b; border-color: #334155; }
[data-theme="dark"] .st-activa     { background: rgba(22,163,74,.2); color: #4ade80; }
[data-theme="dark"] .st-finalizada { background: rgba(99,102,241,.2); color: #a5b4fc; }
[data-theme="dark"] .st-pendiente  { background: rgba(217,119,6,.2); color: #fbbf24; }
[data-theme="dark"] .rt-timeline .date-pill { background: #0f172a; border-color: #334155; }
[data-theme="dark"] .rt-timeline .dias-pill { background: rgba(59,130,246,.2); color: #93c5fd; }
</style>
@endpush

@section('contenido')
@php
    $user = auth()->user();

    // ── Rentas donde soy ARRENDADOR (publiqué la herramienta) ──────────
    $rentasPublicadas = \App\Models\PedidoItem::with(['pedido.user','producto.imagenes'])
        ->where('vendedor_id', $user->id)
        ->where('tipo_accion', 'rentar')
        ->whereHas('pedido', fn($q) => $q->whereIn('estado',['pagado','cancelado']))
        ->latest()
        ->get();

    // ── Rentas donde soy CLIENTE (renté una herramienta) ──────────────
    $rentasCliente = \App\Models\PedidoItem::with(['pedido','producto.imagenes','producto.user'])
        ->where('tipo_accion', 'rentar')
        ->whereHas('pedido', fn($q) =>
            $q->where('user_id', $user->id)
              ->whereIn('estado',['pagado','cancelado'])
        )
        ->latest()
        ->get();

    $totalArrendador = $rentasPublicadas->count();
    $totalCliente    = $rentasCliente->count();
    $ingresoRentas   = $rentasPublicadas
        ->filter(fn($i) => $i->pedido?->estado === 'pagado')
        ->sum('total_item');
@endphp

<div class="container py-4">

    {{-- HERO --}}
    <div class="rt-hero">
        <div class="rt-hero-icon">🔧</div>
        <div>
            <h2>Mis Rentas</h2>
            <p>Gestiona las herramientas que rentaste y las que tienes en renta.</p>
        </div>
        <div class="rt-hero-stats">
            <div class="rt-hero-stat">
                <div class="val">{{ $totalArrendador }}</div>
                <div class="lbl">Rentas publicadas</div>
            </div>
            <div class="rt-hero-stat">
                <div class="val">{{ $totalCliente }}</div>
                <div class="lbl">Herramientas rentadas</div>
            </div>
            <div class="rt-hero-stat">
                <div class="val">${{ number_format($ingresoRentas,0,'.', ',') }}</div>
                <div class="lbl">Ingresos por rentas</div>
            </div>
        </div>
    </div>

    {{-- TABS --}}
    <div class="rt-tabs">
        <button class="rt-tab active" onclick="switchTab('arrendador',this)">
            <i class="bi bi-tools me-1"></i>
            Mis herramientas en renta
            <span class="badge bg-primary">{{ $totalArrendador }}</span>
        </button>
        <button class="rt-tab" onclick="switchTab('cliente',this)">
            <i class="bi bi-basket me-1"></i>
            Herramientas que renté
            <span class="badge bg-secondary">{{ $totalCliente }}</span>
        </button>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         TAB 1 — SOY ARRENDADOR
    ══════════════════════════════════════════════════════════════════ --}}
    <div id="tab-arrendador">

        {{-- Filtros --}}
        <div class="rt-filters">
            <input type="text" class="rt-filter-input" placeholder="🔍 Buscar herramienta..."
                   id="filtroArrendador" oninput="filtrarTarjetas('arrendador')">
            <select class="rt-filter-select" id="filtroEstadoArrendador"
                    onchange="filtrarTarjetas('arrendador')">
                <option value="">Todos los estados</option>
                <option value="activa">Activa</option>
                <option value="finalizada">Finalizada</option>
                <option value="pausada">Pausada</option>
            </select>
        </div>

        @if($rentasPublicadas->isEmpty())
            <div class="rt-empty">
                <i class="bi bi-tools"></i>
                <p>Aún no tienes herramientas en renta.</p>
                <a href="{{ route('publicar.create') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-plus-lg me-1"></i> Publicar herramienta
                </a>
            </div>
        @else
        <div id="cards-arrendador">
        @foreach($rentasPublicadas as $item)
        @php
            $pedido = $item->pedido;
            $prod   = $item->producto;
            $img    = $prod?->imagenes?->first();

            $inicio = $item->fecha_inicio ? \Carbon\Carbon::parse($item->fecha_inicio) : null;
            $fin    = $item->fecha_fin    ? \Carbon\Carbon::parse($item->fecha_fin)    : null;
            $hoy    = now()->startOfDay();

            $totalDias   = ($inicio && $fin) ? $inicio->diffInDays($fin) + 1 : null;
            $diasPasados = ($inicio && $fin)
                ? min($totalDias, max(0, $inicio->diffInDays($hoy)))
                : null;
            $pct = ($totalDias && $totalDias > 0) ? round($diasPasados/$totalDias*100) : 0;

            if ($pedido->estado === 'cancelado') {
                $statusClass = 'st-cancelada'; $statusLabel = 'Cancelada';
            } elseif (!$inicio || !$fin) {
                $statusClass = 'st-pendiente'; $statusLabel = 'Sin fechas';
            } elseif ($hoy->gt($fin)) {
                $statusClass = 'st-finalizada'; $statusLabel = 'Finalizada';
            } elseif ($hoy->gte($inicio)) {
                $statusClass = 'st-activa'; $statusLabel = 'Activa';
            } else {
                $statusClass = 'st-pendiente'; $statusLabel = 'Próxima';
            }

            $pctClass = $pct >= 100 ? 'done' : ($pct >= 70 ? 'warning' : '');
        @endphp
        <div class="rt-card"
             data-titulo="{{ strtolower($prod?->titulo) }}"
             data-estado="{{ strtolower($statusLabel) }}"
             data-tab="arrendador">
            <div class="rt-card-inner">
                <div class="rt-card-thumb">
                    @if($img) <img src="{{ asset($img->ruta) }}" alt="{{ $prod->titulo }}">
                    @else <i class="bi bi-tools"></i> @endif
                </div>
                <div class="rt-card-body">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <span class="rt-status {{ $statusClass }}">
                                <i class="bi bi-circle-fill" style="font-size:.4rem"></i>
                                {{ $statusLabel }}
                            </span>
                            <span style="font-size:.75rem;color:var(--rt-muted);">
                                Pedido {{ $pedido->folio }}
                            </span>
                        </div>
                        <div class="rt-card-title">{{ $prod?->titulo ?? 'Producto eliminado' }}</div>
                        <div class="rt-card-meta">
                            <span><i class="bi bi-person-fill"></i> {{ $pedido->user?->name ?? '—' }}</span>
                            <span><i class="bi bi-envelope"></i> {{ $pedido->user?->email ?? '—' }}</span>
                            @if($item->cantidad > 1)
                                <span><i class="bi bi-hash"></i> {{ $item->cantidad }} unidades</span>
                            @endif
                        </div>
                        @if($inicio && $fin)
                        <div class="rt-timeline">
                            <span class="date-pill">{{ $inicio->format('d/m/Y') }}</span>
                            <span class="arrow">→</span>
                            <span class="date-pill">{{ $fin->format('d/m/Y') }}</span>
                            <span class="dias-pill">{{ $totalDias }} día(s)</span>
                        </div>
                        @if($statusClass === 'st-activa')
                        <div class="renta-progress-wrap">
                            <div class="renta-progress-label">
                                <span>Progreso de la renta</span>
                                <span>{{ $diasPasados }}/{{ $totalDias }} días</span>
                            </div>
                            <div class="renta-progress-bg">
                                <div class="renta-progress-fill {{ $pctClass }}"
                                     style="width:{{ $pct }}%"></div>
                            </div>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
                <div class="rt-card-right">
                    <div class="rt-card-price">
                        ${{ number_format($item->total_item, 2) }}
                        <small>ingreso total</small>
                    </div>
                    <div class="rt-card-actions">
@if($prod && $prod->estado === 'pausado' && $statusClass === 'st-finalizada')
    <form method="POST"
          action="{{ route('dashboard.rentas.reactivar', $item) }}"
          onsubmit="return confirm('¿Reactivar esta publicación?')">
        @csrf @method('PATCH')
        <button class="rt-btn rt-btn-primary w-100">
            <i class="bi bi-play-circle"></i> Reactivar
        </button>
    </form>
                        @elseif($prod && in_array($prod->estado,['activo','pausado']))
                            <a href="{{ route('mis-publicaciones.show', $prod) }}"
                               class="rt-btn rt-btn-outline">
                                <i class="bi bi-eye"></i> Ver pub.
                            </a>
                        @endif
                        <button class="rt-btn rt-btn-outline"
                                onclick="verDetalleRenta({{ $item->id }}, 'arrendador')">
                            <i class="bi bi-info-circle"></i> Detalle
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         TAB 2 — SOY CLIENTE
    ══════════════════════════════════════════════════════════════════ --}}
    <div id="tab-cliente" style="display:none;">

        <div class="rt-filters">
            <input type="text" class="rt-filter-input" placeholder="🔍 Buscar herramienta..."
                   id="filtroCliente" oninput="filtrarTarjetas('cliente')">
            <select class="rt-filter-select" id="filtroEstadoCliente"
                    onchange="filtrarTarjetas('cliente')">
                <option value="">Todos los estados</option>
                <option value="activa">Activa</option>
                <option value="finalizada">Finalizada</option>
                <option value="próxima">Próxima</option>
            </select>
        </div>

        @if($rentasCliente->isEmpty())
            <div class="rt-empty">
                <i class="bi bi-basket"></i>
                <p>Aún no has rentado ninguna herramienta.</p>
                <a href="{{ route('inicio') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-search me-1"></i> Explorar herramientas
                </a>
            </div>
        @else
        <div id="cards-cliente">
        @foreach($rentasCliente as $item)
        @php
            $pedido = $item->pedido;
            $prod   = $item->producto;
            $img    = $prod?->imagenes?->first();
            $vendedor = $prod?->user;

            $inicio = $item->fecha_inicio ? \Carbon\Carbon::parse($item->fecha_inicio) : null;
            $fin    = $item->fecha_fin    ? \Carbon\Carbon::parse($item->fecha_fin)    : null;
            $hoy    = now()->startOfDay();

            $totalDias   = ($inicio && $fin) ? $inicio->diffInDays($fin) + 1 : null;
            $diasPasados = ($inicio && $fin)
                ? min($totalDias, max(0, $inicio->diffInDays($hoy)))
                : null;
            $pct = ($totalDias && $totalDias > 0) ? round($diasPasados/$totalDias*100) : 0;

            if ($pedido->estado === 'cancelado') {
                $statusClass = 'st-cancelada'; $statusLabel = 'Cancelada';
            } elseif (!$inicio || !$fin) {
                $statusClass = 'st-pendiente'; $statusLabel = 'Sin fechas';
            } elseif ($hoy->gt($fin)) {
                $statusClass = 'st-finalizada'; $statusLabel = 'Finalizada';
            } elseif ($hoy->gte($inicio)) {
                $statusClass = 'st-activa'; $statusLabel = 'Activa';
            } else {
                $statusClass = 'st-pendiente'; $statusLabel = 'Próxima';
            }

            $pctClass = $pct >= 100 ? 'done' : ($pct >= 70 ? 'warning' : '');
            $diasRestantes = ($fin && $hoy->lt($fin)) ? (int)$hoy->diffInDays($fin) : 0;
        @endphp
        <div class="rt-card"
             data-titulo="{{ strtolower($prod?->titulo) }}"
             data-estado="{{ strtolower($statusLabel) }}"
             data-tab="cliente">
            <div class="rt-card-inner">
                <div class="rt-card-thumb">
                    @if($img) <img src="{{ asset($img->ruta) }}" alt="{{ $prod?->titulo }}">
                    @else <i class="bi bi-tools"></i> @endif
                </div>
                <div class="rt-card-body">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <span class="rt-status {{ $statusClass }}">
                                <i class="bi bi-circle-fill" style="font-size:.4rem"></i>
                                {{ $statusLabel }}
                            </span>
                            @if($statusClass === 'st-activa' && $diasRestantes > 0)
                                <span style="font-size:.73rem;font-weight:700;color:#d97706;background:#fef3c7;padding:.15rem .5rem;border-radius:6px;">
                                    <i class="bi bi-clock me-1"></i>{{ $diasRestantes }} día(s) restante(s)
                                </span>
                            @endif
                        </div>
                        <div class="rt-card-title">{{ $prod?->titulo ?? 'Producto eliminado' }}</div>
                        <div class="rt-card-meta">
                            <span><i class="bi bi-person-check-fill"></i>
                                Arrendador: {{ $vendedor?->name ?? '—' }}
                            </span>
                            <span><i class="bi bi-geo-alt"></i> {{ $prod?->ubicacion ?? '—' }}</span>
                        </div>
                        @if($inicio && $fin)
                        <div class="rt-timeline">
                            <span class="date-pill">{{ $inicio->format('d/m/Y') }}</span>
                            <span class="arrow">→</span>
                            <span class="date-pill">{{ $fin->format('d/m/Y') }}</span>
                            <span class="dias-pill">{{ $totalDias }} día(s)</span>
                        </div>
                        @if($statusClass === 'st-activa')
                        <div class="renta-progress-wrap">
                            <div class="renta-progress-label">
                                <span>Tiempo de renta transcurrido</span>
                                <span>{{ $diasPasados }}/{{ $totalDias }} días</span>
                            </div>
                            <div class="renta-progress-bg">
                                <div class="renta-progress-fill {{ $pctClass }}"
                                     style="width:{{ $pct }}%"></div>
                            </div>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
                <div class="rt-card-right">
                    <div class="rt-card-price">
                        ${{ number_format($item->total_item, 2) }}
                        <small>pagado total</small>
                    </div>
                    <div class="rt-card-actions">
                        @if($prod && $prod->estado === 'activo')
                        <a href="{{ route('productos.show', $prod) }}"
                           class="rt-btn rt-btn-outline">
                            <i class="bi bi-box-arrow-up-right"></i> Ver prod.
                        </a>
                        @endif
                        <a href="{{ route('dashboard.compras') }}"
                           class="rt-btn rt-btn-outline">
                            <i class="bi bi-receipt"></i> Pedido
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        </div>
        @endif
    </div>

</div>

{{-- ══ MODAL DETALLE ═══════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalDetalleRenta" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none;">
            <div class="modal-header border-0" style="background:linear-gradient(135deg,#0c4a6e,#0ea5e9);color:#fff;">
                <h5 class="modal-title fw-bold" id="detalleTitle">Detalle de renta</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="detalleBody">
                <div class="text-center py-3"><div class="spinner-border text-primary"></div></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ── Tabs ─────────────────────────────────────────────────────────────
function switchTab(tab, btn) {
    document.getElementById('tab-arrendador').style.display = 'none';
    document.getElementById('tab-cliente').style.display    = 'none';
    document.getElementById('tab-' + tab).style.display     = '';
    document.querySelectorAll('.rt-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

// ── Filtros ───────────────────────────────────────────────────────────
function filtrarTarjetas(tab) {
    const q      = document.getElementById('filtro' + (tab==='arrendador' ? 'Arrendador' : 'Cliente'))?.value.toLowerCase() ?? '';
    const estado = document.getElementById('filtroEstado' + (tab==='arrendador' ? 'Arrendador' : 'Cliente'))?.value.toLowerCase() ?? '';

    document.querySelectorAll(`[data-tab="${tab}"]`).forEach(card => {
        const titulo = card.dataset.titulo ?? '';
        const est    = card.dataset.estado ?? '';
        const matchQ = !q || titulo.includes(q);
        const matchE = !estado || est.includes(estado);
        card.style.display = (matchQ && matchE) ? '' : 'none';
    });
}

// ── Modal detalle ─────────────────────────────────────────────────────
const modalDetalle = new bootstrap.Modal(document.getElementById('modalDetalleRenta'));

// Los datos ya están en el DOM; usamos data-attributes para mostrarlos.
// En producción se puede hacer una petición AJAX a un endpoint.
function verDetalleRenta(itemId, tipo) {
    document.getElementById('detalleTitle').textContent = 'Detalle de renta';
    document.getElementById('detalleBody').innerHTML = `
        <p class="text-muted text-center" style="font-size:.88rem;">
            Para ver el detalle completo de esta renta, visita la sección de
            <strong>${tipo === 'arrendador' ? 'Ventas' : 'Compras'}</strong> en tu dashboard.
        </p>
        <div class="d-flex gap-2 justify-content-center mt-3">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <a href="${tipo === 'arrendador' ? '{{ route('dashboard.ventas') }}' : '{{ route('dashboard.compras') }}'}"
               class="btn btn-primary">
                <i class="bi bi-arrow-right me-1"></i>
                Ir a ${tipo === 'arrendador' ? 'Ventas' : 'Compras'}
            </a>
        </div>`;
    modalDetalle.show();
}
</script>
@endpush