<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo_pagina', 'Tools365 - Todas tus herramientas en un solo lugar')</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    <link rel="stylesheet" href="{{ asset('css/tools365.css') }}">
    @stack('css')

    {{-- Aplicar tema antes de pintar la página (evita flash) --}}
    <script>
        const t = localStorage.getItem('tools365-theme')
            ?? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        document.documentElement.setAttribute('data-theme', t);
    </script>

<link rel="stylesheet" href="{{ asset('css/app_blade.css') }}">
   @stack('css')

</head>
<body>

<header class="fixed-top">

    {{-- BARRA DE ACCESIBILIDAD --}}
    <div class="a11y-bar">
        <div class="container">
            <div class="a11y-bar-inner">
                <span class="a11y-label">Accesibilidad</span>
                <button class="a11y-btn" data-zoom="sm" title="Texto pequeño">A-</button>
                <button class="a11y-btn active" data-zoom="md" title="Texto normal">A</button>
                <button class="a11y-btn" data-zoom="lg" title="Texto grande">A+</button>
                <button class="a11y-btn" data-zoom="xl" title="Texto muy grande" style="font-size:0.9rem">A++</button>
                <div class="a11y-divider"></div>
                <button class="dark-toggle" id="darkToggle">
                    <i class="bi bi-moon-stars-fill" id="darkIcon"></i>
                    <div class="dark-switch"></div>
                    <span id="darkLabel">Oscuro</span>
                </button>
            </div>
        </div>
    </div>

    <!-- TOP BAR -->
    <div class="header-top">
        <div class="container">
            <div class="header-top-inner">

                <a class="header-logo" href="{{ route('inicio') }}">
                    <img src="{{ asset('Imagenes/logo.png') }}" alt="logo">
                    <span class="brand-text">Tools<span>365</span></span>
                </a>

                <div class="header-search-wrap">
                    <div class="header-search-group">
                        <form action="{{ route('buscar') }}" method="GET" style="display:contents;">
                            <input class="header-search-input" type="text" name="q"
                                   placeholder="Buscar herramientas, maquinaria, equipos..."
                                   value="{{ request('q') }}">
                            <button class="header-search-btn" type="submit" title="Buscar">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>

                        <a href="{{ route('busqueda.avanzada') }}"
                           class="header-search-btn-avanzada {{ request()->routeIs('busqueda.avanzada') ? 'activa' : '' }}"
                           title="Búsqueda avanzada">
                            <i class="bi bi-sliders"></i>
                            <span>Avanzada</span>
                        </a>
                    </div>
                </div>

@guest
    <a href="{{ route('login') }}" class="btn-ghost-nav">
        <i class="bi bi-box-arrow-in-right"></i><span>Ingresar</span>
    </a>
    <a href="{{ route('register') }}" class="btn-ghost-nav btn-ghost-nav--accent">
        <i class="bi bi-person-plus"></i><span>Crear cuenta</span>
    </a>
@endguest

@auth
    <a href="{{ route('dashboard') }}" class="btn-ghost-nav btn-ghost-nav--accent">
        <i class="bi bi-grid-1x2-fill"></i><span>Mi Panel</span>
    </a>
    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="btn-ghost-nav" style="background:none;border:none;cursor:pointer;">
            <i class="bi bi-box-arrow-left"></i><span>Salir</span>
        </button>
    </form>
@endauth

            </div>
        </div>
    </div>

    <!-- BOTTOM NAV -->
    <div class="header-bottom">
        <div class="container">

            {{-- Dropdown Categorías — alimentado desde la BD vía View Composer --}}
            <div class="cat-dropdown-wrap">
                <a href="#" class="header-nav-link">
                    <i class="bi bi-grid-3x3-gap-fill"></i> Categorías
                    <i class="bi bi-chevron-down" style="font-size:0.7rem;"></i>
                </a>
                <div class="cat-dropdown-menu">
                    @foreach($categoriasNav as $cat)
                        <a href="{{ route('busqueda.avanzada', ['categoria' => $cat->slug]) }}">
                            <i class="bi {{ $cat->icono ?? 'bi-grid' }}"></i>
                            {{ $cat->nombre }}
                        </a>
                    @endforeach
                </div>
            </div>

            <a href="#inicio"   class="header-nav-link"><i class="bi bi-house-fill"></i> Inicio</a>
            <a href="#"         class="header-nav-link"><i class="bi bi-tags-fill"></i> Ofertas</a>
            <a href="#planes"   class="header-nav-link"><i class="bi bi-star-fill"></i> Planes</a>
            <a href="#contacto" class="header-nav-link"><i class="bi bi-chat-dots-fill"></i> Contacto</a>
            <a href="#" class="header-nav-link ms-auto" style="color: var(--color-accent);">
                <i class="bi bi-plus-circle-fill"></i> Publicar herramienta
            </a>

        </div>
    </div>

</header>

@yield('contenido')

<footer class="bg-dark-custom text-white mt-5">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <h4 class="fw-bold">Tools<span class="text-accent">365</span></h4>
                <p class="text-white-50">Todas tus herramientas en un solo lugar. Renta, compra, vende o subasta maquinaria industrial.</p>
                <div class="d-flex gap-3 mt-3">
                    <a href="#" class="text-white"><i class="fab fa-facebook fa-lg"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-instagram fa-lg"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-linkedin fa-lg"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 mb-4">
                <h6 class="text-accent mb-3">Empresa</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Nosotros</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Blog</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Carreras</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Contacto</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-4 mb-4">
                <h6 class="text-accent mb-3">Servicios</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Rentar</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Comprar</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Vender</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Subastar</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-4 mb-4">
                <h6 class="text-accent mb-3">Soporte</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Centro de Ayuda</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">FAQ</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Privacidad</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Términos</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-12 mb-4">
                <h6 class="text-accent mb-3">Contacto</h6>
                <ul class="list-unstyled">
                    <li><div class="footer-contact-item"><i class="bi bi-envelope"></i><span>contacto@tools365.com</span></div></li>
                    <li><div class="footer-contact-item"><i class="bi bi-telephone"></i><span>999 104 1723</span></div></li>
                    <li><div class="footer-contact-item"><i class="bi bi-geo-alt"></i><span>Mérida, Yucatán</span></div></li>
                </ul>
            </div>
        </div>
        <hr class="border-secondary">
        <div class="text-center text-white-50">
            <p class="mb-0">&copy; 2026 Tools365. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/tools365.js') }}"></script>

@stack('scripts')

</body>
</html>