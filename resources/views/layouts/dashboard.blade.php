{{-- resources/views/layouts/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo_pagina', 'Mi Panel – Tools365')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/tools365.css') }}">
    @stack('css')

 <link rel="stylesheet" href="{{ asset('css/dashboard_blade.css') }}">

</head>
<body>

{{-- ── Overlay móvil ── --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- ══════════════════════ SIDEBAR ══════════════════════ --}}
<aside class="sidebar" id="sidebar">

    {{-- Logo --}}
    <a href="{{ route('dashboard') }}" class="sidebar-logo">
        <span class="brand">Tools<span>365</span></span>
        <span class="sidebar-logo-badge">PRO</span>
    </a>

    {{-- Usuario --}}
    <div class="sidebar-user">
        <div class="sidebar-avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="sidebar-user-info">
            <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
            <div class="sidebar-user-role">Plan Básico</div>
        </div>
        <div class="sidebar-user-badge"></div>
    </div>

    {{-- Navegación --}}
    <nav class="sidebar-nav">

        {{-- Principal --}}
        <div class="nav-section-label">Principal</div>

        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i>
            Mi Panel
        </a>

        {{-- Mis actividades --}}
        <div class="nav-section-label">Mis Actividades</div>

        <a href="#" class="nav-item">
            <i class="bi bi-plus-circle-fill"></i>
            Publicar herramienta
        </a>

        <a href="#" class="nav-item">
            <i class="bi bi-box-seam-fill"></i>
            Mis publicaciones
            <span class="nav-badge">3</span>
        </a>

        {{-- Comprar / Rentar --}}
        <div class="nav-section-label">Comprar y Rentar</div>

        <a href="#" class="nav-item">
            <i class="bi bi-bag-heart-fill"></i>
            Mis compras
        </a>

        <a href="#" class="nav-item">
            <i class="bi bi-clock-history"></i>
            Mis rentas activas
            <span class="nav-badge info">2</span>
        </a>

        <a href="#" class="nav-item">
            <i class="bi bi-heart-fill"></i>
            Guardados / Favoritos
        </a>

        {{-- Subastas --}}
        <div class="nav-section-label">Subastas</div>

        <a href="#" class="nav-item">
            <i class="bi bi-hammer"></i>
            Subastas activas
            <span class="nav-badge new">5</span>
        </a>

        <a href="#" class="nav-item">
            <i class="bi bi-trophy-fill"></i>
            Mis pujas
        </a>

        <a href="#" class="nav-item">
            <i class="bi bi-award-fill"></i>
            Subastas ganadas
        </a>

        {{-- Finanzas --}}
        <div class="nav-section-label">Finanzas</div>

        <a href="#" class="nav-item">
            <i class="bi bi-wallet2"></i>
            Mi billetera
        </a>

        <a href="#" class="nav-item">
            <i class="bi bi-receipt"></i>
            Historial de pagos
        </a>

        {{-- Comunicación --}}
        <div class="nav-section-label">Comunicación</div>

        <a href="#" class="nav-item">
            <i class="bi bi-chat-dots-fill"></i>
            Mensajes
            <span class="nav-badge new">2</span>
        </a>

        <a href="#" class="nav-item">
            <i class="bi bi-bell-fill"></i>
            Notificaciones
        </a>

        {{-- Configuración --}}
        <div class="nav-section-label">Cuenta</div>

        <a href="#" class="nav-item">
            <i class="bi bi-person-circle"></i>
            Mi perfil
        </a>

        <a href="#" class="nav-item">
            <i class="bi bi-star-fill"></i>
            Mi plan
        </a>

        <a href="#" class="nav-item">
            <i class="bi bi-gear-fill"></i>
            Configuración
        </a>

        {{-- Soporte --}}
        <div class="nav-section-label">Soporte</div>

        <a href="{{ route('inicio') }}" class="nav-item">
            <i class="bi bi-shop"></i>
            Ir a la tienda
        </a>

        <a href="#" class="nav-item">
            <i class="bi bi-question-circle-fill"></i>
            Ayuda & FAQ
        </a>

    </nav>

    {{-- Logout --}}
    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-logout">
                <i class="bi bi-box-arrow-left"></i>
                Cerrar sesión
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

    {{-- Búsqueda --}}
    <div class="topbar-search">
        <i class="bi bi-search"></i>
        <input type="text" placeholder="Buscar herramientas, publicaciones...">
    </div>

    <div class="topbar-actions">
        {{-- Notificaciones --}}
        <a href="#" class="topbar-icon-btn" title="Notificaciones">
            <i class="bi bi-bell"></i>
            <span class="topbar-notif-dot"></span>
        </a>

        {{-- Mensajes --}}
        <a href="#" class="topbar-icon-btn" title="Mensajes">
            <i class="bi bi-chat-dots"></i>
        </a>

        {{-- Publicar rápido --}}
        <a href="#" class="topbar-icon-btn" title="Publicar herramienta"
           style="background: var(--accent); border-color: var(--accent); color: #fff;">
            <i class="bi bi-plus-lg"></i>
        </a>
    </div>

    {{-- Avatar usuario --}}
    <a href="#" class="topbar-user">
        <div class="topbar-user-avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <span class="topbar-user-name d-none d-md-inline">{{ explode(' ', auth()->user()->name)[0] }}</span>
    </a>

</header>

{{-- ══════════════════════ CONTENT ══════════════════════ --}}
<main class="main-content">
    <div class="page-body">

        {{-- Toast de sesión --}}
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

    </div>
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

    // Auto-ocultar toast después de 4s
    setTimeout(() => {
        const t = document.getElementById('sessionToast');
        if (t) t.remove();
    }, 4000);
</script>

@stack('scripts')
</body>
</html>