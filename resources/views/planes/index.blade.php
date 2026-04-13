@extends('layouts.app')

@section('titulo_pagina', 'Planes y Precios – Tools365')

@push('css')
<style>
/* ── Page ───────────────────────────────────────── */
.planes-page {
    padding-top: 140px;
    padding-bottom: 100px;
    overflow: hidden;
}

/* ── Hero ───────────────────────────────────────── */
.planes-hero {
    text-align: center;
    padding: 80px 20px 60px;
    margin-top: -140px;
    padding-top: 200px;
    background: linear-gradient(180deg, #0f172a 0%, #1e293b 70%, var(--color-bg, #f8fafc) 100%);
    position: relative;
    overflow: hidden;
}
.planes-hero::before {
    content: '';
    position: absolute;
    width: 700px; height: 700px;
    background: radial-gradient(circle, rgba(99,102,241,.15) 0%, transparent 70%);
    top: 0; left: 50%; transform: translateX(-50%);
    pointer-events: none;
}
.planes-hero-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(99,102,241,.2); border: 1px solid rgba(99,102,241,.4);
    color: #818cf8; padding: 5px 16px; border-radius: 100px;
    font-size: .75rem; font-weight: 700; letter-spacing: .06em;
    text-transform: uppercase; margin-bottom: 20px;
}
.planes-hero h1 {
    color: #fff; font-size: clamp(2rem, 5vw, 3.2rem);
    font-weight: 900; margin-bottom: 14px; position: relative; z-index: 1;
}
.planes-hero h1 span {
    background: linear-gradient(135deg, #818cf8, #c084fc);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
}
.planes-hero p { color: rgba(255,255,255,.6); max-width: 500px; margin: 0 auto 30px; position: relative; z-index: 1; }

/* ── Toggle anual/mensual ───────────────────────── */
.billing-toggle {
    display: inline-flex; align-items: center; gap: 10px;
    background: rgba(255,255,255,.05);
    border: 1px solid rgba(255,255,255,.12);
    padding: 6px; border-radius: 100px;
    position: relative; z-index: 1;
}
.billing-toggle span {
    font-size: .82rem; font-weight: 600; color: rgba(255,255,255,.5);
    padding: 6px 14px; border-radius: 100px; cursor: pointer;
    transition: all .2s;
}
.billing-toggle span.active {
    background: rgba(255,255,255,.12);
    color: #fff;
}
.ahorro-pill {
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: #fff; font-size: .68rem; font-weight: 800;
    padding: 2px 8px; border-radius: 100px;
    vertical-align: middle; margin-left: 4px;
}

/* ── Plans grid ─────────────────────────────────── */
.plans-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    align-items: start;
    max-width: 1100px;
    margin: 0 auto;
}

/* ── Plan card ──────────────────────────────────── */
.plan-box {
    background: var(--color-surface, #fff);
    border: 1.5px solid var(--color-border, #e2e8f0);
    border-radius: 20px;
    padding: 32px 28px;
    position: relative;
    transition: transform .25s, box-shadow .25s;
}
.plan-box:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 40px rgba(0,0,0,.1);
}
.plan-box.featured {
    border-color: #6366f1;
    background: linear-gradient(180deg, #fafafe 0%, #fff 100%);
    transform: scale(1.04);
    box-shadow: 0 20px 60px rgba(99,102,241,.2);
    z-index: 2;
}
.plan-box.featured:hover { transform: scale(1.04) translateY(-4px); }

[data-theme="dark"] .plan-box { background: #1e293b; border-color: #334155; }
[data-theme="dark"] .plan-box.featured { background: #1e1b4b; }

/* Badge popular */
.plan-popular-badge {
    position: absolute; top: -14px; left: 50%; transform: translateX(-50%);
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: #fff; font-weight: 800; font-size: .7rem;
    padding: 5px 18px; border-radius: 100px;
    letter-spacing: .06em; text-transform: uppercase;
    white-space: nowrap;
    box-shadow: 0 4px 14px rgba(99,102,241,.4);
}

/* Plan header */
.plan-icon {
    width: 48px; height: 48px;
    border-radius: 14px;
    display: grid; place-items: center;
    font-size: 1.4rem;
    margin-bottom: 16px;
}
.plan-nombre {
    font-size: .7rem; font-weight: 800; letter-spacing: .12em;
    text-transform: uppercase; color: #64748b; margin-bottom: 4px;
}
.plan-descripcion { font-size: .84rem; color: #64748b; margin-bottom: 20px; }

/* Precio */
.plan-precio-wrap { display: flex; align-items: baseline; gap: 4px; margin-bottom: 4px; }
.plan-moneda { font-size: 1.2rem; font-weight: 700; color: #374151; }
.plan-precio {
    font-size: 3rem; font-weight: 900; line-height: 1;
    color: #0f172a;
}
[data-theme="dark"] .plan-precio { color: #f1f5f9; }
.plan-precio.featured-price { color: #6366f1; }
.plan-periodo { font-size: .82rem; color: #94a3b8; margin-bottom: 6px; }
.plan-precio-anual { font-size: .75rem; color: #22c55e; font-weight: 600; min-height: 18px; }

/* Divider */
.plan-divider {
    border: none; border-top: 1.5px solid var(--color-border, #f1f5f9);
    margin: 20px 0;
}

/* Features */
.plan-features { list-style: none; padding: 0; margin-bottom: 24px; }
.plan-features li {
    display: flex; align-items: flex-start; gap: 10px;
    font-size: .84rem; margin-bottom: 10px;
    color: var(--color-text, #374151);
}
.plan-features li i { flex-shrink: 0; margin-top: 1px; }
.plan-features .check { color: #22c55e; }
.plan-features .cross { color: #cbd5e1; }
.plan-features li.disabled { color: #cbd5e1; }

/* CTA button */
.btn-plan {
    display: block; width: 100%;
    padding: 13px; border-radius: 12px;
    font-weight: 700; font-size: .9rem;
    text-align: center; cursor: pointer; transition: all .2s;
    text-decoration: none; border: none;
}
.btn-plan-outline {
    background: transparent;
    border: 2px solid var(--color-border, #e2e8f0);
    color: var(--color-text, #374151);
}
.btn-plan-outline:hover {
    border-color: #6366f1; color: #6366f1;
    background: rgba(99,102,241,.06);
}
.btn-plan-primary {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: #fff;
    box-shadow: 0 6px 20px rgba(99,102,241,.35);
}
.btn-plan-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(99,102,241,.45);
    color: #fff;
}

/* ── FAQ ────────────────────────────────────────── */
.faq-section { max-width: 760px; margin: 0 auto; }
.faq-section h2 { font-weight: 800; text-align: center; margin-bottom: 32px; }
.faq-item {
    border: 1.5px solid var(--color-border, #e2e8f0);
    border-radius: 12px; margin-bottom: 10px;
    overflow: hidden;
}
.faq-question {
    padding: 16px 20px;
    font-weight: 600; font-size: .9rem;
    cursor: pointer;
    display: flex; justify-content: space-between; align-items: center;
    user-select: none;
    background: var(--color-surface, #fff);
    transition: background .2s;
}
.faq-question:hover { background: var(--color-hover, #f8fafc); }
.faq-question i { transition: transform .25s; flex-shrink: 0; }
.faq-item.open .faq-question i { transform: rotate(180deg); }
.faq-answer {
    padding: 0 20px;
    max-height: 0; overflow: hidden;
    transition: max-height .3s ease, padding .3s;
    font-size: .86rem; color: #64748b; line-height: 1.7;
}
.faq-item.open .faq-answer {
    max-height: 300px;
    padding: 0 20px 18px;
}

/* ── Comparativa ────────────────────────────────── */
.compare-table {
    background: var(--color-surface, #fff);
    border: 1.5px solid var(--color-border, #e2e8f0);
    border-radius: 16px; overflow: hidden;
    box-shadow: 0 2px 20px rgba(0,0,0,.06);
}
.compare-table table { width: 100%; border-collapse: collapse; }
.compare-table th {
    padding: 16px 20px; text-align: center;
    font-size: .78rem; font-weight: 800;
    text-transform: uppercase; letter-spacing: .08em;
    border-bottom: 2px solid var(--color-border, #e2e8f0);
    background: var(--color-surface, #fff);
}
.compare-table th:first-child { text-align: left; }
.compare-table th.highlight { background: rgba(99,102,241,.06); color: #6366f1; }
.compare-table td {
    padding: 13px 20px; border-bottom: 1px solid var(--color-border, #f1f5f9);
    font-size: .84rem; text-align: center;
}
.compare-table td:first-child { text-align: left; font-weight: 500; }
.compare-table td.highlight { background: rgba(99,102,241,.04); }
.compare-table tr:last-child td { border-bottom: none; }
.check-icon { color: #22c55e; font-size: 1rem; }
.cross-icon { color: #e2e8f0; font-size: 1rem; }

@media (max-width: 991px) {
    .plans-grid { grid-template-columns: 1fr; max-width: 420px; }
    .plan-box.featured { transform: none; }
    .plan-box.featured:hover { transform: translateY(-4px); }
}
</style>
@endpush

@section('contenido')

{{-- Hero ─────────────────────────────────────────── --}}
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

        {{-- ── GRID DE PLANES ──────────────────────────────────── --}}
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
                    <li><i class="bi bi-check-circle-fill check"></i> Visibilidad estándar</li>
                    <li><i class="bi bi-check-circle-fill check"></i> Soporte por email (48 h)</li>
                    <li><i class="bi bi-check-circle-fill check"></i> Comisión venta: 12%</li>
                    <li><i class="bi bi-check-circle-fill check"></i> Comisión renta: 15%</li>
                    <li class="disabled"><i class="bi bi-x-circle cross"></i> Sin publicaciones destacadas</li>
                    <li class="disabled"><i class="bi bi-x-circle cross"></i> Sin estadísticas</li>
                </ul>
                <a href="{{ route('register') }}" class="btn-plan btn-plan-outline">
                    Empezar gratis
                </a>
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
                    <li><i class="bi bi-check-circle-fill check"></i> 6 fotos + 1 video por publicación</li>
                    <li><i class="bi bi-check-circle-fill check"></i> 2 publicaciones destacadas / mes</li>
                    <li><i class="bi bi-check-circle-fill check"></i> Insignia <strong>Verificado</strong></li>
                    <li><i class="bi bi-check-circle-fill check"></i> Soporte por email (24 h)</li>
                    <li><i class="bi bi-check-circle-fill check"></i> Comisión venta: 10%</li>
                    <li><i class="bi bi-check-circle-fill check"></i> Comisión renta: 12%</li>
                    <li class="disabled"><i class="bi bi-x-circle cross"></i> Sin estadísticas avanzadas</li>
                </ul>
                <a href="{{ route('register') }}" class="btn-plan btn-plan-primary">
                    Elegir Básico
                </a>
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
                    <li><i class="bi bi-check-circle-fill check"></i> 10 fotos + 3 videos por publicación</li>
                    <li><i class="bi bi-check-circle-fill check"></i> 10 publicaciones destacadas / mes</li>
                    <li><i class="bi bi-check-circle-fill check"></i> Insignia <strong>PRO</strong></li>
                    <li><i class="bi bi-check-circle-fill check"></i> Soporte prioritario 24/7</li>
                    <li><i class="bi bi-check-circle-fill check"></i> Estadísticas avanzadas</li>
                    <li><i class="bi bi-check-circle-fill check"></i> Comisiones desde 8%</li>
                    <li><i class="bi bi-check-circle-fill check"></i> API de integración</li>
                </ul>
                <a href="{{ route('register') }}" class="btn-plan btn-plan-outline">
                    Elegir Profesional
                </a>
            </div>
        </div>

        {{-- ── TABLA COMPARATIVA ────────────────────────────────── --}}
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
                            ['Publicaciones',          '5',         '20',        'Ilimitadas'],
                            ['Fotos por publicación',  '3',         '6',         '10'],
                            ['Videos',                 '—',         '1',         '3'],
                            ['Publicaciones destacadas','—',        '2 / mes',   '10 / mes'],
                            ['Insignia verificado',    false,       true,        true],
                            ['Insignia PRO',           false,       false,       true],
                            ['Estadísticas avanzadas', false,       false,       true],
                            ['API de integración',     false,       false,       true],
                            ['Soporte prioritario',    false,       false,       true],
                            ['Comisión venta',         '12%',       '10%',       '8%'],
                            ['Comisión renta',         '15%',       '12%',       'Desde 8%'],
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

        {{-- ── FAQ ─────────────────────────────────────────────── --}}
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

    // ── Toggle anual / mensual ───────────────────────────────────────
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

    // ── FAQ accordion ────────────────────────────────────────────────
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