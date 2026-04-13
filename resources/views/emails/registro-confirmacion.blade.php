<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a Tools365</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f6fb;
            color: #1a202c;
        }
        .wrapper {
            max-width: 580px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
        }
        .header {
            background: linear-gradient(135deg, #1F3A93 0%, #2C3E50 100%);
            padding: 40px 40px 32px;
            text-align: center;
        }
        .header .brand {
            font-size: 32px;
            font-weight: 900;
            color: #fff;
            letter-spacing: -1px;
        }
        .header .brand span {
            color: #f6c90e;
        }
        .header .tagline {
            color: rgba(255,255,255,0.7);
            font-size: 13px;
            margin-top: 6px;
        }
        .body {
            padding: 40px 40px 32px;
        }
        .greeting {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 16px;
            color: #1F3A93;
        }
        .text {
            font-size: 15px;
            line-height: 1.7;
            color: #444;
            margin-bottom: 12px;
        }
        .highlight-box {
            background: #f0f4ff;
            border-left: 4px solid #1F3A93;
            border-radius: 8px;
            padding: 16px 20px;
            margin: 24px 0;
            font-size: 14px;
            color: #1F3A93;
        }
        .features {
            display: table;
            width: 100%;
            margin: 28px 0;
        }
        .feature {
            display: table-row;
        }
        .feature-icon {
            display: table-cell;
            width: 40px;
            padding: 8px 12px 8px 0;
            font-size: 20px;
            vertical-align: top;
        }
        .feature-text {
            display: table-cell;
            padding: 8px 0;
            font-size: 14px;
            color: #555;
            vertical-align: top;
        }
        .feature-text strong {
            display: block;
            color: #222;
            margin-bottom: 2px;
        }
        .btn-container {
            text-align: center;
            margin: 32px 0 20px;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #1F3A93 0%, #2563eb 100%);
            color: #ffffff !important;
            text-decoration: none;
            font-size: 16px;
            font-weight: 700;
            padding: 16px 48px;
            border-radius: 50px;
            letter-spacing: 0.3px;
        }
        .divider {
            border: none;
            border-top: 1px solid #eef0f5;
            margin: 28px 0;
        }
        .footer {
            background: #f8f9fc;
            padding: 24px 40px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eef0f5;
        }
        .footer a {
            color: #1F3A93;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- Header --}}
    <div class="header">
        <div class="brand">Tools<span>365</span></div>
        <div class="tagline">Tu mercado de herramientas en México</div>
    </div>

    {{-- Body --}}
    <div class="body">
        <p class="greeting">¡Hola, {{ $nombre }}! 👋</p>

        <p class="text">
            Tu cuenta en <strong>Tools365</strong> ha sido creada exitosamente. 
            Ya puedes explorar miles de herramientas y maquinaria disponibles para renta, 
            compra y subasta en todo México.
        </p>

        <div class="highlight-box">
            🎉 Tu registro fue completado. Haz clic en el botón de abajo para iniciar sesión y comenzar.
        </div>

        <div class="features">
            <div class="feature">
                <div class="feature-icon">🔧</div>
                <div class="feature-text">
                    <strong>Renta herramientas</strong>
                    Por días, semanas o meses sin grandes inversiones.
                </div>
            </div>
            <div class="feature">
                <div class="feature-icon">💰</div>
                <div class="feature-text">
                    <strong>Compra y vende</strong>
                    Miles de compradores activos te esperan.
                </div>
            </div>
            <div class="feature">
                <div class="feature-icon">🔨</div>
                <div class="feature-text">
                    <strong>Subastas en vivo</strong>
                    Gana maquinaria industrial a precios increíbles.
                </div>
            </div>
        </div>

        <div class="btn-container">
            <a href="{{ $loginUrl }}" class="btn">
                Iniciar Sesión →
            </a>
        </div>

        <hr class="divider">

        <p class="text" style="font-size:13px; color:#888; text-align:center;">
            Si no creaste esta cuenta, puedes ignorar este correo de forma segura.<br>
            Este enlace te llevará al login de <strong>Tools365</strong>.
        </p>
    </div>

    {{-- Footer --}}
    <div class="footer">
        © {{ date('Y') }} Tools365 · Todos los derechos reservados<br>
        <a href="{{ route('inicio') }}">tools365.com</a> · México
    </div>

</div>
</body>
</html>