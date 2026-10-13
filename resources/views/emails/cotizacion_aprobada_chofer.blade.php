<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #2d3748; line-height: 1.6; background-color: #f0f4f8; margin: 0; padding: 0; }
        .wrapper { width: 100%; padding: 40px 0; background-color: #f0f4f8; }
        .contenedor { width: 90%; max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; }
        .header { background-color: #ffffff; text-align: center; padding: 30px 20px; border-bottom: 5px solid #38a169; }
        .content { padding: 40px; }
        h2 { color: #2d3748; font-size: 24px; margin-top: 0; font-weight: 700; text-align: center; }
        .status-badge { background-color: #d1fae5; color: #065f46; display: inline-block; padding: 5px 15px; border-radius: 50px; font-weight: bold; font-size: 14px; margin-bottom: 20px; }
        
        /* Tabla de resumen */
        .tabla-info { width: 100%; border-collapse: collapse; margin: 25px 0; background-color: #f9fafb; border-radius: 8px; border: 1px solid #edf2f7; }
        .tabla-info td { padding: 15px; border-bottom: 1px solid #edf2f7; font-size: 15px; }
        .label { font-weight: bold; color: #4a5568; text-transform: uppercase; font-size: 12px; width: 40%; }
        .value { color: #2d3748; font-weight: 600; text-align: right; }

        .monto-total { font-size: 22px; color: #38a169; font-weight: 800; }
        
        .instrucciones { background-color: #fffaf0; border-left: 4px solid #f6ad55; padding: 20px; margin: 25px 0; border-radius: 0 8px 8px 0; }
        .instrucciones strong { color: #9c4221; display: block; margin-bottom: 5px; }
        .instrucciones p { margin: 0; font-size: 14px; color: #7b341e; }

        .boton-container { text-align: center; margin-top: 35px; }
        .boton { background-color: #2d3748; color: #ffffff !important; padding: 15px 35px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block; font-size: 16px; }
        
        .footer { background-color: #1a202c; font-size: 11px; color: #a0aec0; text-align: center; padding: 25px; }
        .footer strong { color: #ffffff; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="contenedor">
            <div class="header">
                {{-- Logo del sistema --}}
                @if(file_exists(public_path('img/vss_logo.jpeg')))
                    <img src="{{ $message->embed(public_path('img/vss_logo.jpeg')) }}" width="170" alt="VSS LOGO">
                @else
                    <h2 style="color: #38a169; margin: 0;">FRONTERA SUR</h2>
                @endif
            </div>
            <div class="content">
                <div style="text-align: center;">
                    <span class="status-badge">REPARACIÓN AUTORIZADA</span>
                </div>
                <h2>¡Buenas noticias, {{ $chofer_nombre }}! ✅</h2>
                <p style="text-align: center; color: #4a5568;">La cotización para tu unidad ha sido aprobada. Puedes proceder con el ingreso al taller.</p>

                <table class="tabla-info">
                    <tr>
                        <td class="label">🔢 Folio Reporte</td>
                        <td class="value">#{{ $solicitud->id }}</td>
                    </tr>
                    <tr>
                        <td class="label">🚚 Vehículo</td>
                        <td class="value">{{ $nombre_carro }}</td>
                    </tr>
                    <tr>
                        <td class="label">🛠️ Falla</td>
                        <td class="value">{{ $solicitud->titulo_reporte }}</td>
                    </tr>
                    <tr>
                        <td class="label">💵 Presupuesto Aprobado</td>
                        <td class="value"><span class="monto-total">${{ number_format($solicitud->monto_cotizado, 2) }}</span></td>
                    </tr>
                </table>

                <div class="instrucciones">
                    <strong>⚠️ INDICACIONES IMPORTANTES:</strong>
                    <p>
                        1. Acude al taller y confirma que el presupuesto coincida con lo aprobado.<br>
                        2. Al terminar la reparación, solicita tu comprobante.<br>
                        3. **Indispensable:** Confirma en la aplicación cuando la unidad esté lista para operar.
                    </p>
                </div>

                <div class="boton-container">
                    <a href="{{ url('/login') }}" class="boton">IR A MIS REPORTES</a>
                </div>
            </div>

            <div class="footer">
                <strong>© {{ date('Y') }} GRUPO INDUSTRIAL FRONTERA SUR S.A. DE C.V.</strong><br>
                📍 Tuxtla Gutiérrez, Chiapas.<br>
                <span style="display: block; margin-top: 10px; color: #718096;">Este es un mensaje automático, por favor no respondas a este correo.</span>
            </div>
        </div>
    </div>
</body>
</html>