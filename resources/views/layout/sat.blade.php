<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validador QR</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4; /* Fondo gris claro */
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .contenedor-principal {
            max-width: 900px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
        }

        .encabezado {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-hacienda {
            height: 70px; /* Ajusta el tamaño del logo si tienes la imagen */
            margin-right: 15px;
            vertical-align: middle;
        }

        .logo-sat {
            height: 30px; /* Ajusta el tamaño del logo si tienes la imagen */
            vertical-align: middle;
        }

        /* Bloque de alerta */
        .alerta-info {
            background-color: #fcf8e3;
            border: 1px solid #faebcc;
            color: #8a6d3b;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            font-size: 14px;
        }

        .alerta-info span {
            font-size: 18px; /* Icono de la 'i' */
            margin-right: 10px;
            font-weight: bold;
        }

        /* Contenedores de datos */
        .datos-contenedor {
            border-radius: 8px;
            margin-bottom: 20px;
            overflow: hidden; /* Para que el header degrade cubra solo el borde superior */
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
            border: 1px solid #dcdcdc; /* Borde gris muy ligero */
        }

        .datos-header {
            padding: 8px 15px;
            color: #5d451e; /* Color del texto del encabezado */
            font-weight: bold;
            font-size: 14px;
            /* Degradado de color marrón/dorado simulando la vista original */
            background-image: linear-gradient(to bottom, #f0e6d2 0%, #e0d0b8 100%);
            border-bottom: 1px solid #c5b498;
        }

        .datos-cuerpo {
            background-color: white;
            padding: 15px;
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 5px 15px; /* Espacio entre filas y columnas */
            font-size: 14px;
        }

        .etiqueta {
            font-weight: bold;
            color: #333;
            text-align: right;
        }

        .valor {
            color: #555;
            text-align: left;
        }
    </style>
</head>
<body>

    <div class="contenedor-principal">
        <div class="encabezado">
            <img class="logo-hacienda" src="{{asset('img/HACIENDA-SAT.jpg')}}" alt="HACIENDA">
            
        </div>

        <div class="alerta-info">
            <span class="ui-messages-info-icon"><i class="fa-solid fa-circle-info" style="color: #91c5ee;"></i></span>
            <p>La Opinión que verificó fue emitida por el Servicio de Administración Tributaria.</p>
        </div>

        <div class="datos-contenedor">
            <div class="datos-header">
                Datos Generales Enviados
            </div>
            <div class="datos-cuerpo">
                <span class="etiqueta">Folio:</span> <span class="valor">2SNGT554944</span>
                <span class="etiqueta">RFC:</span> <span class="valor">COMD920913LQI</span>
                <span class="etiqueta">Fecha:</span> <span class="valor">05-11-2025</span>
                <span class="etiqueta">Sentido:</span> <span class="valor">Positivo</span>
            </div>
        </div>

        <hr> <div class="datos-contenedor">
            <div class="datos-header">
                Datos Generales Consultados
            </div>
            <div class="datos-cuerpo">
                <span class="etiqueta">Folio:</span> <span class="valor">2SNGT554944</span>
                <span class="etiqueta">RFC:</span> <span class="valor">COMD920913LQI</span>
                <span class="etiqueta">Fecha:</span> <span class="valor">05-11-2025</span>
                <span class="etiqueta">Sentido:</span> <span class="valor">Positivo</span>
            </div>
        </div>
    </div>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>