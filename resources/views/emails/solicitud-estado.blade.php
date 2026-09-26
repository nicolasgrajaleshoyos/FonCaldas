<!DOCTYPE html>
<html lang="es">
<body style="font-family: Arial, sans-serif; color: #0F172A;">
    <h2 style="color:#0B4870;">FONCALDAS</h2>
    <p>Hola {{ $solicitud->asociado_nombre }},</p>
    @if($solicitud->estado === 'recibida')
        <p>Recibimos tu solicitud de <strong>{{ $solicitud->tramiteTipo->nombre }}</strong>. Guarda este código para consultar su estado:</p>
        <p style="font-size: 22px; font-weight: bold; color:#0C67A3;">{{ $solicitud->codigo }}</p>
    @else
        <p>El estado de tu solicitud <strong>{{ $solicitud->codigo }}</strong> ({{ $solicitud->tramiteTipo->nombre }}) cambió a:</p>
        <p style="font-size: 18px; font-weight: bold; color:#0C67A3;">{{ $solicitud->estadoLabel() }}</p>
    @endif
    @if($solicitud->estado === 'rechazada' && $solicitud->justificacion)
        <p><strong>Motivo:</strong> {{ $solicitud->justificacion }}</p>
    @endif
    <p>Puedes consultar el detalle completo ingresando tu número de documento y el código de solicitud en el portal.</p>
    <p style="margin-top:24px; font-size:12px; color:#64748B;">Este es un mensaje automático, por favor no respondas a este correo.</p>
</body>
</html>
