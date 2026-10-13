<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro choferes | Frontera Sur</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo_frontera.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/ml5@1.0.1/dist/ml5.min.js"></script>
    <style>
        :root { --accent: #3b82f6; --bg: #0f172a; --card:linear-gradient(135deg, #9f9f9f57 0%, #eb1313 100%); }
        body { background-color: var(--bg); color: white; font-family: 'Inter', sans-serif; overflow-x: hidden; }
        
        .main-container { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .glass-card { background: var(--card); border-radius: 35px; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); width: 100%; max-width: 900px; overflow: hidden; }
        
        /* Stepper */
        .step-indicator { background: rgba(0,0,0,0.2); padding: 20px; display: flex; justify-content: center; gap: 15px; }
        .step-dot { width: 12px; height: 12px; border-radius: 50%; background: rgba(255,255,255,0.2); transition: 0.3s; }
        .step-dot.active { background: var(--accent); box-shadow: 0 0 15px var(--accent); transform: scale(1.3); }
        .step-dot.completed { background: #22c55e; }

        /* Cámara */
        .camera-box { position: relative; width: 100%; max-width: 500px; margin: 0 auto; border-radius: 25px; overflow: hidden; border: 3px solid rgba(255,255,255,0.1); background: #000; }
        video { width: 100%; height: auto; display: block; }
        .mirror { transform: scaleX(-1); }

        /* Máscaras de recorte visual */
        .guide-overlay { 
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); 
            border: 2px solid var(--accent); pointer-events: none; z-index: 10;
            box-shadow: 0 0 0 1000px rgba(0,0,0,0.6); 
        }
        .mask-face { width: 220px; height: 280px; border-radius: 50% / 45%; }
        .mask-doc { width: 80%; height: 55%; border-radius: 15px; }
        .detected { border-color: #22c55e !important; box-shadow: 0 0 0 1000px rgba(0,0,0,0.4), 0 0 20px #22c55e !important; }

        .form-control, .form-select { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 12px; padding: 12px; }
        .form-control:focus { background: rgba(255,255,255,0.1); color: white; border-color: var(--accent); box-shadow: none; }
        
        .btn-step { padding: 12px 30px; border-radius: 15px; font-weight: 700; transition: 0.3s; }
        .form-step { display: none; }
        .form-step.active { display: block; animation: fadeIn 0.5s ease; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        #pass-feedback { font-size: 0.8rem; margin-top: 5px; }
    </style>
</head>
<body>
<div class="main-container">
    <div class="glass-card">
        <div class="step-indicator">
            @for($i=1; $i<=7; $i++) <div class="step-dot {{ $i==1 ? 'active' : '' }}" id="dot-{{ $i }}"></div> @endfor
        </div>
        <div class="p-4 p-md-5">
            <div id="status-ia" class="badge bg-primary mb-4" style="display:none;">IA EN CURSO...</div>
            
            <form id="form_registro_chofer">
                @csrf
                  
                <div class="form-step active" id="step-1">
                    <h3 class="fw-bold mb-4">COMPLETE TODOS LOS DATOS</h3>
                    <div class="row g-3">
                        <div class="col-md-4"><label class="small opacity-50">Nombre</label><input type="text" name="nombre" class="form-control" style="color:#fff;" placeholder="Juan.." required></div>
                        <div class="col-md-4"><label class="small opacity-50">Apellido P.</label><input type="text" name="apellido_p" class="form-control" placeholder="Hernandez.." required></div>
                        <div class="col-md-4"><label class="small opacity-50">Apellido M.</label><input type="text" name="apellido_m" class="form-control" placeholder="Perez" required></div>
                        <div class="col-md-6"><label class="small opacity-50">Usuario</label><input type="text" name="usuario_sistema" class="form-control" placeholder="juan_123..." required></div>
                        <div class="col-md-6"><label class="small opacity-50">Teléfono</label><input type="text" name="telefono" class="form-control" placeholder="96145778" required></div>
                        <div class="col-md-6"><label class="small opacity-50">Password</label><input type="password" id="password" name="password" class="form-control"  placeholder="********" required></div>
                        <div class="col-md-6"><label class="small opacity-50">Confirmar</label><input type="password" id="confirm_password" class="form-control" placeholder="********" required></div>
                    </div>
                    <div id="pass-feedback" class="mt-2 small"></div>
                    <div class="mt-5 text-end"><button type="button" class="btn btn-primary btn-step next-step" id="btn-to-step2" disabled>Siguiente</button></div>
                </div>

                @php 
                    $pasos = [
                        2 => ['t' => 'Foto de Rostro', 's' => 'Encuadra tu cara', 'm' => 'mask-face', 'k' => 'foto_chofer'],
                        3 => ['t' => 'INE Frente', 's' => 'Cuadro azul', 'm' => 'mask-doc', 'k' => 'foto_ine_frente'],
                        4 => ['t' => 'INE Reverso', 's' => 'Cuadro azul', 'm' => 'mask-doc', 'k' => 'foto_ine_reverso'],
                        5 => ['t' => 'Licencia Frente', 's' => 'Cuadro amarillo', 'm' => 'mask-doc', 'k' => 'foto_licencia_frente'],
                        6 => ['t' => 'Licencia Reverso', 's' => 'Cuadro amarillo', 'm' => 'mask-doc', 'k' => 'foto_licencia_reverso']
                    ];
                @endphp

                @foreach($pasos as $num => $p)
                <div class="form-step" id="step-{{ $num }}">
                    <h4 class="fw-bold text-center mb-1">{{ $p['t'] }}</h4>
                    <p class="text-center opacity-50 small mb-4">{{ $p['s'] }}</p>
                    
                    <div class="camera-box shadow-lg mb-4">
                        <video id="video-{{ $num }}"  width="640" height="480" autoplay playsinline muted class="{{ $num == 2 ? 'mirror' : '' }}"></video>
                        <div id="guide-{{ $num }}" class="guide-overlay {{ $p['m'] }}"></div>
                    </div>

                    <div class="text-center">
                        <button type="button" class="btn btn-light btn-lg rounded-pill px-5 btn-capture" data-step="{{ $num }}" data-key="{{ $p['k'] }}">
                            <i class="fas fa-camera me-2"></i> CAPTURAR
                        </button>
                        <div class="mt-3"><img id="prev-{{ $num }}" style="width:140px; border-radius:10px; display:none; border:2px solid #22c55e;"></div>
                    </div>

                    <div class="mt-5 d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-light btn-step prev-step">Atrás</button>
                        <button type="button" class="btn btn-primary btn-step next-step" id="next-btn-{{ $num }}" disabled>Siguiente</button>
                    </div>
                </div>
                @endforeach

                <div class="form-step" id="step-7">
                    <h3 class="fw-bold mb-4">Finalizar Expediente</h3>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="small">N° Licencia</label><input type="text" name="n_licencia" class="form-control" placeholder="AG168723D" required></div>
                        <div class="col-md-6"><label class="small">Vigencia</label><input type="date" name="vigencia_licencia" class="form-control" required></div>
                        <div class="col-md-6"><label class="small">Sangre</label><select name="tipo_sangre" class="form-select"><option value="O+">O+</option>
                                <option value="O-">O-</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                            </select>
                        </div>
                        <div class="col-md-6"><label class="small">Contacto Emergencia</label><input type="text" name="contacto_emergencia" class="form-control" placeholder="Maria..." required></div>
                        <div class="col-md-12"><label class="small">Tel. Emergencia</label><input type="text" name="telefono_emergencia" class="form-control" placeholder="96145778" required></div>
                    </div>
                    <div class="mt-5 d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-light btn-step prev-step">Atrás</button>
                        <button type="submit" class="btn btn-success btn-step shadow-lg">GUARDAR TODO</button>
                    </div>
                </div>

                <input type="hidden" name="foto_chofer_blob" id="foto_chofer_blob">
                <input type="hidden" name="foto_ine_frente_blob" id="foto_ine_frente_blob">
                <input type="hidden" name="foto_ine_reverso_blob" id="foto_ine_reverso_blob">
                <input type="hidden" name="foto_licencia_frente_blob" id="foto_licencia_frente_blob">
                <input type="hidden" name="foto_licencia_reverso_blob" id="foto_licencia_reverso_blob">
            </form>
        </div>
    </div>
</div>

<canvas id="canvas-pro" width="640" height="480" style="display:none;"></canvas>

<script>
$(document).ready(function() {
    let currentStep = 1;
    let stream = null;
    let faceMesh = null;
    let objectDetector = null;
    
    Swal.fire({
        title: '<h4 class="fw-bold" style="color: #3b82f6;">AVISO DE CONFIDENCIALIDAD</h4>',
        icon: 'info',
        iconColor: '#3b82f6',
        html: `
            <div style="text-align: justify; font-size: 0.9rem; line-height: 1.5; color: #e2e8f0; padding: 0 10px;">
                <p>
                    <i class="fas fa-shield-check text-success me-2"></i> 
                    La información que se proporcione posteriormente está protegida bajo lineamientos de <b >confidencialidad y uso exclusivo </b>.
                </p>
                <p>
                    <i class="fas fa-lock text-primary me-2"></i> 
                    Todos los datos recabados serán tratados con seguridad y únicamente para fines internos de 
                    <b style="color: #fff;"> GRUPO INDUSTRIAL FRONTERA SUR S.A DE C.V</b>.
                </p>
                <hr style="border-color: rgba(255,255,255,0.1)">
                <p style="font-size: 0.8rem; opacity: 0.8;">
                    <i class="fas fa-user-shield me-2"></i> 
                    El acceso está restringido exclusivamente al personal autorizado, garantizando la integridad y privacidad de sus datos conforme a normativas legales vigentes.
                </p>
            </div>
        `,
        background: '#0f172a', // Fondo azul oscuro profundo para hacer match con tu diseño
        showCloseButton: false,
        showConfirmButton: true,
        confirmButtonText: 'ACEPTO Y ENTIENDO',
        confirmButtonColor: '#3b82f6',
        timer: 150000, // 10 segundos exactos
        timerProgressBar: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        customClass: {
            popup: 'rounded-5 border border-secondary shadow-lg',
            confirmButton: 'rounded-pill px-4 py-2 fw-bold'
        }
    });

    // --- VALIDAR PASSWORD ---
    $('#password, #confirm_password').on('keyup', function() {
        const p1 = $('#password').val();
        const p2 = $('#confirm_password').val();
        if(p1 && p1 === p2) {
            $('#pass-feedback').text('✓ Coinciden').css('color','#22c55e');
            $('#btn-to-step2').prop('disabled', false);
        } else {
            $('#pass-feedback').text('× No coinciden').css('color','#ef4444');
            $('#btn-to-step2').prop('disabled', true);
        }
    });

    // --- CÁMARA ---
    async function initCamera(stepNum) {
        if(stream) stream.getTracks().forEach(t => t.stop());
        if(stepNum < 2 || stepNum > 6) return;

        const video = document.getElementById(`video-${stepNum}`);
        const isFront = (stepNum === 2);

        try {
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { facingMode: isFront ? "user" : "environment", width: 640, height: 480 } 
            });
            video.srcObject = stream;

            if(isFront) startIA(video);
        } catch (e) { Swal.fire('Error', 'Cámara bloqueada o no encontrada', 'error'); }
    }

    // --- IA ROSTRO ---
    function startIA(video) {
        $('#status-ia').show();
        faceMesh = ml5.faceMesh(video, () => {
            $('#status-ia').text('Imagen procesada..').addClass('bg-success');
            detect();
        });

        function detect() {
            if(currentStep !== 2) return;
            faceMesh.detect(video).then(res => {
                if(res && res.length > 0) $('#guide-2').addClass('detected');
                else $('#guide-2').removeClass('detected');
                requestAnimationFrame(detect);
            });
        }
    }

    // --- CAPTURA ---
       $('.btn-capture').click(function() {
        const s = $(this).data('step');
        const key = $(this).data('key');
        const video = document.getElementById(`video-${s}`);
        const canvas = document.getElementById('canvas-pro');
        const ctx = canvas.getContext('2d');
    
        // 1. Limpiar y corregir efecto espejo
        ctx.setTransform(1, 0, 0, 1, 0, 0);
        if(s === 2) { 
            ctx.translate(640, 0); 
            ctx.scale(-1, 1); 
        }
        
        // Dibujamos el frame completo del video en el canvas oculto
        ctx.drawImage(video, 0, 0, 640, 480);
    
        let finalData;
    
        if(s === 2) {
            // --- CASO ROSTRO: GUARDAR COMPLETO ---
            // No hacemos recortes, usamos el canvas tal cual
            finalData = canvas.toDataURL('image/jpeg', 0.9);
        } else {
            // --- CASO DOCUMENTOS: APLICAR RECORTE (CROP) ---
            // Definimos el área del cuadro azul/amarillo (80% ancho, 55% alto)
            let cw = 512; // 640 * 0.8
            let ch = 264; // 480 * 0.55
            let cx = (640 - cw) / 2;
            let cy = (480 - ch) / 2;
    
            const tempCanvas = document.createElement('canvas');
            tempCanvas.width = cw; 
            tempCanvas.height = ch;
            
            // Cortamos el área de interés del canvas original
            tempCanvas.getContext('2d').drawImage(canvas, cx, cy, cw, ch, 0, 0, cw, ch);
            finalData = tempCanvas.toDataURL('image/jpeg', 0.9);
        }
    
        // Guardar en el input oculto y mostrar preview
        $(`#${key}_blob`).val(finalData);
        $(`#prev-${s}`).attr('src', finalData).show();
        $(`#next-btn-${s}`).prop('disabled', false);
        $(this).html('✓ CAPTURADO').removeClass('btn-light').addClass('btn-success');
    });

    // --- NAVEGACIÓN ---
    $('.next-step').click(function() {
        $(`#step-${currentStep}`).hide();
        $(`#dot-${currentStep}`).addClass('completed').removeClass('active');
        currentStep++;
        $(`#step-${currentStep}`).show().addClass('active');
        $(`#dot-${currentStep}`).addClass('active');
        initCamera(currentStep);
    });

    $('.prev-step').click(function() {
        $(`#step-${currentStep}`).hide();
        currentStep--;
        $(`#step-${currentStep}`).show().addClass('active');
        initCamera(currentStep);
    });

    // --- GUARDAR ---
    $('#form_registro_chofer').on('submit', function(e) {
        e.preventDefault();
        Swal.fire({ title: 'Guardando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        $.ajax({
            url: '/choferes/guardar',
            type: 'POST',
            data: new FormData(this),
            processData: false, contentType: false,
            success: () => Swal.fire('Éxito', 'Expediente creado', 'success').then(() => location.href="/"),
            error: (xhr) => Swal.fire('Error', xhr.responseText, 'error')
        });
    });
});
</script>

</body>
</html>