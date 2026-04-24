{{-- resources/views/auth/login-admin.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo — Tools365</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login-admin.css') }}">

    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
</head>
<body>

    {{-- ── Panel izquierdo ── --}}
    <div class="auth-left">
        <div class="auth-left-content">

            <a href="{{ route('inicio') }}" class="auth-left-logo">
                <span class="brand">Tools<span>365</span></span>
            </a>

            <div class="auth-left-badge">
                <i class="bi bi-shield-lock-fill"></i>
                Panel administrativo
            </div>

            <h2 class="auth-left-headline">
                Acceso<br><span>restringido</span>
            </h2>
            <p class="auth-left-sub">
                Solo personal autorizado de Tools365 puede ingresar a este panel de control.
            </p>

            <div class="feat-list">
                <div class="feat-item">
                    <div class="feat-icon"><i class="bi bi-box-seam-fill"></i></div>
                    <span>Gestión de herramientas y productos</span>
                </div>
                <div class="feat-item">
                    <div class="feat-icon"><i class="bi bi-people-fill"></i></div>
                    <span>Administración de usuarios y roles</span>
                </div>
                <div class="feat-item">
                    <div class="feat-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
                    <span>Edición de contenido de la página</span>
                </div>
            </div>

        </div>
    </div>

    {{-- ── Panel derecho (form) ── --}}
    <div class="auth-right">

        <div class="auth-right-header">
            <h1>Bienvenido al panel</h1>
            <p>Ingresa tus credenciales de administrador</p>
        </div>

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

        <form action="{{ route('admin.login') }}" method="POST" novalidate>
            @csrf

            {{-- Email --}}
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <div class="input-icon-wrap">
                    <i class="bi bi-envelope input-icon"></i>
                    <input type="email" id="email" name="email"
                           class="form-control-custom {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           value="{{ old('email') }}"
                           placeholder="admin@tools365.com"
                           autocomplete="email" required>
                </div>
                @error('email')
                    <div class="invalid-text">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
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
                    <div class="invalid-text">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn-submit">
                <i class="bi bi-shield-lock"></i>
                Ingresar al panel
            </button>
        </form>

        <div class="auth-back">
            <a href="{{ route('inicio') }}">
                <i class="bi bi-arrow-left"></i>
                Volver a la página principal
            </a>
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

    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;

        grecaptcha.ready(function() {
            grecaptcha.execute('{{ config("services.recaptcha.site_key") }}', { action: 'admin_login' })
                .then(function(token) {
                    let input = document.createElement('input');
                    input.type  = 'hidden';
                    input.name  = 'recaptcha_token';
                    input.value = token;
                    form.appendChild(input);
                    form.submit();
                });
        });
    });
</script>
</body>
</html>