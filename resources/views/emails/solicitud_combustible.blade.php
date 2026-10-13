<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #2d3748; line-height: 1.6; background-color: #f7fafc; margin: 0; padding: 0; }
        .wrapper { width: 100%; padding: 40px 0; background-color: #f7fafc; }
        .contenedor { width: 90%; max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; }
        .header { background-color: #ffffff; text-align: center; padding: 30px 20px; border-bottom: 4px solid #2d3748; }
        .content { padding: 40px; }
        h2 { color: #1a202c; font-size: 24px; margin-top: 0; font-weight: 700; }
        .intro-text { color: #4a5568; font-size: 16px; margin-bottom: 25px; }
        
        /* Tabla de detalles */
        .tabla-info { width: 100%; border-collapse: collapse; margin-bottom: 30px; background-color: #f8fafc; border-radius: 6px; }
        .tabla-info td { padding: 12px 15px; border-bottom: 1px solid #edf2f7; font-size: 15px; }
        .label { font-weight: bold; color: #718096; text-transform: uppercase; font-size: 12px; width: 35%; }
        .value { color: #2d3748; font-weight: 600; }

        .monto-destacado { font-size: 20px; color: #2f855a; font-weight: bold; }
        
        .boton-container { text-align: center; margin: 35px 0; }
        .boton { background-color: #2d3748; color: #ffffff !important; padding: 14px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; font-size: 16px; transition: background 0.3s ease; }
        
        .footer { background-color: #f1f5f9; font-size: 12px; color: #64748b; text-align: center; padding: 25px; line-height: 1.5; }
        .footer strong { color: #475569; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="contenedor">
            <div class="header">
                {{-- Logo con URL absoluta para que siempre cargue --}}
                <img src="{{ asset('img/vss_logo.jpeg') }}" width="180" alt="VSS LOGO">
            </div>
            <div class="content">
                <h2>¡Hola, {{ $admin_nombre }}! 👋</h2>
                <p class="intro-text">Se ha recibido una nueva <strong>Solicitud de Combustible</strong> que requiere tu revisión en el panel administrativo.</p>

                <table class="tabla-info">
                    <tr>
                        <td class="label">👤 Chofer</td>
                        <td class="value">{{ $chofer->nombre }} {{ $chofer->apellido_p }}</td>
                    </tr>
                    <tr>
                        <td class="label">🚚 Vehículo</td>
                        <td class="value">{{ $nombre_carro }}</td>
                    </tr>
                    <tr>
                        <td class="label">💳 Efecticard</td>
                        <td class="value">{{ $efecticard }}</td>
                    </tr>
                    <tr>
                        <td class="label">💵 Monto</td>
                        <td class="value"><span class="monto-destacado">${{ number_format($solicitud['monto_solicitado'], 2) }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">📝 Notas</td>
                        <td class="value"><em>{{ $notas }}</em></td>
                    </tr>
                </table>

                <div class="boton-container">
                    <a href="{{ url('/') }}" class="boton">Ver detalles</a>
                </div>
                
                <p style="font-size: 14px; color: #a0aec0; text-align: center;">Este es un mensaje automático, por favor no respondas a este correo.</p>
            </div>

            <div class="footer">
                <strong>© {{ date('Y') }} GRUPO INDUSTRIAL FRONTERA SUR S.A. DE C.V.</strong><br>
                📍 Bugambilia, Plan de Ayala Ampliación Sur, 29020 Tuxtla Gutiérrez, Chis.<br>
                <span style="margin-top: 10px; display: block; border-top: 1px solid #e2e8f0; padding-top: 10px;">
                    Desarrollado por: <strong>GIFRONTERASUR.COM.MX</strong>
                </span>
            </div>
        </div>
    </div>
</body>
</html>