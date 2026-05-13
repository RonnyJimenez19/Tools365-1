<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <title>Bienvenido a Tools365</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f6fb !important;
            color: #1a202c !important;
            -webkit-text-size-adjust: 100%;
        }
        /* Forzar modo claro en Apple Mail / Outlook */
        :root { color-scheme: light only; }
    </style>
</head>
<body style="margin:0;padding:0;background:#f4f6fb;font-family:'Segoe UI',Arial,sans-serif;">

<div style="max-width:580px;margin:40px auto;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.10);">

    {{-- Header --}}
    <div style="background:linear-gradient(135deg,#1F3A93 0%,#2C3E50 100%);padding:40px 40px 32px;text-align:center;">
        <div style="font-size:32px;font-weight:900;color:#ffffff;letter-spacing:-1px;font-family:'Segoe UI',Arial,sans-serif;">
            <span style="color:#ffffff;">Tools</span><span style="color:#f6c90e;">365</span>
        </div>
        <div style="color:rgba(255,255,255,0.75);font-size:13px;margin-top:6px;">
            Tu mercado de herramientas en México
        </div>
    </div>

    {{-- Body --}}
    <div style="padding:40px 40px 32px;background:#ffffff;">

        <p style="font-size:22px;font-weight:700;margin-bottom:16px;color:#1F3A93;font-family:'Segoe UI',Arial,sans-serif;">
            ¡Hola, {{ $nombre }}! 👋
        </p>

        <p style="font-size:15px;line-height:1.7;color:#444444;margin-bottom:12px;">
            Tu cuenta en <strong style="color:#1a202c;">Tools365</strong> ha sido creada exitosamente.
            Ya puedes explorar miles de herramientas y maquinaria disponibles para renta,
            compra y subasta en todo México.
        </p>

        {{-- Highlight box --}}
        <div style="background:#f0f4ff;border-left:4px solid #1F3A93;border-radius:8px;padding:16px 20px;margin:24px 0;font-size:14px;color:#1F3A93;">
            🎉 Tu registro fue completado. Haz clic abajo para <strong style="color:#1F3A93;">verificar tu correo</strong> y activar tu cuenta.
        </div>

        {{-- Features --}}
        <table width="100%" cellpadding="0" cellspacing="0" style="margin:28px 0;">
            <tr>
                <td width="40" valign="top" style="padding:8px 12px 8px 0;font-size:20px;">🔧</td>
                <td valign="top" style="padding:8px 0;font-size:14px;color:#555555;">
                    <strong style="display:block;color:#222222;margin-bottom:2px;">Renta herramientas</strong>
                    Por días, semanas o meses sin grandes inversiones.
                </td>
            </tr>
            <tr>
                <td width="40" valign="top" style="padding:8px 12px 8px 0;font-size:20px;">💰</td>
                <td valign="top" style="padding:8px 0;font-size:14px;color:#555555;">
                    <strong style="display:block;color:#222222;margin-bottom:2px;">Compra y vende</strong>
                    Miles de compradores activos te esperan.
                </td>
            </tr>
            <tr>
                <td width="40" valign="top" style="padding:8px 12px 8px 0;font-size:20px;">🔨</td>
                <td valign="top" style="padding:8px 0;font-size:14px;color:#555555;">
                    <strong style="display:block;color:#222222;margin-bottom:2px;">Subastas en vivo</strong>
                    Gana maquinaria industrial a precios increíbles.
                </td>
            </tr>
        </table>

        {{-- Botón --}}
        <div style="text-align:center;margin:32px 0 20px;">
            <a href="{{ $verifyUrl }}"
               style="display:inline-block;background:linear-gradient(135deg,#1F3A93 0%,#2563eb 100%);color:#ffffff !important;text-decoration:none;font-size:16px;font-weight:700;padding:16px 48px;border-radius:50px;letter-spacing:0.3px;font-family:'Segoe UI',Arial,sans-serif;mso-padding-alt:0;">
                <span style="color:#ffffff !important;">Verificar mi correo →</span>
            </a>
        </div>

        <hr style="border:none;border-top:1px solid #eef0f5;margin:28px 0;">

        <p style="font-size:13px;color:#888888;text-align:center;line-height:1.6;">
            Si no creaste esta cuenta, puedes ignorar este correo de forma segura.<br>
            Este enlace te llevará al login de <strong style="color:#555555;">Tools365</strong>.
        </p>
    </div>

    {{-- Footer --}}
    <div style="background:#f8f9fc;padding:24px 40px;text-align:center;font-size:12px;color:#999999;border-top:1px solid #eef0f5;">
        © {{ date('Y') }} Tools365 · Todos los derechos reservados<br>
        <a href="{{ route('inicio') }}" style="color:#1F3A93;text-decoration:none;">tools365.com</a> · México
    </div>

</div>
</body>
</html>