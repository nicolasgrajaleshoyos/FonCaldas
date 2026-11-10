<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'FONCALDAS')</title>
</head>
<body style="margin:0; padding:0; background:#EEF2F7; font-family:'Segoe UI', Arial, Helvetica, sans-serif; color:#0F172A;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#EEF2F7; padding:32px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="width:100%; max-width:600px; background:#FFFFFF; border-radius:16px; overflow:hidden;">
                    {{-- Encabezado con logo (incrustado para que se vea aunque APP_URL no sea público) --}}
                    <tr>
                        <td align="center" style="background:#0B4870; padding:26px 24px 22px;">
                            <img src="{{ $message->embed(public_path('images/email-logo.png')) }}" alt="FONCALDAS" width="150" style="display:block; width:150px; height:auto; border:0;">
                            <div style="margin-top:10px; font-size:12px; letter-spacing:2px; text-transform:uppercase; color:#BBD7EC;">@yield('etiqueta', 'Fondo de Empleados')</div>
                        </td>
                    </tr>
                    <tr><td style="height:4px; background:#F2E90F; font-size:0; line-height:0;">&nbsp;</td></tr>

                    <tr>
                        <td style="padding:32px 36px 12px; font-size:15px; line-height:1.6; color:#334155;">
                            @yield('contenido')
                        </td>
                    </tr>

                    @hasSection('boton')
                    <tr>
                        <td align="center" style="padding:8px 36px 32px;">
                            <a href="@yield('boton_url')" style="display:inline-block; background:#0C67A3; color:#FFFFFF; text-decoration:none; font-weight:600; font-size:15px; padding:13px 30px; border-radius:999px;">@yield('boton')</a>
                        </td>
                    </tr>
                    @endif

                    <tr>
                        <td style="background:#F8FAFC; border-top:1px solid #E2E8F0; padding:20px 36px; font-size:12px; line-height:1.6; color:#64748B; text-align:center;">
                            <strong style="color:#0B4870;">FONCALDAS</strong> · Fondo de Empleados<br>
                            Este es un mensaje automático, por favor no respondas a este correo.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
