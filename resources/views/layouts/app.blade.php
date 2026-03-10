<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo_pagina', 'Tools365 - Todas tus herramientas en un solo lugar')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    <link rel="stylesheet" href="{{ asset('css/tools365.css') }}">

    {{-- Aplicar tema antes de pintar la página (evita flash) --}}
    <script>
        const t = localStorage.getItem('tools365-theme')
            ?? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        document.documentElement.setAttribute('data-theme', t);
    </script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap');

    :root {
        --color-primary: #1F3A93;
        --color-secondary: #7F8C8D;
        --color-accent: #F39C12;
        --color-accent-hover: #E67E22;
        --color-dark: #2C3E50;
    }

    * { font-family: 'Nunito', sans-serif; }

    body { padding-top: 128px; }

    /* === TOP BAR === */
    .header-top {
        background: linear-gradient(90deg, #1a2e7a 0%, #1F3A93 60%, #2a4ab5 100%);
        padding: 6px 0;
        box-shadow: 0 3px 12px rgba(0,0,0,0.18);
    }
    .header-top-inner { display: flex; align-items: center; gap: 16px; }

    .header-logo { text-decoration: none; display: flex; align-items: center; gap: 6px; flex-shrink: 0; }
    .header-logo img { height: 62px; }
    .header-logo .brand-text { font-weight: 900; font-size: 1.45rem; color: #fff; letter-spacing: -0.5px; }
    .header-logo .brand-text span { color: var(--color-accent); }

    .header-search-wrap { flex: 1 1 0; min-width: 0; display: flex; justify-content: center; }

    /* El grupo ahora tiene: input + btn buscar + btn avanzada */
    .header-search-group {
        display: flex;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        height: 44px;
        width: 100%;
        max-width: 640px;
    }
    .header-search-input {
        flex: 1;
        border: none;
        padding: 0 16px;
        font-size: 0.97rem;
        outline: none;
        background: #fff;
    }
    .header-search-input::placeholder { color: #aaa; }

    /* Botón buscar (lupa) - igual que antes */
    .header-search-btn {
        background: var(--color-accent);
        border: none;
        padding: 0 22px;
        color: #fff;
        font-size: 1.1rem;
        cursor: pointer;
        transition: background 0.2s;
        flex-shrink: 0;
    }
    .header-search-btn:hover { background: var(--color-accent-hover); }

    /* ===== NUEVO: Botón búsqueda avanzada ===== */
    .header-search-btn-avanzada {
        background: #162369;
        border: none;
        border-left: 1px solid rgba(255,255,255,0.15);
        padding: 0 16px;
        color: rgba(255,255,255,0.85);
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.2s, color 0.2s;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
        font-size: 0.8rem;
        font-weight: 700;
        font-family: 'Nunito', sans-serif;
        text-decoration: none;
    }
    .header-search-btn-avanzada:hover {
        background: #1F3A93;
        color: var(--color-accent);
    }
    /* Resalta si estamos en la página de búsqueda avanzada */
    .header-search-btn-avanzada.activa {
        background: var(--color-accent);
        color: #fff;
    }

    .header-actions { display: flex; align-items: center; gap: 4px; flex-shrink: 0; }
    .btn-ghost-nav { color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.8rem; font-weight: 600; padding: 5px 10px; border-radius: 6px; display: flex; align-items: center; gap: 5px; transition: color 0.2s, background 0.2s; white-space: nowrap; background: none; border: none; cursor: pointer; }
    .btn-ghost-nav:hover { color: #fff; background: rgba(255,255,255,0.1); }
    .btn-ghost-nav--accent { color: rgba(255,210,100,0.85); }
    .btn-ghost-nav--accent:hover { color: var(--color-accent); background: rgba(243,156,18,0.1); }

    /* === BOTTOM NAV === */
    .header-bottom { background: #162369; }
    .header-bottom .container { display: flex; align-items: center; }
    .header-nav-link { color: rgba(255,255,255,0.85); text-decoration: none; font-size: 0.83rem; font-weight: 700; padding: 9px 14px; display: flex; align-items: center; gap: 5px; transition: color 0.18s, background 0.18s; white-space: nowrap; }
    .header-nav-link:hover { color: var(--color-accent); background: rgba(255,255,255,0.06); }

    .cat-dropdown-wrap { position: relative; }
    .cat-dropdown-menu { display: none; position: absolute; top: 100%; left: 0; background: #fff; border-radius: 0 0 10px 10px; box-shadow: 0 8px 24px rgba(0,0,0,0.13); z-index: 999; min-width: 230px; padding: 6px 0; }
    .cat-dropdown-wrap:hover .cat-dropdown-menu { display: block; }
    .cat-dropdown-menu a { display: flex; align-items: center; gap: 9px; padding: 9px 18px; color: #333; text-decoration: none; font-size: 0.87rem; font-weight: 600; transition: background 0.15s, color 0.15s; }
    .cat-dropdown-menu a:hover { background: #f0f4ff; color: var(--color-primary); }
    .cat-dropdown-menu hr { margin: 4px 12px; border-color: #eee; }

    .bg-primary-custom { background-color: var(--color-primary) !important; }
    .bg-accent { background-color: var(--color-accent) !important; }
    .bg-dark-custom { background-color: var(--color-dark) !important; }
    .text-accent { color: var(--color-accent) !important; }
    .btn-accent { background-color: var(--color-accent); border-color: var(--color-accent); color: white; }
    .btn-accent:hover { background-color: var(--color-accent-hover); border-color: var(--color-accent-hover); color: white; }

    .stats-bar { background: #fff; border-bottom: 2px solid #f0f0f0; }
    .stat-number { color: var(--color-primary); }

    @media (max-width: 991.98px) {
        .header-top-inner { flex-wrap: wrap; gap: 8px; }
        .header-search-wrap { order: 3; width: 100%; }
        .header-search-group { max-width: 100%; }
        .header-actions { margin-left: auto; }
        .header-bottom { overflow-x: auto; }
        .header-bottom .container { flex-wrap: nowrap; }
        body { padding-top: 148px; }
    }
    @media (max-width: 575.98px) {
        .btn-ghost-nav span { display: none; }
        .btn-ghost-nav { padding: 6px 8px; }
        .header-search-btn-avanzada span { display: none; }
        .header-search-btn-avanzada { padding: 0 12px; }
        body { padding-top: 115px; }
    }
</style>

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

            {{-- Búsqueda simple: el form sólo envuelve input + botón lupa --}}
            <div class="header-search-wrap">
                <div class="header-search-group">
                    <form action="{{ route('buscar') }}" method="GET"
                          style="display:contents;">
                        <input class="header-search-input" type="text" name="q"
                               placeholder="Buscar herramientas, maquinaria, equipos..."
                               value="{{ request('q') }}">
                        <button class="header-search-btn" type="submit"
                                title="Buscar">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>

                    {{-- BOTÓN BÚSQUEDA AVANZADA --}}
                    <a href="{{ route('busqueda.avanzada') }}"
                       class="header-search-btn-avanzada {{ request()->routeIs('busqueda.avanzada') ? 'activa' : '' }}"
                       title="Búsqueda avanzada">
                        <i class="bi bi-sliders"></i>
                        <span>Avanzada</span>
                    </a>
                </div>
            </div>

            <div class="header-actions">
                <a href="" class="btn-ghost-nav"><i class="bi bi-box-arrow-in-right"></i><span>Ingresa</span></a>
                <a href="" class="btn-ghost-nav btn-ghost-nav--accent"><i class="bi bi-person-plus"></i><span>Crea tu cuenta</span></a>
            </div>

        </div>
    </div>
</div>

    <!-- BOTTOM NAV -->
    <div class="header-bottom">
        <div class="container">
            <div class="cat-dropdown-wrap">
                <a href="#" class="header-nav-link"><i class="bi bi-grid-3x3-gap-fill"></i> Categorías <i class="bi bi-chevron-down" style="font-size:0.7rem;"></i></a>
                <div class="cat-dropdown-menu">
                    {{--  Cada categoría apunta a búsqueda avanzada con el combo preseleccionado --}}
                    <a href="{{ route('busqueda.avanzada', ['categoria' => 'construccion']) }}"><i class="bi bi-building"></i>Construcción</a>
                    <a href="{{ route('busqueda.avanzada', ['categoria' => 'agricultura']) }}"><i class="bi bi-tree"></i>Agricultura</a>
                    <a href="{{ route('busqueda.avanzada', ['categoria' => 'ganaderia']) }}"><i class="bi bi-egg"></i>Ganadería</a>
                    <a href="{{ route('busqueda.avanzada', ['categoria' => 'alimentos']) }}"><i class="bi bi-cup-straw"></i>Alimentos</a>
                    <a href="{{ route('busqueda.avanzada', ['categoria' => 'plomeria']) }}"><i class="bi bi-droplet"></i>Plomería</a>
                    <a href="{{ route('busqueda.avanzada', ['categoria' => 'electricidad']) }}"><i class="bi bi-lightning-charge"></i>Electricidad</a>
                    <a href="{{ route('busqueda.avanzada', ['categoria' => 'carpinteria']) }}"><i class="bi bi-hammer"></i>Carpintería</a>
                    <a href="{{ route('busqueda.avanzada', ['categoria' => 'jardineria']) }}"><i class="bi bi-flower1"></i>Jardinería</a>
                    <a href="{{ route('busqueda.avanzada', ['categoria' => 'soldadura']) }}"><i class="bi bi-fire"></i>Soldadura</a>
                    <a href="{{ route('busqueda.avanzada', ['categoria' => 'pintura']) }}"><i class="bi bi-paint-bucket"></i>Pintura</a>
                    <a href="{{ route('busqueda.avanzada', ['categoria' => 'transporte']) }}"><i class="bi bi-truck"></i>Transporte</a>
                    <hr>
                    <a href="{{ route('busqueda.avanzada', ['categoria' => 'otros']) }}"><i class="bi bi-gear"></i>Otros</a>
                </div>
            </div>
            <a href="#inicio" class="header-nav-link"><i class="bi bi-house-fill"></i> Inicio</a>
            <a href="#" class="header-nav-link"><i class="bi bi-tags-fill"></i> Ofertas</a>
            <a href="#planes" class="header-nav-link"><i class="bi bi-star-fill"></i> Planes</a>
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