@extends('layouts.app')

@section('titulo_pagina', 'tools365 - Inicio')

@section('contenido')

<!-- Hero Section -->
<section id="inicio" class="bg-primary-custom text-white py-5">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold mb-4">
                    Todas tus <span class="text-accent">herramientas</span> en un solo lugar
                </h1>
                <p class="lead mb-4">
                    Renta, compra, vende o subasta maquinaria y herramientas industriales de forma segura y transparente.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <button class="btn btn-accent btn-lg">
                        <i class="fas fa-rocket me-2"></i>Comienza Gratis
                    </button>
                    <button class="btn btn-outline-light btn-lg">
                        <i class="fas fa-play-circle me-2"></i>Ver Demo
                    </button>
                </div>
                
                <!-- Estadísticas -->
                <div class="row mt-5">
                    <div class="col-4">
                        <h3 class="text-accent fw-bold mb-0">10+</h3>
                        <small class="text-white-50">Usuarios</small>
                    </div>
                    <div class="col-4">
                        <h3 class="text-accent fw-bold mb-0">5+</h3>
                        <small class="text-white-50">Herramientas</small>
                    </div>
                    <div class="col-4">
                        <h3 class="text-accent fw-bold mb-0">98%</h3>
                        <small class="text-white-50">Satisfacción</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <img src="{{ asset('Imagenes/dronagricola.png') }}" 
                     alt="Herramientas" 
                     class="img-fluid rounded shadow-lg">
            </div>
        </div>
    </div>
</section>

<!-- Categorías -->
<section id="categorias" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Explora por Categoría</h2>
            <p class="lead text-muted">Encuentra lo que necesitas en nuestra amplia variedad</p>
        </div>
        
        <div class="row g-4">
            <x-categoria-card 
                icon="bi-building" 
                titulo="Construcción" 
                cantidad="1,200+ herramientas"
                color="primary"
            />
            
            <x-categoria-card 
                icon="bi-tree" 
                titulo="Agricultura" 
                cantidad="850+ maquinarias"
                color="success"
            />
            
            <x-categoria-card 
                icon="bi-egg" 
                titulo="Ganadería" 
                cantidad="320+ equipos"
                color="warning"
            />
            
            <x-categoria-card 
                icon="bi-cup-straw" 
                titulo="Alimentos" 
                cantidad="540+ herramientas"
                color="danger"
            />
            
            <x-categoria-card 
                icon="bi-droplet" 
                titulo="Plomería" 
                cantidad="680+ productos"
                color="info"
            />
            
            <x-categoria-card 
                icon="bi-lightning-charge" 
                titulo="Electricidad" 
                cantidad="920+ herramientas"
                color="dark"
            />
            
            <x-categoria-card 
                icon="bi-hammer" 
                titulo="Carpintería" 
                cantidad="1,100+ equipos"
                color="primary"
            />
            
            <x-categoria-card 
                icon="bi-flower1" 
                titulo="Jardinería" 
                cantidad="450+ herramientas"
                color="success"
            />
            
            <x-categoria-card 
                icon="bi-fire" 
                titulo="Soldadura" 
                cantidad="380+ equipos"
                color="warning"
            />
            
            <x-categoria-card 
                icon="bi-paint-bucket" 
                titulo="Pintura" 
                cantidad="290+ productos"
                color="danger"
            />
            
            <x-categoria-card 
                icon="bi-truck" 
                titulo="Transporte" 
                cantidad="520+ vehículos"
                color="info"
            />
            
            <x-categoria-card 
                icon="bi-gear" 
                titulo="Otros" 
                cantidad="730+ herramientas"
                color="secondary"
            />
        </div>
    </div>
</section>

<!-- Características -->
<section class="py-5 bg-dark-custom text-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">¿Por qué tools365?</h2>
            <p class="lead text-white-50">La mejor plataforma para tus necesidades</p>
        </div>
        
        <div class="row g-4">
            <x-feature-card 
                icon="bi-shield-check" 
                titulo="100% Seguro"
                descripcion="Transacciones protegidas con verificación y garantías"
            />
            
            <x-feature-card 
                icon="bi-clock-history" 
                titulo="Disponible 24/7"
                descripcion="Accede cuando quieras desde cualquier dispositivo"
            />
            
            <x-feature-card 
                icon="bi-cash-coin" 
                titulo="Mejores Precios"
                descripcion="Compara y encuentra las mejores ofertas del mercado"
            />
            
            <x-feature-card 
                icon="bi-headset" 
                titulo="Soporte Experto"
                descripcion="Equipo dedicado para ayudarte en todo momento"
            />
        </div>
    </div>
</section>

<!-- Planes de Precios -->
<section id="planes" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Planes para ti</h2>
            <p class="lead text-muted">Elige el que mejor se adapte a tus necesidades</p>
        </div>
        
        <div class="row g-4">
            <!-- Plan FREE -->
            <div class="col-lg-4">
                <x-plan-card 
                    nombre="FREE"
                    precio="Gratis"
                    descripcion="Ideal para usuarios nuevos"
                    :caracteristicas="[
                        'Hasta 5 publicaciones',
                        '3 fotos por publicación',
                        'Visibilidad estándar',
                        'Soporte email (48h)',
                        'Comisión venta: 12%',
                        'Comisión renta: 15%'
                    ]"
                    destacado="false"
                />
            </div>
            
            <!-- Plan BÁSICO -->
            <div class="col-lg-4">
                <x-plan-card 
                    nombre="BÁSICO"
                    precio="$199"
                    periodo="/mes"
                    descripcion="Para vendedores activos"
                    :caracteristicas="[
                        'Hasta 20 publicaciones',
                        '6 fotos + 1 video',
                        '2 destacados al mes',
                        'Insignia Verificado',
                        'Soporte email (24h)',
                        'Comisión venta: 10%',
                        'Comisión renta: 12%'
                    ]"
                    destacado="true"
                    badge="MÁS POPULAR"
                />
            </div>
            
            <!-- Plan PROFESIONAL -->
            <div class="col-lg-4">
                <x-plan-card 
                    nombre="PROFESIONAL"
                    precio="$599"
                    periodo="/mes"
                    descripcion="Para empresas y alto volumen"
                    :caracteristicas="[
                        'Publicaciones ilimitadas',
                        '10 fotos + 3 videos',
                        '10 destacados al mes',
                        'Insignia PRO',
                        'Soporte prioritario 24/7',
                        'Estadísticas avanzadas',
                        'Comisiones desde 8%'
                    ]"
                    destacado="false"
                />
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
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