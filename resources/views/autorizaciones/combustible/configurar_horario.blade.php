@extends('layout.index')
@section('content')
<main class="main-content">
    <br><br><br><br>

    <style>
        .day-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 12px 6px;
            border-radius: 12px;
            border: 2px solid #e9ecef;
            background: #fff;
            color: #495057;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.15s ease;
            user-select: none;
        }

        .day-chip:hover {
            border-color: #b8c6ff;
            background: #f5f7ff;
        }

        .day-chip.active {
            background: linear-gradient(135deg, #4f6df5, #3d56d6);
            border-color: #3d56d6;
            color: #fff;
            box-shadow: 0 4px 10px rgba(61, 86, 214, 0.25);
        }

        .day-chip input {
            display: none;
        }

        .time-box {
            background: #f8f9fc;
            border: 1px solid #e9ecef;
            border-radius: 14px;
            padding: 18px;
        }

        .time-box label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #6c757d;
            margin-bottom: 8px;
            display: block;
        }

        .time-box input[type="time"] {
            border: 1px solid #dbe0e8;
            border-radius: 10px;
            padding: 10px 12px;
            font-weight: 600;
            font-size: 1.05rem;
            width: 100%;
        }

        .preview-banner {
            background: linear-gradient(135deg, #eef1ff, #f8f9ff);
            border: 1px dashed #b8c6ff;
            border-radius: 14px;
            padding: 16px 18px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .preview-banner .icon-wrap {
            width: 46px;
            height: 46px;
            min-width: 46px;
            border-radius: 50%;
            background: #4f6df5;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .preview-text strong {
            color: #2b2f42;
        }

        .bg-soft-primary {
            background: #eef1ff;
            color: #4f6df5;
        }

        .btn-guardar {
            background: linear-gradient(135deg, #4f6df5, #3d56d6);
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 12px 28px;
            border-radius: 12px;
            transition: transform 0.12s ease, box-shadow 0.12s ease;
        }

        .btn-guardar:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(61, 86, 214, 0.3);
            color: #fff;
        }

        @media (max-width: 576px) {
            .day-chip { font-size: 0.72rem; padding: 10px 4px; }
        }
    </style>

    <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card border-0 shadow-lg p-4 rounded-4">
                    <!-- Encabezado con enfoque en Gasto -->
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-3 bg-soft-primary p-3 rounded-circle">
                            <i class="fa-solid fa-calendar-check" style="font-size: 30px"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold text-dark">Configuración de horarios de solicitudes</h4>
                            <p class="text-muted small mb-0">Define el día y hora en que estará abierto para recibir solicitudes de combustible</p>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center gap-2 rounded-3" role="alert">
                            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger rounded-3">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="form-config">
                        @csrf
                        <!-- Selector de día -->
                        <div class="mb-4">
                            <label class="fw-bold text-dark mb-3 d-block">
                                <i class="fa-solid fa-calendar-day me-1 text-primary"></i> Día habilitado
                            </label>
                            <div class="row g-2">
                                @php
                                    $dias = [ 0 => 'Lun', 1 => 'Mar', 2 => 'Mié', 3 => 'Jue', 4 => 'Vie', 5 => 'Sáb',6 => 'Dom'];
                                    $diaSeleccionado = old('dia_semana', $config->dia_semana ?? 4);
                                @endphp
                                @foreach($dias as $valor => $nombre)
                                    <div class="col">
                                        <label class="day-chip {{ (int)$diaSeleccionado === $valor ? 'active' : '' }}" data-dia="{{ $valor }}">
                                            <input type="radio" name="dia_semana" value="{{ $valor }}" {{ (int)$diaSeleccionado === $valor ? 'checked' : '' }}>
                                            {{ $nombre }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Rango de horario -->
                        <div class="mb-4">
                            <label class="fw-bold text-dark mb-3 d-block">
                                <i class="fa-solid fa-clock me-1 text-primary"></i> Rango de horario
                            </label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="time-box">
                                        <label for="hora_inicio">Hora de apertura</label>
                                        <input type="time" id="hora_inicio" name="hora_inicio"
                                               value="{{ old('hora_inicio', isset($config->hora_inicio) ? \Carbon\Carbon::parse($config->hora_inicio)->format('H:i') : '07:00') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="time-box">
                                        <label for="hora_fin">Hora de cierre</label>
                                        <input type="time" id="hora_fin" name="hora_fin"
                                               value="{{ old('hora_fin', isset($config->hora_fin) ? \Carbon\Carbon::parse($config->hora_fin)->format('H:i') : '20:00') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vista previa dinámica -->
                        <div class="preview-banner mb-4">
                            <div class="icon-wrap">
                                <i class="fa-solid fa-gas-pump"></i>
                            </div>
                            <div class="preview-text">
                                <div class="small text-muted mb-1">Así se mostrará a los choferes</div>
                                <strong id="preview-texto">Cargando vista previa...</strong>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-guardar">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Guardar cambios
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</main>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    (function () {
        // Objeto JS válido (antes tenías sintaxis de PHP, por eso truena)
        const array_dias = {
            0: 'lunes',
            1: 'martes',
            2: 'miércoles',
            3: 'jueves',
            4: 'viernes',
            5: 'sábado',
            6: 'domingo'
        };

        const chips = document.querySelectorAll('.day-chip');
        const horaInicio = document.getElementById('hora_inicio');
        const horaFin = document.getElementById('hora_fin');
        const preview = document.getElementById('preview-texto');

        function actualizarPreview() {
            const chipActivo = document.querySelector('.day-chip.active');
            const dia = chipActivo ? array_dias[chipActivo.dataset.dia] : '—';
            const inicio = horaInicio.value || '--:--';
            const fin = horaFin.value || '--:--';
            preview.textContent = `Cada ${dia}, de ${inicio} a ${fin} hrs.`;
        }

        function marcarDiaActivo(diaSeleccionado) {
            chips.forEach(chip => {
                const input = chip.querySelector('input[type="radio"]');
                const esEsteDia = parseInt(input.value) === parseInt(diaSeleccionado);
                chip.classList.toggle('active', esEsteDia);
                input.checked = esEsteDia;
            });
        }

        // Listeners de interacción (se registran una sola vez)
        chips.forEach(chip => {
            chip.addEventListener('click', function () {
                chips.forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                this.querySelector('input[type="radio"]').checked = true;
                actualizarPreview();
            });
        });

        horaInicio.addEventListener('input', actualizarPreview);
        horaFin.addEventListener('input', actualizarPreview);

        // Recuperamos el horario actual guardado en BD
        fetch('/combustible/solicitudes/configurar/horario/recuperar')
            .then(response => response.json())
            .then(data => {
                if (data && data.dia_semana !== undefined && data.dia_semana !== null) {
                    marcarDiaActivo(data.dia_semana);
                    horaInicio.value = data.horario_inicio ? data.horario_inicio.substring(0, 5) : horaInicio.value;
                    horaFin.value = data.horario_finalizar ? data.horario_finalizar.substring(0, 5) : horaFin.value;
                }
                actualizarPreview();
            })
            .catch(err => {
                console.log('Error al recuperar horario:', err);
                actualizarPreview();
            });

        // Envío del formulario para actualizar el horario
        $('#form-config').on('submit', function (e) {
            e.preventDefault();
            const formData = $(this).serialize();

            Swal.fire({
                title: 'Actualizando el rango de horario...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: '/combustible/solicitudes/configurar/horario/actualizar',
                method: 'POST',
                data: formData,
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.message || 'El rango de horario ha sido actualizado correctamente.',
                        confirmButtonText: 'Aceptar'
                    });
                },
                error: function (xhr) {
                    console.log('el error es:', xhr.responseText);
                    let errorMsg = 'Ocurrió un error al actualizar el rango de horario.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMsg = Object.values(xhr.responseJSON.errors).flat().join(' ');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMsg,
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        });
    })();
</script>
@endsection