@extends('emails.layout')

@section('titulo', 'Solicitud '.$solicitud->codigo)
@section('etiqueta', 'Panel administrativo')

@section('contenido')
    <h1 style="margin:0 0 8px; font-size:22px; color:#0B4870;">{{ $reasignada ? 'Se te asignó una solicitud' : 'Nueva solicitud recibida' }}</h1>
    <p style="margin:0 0 20px;">{{ $reasignada ? 'Un asociado necesita tu atención. Por favor revisa y gestiona esta solicitud:' : 'Un asociado acaba de radicar un trámite. Por favor ingresa al panel y verifica la información:' }}</p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F1F6FB; border:1px solid #D6E4F0; border-radius:12px; margin-bottom:20px;">
        <tr>
            <td style="padding:20px 22px;">
                <div style="font-size:11px; letter-spacing:1px; text-transform:uppercase; color:#64748B;">Código</div>
                <div style="font-size:24px; font-weight:700; color:#0C67A3; margin-bottom:14px;">{{ $solicitud->codigo }}</div>

                <div style="font-size:11px; letter-spacing:1px; text-transform:uppercase; color:#64748B;">Trámite</div>
                <div style="font-size:16px; font-weight:600; color:#0F172A; margin-bottom:14px;">{{ $solicitud->tramiteTipo->nombre }}</div>

                <div style="font-size:11px; letter-spacing:1px; text-transform:uppercase; color:#64748B;">Asociado</div>
                <div style="font-size:16px; font-weight:600; color:#0F172A;">{{ $solicitud->asociado_nombre }}</div>
                <div style="font-size:13px; color:#64748B;">Documento {{ $solicitud->asociado_documento }}</div>
            </td>
        </tr>
    </table>
@endsection

@section('boton', 'Revisar solicitud')
@section('boton_url', route('admin.solicitudes.show', $solicitud))
