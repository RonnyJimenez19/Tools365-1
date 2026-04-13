{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión – Tools365</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
    @stack('css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    
</head>
<body>

    {{-- ── Panel izquierdo ── --}}
    <div class="auth-left">
        <div class="auth-left-noise"></div>
        <div class="auth-left-content">

            <a href="{{ route('inicio') }}" class="auth-left-logo">
                <span class="brand">Tools<span>365</span></span>
            </a>

            <div class="auth-left-badge">
                <i class="bi bi-shield-check-fill"></i>
                Plataforma 100% segura
            </div>

            <h2 class="auth-left-headline">
                Tu mercado de<br><span>herramientas</span>
            </h2>
            <p class="auth-left-sub">
                Renta, compra, vende o subasta maquinaria a los mejores precios. Miles de usuarios confían en Tools365.
            </p>

            <div class="stat-pills">
                <div class="stat-pill"><strong>10K+</strong><span>Usuarios</span></div>
                <div class="stat-pill"><strong>5K+</strong><span>Productos</span></div>
                <div class="stat-pill"><strong>32</strong><span>Ciudades</span></div>
            </div>

        </div>
    </div>

    {{-- ── Panel derecho (form) ── --}}
    <div class="auth-right">

        <div class="auth-right-header">
            <h1>Bienvenido de vuelta </h1>
            <p>Ingresa tus datos para acceder a tu cuenta</p>
        </div>

        {{-- Alertas de sesión --}}
        @if(session('success'))
            <div class="alert-success">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($errors->has('email') && str_contains($errors->first('email'), 'verificar'))
    <div style="background:#fffbeb;border:1.5px solid #f6ad55;border-radius:12px;padding:14px 18px;font-size:14px;color:#744210;margin-bottom:20px;">
        <strong style="display:block;margin-bottom:6px;">¿No recibiste el correo?</strong>
        <a href="{{ route('verificacion.pendiente') }}" style="color:#1F3A93;font-weight:700;">
            Reenviar correo de verificación →
        </a>
    </div>
@endif

        @if(session('session_expired'))
    <div class="alert-expired" style="
        display: flex; align-items: flex-start; gap: 12px;
        background: #fffbeb; border: 1.5px solid #f6ad55;
        border-radius: 12px; padding: 14px 18px;
        font-size: 14px; color: #744210;
        margin-bottom: 20px;
    ">
        <i class="bi bi-hourglass-split" style="font-size:20px; flex-shrink:0; margin-top:1px;"></i>
        <div>
            <strong style="display:block; margin-bottom:2px;">Sesión expirada</strong>
            {{ session('session_expired') }}
        </div>
    </div>
@endif

        <form action="{{ route('login') }}" method="POST" novalidate>
            @csrf

            {{-- Email --}}
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <div class="input-icon-wrap">
                    <i class="bi bi-envelope input-icon"></i>
                    <input type="email" id="email" name="email"
                           class="form-control-custom {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           value="{{ old('email') }}"
                           placeholder="tucorreo@ejemplo.com"
                           autocomplete="email" required>
                </div>
                @error('email')
                    <div class="invalid-text"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label for="password">Contraseña</label>
                <div class="input-icon-wrap">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" id="password" name="password"
                           class="form-control-custom {{ $errors->has('password') ? 'is-invalid' : '' }}"
                           placeholder="Tu contraseña"
                           autocomplete="current-password" required>
                    <button type="button" class="toggle-pass" onclick="togglePass('password', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-text"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>

            {{-- Remember --}}
            <div class="remember-row">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Mantener sesión iniciada</label>
            </div>

            <button type="submit" class="btn-submit">
                <i class="bi bi-box-arrow-in-right"></i>
                Iniciar Sesión
            </button>
        </form>

        <div class="divider">o</div>

        <div class="auth-switch">
            ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate gratis</a>
        </div>

    </div>

<script>
    function togglePass(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon  = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }
</script>
</body>
</html>