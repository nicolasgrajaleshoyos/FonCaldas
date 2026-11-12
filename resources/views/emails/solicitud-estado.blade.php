@extends('emails.layout')

@php
    $nombre = \Illuminate\Support\Str::ucfirst(mb_strtolower(\Illuminate\Support\Str::before(trim($solicitud->asociado_nombre), ' ') ?: $solicitud->asociado_nombre));
    $tramite = $solicitud->tramiteTipo->nombre;
    $paso = match ($solicitud->estado) { 'recibida' => 1, 'en_revision' => 2, default => 3 };
    $rechazada = $solicitud->estado === 'rechazada';

    // [emoji, color del círculo, titular, bajada, qué sigue]
    [$icono, $color, $titular, $bajada, $siguiente] = match ($solicitud->estado) {
        'recibida' => ['📥', '#FEF3C7', "¡Hola, {$nombre}! Recibimos tu solicitud", "Tu trámite de {$tramite} ya está en manos de nuestro equipo.", 'Primero verificaremos tu identidad y luego revisaremos tu trámite. Te escribiremos a este correo cada vez que avance. <strong>Guarda tu código</strong>: lo necesitas para consultar.'],
        'en_revision' => ['🔍', '#E0EEF8', "{$nombre}, estamos revisando tu solicitud", "Verificamos tu identidad y un funcionario ya está trabajando en tu trámite de {$tramite}.", 'No tienes que hacer nada por ahora. Si nos hace falta algún documento, puedes adjuntarlo desde la consulta de tu solicitud.'],
        'aprobada' => ['🎉', '#D1FAE5', "¡Buenas noticias, {$nombre}! Tu solicitud fue aprobada", "Revisamos tu trámite de {$tramite} y fue aprobado.", 'Entra a consultar tu solicitud: allí encontrarás la respuesta o el documento que preparamos para ti.'],
        'rechazada' => ['📝', '#FFE4E6', "{$nombre}, tu solicitud no pudo continuar", "Revisamos tu trámite de {$tramite}, pero no fue posible aprobarlo.", 'Lee el motivo arriba. Puedes radicar una nueva solicitud corrigiendo lo indicado, o escribirnos por WhatsApp si tienes dudas.'],
        default => ['✅', '#D1FAE5', "{$nombre}, tu solicitud está finalizada", "El proceso de tu trámite de {$tramite} terminó.", 'Si necesitas algo más, puedes radicar una nueva solicitud cuando quieras.'],
    };
@endphp

@section('titulo', 'Tu solicitud '.$solicitud->codigo)
@section('etiqueta', 'Tu solicitud')
@section('icono', $icono)
@section('color', $color)
@section('titular', $titular)
@section('bajada', $bajada)

@section('contenido')
    @include('emails.pasos', [
        'pasos' => [['Recibida', '📥'], ['En revisión', '🔍'], [$paso === 3 ? $solicitud->estadoLabel() : 'Resultado', $rechazada ? '✕' : '🏆']],
        'actual' => $paso,
        'colorFinal' => $rechazada ? '#E11D48' : '#10B981',
    ])

    {{-- Código, bien grande y fácil de copiar --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:18px; border:2px dashed #0C67A3; border-radius:16px;">
        <tr>
            <td align="center" style="padding:18px 16px;">
                <div style="font-size:12px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:#64748B;">🎫 Tu código de solicitud</div>
                <div style="margin-top:4px; font-family:Consolas, 'Courier New', monospace; font-size:30px; font-weight:800; letter-spacing:2px; color:#0C67A3;">{{ $solicitud->codigo }}</div>
                <div style="margin-top:4px; font-size:13px; color:#64748B;">Úsalo junto con tu número de documento para consultar.</div>
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:18px; background:#F8FAFC; border-radius:16px;">
        <tr>
            <td style="padding:6px 18px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:15px;">
                    <tr><td style="padding:10px 0; color:#64748B; border-bottom:1px solid #E2E8F0;">📋 Trámite</td><td align="right" style="padding:10px 0; font-weight:700; color:#0F172A; border-bottom:1px solid #E2E8F0;">{{ $tramite }}</td></tr>
                    <tr><td style="padding:10px 0; color:#64748B; border-bottom:1px solid #E2E8F0;">📅 Radicada</td><td align="right" style="padding:10px 0; font-weight:700; color:#0F172A; border-bottom:1px solid #E2E8F0;">{{ $solicitud->created_at->format('d/m/Y') }}</td></tr>
                    <tr><td style="padding:10px 0; color:#64748B;">🚦 Estado</td><td align="right" style="padding:10px 0; font-weight:800; color:{{ $rechazada ? '#BE123C' : '#0B4870' }};">{{ $solicitud->estadoLabel() }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    @if($rechazada && $solicitud->justificacion)
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:18px; background:#FFF1F2; border-left:5px solid #E11D48; border-radius:12px;">
            <tr><td style="padding:14px 18px; font-size:15px; color:#881337;"><strong style="display:block; margin-bottom:2px;">Motivo</strong>{{ $solicitud->justificacion }}</td></tr>
        </table>
    @endif
@endsection

@section('siguiente'){!! $siguiente !!}@endsection

@section('boton', 'Consultar mi solicitud')
@section('boton_url', route('solicitudes.consultar'))
