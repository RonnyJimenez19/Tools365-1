@extends('layouts.dashboard')

@section('titulo_pagina', 'Mis Subastas – Tools365')
@section('topbar_title', 'Mis Subastas')

@push('css')
<link rel="stylesheet" href="{{ asset('css/index_subasta.css') }}">
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