@extends('layouts.app')

@section('titulo_pagina', 'Planes y Precios — Tools365')

@push('css')
<link rel="stylesheet" href="{{ asset('css/planes.css') }}">
@endpush

@section('contenido')

{{-- Hero ────────────────────────────────────────────── --}}
<div class="planes-hero">
    <div class="planes-hero-badge">
        <i class="bi bi-stars"></i> Planes y precios
    </div>
    <h1>Elige el plan<br><span>perfecto para ti</span></h1>
    <p>Desde publicaciones gratuitas hasta herramientas profesionales de alto volumen.</p>

    {{-- Toggle mensual / anual --}}
    <div class="billing-toggle" id="billing-toggle">
        <span class="active" data-period="mensual">Mensual</span>
        <span data-period="anual">Anual <span class="ahorro-pill">−20%</span></span>
    </div>
</div>

<div class="planes-page">
    <div class="container">

        {{-- ── GRID DE PLANES ──────────────────────────────────────────── --}}
        <div class="plans-grid mb-5">

            {{-- FREE --}}
            <div class="plan-box">
                <div class="plan-icon" style="background:#f0fdf4; color:#22c55e;">🌱</div>
                <div class="plan-nombre">Free</div>
                <div class="plan-descripcion">Ideal para comenzar sin costo</div>
                <div class="plan-precio-wrap">
                    <span class="plan-moneda">$</span>
                    <span class="plan-precio precio-display" data-mensual="0" data-anual="0">0</span>
                </div>
                <div class="plan-periodo">MXN / mes</div>
                <div class="plan-precio-anual" id="ahorro-free"></div>
                <hr class="plan-divider">
                <ul class="plan-features">
                    <li><i class="bi bi-check-circle-fill check"></i> Hasta 5 publicaciones</li>
                    <li><i class="bi bi-check-circle-fill check"></i> 3 fotos por publicación</li>
                    <li><i class="bi bi-check-circle-fill check"></i> Soporte por email (48 h)</li>
                    <li><i class="bi bi-check-circle-fill check"></i> Comisión venta: 12%</li>
                    <li><i class="bi bi-check-circle-fill check"></i> Comisión renta: 15%</li>
                </ul>
                @auth
                    @if(auth()->user()->plan === null || auth()->user()->plan === 'free')
                        <button class="btn-plan btn-plan-outline" disabled>Tu plan actual</button>
                    @else
                        <button class="btn-plan btn-plan-outline" disabled>Plan menor</button>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="btn-plan btn-plan-outline">
                        Empezar gratis
                    </a>
                @endauth
            </div>

            {{-- BÁSICO --}}
            <div class="plan-box featured">
                <div class="plan-popular-badge">⚡ Más popular</div>
                <div class="plan-icon" style="background:#ede9fe; color:#7c3aed;">🚀</div>
                <div class="plan-nombre">Básico</div>
                <div class="plan-descripcion">Para vendedores y arrendadores activos</div>
                <div class="plan-precio-wrap">
                    <span class="plan-moneda">$</span>
                    <span class="plan-precio featured-price precio-display" data-mensual="199" data-anual="159">199</span>
                </div>
                <div class="plan-periodo">MXN / mes</div>
                <div class="plan-precio-anual" id="ahorro-basico"></div>
                <hr class="plan-divider">
                <ul class="plan-features">
                    <li><i class="bi bi-check-circle-fill check"></i> Hasta 20 publicaciones</li>
                    <li><i class="bi bi-check-circle-fill check"></i> 6 fotos por publicación</li>
                    <li><i class="bi bi-check-circle-fill check"></i> Soporte por email (24 h)</li>
                    <li><i class="bi bi-check-circle-fill check"></i> Comisión venta: 10%</li>
                    <li><i class="bi bi-check-circle-fill check"></i> Comisión renta: 12%</li>
                </ul>
                @auth
                    @if(auth()->user()->plan === 'basico')
                        <button class="btn-plan btn-plan-primary" disabled>Tu plan actual</button>
                    @elseif(auth()->user()->plan === 'profesional')
                        <button class="btn-plan btn-plan-primary" disabled>Plan menor</button>
                    @else
                        <a href="{{ route('dashboard.plan.checkout', 'basico') }}" class="btn-plan btn-plan-primary">
                            Elegir Básico
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="btn-plan btn-plan-primary">
                        Elegir Básico
                    </a>
                @endauth
            </div>

            {{-- PROFESIONAL --}}
            <div class="plan-box">
                <div class="plan-icon" style="background:#fef3c7; color:#d97706;">💎</div>
                <div class="plan-nombre">Profesional</div>
                <div class="plan-descripcion">Para empresas y alto volumen</div>
                <div class="plan-precio-wrap">
                    <span class="plan-moneda">$</span>
                    <span class="plan-precio precio-display" data-mensual="599" data-anual="479">599</span>
                </div>
                <div class="plan-periodo">MXN / mes</div>
                <div class="plan-precio-anual" id="ahorro-pro"></div>
                <hr class="plan-divider">
                <ul class="plan-features">
                    <li><i class="bi bi-check-circle-fill check"></i> Publicaciones <strong>ilimitadas</strong></li>
                    <li><i class="bi bi-check-circle-fill check"></i> 10 fotos por publicación</li>
                    <li><i class="bi bi-check-circle-fill check"></i> 10 publicaciones destacadas / mes</li>
                    <li><i class="bi bi-check-circle-fill check"></i> Soporte prioritario 24/7</li>
                    <li><i class="bi bi-check-circle-fill check"></i> Comisiones desde 8%</li>
                </ul>
                @auth
                    @if(auth()->user()->plan === 'profesional')
                        <button class="btn-plan btn-plan-outline" disabled>Tu plan actual</button>
                    @else
                        <a href="{{ route('dashboard.plan.checkout', 'profesional') }}" class="btn-plan btn-plan-outline">
                            Elegir Profesional
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="btn-plan btn-plan-outline">
                        Elegir Profesional
                    </a>
                @endauth
            </div>
        </div>

        {{-- ── TABLA COMPARATIVA ────────────────────────────────────────── --}}
        <div class="mb-5">
            <h2 class="fw-bold text-center mb-4">Comparativa detallada</h2>
            <div class="compare-table">
                <table>
                    <thead>
                        <tr>
                            <th>Característica</th>
                            <th>Free</th>
                            <th class="highlight">Básico</th>
                            <th>Profesional</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $rows = [
                            ['Publicaciones',           '5',        '20',       'Ilimitadas'],
                            ['Fotos por publicación',   '3',        '6',        '10'],
                            ['Videos',                  '—',        '1',        '3'],
                            ['Publicaciones destacadas','—',        '2 / mes',  '10 / mes'],
                            ['Insignia verificado',     false,      true,       true],
                            ['Insignia PRO',            false,      false,      true],
                            ['Estadísticas avanzadas',  false,      false,      true],
                            ['API de integración',      false,      false,      true],
                            ['Soporte prioritario',     false,      false,      true],
                            ['Comisión venta',          '12%',      '10%',      '8%'],
                            ['Comisión renta',          '15%',      '12%',      'Desde 8%'],
                        ];
                        @endphp
                        @foreach($rows as $row)
                        <tr>
                            <td>{{ $row[0] }}</td>
                            @for($i = 1; $i <= 3; $i++)
                                <td @if($i === 2) class="highlight" @endif>
                                    @if($row[$i] === true)
                                        <i class="bi bi-check-circle-fill check-icon"></i>
                                    @elseif($row[$i] === false)
                                        <i class="bi bi-x-circle cross-icon"></i>
                                    @else
                                        {{ $row[$i] }}
                                    @endif
                                </td>
                            @endfor
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── FAQ ──────────────────────────────────────────────────────── --}}
        <div class="faq-section mt-5">
            <h2 class="fw-bold">Preguntas frecuentes</h2>

            @php
            $faqs = [
                ['¿Puedo cambiar de plan en cualquier momento?',
                 'Sí. Puedes actualizar o degradar tu plan desde tu panel de usuario en cualquier momento. Los cambios aplican al siguiente ciclo de facturación.'],
                ['¿Qué métodos de pago aceptan?',
                 'Aceptamos tarjetas de crédito y débito (Visa, Mastercard, American Express), OXXO Pay y transferencia bancaria (SPEI).'],
                ['¿Hay periodo de prueba?',
                 'El plan Free es permanentemente gratuito. Para los planes de pago, ofrecemos 7 días de prueba gratuita sin necesidad de tarjeta.'],
                ['¿Cómo se calculan las comisiones?',
                 'La comisión se aplica únicamente sobre transacciones completadas dentro de la plataforma. No se cobra por publicar ni por recibir contactos.'],
                ['¿Qué es una publicación destacada?',
                 'Las publicaciones destacadas aparecen en los primeros resultados de búsqueda y en las secciones "Destacados" de la página de inicio, generando hasta 8× más visitas.'],
            ];
            @endphp

            @foreach($faqs as $i => $faq)
            <div class="faq-item" data-idx="{{ $i }}">
                <div class="faq-question">
                    {{ $faq[0] }}
                    <i class="bi bi-chevron-down"></i>
                </div>
                <div class="faq-answer">{{ $faq[1] }}</div>
            </div>
            @endforeach
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // ── Toggle anual / mensual ────────────────────────────────────────────
    const toggle  = document.getElementById('billing-toggle');
    const btns    = toggle.querySelectorAll('span[data-period]');
    const precios = document.querySelectorAll('.precio-display');
    const ids     = {
        'ahorro-free':   { mensual: 0,   anual: 0   },
        'ahorro-basico': { mensual: 199, anual: 159 },
        'ahorro-pro':    { mensual: 599, anual: 479 },
    };

    function setPeriod(period) {
        btns.forEach(b => b.classList.toggle('active', b.dataset.period === period));
        precios.forEach(el => {
            const val = parseInt(el.dataset[period]);
            el.textContent = val.toLocaleString('es-MX');
        });
        Object.keys(ids).forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            if (period === 'anual' && ids[id].anual > 0) {
                const ahorro = (ids[id].mensual - ids[id].anual) * 12;
                el.textContent = `Facturado anual · Ahorras $${ahorro.toLocaleString('es-MX')} al año`;
            } else {
                el.textContent = '';
            }
        });
    }

    btns.forEach(b => b.addEventListener('click', () => setPeriod(b.dataset.period)));

    // ── FAQ accordion ─────────────────────────────────────────────────────
    document.querySelectorAll('.faq-question').forEach(q => {
        q.addEventListener('click', () => {
            const item = q.closest('.faq-item');
            const wasOpen = item.classList.contains('open');
            document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
            if (!wasOpen) item.classList.add('open');
        });
    });
});
</script>
@endpush