@extends('emails.layout')

@section('titulo', 'Solicitud '.$solicitud->codigo)
@section('etiqueta', 'Panel administrativo')
@section('icono', $reasignada ? '🙋' : '🔔')
@section('color', '#FEF3C7')
@section('titular', $reasignada ? 'Te asignaron una solicitud' : 'Llegó una nueva solicitud')
@section('bajada', $reasignada ? 'Ahora eres responsable de este trámite. Un asociado espera tu respuesta.' : 'Un asociado acaba de radicar un trámite y está esperando respuesta.')

@section('contenido')
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:18px; background:#F1F6FB; border:1px solid #D6E4F0; border-radius:16px;">
        <tr>
            <td style="padding:20px 22px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td valign="middle">
                            <div style="font-size:12px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:#64748B;">Código</div>
                            <div style="font-family:Consolas, 'Courier New', monospace; font-size:24px; font-weight:800; color:#0C67A3;">{{ $solicitud->codigo }}</div>
                        </td>
                        <td align="right" valign="middle">
                            <span style="display:inline-block; padding:6px 14px; border-radius:999px; background:#E0F2FE; font-size:13px; font-weight:700; color:#0369A1;">● {{ $solicitud->estadoLabel() }}</span>
                        </td>
                    </tr>
                </table>

                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:14px; font-size:15px;">
                    <tr><td style="padding:10px 0; color:#64748B; border-top:1px solid #D6E4F0;">📋 Trámite</td><td align="right" style="padding:10px 0; font-weight:700; color:#0F172A; border-top:1px solid #D6E4F0;">{{ $solicitud->tramiteTipo->nombre }}</td></tr>
                    <tr><td style="padding:10px 0; color:#64748B; border-top:1px solid #D6E4F0;">👤 Asociado</td><td align="right" style="padding:10px 0; font-weight:700; color:#0F172A; border-top:1px solid #D6E4F0;">{{ $solicitud->asociado_nombre }}</td></tr>
                    <tr><td style="padding:10px 0; color:#64748B; border-top:1px solid #D6E4F0;">🪪 Documento</td><td align="right" style="padding:10px 0; font-weight:700; color:#0F172A; border-top:1px solid #D6E4F0;">{{ $solicitud->asociado_documento }}</td></tr>
                    <tr><td style="padding:10px 0; color:#64748B; border-top:1px solid #D6E4F0;">📅 Radicada</td><td align="right" style="padding:10px 0; font-weight:700; color:#0F172A; border-top:1px solid #D6E4F0;">{{ $solicitud->created_at->format('d/m/Y · g:i a') }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Lo que hay que hacer, en pasos --}}
    <div style="margin:4px 0 10px; font-size:16px; font-weight:800; color:#0F172A;">Qué debes hacer</div>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:15px; color:#334155;">
        @foreach ([
            'Abre la solicitud con el botón de abajo.',
            'Compara el nombre y el documento con la información de FONCALDAS.',
            'Si coinciden, verifica la identidad y gestiona el trámite. Si no, recházala indicando el motivo.',
        ] as $i => $texto)
            <tr>
                <td width="36" valign="top" style="padding:6px 0;">
                    <table role="presentation" cellpadding="0" cellspacing="0"><tr><td align="center" width="26" height="26" style="width:26px; height:26px; border-radius:999px; background:#0C67A3; color:#FFFFFF; font-size:13px; font-weight:800; line-height:26px;">{{ $i + 1 }}</td></tr></table>
                </td>
                <td valign="top" style="padding:8px 0 6px;">{{ $texto }}</td>
            </tr>
        @endforeach
    </table>
@endsection

@section('boton', 'Revisar solicitud')
@section('boton_url', route('admin.solicitudes.show', $solicitud))
