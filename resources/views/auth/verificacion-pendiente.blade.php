{{-- resources/views/auth/verificacion-pendiente.blade.php --}}
<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifica tu correo — Tools365</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    <style>
        body { display: flex; align-items: center; justify-content: center; min-height: 100vh; background: #f4f6fb; }
        .verify-card {
            background: #fff; border-radius: 20px; padding: 52px 44px;
            max-width: 480px; width: 100%; text-align: center;
            box-shadow: 0 8px 40px rgba(31,58,147,.10);
        }
        .verify-icon {
            width: 88px; height: 88px; border-radius: 50%;
            background: linear-gradient(135deg, #e8eeff 0%, #dbeafe 100%);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 28px; font-size: 40px; color: #1F3A93;
        }
        .verify-card h1 { font-size: 24px; font-weight: 800; color: #1a202c; margin-bottom: 12px; }
        .verify-card p  { font-size: 15px; color: #64748b; line-height: 1.7; margin-bottom: 0; }
        .email-badge {
            display: inline-block; background: #f0f4ff; color: #1F3A93;
            border-radius: 8px; padding: 6px 16px; font-weight: 700;
            font-size: 14px; margin: 14px 0 20px;
        }
        .btn-login {
            display: inline-flex; align-items: center; gap: 8px;
            background: linear-gradient(135deg, #1F3A93, #2563eb);
            color: #fff; font-weight: 700; font-size: 15px;
            padding: 14px 36px; border-radius: 50px; text-decoration: none;
            margin-top: 28px; transition: opacity .2s;
        }
        .btn-login:hover { opacity: .88; color: #fff; }
        .hint { font-size: 13px; color: #94a3b8; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="verify-card">
        <div class="verify-icon"><i class="bi bi-envelope-check-fill"></i></div>

        <h1>Revisa tu correo</h1>

        @if(session('email'))
            <p>Enviamos un enlace de verificación a:</p>
            <div class="email-badge">{{ session('email') }}</div>
        @endif

        <p>Haz clic en el enlace del correo para activar tu cuenta y poder iniciar sesión en Tools365.</p>

        <a href="{{ route('login') }}" class="btn-login">
            <i class="bi bi-box-arrow-in-right"></i>
            Ir al inicio de sesión
        </a>

        <p class="hint">¿No recibiste el correo? Revisa tu carpeta de spam.</p>

        <hr style="border:none;border-top:1px solid #eef0f5;margin:28px 0;">

@if(session('success'))
    <div style="background:#f0fdf4;border:1px solid #86efac;border-radius:8px;padding:10px 16px;font-size:13px;color:#166534;margin-bottom:16px;">
        ✓ {{ session('success') }}
    </div>
@endif

@if(session('info'))
    <div style="background:#eff6ff;border:1px solid #93c5fd;border-radius:8px;padding:10px 16px;font-size:13px;color:#1e40af;margin-bottom:16px;">
        ℹ {{ session('info') }}
    </div>
@endif

@if($errors->any())
    <div style="background:#fef2f2;border:1px solid #fca5a5;border-radius:8px;padding:10px 16px;font-size:13px;color:#991b1b;margin-bottom:16px;">
        {{ $errors->first() }}
    </div>
@endif

<p style="font-size:13px;color:#64748b;margin-bottom:12px;">
    ¿No recibiste el correo? Ingresa tu correo y te lo reenviamos.
</p>

<form action="{{ route('verificacion.reenviar') }}" method="POST">
    @csrf
    <div style="display:flex;gap:8px;">
        <input type="email" name="email"
               value="{{ session('email') }}"
               placeholder="tucorreo@ejemplo.com"
               required
               style="flex:1;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none;">
        <button type="submit"
                style="background:linear-gradient(135deg,#1F3A93,#2563eb);color:#fff;font-weight:700;font-size:14px;padding:10px 18px;border:none;border-radius:10px;cursor:pointer;white-space:nowrap;">
            Reenviar
        </button>
    </div>
</form>
    </div>
</body>
</html>