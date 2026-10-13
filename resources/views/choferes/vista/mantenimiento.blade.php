<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema en Mantenimiento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card-mantenimiento {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            max-width: 500px;
            padding: 40px;
            text-align: center;
            background: white;
        }
        .icon-wrapper {
            font-size: 80px;
            color: #ffc107;
            margin-bottom: 20px;
            animation: gear-spin 4s linear infinite;
        }
        @keyframes gear-spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .nombre-chofer {
            color: #0d6efd;
            font-weight: 700;
        }
        .btn-regresar {
            border-radius: 50px;
            padding: 10px 30px;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
            transition: 0.3s;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10 d-flex justify-content-center">
            <div class="card-mantenimiento">
                <div class="icon-wrapper">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                </div>
                
                <h2 class="fw-bold mb-3">¡Hola, <span class="nombre-chofer">{{ $nombreChofer }}</span>!</h2>
                
                <p class="text-muted fs-5">
                    Estamos realizando mejoras en el sistema para brindarte una mejor experiencia en la gestión de tus solicitudes.
                </p>
                
                <div class="alert alert-warning border-0 bg-light-subtle py-3 my-4">
                    <i class="fa-solid fa-circle-info me-2"></i>
                    <strong>Aviso:</strong> Por el momento, la creación de nuevas solicitudes y registros de combustible no están disponibles.
                </div>

                <p class="mb-4 small text-secondary">
                    Volveremos a estar en línea en unos minutos. Agradecemos tu paciencia mientras ponemos todo a punto.
                </p>

                <a href="{{ route('choferes.dashboard.mantenimiento') }}" class="btn btn-outline-primary btn-regresar">
                    <i class="fa-solid fa-rotate me-2"></i> Verificar nuevamente
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>