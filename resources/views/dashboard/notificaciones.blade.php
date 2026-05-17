{{-- resources/views/dashboard/notificaciones.blade.php --}}
@extends('layouts.dashboard')

@section('titulo_pagina', 'Notificaciones — Tools365')
@section('topbar_title', 'Notificaciones')

@push('css')
<style>
.notif-card {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: .75rem; overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,.04);
}
.notif-item {
    display: flex; gap: 14px; align-items: flex-start;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--bs-border-color);
    transition: background .15s;
    position: relative;
}
.notif-item:last-child { border-bottom: none; }
.notif-item:hover { background: var(--bs-tertiary-bg); }
.notif-item.no-leida { background: rgba(83,74,183,.04); }
.notif-item.no-leida::before {
    content: '';
    position: absolute; left: 0; top: 0; bottom: 0;
    width: 3px;
    background: #534AB7;
    border-radius: 0 3px 3px 0;
}

.notif-icon {
    width: 40px; height: 40px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.notif-green { background: #e6f9f0; color: #1a7f4b; }
.notif-red   { background: #fdecea; color: #c0392b; }
.notif-blue  { background: #e8f4fd; color: #1a6fa8; }

.notif-body { flex: 1; min-width: 0; }
.notif-titulo { font-weight: 700; font-size: .9rem; margin-bottom: .2rem; }
.notif-cuerpo { font-size: .83rem; color: var(--bs-secondary-color); margin: 0; }
.notif-meta   { font-size: .74rem; color: var(--bs-secondary-color); margin-top: .35rem; }

.notif-actions { display: flex; align-items: center; gap: .5rem; flex-shrink: 0; }
.btn-notif-link {
    font-size: .78rem; font-weight: 600;
    color: #534AB7; text-decoration: none;
    padding: .25rem .65rem; border-radius: .375rem;
    background: #ede9ff; transition: background .15s;
}
.btn-notif-link:hover { background: #d9d3ff; color: #534AB7; }

.dot-no-leida {
    width: 8px; height: 8px; border-radius: 50%;
    background: #534AB7; flex-shrink: 0;
}

.empty-notif { text-align:center; padding: 60px 20px; color: var(--bs-secondary-color); }
.empty-notif i { font-size:3rem; display:block; margin-bottom:14px; opacity:.3; }
</style>
@endpush

@section('contenido')

<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div>
        <h1 style="font-size:1.5rem;font-weight:800;margin:0 0 .2rem;">Notificaciones</h1>
        <p style="margin:0;color:var(--bs-secondary-color);font-size:.88rem;">
            Actividad reciente en tu cuenta
        </p>
    </div>
    @if($notificaciones->isNotEmpty())
    <form action="{{ route('notificaciones.leer-todas') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-check2-all me-1"></i>Marcar todas como leídas
        </button>
    </form>
    @endif
</div>

<div class="notif-card">
    @if($notificaciones->isEmpty())
        <div class="empty-notif">
            <i class="bi bi-bell-slash"></i>
            <h5 style="font-weight:700;color:var(--bs-body-color);">Sin notificaciones</h5>
            <p>Cuando haya actividad en tu cuenta aparecerá aquí.</p>
        </div>
    @else
        @foreach($notificaciones as $notif)
        <div class="notif-item {{ !$notif->leida ? 'no-leida' : '' }}">
            <div class="notif-icon {{ $notif->colorClase() }}">
                <i class="bi {{ $notif->icono() }}"></i>
            </div>
            <div class="notif-body">
                <div class="notif-titulo">{{ $notif->titulo }}</div>
                <p class="notif-cuerpo">{{ $notif->cuerpo }}</p>
                <div class="notif-meta">
                    <i class="bi bi-clock me-1"></i>
                    {{ $notif->created_at->diffForHumans() }}
                </div>
            </div>
            <div class="notif-actions">
                @if(!$notif->leida)
                    <div class="dot-no-leida" title="Sin leer"></div>
                @endif
                @if($notif->url)
                    <a href="{{ $notif->url }}" class="btn-notif-link">
                        Ver <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                @endif
            </div>
        </div>
        @endforeach

        @if($notificaciones->hasPages())
            <div style="padding:14px 20px;border-top:1px solid var(--bs-border-color);">
                {{ $notificaciones->links() }}
            </div>
        @endif
    @endif
</div>

@endsection