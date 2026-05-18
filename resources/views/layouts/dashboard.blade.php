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
    <link rel="stylesheet" href="{{ asset('css/layout_dashboard.css') }}">
 

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
<a href="{{ route('admin.usuarios') }}" class="nav-item {{ request()->routeIs('admin.usuarios') ? 'active' : '' }}">
    <i class="bi bi-people-fill"></i> Usuarios
</a>

                {{--<a href="#" class="nav-item">
                    <i class="bi bi-file-earmark-text-fill"></i> Contenido de la página
                </a>--}}
                <a href="#" class="nav-item">
                    <i class="bi bi-bar-chart-fill"></i> Reportes
                </a>
                {{--<a href="#" class="nav-item">
                    <i class="bi bi-gear-fill"></i> Configuración
                </a>--}}
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

<a href="{{ route('dashboard.ventas') }}"
   class="nav-item {{ request()->routeIs('dashboard.ventas') ? 'active' : '' }}">
    <i class="bi bi-bag-check-fill"></i> Mis ventas
</a>

<a href="{{ route('dashboard.compras') }}"
   class="nav-item {{ request()->routeIs('dashboard.compras') ? 'active' : '' }}">
    <i class="bi bi-bag-heart-fill"></i> Mis compras
</a>

<a href="{{ route('notificaciones.index') }}"
   class="nav-item {{ request()->routeIs('notificaciones.*') ? 'active' : '' }}">
    <i class="bi bi-bell-fill"></i> Notificaciones
    <span class="nav-badge new" id="notif-badge" style="display:none;"></span>
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
<a href="{{ route('notificaciones.index') }}" class="topbar-icon-btn" title="Notificaciones">
    <i class="bi bi-bell"></i>
    <span class="topbar-notif-dot" id="notif-dot" style="display:none;"></span>
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

    async function actualizarBadgeNotif() {
        try {
            const r = await fetch('{{ route('notificaciones.conteo') }}');
            const d = await r.json();

            const badge = document.getElementById('notif-badge');
            const dot   = document.getElementById('notif-dot');

            if (badge) {
                badge.textContent = d.conteo;
                badge.style.display = d.conteo > 0 ? '' : 'none';
            }

            if (dot) {
                dot.style.display = d.conteo > 0 ? '' : 'none';
            }
        } catch (e) {
            console.log('Error cargando notificaciones');
        }
    }

    actualizarBadgeNotif();
    setInterval(actualizarBadgeNotif, 30000);
</script>

@stack('scripts')
</body>
</html>