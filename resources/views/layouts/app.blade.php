<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo_pagina', 'tools365 - Todas tus herramientas en un solo lugar')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

    <style>
        :root {
            --color-primary: #1F3A93;
            --color-secondary: #7F8C8D;
            --color-accent: #F39C12;
            --color-dark: #2C3E50;
        }

        .navbar-tools {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-dark) 100%);
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 800;
        }

        body {
            padding-top: 100px;
        }

        .bg-primary-custom {
            background-color: var(--color-primary) !important;
        }

        .bg-accent {
            background-color: var(--color-accent) !important;
        }

        .bg-dark-custom {
            background-color: var(--color-dark) !important;
        }

        .text-accent {
            color: var(--color-accent) !important;
        }

        .btn-accent {
            background-color: var(--color-accent);
            border-color: var(--color-accent);
            color: white;
        }

        .btn-accent:hover {
            background-color: #E67E22;
            border-color: #E67E22;
            color: white;
        }

        .dropdown-menu-dark {
            max-height: 400px;
            overflow-y: auto;
        }

        /* Estilos responsivos para el navbar */
        @media (max-width: 991.98px) {
            /* En móviles/tablets, la barra de búsqueda ocupa todo el ancho */
            .search-container {
                width: 100%;
            }
            
            .search-container .input-group {
                max-width: 100% !important;
            }
            
            /* Espaciado entre elementos del menú colapsado */
            .navbar-collapse {
                padding-top: 1rem;
            }
            
            /* Botones apilados en móvil */
            .btn-outline-light,
            .btn-accent {
                width: 100%;
            }
        }

        @media (min-width: 992px) {
            /* En desktop, centramos la búsqueda */
            .search-container {
                justify-content: center;
            }
        }

        /* Mejora visual del input de búsqueda */
        .search-container input:focus {
            box-shadow: none;
            border-color: #ced4da;
        }

        .search-container .input-group-text {
            border-right: 0;
        }

        .search-container .form-control {
            border-left: 0;
        }

        .search-container .form-control:focus + .input-group-text,
        .search-container .input-group-text:has(+ .form-control:focus) {
            border-color: var(--color-accent);
        }
    </style>

    @stack('css')
</head>
<body>

<header class="fixed-top">
    <nav class="navbar navbar-expand-lg navbar-dark navbar-tools">
        <div class="container">

            <a class="navbar-brand d-flex align-items-center gap-1 ms-3" href="{{ route('inicio') }}">
                <img src="{{ asset('Imagenes/logo.png') }}" alt="logo" class="img-fluid" style="height:90px;">
                <span class="fw-bold fs-4 ms-1">tools<span class="text-accent">365</span></span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="#inicio">Inicio</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Categorías
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-building me-2"></i>Construcción</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-tree me-2"></i>Agricultura</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-egg me-2"></i>Ganadería</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-cup-straw me-2"></i>Alimentos</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-droplet me-2"></i>Plomería</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-lightning-charge me-2"></i>Electricidad</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-hammer me-2"></i>Carpintería</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-flower1 me-2"></i>Jardinería</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-fire me-2"></i>Soldadura</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-paint-bucket me-2"></i>Pintura</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-truck me-2"></i>Transporte</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Otros</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#planes">Planes</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
                </ul>

                <!-- Barra de búsqueda - Responsiva -->
                <div class="search-container d-flex align-items-center gap-3 flex-grow-1 mx-lg-3 my-3 my-lg-0">
                    <div class="input-group w-100" style="max-width: 400px;">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" placeholder="Buscar herramientas...">
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="d-flex gap-2 flex-column flex-lg-row w-100 w-lg-auto">
                    <button class="btn btn-outline-light">Iniciar Sesión</button>
                    <button class="btn btn-accent">Comienza Gratis</button>
                </div>
            </div>
        </div>
    </nav>
</header>

@yield('contenido')

<footer class="bg-dark-custom text-white mt-5">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <h4 class="fw-bold">tools<span class="text-accent">365</span></h4>
                <p class="text-white-50">
                    Todas tus herramientas en un solo lugar. Renta, compra, vende o subasta maquinaria industrial.
                </p>
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
                    <li class="mb-2 text-white-50"><i class="bi bi-envelope me-2"></i>contacto@tools365.com</li>
                    <li class="mb-2 text-white-50"><i class="bi bi-telephone me-2"></i>999 104 1723</li>
                    <li class="mb-2 text-white-50"><i class="bi bi-geo-alt me-2"></i>Mérida, Yucatán</li>
                </ul>
            </div>
        </div>

        <hr class="border-secondary">

        <div class="text-center text-white-50">
            <p class="mb-0">&copy; 2026 tools365. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>
</html>