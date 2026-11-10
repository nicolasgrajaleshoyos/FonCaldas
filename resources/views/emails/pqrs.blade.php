@extends('emails.layout')

@section('titulo', 'PQRS '.$pqrs->codigo)
@section('etiqueta', 'Peticiones, quejas y reclamos')

@section('contenido')
    <p style="margin:0 0 20px; font-size:16px;">{{ $mensaje }}</p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F1F6FB; border:1px solid #D6E4F0; border-radius:12px; margin-bottom:20px;">
        <tr>
            <td style="padding:20px 22px;">
                <div style="font-size:11px; letter-spacing:1px; text-transform:uppercase; color:#64748B;">Código</div>
                <div style="font-size:24px; font-weight:700; color:#0C67A3; margin-bottom:14px;">{{ $pqrs->codigo }}</div>

                <div style="font-size:11px; letter-spacing:1px; text-transform:uppercase; color:#64748B;">Tipo</div>
                <div style="font-size:16px; font-weight:600; margin-bottom:14px;">{{ $pqrs->tipoLabel() }}</div>

                <div style="font-size:11px; letter-spacing:1px; text-transform:uppercase; color:#64748B;">Estado</div>
                <div style="font-size:16px; font-weight:600; color:#0B4870;">{{ $pqrs->estadoLabel() }}</div>
            </td>
        </tr>
    </table>

    @if($pqrs->respuesta)
        <div style="font-size:11px; letter-spacing:1px; text-transform:uppercase; color:#64748B; margin-bottom:6px;">Respuesta</div>
        <p style="margin:0 0 20px; padding:14px 16px; background:#F8FAFC; border-left:4px solid #0C67A3; border-radius:6px; white-space:pre-line;">{{ $pqrs->respuesta }}</p>
    @endif
@endsection

@section('boton', 'Consultar mi PQRS')
@section('boton_url', route('pqrs.consultar'))
