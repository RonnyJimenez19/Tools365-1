@extends('layouts.app')

@section('titulo_pagina', 'tools365 - Inicio')

@push('css')
<link rel="stylesheet" href="{{ asset('css/inicio_blade.css') }}">
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
            @forelse($subastas as $producto)
                <x-product-card :producto="$producto" />
            @empty
                <div class="col-12 text-center text-muted py-4">
                    <i class="bi bi-hammer fs-2 mb-2 d-block"></i>
                    No hay subastas activas en este momento.
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ===== RENTAR ===== --}}
<section class="py-5">
    <div class="container">
        <x-section-header titulo="Disponible para Rentar" icono="bi-clock-history" color="primary" />
        <div class="row g-3">
            @forelse($rentas as $producto)
            <x-product-card :producto="$producto" />
            @empty
                <div class="col-12 text-center text-muted py-4">
                    <i class="bi bi-clock-history fs-2 mb-2 d-block"></i>
                    No hay productos disponibles para renta.
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ===== COMPRAR ===== --}}
<section class="py-5 bg-light">
    <div class="container">
        <x-section-header titulo="Comprar" icono="bi-bag-check" color="success" />
        <div class="row g-3">
            @forelse($ventas as $producto)
                <x-product-card :producto="$producto" />
            @empty
                <div class="col-12 text-center text-muted py-4">
                    <i class="bi bi-bag-check fs-2 mb-2 d-block"></i>
                    No hay productos en venta en este momento.
                </div>
            @endforelse
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

{{-- ===== COMENTARIOS / OPINIONES ===== --}}
<section id="opiniones" class="py-5 bg-light">
    <div class="container">
 
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Lo que dice nuestra comunidad</h2>
            <p class="lead text-muted">Opiniones reales de usuarios de Tools365</p>
        </div>
 
        {{-- Grid de comentarios aprobados --}}
        @if(isset($comentarios_inicio) && $comentarios_inicio->count())
            <div class="row g-4 mb-5">
                @foreach($comentarios_inicio as $c)
                    <div class="col-md-4">
                        <x-comentario-card :comentario="$c" />
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-muted py-4 mb-4">
                <i class="bi bi-chat-dots fs-2 d-block mb-2"></i>
                Aún no hay opiniones. ¡Sé el primero en comentar!
            </div>
        @endif
 
        {{-- Botón para ver todos / dejar opinión --}}
        <div class="text-center">
            <a href="{{ route('comentarios.index') }}" class="btn btn-outline-primary rounded-pill px-5">
                <i class="bi bi-chat-square-quote me-2"></i>
                Ver todas las opiniones y dejar la tuya
            </a>
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