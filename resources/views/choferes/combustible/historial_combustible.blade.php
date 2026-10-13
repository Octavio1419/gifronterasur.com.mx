<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Combustible</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo_frontera.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

    <style>
        /* ==========================================================
           TOKENS — mismos que panel_operador.blade.php / perfil_operador.blade.php
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
            padding: 18px 4px 22px;
        }

        .back-bezel {
            width: 38px; height: 38px;
            border: 1px solid var(--hairline);
            background: var(--panel-800);
            color: var(--ink) !important;
            text-decoration: none;
        }

        .top-bar h5 {
            font-size: 1.1rem;
            font-weight: 600;
            text-transform: uppercase;
            margin: 0;
        }

        /* ---------- STAT TILES (mismo motivo que "Estado y Rendimiento") ---------- */
        .stat-tile {
            background: var(--panel-800);
            border: 1px solid var(--hairline);
            border-radius: 16px;
            padding: 14px;
        }

        .stat-ring {
            width: 36px; height: 36px;
            border: 2px solid;
            font-size: 0.82rem;
            margin-bottom: 10px;
        }

        .stat-ring--amber  { border-color: var(--amber);  background: var(--amber-dim);  color: var(--amber); }
        .stat-ring--red    { border-color: var(--red);    background: var(--red-dim);    color: var(--red); }
        .stat-ring--green  { border-color: var(--green);  background: var(--green-dim);  color: var(--green); }
        .stat-ring--blue   { border-color: var(--blue);   background: var(--blue-dim);   color: var(--blue); }

        .stat-value {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            font-size: 1.35rem;
            letter-spacing: -0.02em;
            display: block;
            line-height: 1.1;
        }

        .stat-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--ink-faint);
            font-weight: 700;
        }

        /* ---------- TARJETA DE TOTAL AUTORIZADO ---------- */
        .total-authorized-card {
            background: linear-gradient(150deg, var(--green-dim) 0%, var(--panel-800) 70%);
            border: 1px solid rgba(79, 180, 119, 0.3);
            border-radius: 18px;
            padding: 18px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .total-authorized-card .bezel {
            width: 46px; height: 46px;
            background: var(--green);
            color: var(--asphalt-950);
            font-size: 1.05rem;
        }

        .total-authorized-card .stat-value {
            font-size: 1.6rem;
            color: var(--green);
        }

        .total-authorized-card .stat-label { color: var(--green); opacity: 0.85; }

        /* ---------- ESTADO VACÍO / ERROR ---------- */
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

        /* ---------- TARJETAS DE SOLICITUD (JS las inyecta con estas clases) ---------- */
        .fuel-card {
            background: var(--panel-800);
            border: 1px solid var(--hairline);
            border-left-width: 3px;
            border-radius: 16px;
            padding: 14px;
            margin-bottom: 12px;
        }

        .fuel-status-pill {
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

        .pill-pendiente  { background: var(--blue-dim);  color: var(--blue);  border-color: rgba(79,167,199,0.3); }
        .pill-autorizado { background: var(--green-dim); color: var(--green); border-color: rgba(79,180,119,0.3); }
        .pill-rechazado  { background: var(--red-dim);   color: var(--red);   border-color: rgba(229,72,77,0.3); }

        .fuel-timestamp {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.68rem;
            color: var(--ink-faint);
        }

        .fuel-icon-bezel {
            width: 46px; height: 46px;
            border: 2px solid;
            font-size: 1rem;
        }

        .fuel-icon--green { border-color: var(--green); background: var(--green-dim); color: var(--green); }
        .fuel-icon--red   { border-color: var(--red);   background: var(--red-dim);   color: var(--red); }

        .fuel-title {
            font-family: 'Oswald', sans-serif;
            font-weight: 600;
            font-size: 0.92rem;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.01em;
        }

        .fuel-sub {
            font-size: 0.76rem;
            color: var(--ink-dim);
            margin: 1px 0 0;
        }

        .fuel-amount-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--ink-faint);
            display: block;
        }

        .fuel-amount-value {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            font-size: 1rem;
            color: var(--ink);
        }

        .fuel-authorized-box {
            background: var(--green-dim);
            border: 1px dashed rgba(79, 180, 119, 0.4);
            border-radius: 12px;
            padding: 10px 12px;
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .fuel-authorized-box .label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.66rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--green);
        }

        .fuel-authorized-box .value {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--green);
        }

        .fuel-note-box {
            background: var(--asphalt-900);
            border-left: 3px solid var(--ink-faint);
            border-radius: 10px;
            padding: 9px 12px;
            margin-top: 10px;
        }

        .fuel-note-box .label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.6rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--ink-faint);
            display: block;
            margin-bottom: 2px;
        }

        .fuel-note-box p {
            font-size: 0.8rem;
            color: var(--ink-dim);
            margin: 0;
            font-style: italic;
        }

        .fuel-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 12px;
            padding-top: 10px;
            border-top: 1px solid var(--hairline);
            font-size: 0.72rem;
            color: var(--ink-faint);
        }

        .fuel-footer strong { color: var(--ink-dim); font-weight: 600; }
    </style>
</head>
<body>

<div class="container px-4 py-2">
    <div class="top-bar">
        <a href="{{ route('choferes.dashboard') }}" class="bezel back-bezel"><i class="fas fa-chevron-left"></i></a>
        <div>
            <h5>Historial de combustible</h5>
            <span class="eyebrow">Panel de control de solicitudes</span>
        </div>
    </div>

    <div class="row g-2 mb-3">
        <div class="col-6">
            <div class="stat-tile">
                <div class="bezel stat-ring stat-ring--blue"><i class="fas fa-list-ul"></i></div>
                <span class="stat-value" id="total-solicitudes">0</span>
                <span class="stat-label">Total pedidos</span>
            </div>
        </div>
        <div class="col-6">
            <div class="stat-tile">
                <div class="bezel stat-ring stat-ring--green"><i class="fas fa-check-circle"></i></div>
                <span class="stat-value" id="total-autorizadas">0</span>
                <span class="stat-label">Autorizadas</span>
            </div>
        </div>
        <div class="col-6">
            <div class="stat-tile">
                <div class="bezel stat-ring stat-ring--amber"><i class="fas fa-clock"></i></div>
                <span class="stat-value" id="total-pendientes">0</span>
                <span class="stat-label">Pendientes</span>
            </div>
        </div>
        <div class="col-6">
            <div class="stat-tile">
                <div class="bezel stat-ring stat-ring--red"><i class="fas fa-times-circle"></i></div>
                <span class="stat-value" id="total-rechazadas">0</span>
                <span class="stat-label">Rechazadas</span>
            </div>
        </div>
    </div>

    <div class="total-authorized-card mb-4">
        <div class="bezel"><i class="fas fa-dollar-sign"></i></div>
        <div>
            <span class="stat-value" id="monto-total-acumulado">$0.00</span>
            <span class="stat-label">Total autorizado</span>
        </div>
    </div>

    <div class="section-label eyebrow mb-2">Solicitudes</div>
    <div id="contenedor-reportes">
        <div class="skeleton-row"></div>
        <div class="skeleton-row"></div>
        <div class="skeleton-row"></div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        cargarSolicitudes();
    });

    function cargarSolicitudes() {
        $.ajax({
            url: "{{ route('choferes.recuperar_solicitudes_combustible') }}",
            type: 'GET',
            success: function(res) {
                let html = '';
                const solicitudes = res.solicitudes;

                let total = solicitudes.length;
                let autorizadas = 0;
                let pendientes = 0;
                let rechazadas = 0;
                let montoAcumulado = 0;

                if (total === 0) {
                    html = `<div class="empty-state">
                                <i class="fas fa-gas-pump"></i>
                                <p>No has realizado solicitudes aún.</p>
                            </div>`;
                } else {
                    solicitudes.forEach(sol => {
                        let pillClass = '';
                        if (sol.estatus === 'pendiente') {
                            pillClass = 'pill-pendiente';
                            pendientes++;
                        } else if (sol.estatus === 'autorizado') {
                            pillClass = 'pill-autorizado';
                            autorizadas++;
                            montoAcumulado += parseFloat(sol.monto_real_cargado || 0);
                        } else if (sol.estatus === 'rechazado') {
                            pillClass = 'pill-rechazado';
                            rechazadas++;
                        }

                        // Borde izquierdo según urgencia de la solicitud
                        let borderColor = 'var(--hairline)';
                        if (sol.estatus_solicitud === 'EXTRA_EXPONTANEA') {
                            borderColor = 'var(--amber)';
                        } else if (sol.estatus_solicitud === 'A_TIEMPO') {
                            borderColor = 'var(--green)';
                        }

                        let fecha = new Date(sol.created_at).toLocaleDateString('es-MX', {
                            day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit'
                        });

                        let montoSolicitado = parseFloat(sol.monto_solicitado).toLocaleString('es-MX', { style: 'currency', currency: 'MXN' });
                        let montoReal = sol.monto_real_cargado ? parseFloat(sol.monto_real_cargado).toLocaleString('es-MX', { style: 'currency', currency: 'MXN' }) : null;

                        let esPremium = sol.tipo_combustible === 'Premium';

                        html += `
                        <div class="fuel-card" style="border-left-color: ${borderColor};">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="fuel-status-pill ${pillClass}">${sol.estatus}</span>
                                <span class="fuel-timestamp">${fecha}</span>
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="bezel fuel-icon-bezel ${esPremium ? 'fuel-icon--red' : 'fuel-icon--green'} me-3">
                                    <i class="fas fa-gas-pump"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fuel-title">Solicitud #${sol.id}</h6>
                                    <p class="fuel-sub">${sol.tipo_combustible || 'Magna'}</p>
                                </div>
                                <div class="text-end">
                                    <span class="fuel-amount-label">Solicitado</span>
                                    <span class="fuel-amount-value">${montoSolicitado}</span>
                                </div>
                            </div>

                            ${montoReal ? `
                            <div class="fuel-authorized-box">
                                <span class="label"><i class="fas fa-check-double me-1"></i> Autorizado</span>
                                <span class="value">${montoReal}</span>
                            </div>
                            ` : ''}

                            ${sol.autorizador_id ? `
                            <div class="fuel-note-box">
                                <span class="label">Retroalimentación</span>
                                <p>"${sol.notes_admin || sol.notas_admin || 'Sin observaciones.'}"</p>
                            </div>
                            <div class="fuel-footer">
                                <span><i class="fas fa-hashtag me-1"></i> ${sol.folio_autorizacion || 'Sin folio'}</span>
                                <span class="text-end">
                                    Revisó: <strong>${sol.nombre_admin ? `${sol.nombre_admin} ${sol.apellido_admin_p || ''}` : 'Administrador'}</strong>
                                    <div>${new Date(sol.updated_at).toLocaleDateString('es-MX', { day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' })}</div>
                                </span>
                            </div>
                            ` : ''}
                        </div>`;
                    });
                }

                $('#contenedor-reportes').html(html);

                $('#total-solicitudes').text(total);
                $('#total-autorizadas').text(autorizadas);
                $('#total-pendientes').text(pendientes);
                $('#total-rechazadas').text(rechazadas);
                $('#monto-total-acumulado').text(montoAcumulado.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' }));
            },
            error: function(xhr) {
                console.error('Error al recuperar las solicitudes', xhr.responseText);
                $('#contenedor-reportes').html(`
                    <div class="error-state">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>Error de conexión al obtener el historial.</p>
                    </div>
                `);
            }
        });
    }
</script>
</body>
</html>