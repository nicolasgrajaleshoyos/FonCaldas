<!DOCTYPE html>
<html lang="es">
<body style="font-family: Arial, sans-serif; color: #0F172A;">
    <h2 style="color:#0B4870;">FONCALDAS - Panel Administrativo</h2>
    <p>{{ $reasignada ? 'Se te asignó la siguiente solicitud:' : 'Ha llegado una nueva solicitud:' }}</p>
    <ul>
        <li><strong>Código:</strong> {{ $solicitud->codigo }}</li>
        <li><strong>Trámite:</strong> {{ $solicitud->tramiteTipo->nombre }}</li>
        <li><strong>Asociado:</strong> {{ $solicitud->asociado_nombre }} ({{ $solicitud->asociado_documento }})</li>
    </ul>
    <p>Ingresa al panel administrativo para revisarla.</p>
</body>
</html>
