@extends('layouts.dashboard')

@section('titulo_pagina', 'Mis Subastas – Tools365')
@section('topbar_title', 'Mis Subastas')

@push('css')
<style>
/* ══════════════════════════════════════════
   SUBASTAS INDEX
══════════════════════════════════════════ */
.sub-hero {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
    border-radius: 16px;
    padding: 2rem 2.5rem;
    color: #fff;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
}
.sub-hero-icon {
    font-size: 3rem;
    opacity: .85;
}
.sub-hero h2 { margin: 0; font-size: 1.6rem; font-weight: 800; }
.sub-hero p  { margin: .25rem 0 0; opacity: .75; font-size: .93rem; }

/* Tabs */
.sub-tabs {
    display: flex;
    gap: .5rem;
    margin-bottom: 1.5rem;
    border-bottom: 2px solid var(--color-border, #dee2e6);
    padding-bottom: 0;
}
.sub-tab {
    padding: .6rem 1.4rem;
    border-radius: 8px 8px 0 0;
    border: none;
    background: transparent;
    font-weight: 600;
    font-size: .9rem;
    color: #6c757d;
    cursor: pointer;
    transition: color .15s, background .15s;
    position: relative;
    bottom: -2px;
    border-bottom: 2px solid transparent;
}
.sub-tab.active {
    color: var(--color-accent, #0d6efd);
    border-bottom-color: var(--color-accent, #0d6efd);
    background: rgba(13,110,253,.05);
}
.sub-tab .badge {
    font-size: .7rem;
    vertical-align: middle;
    margin-left: .35rem;
}

/* Cards de subasta */
.sub-card {
    border-radius: 14px;
    border: 1.5px solid var(--color-border, #dee2e6);
    overflow: hidden;
    transition: box-shadow .2s, transform .15s;
    background: var(--color-surface, #fff);
}
.sub-card:hover {
    box-shadow: 0 8px 28px rgba(0,0,0,.10);
    transform: translateY(-2px);
}
.sub-card-img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: #9fa8da;
}
.sub-card-img img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}
.sub-card-body { padding: 1.1rem; }
.sub-card-title {
    font-size: .95rem;
    font-weight: 700;
    line-height: 1.3;
    color: var(--color-text, #212529);
    margin: 0 0 .6rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.sub-precio-row {
    display: flex;
    align-items: baseline;
    gap: .5rem;
    margin-bottom: .5rem;
}
.sub-precio-label { font-size: .72rem; color: #999; font-weight: 600; text-transform: uppercase; }
.sub-precio-val   { font-size: 1.35rem; font-weight: 800; color: var(--color-accent, #0d6efd); }
.sub-precio-mi    { font-size: .85rem; color: #6c757d; }

/* Status pill */
.sub-status {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    font-size: .74rem;
    font-weight: 700;
    padding: .2rem .65rem;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: .04em;
}
.sub-status.activa   { background: #d1fae5; color: #065f46; }
.sub-status.vencida  { background: #fee2e2; color: #991b1b; }
.sub-status.pausada  { background: #fef3c7; color: #92400e; }
.sub-status.vendida  { background: #ede9fe; color: #4c1d95; }

/* Countdown chip */
.countdown-chip {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    font-size: .8rem;
    font-weight: 600;
    color: #856404;
    background: #fff3cd;
    border: 1px solid #ffc107;
    border-radius: 8px;
    padding: .2rem .6rem;
    margin-top: .4rem;
}
.countdown-chip.urgente { background: #fee2e2; border-color: #dc3545; color: #dc3545; }

/* Ganando/Perdiendo badge */
.posicion-badge {
    font-size: .74rem;
    font-weight: 700;
    padding: .2rem .6rem;
    border-radius: 20px;
}
.posicion-ganando { background: #d1fae5; color: #065f46; }
.posicion-perdiendo { background: #fee2e2; color: #991b1b; }

/* Historial pujas modal */
.puja-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: .55rem 0;
    border-bottom: 1px solid var(--color-border, #dee2e6);
    font-size: .88rem;
}
.puja-row:last-child { border-bottom: none; }
.puja-row .nombre { font-weight: 600; }
.puja-row .monto  { font-weight: 800; color: var(--color-accent, #0d6efd); }
.puja-row .fecha  { font-size: .76rem; color: #999; }

/* Empty state */
.sub-empty {
    text-align: center;
    padding: 3.5rem 1rem;
    color: #999;
}
.sub-empty i { font-size: 3.5rem; margin-bottom: 1rem; display: block; }
.sub-empty p { font-size: .95rem; margin: 0; }

/* Dark */
[data-theme="dark"] .sub-card { background: #1e1e2e; border-color: #333; }
[data-theme="dark"] .sub-card-title { color: #f1f1f1; }
[data-theme="dark"] .sub-tab.active { background: rgba(99,179,237,.08); }
</style>
@endpush

@section('contenido')
<div class="container py-4">

    {{-- Hero --}}
    <div class="sub-hero">
        <div class="sub-hero-icon">🔨</div>
        <div>
            <h2>Mis Subastas</h2>
            <p>Gestiona las subastas que publicaste y las que estás siguiendo.</p>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="sub-tabs">
        <button class="sub-tab active" onclick="switchTab('publicadas', this)">
            <i class="bi bi-megaphone me-1"></i>
            Publicadas
            <span class="badge bg-primary">{{ $publicadas->count() }}</span>
        </button>
        <button class="sub-tab" onclick="switchTab('participando', this)">
            <i class="bi bi-hand-index me-1"></i>
            Participando
            <span class="badge bg-secondary">{{ $participando->count() }}</span>
        </button>
    </div>

    {{-- ══ TAB: Publicadas ══ --}}
    <div id="tab-publicadas">
        @if($publicadas->isEmpty())
            <div class="sub-empty">
                <i class="bi bi-megaphone"></i>
                <p>Aún no has publicado ninguna subasta.</p>
                <a href="{{ route('publicar.create') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-plus-lg me-1"></i>Publicar subasta
                </a>
            </div>
        @else
            <div class="row g-3">
                @foreach($publicadas as $p)
                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <div class="sub-card h-100 d-flex flex-column">

                        {{-- Imagen --}}
                        <div class="sub-card-img">
                            @if($p->imagenes->isNotEmpty())
                                <img src="{{ asset($p->imagenes->first()->ruta) }}" alt="{{ $p->titulo }}">
                            @else
                                <i class="bi bi-tools"></i>
                            @endif
                        </div>

                        <div class="sub-card-body flex-grow-1 d-flex flex-column">
                            {{-- Status --}}
                            <div class="mb-2 d-flex gap-1 flex-wrap">
                                @if($p->estado === 'vendido')
                                    <span class="sub-status vendida"><i class="bi bi-check-circle-fill"></i> Vendida</span>
                                @elseif($p->activa)
                                    <span class="sub-status activa"><i class="bi bi-circle-fill" style="font-size:.45rem"></i> Activa</span>
                                @elseif($p->estado === 'pausado')
                                    <span class="sub-status pausada"><i class="bi bi-pause-circle-fill"></i> Cancelada</span>
                                @else
                                    <span class="sub-status vencida"><i class="bi bi-clock"></i> Vencida</span>
                                @endif
                            </div>

                            <p class="sub-card-title">{{ $p->titulo }}</p>

                            {{-- Precio / Puja actual --}}
                            <div>
                                <div class="sub-precio-label">Puja actual</div>
                                <div class="sub-precio-val">${{ number_format($p->puja_actual, 0, '.', ',') }}</div>
                                <div class="sub-precio-mi">
                                    <i class="bi bi-people me-1"></i>{{ $p->pujas_count }} puja(s)
                                </div>
                            </div>

                            {{-- Countdown --}}
                            @if($p->activa && $p->timer_fin)
                                <div class="countdown-chip mt-2"
                                     data-fin="{{ \Carbon\Carbon::parse($p->timer_fin)->toISOString() }}">
                                    <i class="bi bi-alarm"></i>
                                    <span class="countdown-txt">Calculando…</span>
                                </div>
                            @elseif($p->timer_fin)
                                <div class="countdown-chip urgente mt-2">
                                    <i class="bi bi-clock-history"></i>
                                    Tiempo agotado
                                </div>
                            @endif

                            <div class="mt-auto pt-3 d-flex gap-1 flex-column">
                                {{-- Ver historial --}}
                                <button class="btn btn-outline-secondary btn-sm"
                                        onclick="verHistorial({{ $p->id }}, '{{ addslashes($p->titulo) }}')">
                                    <i class="bi bi-list-ol me-1"></i>Ver pujas
                                </button>

                                @if($p->activa)
                                    {{-- Vender al mejor postor --}}
                                    @if($p->pujas_count > 0)
                                        <form method="POST"
                                              action="{{ route('subastas.vender', $p) }}"
                                              onsubmit="return confirm('¿Cerrar la subasta y vender a {{ addslashes($p->puja_top?->user?->name ?? "el mejor postor") }} por ${{ number_format($p->puja_actual, 2) }}?')">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-success btn-sm w-100">
                                                <i class="bi bi-hammer me-1"></i>Vender ahora
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Cancelar --}}
                                    <form method="POST"
                                          action="{{ route('subastas.cancelar', $p) }}"
                                          onsubmit="return confirm('¿Cancelar esta subasta? Se notificará a todos los pujadores.')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm w-100">
                                            <i class="bi bi-x-circle me-1"></i>Cancelar subasta
                                        </button>
                                    </form>
                                @endif

                                {{-- Ver producto --}}
                                @if($p->estado === 'activo')
                                <a href="{{ route('productos.show', $p) }}"
                                   class="btn btn-link btn-sm text-start p-0 mt-1"
                                   target="_blank">
                                    <i class="bi bi-box-arrow-up-right me-1"></i>Ver publicación
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ══ TAB: Participando ══ --}}
    <div id="tab-participando" style="display:none;">
        @if($participando->isEmpty())
            <div class="sub-empty">
                <i class="bi bi-hand-index"></i>
                <p>Aún no participas en ninguna subasta.</p>
                <a href="{{ route('inicio') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-search me-1"></i>Explorar subastas
                </a>
            </div>
        @else
            <div class="row g-3">
                @foreach($participando as $p)
                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <div class="sub-card h-100 d-flex flex-column">

                        <div class="sub-card-img">
                            @if($p->imagenes->isNotEmpty())
                                <img src="{{ asset($p->imagenes->first()->ruta) }}" alt="{{ $p->titulo }}">
                            @else
                                <i class="bi bi-tools"></i>
                            @endif
                        </div>

                        <div class="sub-card-body flex-grow-1 d-flex flex-column">

                            <div class="mb-2 d-flex gap-1 flex-wrap align-items-center">
                                @if($p->estado === 'vendido')
                                    <span class="sub-status vendida"><i class="bi bi-check-circle-fill"></i>
                                        {{ $p->voy_ganando ? '¡Ganaste!' : 'Finalizada' }}
                                    </span>
                                @elseif($p->activa)
                                    <span class="sub-status activa"><i class="bi bi-circle-fill" style="font-size:.45rem"></i> Activa</span>
                                @else
                                    <span class="sub-status vencida"><i class="bi bi-clock"></i> Vencida</span>
                                @endif

                                @if($p->activa)
                                    <span class="posicion-badge {{ $p->voy_ganando ? 'posicion-ganando' : 'posicion-perdiendo' }}">
                                        {{ $p->voy_ganando ? '🏆 Ganando' : '⚠️ Superado' }}
                                    </span>
                                @endif
                            </div>

                            <p class="sub-card-title">{{ $p->titulo }}</p>

                            <div>
                                <div class="sub-precio-label">Puja actual</div>
                                <div class="sub-precio-val">${{ number_format($p->puja_actual, 0, '.', ',') }}</div>
                                <div class="sub-precio-mi">
                                    Mi mejor puja: <strong>${{ number_format($p->mi_mejor_puja, 0, '.', ',') }}</strong>
                                </div>
                            </div>

                            @if($p->activa && $p->timer_fin)
                                <div class="countdown-chip mt-2"
                                     data-fin="{{ \Carbon\Carbon::parse($p->timer_fin)->toISOString() }}">
                                    <i class="bi bi-alarm"></i>
                                    <span class="countdown-txt">Calculando…</span>
                                </div>
                            @elseif($p->timer_fin && !$p->activa)
                                <div class="countdown-chip urgente mt-2">
                                    <i class="bi bi-clock-history"></i> Tiempo agotado
                                </div>
                            @endif

{{-- REEMPLAZA este bloque en tab-participando --}}
<div class="mt-auto pt-3 d-flex gap-1 flex-column">
    <button class="btn btn-outline-secondary btn-sm"
            onclick="verHistorial({{ $p->id }}, '{{ addslashes($p->titulo) }}')">
        <i class="bi bi-list-ol me-1"></i>Ver pujas
    </button>

    {{-- ✅ BOTÓN PAGAR — aparece cuando ganaste y tienes pedido pendiente --}}
    @if($p->pedido_pendiente)
        @php
            $limite = \Carbon\Carbon::parse($p->pedido_pendiente->pago_limite);
            $segundos = max(0, now()->diffInSeconds($limite, false));
            $horas = floor($segundos / 3600);
            $mins  = floor(($segundos % 3600) / 60);
        @endphp
        <a href="{{ route('subastas.pagar', $p->pedido_pendiente) }}"
           class="btn btn-success btn-sm w-100">
            <i class="bi bi-credit-card-fill me-1"></i>
            Pagar ahora
        </a>
        <div class="text-center" style="font-size:.72rem; color:#dc3545; font-weight:600;">
            <i class="bi bi-clock me-1"></i>
            Tiempo restante: {{ $horas }}h {{ $mins }}m
        </div>

    {{-- Si ya ganó pero aún activa (antes de que el vendedor adjudique) --}}
    @elseif($p->activa && !$p->voy_ganando)
        <a href="{{ route('productos.show', $p) }}"
           class="btn btn-warning btn-sm">
            <i class="bi bi-hammer me-1"></i>Hacer nueva puja
        </a>
    @elseif($p->activa && $p->voy_ganando)
        <a href="{{ route('productos.show', $p) }}"
           class="btn btn-outline-success btn-sm">
            <i class="bi bi-eye me-1"></i>Ver subasta
        </a>
    @endif
</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

{{-- ══ Modal historial de pujas ══ --}}
<div class="modal fade" id="modalHistorial" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="historialTitulo">Historial de pujas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="historialBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ── Tabs ──────────────────────────────────────────────
function switchTab(tab, btn) {
    document.getElementById('tab-publicadas').style.display   = 'none';
    document.getElementById('tab-participando').style.display = 'none';
    document.getElementById('tab-' + tab).style.display       = '';

    document.querySelectorAll('.sub-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

// ── Countdown ─────────────────────────────────────────
function updateCountdowns() {
    document.querySelectorAll('.countdown-chip[data-fin]').forEach(chip => {
        const fin  = new Date(chip.dataset.fin);
        const diff = fin - Date.now();
        const txt  = chip.querySelector('.countdown-txt');
        if (!txt) return;

        if (diff <= 0) {
            txt.textContent = 'Tiempo agotado';
            chip.classList.add('urgente');
            return;
        }

        const d = Math.floor(diff / 86400000);
        const h = Math.floor((diff % 86400000) / 3600000);
        const m = Math.floor((diff % 3600000) / 60000);
        const s = Math.floor((diff % 60000) / 1000);

        if (diff < 3600000) chip.classList.add('urgente');

        txt.textContent = d > 0
            ? `${d}d ${h}h ${m}m`
            : `${h}h ${m}m ${s}s`;
    });
}
updateCountdowns();
setInterval(updateCountdowns, 1000);

// ── Modal historial ───────────────────────────────────
const modalHistorial = new bootstrap.Modal(document.getElementById('modalHistorial'));

async function verHistorial(productoId, titulo) {
    document.getElementById('historialTitulo').textContent = titulo;
    document.getElementById('historialBody').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary"></div>
        </div>`;
    modalHistorial.show();

    try {
        const res  = await fetch(`/subastas/${productoId}/historial`);
        const data = await res.json();

        if (!data.pujas.length) {
            document.getElementById('historialBody').innerHTML = `
                <p class="text-center text-muted py-3">Aún no hay pujas en esta subasta.</p>`;
            return;
        }

        let html = `
            <div class="mb-3 d-flex gap-3 text-center">
                <div class="flex-fill border rounded-3 p-2">
                    <div style="font-size:.75rem;color:#999;font-weight:600;text-transform:uppercase;">Puja actual</div>
                    <div style="font-size:1.3rem;font-weight:800;color:#0d6efd;">
                        $${Number(data.puja_actual).toLocaleString('es-MX')}
                    </div>
                </div>
                <div class="flex-fill border rounded-3 p-2">
                    <div style="font-size:.75rem;color:#999;font-weight:600;text-transform:uppercase;">Total pujas</div>
                    <div style="font-size:1.3rem;font-weight:800;">${data.total_pujas}</div>
                </div>
            </div>`;

        data.pujas.forEach((p, i) => {
            html += `
                <div class="puja-row ${i === 0 ? 'fw-bold' : ''}">
                    <div>
                        ${i === 0 ? '🏆 ' : ''}<span class="nombre">${p.nombre}</span>
                        <div class="fecha">${p.fecha}</div>
                    </div>
                    <div class="monto">$${Number(p.monto).toLocaleString('es-MX')}</div>
                </div>`;
        });

        document.getElementById('historialBody').innerHTML = html;
    } catch (e) {
        document.getElementById('historialBody').innerHTML =
            `<p class="text-danger text-center">Error al cargar el historial.</p>`;
    }
}
</script>
@endpush