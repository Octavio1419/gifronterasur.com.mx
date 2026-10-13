<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Combustible</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo_frontera.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

    <style>
        /* ==========================================================
           TOKENS — mismos que panel_operador / perfil_operador / historial_combustible
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
            padding: 18px 4px 6px;
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

        /* ---------- BANNER DE CONTEXTO (estatus de la solicitud) ---------- */
        .context-banner {
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: 14px;
            border: 1px solid;
            padding: 12px 14px;
            margin: 18px 0;
            font-size: 0.82rem;
        }

        .context-banner i { font-size: 1rem; }

        .context-banner strong { font-weight: 700; }

        .context-banner--ok    { background: var(--green-dim); border-color: rgba(79,180,119,0.3); color: var(--green); }
        .context-banner--warn  { background: var(--amber-dim); border-color: rgba(242,169,59,0.3); color: var(--amber); }
        .context-banner--error { background: var(--red-dim);   border-color: rgba(229,72,77,0.3);  color: var(--red); }

        /* ---------- SECCIONES DEL FORMULARIO (reusa el sistema de perfil) ---------- */
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

        select.field-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23A6AEB8' stroke-width='1.6' fill='none' fill-rule='evenodd'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 34px;
        }

        .field-hint {
            font-size: 0.72rem;
            color: var(--ink-faint);
            margin-top: 5px;
        }

        .field-error {
            font-size: 0.72rem;
            color: var(--red);
            margin-top: 5px;
            display: none;
        }

        .field-control.is-invalid { border-color: var(--red); box-shadow: 0 0 0 3px var(--red-dim); }

        /* input con prefijo tipo bezel ($ / km) */
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
            font-size: 1.1rem;
        }

        .field-with-prefix .field-control#km_actual { font-size: 0.95rem; }

        /* ---------- CHIPS DE OBRAS (reemplaza el <select multiple>) ---------- */
        .obra-chip-list {
            max-height: 190px;
            overflow-y: auto;
            border: 1px solid var(--hairline);
            border-radius: 10px;
            background: var(--asphalt-900);
            padding: 6px;
        }

        .obra-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 8px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.86rem;
            color: var(--ink-dim);
        }

        .obra-chip:hover { background: rgba(255,255,255,0.03); }

        .obra-chip input { accent-color: var(--amber); width: 16px; height: 16px; flex-shrink: 0; }

        .obra-chip input:checked ~ span { color: var(--ink); font-weight: 600; }

        .obra-chip-loading, .obra-chip-empty {
            padding: 14px;
            text-align: center;
            font-size: 0.8rem;
            color: var(--ink-faint);
        }

        /* ---------- FOTO DEL TICKET ---------- */
        .ticket-upload {
            position: relative;
            width: 100%;
            min-height: 140px;
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

        .ticket-upload:hover { border-color: rgba(242,169,59,0.4); }

        .ticket-upload i { font-size: 1.4rem; }
        .ticket-upload span { font-size: 0.78rem; font-weight: 600; }

        .ticket-upload img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        .ticket-remove-btn {
            position: absolute;
            top: 8px; right: 8px;
            width: 28px; height: 28px;
            border-radius: 50%;
            background: rgba(18,21,26,0.75);
            color: var(--ink);
            border: 1px solid var(--hairline);
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            z-index: 2;
        }

        /* ---------- BOTÓN DE ENVÍO ---------- */
        .save-bar {
            position: sticky;
            bottom: 0;
            background: linear-gradient(180deg, transparent, var(--asphalt-950) 30%);
            padding: 18px 0 4px;
            margin-top: 18px;
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, var(--green) 0%, #2F9160 100%);
            color: var(--asphalt-950);
            border: none;
            border-radius: 14px;
            padding: 15px;
            font-family: 'Oswald', sans-serif;
            font-weight: 600;
            font-size: 0.98rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 8px 20px rgba(79, 180, 119, 0.25);
        }

        .btn-submit:active { transform: scale(0.99); }
        .btn-submit:disabled { opacity: 0.65; box-shadow: none; }
    </style>
</head>
<body>
<div class="container px-4">

    <div class="top-bar">
        <a href="{{ route('choferes.dashboard') }}" class="bezel back-bezel"><i class="fas fa-chevron-left"></i></a>
        <div>
            <h5>Solicitar combustible</h5>
            <span class="eyebrow">Registra tu carga</span>
        </div>
    </div>

    <div id="contextBanner"></div>

    <form id="form-combustible" novalidate>
        @csrf
        <input type="hidden" name="foto_ticket_base64" id="foto_base64">

        <!-- Detalles de la carga -->
        <div class="section-label"><i class="fas fa-gas-pump"></i> Detalles de la carga</div>
        <div class="form-panel mb-4">
            <div class="field-group">
                <label class="field-label">Monto estimado</label>
                <div class="field-with-prefix">
                    <span class="field-prefix">$</span>
                    <input type="number" name="monto_solicitado" id="monto_solicitado" class="field-control" placeholder="0.00" min="1" step="0.01" required>
                </div>
                <div class="field-error" id="err-monto">Ingresa un monto mayor a $0.</div>
            </div>

            <div class="field-group">
                <label class="field-label">Tipo de combustible</label>
                <select name="tipo_combustible" id="tipo_combustible" class="field-control" required>
                    <option value="Diesel">Diesel</option>
                    <option value="Magna" selected>Magna (Verde)</option>
                    <option value="Premium">Premium (Roja)</option>
                </select>
            </div>

            <div class="field-group">
                <label class="field-label">Kilometraje actual</label>
                <div class="field-with-prefix">
                    <span class="field-prefix"><i class="fas fa-tachometer-alt"></i></span>
                    <input type="number" name="km_actual" id="km_actual" class="field-control" placeholder="KM del tablero" min="0" required>
                </div>
                <div class="field-error" id="err-km">Ingresa el kilometraje del tablero.</div>
            </div>
        </div>

        <!-- Obras -->
        @if($vehiculo->obra_id == 14)
            {{-- Unidad fija: no aplica selección de obras adicionales --}}
        @else
        <div class="section-label"><i class="fas fa-hard-hat"></i> Proyectos / obras en movimiento</div>
        <div class="form-panel mb-4">
            <div class="obra-chip-list" id="obraChipList">
                <div class="obra-chip-loading"><i class="fas fa-spinner fa-spin me-1"></i> Cargando obras...</div>
            </div>
            <div class="field-hint">Si estuviste en otras obras, selecciónalas.</div>
            <div class="field-error" id="err-obras">Selecciona al menos una obra.</div>
        </div>
        @endif

        <!-- Notas -->
        <div class="section-label"><i class="fas fa-pen"></i> Notas adicionales</div>
        <div class="form-panel mb-4">
            <div class="field-group">
                <textarea name="notas_chofer" class="field-control" rows="3" placeholder="Ej: Carga para viaje a Frontera..."></textarea>
            </div>
        </div>

        <div class="save-bar">
            <button type="submit" id="btn-enviar" class="btn-submit">
                <i class="fas fa-check-circle"></i> Enviar solicitud
            </button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const urlParams = new URLSearchParams(window.location.search);
    const estatus = urlParams.get('estatus');

    // Mapa único de estatus -> método de reposición y mensaje de contexto.
    // Si el estatus no viene o no está mapeado, la solicitud NO se deja enviar:
    // antes se mandaba con metodo_reposicion = "undefined" en la URL sin avisar a nadie.
    const MAPA_ESTATUS = {
        'A_TIEMPO':          { metodo: 'regular',          banner: 'ok',    icono: 'fa-check-circle',        texto: 'Solicitud dentro de horario' },
        'EXTRA_EXPONTANEA':  { metodo: 'reposicion_caja',  banner: 'warn',  icono: 'fa-exclamation-triangle', texto: 'Solicitud fuera de horario · Reposición de caja' }
    };

    function pintarBanner() {
        const cfg = MAPA_ESTATUS[estatus];
        const el = $('#contextBanner');
        if (cfg) {
            el.html(`
                <div class="context-banner context-banner--${cfg.banner}">
                    <i class="fas ${cfg.icono}"></i>
                    <span><strong>${cfg.texto}</strong></span>
                </div>
            `);
        } else {
            el.html(`
                <div class="context-banner context-banner--error">
                    <i class="fas fa-triangle-exclamation"></i>
                    <span>No se detectó el tipo de solicitud. Regresa al panel e inténtalo de nuevo.</span>
                </div>
            `);
            $('#btn-enviar').prop('disabled', true);
        }
    }

    $(document).ready(function() {
        pintarBanner();
        cargarObras();
    });

    function cargarObras() {
        const $list = $('#obraChipList');
        if ($list.length === 0) return; // vehiculo->obra_id == 14, no aplica

        fetch('/obras/recuperar/obras-listadas')
            .then(response => response.json())
            .then(data => {
                const obras = data.data || [];
                if (obras.length === 0) {
                    $list.html('<div class="obra-chip-empty">No hay obras disponibles.</div>');
                    return;
                }
                let html = '';
                obras.forEach(obra => {
                    html += `
                        <label class="obra-chip">
                            <input type="checkbox" name="obras_complemento[]" value="${obra.id}">
                            <span>${obra.nombre_obra}</span>
                        </label>`;
                });
                $list.html(html);
            })
            .catch(error => {
                console.error('Error cargando obras:', error);
                $list.html('<div class="obra-chip-empty" style="color: var(--red);">No se pudieron cargar las obras. <a href="#" onclick="cargarObras(); return false;" style="color: var(--red); text-decoration: underline;">Reintentar</a></div>');
            });
    }

    // --- Foto del ticket: captura, valida tamaño, convierte a base64 y muestra preview ---
    $('#ticketUpload').on('click', function(e) {
        if ($(e.target).closest('#ticketRemoveBtn').length) return;
        $('#ticketInput').trigger('click');
    });

    $('#ticketInput').on('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        if (file.size > 5 * 1024 * 1024) {
            Swal.fire('Archivo muy pesado', 'La foto no debe superar 5 MB.', 'warning');
            e.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(ev) {
            $('#foto_base64').val(ev.target.result);
            $('#ticketPreview').attr('src', ev.target.result).show();
            $('#ticketUpload i, #ticketUpload span').hide();
            $('#ticketRemoveBtn').css('display', 'flex');
            $('#ticketHint').text('Toca la foto para reemplazarla.');
        };
        reader.readAsDataURL(file);
    });

    $('#ticketRemoveBtn').on('click', function(e) {
        e.stopPropagation();
        $('#foto_base64').val('');
        $('#ticketInput').val('');
        $('#ticketPreview').hide().attr('src', '');
        $('#ticketUpload i, #ticketUpload span').show();
        $(this).hide();
        $('#ticketHint').text('Tamaño máximo 5 MB.');
    });

    // --- Validación antes de enviar ---
    function validarFormulario() {
        let valido = true;

        $('.field-control').removeClass('is-invalid');
        $('.field-error').hide();

        const monto = parseFloat($('#monto_solicitado').val());
        if (isNaN(monto) || monto <= 0) {
            $('#monto_solicitado').addClass('is-invalid');
            $('#err-monto').show();
            valido = false;
        }

        const km = $('#km_actual').val();
        if (km === '' || parseFloat(km) < 0) {
            $('#km_actual').addClass('is-invalid');
            $('#err-km').show();
            valido = false;
        }

        const $obrasList = $('#obraChipList');
        if ($obrasList.length && $obrasList.find('input[type="checkbox"]:checked').length === 0) {
            $('#err-obras').show();
            valido = false;
        }

        if (!MAPA_ESTATUS[estatus]) {
            valido = false;
        }

        return valido;
    }

    // --- Envío por AJAX ---
    $('#form-combustible').on('submit', function(e) {
        e.preventDefault();

        if (!validarFormulario()) {
            return;
        }

        const btn = $('#btn-enviar');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Enviando solicitud...');

        const metodo_reposicion = MAPA_ESTATUS[estatus].metodo;
        const datos_form = $(this).serialize();

        $.ajax({
            url: '/choferes/combustible/guardar/solicitud/' + encodeURIComponent(estatus) + '/' + encodeURIComponent(metodo_reposicion),
            type: 'POST',
            data: datos_form,
            success: function(res) {
                $('#form-combustible')[0].reset();
                Swal.fire('¡Enviado!', 'Tu solicitud se ha enviado correctamente.', 'success');
                setTimeout(function() {
                    window.location.href = '/choferes/dashboard';
                }, 2000);
            },
            error: function(xhr) {
                console.error('error es ', xhr.responseText);
                btn.prop('disabled', false).html('<i class="fas fa-check-circle"></i> Enviar solicitud');
                Swal.fire('Error', 'No se pudo procesar la solicitud.', 'error');
            }
        });
    });
</script>
</body>
</html>