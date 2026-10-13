<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de control</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo_frontera.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/choferes/panel.css') }}">

    <style>
     
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

        /* ---------- HERO / CLÚSTER DE UNIDAD ---------- */
        .dash-hero {
            position: relative;
            min-height: 232px;
            background-size: cover;
            background-position: center;
            background-color: var(--asphalt-900);
        }

        .dash-hero::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(18,21,26,0.55) 0%, rgba(18,21,26,0.65) 40%, rgba(18,21,26,0.97) 100%);
        }

        .dash-hero__inner {
            position: relative;
            z-index: 2;
            padding: 18px 20px 0;
        }

        .status-led {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(79, 180, 119, 0.16);
            border: 1px solid rgba(79, 180, 119, 0.35);
            color: var(--green);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            padding: 5px 12px 5px 9px;
            border-radius: 999px;
        }

        .status-led__dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--green);
            box-shadow: 0 0 0 0 rgba(79, 180, 119, 0.6);
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {
            0%   { box-shadow: 0 0 0 0 rgba(79, 180, 119, 0.55); }
            70%  { box-shadow: 0 0 0 7px rgba(79, 180, 119, 0); }
            100% { box-shadow: 0 0 0 0 rgba(79, 180, 119, 0); }
        }

        .exit-bezel {
            width: 34px; height: 34px;
            display: inline-flex; align-items: center; justify-content: center;
            border-radius: 50%;
            border: 1px solid var(--hairline);
            background: rgba(18,21,26,0.4);
            color: var(--ink-dim) !important;
            text-decoration: none;
        }

        .dash-hero__unit {
            text-align: center;
            margin-top: 22px;
        }

        .dash-hero__unit h4 {
            font-size: 1.5rem;
            font-weight: 700;
            text-transform: uppercase;
            margin: 4px 0 10px;
        }

        /* placa estilo relieve vehicular */
        .plate-badge {
            display: inline-block;
            background: linear-gradient(180deg, #F5F3EE, #E4E0D5);
            color: #12151A;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            font-size: 0.85rem;
            letter-spacing: 0.12em;
            padding: 5px 14px;
            border-radius: 6px;
            box-shadow: inset 0 -2px 0 rgba(0,0,0,0.18), 0 1px 0 rgba(255,255,255,0.4);
        }

        /* carátula/bezel circular — motivo repetido en toda la vista */
        .bezel {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .chofer-avatar-box {
            display: flex;
            justify-content: center;
            margin-top: 14px;
            position: relative;
            z-index: 3;
        }

        .bezel--avatar {
            width: 84px; height: 84px;
            padding: 4px;
            background: var(--panel-800);
            border: 2px solid var(--amber);
            box-shadow: 0 0 0 5px var(--asphalt-950), 0 6px 18px rgba(0,0,0,0.4);
        }

        .chofer-avatar {
            width: 100%; height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .avatar-edit-badge {
            position: absolute;
            bottom: -2px; right: -2px;
            width: 26px; height: 26px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%;
            background: var(--amber);
            color: var(--asphalt-950);
            font-size: 0.68rem;
            border: 2px solid var(--asphalt-950);
        }

        .edit-profile-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-left: 8px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--amber);
            text-decoration: none;
            vertical-align: middle;
        }

        /* ---------- FRANJA DE DATOS PERSONALES ---------- */
        .driver-strip {
            text-align: center;
            padding: 10px 20px 4px;
            position: relative;
            z-index: 2;
        }

        .driver-strip h5 {
            font-size: 1.15rem;
            font-weight: 600;
            margin: 2px 0 8px;
        }

        .license-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--amber-dim);
            border: 1px solid rgba(242,169,59,0.3);
            color: var(--amber);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            padding: 4px 12px;
            border-radius: 999px;
        }

        /* ---------- SECCIÓN GENERAL ---------- */
        .container { max-width: 480px; }

        .section-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--ink-faint);
            margin: 0 2px 10px;
        }

        /* ---------- TARJETAS DE ACCIÓN ("GAUGES") ---------- */
        .gauge-card {
            display: flex;
            align-items: center;
            gap: 14px;
            background: var(--panel-800);
            border: 1px solid var(--hairline);
            border-radius: 16px;
            padding: 14px 16px;
            text-decoration: none;
            color: var(--ink);
            transition: transform 0.12s ease, border-color 0.12s ease;
        }

        .gauge-card:active { transform: scale(0.98); }
        .gauge-card:hover { border-color: rgba(245,243,238,0.16); color: var(--ink); }

        .gauge-icon {
            width: 48px; height: 48px;
            border: 2px solid;
            font-size: 1.05rem;
        }

        .gauge-icon--amber  { border-color: var(--amber);  background: var(--amber-dim);  color: var(--amber); }
        .gauge-icon--red    { border-color: var(--red);    background: var(--red-dim);    color: var(--red); }
        .gauge-icon--green  { border-color: var(--green);  background: var(--green-dim);  color: var(--green); }
        .gauge-icon--blue   { border-color: var(--blue);   background: var(--blue-dim);   color: var(--blue); }
        .gauge-icon--violet { border-color: var(--violet); background: var(--violet-dim); color: var(--violet); }
        .gauge-icon--muted  { border-color: var(--ink-faint); background: rgba(255,255,255,0.03); color: var(--ink-faint); }

        .gauge-title {
            font-family: 'Oswald', sans-serif;
            font-size: 0.92rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            margin: 0;
        }

        .gauge-sub {
            font-size: 0.76rem;
            color: var(--ink-dim);
            margin: 2px 0 0;
        }

        .gauge-chevron {
            margin-left: auto;
            color: var(--ink-faint);
            font-size: 0.85rem;
        }

        .gauge-disabled {
            opacity: 0.45;
            pointer-events: none;
        }

        .gauge-disabled .gauge-icon {
            border-style: dashed;
        }

        /* ---------- ESTADO Y RENDIMIENTO ---------- */
        .stat-tile {
            background: var(--panel-800);
            border: 1px solid var(--hairline);
            border-radius: 16px;
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .stat-ring {
            width: 38px; height: 38px;
            border: 2px solid;
            font-size: 0.85rem;
        }

        .stat-value {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            font-size: 0.95rem;
            margin: 0;
        }

        /* ---------- EXPEDIENTE DE UNIDAD ---------- */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .info-item {
            background: var(--panel-800);
            border: 1px solid var(--hairline);
            border-radius: 14px;
            padding: 12px 14px;
        }

        .info-item i {
            font-size: 0.85rem;
            opacity: 0.9;
        }

        .info-item span {
            display: block;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.6rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--ink-faint);
            margin: 6px 0 3px;
        }

        .info-item p {
            font-weight: 600;
            font-size: 0.86rem;
            margin: 0;
            color: var(--ink);
        }

        .badge-seguro {
            display: inline-block;
            margin-top: 6px;
            font-size: 0.62rem;
            font-family: 'JetBrains Mono', monospace;
            padding: 2px 8px;
            border-radius: 999px;
        }

        /* ---------- FICHA DE OBRA (ticket de despacho) ---------- */
        .info-obra-card {
            background: var(--panel-800);
            border: 1px solid var(--hairline);
            border-radius: 18px;
            padding: 18px 18px 16px;
            position: relative;
        }

        .info-obra-card::before {
            content: "";
            position: absolute;
            top: 0; left: 20px; right: 20px;
            height: 1px;
            background-image: repeating-linear-gradient(90deg, var(--hairline) 0 6px, transparent 6px 12px);
        }

        .map-container {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--hairline);
        }

        .map-container iframe { width: 100%; min-height: 160px; border: 0; display: block; }

        /* Overrides puntuales sobre utilidades de Bootstrap para respetar el tema oscuro */
        .text-primary { color: var(--amber) !important; }
        .bg-primary   { background-color: var(--panel-700) !important; }
        .opacity-50   { opacity: 0.65 !important; }
        .opacity-75   { opacity: 0.8 !important; }

        /* SweetAlert2 a juego con el tablero */
        .swal2-popup.dash-swal {
            background: var(--panel-800) !important;
            color: var(--ink) !important;
            border-radius: 18px !important;
            border: 1px solid var(--hairline);
        }
        .swal2-popup.dash-swal .swal2-textarea {
            background: var(--asphalt-900) !important;
            color: var(--ink) !important;
            border-color: var(--hairline) !important;
        }
    </style>
</head>

<body>
    @php
        $u = Auth::guard('chofer')->user();
        $fotoVehiculo = !empty($vehiculo->foto_vehiculo) ? asset('storage/'.$vehiculo->foto_vehiculo) : asset('assets/img/default-truck.jpg');
    @endphp

    <div class="dash-hero" style="background-image: url('{{ $fotoVehiculo }}');">
        <div class="dash-hero__inner">
            <div class="d-flex justify-content-between align-items-center">
                <span class="status-led"><span class="status-led__dot"></span> EN LÍNEA</span>
                <a href="{{ route('logout') }}" class="exit-bezel"><i class="fas fa-sign-out-alt"></i></a>
            </div>

            <div class="dash-hero__unit">
                <span class="eyebrow">Unidad asignada</span>
                <h4 class="text-truncate">{{ $vehiculo->marca ?? 'SIN UNIDAD' }} {{ $vehiculo->linea ?? 'ASIGNADA' }}</h4>
                <span class="plate-badge">{{ $vehiculo->placas ?? 'SIN PLACAS' }}</span>
            </div>

            <div class="chofer-avatar-box">
                <a href="{{ route('choferes.perfil') }}" class="bezel bezel--avatar position-relative text-decoration-none">
                    <img src="{{ $u->foto_perfil ? asset('storage/'.$u->foto_perfil) : asset('img/01.png') }}" class="chofer-avatar">
                    <span class="avatar-edit-badge"><i class="fas fa-pen"></i></span>
                </a>
            </div>
        </div>
    </div>

    <div class="container px-4">

        <div class="driver-strip mb-4">
            <span class="eyebrow">Datos personales</span>
            <h5>Hola, {{ $u->nombre }} {{ $u->apellido_p }}
              
            </h5>
            <span class="license-pill"><i class="fas fa-id-badge"></i> {{ $vehiculo->n_licencia ?? 'SIN LICENCIA' }}</span>
        </div>

        <div class="section-label">Jornada</div>
        <div class="d-flex flex-column gap-3 mb-2">
            <a class="gauge-card {{ !$puedoCheckIn ? 'gauge-disabled' : '' }}" id="checkin">
                <div class="bezel gauge-icon gauge-icon--amber">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <div>
                    <h6 class="gauge-title">Check-in de KM</h6>
                    <p class="gauge-sub">Registrar inicio de jornada</p>
                </div>
                <i class="fas fa-chevron-right gauge-chevron"></i>
            </a>
            <a class="gauge-card {{ !$puedoCheckOut ? 'gauge-disabled' : '' }}" id="checkout">
                <div class="bezel gauge-icon gauge-icon--red">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <div>
                    <h6 class="gauge-title">Check-out de KM</h6>
                    <p class="gauge-sub">Registrar final de jornada</p>
                </div>
                <i class="fas fa-chevron-right gauge-chevron"></i>
            </a>
        </div>
        <div class="section-label mt-4">Unidad</div>
        <div class="d-flex flex-column gap-3 mb-2">

            <a class="gauge-card" href="{{ route('choferes.reportar_incidencia') }}">
                <div class="bezel gauge-icon gauge-icon--red">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <h6 class="gauge-title">Reportar falla</h6>
                    <p class="gauge-sub">Ruidos, golpes o averías</p>
                </div>
                <i class="fas fa-chevron-right gauge-chevron"></i>
            </a>

            @if($botonCombustibleActivo)
                <a class="gauge-card" href="{{ route('choferes.solicitud_combustible', ['estatus' => $estatusSolicitud]) }}">
            @else
                <a class="gauge-card" onclick="enviarPeticionPermiso({{ $chofer->id }})">
            @endif
                    <div class="bezel gauge-icon {{ $botonCombustibleActivo ? 'gauge-icon--green' : 'gauge-icon--muted' }}">
                        <i class="fas fa-gas-pump"></i>
                    </div>
                    <div>
                        <h6 class="gauge-title">Solicitar combustible</h6>
                        <p class="gauge-sub">{{ $textoBoton }}</p>
                    </div>
                    <i class="fas fa-chevron-right gauge-chevron"></i>
                </a>

            <a class="gauge-card" href="{{ route('choferes.historial_combustible') }}">
                <div class="bezel gauge-icon gauge-icon--blue">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <div>
                    <h6 class="gauge-title">Historial de combustible</h6>
                    <p class="gauge-sub">Ver registros de consumo</p>
                </div>
                <i class="fas fa-chevron-right gauge-chevron"></i>
            </a>

            <a class="gauge-card" href="{{ route('choferes.vista.documentacion')}}">
                <div class="bezel gauge-icon gauge-icon--violet">
                    <i class="fas fa-folder-open"></i>
                </div>
                <div>
                    <h6 class="gauge-title">Documentación digital</h6>
                    <p class="gauge-sub">Póliza, tarjeta y permisos</p>
                </div>
                <i class="fas fa-download gauge-chevron"></i>
            </a>

        </div>

        <div class="mt-4">
            <div class="section-label">Estado y rendimiento</div>
            <div class="row g-2">
                <div class="col-6">
                    <div class="stat-tile">
                        <div class="bezel stat-ring gauge-icon--green"><i class="fas fa-leaf"></i></div>
                        <div>
                            <span class="eyebrow d-block mb-1">Rendimiento</span>
                            <p class="stat-value">S/N</p>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="stat-tile">
                        <div class="bezel stat-ring gauge-icon--amber"><i class="fas fa-tools"></i></div>
                        <div>
                            <span class="eyebrow d-block mb-1">Próx. servicio</span>
                            <p class="stat-value">S/N</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <div class="section-label">Expediente de unidad</div>
            <div class="info-grid">
                <div class="info-item">
                    <i class="fa-solid fa-barcode"></i>
                    <span>Serie (vehicular)</span>
                    <p>{{ $vehiculo->serie ?? 'No reg.' }}</p>
                </div>
                <div class="info-item">
                    <i class="fas fa-gas-pump" style="color: var(--amber);"></i>
                    <span>Efecticard</span>
                    <p>{{ $vehiculo->efecticard ?? 'S/D' }}</p>
                </div>
                <div class="info-item">
                    <i class="fas fa-id-card" style="color: var(--green);"></i>
                    <span>T. circulación</span>
                    <p>{{ $vehiculo->tarjeta_circulacion ?? 'S/D' }}</p>
                </div>
                <div class="info-item">
                    @if($vehiculo->estatus_seguro)
                        <i class="fas fa-shield-alt" style="color: var(--blue);"></i>
                        <span>Seguro vigente</span>
                        <p>{{ $vehiculo->compania_seguro ?? 'S/D' }}</p>
                        <span class="badge-seguro" style="background: var(--blue-dim); color: var(--blue);">Vence: {{ \Carbon\Carbon::parse($vehiculo->fecha_fin_seguro)->format('d/m/y') }}</span>
                    @else
                        <i class="fas fa-exclamation-circle" style="color: var(--red);"></i>
                        <span>Seguro</span>
                        <p style="color: var(--red);">VENCIDO</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-4 mb-5">
            <div class="info-obra-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="eyebrow" style="color: var(--amber);"><i class="fas fa-map-marker-alt me-1"></i> Obra actual</span>
                        <h5 class="fw-bold mb-1 mt-2" style="font-size: 1.05rem;">{{ $obra->nombre_obra ?? 'Sin obra asignada' }}</h5>
                        <p class="small mb-0" style="color: var(--ink-dim);">
                            <i class="fas fa-location-arrow me-1"></i> {{ $obra->municipio ?? 'Ubicación no disponible' }}, {{ $obra->estado ?? 'No disponible' }}
                        </p>
                    </div>
                    <div class="bezel gauge-icon gauge-icon--amber" style="width:40px;height:40px;">
                        <i class="fas fa-hard-hat"></i>
                    </div>
                </div>

                @if($obra && $obra->iframe)
                    <div class="map-container">
                        {!! $obra->iframe !!}
                    </div>
                @else
                    <div class="map-container d-flex align-items-center justify-content-center" style="min-height:160px; background: var(--asphalt-900);">
                        <div class="text-center" style="color: var(--ink-faint);">
                            <i class="fas fa-map-marked-alt fa-2x mb-2"></i>
                            <p class="small mb-0">Mapa no disponible</p>
                        </div>
                    </div>
                @endif

                <div class="mt-3 pt-2" style="border-top: 1px solid var(--hairline);">
                    <p class="small mb-0" style="color: var(--ink-dim);">
                        <strong style="color: var(--ink);">Se realiza:</strong> {{ $obra->nombre_obra ?? 'Consulte con su administrador' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('#checkin').click(function() {
            window.location.href = "{{ route('chofer.checkin', ['tipo' => 1]) }}";
        });

        $('#checkout').click(function() {
            window.location.href = "{{ route('chofer.checkin', ['tipo' => 0]) }}";
        });
    });

    function enviarPeticionPermiso(choferId) {
        Swal.fire({
            title: 'Fuera de horario',
            text: 'Las solicitudes se realizan todos los días jueves en un horario de 7:00 a 20:00. ¿Deseas solicitar un permiso extraordinario al administrador?',
            icon: 'info',
            input: 'textarea',
            inputPlaceholder: 'Si deseas solicitar combustible describe tu emergencia...',
            showCancelButton: true,
            confirmButtonText: 'Enviar solicitud',
            confirmButtonColor: '#F2A93B',
            cancelButtonColor: '#2A3039',
            customClass: { popup: 'dash-swal' },
            preConfirm: (motivo) => {
                if (!motivo) {
                    Swal.showValidationMessage('Es necesario indicar el motivo');
                }
                return motivo;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Enviando solicitud...',
                    allowOutsideClick: false,
                    customClass: { popup: 'dash-swal' },
                    didOpen: () => Swal.showLoading()
                });
                $.ajax({
                    url: "{{ route('combustible.solicitudes.fuera.plazo.solicitar.autorizacion') }}",
                    method: 'POST',
                    data: {
                        chofer_id: choferId,
                        motivo: result.value,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        Swal.fire({ title: 'Solicitud enviada', text: response.message, icon: 'success', customClass: { popup: 'dash-swal' } });
                    },
                    error: function(xhr) {
                        Swal.fire({ title: 'Error', text: 'Hubo un error al enviar tu solicitud. Por favor, intenta nuevamente.', icon: 'error', customClass: { popup: 'dash-swal' } });
                        console.log('error', xhr.responseText);
                    }
                });
            }
        });
    }
</script>
</body>
</html>
