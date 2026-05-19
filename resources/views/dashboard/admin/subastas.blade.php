{{-- resources/views/dashboard/admin/subastas.blade.php --}}
@extends('layouts.dashboard')

@section('titulo_pagina', 'Subastas – Tools365')
@section('topbar_title', 'Subastas')
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
.btn-filter-clear:hover { background:var(--bs-secondary-bg); }

.subasta-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(360px, 1fr)); gap:1rem; padding:1.25rem; }

.subasta-card { background:var(--bs-body-bg); border:1px solid var(--bs-border-color); border-radius:.875rem; overflow:hidden; transition:box-shadow .15s; }
.subasta-card:hover { box-shadow:0 4px 20px rgba(0,0,0,.08); }

.subasta-card-img { position:relative; height:160px; background:var(--bs-tertiary-bg); overflow:hidden; }
.subasta-card-img img { width:100%; height:100%; object-fit:cover; }
.subasta-card-img .no-img { display:flex; align-items:center; justify-content:center; height:100%; color:var(--bs-secondary-color); font-size:2rem; }

.subasta-badge-estado { position:absolute; top:.6rem; left:.6rem; font-size:.72rem; font-weight:700; padding:.3em .7em; border-radius:999px; }
.sb-activo     { background:#22c55e; color:#fff; }
.sb-adjudicado { background:#3b82f6; color:#fff; }
.sb-adj-exp    { background:#f97316; color:#fff; }
.sb-pausado    { background:rgba(0,0,0,.55); color:#fff; }
.sb-vendido    { background:#6366f1; color:#fff; }

.countdown-badge { position:absolute; top:.6rem; right:.6rem; background:rgba(0,0,0,.7); color:#fff; font-size:.72rem; font-weight:700; padding:.3em .65em; border-radius:.4rem; display:flex; align-items:center; gap:.3rem; }

.subasta-card-body { padding:1rem; }
.subasta-title { font-weight:700; font-size:.9rem; margin-bottom:.5rem; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
.subasta-meta  { font-size:.75rem; color:var(--bs-secondary-color); display:flex; flex-wrap:wrap; gap:.5rem; margin-bottom:.75rem; }
.subasta-meta span { display:flex; align-items:center; gap:.25rem; }

.puja-row { display:flex; align-items:center; justify-content:space-between; background:var(--bs-tertiary-bg); border-radius:.5rem; padding:.55rem .75rem; margin-bottom:.75rem; }
.puja-label { font-size:.72rem; color:var(--bs-secondary-color); font-weight:600; }
.puja-amount { font-size:1.05rem; font-weight:800; color:#534AB7; }
.puja-count  { font-size:.72rem; color:var(--bs-secondary-color); }

.pujador-top { display:flex; align-items:center; gap:.4rem; font-size:.78rem; }
.pujador-avatar { width:24px; height:24px; border-radius:50%; background:#ede9ff; color:#534AB7; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:.65rem; flex-shrink:0; }

.subasta-card-footer { display:flex; gap:.4rem; flex-wrap:wrap; padding:.75rem 1rem; border-top:1px solid var(--bs-border-color); }
.btn-sc { border:none; border-radius:.4rem; font-size:.78rem; font-weight:600; padding:.38rem .8rem; cursor:pointer; transition:all .15s; display:inline-flex; align-items:center; gap:.3rem; }
.btn-sc-view   { background:#e8f4fd; color:#1a6fa8; }
.btn-sc-view:hover { background:#bee3f8; }
.btn-sc-cancel { background:#fdecea; color:#c0392b; margin-left:auto; }
.btn-sc-cancel:hover { background:#fbc4c0; }

.dash-card { background:var(--bs-body-bg); border:1px solid var(--bs-border-color); border-radius:.75rem; overflow:hidden; }
.card-header-row { display:flex; align-items:center; justify-content:space-between; padding:1rem 1.25rem; border-bottom:1px solid var(--bs-border-color); }
.card-header-row h3 { font-size:.95rem; font-weight:700; margin:0; }
.results-count { font-size:.8rem; color:var(--bs-secondary-color); }

.empty-state { text-align:center; padding:3rem 1rem; color:var(--bs-secondary-color); }
.empty-state i { font-size:2.5rem; margin-bottom:.75rem; display:block; }

.pagination-wrap { padding:.875rem 1.25rem; border-top:1px solid var(--bs-border-color); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.5rem; }
.pagination-info { font-size:.8rem; color:var(--bs-secondary-color); }
</style>
@endpush

@section('contenido')

<div class="dash-page-header">
    <div>
        <h1><i class="bi bi-hammer me-2" style="color:#534AB7;"></i>Subastas</h1>
        <p>Monitorea todas las subastas activas y adjudicadas de la plataforma.</p>
    </div>
    <span class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill"
          style="background:#ede9ff;color:#534AB7;font-size:.78rem;font-weight:700;">
        <i class="bi bi-shield-fill"></i> Administrador
    </span>
</div>

{{-- Mini stats --}}
<div class="mini-stats">
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#e8f4fd;color:#1a6fa8;"><i class="bi bi-hammer"></i></div>
        <div><div class="mini-stat-num">{{ $stats['total'] }}</div><div class="mini-stat-lbl">Total</div></div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#e6f9f0;color:#1a7f4b;"><i class="bi bi-activity"></i></div>
        <div><div class="mini-stat-num">{{ $stats['activas'] }}</div><div class="mini-stat-lbl">Activas</div></div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#e8f4fd;color:#3b82f6;"><i class="bi bi-trophy-fill"></i></div>
        <div><div class="mini-stat-num">{{ $stats['adjudicadas'] }}</div><div class="mini-stat-lbl">Adjudicadas</div></div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#ede9ff;color:#534AB7;"><i class="bi bi-bag-check-fill"></i></div>
        <div><div class="mini-stat-num">{{ $stats['vendidas'] }}</div><div class="mini-stat-lbl">Vendidas</div></div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#fdecea;color:#c0392b;"><i class="bi bi-slash-circle-fill"></i></div>
        <div><div class="mini-stat-num">{{ $stats['canceladas'] }}</div><div class="mini-stat-lbl">Canceladas</div></div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:#fff3e0;color:#e65c00;"><i class="bi bi-currency-dollar"></i></div>
        <div><div class="mini-stat-num">{{ $stats['total_pujas'] }}</div><div class="mini-stat-lbl">Total pujas</div></div>
    </div>
</div>

{{-- Filtros --}}
<form method="GET" action="{{ route('admin.subastas') }}" class="filter-bar">
    <div class="filter-group" style="max-width:260px;">
        <label>Buscar</label>
        <input type="text" name="buscar" class="form-control" placeholder="Título de subasta..." value="{{ request('buscar') }}">
    </div>
    <div class="filter-group" style="max-width:180px;">
        <label>Estado</label>
        <select name="estado" class="form-select">
            <option value="">Activas + Adjudicadas</option>
            <option value="activo"             {{ request('estado') === 'activo'             ? 'selected' : '' }}>Activo</option>
            <option value="adjudicado"         {{ request('estado') === 'adjudicado'         ? 'selected' : '' }}>Adjudicado</option>
            <option value="adjudicado_expirado"{{ request('estado') === 'adjudicado_expirado'? 'selected' : '' }}>Expirado (sin pago)</option>
            <option value="vendido"            {{ request('estado') === 'vendido'            ? 'selected' : '' }}>Vendido</option>
            <option value="pausado"            {{ request('estado') === 'pausado'            ? 'selected' : '' }}>Cancelado</option>
        </select>
    </div>
    <div class="d-flex gap-2 align-items-end">
        <button type="submit" class="btn-filter btn-filter-apply"><i class="bi bi-search me-1"></i>Filtrar</button>
        @if(request()->hasAny(['buscar','estado']))
            <a href="{{ route('admin.subastas') }}" class="btn-filter btn-filter-clear">Limpiar</a>
        @endif
    </div>
</form>

{{-- Grid de subastas --}}
<div class="dash-card">
    <div class="card-header-row">
        <h3><i class="bi bi-hammer me-2" style="color:#534AB7;"></i>Subastas</h3>
        <span class="results-count">{{ $subastas->total() }} subastas</span>
    </div>

    @if($subastas->isEmpty())
        <div class="empty-state">
            <i class="bi bi-hammer"></i>
            <p>No se encontraron subastas con los filtros aplicados.</p>
        </div>
    @else
        <div class="subasta-grid">
            @foreach($subastas as $s)
            <div class="subasta-card">
                {{-- Imagen --}}
                <div class="subasta-card-img">
                    @if($s->imagenes->first())
                        <img src="{{ asset($s->imagenes->first()->ruta) }}" alt="">
                    @else
                        <div class="no-img"><i class="bi bi-image"></i></div>
                    @endif

                    {{-- Badge estado --}}
                    @php
                        $sbClass = match($s->estado) {
                            'activo'             => 'sb-activo',
                            'adjudicado'         => 'sb-adjudicado',
                            'adjudicado_expirado'=> 'sb-adj-exp',
                            'pausado'            => 'sb-pausado',
                            'vendido'            => 'sb-vendido',
                            default              => 'sb-pausado',
                        };
                        $sbLabel = match($s->estado) {
                            'activo'             => '● Activa',
                            'adjudicado'         => '⚡ Adjudicada',
                            'adjudicado_expirado'=> '⚠ Exp. sin pago',
                            'pausado'            => '✕ Cancelada',
                            'vendido'            => '✓ Vendida',
                            default              => $s->estado,
                        };
                    @endphp
                    <span class="subasta-badge-estado {{ $sbClass }}">{{ $sbLabel }}</span>

                    {{-- Countdown si está activa --}}
                    @if($s->esta_activa && $s->timer_fin)
                        <span class="countdown-badge" data-fin="{{ \Carbon\Carbon::parse($s->timer_fin)->timestamp }}">
                            <i class="bi bi-clock"></i> <span class="cd-text">--:--:--</span>
                        </span>
                    @endif
                </div>

                <div class="subasta-card-body">
                    <div class="subasta-title" title="{{ $s->titulo }}">{{ $s->titulo }}</div>
                    <div class="subasta-meta">
                        <span><i class="bi bi-geo-alt-fill"></i> {{ $s->ubicacion ?? '—' }}</span>
                        <span><i class="bi bi-person-fill"></i> {{ $s->user?->name ?? '—' }}</span>
                        <span><i class="bi bi-calendar3"></i> {{ $s->created_at->format('d/m/Y') }}</span>
                    </div>

                    {{-- Puja actual --}}
                    <div class="puja-row">
                        <div>
                            <div class="puja-label">Puja actual</div>
                            <div class="puja-amount">${{ number_format($s->puja_actual, 2) }}</div>
                        </div>
                        <div class="text-end">
                            <div class="puja-count">{{ $s->pujas_count }} puja{{ $s->pujas_count !== 1 ? 's' : '' }}</div>
                            @if($s->puja_top)
                                <div class="pujador-top">
                                    <div class="pujador-avatar">{{ strtoupper(substr($s->puja_top->user?->name ?? '?', 0, 2)) }}</div>
                                    <span>{{ $s->puja_top->user?->name ?? 'Anónimo' }}</span>
                                </div>
                            @else
                                <span style="font-size:.72rem;color:var(--bs-secondary-color);">Sin pujas</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="subasta-card-footer">
                    <a href="{{ route('productos.show', $s) }}" class="btn-sc btn-sc-view" target="_blank">
                        <i class="bi bi-eye-fill"></i> Ver
                    </a>

                    @if(in_array($s->estado, ['activo', 'adjudicado', 'adjudicado_expirado']))
                        <form method="POST" action="{{ route('admin.subastas.cancelar', $s) }}"
                              onsubmit="return confirm('¿Cancelar la subasta \"{{ addslashes($s->titulo) }}\"? Los participantes serán notificados.')">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-sc btn-sc-cancel">
                                <i class="bi bi-slash-circle-fill"></i> Cancelar subasta
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif

    @if($subastas->hasPages())
        <div class="pagination-wrap">
            <span class="pagination-info">Página {{ $subastas->currentPage() }} de {{ $subastas->lastPage() }}</span>
            {{ $subastas->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
// Countdown para subastas activas
function formatTime(secs) {
    if (secs <= 0) return '00:00:00';
    const h = Math.floor(secs / 3600);
    const m = Math.floor((secs % 3600) / 60);
    const s = secs % 60;
    return [h, m, s].map(v => String(v).padStart(2, '0')).join(':');
}

function updateCountdowns() {
    document.querySelectorAll('.countdown-badge[data-fin]').forEach(el => {
        const fin = parseInt(el.dataset.fin, 10);
        const now = Math.floor(Date.now() / 1000);
        const diff = fin - now;
        const cdText = el.querySelector('.cd-text');
        if (cdText) cdText.textContent = formatTime(diff);
        if (diff <= 0) el.style.background = 'rgba(239,68,68,.8)';
    });
}

updateCountdowns();
setInterval(updateCountdowns, 1000);
</script>
@endpush