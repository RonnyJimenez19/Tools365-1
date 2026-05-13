{{-- resources/views/layouts/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="es" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo_pagina', 'Mi Panel – Tools365')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/tools365.css') }}">

    <style>
        :root {
            --sidebar-width: 260px;
            --topbar-height: 60px;
            --brand-color: #534AB7;
            --brand-light: #ede9ff;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--bs-body-bg);
            border-right: 1px solid var(--bs-border-color);
            display: flex;
            flex-direction: column;
            z-index: 1040;
            overflow-y: auto;
            transition: transform .25s ease;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            padding: 1.1rem 1.25rem;
            text-decoration: none;
            border-bottom: 1px solid var(--bs-border-color);
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--bs-body-color);
            letter-spacing: -.5px;
        }
        .sidebar-logo span { color: var(--brand-color); }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--bs-border-color);
        }
        .sidebar-avatar {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: var(--brand-color);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: .9rem;
            flex-shrink: 0;
        }
        .sidebar-user-name { font-weight: 600; font-size: .88rem; line-height: 1.2; }
        .sidebar-user-role { font-size: .75rem; color: var(--bs-secondary-color); }

        .sidebar-nav { flex: 1; padding: .75rem .75rem; }

        .nav-section-label {
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--bs-secondary-color);
            padding: .75rem .5rem .25rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .5rem .75rem;
            border-radius: .5rem;
            color: var(--bs-body-color);
            text-decoration: none;
            font-size: .875rem;
            transition: background .15s, color .15s;
            position: relative;
        }
        .nav-item:hover  { background: var(--bs-tertiary-bg); color: var(--brand-color); }
        .nav-item.active { background: var(--brand-light); color: var(--brand-color); font-weight: 600; }

        .nav-badge {
            margin-left: auto;
            font-size: .7rem;
            padding: .15em .5em;
            border-radius: 999px;
            background: var(--bs-secondary-bg);
            color: var(--bs-secondary-color);
            font-weight: 600;
        }
        .nav-badge.new  { background: #fdecea; color: #c0392b; }
        .nav-badge.info { background: #e8f4fd; color: #1a6fa8; }

        .sidebar-role-badge {
            margin: .75rem .5rem 0;
            padding: .4rem .75rem;
            border-radius: .5rem;
            background: var(--brand-light);
            color: var(--brand-color);
            font-size: .75rem;
            font-weight: 700;
            display: flex; align-items: center; gap: .4rem;
        }

        .sidebar-footer {
            padding: .75rem 1rem 1rem;
            border-top: 1px solid var(--bs-border-color);
        }
        .sidebar-logout {
            width: 100%;
            display: flex; align-items: center; gap: .6rem;
            padding: .5rem .75rem;
            border-radius: .5rem;
            border: 1px solid var(--bs-border-color);
            background: transparent;
            color: var(--bs-body-color);
            font-size: .875rem;
            cursor: pointer;
            transition: background .15s, color .15s;
        }
        .sidebar-logout:hover { background: #fdecea; color: #c0392b; border-color: #fbc4c0; }

        /* ── OVERLAY MÓVIL ── */
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.4);
            z-index: 1039;
        }
        .sidebar-overlay.open { display: block; }

        /* ── TOPBAR ── */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: var(--bs-body-bg);
            border-bottom: 1px solid var(--bs-border-color);
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: 0 1.25rem;
            z-index: 1030;
        }

        .topbar-toggle {
            display: none;
            background: none; border: none;
            padding: .25rem .4rem;
            cursor: pointer;
            color: var(--bs-body-color);
            border-radius: .375rem;
        }
        .topbar-toggle:hover { background: var(--bs-tertiary-bg); }

        .topbar-title {
            font-weight: 700;
            font-size: 1rem;
        }
        .topbar-breadcrumb {
            font-size: .85rem;
            color: var(--bs-secondary-color);
            margin-left: .25rem;
        }

        .topbar-search {
            display: flex;
            align-items: center;
            gap: .5rem;
            background: var(--bs-tertiary-bg);
            border: 1px solid var(--bs-border-color);
            border-radius: .5rem;
            padding: .35rem .75rem;
            flex: 1;
            max-width: 380px;
            margin-left: auto;
        }
        .topbar-search i { color: var(--bs-secondary-color); font-size: .85rem; }
        .topbar-search input {
            border: none; background: none; outline: none;
            font-size: .85rem; width: 100%;
            color: var(--bs-body-color);
        }
        .topbar-search input::placeholder { color: var(--bs-secondary-color); }

        .topbar-actions { display: flex; align-items: center; gap: .25rem; }

        .topbar-icon-btn {
            position: relative;
            width: 36px; height: 36px;
            display: flex; align-items: center; justify-content: center;
            border-radius: .5rem;
            color: var(--bs-body-color);
            text-decoration: none;
            transition: background .15s;
        }
        .topbar-icon-btn:hover { background: var(--bs-tertiary-bg); color: var(--brand-color); }
        .topbar-icon-btn.topbar-btn-accent {
            background: var(--brand-color);
            color: #fff;
        }
        .topbar-icon-btn.topbar-btn-accent:hover { background: #453da0; color: #fff; }

        .topbar-notif-dot {
            position: absolute;
            top: 6px; right: 6px;
            width: 7px; height: 7px;
            border-radius: 50%;
            background: #e74c3c;
            border: 1.5px solid var(--bs-body-bg);
        }

        .topbar-user {
            display: flex; align-items: center; gap: .5rem;
            text-decoration: none;
            color: var(--bs-body-color);
            padding: .25rem .4rem;
            border-radius: .5rem;
            transition: background .15s;
        }
        .topbar-user:hover { background: var(--bs-tertiary-bg); }
        .topbar-user-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: var(--brand-color);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: .8rem;
        }
        .topbar-user-name { font-size: .875rem; font-weight: 600; }

        /* ── MAIN CONTENT ── */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--topbar-height);
            min-height: calc(100vh - var(--topbar-height));
            padding: 1.75rem;
            background: var(--bs-tertiary-bg);
        }

        /* ── TOAST SESSION ── */
        .toast-session {
            position: fixed;
            bottom: 1.5rem; right: 1.5rem;
            background: #1a7f4b;
            color: #fff;
            padding: .75rem 1.25rem;
            border-radius: .75rem;
            display: flex; align-items: center; gap: .6rem;
            font-size: .875rem;
            font-weight: 500;
            z-index: 9999;
            box-shadow: 0 4px 16px rgba(0,0,0,.15);
            animation: slideUp .3s ease;
        }
        .toast-close {
            margin-left: .5rem;
            background: none; border: none;
            color: rgba(255,255,255,.7);
            cursor: pointer; padding: 0;
            display: flex; align-items: center;
        }
        .toast-close:hover { color: #fff; }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to   { transform: translateY(0);    opacity: 1; }
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .topbar { left: 0; }
            .topbar-toggle { display: flex; }
            .topbar-search { max-width: 200px; }
            .main-content { margin-left: 0; }
        }

        @media (max-width: 575.98px) {
            .topbar-search { display: none; }
            .main-content { padding: 1rem; }
        }
    </style>

    @stack('css')
</head>
<body>

{{-- Overlay móvil --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- ══════════════════════ SIDEBAR ══════════════════════ --}}
<aside class="sidebar" id="sidebar">

    <a href="{{ route('inicio') }}" class="sidebar-logo">
        Tools<span>365</span>
    </a>

    {{-- Usuario --}}
    <div class="sidebar-user">
        <div class="sidebar-avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div>
            <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
            <div class="sidebar-user-role">
                @if(auth()->user()->esAdmin())
                    Administrador
                @elseif(auth()->user()->rol === 'gerente')
                    Gerente
                @else
                    Plan Básico
                @endif
            </div>
        </div>
    </div>

    {{-- Navegación --}}
    <nav class="sidebar-nav">

        {{-- ── PANEL PRINCIPAL ── --}}
        <div class="nav-section-label">Principal</div>
        <a href="{{ route('dashboard') }}"
           class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> Mi Panel
        </a>

        {{-- ── ADMIN / GERENTE ── --}}
        @if(auth()->user()->puedeEditar())
            <div class="nav-section-label">Gestión del sistema</div>
            <a href="#" class="nav-item">
                <i class="bi bi-plus-circle-fill"></i> Publicar herramienta
            </a>
            <a href="#" class="nav-item">
                <i class="bi bi-box-seam-fill"></i> Todas las herramientas
            </a>
            <a href="#" class="nav-item">
                <i class="bi bi-hammer"></i> Subastas
                <span class="nav-badge new">5</span>
            </a>

            @if(auth()->user()->esAdmin())
                <div class="nav-section-label">Administración</div>
                <a href="{{ route('dashboard') }}" class="nav-item">
                    <i class="bi bi-people-fill"></i> Usuarios
                </a>
                <a href="#" class="nav-item">
                    <i class="bi bi-file-earmark-text-fill"></i> Contenido de la página
                </a>
                <a href="#" class="nav-item">
                    <i class="bi bi-bar-chart-fill"></i> Reportes
                </a>
                <a href="#" class="nav-item">
                    <i class="bi bi-gear-fill"></i> Configuración
                </a>
            @endif
        @endif

        {{-- ── USUARIO INVITADO ── --}}
        @if(!auth()->user()->puedeEditar())
            <div class="nav-section-label">Mis publicaciones</div>
            <a href="{{ route('publicar.create') }}" class="nav-item">
                <i class="bi bi-plus-circle-fill"></i> Publicar herramienta
            </a>
            <a href="{{ route('mis-publicaciones.index') }}"
               class="nav-item {{ request()->routeIs('mis-publicaciones.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam-fill"></i> Mis publicaciones
                <span class="nav-badge info">3</span>
            </a>

            <div class="nav-section-label">Ventas</div>
            <a href="#" class="nav-item">
                <i class="bi bi-chat-left-text-fill"></i> Comentarios
                <span class="nav-badge">3</span>
            </a>

            <div class="nav-section-label">Actividad</div>
            <a href="#" class="nav-item">
                <i class="bi bi-clock-history"></i> Mis rentas
                <span class="nav-badge info">2</span>
            </a>
            <a href="#" class="nav-item">
                <i class="bi bi-hammer"></i> Subastas
                <span class="nav-badge new">5</span>
            </a>

            <div class="nav-section-label">Cuenta</div>
            <a href="#" class="nav-item {{ request()->routeIs('perfil.*') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i> Mi perfil
            </a>
            <a href="{{ route('planes.index') }}"
               class="nav-item {{ request()->routeIs('planes.*') ? 'active' : '' }}">
                <i class="bi bi-star-fill"></i> Mi plan
            </a>
        @endif

        {{-- ── SOPORTE ── --}}
        <div class="nav-section-label">Soporte</div>
        <a href="{{ route('inicio') }}" class="nav-item">
            <i class="bi bi-shop"></i> Ir a la tienda
        </a>
        <a href="#" class="nav-item">
            <i class="bi bi-question-circle-fill"></i> Ayuda & FAQ
        </a>

        @if(auth()->user()->puedeEditar())
            <div class="sidebar-role-badge">
                <i class="bi bi-shield-fill"></i>
                {{ ucfirst(auth()->user()->rol) }}
            </div>
        @endif

    </nav>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-logout">
                <i class="bi bi-box-arrow-left"></i> Cerrar sesión
            </button>
        </form>
    </div>

</aside>

{{-- ══════════════════════ TOPBAR ══════════════════════ --}}
<header class="topbar">

    <button class="topbar-toggle" onclick="toggleSidebar()" title="Menú">
        <i class="bi bi-list" style="font-size:1.4rem;"></i>
    </button>

    <div>
        <span class="topbar-title">@yield('topbar_title', 'Mi Panel')</span>
        @hasSection('topbar_breadcrumb')
            <span class="topbar-breadcrumb">/ @yield('topbar_breadcrumb')</span>
        @endif
    </div>

    <div class="topbar-search">
        <i class="bi bi-search"></i>
        <input type="text" placeholder="Buscar herramientas, publicaciones...">
    </div>

    <div class="topbar-actions">
        <a href="#" class="topbar-icon-btn" title="Notificaciones">
            <i class="bi bi-bell"></i>
            <span class="topbar-notif-dot"></span>
        </a>
        <a href="#" class="topbar-icon-btn" title="Mensajes">
            <i class="bi bi-chat-dots"></i>
        </a>
        @if(!auth()->user()->puedeEditar())
            <a href="{{ route('publicar.create') }}" class="topbar-icon-btn topbar-btn-accent" title="Publicar herramienta">
                <i class="bi bi-plus-lg"></i>
            </a>
        @endif
    </div>

    <a href="#" class="topbar-user">
        <div class="topbar-user-avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <span class="topbar-user-name d-none d-md-inline">
            {{ explode(' ', auth()->user()->name)[0] }}
        </span>
    </a>

</header>

{{-- ══════════════════════ CONTENT ══════════════════════ --}}
<main class="main-content">

    @if(session('success'))
        <div class="toast-session" id="sessionToast">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
            <button class="toast-close" onclick="this.parentElement.remove()">
                <i class="bi bi-x"></i>
            </button>
        </div>
    @endif

    @yield('contenido')

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('open');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('open');
    }
    setTimeout(() => {
        const t = document.getElementById('sessionToast');
        if (t) t.remove();
    }, 4000);
</script>

@stack('scripts')
</body>
</html>