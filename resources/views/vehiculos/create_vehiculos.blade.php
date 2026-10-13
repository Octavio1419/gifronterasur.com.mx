@extends('layout.index')

@section('content')
<main class="main-content mt-2">
    <br><br><br>
    <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    
                    <div class="glass-header d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold mb-1"><i class="fa-solid fa-car-on me-2"></i> Registro de Vehículos</h2>
                            <p class="mb-0 opacity-75">Expediente técnico para el alta de unidades en sistema.</p>
                        </div>
                        <div class="text-end d-none d-md-block">
                            <span class="badge bg-white text-dark py-2 px-3 rounded-pill fw-bold">UNIDAD NUEVA</span>
                        </div>
                    </div>

                    <div class="card-body">
                        <form id="formulario_registro_vehiculo" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="section-title"><i class="fa-solid fa-camera-retro"></i> Foto del vehiculo</div>
                                    <div class="photo-preview-container mb-4" id="drop-area">
                                        <div class="text-center" id="upload-placeholder">
                                            <i class="fa-solid fa-image-portrait fs-1 text-muted mb-3"></i>
                                            <p class="fw-bold text-muted mb-1">Subir Foto de la Unidad</p>
                                            <p class="small text-muted">Formatos: JPG, PNG (Máx 5MB)</p>
                                        </div>
                                        <img id="img-preview" src="#" alt="">
                                        <input type="file" name="foto_vehiculo" id="file_input" class="d-none" accept="image/*">
                                    </div>

                                    <div class="form-section-card bg-light border-dark">
                                        <label class="custom-label"># Placas</label>
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="plate-badge me-2">MEX</div>
                                            <input type="text" name="placas" id="in_placas" class="form-control form-control-pro fw-bold text-center text-uppercase fs-5" placeholder="ABC-123" required>
                                        </div>
                                        <label class="custom-label">Estado de Emplacado</label>
                                        <input type="text" name="estado_placa" class="form-control form-control-pro" placeholder="Ej. Chiapas, Tabasco..." required>
                                    </div>
                                </div>

                                <div class="col-lg-8">
                                    
                                    <div class="form-section-card">
                                        <div class="section-title"><i class="fa-solid fa-microchip"></i> Especificaciones de Fábrica</div>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="custom-label">Marca / Constructor</label>
                                                <input type="text" name="marca" class="form-control form-control-pro" placeholder="Ej. Nissan, Ford..." required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="custom-label">Línea o Submarca</label>
                                                <input type="text" name="linea" class="form-control form-control-pro" placeholder="Ej. Versa, Ranger..." required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="custom-label">Modelo (Año)</label>
                                                <input type="number" name="modelo" class="form-control form-control-pro" placeholder="2024" required>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="custom-label">Número de Serie (VIN)</label>
                                                <input type="text" name="serie" class="form-control form-control-pro text-uppercase" placeholder="Ingrese los 17 dígitos del chasis" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-section-card" style="border-left-color: #f57c00;">
                                        <div class="section-title" style="color: #f57c00;"><i class="fa-solid fa-file-shield"></i> Documentación y Protección</div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="custom-label">Número de Efecticard</label>
                                                <input type="text" name="efecticard" class="form-control form-control-pro" placeholder="N° Tarjeta de combustible">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="custom-label">Tarjeta de Circulación</label>
                                                <input type="text" name="tarjeta_circulacion" class="form-control form-control-pro" placeholder="N° de folio de tarjeta">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="custom-label">¿Cuenta con Seguro?</label>
                                                <select name="estatus_seguro" id="estatus_seguro" class="form-select form-control-pro">
                                                    <option value="1" selected>Vigente / Activo</option>
                                                    <option value="0">Sin Seguro</option>
                                                </select>
                                            </div>
                                            <div class="col-md-8 seccion-seguro">
                                                <label class="custom-label">Compañía Aseguradora</label>
                                                <input type="text" name="compania_seguro" class="form-control form-control-pro" placeholder="Ej. Qualitas, AXA, GNP...">
                                            </div>
                                            <div class="col-md-6 seccion-seguro">
                                                <label class="custom-label">Inicio Vigencia Seguro</label>
                                                <input type="date" name="fecha_inicio_seguro" class="form-control form-control-pro">
                                            </div>
                                            <div class="col-md-6 seccion-seguro">
                                                <label class="custom-label">Fin Vigencia Seguro</label>
                                                <input type="date" name="fecha_fin_seguro" class="form-control form-control-pro">
                                            </div>
                                            <div class="col-md-12">
                                                <label class="custom-label">Fecha de Pago Última Tenencia</label>
                                                <input type="date" name="ultima_tenencia" class="form-control form-control-pro">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-section-card" style="border-left-color: #2e7d32;">
                                        <div class="section-title text-success"><i class="fa-solid fa-user-check"></i> Asignación y Operación</div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="custom-label">Propietario Responsable</label>
                                                <select name="propietario_id" id="select_propietario" class="form-select form-control-pro" required>
                                                    <option value="">Cargando catálogo...</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="custom-label">Obra o Proyecto Destino</label>
                                                <select name="obra_id" id="select_obra" class="form-select form-control-pro">
                                                    <option value="">Disponible en Inventario (Sin Obra)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="custom-label">Kilometraje Inicial</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-gauge-high text-muted"></i></span>
                                                    <input type="number" name="km_actual" class="form-control form-control-pro border-start-0" value="0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-2 d-flex justify-content-between align-items-center bg-light p-2 rounded-4">
                                <button type="reset" class="btn btn-outline-secondary px-4 fw-bold">Limpiar</button>
                                <button type="submit" class="btn btn-primary px-3 py-2 fw-bold shadow-lg" style="border-radius: 12px; background: #0d47a1;" id="guardar_vehiculo">
                                    <i class="fa-solid fa-save me-2"></i>
                                    Guardar vehiculo
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    /* Estilo Enterprise */
    .main-content { background-color: #f0f2f5; }
    
    .glass-header {
        background: linear-gradient(135deg, #9da5ff 0%, #b0cdfa 100%);
        border-radius: 20px 20px 0 0;
        padding: 15px;
        color: white;
    }

    .form-section-card {
        background: #ffffff;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
        border-left: 5px solid #0d47a1;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }

    .section-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: #0d47a1;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
    }

    .section-title i { margin-right: 10px; font-size: 1.2rem; }

    .custom-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: #444;
        margin-bottom: 8px;
    }

    .form-control-pro {
        border: 1.5px solid #e0e0e0;
        border-radius: 10px;
        padding: 12px;
        font-size: 0.95rem;
        transition: all 0.3s;
    }

    .form-control-pro:focus {
        border-color: #0d47a1;
        box-shadow: 0 0 0 0.2rem rgba(13, 71, 161, 0.1);
    }

    .photo-preview-container {
        position: relative;
        width: 100%;
        height: 250px;
        border-radius: 15px;
        background: #f8f9fa;
        border: 2px dashed #cbd5e0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
        cursor: pointer;
        overflow: hidden;
    }

    .photo-preview-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0; left: 0;
        display: none;
    }

    .plate-badge {
        background: #333;
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        font-family: 'Courier New', Courier, monospace;
        font-weight: bold;
        letter-spacing: 2px;
        box-shadow: inset 0 0 5px rgba(255,255,255,0.2);
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Manejo de la imagen
   // Manejo de la imagen CORREGIDO
    $('#drop-area').on('click', function(e) {
        // Solo disparamos el click del input si el click NO vino directamente del input
        if (e.target !== document.getElementById('file_input')) {
            $('#file_input').click();
        }
    });

    // Detener la propagación para evitar el bucle infinito
    $('#file_input').on('click', function(e) {
        e.stopPropagation();
    });

    $('#file_input').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                $('#img-preview').attr('src', e.target.result).show();
                $('#upload-placeholder').hide();
            };
            reader.readAsDataURL(file);
        }
    });

    // Logica para mostrar/ocultar seguro
    $('#estatus_seguro').on('change', function() {
        const tieneSeguro = $(this).val() == "1";
        const $campos = $('.seccion-seguro input');

        if (!tieneSeguro) {
            // Efecto visual de desvanecimiento
            $('.seccion-seguro').css('opacity', '0.5'); 
            // Bloquear inputs para que no se pueda escribir
            $campos.prop('disabled', true);
            // Limpiar los valores para que no se envíen datos basura
            $campos.val(''); 
            // Quitar el atributo 'required' si lo tenían
            $campos.removeAttr('required');
        } else {
            //Restaurar opacidad
            $('.seccion-seguro').css('opacity', '1');
            //Habilitar inputs
            $campos.prop('disabled', false);
            //Opcional: Hacerlos obligatorios si hay seguro
            $campos.attr('required', true);
        }
    });

    //cargar datos dels eelct

    // Carga de catálogos mediante AJAX
    async function loadData() {
        try {
            const [p, o] = await Promise.all([
                fetch("{{ route('recuperar.propietarios') }}").then(r => r.json()),
                fetch("/obras/recuperar/obras-listadas").then(r => r.json())
            ]);
            
            let pOpts = '<option value="">-- Seleccione Propietario --</option>';
            p.data.forEach(x => pOpts += `<option value="${x.id}">${x.nombre} ${x.apellido_p} ${x.apellido_m}</option>`);
            $('#select_propietario').html(pOpts);

            let oOpts = '<option value="">Disponible en Inventario (Sin Obra)</option>';
            o.data.forEach(x => oOpts += `<option value="${x.id}">${x.nombre_obra}</option>`);
            $('#select_obra').html(oOpts);
        } catch (e) {
            console.error("Error al cargar catálogos");
        }
    }
    loadData();


    //recuperando datos del formulario}
    $('#guardar_vehiculo').click(function(e){
    e.preventDefault();
        var crear_vehiculo = new FormData($('#formulario_registro_vehiculo')[0]);

        // Validación básica antes de enviar
        if(!$('#in_placas').val() || !$('input[name="marca"]').val()){
            Swal.fire('Atención', 'Por favor rellena los campos obligatorios', 'warning');
            return;
        }

        Swal.fire({
            title: 'Guardando unidad...',
            text: 'Espere un momento por favor',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        $.ajax({
            url: "{{ route('vehiculos.guardar') }}", 
            method: "POST",
            data: crear_vehiculo,
            processData: false,
            contentType: false,
            success: function(response){
                if(response.success){
                    Swal.fire('¡Éxito!', response.message, 'success').then(() => {
                        window.location.href = "/vehiculos/listar"; // Redirige al listado
                    });
                }
            },
            error: function(xhr){
                Swal.fire('Error', 'Hubo un problema al guardar: ' + xhr.responseText, 'error');
            }
        });
    });
    
});
</script>
@endsection