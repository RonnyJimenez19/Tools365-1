@extends('layouts.app')

@section('titulo_pagina', 'tools365 - Inicio')

@push('css')
<style>
    /* ===== HERO ===== */
    #heroCarousel,
    #heroCarousel .carousel-item { height: 520px; }
    .hero-slide {
        height: 100%; display: flex; align-items: center;
        position: relative; overflow: hidden;
    }
    .hero-img {
        width: 100%; height: 100%; object-fit: cover;
        opacity: 0.3; position: absolute; top: 0; left: 0;
    }
    .hero-content { position: relative; z-index: 2; }
    #heroCarousel .carousel-indicators button {
        width: 10px; height: 10px; border-radius: 50%;
        background: rgba(255,255,255,0.5); border: none;
    }
    #heroCarousel .carousel-indicators .active { background: var(--color-accent); }

    /* ===== STATS ===== */
    .stats-bar { background: #fff; border-bottom: 2px solid #f0f0f0; }
    .stat-item { padding: 14px 0; border-right: 1px solid #eee; text-align: center; }
    .stat-item:last-child { border-right: none; }
    .stat-number { font-size: 1.5rem; font-weight: 900; color: var(--color-primary); }
    .stat-label  { font-size: 0.78rem; color: #888; font-weight: 600; }

    /* ===== SECTION HEADER ===== */
    .section-title-bar {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 1.5rem; padding-bottom: 0.75rem;
        border-bottom: 3px solid var(--color-accent);
    }
    .section-title-bar h2 { font-size: 1.3rem; font-weight: 800; margin: 0; display: flex; align-items: center; gap: 8px; }
    .section-title-bar a  { font-size: 0.85rem; font-weight: 700; color: var(--color-primary); text-decoration: none; }
    .section-title-bar a:hover { text-decoration: underline; }

    /* ===== PRODUCT CARDS ===== */
    .product-card {
        border: 1px solid #eee; border-radius: 10px; overflow: hidden;
        transition: all 0.25s ease; background: #fff; height: 100%;
    }
    .product-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); border-color: #ddd; }
    .product-card-img {
        height: 160px; object-fit: cover; width: 100%;
        display: flex; align-items: center; justify-content: center; font-size: 3.5rem;
    }
    .product-card-body    { padding: 12px 14px; }
    .product-card-title   { font-size: 0.88rem; font-weight: 700; color: #222; margin-bottom: 4px; line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .product-card-price   { font-size: 1.15rem; font-weight: 900; color: var(--color-primary); }
    .product-card-price small { font-size: 0.75rem; font-weight: 600; color: #888; }
    .product-card-badge   { font-size: 0.72rem; font-weight: 700; padding: 3px 8px; border-radius: 20px; }
    .product-card-location{ font-size: 0.75rem; color: #999; margin-top: 6px; }
    .auction-timer {
        background: #fff3e0; border: 1px solid #ffe0b2; border-radius: 6px;
        padding: 4px 10px; font-size: 0.78rem; font-weight: 700; color: #e65100;
        display: inline-flex; align-items: center; gap: 4px;
    }

    /* ===== MISIÓN Y VISIÓN ===== */
    .mision-vision-section { background: linear-gradient(135deg,#f8f9ff 0%,#eef1ff 100%); border-top: 4px solid var(--color-accent); }
    .mv-card { background: #fff; border-radius: 16px; padding: 2.5rem; height: 100%; box-shadow: 0 4px 20px rgba(31,58,147,0.08); border-left: 5px solid transparent; transition: transform 0.2s; }
    .mv-card:hover { transform: translateY(-3px); }
    .mv-card.mision { border-left-color: var(--color-primary); }
    .mv-card.vision  { border-left-color: var(--color-accent); }
    .mv-icon { width: 64px; height: 64px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin-bottom: 1.2rem; }
    .mv-icon.mision { background: #e8eaf6; color: var(--color-primary); }
    .mv-icon.vision  { background: #fff8e1; color: var(--color-accent); }
    .mv-card h3 { font-weight: 900; font-size: 1.4rem; margin-bottom: 0.8rem; }
    .mv-card p  { color: #555; line-height: 1.7; font-size: 0.97rem; }
    .mv-values  { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 1rem; }
    .mv-value-tag { background: #f0f4ff; color: var(--color-primary); font-size: 0.78rem; font-weight: 700; padding: 4px 12px; border-radius: 20px; }
    .mv-value-tag--vision { background: #fff8e1; color: #E67E22; }
    /* ===== MV CARD CON IMAGEN ===== */
.mv-card--con-imagen {
    padding: 0;
    overflow: hidden;
}
.mv-card--con-imagen .mv-card-body {
    padding: 1.8rem 2.5rem 2.5rem;
}
.mv-imagen-wrap {
    position: relative;
    height: 200px;
    overflow: hidden;
}
.mv-imagen {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}
.mv-card--con-imagen:hover .mv-imagen {
    transform: scale(1.04);
}
/* Degradado oscuro sobre la imagen para que el ícono resalte */
.mv-imagen-wrap::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0.25) 0%, rgba(0,0,0,0.55) 100%);
}
/* Ícono flotante encima de la imagen */
.mv-icon-flotante {
    position: absolute;
    bottom: -22px;
    left: 2.5rem;
    z-index: 2;
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    box-shadow: 0 4px 14px rgba(0,0,0,0.2);
    border: 3px solid #fff;
}
.mv-icon-flotante.mision { background: var(--color-primary); color: #fff; }
.mv-icon-flotante.vision  { background: var(--color-accent);  color: #fff; }
/* Espacio para que el ícono flotante no tape el texto */
.mv-card--con-imagen .mv-card-body { padding-top: 2.5rem; }
/* Sin imagen: el body no necesita padding extra */
.mv-card-body { padding: 0; }

    /* ===== PLANES ===== */
    .plans-row { display: flex; align-items: stretch; gap: 1.5rem; }
    .plan-col  { flex: 1; display: flex; flex-direction: column; }
    .plan-card-inner { border: 2px solid #eee; border-radius: 16px; display: flex; flex-direction: column; height: 100%; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s; }
    .plan-card-inner:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,0.1); }
    .plan-card-inner.destacado { border-color: #F39C12; box-shadow: 0 8px 32px rgba(243,156,18,0.2); }
    .plan-header { padding: 2rem 1.5rem 1.5rem; text-align: center; border-bottom: 1px solid #f0f0f0; }
    .plan-name { font-size: 0.85rem; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; color: #888; margin-bottom: 0.5rem; }
    .plan-name.destacado-text { color: #E67E22; }
    .plan-price-row { display: flex; align-items: flex-end; justify-content: center; gap: 4px; margin: 0.75rem 0 0.25rem; }
    .plan-price  { font-size: 2.8rem; font-weight: 900; color: var(--color-primary); line-height: 1; }
    .plan-period { font-size: 0.9rem; color: #aaa; font-weight: 600; padding-bottom: 6px; }
    .plan-desc   { font-size: 0.82rem; color: #999; margin: 0; }
    .plan-features { padding: 1.5rem; flex: 1; list-style: none; margin: 0; }
    .plan-features li { padding: 0.55rem 0; font-size: 0.88rem; color: #444; display: flex; align-items: center; gap: 8px; border-bottom: 1px solid #f5f5f5; }
    .plan-features li:last-child { border-bottom: none; }
    .plan-features li i { color: #27ae60; font-size: 0.9rem; flex-shrink: 0; }
    .plan-footer { padding: 1.25rem 1.5rem; border-top: 1px solid #f0f0f0; }
    .btn-plan { width: 100%; padding: 14px; border-radius: 10px; font-weight: 800; font-size: 0.95rem; border: 2px solid var(--color-primary); background: transparent; color: var(--color-primary); cursor: pointer; transition: all 0.2s; }
    .btn-plan:hover { background: var(--color-primary); color: #fff; }
    .btn-plan.destacado-btn { background: var(--color-accent); border-color: var(--color-accent); color: #fff; }
    .btn-plan.destacado-btn:hover { background: #E67E22; border-color: #E67E22; }
    .plan-badge-wrap { text-align: center; margin-bottom: 0.5rem; min-height: 28px; }
    .plan-badge { background: #F39C12; color: #fff; font-size: 0.72rem; font-weight: 800; letter-spacing: 1px; padding: 4px 14px; border-radius: 20px; }

    @media (max-width: 767px) {
        .plans-row { flex-direction: column; }
        #heroCarousel, #heroCarousel .carousel-item { height: 400px; }
    }
</style>
@endpush

@section('contenido')

{{-- ===== HERO CAROUSEL ===== --}}
<section id="inicio">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3"></button>
        </div>
        <div class="carousel-inner">
            <x-hero-slide :activo="true"
                imagen="Imagenes/dronagricola.png"
                gradiente="linear-gradient(135deg,#1F3A93 0%,#2C3E50 100%)"
                badge="🚀 Plataforma #1 en México"
                titulo="Todas tus" acento="herramientas en un solo lugar"
                descripcion="Renta, compra, vende o subasta maquinaria y herramientas industriales de forma segura."
                btnTexto="Comienza Gratis" btnIcono="bi-rocket-takeoff"
                btnTexto2="Ver Demo"       btnIcono2="bi-play-circle"
            />
            <x-hero-slide :activo="false"
                imagen="Imagenes/sistemariego.jpg"
                gradiente="linear-gradient(135deg,#1a6e3c 0%,#2C3E50 100%)"
                badge="🕐 Renta por días, semanas o meses" badgeColor="#27ae60"
                titulo="Renta el equipo que" acento="necesitas hoy"
                descripcion="Accede a maquinaria de alto rendimiento sin grandes inversiones. Desde excavadoras hasta drones agrícolas."
                btnTexto="Explorar Rentas" btnIcono="bi-search"
            />
            <x-hero-slide :activo="false"
                imagen="Imagenes/subasta.jpeg"
                gradiente="linear-gradient(135deg,#7b2d00 0%,#2C3E50 100%)"
                badge="🔨 Subastas en vivo" badgeColor="#e74c3c"
                titulo="Gana en las" acento="mejores subastas del día"
                descripcion="Participa en subastas de maquinaria industrial a precios increíbles. Nuevas subastas cada día."
                btnTexto="Ver Subastas" btnIcono="bi-hammer"
            />
            <x-hero-slide :activo="false"
                imagen="Imagenes/herramientas.png"
                gradiente="linear-gradient(135deg,#1a3a6e 0%,#0d1f40 100%)"
                badge="💰 Vende sin complicaciones" badgeColor="#2980b9"
                titulo="Publica tu equipo y" acento="véndelo rápido"
                descripcion="Miles de compradores activos te esperan. Publicar es gratis y llegas a toda la república."
                btnTexto="Publicar Ahora" btnIcono="bi-plus-circle"
            />
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>

{{-- ===== STATS BAR ===== --}}
<div class="stats-bar shadow-sm">
    <div class="container">
        <div class="row">
            <div class="col-3 stat-item"><div class="stat-number">10K+</div><div class="stat-label">Usuarios activos</div></div>
            <div class="col-3 stat-item"><div class="stat-number">5K+</div><div class="stat-label">Herramientas</div></div>
            <div class="col-3 stat-item"><div class="stat-number">98%</div><div class="stat-label">Satisfacción</div></div>
            <div class="col-3 stat-item"><div class="stat-number">32</div><div class="stat-label">Ciudades</div></div>
        </div>
    </div>
</div>

{{-- ===== SUBASTAS DEL DÍA ===== --}}
<section class="py-5 bg-light">
    <div class="container">
        <x-section-header titulo="Subastas del día" icono="bi-hammer" color="danger" />
        <div class="row g-3">
            <x-product-card titulo="Excavadora Caterpillar 320D 2019"   precio="$320,000" unidad="puja actual" ubicacion="Mérida, Yuc."    timer="04:32:15" imagen="Imagenes/excavadora.jpg" />
            <x-product-card titulo="Dron Agrícola DJI Agras T40"         precio="$85,000"  unidad="puja actual" ubicacion="Cancún, Q.R."    timer="01:14:05" imagen="Imagenes/dronagricola.png" iconoBg="linear-gradient(135deg,#e8f5e9,#c8e6c9)" iconoColor="#81c784" />
            <x-product-card titulo="Generador Industrial 150 kW Cummins" precio="$55,000"  unidad="puja actual" ubicacion="CDMX"            timer="08:00:00" imagen="Imagenes/Generador.jpeg" iconoBg="linear-gradient(135deg,#fff3e0,#ffe0b2)" iconoColor="#ffb74d" />
            <x-product-card titulo="Compresor Atlas Copco GA15 2021"     precio="$28,500"  unidad="puja actual" ubicacion="Monterrey, NL"   timer="02:45:30" imagen="Imagenes/compresor.png" iconoBg="linear-gradient(135deg,#fce4ec,#f8bbd0)" iconoColor="#f48fb1" />
        </div>
    </div>
</section>

{{-- ===== RENTAR ===== --}}
<section class="py-5">
    <div class="container">
        <x-section-header titulo="Disponible para Rentar" icono="bi-clock-history" color="primary" />
        <div class="row g-3">
            <x-product-card titulo="Andamio Multidireccional 6m — Acero" precio="$450" unidad="/día" ubicacion="Mérida, Yuc."    badge="Renta" badgeTipo="primary" imagen="Imagenes/andamio.jpg" />
            <x-product-card titulo="Bomba de Agua Sumergible 3HP"         precio="$180" unidad="/día" ubicacion="Valladolid, Yuc." badge="Renta" badgeTipo="primary" imagen="Imagenes/bombagua.png" iconoBg="linear-gradient(135deg,#e3f2fd,#bbdefb)" iconoColor="#64b5f6" />
            <x-product-card titulo="Pistola Airless Wagner 2800 PSI"      precio="$220" unidad="/día" ubicacion="Progreso, Yuc."   badge="Renta" badgeTipo="primary" imagen="Imagenes/pistola.jpg" iconoBg="linear-gradient(135deg,#f3e5f5,#e1bee7)" iconoColor="#ce93d8" />
            <x-product-card titulo="Motosierra Husqvarna 455 Rancher"     precio="$350" unidad="/día" ubicacion="Tizimín, Yuc."   badge="Renta" badgeTipo="primary" imagen="Imagenes/motosierra.jpg" />
        </div>
    </div>
</section>

{{-- ===== COMPRAR ===== --}}
<section class="py-5 bg-light">
    <div class="container">
        <x-section-header titulo="Comprar" icono="bi-bag-check" color="success" />
        <div class="row g-3">
            <x-product-card titulo="Taladro Percutor DeWalt 20V — Kit completo" precio="$3,200"  ubicacion="Mérida, Yuc."    badge="Venta" badgeTipo="success" imagen="Imagenes/taladro.jpg" />
            <x-product-card titulo="Soldadora MIG Lincoln Electric 180"          precio="$12,500" ubicacion="Campeche, Camp." badge="Venta" badgeTipo="success" imagen="Imagenes/soldadora.jpg" iconoBg="linear-gradient(135deg,#fff3e0,#ffe0b2)" iconoColor="#ffb74d" />
            <x-product-card titulo="Cortadora de Pasto Honda HRX217"             precio="$7,800"  ubicacion="Mérida, Yuc."    badge="Venta" badgeTipo="success" imagen="Imagenes/cortador.jpg" iconoBg="linear-gradient(135deg,#e0f2f1,#b2dfdb)" iconoColor="#4db6ac" />
            <x-product-card titulo="Multímetro Digital Fluke 115"                precio="$1,950"  ubicacion="Cancún, Q.R."    badge="Venta" badgeTipo="success" imagen="Imagenes/multimetro.jpg" iconoBg="linear-gradient(135deg,#fce4ec,#f8bbd0)" iconoColor="#f48fb1" />
        </div>
    </div>
</section>

{{-- ===== CTA VENDER ===== --}}
<section class="py-5" style="background:linear-gradient(135deg,#1F3A93 0%,#2C3E50 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 text-white mb-3 mb-lg-0">
                <h2 class="fw-bold mb-2"><i class="bi bi-cash-coin me-2 text-accent"></i>¿Tienes equipo para vender?</h2>
                <p class="text-white-50 mb-0">Llega a miles de compradores. Publica gratis y vende más rápido con Tools365.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <button class="btn btn-accent btn-lg px-5">
                    <i class="bi bi-plus-circle me-2"></i>Publicar ahora
                </button>
            </div>
        </div>
    </div>
</section>

{{-- ===== CATEGORÍAS ===== --}}
<section id="categorias" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Explora por Categoría</h2>
            <p class="lead text-muted">Encuentra lo que necesitas en nuestra amplia variedad</p>
        </div>
        <div class="row g-4">
            <x-categoria-card icon="bi-building"         titulo="Construcción" cantidad="1,200+ herramientas" color="primary" />
            <x-categoria-card icon="bi-tree"             titulo="Agricultura"  cantidad="850+ maquinarias"    color="success" />
            <x-categoria-card icon="bi-egg"              titulo="Ganadería"    cantidad="320+ equipos"         color="warning" />
            <x-categoria-card icon="bi-cup-straw"        titulo="Alimentos"    cantidad="540+ herramientas"    color="danger" />
            <x-categoria-card icon="bi-droplet"          titulo="Plomería"     cantidad="680+ productos"       color="info" />
            <x-categoria-card icon="bi-lightning-charge" titulo="Electricidad" cantidad="920+ herramientas"    color="dark" />
            <x-categoria-card icon="bi-hammer"           titulo="Carpintería"  cantidad="1,100+ equipos"       color="primary" />
            <x-categoria-card icon="bi-flower1"          titulo="Jardinería"   cantidad="450+ herramientas"    color="success" />
            <x-categoria-card icon="bi-fire"             titulo="Soldadura"    cantidad="380+ equipos"         color="warning" />
            <x-categoria-card icon="bi-paint-bucket"     titulo="Pintura"      cantidad="290+ productos"       color="danger" />
            <x-categoria-card icon="bi-truck"            titulo="Transporte"   cantidad="520+ vehículos"       color="info" />
            <x-categoria-card icon="bi-gear"             titulo="Otros"        cantidad="730+ herramientas"    color="secondary" />
        </div>
    </div>
</section>

{{-- ===== MISIÓN Y VISIÓN ===== --}}
<section class="py-5 mision-vision-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Quiénes somos</h2>
            <p class="lead text-muted">Construyendo el futuro del mercado de herramientas en México</p>
        </div>
        <div class="row g-4">
            <x-mv-card
                tipo="mision" imagen="Imagenes/mision.jpg" titulo="Nuestra Misión"
                texto="Democratizar el acceso a maquinaria y herramientas industriales en México, conectando a propietarios y usuarios a través de una plataforma segura, transparente y eficiente. Creemos que cualquier persona o empresa merece acceso a las mejores herramientas para crecer."
                :valores="['Accesibilidad', 'Transparencia', 'Confianza', 'Innovación']"
            />
            <x-mv-card
                tipo="vision" imagen="Imagenes/vision.jpg" titulo="Nuestra Visión"
                texto="Ser la plataforma líder en Latinoamérica para la renta, compra y subasta de herramientas y maquinaria industrial para 2030. Un ecosistema donde el equipo siempre está en uso, generando valor para quien lo posee y para quien lo necesita."
                :valores="['Liderazgo', 'Sustentabilidad', 'Impacto social']"
            />
        </div>
    </div>
</section>

{{-- ===== CARACTERÍSTICAS ===== --}}
<section class="py-5 bg-dark-custom text-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">¿Por qué tools365?</h2>
            <p class="lead text-white-50">La mejor plataforma para tus necesidades</p>
        </div>
        <div class="row g-4">
            <x-feature-card icon="bi-shield-check"  titulo="100% Seguro"     descripcion="Transacciones protegidas con verificación y garantías" />
            <x-feature-card icon="bi-clock-history" titulo="Disponible 24/7" descripcion="Accede cuando quieras desde cualquier dispositivo" />
            <x-feature-card icon="bi-cash-coin"     titulo="Mejores Precios" descripcion="Compara y encuentra las mejores ofertas del mercado" />
            <x-feature-card icon="bi-headset"       titulo="Soporte Experto" descripcion="Equipo dedicado para ayudarte en todo momento" />
        </div>
    </div>
</section>

{{-- ===== PLANES ===== --}}
<section id="planes" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Planes para ti</h2>
            <p class="lead text-muted">Elige el que mejor se adapte a tus necesidades</p>
        </div>
        <div class="plans-row">
            <x-plan-card
                nombre="FREE" precio="$0" periodo="/mes" descripcion="Ideal para usuarios nuevos"
                :caracteristicas="['Hasta 5 publicaciones','3 fotos por publicación','Visibilidad estándar','Soporte email (48h)','Comisión venta: 12%','Comisión renta: 15%']"
            />
            <x-plan-card
                nombre="BÁSICO" precio="$199" periodo="/mes" descripcion="Para vendedores activos"
                destacado="true" badge="MÁS POPULAR"
                :caracteristicas="['Hasta 20 publicaciones','6 fotos + 1 video','2 destacados al mes','Insignia Verificado','Soporte email (24h)','Comisión venta: 10%','Comisión renta: 12%']"
            />
            <x-plan-card
                nombre="PROFESIONAL" precio="$599" periodo="/mes" descripcion="Para empresas y alto volumen"
                :caracteristicas="['Publicaciones ilimitadas','10 fotos + 3 videos','10 destacados al mes','Insignia PRO','Soporte prioritario 24/7','Estadísticas avanzadas','Comisiones desde 8%']"
            />
        </div>
    </div>
</section>

{{-- ===== CTA FINAL ===== --}}
<section class="py-5 bg-accent text-white">
    <div class="container text-center py-5">
        <h2 class="display-4 fw-bold mb-4">¿Listo para comenzar?</h2>
        <p class="lead mb-4">Únete a miles de usuarios que ya confían en tools365</p>
        <button class="btn btn-light btn-lg px-5">
            <i class="fas fa-rocket me-2"></i>Crear cuenta gratis
        </button>
    </div>
</section>

@endsection