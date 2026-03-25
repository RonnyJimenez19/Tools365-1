{{-- resources/views/partials/navbar.blade.php --}}
{{--
    Incluir en app.blade.php con: @include('partials.navbar')
    Los links del nav provienen de la tabla nav_items (orden ASC, activo = true).
    La variable $navItems llega vía ViewComposer: App\Http\View\Composers\NavComposer
--}}

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

                <div class="header-actions">

                    @guest
                        <a href="{{ route('login') }}" class="btn-ghost-nav">
                            <i class="bi bi-box-arrow-in-right"></i><span>Ingresar</span>
                        </a>
                        <a href="{{ route('register') }}" class="btn-ghost-nav btn-ghost-nav--accent">
                            <i class="bi bi-person-plus"></i><span>Crear cuenta</span>
                        </a>
                    @endguest

                    @auth
                    <div class="dropdown">
                        <button class="btn-ghost-nav dropdown-toggle d-flex align-items-center gap-2"
                                type="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                            <i class="bi bi-person-circle"></i>
                            <span>
                                Hola, <strong style="color:#fff; font-weight:800;">
                                    {{ explode(' ', auth()->user()->name)[0] }}
                                </strong>
                            </span>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-person me-2"></i>Mi perfil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="bi bi-grid-1x2-fill me-2"></i>Mi panel
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-megaphone me-2"></i>Mis publicaciones
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-stars me-2"></i>Planes
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-left me-2"></i>Cerrar sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @endauth

                </div>
            </div>
        </div>
    </div>

    <!-- BOTTOM NAV — links desde la BD vía NavComposer ($navItems) -->
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

            {{--
                Links dinámicos desde nav_items (orden ASC).
                Cada item tiene: label, url, icono, target, es_acento (bool)
            --}}
            @foreach($navItems as $item)
                <a href="{{ $item->url }}"
                   class="header-nav-link {{ $item->es_acento ? 'ms-auto' : '' }}"
                   style="{{ $item->es_acento ? 'color: var(--color-accent);' : '' }}"
                   @if($item->target) target="{{ $item->target }}" @endif>
                    <i class="bi {{ $item->icono }}"></i> {{ $item->label }}
                </a>
            @endforeach

        </div>
    </div>

</header>