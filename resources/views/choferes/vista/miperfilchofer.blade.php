<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil | Operador</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo_frontera.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/choferes/panel.css') }}">

    <style>
        /* ==========================================================
           TOKENS — mismos que panel_operador.blade.php
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
            padding-bottom: 50px;
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
            padding: 18px 20px 6px;
        }

        .back-bezel {
            width: 36px; height: 36px;
            border: 1px solid var(--hairline);
            background: var(--panel-800);
            color: var(--ink) !important;
            text-decoration: none;
        }

        .top-bar h4 {
            font-size: 1.1rem;
            font-weight: 600;
            text-transform: uppercase;
            margin: 0;
        }

        /* ---------- FOTO DE PERFIL ---------- */
        .avatar-editor {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 10px 0 24px;
        }

        .bezel--avatar-lg {
            width: 104px; height: 104px;
            padding: 4px;
            background: var(--panel-800);
            border: 2px solid var(--amber);
            box-shadow: 0 6px 18px rgba(0,0,0,0.35);
            position: relative;
        }

        .bezel--avatar-lg img {
            width: 100%; height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .avatar-upload-btn {
            position: absolute;
            bottom: 2px; right: 2px;
            width: 32px; height: 32px;
            border-radius: 50%;
            background: var(--amber);
            color: var(--asphalt-950);
            display: flex; align-items: center; justify-content: center;
            border: 3px solid var(--asphalt-950);
            cursor: pointer;
            font-size: 0.75rem;
        }

        .avatar-upload-btn input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
        }

        .avatar-editor .file-hint {
            font-size: 0.7rem;
            color: var(--ink-faint);
            margin-top: 10px;
        }

        /* ---------- SECCIONES DEL FORMULARIO ---------- */
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

        .field-row { display: flex; gap: 10px; }
        .field-row .field-group { flex: 1; }

        .field-hint {
            font-size: 0.72rem;
            color: var(--ink-faint);
            margin-top: 5px;
        }

        .field-error {
            font-size: 0.72rem;
            color: var(--red);
            margin-top: 5px;
        }

        .field-control.is-invalid { border-color: var(--red); box-shadow: 0 0 0 3px var(--red-dim); }

        .password-wrap { position: relative; }
        .password-toggle {
            position: absolute;
            top: 50%; right: 13px;
            transform: translateY(-50%);
            color: var(--ink-faint);
            cursor: pointer;
            font-size: 0.85rem;
            background: none;
            border: none;
        }

        /* ---------- VIGENCIA DE LICENCIA — reusa el motivo "expediente" ---------- */
        .license-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.66rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 999px;
            margin-top: 8px;
        }

        /* ---------- ACCIONES ---------- */
        .save-bar {
            position: sticky;
            bottom: 0;
            background: linear-gradient(180deg, transparent, var(--asphalt-950) 30%);
            padding: 18px 0 4px;
            margin-top: 8px;
        }

        .btn-save {
            width: 100%;
            background: var(--amber);
            color: var(--asphalt-950);
            border: none;
            border-radius: 14px;
            padding: 14px;
            font-family: 'Oswald', sans-serif;
            font-weight: 600;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-save:active { transform: scale(0.99); }

        .flash-alert {
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 0.85rem;
            margin-bottom: 16px;
            border: 1px solid;
        }

        .flash-alert--ok {
            background: var(--green-dim);
            border-color: rgba(79,180,119,0.35);
            color: var(--green);
        }

        .loading-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            padding: 70px 20px;
            color: var(--ink-dim);
            font-size: 0.82rem;
        }

        .loading-spinner {
            width: 34px; height: 34px;
            border-radius: 50%;
            border: 3px solid var(--hairline);
            border-top-color: var(--amber);
            animation: dash-spin 0.8s linear infinite;
        }

        @keyframes dash-spin { to { transform: rotate(360deg); } }
    </style>
</head>

<body>
    <div class="top-bar">
        <a href="{{ route('choferes.dashboard') }}" class="bezel back-bezel"><i class="fas fa-arrow-left"></i></a>
        <h4>Mi perfil</h4>
    </div>

    <div class="container px-4">

        @if(session('status'))
            <div class="flash-alert flash-alert--ok mt-3">
                <i class="fas fa-check-circle me-1"></i> {{ session('status') }}
            </div>
        @endif

        <div id="loadingState" class="loading-state">
            <div class="loading-spinner"></div>
            <span>Cargando tu perfil...</span>
        </div>

        <div id="loadErrorState" class="flash-alert mt-3" style="display:none; background: var(--red-dim); border-color: rgba(229,72,77,0.35); color: var(--red);">
            <i class="fas fa-exclamation-circle me-1"></i> No se pudo cargar tu perfil.
            <a href="#" onclick="cargarPerfil(); return false;" style="color: var(--red); text-decoration: underline;">Reintentar</a>
        </div>

        <form action="" method="POST" enctype="multipart/form-data" id="perfilForm" style="display:none;">
            @csrf
            @method('PUT')

            <div class="avatar-editor">
                <div class="bezel bezel--avatar-lg">
                    <img id="avatarPreview" src="{{ asset('img/01.png') }}" alt="Foto de perfil">
                    <label class="avatar-upload-btn">
                        <i class="fas fa-camera"></i>
                        <input type="file" name="foto_perfil" id="fotoPerfilInput" accept="image/*">
                    </label>
                </div>
                <span class="file-hint">Toca el ícono para cambiar tu foto</span>
                @error('foto_perfil') <div class="field-error text-center">{{ $message }}</div> @enderror
            </div>

            <!-- Datos personales -->
            <div class="section-label"><i class="fas fa-user"></i> Datos personales</div>
            <div class="form-panel mb-4">
                <div class="field-group">
                    <label class="field-label">Nombre(s)</label>
                    <input type="text" name="nombre" id="nombre" class="field-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
                    @error('nombre') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="field-row">
                    <div class="field-group">
                        <label class="field-label">Apellido paterno</label>
                        <input type="text" name="apellido_p" id="apellido_p" class="field-control @error('apellido_p') is-invalid @enderror" value="{{ old('apellido_p') }}" required>
                        @error('apellido_p') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="field-group">
                        <label class="field-label">Apellido materno</label>
                        <input type="text" name="apellido_m" id="apellido_m" class="field-control @error('apellido_m') is-invalid @enderror" value="{{ old('apellido_m') }}">
                        @error('apellido_m') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="field-group">
                    <label class="field-label">Teléfono</label>
                    <input type="tel" name="telefono" id="telefono" class="field-control @error('telefono') is-invalid @enderror" value="{{ old('telefono') }}" placeholder="10 dígitos" required>
                    @error('telefono') <div class="field-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Acceso al sistema -->
            <div class="section-label"><i class="fas fa-key"></i> Acceso al sistema</div>
            <div class="form-panel mb-4">
                <div class="field-group">
                    <label class="field-label">Usuario</label>
                    <input type="text" name="usuario_sistema" id="usuario_sistema" class="field-control @error('usuario_sistema') is-invalid @enderror" value="{{ old('usuario_sistema') }}" required>
                    @error('usuario_sistema') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="field-group">
                    <label class="field-label">Correo electrónico</label>
                    <input type="email" name="correo" id="correo" class="field-control @error('correo') is-invalid @enderror" value="{{ old('correo') }}" required>
                    @error('correo') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="field-group">
                    <label class="field-label">Nueva contraseña</label>
                    <div class="password-wrap">
                        <input type="password" name="password" id="passwordInput" class="field-control @error('password') is-invalid @enderror" placeholder="Dejar en blanco para no cambiarla">
                        <button type="button" class="password-toggle" onclick="togglePassword('passwordInput', this)"><i class="fas fa-eye"></i></button>
                    </div>
                    <div class="field-hint">Mínimo 8 caracteres. Déjalo vacío si no quieres cambiarla.</div>
                    @error('password') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="field-group">
                    <label class="field-label">Confirmar nueva contraseña</label>
                    <div class="password-wrap">
                        <input type="password" name="password_confirmation" id="passwordConfirmInput" class="field-control" placeholder="Repite la nueva contraseña">
                        <button type="button" class="password-toggle" onclick="togglePassword('passwordConfirmInput', this)"><i class="fas fa-eye"></i></button>
                    </div>
                </div>
            </div>

            <!-- Licencia -->
            <div class="section-label"><i class="fas fa-id-card"></i> Licencia de conducir</div>
            <div class="form-panel mb-4">
                <div class="field-row">
                    <div class="field-group">
                        <label class="field-label">Número de licencia</label>
                        <input type="text" name="n_licencia" id="n_licencia" class="field-control @error('n_licencia') is-invalid @enderror" value="{{ old('n_licencia') }}" required>
                        @error('n_licencia') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="field-group">
                        <label class="field-label">Vigencia</label>
                        <input type="date" name="vigencia_licencia" id="vigencia_licencia" class="field-control @error('vigencia_licencia') is-invalid @enderror" value="{{ old('vigencia_licencia') }}" required>
                        @error('vigencia_licencia') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <span class="license-status-pill" id="licenseStatusPill" style="display:none;">
                    <i class="fas" id="licenseStatusIcon"></i>
                    <span id="licenseStatusText"></span>
                </span>
            </div>

            <!-- Salud y emergencia -->
            <div class="section-label"><i class="fas fa-heartbeat"></i> Salud y contacto de emergencia</div>
            <div class="form-panel mb-4">
                <div class="field-group">
                    <label class="field-label">Tipo de sangre</label>
                    <select name="tipo_sangre" id="tipo_sangre" class="field-control @error('tipo_sangre') is-invalid @enderror" required>
                        <option value="" disabled selected>Selecciona...</option>
                        @foreach(['O+','O-','A+','A-','B+','B-','AB+','AB-'] as $tipo)
                            <option value="{{ $tipo }}" {{ old('tipo_sangre') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                        @endforeach
                    </select>
                    @error('tipo_sangre') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="field-group">
                    <label class="field-label">Contacto de emergencia</label>
                    <input type="text" name="contacto_emergencia" id="contacto_emergencia" class="field-control @error('contacto_emergencia') is-invalid @enderror" value="{{ old('contacto_emergencia') }}" placeholder="Nombre completo" required>
                    @error('contacto_emergencia') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="field-group">
                    <label class="field-label">Teléfono de emergencia</label>
                    <input type="tel" name="telefono_emergencia" id="telefono_emergencia" class="field-control @error('telefono_emergencia') is-invalid @enderror" value="{{ old('telefono_emergencia') }}" placeholder="10 dígitos" required>
                    @error('telefono_emergencia') <div class="field-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="save-bar">
                <button type="submit" class="btn-save" id="btnGuardar"><i class="fas fa-save"></i> Guardar cambios</button>
            </div>
        </form>
    </div>
    
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const ENDPOINT_PERFIL = '/choferes/perfil/datos/logueado';

        function pintarEstatusLicencia(fecha) {
            const pill = document.getElementById('licenseStatusPill');
            const icon = document.getElementById('licenseStatusIcon');
            const text = document.getElementById('licenseStatusText');
            if (!fecha) { pill.style.display = 'none'; return; }

            const vencida = new Date(fecha) < new Date(new Date().toDateString());
            pill.style.background = vencida ? 'var(--red-dim)' : 'var(--green-dim)';
            pill.style.color = vencida ? 'var(--red)' : 'var(--green)';
            icon.className = 'fas ' + (vencida ? 'fa-exclamation-circle' : 'fa-check-circle');
            text.textContent = vencida ? 'Licencia vencida' : 'Licencia vigente';
            pill.style.display = 'inline-flex';
        }

        function cargarPerfil() {
            document.getElementById('loadingState').style.display = 'flex';
            document.getElementById('perfilForm').style.display = 'none';
            document.getElementById('loadErrorState').style.display = 'none';

            fetch(ENDPOINT_PERFIL)
                .then(response => {
                    if (!response.ok) throw new Error('Respuesta no válida');
                    return response.json();
                })
                .then(respuesta => {
                    // recuperarMisDatos() devuelve una colección: el registro va en perfil[0]
                    const datos = respuesta.perfil && respuesta.perfil[0] ? respuesta.perfil[0] : null;
                    if (!datos) throw new Error('Perfil vacío');

                    document.getElementById('nombre').value = datos.nombre ?? '';
                    document.getElementById('apellido_p').value = datos.apellido_p ?? '';
                    document.getElementById('apellido_m').value = datos.apellido_m ?? '';
                    document.getElementById('telefono').value = datos.telefono ?? '';
                    document.getElementById('usuario_sistema').value = datos.usuario_sistema ?? '';
                    document.getElementById('correo').value = datos.correo ?? '';
                    document.getElementById('n_licencia').value = datos.n_licencia ?? '';
                    document.getElementById('vigencia_licencia').value = datos.vigencia_licencia ?? '';
                    document.getElementById('tipo_sangre').value = datos.tipo_sangre ?? '';
                    document.getElementById('contacto_emergencia').value = datos.contacto_emergencia ?? '';
                    document.getElementById('telefono_emergencia').value = datos.telefono_emergencia ?? '';

                    document.getElementById('avatarPreview').src = datos.foto_perfil
                        ? '/storage/' + datos.foto_perfil
                        : '{{ asset("img/01.png") }}';

                    pintarEstatusLicencia(datos.vigencia_licencia);

                    document.getElementById('loadingState').style.display = 'none';
                    document.getElementById('perfilForm').style.display = 'block';
                })
                .catch(err => {
                    console.error('Error al cargar el perfil', err);
                    document.getElementById('loadingState').style.display = 'none';
                    document.getElementById('loadErrorState').style.display = 'block';
                });
        }

        document.addEventListener('DOMContentLoaded', cargarPerfil);

        // Vista previa de la foto de perfil antes de subir
        document.getElementById('fotoPerfilInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                document.getElementById('avatarPreview').src = URL.createObjectURL(file);
            }
        });

        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        //recuperar los datos
        $('#perfilForm').on('submit', function(e){
            e.preventDefault();
            
            //que recibimos del formulario
            const formulario = $(this).serialize();
            console.log('que recibimos', formulario);
        });
    </script>
</body>
</html>