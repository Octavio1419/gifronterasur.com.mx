<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #2d3748; line-height: 1.6; background-color: #f7fafc; margin: 0; padding: 0; }
        .wrapper { width: 100%; padding: 40px 0; background-color: #f7fafc; }
        .contenedor { width: 90%; max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; }
        .header { background-color: #ffffff; text-align: center; padding: 30px 20px; border-bottom: 4px solid #38a169; }
        .content { padding: 40px; }
        h2 { color: #1a202c; font-size: 24px; margin-top: 0; font-weight: 700; }
        .intro-text { color: #4a5568; font-size: 16px; margin-bottom: 25px; }
        
        /* Tabla de detalles */
        .tabla-info { width: 100%; border-collapse: collapse; margin-bottom: 30px; background-color: #f0fff4; border-radius: 6px; }
        .tabla-info td { padding: 12px 15px; border-bottom: 1px solid #c6f6d5; font-size: 15px; }
        .label { font-weight: bold; color: #2f855a; text-transform: uppercase; font-size: 12px; width: 35%; }
        .value { color: #22543d; font-weight: 600; }

        .monto-destacado { font-size: 20px; color: #2f855a; font-weight: bold; }
        
        .boton-container { text-align: center; margin: 35px 0; }
        .boton { background-color: #38a169; color: #ffffff !important; padding: 14px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; font-size: 16px; transition: background 0.3s ease; }
        
        .footer { background-color: #f1f5f9; font-size: 12px; color: #64748b; text-align: center; padding: 25px; line-height: 1.5; }
        .footer strong { color: #475569; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="contenedor">
            <div class="header">
                {{-- Logo del sistema --}}
                <img src="{{ $message->embed(public_path('img/vss_logo.jpeg')) }}" width="180" alt="VSS LOGO">
            </div>
            <div class="content">
                <h2>¡Buenas noticias, {{ $chofer_nombre }}! ✅</h2>
                <p class="intro-text">Tu <strong>Solicitud de Combustible</strong> ha sido pre-autorizada satisfactoriamente.En las proximas horas estara disperso en tu cuenta.</p>

                <table class="tabla-info">
                    <tr>
                        <td class="label">🔢 Folio Solicitud</td>
                        <td class="value">#{{ $solicitud->id }}</td>
                    </tr>
                    <tr>
                        <td class="label">🚚 Vehículo</td>
                        <td class="value">{{ $nombre_carro }}</td>
                    </tr>
                    <tr>
                        <td class="label">🎫 Folio Autorización</td>
                        <td class="value">{{ $efecticard }}</td>
                    </tr>
                    <tr>
                        <td class="label">💵 Monto Autorizado</td>
                        <td class="value"><span class="monto-destacado">${{ number_format($solicitud->monto_real_cargado, 2) }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">📝 Notas Admin</td>
                        <td class="value"><em>{{ $notas }}</em></td>
                    </tr>
                </table>

                <div class="boton-container">
                    <a href="{{ url('/login') }}" class="boton">Ver mi historial</a>
                </div>
                <div style="font-size: 13px; color: #4a5568; text-align: center; padding: 15px; border-top: 1px solid #edf2f7; background-color: #f8fafc; border-radius: 0 0 10px 10px;">
                    <strong style="color: #2d3748; display: block; margin-bottom: 8px;">
                        <i class="fas fa-clock"></i> TIEMPOS DE DISPERSIÓN
                    </strong>

                    <div style="margin-bottom: 10px;">
                        <span style="background: #d1fae5; color: #065f46; padding: 2px 8px; border-radius: 4px; font-weight: bold;">TODOS LOS JUEVES</span>
                    </div>

                    <div style="border-top: 1px dashed #cbd5e1; pt: 10px;">
                        <p style="margin: 8px 0; color: #718096; font-style: italic;">
                           <i class="fas fa-info-circle" style="color: #3056d3;"></i> <strong>Recordatorio de Solicitudes:</strong><br>
                        Para garantizar la dispersión en <strong>menos de 24 horas</strong>, realiza tu solicitud los días <strong>jueves a primera hora</strong>. 
                        Las solicitudes realizadas en días distintos se acreditarán en un lapso de <strong>36 horas</strong> (salvo casos de urgencia justificada).
                        </p>
                    </div>
                    </div>

                <p style="font-size: 14px; color: #718096; text-align: center;">Por favor, solicita tu ticket de carga y verifica que el monto coincida con lo autorizado.</p>
            </div>

            <div class="footer">
                <strong>© {{ date('Y') }} GRUPO INDUSTRIAL FRONTERA SUR S.A. DE C.V.</strong><br>
                📍 Bugambilia, Plan de Ayala Ampliación Sur, 29020 Tuxtla Gutiérrez, Chis.<br>
                <span style="margin-top: 10px; display: block; border-top: 1px solid #e2e8f0; padding-top: 10px;">
                    DEPARTAMENTO DE SISTEMAS
                </span>
            </div>
        </div>
    </div>
</body>
</html>