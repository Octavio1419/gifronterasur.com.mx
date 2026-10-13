<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Arial', sans-serif; color: #333; background-color: #f4f4f4; }
        .container { width: 90%; max-width: 600px; margin: 20px auto; background: #fff; border-radius: 10px; overflow: hidden; border: 1px solid #ddd; }
        .header { background: #1e293b; color: white; padding: 20px; text-align: center; }
        .content { padding: 30px; }
        .info-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .info-table td { padding: 10px; border-bottom: 1px solid #eee; }
        .label { font-weight: bold; color: #666; width: 40%; font-size: 12px; text-transform: uppercase; }
        .value { font-weight: bold; color: #1e293b; }
        .price { color: #10b981; font-size: 20px; }
        .btn { display: inline-block; padding: 15px 25px; background: #3b82f6; color: white !important; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 20px; }
        .footer { background: #f9f9f9; padding: 20px; text-align: center; font-size: 11px; color: #999; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin:0;">NUEVA COTIZACIÓN</h2>
        </div>
        <div class="content">
            <p>Se ha recibido una nueva cotización para el reporte de falla <strong>#{{ $incidencia->id }}</strong>.</p>
            
            <table class="info-table">
                <tr>
                    <td class="label">👤 Chofer:</td>
                    <td class="value">{{ $chofer->nombre }} {{ $chofer->apellido_p }}</td>
                </tr>
                <tr>
                    <td class="label">🚚 Vehículo:</td>
                    <td class="value">{{ $nombre_carro }} ({{ $vehiculo->placas }})</td>
                </tr>
                <tr>
                    <td class="label">🛠️ Falla:</td>
                    <td class="value">{{ $incidencia->titulo_reporte }}</td>
                </tr>
                <tr>
                    <td class="label">💰 Monto Reportado:</td>
                    <td class="value price">${{ number_format($incidencia->monto_cotizado, 2) }}</td>
                </tr>
            </table>

            <p style="font-size: 13px; color: #666;">El archivo original (PDF o Imagen) ha sido adjuntado a este correo electrónico.</p>

            <div style="text-align: center;">
                <a href="{{ url('/incidencias/fallas/reportadas') }}" class="btn">REVISAR EN EL PANEL</a>
            </div>
        </div>
        <div class="footer">
            SISTEMA DE GESTIÓN VEHICULAR - FRONTERA SUR<br>
            © {{ date('Y') }}
        </div>
    </div>
</body>
</html>