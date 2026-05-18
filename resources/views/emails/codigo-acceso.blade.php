<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Código de verificación — Ubicatec</title>
</head>
<body style="margin:0;padding:0;background-color:#eef3f7;font-family:'Segoe UI',Arial,sans-serif;-webkit-font-smoothing:antialiased;">

  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef3f7;padding:48px 16px;">
    <tr><td align="center">

      {{-- Contenedor principal --}}
      <table role="presentation" width="540" cellpadding="0" cellspacing="0"
             style="max-width:540px;width:100%;background:#ffffff;border-radius:20px;overflow:hidden;box-shadow:0 8px 40px rgba(0,0,0,0.10);">

        {{-- ══ HEADER ══ --}}
        <tr>
          <td style="background:linear-gradient(135deg,#0f2535 0%,#2c4f60 55%,#376073 100%);padding:36px 48px 32px;text-align:center;position:relative;">

            {{-- Logo icon --}}
            <div style="display:inline-block;background:rgba(255,255,255,0.15);border:1.5px solid rgba(255,255,255,0.3);border-radius:14px;padding:10px 14px;margin-bottom:14px;">
              <img src="https://img.icons8.com/ios-filled/28/ffffff/home.png" alt="Ubicatec" width="24" height="24" style="display:block;">
            </div>

            <div style="font-size:26px;font-weight:800;color:#ffffff;letter-spacing:-0.5px;line-height:1;">Ubicatec</div>
            <div style="font-size:11.5px;color:rgba(255,255,255,0.55);margin-top:5px;letter-spacing:0.5px;">Sistema de gestión del campus universitario</div>

            {{-- Pill "Verificación de acceso" --}}
            <div style="display:inline-block;margin-top:18px;background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.22);border-radius:20px;padding:5px 16px;">
              <span style="font-size:11px;color:rgba(255,255,255,0.85);font-weight:700;letter-spacing:0.8px;text-transform:uppercase;">🔐 Verificación de acceso</span>
            </div>

          </td>
        </tr>

        {{-- ══ BODY ══ --}}
        <tr>
          <td style="padding:40px 48px 32px;">

            {{-- Saludo --}}
            <p style="margin:0 0 6px;font-size:13px;font-weight:700;color:#9ab3c0;text-transform:uppercase;letter-spacing:0.7px;">Hola,</p>
            <h1 style="margin:0 0 18px;font-size:26px;font-weight:800;color:#1e3040;line-height:1.2;">{{ $nombre }} 👋</h1>

            <p style="margin:0 0 28px;font-size:15px;color:#5a7a8a;line-height:1.75;">
              Recibimos una solicitud de inicio de sesión en tu cuenta de <strong style="color:#376073;">Ubicatec</strong>.
              Ingresa el siguiente código para completar el acceso de forma segura:
            </p>

            {{-- ══ CÓDIGO ══ --}}
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
              <tr>
                <td align="center" style="background:linear-gradient(135deg,#f0f6f9,#e4eff5);border:2px dashed #376073;border-radius:16px;padding:32px 24px;">

                  <p style="margin:0 0 10px;font-size:11px;font-weight:700;color:#9ab3c0;text-transform:uppercase;letter-spacing:1px;">Tu código de verificación</p>

                  <div style="font-size:48px;font-weight:900;letter-spacing:14px;color:#376073;font-variant-numeric:tabular-nums;line-height:1;">
                    {{ $codigo }}
                  </div>

                  <div style="margin-top:14px;display:inline-block;background:#fff3e0;border:1px solid #ffe0b2;border-radius:20px;padding:5px 14px;">
                    <span style="font-size:12px;color:#e67e22;font-weight:700;">⏱️ Expira en 5 minutos</span>
                  </div>

                </td>
              </tr>
            </table>

            {{-- Pasos --}}
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
              <tr>
                <td style="background:#f8fbfd;border-radius:12px;padding:20px 24px;">
                  <p style="margin:0 0 12px;font-size:12px;font-weight:700;color:#376073;text-transform:uppercase;letter-spacing:0.6px;">Cómo usarlo</p>
                  <table role="presentation" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="padding:4px 0;">
                        <span style="display:inline-block;width:20px;height:20px;background:#376073;color:#fff;border-radius:50%;font-size:11px;font-weight:800;text-align:center;line-height:20px;margin-right:10px;">1</span>
                        <span style="font-size:13px;color:#5a7a8a;">Regresa a la pantalla de verificación</span>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:4px 0;">
                        <span style="display:inline-block;width:20px;height:20px;background:#376073;color:#fff;border-radius:50%;font-size:11px;font-weight:800;text-align:center;line-height:20px;margin-right:10px;">2</span>
                        <span style="font-size:13px;color:#5a7a8a;">Escribe el código de 6 dígitos</span>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:4px 0;">
                        <span style="display:inline-block;width:20px;height:20px;background:#376073;color:#fff;border-radius:50%;font-size:11px;font-weight:800;text-align:center;line-height:20px;margin-right:10px;">3</span>
                        <span style="font-size:13px;color:#5a7a8a;">Presiona <strong>Verificar</strong> para entrar</span>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

            {{-- Aviso de seguridad --}}
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td style="background:#fde8e8;border-left:3px solid #e74c3c;border-radius:0 10px 10px 0;padding:14px 18px;">
                  <p style="margin:0;font-size:12.5px;color:#c0392b;line-height:1.6;">
                    <strong>⚠️ ¿No fuiste tú?</strong> Si no solicitaste este código, ignora este correo.
                    Tu cuenta permanece segura. No compartas este código con nadie.
                  </p>
                </td>
              </tr>
            </table>

          </td>
        </tr>

        {{-- ══ DIVIDER ══ --}}
        <tr>
          <td style="padding:0 48px;">
            <div style="height:1px;background:linear-gradient(to right,transparent,#dce8ef,transparent);"></div>
          </td>
        </tr>

        {{-- ══ FOOTER ══ --}}
        <tr>
          <td style="background:#f8fbfd;padding:24px 48px;text-align:center;">
            <p style="margin:0 0 6px;font-size:13px;font-weight:700;color:#376073;">Ubicatec</p>
            <p style="margin:0;font-size:11.5px;color:#9ab3c0;line-height:1.7;">
              Este es un correo automático, por favor no respondas.<br>
              © {{ date('Y') }} Ubicatec · Sistema de gestión del campus universitario
            </p>
          </td>
        </tr>

      </table>

      {{-- Texto bajo el correo --}}
      <p style="margin-top:24px;font-size:11px;color:#9ab3c0;text-align:center;">
        Recibiste este correo porque alguien inició sesión con tu cuenta en Ubicatec.
      </p>

    </td></tr>
  </table>

</body>
</html>