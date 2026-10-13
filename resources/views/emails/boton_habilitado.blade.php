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
            background-color: #37cc0a;
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
            <h2 style="margin:0;">SOLICITUD EXTRAORDINARIA APROBADA</h2>
            <p style="margin:5px 0 0 0;">Su solicitud ha sido aprobada y el botón de combustible ha sido habilitado para el chofer.</p>
        </div>

        <div class="content">
            <p>Hola, <strong>{{ $chofer->nombre }}</strong>,</p>
            <p>Se ha aprobado tu solicitud, el boton de (solicitud de combustible) ha sido habilitado para ti.</p>
      
            <div style="text-align: center;">
                <a href="{{ url('/login') }}" class="button">
                    Solicitar Combustible
                </a>
            </div>
            
            <p style="margin-top:25px; font-size: 13px; color: #666;">
               Recuerda que esta habilitación es temporal y solo para la emergencia descrita. Por favor, asegúrate de realizar tu solicitud de combustible lo antes posible.
            </p>
        </div>

        <div class="footer">
       
            <p>&copy; {{ date('Y') }} GRUPO INDUSTRIAL FRONTERA SUR</p>
        </div>
    </div>
</body>
</html>