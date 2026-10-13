<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #2d3748; line-height: 1.6; background-color: #f4f7f6; margin: 0; padding: 0; }
        .wrapper { width: 100%; padding: 40px 0; background-color: #f4f7f6; }
        .contenedor { width: 90%; max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; }
        .header { background-color: #ffffff; text-align: center; padding: 25px; border-bottom: 5px solid #3182ce; }
        .content { padding: 35px; }
        h2 { color: #2c5282; font-size: 22px; margin-top: 0; font-weight: 700; text-align: center; }
        .intro-text { color: #4a5568; font-size: 16px; margin-bottom: 25px; text-align: center; }
        
        /* Tabla de detalles */
        .tabla-info { width: 100%; border-collapse: collapse; margin-bottom: 25px; background-color: #ebf8ff; border-radius: 8px; }
        .tabla-info td { padding: 15px; border-bottom: 1px solid #bee3f8; font-size: 15px; }
        .label { font-weight: bold; color: #2c5282; text-transform: uppercase; font-size: 11px; width: 40%; }
        .value { color: #2a4365; font-weight: 600; }

        .prioridad-badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; color: white; font-weight: bold; text-transform: uppercase; }
        .bg-alta { background-color: #e53e3e; }
        .bg-media { background-color: #dd6b20; }
        .bg-baja { background-color: #38a169; }
        
        .boton-container { text-align: center; margin: 30px 0; }
        .boton { background-color: #3182ce; color: #ffffff !important; padding: 15px 35px; text-decoration: none; border-radius: 50px; font-weight: bold; display: inline-block; font-size: 16px; box-shadow: 0 4px 6px rgba(49, 130, 206, 0.3); }
        
        .instrucciones { background-color: #fffaf0; border-left: 4px solid #ed8936; padding: 15px; margin-bottom: 25px; border-radius: 0 8px 8px 0; }
        .instrucciones h4 { margin: 0 0 8px 0; color: #9c4221; font-size: 14px; text-transform: uppercase; }
        .instrucciones p { margin: 0; font-size: 13px; color: #7b341e; }

        .footer { background-color: #2d3748; font-size: 11px; color: #cbd5e0; text-align: center; padding: 25px; line-height: 1.6; }
        .footer strong { color: #ffffff; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="contenedor">
            <div class="header">
                {{-- Logo del sistema --}}
                @if(file_exists(public_path('img/vss_logo.jpeg')))
                    <img src="{{ $message->embed(public_path('img/vss_logo.jpeg')) }}" width="160" alt="VSS LOGO">
                @else
                    <h1 style="color: #3182ce; margin: 0;">VSS MATERIALES</h1>
                @endif
            </div>
            <div class="content">
                <h2>¡Atención, {{ $chofer_nombre }}! 🛠️</h2>
                <p class="intro-text">Tu reporte de falla ha sido revisado por el administrador. Se requiere que realices la <strong>cotización</strong> para proceder con la reparación.</p>

                <div class="instrucciones">
                    <h4><i class="fas fa-exclamation-triangle"></i> Próximo paso:</h4>
                    <p>Acude al taller autorizado más cercano, solicita el presupuesto y sube la fotografía o PDF de la cotización a través de tu aplicación móvil.</p>
                </div>

                <table class="tabla-info">
                    <tr>
                        <td class="label">🔢 Folio Incidencia</td>
                        <td class="value">#{{ $solicitud->id }}</td>
                    </tr>
                    <tr>
                        <td class="label">🚚 Unidad</td>
                        <td class="value">{{ $nombre_carro }}</td>
                    </tr>
                    <tr>
                        <td class="label">🚨 Falla Reportada</td>
                        <td class="value">{{ $solicitud->titulo_reporte }}</td>
                    </tr>
                    <tr>
                        <td class="label">🚩 Prioridad</td>
                        <td class="value">
                            <span class="prioridad-badge bg-{{ $solicitud->prioridad }}">
                                {{ $solicitud->prioridad }}
                            </span>
                        </td>
                    </tr>
                </table>

                <div class="boton-container">
                    <a href="{{ url('/login') }}" class="boton">SUBIR COTIZACIÓN AHORA</a>
                </div>

                <p style="font-size: 13px; color: #718096; text-align: center; font-style: italic;">
                    * Recuerda que la unidad no entrará a reparación hasta que la cotización sea aprobada por el departamento administrativo.
                </p>
            </div>

            <div class="footer">
                <strong>© {{ date('Y') }} GRUPO INDUSTRIAL FRONTERA SUR S.A. DE C.V.</strong><br>
                📍 Bugambilia, Plan de Ayala Ampliación Sur, 29020 Tuxtla Gutiérrez, Chis.<br>
                <span style="margin-top: 10px; display: block; border-top: 1px solid #4a5568; padding-top: 10px; color: #a0aec0;">
                    SISTEMA DE CONTROL VEHICULAR - GRUPO INDUSTRIAL FRONTERA SUR
                </span>
            </div>
        </div>
    </div>
</body>
</html>