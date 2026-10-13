@extends('layout.index')

@section('content')
<style>
    /* VARIABLES EJECUTIVAS */
    :root {
        --primary-blue: #3056d3; /* Un azul más profesional */
        --dark-navy: #090e30;
        --bg-gray: #f8fafc;
        --text-muted: #6b7280;
        --success-green: #10b981;
        --danger-red: #ef4444;
    }

    .main-content { background-color: var(--bg-gray); min-height: 100vh; padding: 40px 0; font-family: 'Inter', sans-serif; }
    
    /* CARD DISEÑO EJECUTIVO UPGRADED */
    .card-chofer-premium { 
        border: none; border-radius: 24px; background: white; 
        transition: all 0.3s ease;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.02), 0 4px 6px -2px rgba(0,0,0,0.01);
        position: relative; overflow: visible; /* Importante para que la foto sobresalga */
        border: 1px solid rgba(0,0,0,0.04);
    }
    .card-chofer-premium:hover { 
        transform: translateY(-8px); 
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04); 
    }
    
    /* Foto de perfil flotante */
    .perfil-container {
        position: absolute; top: -30px; left: 30px;
        width: 100px; height: 100px;
        border-radius: 50%; background: white; padding: 8px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }
    .img-perfil-exec { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }

    /* Estatus en la esquina */
    .status-badge-exec { 
        position: absolute; top: 20px; right: 20px;
        font-size: 0.75rem; font-weight: 700; padding: 6px 16px; 
        border-radius: 30px; letter-spacing: 0.5px;
    }
    .status-active { background-color: #d1fae5; color: var(--success-green); }
    .status-inactive { background-color: #fee2e2; color: var(--danger-red); }

    /* Detalles del Chofer */
    .chofer-details-exec { padding-top: 80px; }
    .chofer-name-exec { font-size: 1.25rem; font-weight: 800; color: var(--dark-navy); line-height: 1.2; }
    .chofer-lastname-exec { font-size: 1rem; color: var(--text-muted); font-weight: 500; text-transform: uppercase; }

    /* Sección Vehículo con Diseño Limpio */
    .vehicle-info-exec { 
        background-color: #f1f5f9; border-radius: 18px; padding: 15px 20px; 
        margin-top: 25px; border: 1px solid #e2e8f0;
    }
    .vehicle-title-exec { font-size: 0.85rem; text-transform: uppercase; font-weight: 800; color: var(--dark-navy); letter-spacing: 0.5px; }
    .vehicle-sub-exec { font-size: 0.8rem; color: var(--text-muted); font-weight: 500; }
    .placas-exec { font-size: 1rem; color: #1e293b; font-weight: 700; }

    /* Iconos y Licencia */
    .icon-box-exec { width: 35px; color: var(--primary-blue); font-size: 1.1rem; }
    .licencia-box-exec { font-size: 0.9rem; color: #1e293b; font-weight: 600; margin-top: 15px; }

    /* Botón de Gestión Estilizado */
    .btn-gestion-exec { 
        border-radius: 30px; font-weight: 700; padding: 12px 24px; font-size: 0.9rem;
        transition: all 0.3s ease; border: none;
        background-color: var(--primary-blue); color: white;
        box-shadow: 0 8px 15px rgba(48, 86, 211, 0.2);
    }
    .btn-gestion-exec:hover { 
        background-color: #254ac7; 
        box-shadow: 0 12px 20px rgba(48, 86, 211, 0.3);
        transform: scale(1.01);
    }
</style>

<main class="main-content">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5 mt-4 p-4 rounded-4" style="background: white; border: 1px solid rgba(0,0,0,0.03);">
            <div>
                <h2 class="fw-bold text-dark mb-1">Panel de Gestión de Operadores</h2>
                <p class="text-muted small mb-0">Expedientes digitales y control de flota en tiempo real</p>
            </div>
        </div>

        <div class="row g-5"> @foreach($choferes as $c)
            <div class="col-md-6 col-lg-4">
                <div class="card card-chofer-premium h-100">
                    <div class="perfil-container">
                        <img src="{{ asset($c->foto_chofer ? 'storage/'.$c->foto_chofer : 'assets/img/01.png') }}" class="img-perfil-exec border" alt="Perfil">
                    </div>

                    <span class="status-badge-exec {{ $c->estatus ? 'status-active' : 'status-inactive' }}">
                        ● {{ $c->estatus ? 'ACTIVO' : 'BAJA' }}
                    </span>

                    <div class="card-body chofer-details-exec px-4 pb-4">
                        <h4 class="chofer-name-exec mb-1" style="background: ">{{ $c->nombre }}</h4>
                        <p class="chofer-lastname-exec mb-0">{{ $c->apellido_p }} {{ $c->apellido_m }}</p>
                        
                        <div class="vehicle-info-exec shadow-inner">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box-exec"><i class="fas fa-truck-moving"></i></div>
                                    <div>
                                        <div class="vehicle-title-exec mb-0">{{ $c->marca }}</div>
                                        <div class="vehicle-sub-exec">{{ $c->linea }}</div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="placas-exec">{{ $c->placas }}</div>
                                    <small class="text-muted small">Placas</small>
                                </div>
                            </div>
                        </div>

                        <div class="licencia-box-exec d-flex align-items-center p-2 rounded-3" style="background: #fdfdfd;">
                            <div class="icon-box-exec"><i class="fas fa-id-card-alt"></i></div>
                            <span class="text-muted small me-2">Licencia:</span>
                            <span class="fw-bold">{{ $c->n_licencia }}</span>
                        </div>

                        <div class="mt-4 pt-2">
                            <button class="btn btn-gestion-exec ver-detalle w-100 shadow-sm" 
                                    data-chofer="{{ json_encode($c) }}">
                                <i class="fas fa-folder-open me-2"></i> GESTIONAR EXPEDIENTE
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</main>
<div class="modal fade modal-premium" id="modalChofer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 30px; overflow: hidden;">
            <div class="row g-0">
                
                <div class="col-lg-4 p-5 text-white" style="background: linear-gradient(135deg, #001cd9 0%, #000000 100%);">
                    <div class="text-center">
                        <div class="position-relative d-inline-block mb-4">
                            <img id="m_perfil_img" src="" class="rounded-circle shadow-lg border border-4 border-primary" 
                                 style="width: 150px; height: 150px; object-fit: cover;">
                            <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle" 
                                  style="width: 25px; height: 25px;"></span>
                        </div>
                        <h3 id="m_nombre_full" class="fw-bold mb-1" style="color: #fff"></h3>
                        <p id="m_usuario_badge" class="badge bg-primary px-3 rounded-pill mb-4"></p>
                    </div>

                    <div class="mt-4 space-y-3">
                        <div class="d-flex align-items-center mb-3 bg-white bg-opacity-10 p-1 rounded-4">
                            <i class="fas fa-phone-alt me-3 text-primary"></i>
                            <div>
                                <small class="d-block opacity-50">Teléfono Personal</small>
                                <span id="m_side_tel" class="fw-bold"></span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3 bg-white bg-opacity-10 p-1 rounded-4">
                            <i class="fas fa-tint me-3 text-danger"></i>
                            <div>
                                <small class="d-block opacity-50">Tipo de Sangre</small>
                                <span id="m_side_sangre" class="fw-bold"></span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3 bg-white bg-opacity-10 p-1 rounded-4">
                            <i class="fas fa-id-card me-3 text-warning"></i>
                            <div>
                                <small class="d-block opacity-50">Vencimiento Licencia</small>
                                <span id="m_side_vence" class="fw-bold"></span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 p-2 rounded-4" style="background: rgb(231 0 0 / 54%); border: 1px border rgba(239, 68, 68, 0.3);">
                        <h6 class="fw-bold mb-2"><i class="fas fa-exclamation-triangle me-2"></i>Emergencia</h6>
                        <p class="mb-1 small" id="m_em_nom"></p>
                        <p class="mb-0 fw-bold" id="m_em_tel"></p>
                    </div>
                </div>

                <div class="col-lg-8 bg-white p-5">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <ul class="nav nav-pills custom-tabs" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active me-2 px-4 rounded-pill fw-bold" data-bs-toggle="pill" data-bs-target="#tab-docs">
                                    <i class="fas fa-images me-2"></i>DOCUMENTOS
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link px-4 rounded-pill fw-bold" data-bs-toggle="pill" data-bs-target="#tab-edit">
                                    <i class="fas fa-edit me-2"></i>EDITAR PERFIL
                                </button>
                            </li>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="tab-content" id="pills-tabContent">
                        
                        <div class="tab-pane fade show active" id="tab-docs" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="text-muted small fw-bold mb-2 d-block">IDENTIFICACIÓN (FRENTE)</label>
                                    <div class="doc-card">
                                        <img id="m_img_ine_f" src="" class="img-fluid rounded-4 shadow-sm pointer" onclick="window.open(this.src)">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small fw-bold mb-2 d-block">IDENTIFICACIÓN (REVERSO)</label>
                                    <div class="doc-card">
                                        <img id="m_img_ine_r" src="" class="img-fluid rounded-4 shadow-sm pointer" onclick="window.open(this.src)">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small fw-bold mb-2 d-block">LICENCIA (FRENTE)</label>
                                    <div class="doc-card">
                                        <img id="m_img_lic_f" src="" class="img-fluid rounded-4 shadow-sm pointer" onclick="window.open(this.src)">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small fw-bold mb-2 d-block">LICENCIA (REVERSO)</label>
                                    <div class="doc-card">
                                        <img id="m_img_lic_r" src="" class="img-fluid rounded-4 shadow-sm pointer" onclick="window.open(this.src)">
                                    </div>
                                </div>
                            </div>
                        </div>

                    <div class="tab-pane fade" id="tab-edit" role="tabpanel">
    <form id="form-update-chofer" class="row g-3">
        @csrf
        <input type="hidden" name="chofer_id" id="m_edit_id">

        <div class="col-12"><h6 class="fw-bold text-primary mb-2 small text-uppercase">Información Personal</h6></div>
        
        <div class="col-md-4">
            <div class="form-floating">
                <input type="text" name="nombre" id="e_nombre" class="form-control border-0 bg-light rounded-4 fw-bold" placeholder="Nombre">
                <label>Nombre(s)</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input type="text" name="apellido_p" id="e_apellido_p" class="form-control border-0 bg-light rounded-4 fw-bold" placeholder="Paterno">
                <label>Apellido Paterno</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input type="text" name="apellido_m" id="e_apellido_m" class="form-control border-0 bg-light rounded-4 fw-bold" placeholder="Materno">
                <label>Apellido Materno</label>
            </div>
        </div>

        <div class="col-12 mt-3"><h6 class="fw-bold text-primary mb-2 small text-uppercase">Seguridad y Acceso</h6></div>
        
        <div class="col-md-4">
            <div class="form-floating">
                <input type="text" name="usuario_sistema" id="e_usuario_sistema" class="form-control border-0 bg-light rounded-4 fw-bold" placeholder="Usuario">
                <label>Usuario Sistema</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input type="password" name="password" id="e_password" class="form-control border-0 bg-light rounded-4" placeholder="Password">
                <label>Nueva Contraseña</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input type="password" name="password_confirmation" id="e_password_conf" class="form-control border-0 bg-light rounded-4" placeholder="Confirmar">
                <label>Confirmar Password</label>
            </div>
        </div>

        <div class="col-12 mt-3"><h6 class="fw-bold text-primary mb-2 small text-uppercase">Datos Operativos y Médicos</h6></div>
        
        <div class="col-md-3">
            <div class="form-floating">
                <input type="text" name="telefono" id="e_tel" class="form-control border-0 bg-light rounded-4 fw-bold" placeholder="Tel">
                <label>Teléfono Móvil</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <input type="text" name="n_licencia" id="e_n_licencia" class="form-control border-0 bg-light rounded-4 fw-bold" placeholder="Licencia">
                <label>N° Licencia</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <input type="date" name="vigencia_licencia" id="e_vigencia" class="form-control border-0 bg-light rounded-4 fw-bold" placeholder="Vigencia">
                <label>Vigencia Licencia</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <select name="tipo_sangre" id="e_tipo_sangre" class="form-select border-0 bg-light rounded-4 fw-bold">
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                </select>
                <label>Tipo Sangre</label>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-floating">
                <select name="vehiculo_id" id="e_vehiculo_id" class="form-select border-0 bg-light rounded-4 fw-bold">
                    @foreach($vehiculos as $v)
                        <option value="{{ $v->id }}">{{ $v->marca }} {{ $v->linea }} - {{ $v->placas }}</option>
                    @endforeach
                </select>
                <label>Vehículo Asignado</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <select name="estatus" id="e_estatus" class="form-select border-0 bg-light rounded-4 fw-bold">
                    <option value="1">Activo / En Ruta</option>
                    <option value="0">Inactivo / Suspendido</option>
                </select>
                <label>Estatus Operativo</label>
            </div>
        </div>

        <div class="col-12 mt-3"><h6 class="fw-bold text-danger mb-2 small text-uppercase">Contacto de Emergencia</h6></div>
        
        <div class="col-md-6">
            <div class="form-floating">
                <input type="text" name="contacto_emergencia" id="e_em_nom" class="form-control border-0 bg-light rounded-4 fw-bold" placeholder="Nombre">
                <label>Nombre Contacto</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <input type="text" name="telefono_emergencia" id="e_em_tel" class="form-control border-0 bg-light rounded-4 fw-bold" placeholder="Tel">
                <label>Teléfono Emergencia</label>
            </div>
        </div>

        <div class="col-12 text-end mt-4">
            <button type="button" class="btn btn-link text-muted fw-bold text-decoration-none me-3" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill fw-bold shadow-sm">
                GUARDAR CAMBIOS
            </button>
        </div>
    </form>
</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos extra para el modal */
    .custom-tabs .nav-link { color: #64748b; background: #f1f5f9; margin-bottom: 10px; border: none; }
    .custom-tabs .nav-link.active { background: #3056d3 !important; color: white !important; box-shadow: 0 10px 20px rgba(48, 86, 211, 0.2); }
    .doc-card { transition: all 0.3s ease; border: 2px solid transparent; border-radius: 18px; overflow: hidden; }
    .doc-card:hover { transform: translateY(-5px); border-color: #3056d3; }
    .doc-card img { cursor: pointer; height: 180px; width: 100%; object-fit: cover; }
    .pointer { cursor: pointer; }
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let APP_URL = "{{ asset('/storage') }}"; 
    if (!APP_URL.endsWith('/')) APP_URL += '/';

    $('.ver-detalle').click(function() {
        const c = $(this).data('chofer');
        
        // Sidebar del Modal
        $('#m_perfil_img').attr('src', APP_URL + (c.foto_chofer || 'assets/img/default-user.png'));
        $('#m_nombre_full').text(`${c.nombre} ${c.apellido_p}`);
        $('#m_usuario_badge').text(`@${c.usuario_sistema}`);
        $('#m_side_tel').text(c.telefono || 'S/N');
        $('#m_side_sangre').text(c.tipo_sangre || '--');
        $('#m_side_vence').text(c.vigencia_licencia || '--');
        $('#m_em_nom').text(c.contacto_emergencia || 'No asignado');
        $('#m_em_tel').text(c.telefono_emergencia || '');

        // Documentos
        $('#m_img_ine_f').attr('src', APP_URL + c.foto_ine);
        console.log('es', APP_URL + c.foto_ine);
        $('#m_img_ine_r').attr('src', APP_URL + c.foto_ine_reverso);
        $('#m_img_lic_f').attr('src', APP_URL + c.foto_licencia_enfre);
        $('#m_img_lic_r').attr('src', APP_URL + c.foto_licencia_reverso);

        // Formulario Editar
        $('#m_edit_id').val(c.id);
        $('#e_nombre').val(c.nombre);
        $('#e_tel').val(c.telefono);
        $('#e_em_nom').val(c.contacto_emergencia);
        $('#e_em_tel').val(c.telefono_emergencia);
        $('#e_estatus').val(c.estatus);
        $('#e_apellido_p').val(c.apellido_p);
        $('#e_apellido_m').val(c.apellido_m);
        $('#e_usuario_sistema').val(c.usuario_sistema);
        $('#e_n_licencia').val(c.n_licencia);
        $('#e_vigencia').val(c.vigencia_licencia);
        $('#e_tipo_sangre').val(c.tipo_sangre);
        $('#e_vehiculo_id').val(c.vehiculo_id);
        
        $('#modalChofer').modal('show');
    });
    // Acción de Actualizar
    $('#form-update-chofer').submit(function(e) {
        e.preventDefault();
        // Alerta de carga
        Swal.fire({
            title: 'Procesando...',
            text: 'Actualizando expediente del operador',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        $.ajax({
            url: "/choferes/actualizar",
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if(res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Actualizado!',
                        text: 'Los datos se guardaron correctamente.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload(); 
                    });
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            },
            error: function(xhr) {
                let errorMsg = xhr.responseJSON ? xhr.responseJSON.message : 'Error interno del servidor';
                Swal.fire('Error', errorMsg, 'error');

                console.log('error al actualizar', xhr.responseText);
            }
        });
    });
});
</script>
@endsection