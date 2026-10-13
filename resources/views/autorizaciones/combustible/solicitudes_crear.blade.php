@extends('layout.index')
@section('content')
<main class="main-content">
    <br><br><br><br>
    <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card border-0 shadow-lg p-4 rounded-4" style="border-left: 8px solid #38bdf8 !important;"> 
                    <!-- Encabezado con enfoque en Gasto -->
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-3 bg-soft-primary p-3 rounded-circle">
                            <i class="fa-solid fa-hand-holding-dollar text-primary" style="font-size: 30px"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold text-dark">Crea tus solicitudes ordinarias o extra-ordinarias</h4>
                            <p class="text-muted small mb-0">Registro de combustible para personal externo o viajes únicos sin registro</p>
                        </div>
                    </div>

                    <!-- Alerta informativa dinámicamente actualizada -->
                    <div class="alert alert-info d-flex align-items-center border-0 shadow-sm mb-4" role="alert" id="time_message">
                        <i class="fa-solid fa-circle-info me-3 fa-lg"></i>
                        <div id="texto-alerta">
                            Este registro se marcará por defecto como <strong>EXTRA_ORDINARIA</strong> y se sumará a la reposición del próximo jueves. Si activa la casilla, se registrará como una solicitud <strong>ORDINARIA</strong>.
                        </div>
                    </div>

                    <form id="form-Extraordinaria">
                        @csrf
                        <div class="row g-4">
                            <!-- Datos del Solicitante -->
                            <div class="col-md-7">
                                <label class="form-label fw-bold">Nombre del Beneficiario / Empresa Externa</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                    <input type="text" name="nombre_beneficiario" class="form-control" placeholder="Ej. Fletes México / Ing. Pedro Gómez" required>
                                </div>
                                <small class="text-muted">Nombre de la persona o razón social.</small>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-bold">Monto a Entregar</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white border-0">$</span>
                                    <input type="number" step="0.01" name="monto_solicitado" class="form-control form-control-lg border-2 border-success-subtle" placeholder="0.00" required>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-bold">Efectivale</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white border-0">💳</span>
                                    <input type="number" name="efectivale" class="form-control form-control-lg border-2 border-success-subtle" placeholder="146928" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Vehículo / Placas (Referencia)</label>
                                <input type="text" name="vehiculo_temporal" class="form-control" placeholder="Ej. Camión Torton Blanco / Placas 12-ABC">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-bold">Tipo de Combustible</label>
                                <select name="tipo_combustible" class="form-select">
                                    <option value="DIESEL" selected>DIESEL</option>
                                    <option value="MAGNA">MAGNA</option>
                                    <option value="PREMIUM">PREMIUM</option>
                                </select>
                            </div>

                            <div class="col-md-2" id="fecha">
                                <label class="form-label fw-bold">Fecha de registro</label>
                                <input type="date" name="fecha_registro" id="fecha_registro" class="form-control form-control-sm bg-light fs-7">
                            </div>

                            <hr class="my-2 opacity-25">

                            <!-- Justificación del Viaje -->
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Justificación / ¿Para qué se utilizará o a dónde cargar la solicitud?</label>
                                <textarea name="notas_admin" class="form-control" rows="3" placeholder="Ej. Traslado de material de cemento a obra X por falla de unidad interna..." required></textarea>
                            </div>

                            <!-- CAMPOS OCULTOS DINÁMICOS -->
                            <input type="hidden" name="estatus_solicitud" id="input_estatus_solicitud" value="EXTRA_ORDINARIA">
                            <input type="hidden" name="metodo_recargado" id="input_metodo_recargado" value="reposicion_caja">
                            <input type="hidden" name="estatus" value="autorizado"> 

                            <!-- Checkbox para cambiar el tipo de solicitud -->
                            <div class="col-12">
                                <div class="form-check form-check-reverse pe-3">
                                    <input class="form-check-input" type="checkbox" id="checkOrdinaria">
                                    <label class="form-check-label text-dark fw-bold" for="checkOrdinaria">
                                        ¿Registrar como solicitud ORDINARIA?
                                    </label>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="col-12 mt-4 text-end">
                                <button type="button" class="btn btn-light px-4 me-2" onclick="history.back()">Cancelar</button>
                                <button type="submit" id="crear_solicitud" class="btn btn-primary px-5 fw-bold shadow">
                                    <i class="fa-solid fa-floppy-disk me-2"></i> Crear solicitud
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .bg-soft-primary { background-color: rgba(56, 189, 248, 0.1); }
    .form-control, .form-select {
        border: 1px solid #dee2e6;
        padding: 0.6rem 0.8rem;
    }
    .form-control:focus {
        border-color: #38bdf8;
        box-shadow: 0 0 0 0.25rem rgba(56, 189, 248, 0.1);
    }
</style>

<script>
    $(document).ready(function() {
        // Establecer fecha de hoy por defecto en el input de fecha
        const today = new Date().toISOString().split('T')[0];
        $('#fecha_registro').val(today);

        // Control dinámico según el estado del Checkbox
        $('#checkOrdinaria').on('change', function() {
            if ($(this).is(':checked')) {
                // Configuración para ORDINARIA
                $('#input_estatus_solicitud').val('A_TIEMPO');
                $('#input_metodo_recargado').val('regular');
                
                $('#texto-alerta').html('Este registro se guardará como una solicitud <strong>ORDINARIA (A TIEMPO)</strong> con método regular.');
                $('#time_message').removeClass('alert-info').addClass('alert-success');
                //QUITAR CAMPO DE FECHA
                $('#fecha').style.display = 'none';
            } else {
                // Configuración para EXTRA_ORDINARIA (Por Defecto)
                $('#input_estatus_solicitud').val('EXTRA_ORDINARIA');
                $('#input_metodo_recargado').val('reposicion_caja');
                
                $('#texto-alerta').html('Este registro se marcará por defecto como <strong>EXTRA_ORDINARIA</strong> y se sumará a la reposición del próximo jueves.');
              

                $('#time_message').removeClass('alert-success').addClass('alert-info');
            }
        });

        // Submit vía AJAX
        $('#form-Extraordinaria').on('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Creando la solicitud...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            const form_crea = $(this).serialize();

            $.ajax({
                url: '/solicitudes/extraordinaria/guardar',
                type: 'POST',
                data: form_crea,
                success: function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: res.message 
                    });

                    // Limpiar formulario y resetear estado del check
                    $('#form-Extraordinaria')[0].reset();
                    $('#checkOrdinaria').trigger('change');
                    $('#fecha_registro').val(today);
                },
                error: function(xhr) {
                    let msgError = 'Ocurrió un error inesperado';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msgError = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: msgError
                    });
                }
            });
        });
    });
</script>
</main>
@endsection