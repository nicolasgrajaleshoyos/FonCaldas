@extends('emails.layout')

@section('titulo', 'Tu solicitud '.$solicitud->codigo)
@section('etiqueta', 'Tu solicitud')

@section('contenido')
    <h1 style="margin:0 0 8px; font-size:22px; color:#0B4870;">Hola, {{ $solicitud->asociado_nombre }}</h1>

    @if($solicitud->estado === 'recibida')
        <p style="margin:0 0 20px;">Recibimos tu solicitud de <strong>{{ $solicitud->tramiteTipo->nombre }}</strong>. Guarda este código para consultar su estado en cualquier momento:</p>
    @else
        <p style="margin:0 0 20px;">El estado de tu solicitud de <strong>{{ $solicitud->tramiteTipo->nombre }}</strong> cambió:</p>
    @endif

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F1F6FB; border:1px solid #D6E4F0; border-radius:12px; margin-bottom:20px;">
        <tr>
            <td align="center" style="padding:20px 22px;">
                <div style="font-size:11px; letter-spacing:1px; text-transform:uppercase; color:#64748B;">Código de solicitud</div>
                <div style="font-size:26px; font-weight:700; color:#0C67A3; letter-spacing:1px;">{{ $solicitud->codigo }}</div>
                @if($solicitud->estado !== 'recibida')
                    <div style="margin-top:14px; font-size:11px; letter-spacing:1px; text-transform:uppercase; color:#64748B;">Estado actual</div>
                    <div style="font-size:18px; font-weight:700; color:#0B4870;">{{ $solicitud->estadoLabel() }}</div>
                @endif
            </td>
        </tr>
    </table>

    @if($solicitud->estado === 'rechazada' && $solicitud->justificacion)
        <p style="margin:0 0 20px; padding:14px 16px; background:#FEF2F2; border-left:4px solid #DC2626; border-radius:6px; color:#7F1D1D;"><strong>Motivo:</strong> {{ $solicitud->justificacion }}</p>
    @endif

    <p style="margin:0 0 8px;">Para ver el detalle completo ingresa tu número de documento y el código de la solicitud en el portal.</p>
@endsection

@section('boton', 'Consultar mi solicitud')
@section('boton_url', route('solicitudes.consultar'))
