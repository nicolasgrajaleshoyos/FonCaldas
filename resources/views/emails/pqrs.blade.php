@extends('emails.layout')

@php
    $tipo = $pqrs->tipoLabel();
    $paso = match ($pqrs->estado) { 'recibida' => 1, 'en_tramite' => 2, default => 3 };
    $emojiTipo = ['peticion' => '📄', 'queja' => '⚡', 'reclamo' => '🛡️', 'sugerencia' => '💡'][$pqrs->tipo] ?? '📄';

    // El administrador recibe el aviso de la nueva PQRS; el asociado, su confirmación y los cambios de estado.
    if ($paraAdmin) {
        [$icono, $color, $titular, $bajada] = ['🔔', '#FEF3C7', "Llegó una nueva {$tipo}", $mensaje];
    } else {
        $nombre = \Illuminate\Support\Str::ucfirst(mb_strtolower(\Illuminate\Support\Str::before(trim($pqrs->nombre), ' ') ?: $pqrs->nombre));
        [$icono, $color, $titular, $siguiente] = match ($pqrs->estado) {
            'recibida' => ['📥', '#FEF3C7', "¡Hola, {$nombre}! Recibimos tu ".mb_strtolower($tipo), 'Nuestro equipo la revisará y te responderá a este correo. <strong>Guarda tu código</strong>: con él y tu documento puedes consultarla.'],
            'en_tramite' => ['🔍', '#E0EEF8', "{$nombre}, estamos trabajando en tu caso", 'No tienes que hacer nada. Te avisaremos por este medio en cuanto tengamos la respuesta.'],
            default => ['💬', '#D1FAE5', "{$nombre}, ¡ya te respondimos!", 'Arriba encontrarás nuestra respuesta. Si tienes más dudas, puedes radicar una nueva PQRS o escribirnos por WhatsApp.'],
        };
        $bajada = $mensaje;
    }
@endphp

@section('titulo', 'PQRS '.$pqrs->codigo)
@section('etiqueta', $paraAdmin ? 'Panel administrativo' : 'Peticiones, quejas y reclamos')
@section('icono', $icono)
@section('color', $color)
@section('titular', $titular)
@section('bajada', $bajada)

@section('contenido')
    @unless($paraAdmin)
        @include('emails.pasos', ['pasos' => [['Recibida', '📥'], ['En trámite', '🔍'], ['Respondida', '💬']], 'actual' => $paso])
    @endunless

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:18px; background:#F1F6FB; border:1px solid #D6E4F0; border-radius:16px;">
        <tr>
            <td style="padding:20px 22px;">
                <div style="font-size:12px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:#64748B;">🎫 Código</div>
                <div style="font-family:Consolas, 'Courier New', monospace; font-size:26px; font-weight:800; letter-spacing:1px; color:#0C67A3;">{{ $pqrs->codigo }}</div>

                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:14px; font-size:15px;">
                    <tr><td style="padding:10px 0; color:#64748B; border-top:1px solid #D6E4F0;">{{ $emojiTipo }} Tipo</td><td align="right" style="padding:10px 0; font-weight:700; color:#0F172A; border-top:1px solid #D6E4F0;">{{ $tipo }}</td></tr>
                    <tr><td style="padding:10px 0; color:#64748B; border-top:1px solid #D6E4F0;">✏️ Asunto</td><td align="right" style="padding:10px 0; font-weight:700; color:#0F172A; border-top:1px solid #D6E4F0;">{{ $pqrs->asunto }}</td></tr>
                    @if($paraAdmin)
                        <tr><td style="padding:10px 0; color:#64748B; border-top:1px solid #D6E4F0;">👤 Asociado</td><td align="right" style="padding:10px 0; font-weight:700; color:#0F172A; border-top:1px solid #D6E4F0;">{{ $pqrs->nombre }}</td></tr>
                    @endif
                    <tr><td style="padding:10px 0; color:#64748B; border-top:1px solid #D6E4F0;">🚦 Estado</td><td align="right" style="padding:10px 0; font-weight:800; color:#0B4870; border-top:1px solid #D6E4F0;">{{ $pqrs->estadoLabel() }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    @if($pqrs->respuesta && ! $paraAdmin)
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:18px; background:#ECFDF5; border:2px solid #10B981; border-radius:16px;">
            <tr>
                <td style="padding:18px 20px;">
                    <div style="font-size:16px; font-weight:800; color:#065F46;">💬 Respuesta de FONCALDAS</div>
                    <p style="margin:8px 0 0; font-size:15px; line-height:1.6; color:#0F172A; white-space:pre-line;">{{ $pqrs->respuesta }}</p>
                </td>
            </tr>
        </table>
    @endif
@endsection

@if(! $paraAdmin)
    @section('siguiente'){!! $siguiente !!}@endsection
@endif

@section('boton', $paraAdmin ? 'Revisar en el panel' : 'Consultar mi PQRS')
@section('boton_url', $paraAdmin ? route('admin.pqrs.show', $pqrs) : route('pqrs.consultar'))
