<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de KM | Frontera Sur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/ml5@1.0.1/dist/ml5.min.js"></script>

    <style>
        :root { --bg-dark: #0f172a; --accent: #3b82f6; }
        body { background-color: var(--bg-dark); color: white; font-family: 'Inter', sans-serif; overflow: hidden; }
        
        .camera-container { position: relative; width: 100%; height: 42vh; background: #000; overflow: hidden; }
        video { width: 100%; height: 100%; object-fit: cover; }
        
        /* Guía visual para el odómetro */
        .scan-guide {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            width: 80%; height: 120px;
            border: 2px solid var(--accent);
            border-radius: 15px;
            box-shadow: 0 0 0 1000px rgba(0,0,0,0.6);
            z-index: 10;
        }
        .scan-guide.detected { border-color: #22c55e; box-shadow: 0 0 20px #22c55e, 0 0 0 1000px rgba(0,0,0,0.4); }

        .btn-capture {
            width: 80px; height: 80px; border-radius: 50%;
            border: 5px solid white; background: var(--accent);
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
        }
        .photo-preview { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; display: none; z-index: 20; }
    </style>
</head>
<body>

<div class="d-flex flex-column vh-100">
    <div class="p-3 d-flex align-items-center">
        <a href="{{ route('choferes.dashboard') }}" class="text-white me-3"><i class="fas fa-arrow-left fa-lg"></i></a>
        <h6 class="mb-0 fw-bold">REGISTRO DE KILOMETRAJE</h6>
    </div>

    <div class="camera-container">
        <video id="video" autoplay playsinline muted></video>
        <div id="guide" class="scan-guide"></div>
        <img id="preview" class="photo-preview">
        
        <div id="status-ia" class="position-absolute bottom-0 start-50 translate-middle-x mb-3 badge bg-primary" style="z-index: 30;">
            ALINEE EL ODÓMETRO
        </div>
    </div>

    <div class="p-4 flex-grow-1 bg-white rounded-top-5 text-dark">
        <form id="form-checkin">
            @csrf
            <input type="hidden" name="vehiculo_id" value="{{ $vehiculo->id }}">
            <input type="hidden" name="foto_blob" id="foto_blob">
            <input type="hidden" name="tipo_registro" value="{{ $tipo }}">

            <div class="mb-3">
                <label class="small fw-bold text-muted">KILOMETRAJE ACTUAL</label>
                <input type="number" name="km_lectura" id="km_input" class="form-control form-control-lg fw-bold" placeholder="Escriba el número aquí" required>
                <small class="text-muted">Último registro: {{ $vehiculo->km_actual }} km</small>
            </div>

            <!-- por si envia alguna nota -->
            <div class="mb-3">
                <label class="small fw-bold text-muted">NOTAS (OPCIONAL)</label>
                <textarea name="notas" class="form-control" rows="3" placeholder="Escriba alguna nota adicional..."></textarea>
            </div>

            <div class="d-flex justify-content-center align-items-center gap-4">
                <button type="button" id="btn-snap" class="btn-capture"></button>
                <button type="submit" id="btn-save" class="btn btn-success btn-lg rounded-pill px-5 d-none">ENVIAR REPORTE</button>
            </div>
            
            <button type="button" id="btn-retry" class="btn btn-link text-danger w-100 mt-2 d-none">Repetir foto</button>
        </form>
    </div>
</div>

<canvas id="canvas" width="640" height="480" class="d-none"></canvas>

<script>
    let stream;
    let objectDetector;

    async function startCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { facingMode: "environment", width: 640, height: 480 } 
            });
            document.getElementById('video').srcObject = stream;
            initIA();
        } catch (e) { alert("Error al acceder a la cámara"); }
    }

    function initIA() {
        ml5.objectDetector('cocossd', (model) => {
            objectDetector = model;
            detect();
        });
    }

    function detect() {
        objectDetector.detect(document.getElementById('video'), (err, results) => {
            if (results && results.length > 0) {
                // Si detecta un objeto rectangular con buena confianza
                $('#guide').addClass('detected');
                $('#status-ia').text('LECTURA POSIBLE').removeClass('bg-primary').addClass('bg-success');
            } else {
                $('#guide').removeClass('detected');
                $('#status-ia').text('BUSCANDO ODÓMETRO...').removeClass('bg-success').addClass('bg-primary');
            }
            requestAnimationFrame(detect);
        });
    }

    $('#btn-snap').click(function() {
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        
        ctx.setTransform(1, 0, 0, 1, 0, 0); 
    
        ctx.drawImage(video, 0, 140, 640, 200, 0, 0, 640, 480);
        
        const data = canvas.toDataURL('image/jpeg');
        
        $('#foto_blob').val(data);
        $('#preview').attr('src', data).fadeIn();
        $(this).addClass('d-none');
        $('#btn-save, #btn-retry').removeClass('d-none');
        $('#status-ia').hide();
    });

    $('#btn-retry').click(function() {
        $('#preview').hide();
        $('#btn-snap').removeClass('d-none');
        $('#btn-save, #btn-retry').addClass('d-none');
        $('#status-ia').show();
    });

    $('#form-checkin').on('submit', function(e) {
        e.preventDefault();
        Swal.fire({ title: 'Guardando...', didOpen: () => Swal.showLoading() });

        const tipoLabel = $('input[name="tipo_registro"]').val();
        console.log('Tipo de registro seleccionado:', tipoLabel);
        const tipoId = (tipoLabel === 'inicio') ? 1 : 0;

        console.log('tipo de dato', tipoId);

        //que estamos enviando al servidor
        console.log('Enviando datos:', {
            vehiculo_id: $('input[name="vehiculo_id"]').val(),
            chofer_id: {{ Auth::guard('chofer')->id() }},
            km_lectura: $('#km_input').val(),
            foto_blob: $('#foto_blob').val(),
            notas: $('textarea[name="notas"]').val(),
        });

        $.ajax({
            url: '/checkin/guardar/' + tipoId,
            type: 'POST',
            data: new FormData(this),
            processData: false, contentType: false,
            success: (res) => {
                Swal.fire('Éxito', 'Kilometraje registrado correctamente', 'success')
                .then(() => location.href = "{{ route('choferes.dashboard') }}");
            },
            error: function (xhr, status, error) {
                console.error('Error en el envío:', xhr.responseText);
               //mostrando error en swalt
                Swal.fire('Error', 'Hubo un problema al guardar el registro. Intente nuevamente.', xhr.status === 422 ? 'warning' : 'error');
            }
        });
    });

    startCamera();
</script>
</body>
</html>