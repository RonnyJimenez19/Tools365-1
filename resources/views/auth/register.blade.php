{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta – Tools365</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    @stack('css')

    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>

    {{-- ── Panel izquierdo ── --}}
    <div class="auth-left">
        <div class="auth-left-content">

            <a href="{{ route('inicio') }}" class="auth-left-logo">
                <span class="brand">Tools<span>365</span></span>
            </a>

            <h2 class="auth-left-headline">
                Únete y empieza a<br><span>generar dinero</span>
            </h2>
            <p class="auth-left-sub">
                Publica tus herramientas, renta maquinaria o consigue las mejores ofertas en subastas.
            </p>

            <div class="benefits-list">
                <div class="benefit-item">
                    <div class="benefit-icon"><i class="bi bi-rocket-takeoff-fill"></i></div>
                    <div class="benefit-text">
                        <strong>Publica en minutos</strong>
                        <span>Sube fotos y empieza a recibir ofertas de inmediato</span>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon"><i class="bi bi-shield-fill-check"></i></div>
                    <div class="benefit-text">
                        <strong>Transacciones seguras</strong>
                        <span>Sistema de verificación y garantías en cada operación</span>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon"><i class="bi bi-cash-stack"></i></div>
                    <div class="benefit-text">
                        <strong>Registro 100% gratis</strong>
                        <span>Sin cargos iniciales, solo pagas al cerrar una venta</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ── Panel derecho ── --}}
    <div class="auth-right">

        <div class="auth-right-header">
            <h1>Crea tu cuenta</h1>
            <p>Completa los datos y únete a la comunidad Tools365</p>
        </div>

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

        <form action="{{ route('register') }}" method="POST" novalidate>
            @csrf

            {{-- Nombre completo --}}
            <div class="form-group">
                <label for="name">Nombre completo</label>
                <div class="input-icon-wrap">
                    <i class="bi bi-person input-icon"></i>
                    <input type="text" id="name" name="name"
                           class="form-control-custom {{ $errors->has('name') ? 'is-invalid' : '' }}"
                           value="{{ old('name') }}"
                           placeholder="Tu nombre completo"
                           autocomplete="name" required>
                </div>
                @error('name')
                    <div class="invalid-text"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

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

            {{-- Contraseña --}}
            <div class="form-group">
                <label for="password">Contraseña</label>
                <div class="input-icon-wrap">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" id="password" name="password"
                           class="form-control-custom {{ $errors->has('password') ? 'is-invalid' : '' }}"
                           placeholder="Mínimo 8 caracteres"
                           autocomplete="new-password"
                           oninput="updateStrength(this.value)"
                           required>
                    <button type="button" class="toggle-pass" onclick="togglePass('password', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                <div class="pass-strength">
                    <div class="pass-strength-bar">
                        <div class="pass-strength-fill" id="strengthFill"></div>
                    </div>
                    <span class="pass-strength-label" id="strengthLabel">Ingresa una contraseña</span>
                </div>
                @error('password')
                    <div class="invalid-text"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            {{-- Confirmar contraseña --}}
            <div class="form-group">
                <label for="password_confirmation">Confirmar contraseña</label>
                <div class="input-icon-wrap">
                    <i class="bi bi-lock-fill input-icon"></i>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="form-control-custom"
                           placeholder="Repite tu contraseña"
                           autocomplete="new-password" required>
                    <button type="button" class="toggle-pass" onclick="togglePass('password_confirmation', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            {{-- Términos --}}
            <div class="terms-row">
                <input type="checkbox" id="terms" required>
                <label for="terms">
                    Acepto los <a href="#">Términos y Condiciones</a> y la
                    <a href="#">Política de Privacidad</a> de Tools365
                </label>
            </div>

            <button type="submit" class="btn-submit">
                <i class="bi bi-person-plus-fill"></i>
                Crear cuenta gratis
            </button>
        </form>

        <div class="divider">o</div>

        <div class="auth-switch">
            ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
        </div>

    </div>

<script>
    function togglePass(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon  = btn.querySelector('i');
        input.type = input.type === 'password' ? 'text' : 'password';
        icon.className = input.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
    }

    function updateStrength(val) {
        const fill  = document.getElementById('strengthFill');
        const label = document.getElementById('strengthLabel');
        let score = 0;
        if (val.length >= 8)          score++;
        if (/[A-Z]/.test(val))        score++;
        if (/[0-9]/.test(val))        score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const levels = [
            { w: '0%',   bg: '#edf0f4', txt: 'Ingresa una contraseña',  color: '#b0bec5' },
            { w: '25%',  bg: '#e74c3c', txt: 'Muy débil',               color: '#e74c3c' },
            { w: '50%',  bg: '#f39c12', txt: 'Débil',                   color: '#f39c12' },
            { w: '75%',  bg: '#3498db', txt: 'Buena',                   color: '#3498db' },
            { w: '100%', bg: '#27ae60', txt: 'Excelente 💪',            color: '#27ae60' },
        ];
        const l = val.length === 0 ? levels[0] : levels[score] ?? levels[0];
        fill.style.width      = l.w;
        fill.style.background = l.bg;
        label.textContent     = l.txt;
        label.style.color     = l.color;
    }
</script>
</body>
</html>