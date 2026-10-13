<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #d9534f;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 30px;
            color: #333333;
            line-height: 1.6;
        }
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #d9534f;
            padding: 15px;
            margin: 20px 0;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
        }
        .details-table td {
            padding: 10px 0;
            border-bottom: 1px solid #eeeeee;
        }
        .label {
            font-weight: bold;
            color: #555555;
            width: 40%;
        }
        .footer {
            background-color: #f1f1f1;
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #777777;
        }
        .button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #ffc558;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
             <img src="{{ asset('img/vss_logo.jpeg') }}" width="180" alt="VSS LOGO">
            <h2 style="margin:0;">⚠️ SOLICITUD EXTRAORDINARIA</h2>
            <p style="margin:5px 0 0 0;">Combustible fuera de horario</p>
        </div>

        <div class="content">
            <p>Hola, <strong>{{ $admin_nombre }}</strong>,</p>
            <p>Se ha recibido una nueva petición de un chofer para habilitar el botón de combustible fuera de tu plazo permitido.</p>
            
            <div class="info-box">
                <h3 style="margin-top:0; color:#d9534f;">Motivo de la Emergencia:</h3>
                <p style="font-style: italic; margin-bottom:0;">"{{ $notas }}"</p>
            </div>

            <table class="details-table">
                <tr>
                    <td class="label">Chofer:</td>
                    <td>{{ $chofer->nombre }} {{ $chofer->apellido_p }}</td>
                </tr>
                <tr>
                    <td class="label">Vehículo:</td>
                    <td>{{ $nombre_carro }}</td>
                </tr>
                <tr>
                    <td class="label">Fecha Solicitud:</td>
                    <td>{{ date('d/m/Y H:i:s') }}</td>
                </tr>
            </table>

            <div style="text-align: center;">
                <a href="{{ url('/login') }}" class="button">
                    Ir al Panel para Autorizar
                </a>
            </div>
            
            <p style="margin-top:25px; font-size: 13px; color: #666;">
                Recuerde que al autorizar, el botón se habilitará únicamente para este chofer y para una sola transacción.
            </p>
        </div>

        <div class="footer">
       
            <p>&copy; {{ date('Y') }} GRUPO INDUSTRIAL FRONTERA SUR</p>
        </div>
    </div>
</body>
</html>