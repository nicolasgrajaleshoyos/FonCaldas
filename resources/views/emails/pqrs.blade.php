<!DOCTYPE html>
<html lang="es">
<body style="font-family: Arial, sans-serif; color: #0F172A;">
    <h2 style="color:#0B4870;">FONCALDAS</h2>
    <p>{{ $mensaje }}</p>
    <p><strong>Código:</strong> {{ $pqrs->codigo }}<br>
       <strong>Tipo:</strong> {{ $pqrs->tipoLabel() }}<br>
       <strong>Estado:</strong> {{ $pqrs->estadoLabel() }}</p>
    @if($pqrs->respuesta)
        <p><strong>Respuesta:</strong></p>
        <p style="white-space: pre-line;">{{ $pqrs->respuesta }}</p>
    @endif
    <p style="margin-top:24px; font-size:12px; color:#64748B;">Este es un mensaje automático, por favor no respondas a este correo.</p>
</body>
</html>
