<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportar Falla | Módulo de Incidencias</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo_frontera.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

    <style>
        /* ==========================================================
           TOKENS — mismos que panel_operador / perfil_operador / historial_combustible / solicitar_combustible
           ========================================================== */
        :root {
            --asphalt-950: #12151A;
            --asphalt-900: #191D23;
            --panel-800:   #21262E;
            --panel-700:   #2A3039;
            --hairline:    rgba(245, 243, 238, 0.08);

            --amber:      #F2A93B;
            --amber-dim:  rgba(242, 169, 59, 0.14);
            --red:        #E5484D;
            --red-dim:    rgba(229, 72, 77, 0.14);
            --green:      #4FB477;
            --green-dim:  rgba(79, 180, 119, 0.14);
            --blue:       #4FA7C7;
            --blue-dim:   rgba(79, 167, 199, 0.14);
            --violet:     #8C86E0;
            --violet-dim: rgba(140, 134, 224, 0.14);

            --ink:        #F5F3EE;
            --ink-dim:    #A6AEB8;
            --ink-faint:  #626B76;
        }

        * { box-sizing: border-box; }

        body {
            background: var(--asphalt-950);
            color: var(--ink);
            font-family: 'Inter', sans-serif;
            padding-bottom: 40px;
            -webkit-font-smoothing: antialiased;
        }

        .eyebrow {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--ink-faint);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Oswald', sans-serif;
            letter-spacing: 0.01em;
        }

        .container { max-width: 480px; }

        .bezel {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* ---------- BARRA SUPERIOR ---------- */
        .top-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 18px 4px 20px;
        }

        .back-bezel {
            width: 38px; height: 38px;
            border: 1px solid var(--hairline);
            background: var(--panel-800);
            color: var(--ink) !important;
            text-decoration: none;
            flex-shrink: 0;
        }

        .top-bar h5 {
            font-size: 1.1rem;
            font-weight: 600;
            text-transform: uppercase;
            margin: 0;
        }

        /* ---------- TABS ---------- */
        .dash-tabs {
            display: flex;
            gap: 4px;
            background: var(--panel-800);
            border: 1px solid var(--hairline);
            border-radius: 14px;
            padding: 4px;
            margin-bottom: 20px;
        }

        .dash-tabs .nav-link {
            flex: 1;
            text-align: center;
            border: none;
            background: transparent;
            color: var(--ink-faint) !important;
            font-family: 'Oswald', sans-serif;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            padding: 10px 8px;
            border-radius: 10px;
            transition: all 0.15s ease;
        }

        .dash-tabs .nav-link.active {
            background: var(--amber) !important;
            color: var(--asphalt-950) !important;
        }

        /* ---------- FORM (reusa el sistema de perfil/combustible) ---------- */
        .section-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--ink-faint);
            margin: 0 2px 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-label i { color: var(--amber); font-size: 0.7rem; }

        .form-panel {
            background: var(--panel-800);
            border: 1px solid var(--hairline);
            border-radius: 16px;
            padding: 16px;
        }

        .field-group { margin-bottom: 14px; }
        .field-group:last-child { margin-bottom: 0; }

        .field-label {
            display: block;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.66rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--ink-dim);
            margin-bottom: 6px;
        }

        .field-control {
            width: 100%;
            background: var(--asphalt-900);
            border: 1px solid var(--hairline);
            border-radius: 10px;
            color: var(--ink);
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            padding: 11px 13px;
            transition: border-color 0.12s ease, box-shadow 0.12s ease;
        }

        .field-control::placeholder { color: var(--ink-faint); }

        .field-control:focus {
            outline: none;
            border-color: var(--amber);
            box-shadow: 0 0 0 3px var(--amber-dim);
            background: var(--asphalt-900);
            color: var(--ink);
        }

        .field-with-prefix { display: flex; align-items: stretch; }

        .field-prefix {
            width: 46px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--green-dim);
            border: 1px solid var(--hairline);
            border-right: none;
            border-radius: 10px 0 0 10px;
            color: var(--green);
            font-weight: 700;
        }

        .field-with-prefix .field-control {
            border-radius: 0 10px 10px 0;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            font-size: 1.05rem;
        }

        /* ---------- CHIPS DE PRIORIDAD (reemplaza el <select>) ---------- */
        .priority-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .priority-chip { position: relative; }

        .priority-chip input {
            position: absolute;
            opacity: 0;
            inset: 0;
            margin: 0;
            cursor: pointer;
        }

        .priority-chip label {
            display: block;
            border: 1px solid var(--hairline);
            border-radius: 12px;
            padding: 10px 10px;
            cursor: pointer;
            transition: all 0.12s ease;
            background: var(--asphalt-900);
        }

        .priority-chip .chip-name {
            font-family: 'Oswald', sans-serif;
            font-weight: 600;
            font-size: 0.82rem;
            text-transform: uppercase;
            display: block;
        }

        .priority-chip .chip-desc {
            font-size: 0.66rem;
            color: var(--ink-faint);
            display: block;
            margin-top: 2px;
            line-height: 1.25;
        }

        .priority-chip input:checked ~ label { border-width: 2px; }

        .chip-baja label     { }
        .chip-baja input:checked ~ label     { border-color: var(--green);  background: var(--green-dim); }
        .chip-baja .chip-name { color: var(--green); }

        .chip-media input:checked ~ label    { border-color: var(--blue);   background: var(--blue-dim); }
        .chip-media .chip-name { color: var(--blue); }

        .chip-alta input:checked ~ label     { border-color: var(--amber);  background: var(--amber-dim); }
        .chip-alta .chip-name { color: var(--amber); }

        .chip-critica input:checked ~ label  { border-color: var(--red);    background: var(--red-dim); }
        .chip-critica .chip-name { color: var(--red); }

        .priority-chip:not(:has(input:checked)) .chip-name,
        .priority-chip:not(:has(input:checked)) .chip-desc { color: var(--ink-faint); }

        /* ---------- CAPTURA DE FOTO (reusa el patrón de solicitar_combustible) ---------- */
        .photo-upload {
            position: relative;
            width: 100%;
            min-height: 150px;
            border: 2px dashed var(--hairline);
            border-radius: 14px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background: var(--asphalt-900);
            color: var(--ink-faint);
            cursor: pointer;
            overflow: hidden;
            transition: border-color 0.12s ease;
        }

        .photo-upload:hover { border-color: rgba(242,169,59,0.4); }
        .photo-upload i { font-size: 1.5rem; }
        .photo-upload span { font-size: 0.8rem; font-weight: 600; }
        .photo-upload small { font-size: 0.7rem; }

        .photo-upload img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        /* ---------- BOTÓN DE ENVÍO ---------- */
        .save-bar { margin-top: 18px; }

        .btn-submit {
            width: 100%;
            border: none;
            border-radius: 14px;
            padding: 15px;
            font-family: 'Oswald', sans-serif;
            font-weight: 600;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: var(--asphalt-950);
        }

        .btn-submit--red {
            background: linear-gradient(135deg, var(--red) 0%, #C43439 100%);
            color: var(--ink);
            box-shadow: 0 8px 20px rgba(229, 72, 77, 0.22);
        }

        .btn-submit--green {
            background: linear-gradient(135deg, var(--green) 0%, #2F9160 100%);
            box-shadow: 0 8px 20px rgba(79, 180, 119, 0.22);
        }

        .btn-submit:active { transform: scale(0.99); }
        .btn-submit:disabled { opacity: 0.6; }

        /* ---------- HISTORIAL: TARJETAS ---------- */
        .empty-state, .error-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--ink-faint);
        }

        .empty-state i, .error-state i { font-size: 2.2rem; margin-bottom: 12px; opacity: 0.5; }
        .empty-state p, .error-state p { font-size: 0.85rem; font-weight: 500; margin: 0; }
        .error-state { color: var(--red); }
        .error-state i { color: var(--red); opacity: 0.7; }

        .skeleton-row {
            height: 118px;
            border-radius: 16px;
            background: linear-gradient(90deg, var(--panel-800) 0%, var(--panel-700) 50%, var(--panel-800) 100%);
            background-size: 200% 100%;
            animation: skeleton-pulse 1.3s ease-in-out infinite;
            margin-bottom: 12px;
        }

        @keyframes skeleton-pulse {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        .incident-card {
            background: var(--panel-800);
            border: 1px solid var(--hairline);
            border-radius: 16px;
            padding: 14px;
            margin-bottom: 12px;
        }

        .incident-pill {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 999px;
            border: 1px solid;
            display: inline-block;
        }

        .pill-critica { background: var(--red-dim);   color: var(--red);   border-color: rgba(229,72,77,0.3); }
        .pill-alta    { background: var(--amber-dim); color: var(--amber); border-color: rgba(242,169,59,0.3); }
        .pill-media   { background: var(--blue-dim);  color: var(--blue);  border-color: rgba(79,167,199,0.3); }
        .pill-baja    { background: var(--green-dim); color: var(--green); border-color: rgba(79,180,119,0.3); }

        .incident-thumb {
            width: 56px; height: 56px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid var(--hairline);
            flex-shrink: 0;
        }

        .incident-thumb-empty {
            width: 56px; height: 56px;
            border-radius: 12px;
            border: 1px solid var(--hairline);
            background: var(--asphalt-900);
            display: flex; align-items: center; justify-content: center;
            color: var(--ink-faint);
            flex-shrink: 0;
        }

        .incident-title {
            font-family: 'Oswald', sans-serif;
            font-weight: 600;
            font-size: 0.86rem;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: 0.01em;
        }

        .status-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }

        .status-text {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.66rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            color: var(--ink-faint);
        }

        .btn-incident-action {
            width: 100%;
            border-radius: 10px;
            padding: 10px;
            font-family: 'Oswald', sans-serif;
            font-weight: 600;
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .action-view    { background: transparent; border: 1px solid var(--hairline); color: var(--ink-dim) !important; }
        .action-quote    { background: var(--amber); color: var(--asphalt-950); }
        .action-waiting  { background: var(--violet-dim); color: var(--violet); opacity: 0.9; }
        .action-confirm  { background: var(--green); color: var(--asphalt-950); }
        .action-done     { background: var(--green-dim); color: var(--green); opacity: 0.9; }

        /* ---------- OFFCANVAS ---------- */
        .offcanvas {
            max-width: 440px;
            background: var(--asphalt-950) !important;
            color: var(--ink);
        }

        .offcanvas-header {
            border-bottom: 1px solid var(--hairline);
        }

        .offcanvas-title {
            font-family: 'Oswald', sans-serif;
            font-size: 0.95rem;
            text-transform: uppercase;
            color: var(--ink);
        }

        .btn-close { filter: invert(1) grayscale(1) brightness(1.4); }

        .file-drop-web {
            border: 2px dashed var(--hairline);
            background: var(--asphalt-900);
            border-radius: 14px;
            padding: 26px 16px;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.12s ease;
        }

        .file-drop-web:hover { border-color: rgba(79,180,119,0.4); }
        .file-drop-web i { font-size: 1.6rem; color: var(--red); opacity: 0.85; margin-bottom: 8px; display: block; }
        .file-drop-web p { margin: 0; font-size: 0.84rem; font-weight: 600; color: var(--ink); }
        .file-drop-web small { color: var(--ink-faint); font-size: 0.72rem; }

        /* ---------- SWEETALERT A JUEGO ---------- */
        .swal2-popup.dash-swal {
            background: var(--panel-800) !important;
            color: var(--ink) !important;
            border-radius: 18px !important;
            border: 1px solid var(--hairline);
            font-family: 'Inter', sans-serif;
        }
        .swal2-popup.dash-swal .swal2-title { color: var(--ink) !important; font-family: 'Oswald', sans-serif; }
        .swal2-popup.dash-swal .swal2-html-container { color: var(--ink-dim) !important; }
        .swal2-popup.dash-swal .form-label-web { color: var(--ink-dim); font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
    </style>
</head>
<body>

<div class="container px-4">

    <div class="top-bar">
        <a href="{{ route('choferes.dashboard') }}" class="bezel back-bezel"><i class="fas fa-arrow-left"></i></a>
        <div>
            <h5>Módulo de incidencias</h5>
            <span class="eyebrow">Reporta y da seguimiento a fallas</span>
        </div>
    </div>

    <ul class="nav dash-tabs">
        <li class="nav-item flex-fill">
            <button class="nav-link active w-100" id="tab-nuevo" data-bs-toggle="pill" data-bs-target="#vista-formulario"><i class="fas fa-plus-circle me-1"></i> Nuevo reporte</button>
        </li>
        <li class="nav-item flex-fill">
            <button class="nav-link w-100" id="tab-lista" data-bs-toggle="pill" data-bs-target="#vista-lista"><i class="fas fa-history me-1"></i> Historial</button>
        </li>
    </ul>

    <div class="tab-content">

        <!-- ================= NUEVO REPORTE ================= -->
        <div class="tab-pane fade show active" id="vista-formulario">
            <form id="form-incidencia">
                @csrf
                <input type="hidden" name="foto_base64" id="foto_base64">

                <div class="section-label"><i class="fas fa-triangle-exclamation"></i> ¿Qué falla detectaste?</div>
                <div class="form-panel mb-4">
                    <div class="field-group">
                        <label class="field-label">Título del reporte</label>
                        <input type="text" name="titulo_reporte" class="field-control" placeholder="Ej: Ruido en suspensión delantera, clima no enfría..." required>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Descripción detallada</label>
                        <textarea name="descripcion" class="field-control" rows="4" placeholder="Describe qué sucede, en qué momento ocurre y los síntomas observados..." required></textarea>
                    </div>
                </div>

                <div class="section-label"><i class="fas fa-gauge-high"></i> Nivel de urgencia operativa</div>
                <div class="form-panel mb-4">
                    <div class="priority-grid">
                        <div class="priority-chip chip-baja">
                            <input type="radio" name="prioridad" id="prio-baja" value="baja">
                            <label for="prio-baja">
                                <span class="chip-name">Baja</span>
                                <span class="chip-desc">Mantenimiento preventivo / detalle estético</span>
                            </label>
                        </div>
                        <div class="priority-chip chip-media">
                            <input type="radio" name="prioridad" id="prio-media" value="media">
                            <label for="prio-media">
                                <span class="chip-name">Media</span>
                                <span class="chip-desc">Revisión programada en taller</span>
                            </label>
                        </div>
                        <div class="priority-chip chip-alta">
                            <input type="radio" name="prioridad" id="prio-alta" value="alta" checked>
                            <label for="prio-alta">
                                <span class="chip-name">Alta</span>
                                <span class="chip-desc">Riesgo potencial en la ruta actual</span>
                            </label>
                        </div>
                        <div class="priority-chip chip-critica">
                            <input type="radio" name="prioridad" id="prio-critica" value="critica">
                            <label for="prio-critica">
                                <span class="chip-name">Crítica</span>
                                <span class="chip-desc">Unidad detenida / inoperable</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="section-label"><i class="fas fa-camera-retro"></i> Evidencia fotográfica <span style="color: var(--ink-faint); text-transform: none; letter-spacing: 0;">(opcional)</span></div>
                <div class="form-panel mb-4">
                    <input type="file" id="foto_dispositivo" class="d-none" accept="image/*" capture="environment">
                    <label class="photo-upload" id="trigger-foto">
                        <div id="text-preview" class="text-center">
                            <i class="fas fa-camera-retro"></i>
                            <span class="d-block">Capturar evidencia</span>
                            <small>Toca para abrir cámara o galería</small>
                        </div>
                        <img id="img-preview" alt="Vista previa">
                    </label>
                </div>

                <div class="save-bar">
                    <button type="submit" id="btn-submit" class="btn-submit btn-submit--red">
                        <i class="fas fa-paper-plane"></i> Enviar reporte al administrador
                    </button>
                </div>
            </form>
        </div>

        <!-- ================= HISTORIAL ================= -->
        <div class="tab-pane fade" id="vista-lista">
            <div id="contenedor-reportes">
                <div class="skeleton-row"></div>
                <div class="skeleton-row"></div>
            </div>
        </div>
    </div>
</div>

<!-- ================= OFFCANVAS: CARGAR COTIZACIÓN ================= -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="panelCotizacion">
    <div class="offcanvas-header">
        <h6 class="offcanvas-title" id="tituloIncidencia">Cargar presupuesto de falla</h6>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <form id="formCotizacionChofer" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id_incidencia" id="id_incidencia_input">

            <div class="section-label"><i class="fas fa-dollar-sign"></i> Monto de la cotización</div>
            <div class="form-panel mb-4">
                <div class="field-group">
                    <div class="field-with-prefix">
                        <span class="field-prefix">$</span>
                        <input type="number" step="0.01" name="monto_cotizado" class="field-control" placeholder="0.00" required>
                    </div>
                </div>
            </div>

            <div class="section-label"><i class="fas fa-file-pdf"></i> Comprobante de cotización</div>
            <div class="form-panel mb-4">
                <div class="file-drop-web" onclick="$('#filePdf').click()">
                    <i class="fas fa-file-pdf"></i>
                    <p>Seleccionar archivo digital</p>
                    <small>Acepta fotos de cámara o archivos PDF</small>
                    <div id="nombreArchivo" class="mt-2 small fw-bold" style="color: var(--green);"></div>
                </div>
                <input type="file" name="pdf_cotizacion" id="filePdf" accept="image/*,application/pdf" class="d-none" onchange="actualizarNombreArchivo(this)">
            </div>

            <button type="submit" class="btn-submit btn-submit--green">
                <i class="fas fa-cloud-upload-alt"></i> Subir y enviar registro
            </button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $('#trigger-foto').on('click', function() { $('#foto_dispositivo').click(); });

    function comprimirYPrevisualizar(file) {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function (event) {
            const img = new Image();
            img.src = event.target.result;
            img.onload = function () {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                const MAX_WIDTH = 1000;
                const scaleSize = MAX_WIDTH / img.width;
                canvas.width = MAX_WIDTH;
                canvas.height = img.height * scaleSize;
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                const dataUrl = canvas.toDataURL('image/jpeg', 0.7);
                $('#img-preview').attr('src', dataUrl).show();
                $('#text-preview').hide();
                $('#foto_base64').val(dataUrl);
            };
        };
    }

    $('#foto_dispositivo').on('change', function(e) {
        if (e.target.files.length > 0) {
            Swal.fire({ title: 'Procesando imagen...', allowOutsideClick: false, customClass: { popup: 'dash-swal' }, didOpen: () => Swal.showLoading() });
            comprimirYPrevisualizar(e.target.files[0]);
            setTimeout(() => Swal.close(), 800);
        }
    });

    $('#form-incidencia').on('submit', function(e) {
        e.preventDefault();
        const btn = $('#btn-submit');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Enviando...');
        Swal.fire({ title: 'Generando reporte...', allowOutsideClick: false, customClass: { popup: 'dash-swal' }, didOpen: () => Swal.showLoading() });

        $.ajax({
            url: "{{ route('incidencia.store') }}",
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                Swal.fire({ title: '¡Reporte generado!', text: 'El reporte ha sido enviado con éxito.', icon: 'success', customClass: { popup: 'dash-swal' } })
                    .then(() => location.href = "{{ route('choferes.dashboard') }}");
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Enviar reporte al administrador');
                Swal.fire({ title: 'Error', text: 'No se pudo enviar. Verifica tu conexión.', icon: 'error', customClass: { popup: 'dash-swal' } });
            }
        });
    });

    $(document).ready(function() { cargarReportes(); });

    function cargarReportes() {
        $('#contenedor-reportes').html('<div class="skeleton-row"></div><div class="skeleton-row"></div>');

        $.ajax({
            url: "{{ route('choferes.recuperar_incidencias') }}",
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                let html = '';
                const incidencias = res.incidencias;

                if (incidencias.length === 0) {
                    html = `<div class="empty-state"><i class="fas fa-clipboard-list"></i><p>No tienes reportes registrados.</p></div>`;
                } else {
                    incidencias.forEach(inc => {
                        let classPrio = '';
                        switch (inc.prioridad) {
                            case 'critica': classPrio = 'pill-critica'; break;
                            case 'alta':    classPrio = 'pill-alta'; break;
                            case 'media':   classPrio = 'pill-media'; break;
                            default:        classPrio = 'pill-baja';
                        }

                        let fecha = new Date(inc.created_at).toLocaleDateString('es-MX', { day: '2-digit', month: 'short' });

                        let dotColor = 'var(--amber)';
                        if (inc.estatus_reporte === 'en_espera_cotizacion') dotColor = 'var(--blue)';
                        else if (inc.estatus_reporte === 'en_revision') dotColor = 'var(--violet)';
                        else if (inc.estatus_reporte === 'cotizacion_aprobada' || inc.estatus_reporte === 'reparado') dotColor = 'var(--green)';

                        let actionHtml = '';
                        switch (inc.estatus_reporte) {
                            case 'abierto':
                                actionHtml = `<button class="btn-incident-action action-view" onclick="mensajeAdminPendiente()">Ver estatus <i class="fas fa-chevron-right ms-1"></i></button>`;
                                break;
                            case 'en_espera_cotizacion':
                                actionHtml = `<button class="btn-incident-action action-quote" onclick="abrirPanelSubida(${inc.id}, '${inc.titulo_reporte}')"><i class="fas fa-file-upload"></i> Subir cotización</button>`;
                                break;
                            case 'en_revision':
                                actionHtml = `<button class="btn-incident-action action-waiting" disabled><i class="fas fa-hourglass-half"></i> En revisión de presupuesto</button>`;
                                break;
                            case 'cotizacion_aprobada':
                                actionHtml = `<button class="btn-incident-action action-confirm" onclick="confirmarReparacion(${inc.id})"><i class="fas fa-tools"></i> Confirmar salida / reparado</button>`;
                                break;
                            default:
                                actionHtml = `<button class="btn-incident-action action-done" disabled><i class="fa-solid fa-circle-check"></i> Unidad reparada en ruta</button>`;
                        }

                        html += `
                        <div class="incident-card">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="incident-pill ${classPrio}">${inc.prioridad}</span>
                                <span class="eyebrow" style="letter-spacing: 0.04em;">${fecha}</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                ${inc.foto_evidencia ? `<img src="/${inc.foto_evidencia}" class="incident-thumb">` : `<div class="incident-thumb-empty"><i class="fas fa-image opacity-50"></i></div>`}
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="incident-title">${inc.titulo_reporte}</h6>
                                    <div class="d-flex align-items-center mt-1">
                                        <span class="status-dot" style="background: ${dotColor};"></span>
                                        <span class="status-text">${inc.estatus_reporte.replace(/_/g, ' ').toUpperCase()}</span>
                                    </div>
                                </div>
                            </div>
                            ${actionHtml}
                        </div>`;
                    });
                }
                $('#contenedor-reportes').html(html);
            },
            error: function() {
                $('#contenedor-reportes').html(`<div class="error-state"><i class="fas fa-exclamation-triangle"></i><p>Error de conexión al obtener el historial.</p></div>`);
            }
        });
    }

    function mensajeAdminPendiente() {
        Swal.fire({
            icon: 'info',
            title: 'Reporte en revisión',
            text: 'Tu reporte ya se encuentra en la bandeja del administrador. Espera la solicitud de cotización.',
            confirmButtonColor: '#F2A93B',
            confirmButtonText: 'Entendido',
            customClass: { popup: 'dash-swal' }
        });
    }

    function abrirPanelSubida(id, titulo) {
        const titulo_nuevo = 'Subir cotización: ' + titulo;
        $('#id_incidencia_input').val(id);
        $('#tituloIncidencia').text(titulo_nuevo);
        $('#nombreArchivo').text('');

        var bsOffcanvas = new bootstrap.Offcanvas(document.getElementById('panelCotizacion'));
        bsOffcanvas.show();
    }

    function actualizarNombreArchivo(input) {
        if (input.files && input.files[0]) {
            $('#nombreArchivo').text('Adjunto: ' + input.files[0].name);
        }
    }

    $('#formCotizacionChofer').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);

        Swal.fire({ title: 'Subiendo presupuesto...', allowOutsideClick: false, customClass: { popup: 'dash-swal' }, didOpen: () => Swal.showLoading() });

        $.ajax({
            url: "{{ route('choferes.guardar_cotizacion') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                Swal.fire({ title: '¡Cargado!', text: 'La cotización se guardó y notificó al área de control.', icon: 'success', customClass: { popup: 'dash-swal' } });
                bootstrap.Offcanvas.getInstance(document.getElementById('panelCotizacion')).hide();
                cargarReportes();
            },
            error: function() {
                Swal.fire({ title: 'Error', text: 'Hubo un percance de red al subir la cotización', icon: 'error', customClass: { popup: 'dash-swal' } });
            }
        });
    });

    function confirmarReparacion(id) {
        Swal.fire({
            title: 'Cierre de reparación',
            text: 'Ingresa los datos finales para liberar la unidad en el sistema.',
            html: `
                <div class="text-start mt-2">
                    <label class="form-label-web d-block mb-1">1. Kilometraje al salir del taller:</label>
                    <input type="number" id="km_cierre" class="field-control mb-3" placeholder="Ej: 84320">

                    <label class="form-label-web d-block mb-1">2. Foto de comprobante / factura pagada:</label>
                    <input type="file" id="foto_comprobante" class="field-control" accept="image/*,application/pdf">
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Finalizar orden',
            confirmButtonColor: '#4FB477',
            cancelButtonText: 'Cancelar',
            cancelButtonColor: '#2A3039',
            customClass: { popup: 'dash-swal' },
            preConfirm: () => {
                const km = document.getElementById('km_cierre').value;
                const foto = document.getElementById('foto_comprobante').files[0];
                if (!km) { Swal.showValidationMessage('El kilometraje es requerido'); return false; }
                if (!foto) { Swal.showValidationMessage('Debes adjuntar el comprobante'); return false; }
                return { km: km, foto: foto };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('id', id);
                formData.append('km_actual', result.value.km);
                formData.append('comprobante', result.value.foto);
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: '/incidencias/fallas/vehiculo/reparado',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        Swal.fire({ title: 'Cerrando reporte...', allowOutsideClick: false, customClass: { popup: 'dash-swal' }, didOpen: () => Swal.showLoading() });
                    },
                    success: function(res) {
                        Swal.fire({ title: '¡Éxito!', text: 'Unidad liberada y reportada en oficinas centrales.', icon: 'success', customClass: { popup: 'dash-swal' } });
                        cargarReportes();
                    },
                    error: function(xhr) {
                        Swal.fire({ title: 'Error', text: 'No se guardó el cierre: ' + xhr.responseText, icon: 'error', customClass: { popup: 'dash-swal' } });
                    }
                });
            }
        });
    }
</script>
</body>
</html>