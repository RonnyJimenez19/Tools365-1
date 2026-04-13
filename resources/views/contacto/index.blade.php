@extends('layouts.app')

@section('titulo_pagina', 'Contacto – Tools365')

@push('css')
<style>
/* ── Page ────────────────────────────────────────── */
.contacto-page {
    padding-top: 140px;
    padding-bottom: 80px;
}

/* ── Hero ────────────────────────────────────────── */
.contacto-hero {
    background: linear-gradient(135deg, #0c4a6e 0%, #0369a1 60%, #0ea5e9 100%);
    padding: 56px 0 90px;
    margin-top: -140px;
    padding-top: 196px;
    margin-bottom: -50px;
    position: relative;
    overflow: hidden;
    text-align: center;
}
.contacto-hero::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at center top, rgba(255,255,255,.08) 0%, transparent 60%);
}
/* Decorative circles */
.contacto-hero::after {
    content: '';
    position: absolute;
    width: 600px; height: 600px;
    border: 1px solid rgba(255,255,255,.05);
    border-radius: 50%;
    top: -200px; right: -150px;
    pointer-events: none;
}
.c-hero-inner { position: relative; z-index: 1; }
.c-hero-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
    color: rgba(255,255,255,.9); padding: 5px 16px; border-radius: 100px;
    font-size: .75rem; font-weight: 700; letter-spacing: .07em;
    text-transform: uppercase; margin-bottom: 18px;
}
.contacto-hero h1 {
    color: #fff; font-size: clamp(1.8rem, 5vw, 2.8rem);
    font-weight: 900; margin-bottom: 12px;
}
.contacto-hero p { color: rgba(255,255,255,.7); max-width: 480px; margin: 0 auto; }

/* ── Contact cards (top) ─────────────────────────── */
.contact-chips {
    display: flex; gap: 16px; justify-content: center;
    flex-wrap: wrap; margin-top: 36px; position: relative; z-index: 1;
}
.contact-chip {
    background: rgba(255,255,255,.1); backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,.18);
    color: #fff; padding: 10px 20px; border-radius: 100px;
    font-size: .82rem; font-weight: 600;
    display: inline-flex; align-items: center; gap: 8px;
    text-decoration: none; transition: all .2s;
}
.contact-chip:hover {
    background: rgba(255,255,255,.2); color: #fff;
    transform: translateY(-2px);
}
.contact-chip i { font-size: 1rem; }

/* ── Main content ────────────────────────────────── */
.contacto-body {
    padding-top: 50px;
}

/* ── Info cards ──────────────────────────────────── */
.info-card {
    background: var(--color-surface, #fff);
    border: 1px solid var(--color-border, #e2e8f0);
    border-radius: 16px;
    padding: 28px;
    box-shadow: 0 2px 16px rgba(0,0,0,.05);
    height: 100%;
}
.info-card-icon {
    width: 52px; height: 52px;
    border-radius: 14px;
    display: grid; place-items: center;
    font-size: 1.4rem;
    margin-bottom: 16px;
}
.info-card h6 { font-weight: 700; margin-bottom: 6px; }
.info-card p { font-size: .85rem; color: #64748b; margin: 0; }
.info-card a { color: #0369a1; text-decoration: none; font-weight: 500; }
.info-card a:hover { text-decoration: underline; }

/* ── Form card ───────────────────────────────────── */
.form-card {
    background: var(--color-surface, #fff);
    border: 1px solid var(--color-border, #e2e8f0);
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 4px 30px rgba(0,0,0,.06);
}
.form-card h4 { font-weight: 800; margin-bottom: 6px; }
.form-card .subtitle { color: #64748b; font-size: .88rem; margin-bottom: 28px; }

.form-label {
    font-size: .8rem; font-weight: 600;
    color: var(--color-text, #374151);
    letter-spacing: .03em; margin-bottom: 6px;
}
.form-control, .form-select {
    border: 1.5px solid var(--color-border, #e2e8f0);
    border-radius: 10px; padding: 11px 14px;
    font-size: .88rem;
    background: var(--color-surface, #fff);
    color: var(--color-text, #0f172a);
    transition: border-color .2s, box-shadow .2s;
}
.form-control:focus, .form-select:focus {
    border-color: #0ea5e9;
    box-shadow: 0 0 0 3px rgba(14,165,233,.15);
    outline: none;
}

/* Asunto chips */
.asunto-grid { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 4px; }
.asunto-chip {
    display: none;
}
.asunto-label {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 7px 14px; border-radius: 100px;
    border: 1.5px solid var(--color-border, #e2e8f0);
    font-size: .78rem; font-weight: 600; cursor: pointer;
    transition: all .2s; color: #64748b;
}
.asunto-chip:checked + .asunto-label {
    border-color: #0ea5e9; background: #f0f9ff; color: #0369a1;
}
.asunto-label:hover { border-color: #0ea5e9; color: #0369a1; }

/* Rating */
.rating-wrap { display: flex; gap: 6px; }
.star-input { display: none; }
.star-label {
    font-size: 1.6rem; cursor: pointer; color: #e2e8f0;
    transition: color .15s;
}
.star-input:checked ~ .star-label,
.star-label:hover,
.star-label:hover ~ .star-label { /* handled by JS */ }
.star-label.active { color: #f59e0b; }

/* Btn enviar */
.btn-enviar {
    background: linear-gradient(135deg, #0369a1, #0ea5e9);
    color: #fff; border: none; padding: 13px 36px;
    border-radius: 12px; font-weight: 700; font-size: .9rem;
    cursor: pointer; transition: all .25s;
    display: inline-flex; align-items: center; gap: 8px;
    box-shadow: 0 4px 14px rgba(14,165,233,.35);
}
.btn-enviar:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(14,165,233,.45);
}

/* ── Success message ─────────────────────────────── */
.success-msg {
    display: none;
    background: #f0fdf4; border: 1.5px solid #86efac;
    border-radius: 14px; padding: 28px; text-align: center;
}
.success-msg i { font-size: 2.5rem; color: #22c55e; margin-bottom: 12px; display: block; }
.success-msg h5 { font-weight: 700; color: #166534; margin-bottom: 6px; }
.success-msg p { color: #4ade80; font-size: .88rem; margin: 0; }

/* ── Mapa placeholder ────────────────────────────── */
.map-wrap {
    border-radius: 16px; overflow: hidden;
    height: 260px; position: relative;
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    display: flex; align-items: center; justify-content: center;
    border: 1px solid #bfdbfe;
}
.map-pin {
    text-align: center; color: #1d4ed8;
}
.map-pin i { font-size: 2.5rem; margin-bottom: 8px; display: block; }
.map-pin span { font-size: .85rem; font-weight: 600; }

/* ── Social links ────────────────────────────────── */
.social-grid { display: flex; gap: 10px; flex-wrap: wrap; }
.social-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 9px 16px; border-radius: 10px; border: 1.5px solid var(--color-border, #e2e8f0);
    font-size: .8rem; font-weight: 600; text-decoration: none;
    transition: all .2s; color: var(--color-text, #374151);
}
.social-btn:hover { transform: translateY(-2px); color: var(--color-text, #374151); }
.social-btn.fb:hover  { border-color: #1877f2; background: #eff6ff; }
.social-btn.ig:hover  { border-color: #e1306c; background: #fdf2f8; }
.social-btn.wa:hover  { border-color: #25d366; background: #f0fdf4; }
.social-btn.li:hover  { border-color: #0a66c2; background: #eff6ff; }

/* ── Horarios ────────────────────────────────────── */
.horario-table { width: 100%; }
.horario-table tr td {
    padding: 7px 0; font-size: .84rem;
    border-bottom: 1px solid var(--color-border, #f1f5f9);
}
.horario-table tr:last-child td { border-bottom: none; }
.horario-table .dia { font-weight: 600; color: var(--color-text, #374151); }
.horario-table .hora { color: #64748b; text-align: right; }
.horario-table .cerrado { color: #ef4444; text-align: right; }
.dot-online { width: 8px; height: 8px; background: #22c55e; border-radius: 50%; display: inline-block; margin-right: 5px; animation: blink .8s infinite alternate; }
@keyframes blink { from { opacity: 1; } to { opacity: .4; } }

@media (max-width: 767px) {
    .form-card { padding: 24px; }
    .asunto-grid { gap: 6px; }
}
</style>
@endpush

@section('contenido')

{{-- Hero ─────────────────────────────────────────────────────── --}}
<div class="contacto-hero">
    <div class="container">
        <div class="c-hero-inner">
            <div class="c-hero-badge">
                <i class="bi bi-headset"></i> Soporte & contacto
            </div>
            <h1>¿Cómo podemos ayudarte?</h1>
            <p>Estamos disponibles para resolver tus dudas, reportar problemas o simplemente conectar.</p>
            <div class="contact-chips">
                <a href="mailto:hola@tools365.mx" class="contact-chip">
                    <i class="bi bi-envelope-fill"></i> hola@tools365.mx
                </a>
                <a href="tel:+529991234567" class="contact-chip">
                    <i class="bi bi-telephone-fill"></i> +52 999 123 4567
                </a>
                <span class="contact-chip" style="cursor:default;">
                    <span class="dot-online"></span> En línea ahora
                </span>
            </div>
        </div>
    </div>
</div>

<div class="contacto-page">
    <div class="container contacto-body">
        <div class="row g-4">

            {{-- ── Columna izquierda ─────────────────────── --}}
            <div class="col-lg-5">

                {{-- Cards de contacto rápido --}}
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="info-card">
                            <div class="info-card-icon" style="background:#eff6ff; color:#2563eb;">
                                <i class="bi bi-envelope-fill"></i>
                            </div>
                            <h6>Email</h6>
                            <p><a href="mailto:hola@tools365.mx">hola@tools365.mx</a><br>Respuesta en 24 h</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-card">
                            <div class="info-card-icon" style="background:#f0fdf4; color:#16a34a;">
                                <i class="bi bi-whatsapp"></i>
                            </div>
                            <h6>WhatsApp</h6>
                            <p><a href="https://wa.me/529991234567" target="_blank">+52 999 123 4567</a><br>Lun – Vie 9-18 h</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-card">
                            <div class="info-card-icon" style="background:#fefce8; color:#ca8a04;">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <h6>Oficina</h6>
                            <p>Calle 60 #500<br>Mérida, Yucatán, MX</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-card">
                            <div class="info-card-icon" style="background:#fdf2f8; color:#be185d;">
                                <i class="bi bi-clock-fill"></i>
                            </div>
                            <h6>Horario</h6>
                            <p>Lun – Vie<br>9:00 – 18:00 h CST</p>
                        </div>
                    </div>
                </div>

                {{-- Horario detallado --}}
                <div class="info-card mb-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-calendar-week me-2 text-primary"></i>Horario de atención</h6>
                    <table class="horario-table">
                        <tr><td class="dia">Lunes – Viernes</td><td class="hora">9:00 – 18:00 h</td></tr>
                        <tr><td class="dia">Sábado</td><td class="hora">10:00 – 14:00 h</td></tr>
                        <tr><td class="dia">Domingo</td><td class="cerrado">Cerrado</td></tr>
                        <tr><td class="dia">Días festivos</td><td class="cerrado">Cerrado</td></tr>
                    </table>
                </div>

                {{-- Mapa --}}
                <div class="map-wrap mb-4">
                    <div class="map-pin">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>Mérida, Yucatán, México</span>
                    </div>
                </div>

                {{-- Redes sociales --}}
                <div class="info-card">
                    <h6 class="fw-bold mb-3"><i class="bi bi-share-fill me-2" style="color:#0ea5e9;"></i>Síguenos</h6>
                    <div class="social-grid">
                        <a href="#" class="social-btn fb"><i class="bi bi-facebook" style="color:#1877f2;"></i> Facebook</a>
                        <a href="#" class="social-btn ig"><i class="bi bi-instagram" style="color:#e1306c;"></i> Instagram</a>
                        <a href="#" class="social-btn wa"><i class="bi bi-whatsapp" style="color:#25d366;"></i> WhatsApp</a>
                        <a href="#" class="social-btn li"><i class="bi bi-linkedin" style="color:#0a66c2;"></i> LinkedIn</a>
                    </div>
                </div>

            </div>

            {{-- ── Formulario ────────────────────────────── --}}
            <div class="col-lg-7">
                <div class="form-card">
                    <h4>Escríbenos</h4>
                    <p class="subtitle">Completa el formulario y te respondemos a la brevedad.</p>

                    {{-- Mensaje de éxito --}}
                    <div class="success-msg" id="success-msg">
                        <i class="bi bi-patch-check-fill"></i>
                        <h5>¡Mensaje enviado!</h5>
                        <p>Te responderemos en las próximas 24 horas hábiles.</p>
                    </div>

                    <form id="form-contacto" novalidate>
                        @csrf

                        {{-- Asunto --}}
                        <div class="mb-4">
                            <label class="form-label">¿Sobre qué quieres escribirnos? *</label>
                            <div class="asunto-grid">
                                @foreach([
                                    ['soporte',   'bi-headset',       'Soporte técnico'],
                                    ['ventas',    'bi-bag-check',     'Ventas / precios'],
                                    ['reporte',   'bi-flag',          'Reportar un problema'],
                                    ['sugerencia','bi-lightbulb',     'Sugerencia'],
                                    ['otro',      'bi-chat-dots',     'Otro'],
                                ] as [$val, $icon, $lab])
                                    <input type="radio" name="asunto" id="asunto-{{ $val }}"
                                           value="{{ $val }}" class="asunto-chip">
                                    <label for="asunto-{{ $val }}" class="asunto-label">
                                        <i class="bi {{ $icon }}"></i> {{ $lab }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Nombre + Email --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="c-nombre" class="form-label">Nombre completo *</label>
                                <input type="text" class="form-control" id="c-nombre"
                                       placeholder="Tu nombre" required>
                            </div>
                            <div class="col-md-6">
                                <label for="c-email" class="form-label">Correo electrónico *</label>
                                <input type="email" class="form-control" id="c-email"
                                       placeholder="tu@correo.com" required>
                            </div>
                        </div>

                        {{-- Teléfono + Ciudad --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="c-tel" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="c-tel"
                                       placeholder="+52 999 000 0000">
                            </div>
                            <div class="col-md-6">
                                <label for="c-ciudad" class="form-label">Ciudad</label>
                                <input type="text" class="form-control" id="c-ciudad"
                                       placeholder="Mérida, Yucatán">
                            </div>
                        </div>

                        {{-- Mensaje --}}
                        <div class="mb-4">
                            <label for="c-mensaje" class="form-label">Mensaje *</label>
                            <textarea class="form-control" id="c-mensaje" rows="5"
                                      placeholder="Cuéntanos con detalle en qué podemos ayudarte..." required></textarea>
                        </div>

                        {{-- Calificación --}}
                        <div class="mb-4">
                            <label class="form-label">¿Cómo calificarías tu experiencia con Tools365? <span style="color:#94a3b8;">(opcional)</span></label>
                            <div class="rating-wrap" id="star-rating">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" class="star-input">
                                @endfor
                                @for($i = 1; $i <= 5; $i++)
                                    <label for="star{{ $i }}" class="star-label" data-val="{{ $i }}">★</label>
                                @endfor
                            </div>
                        </div>

                        {{-- Privacy --}}
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="c-privacidad" required>
                                <label class="form-check-label" for="c-privacidad" style="font-size:.82rem; color:#64748b;">
                                    He leído y acepto el
                                    <a href="#" style="color:#0369a1;">aviso de privacidad</a>
                                    y los
                                    <a href="#" style="color:#0369a1;">términos de uso</a>.
                                </label>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <button type="submit" class="btn-enviar" id="btn-enviar">
                                <i class="bi bi-send-fill"></i>
                                Enviar mensaje
                            </button>
                            <span style="font-size:.75rem; color:#94a3b8;">
                                <i class="bi bi-shield-lock me-1"></i>
                                Tus datos están protegidos
                            </span>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // ── Star rating ────────────────────────────────────────────────
    const labels = document.querySelectorAll('.star-label');
    const inputs = document.querySelectorAll('.star-input');

    function renderStars(val) {
        labels.forEach(l => l.classList.toggle('active', +l.dataset.val <= val));
    }

    labels.forEach(l => {
        l.addEventListener('mouseenter', () => renderStars(+l.dataset.val));
        l.addEventListener('click', () => {
            const radio = document.getElementById('star' + l.dataset.val);
            if (radio) radio.checked = true;
            renderStars(+l.dataset.val);
        });
    });
    document.getElementById('star-rating')
        ?.addEventListener('mouseleave', () => {
            const checked = document.querySelector('.star-input:checked');
            renderStars(checked ? +checked.value : 0);
        });

    // ── Form submit (simulado) ─────────────────────────────────────
    const form    = document.getElementById('form-contacto');
    const btnEnv  = document.getElementById('btn-enviar');
    const success = document.getElementById('success-msg');

    form.addEventListener('submit', e => {
        e.preventDefault();
        if (!form.checkValidity()) { form.reportValidity(); return; }

        btnEnv.disabled = true;
        btnEnv.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';

        setTimeout(() => {
            form.style.display = 'none';
            success.style.display = 'block';
        }, 1200);
    });
});
</script>
@endpush