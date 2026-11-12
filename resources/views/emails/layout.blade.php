<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <title>@yield('titulo', 'FONCALDAS')</title>
</head>
{{-- Plantilla de todos los correos. Solo tablas y estilos en línea: es lo único que respetan Gmail, Outlook y los celulares.
     Secciones: etiqueta, icono (emoji), color (acento del aviso), titular, bajada, contenido, siguiente (¿Qué sigue?), boton y boton_url. --}}
<body style="margin:0; padding:0; background:#E8EEF5; font-family:'Segoe UI', Arial, Helvetica, sans-serif; color:#0F172A; -webkit-text-size-adjust:100%;">
    {{-- Texto de vista previa que muestra la bandeja de entrada junto al asunto --}}
    <div style="display:none; max-height:0; overflow:hidden; opacity:0;">@yield('bajada')</div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#E8EEF5; padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="width:100%; max-width:600px; background:#FFFFFF; border-radius:24px; overflow:hidden; box-shadow:0 10px 30px rgba(11,72,112,.12);">

                    {{-- Encabezado de marca --}}
                    <tr>
                        <td align="center" bgcolor="#0B4870" style="background:#0B4870; background-image:linear-gradient(135deg,#0B4870 0%,#0C67A3 55%,#0B8B7B 100%); padding:28px 24px 30px;">
                            <img src="{{ $message->embed(public_path('images/email-logo.png')) }}" alt="FONCALDAS" width="150" style="display:block; width:150px; height:auto; border:0;">
                            <div style="margin-top:12px; display:inline-block; padding:5px 14px; border-radius:999px; background:rgba(255,255,255,.15); font-size:11px; font-weight:700; letter-spacing:2px; text-transform:uppercase; color:#FACC15;">@yield('etiqueta', 'Fondo de Empleados')</div>
                        </td>
                    </tr>
                    {{-- Franja con los tres colores del logo --}}
                    <tr>
                        <td style="font-size:0; line-height:0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"><tr>
                                <td width="34%" height="5" bgcolor="#FACC15" style="background:#FACC15; height:5px;"></td>
                                <td width="33%" height="5" bgcolor="#0B8B7B" style="background:#0B8B7B; height:5px;"></td>
                                <td width="33%" height="5" bgcolor="#0C67A3" style="background:#0C67A3; height:5px;"></td>
                            </tr></table>
                        </td>
                    </tr>

                    {{-- Aviso principal: ícono grande, titular y una frase que lo explica --}}
                    <tr>
                        <td align="center" style="padding:34px 32px 6px;">
                            <table role="presentation" cellpadding="0" cellspacing="0"><tr>
                                <td align="center" valign="middle" width="76" height="76" style="width:76px; height:76px; border-radius:999px; background:@yield('color', '#E0EEF8'); font-size:38px; line-height:76px;">@yield('icono', '📩')</td>
                            </tr></table>
                            <h1 style="margin:18px 0 8px; font-size:26px; line-height:1.25; font-weight:800; color:#0B4870;">@yield('titular')</h1>
                            <p style="margin:0; font-size:16px; line-height:1.6; color:#475569;">@yield('bajada')</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:26px 32px 8px; font-size:15px; line-height:1.6; color:#334155;">
                            @yield('contenido')
                        </td>
                    </tr>

                    {{-- ¿Qué sigue? --}}
                    @hasSection('siguiente')
                    <tr>
                        <td style="padding:8px 32px 6px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#FEF9E7; border:2px solid #FACC15; border-radius:16px;">
                                <tr>
                                    <td width="46" valign="top" style="padding:16px 0 16px 16px; font-size:24px; line-height:1;">👉</td>
                                    <td style="padding:14px 18px 14px 10px; font-size:15px; line-height:1.55; color:#422006;">
                                        <strong style="display:block; font-size:16px; color:#0F172A; margin-bottom:2px;">¿Qué sigue?</strong>
                                        @yield('siguiente')
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    @endif

                    @hasSection('boton')
                    <tr>
                        <td align="center" style="padding:26px 32px 34px;">
                            <table role="presentation" cellpadding="0" cellspacing="0"><tr>
                                <td align="center" bgcolor="#0B4870" style="border-radius:999px; background:#0B4870; box-shadow:0 6px 16px rgba(11,72,112,.3);">
                                    <a href="@yield('boton_url')" style="display:inline-block; padding:15px 34px; font-size:16px; font-weight:700; color:#FFFFFF; text-decoration:none; border-radius:999px;">@yield('boton') &nbsp;→</a>
                                </td>
                            </tr></table>
                        </td>
                    </tr>
                    @endif

                    {{-- Pie: datos de contacto --}}
                    <tr>
                        <td style="background:#0B1220; padding:26px 32px; text-align:center;">
                            <div style="font-size:15px; font-weight:800; color:#FFFFFF;">FONCALDAS <span style="font-weight:400; color:#94A3B8;">· Fondo de Empleados</span></div>
                            <div style="margin-top:12px; font-size:13px; line-height:1.9; color:#CBD5E1;">
                                📍 Calle 60 #25-01, Manizales<br>
                                🕗 Lunes a viernes · 8:00 a. m. – 12:00 p. m. y 1:00 p. m. – 4:00 p. m.<br>
                                💬 WhatsApp <a href="https://wa.me/573148330328" style="color:#FACC15; text-decoration:none; font-weight:700;">314 833 0328</a>
                            </div>
                            <div style="margin-top:16px; font-size:11px; color:#64748B;">Este es un mensaje automático, por favor no respondas a este correo.</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
