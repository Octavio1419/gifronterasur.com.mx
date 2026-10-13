@extends('layout.index') <!-- Extiende del layout base -->
@section('content')
    <main class="main-content">
      <div class="position-relative iq-banner">
          <div class="iq-navbar-header dash-hero-ledger" style="height: 215px;">
              <div class="hero-dot-grid"></div>
              <div class="hero-scrim"></div>
              <div class="container-fluid iq-container">
                  <div class="row">
                      <div class="col-md-12">
                          <div class="flex-wrap d-flex justify-content-between align-items-center" id="messges">
                              <div>
                                  <span class="eyebrow-ledger"><span class="diamond-bullet"></span> Mi Perfil</span>
                                  <h1 id="mensaje_buenos_dias_tardes" class="hero-title-ledger"></h1>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="iq-header-img" style="top:0px; height: 275px;">
                 <img src="" alt="header" class="theme-color-default-img img-fluid w-100 h-100 animated-scaleX" id="img_fondo_perfil">
              </div>
          </div>
      </div>
      <div class="conatiner-fluid content-inner mt-n5 py-0">
      <div class="row">
          <div class="col-lg-12" style="z-index: 999;">
             <div class="ledger-bar mb-4">
                  <div class="d-flex flex-wrap align-items-center justify-content-between p-3">
                     <div class="d-flex flex-wrap align-items-center">
                        <div class="profile-img position-relative me-3 mb-3 mb-lg-0 profile-logo profile-logo1">
                           <img src="" alt="User-Profile" class="theme-color-default-img img-fluid rounded-pill avatar-100 ledger-avatar-ring" id="foto_perfil_actualizada">
                        </div>
                        <div class="d-flex flex-wrap align-items-center mb-3 mb-sm-0">
                           <h4 class="me-2 ledger-user-name" id="usuario_logueado"></h4>
                           <span id="rol_usuario"> </span>
                        </div>
                     </div>
                     <ul class="d-flex nav nav-pills mb-0 text-center ledger-tabs" data-toggle="slider-tab" id="profile-pills-tab" role="tablist">
                        <li class="nav-item">
                           <a class="nav-link active show" data-bs-toggle="tab" href="#profile-profile" role="tab" aria-selected="false">Perfil</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" data-bs-toggle="tab" href="#profile-friends" role="tab" aria-selected="false">Asignar tareas</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" data-bs-toggle="tab" href="#profile-mis-tareas" role="tab" aria-selected="false">Tareas asignadas</a>
                        </li>
                     </ul>
                  </div>
             </div>
          </div>
          <div class="col-lg-3">
            <div class="ledger-card mb-4">
                  <div class="ledger-card-header">
                     <div class="ledger-section-label"><span class="diamond-bullet diamond-bullet--sm"></span> Notificaciones</div>
                  </div>
                  <div class="ledger-card-body" id="lista-notificaciones">
                  </div>
               </div>
             <div class="ledger-card mb-4">
               <div class="ledger-card-header d-flex align-items-center justify-content-between">
                  <div class="ledger-section-label"><span class="diamond-bullet diamond-bullet--sm"></span> Galería</div>
                  <span class="gallery-count-pill" id="total_fotos_galeria"></span>
               </div>
               <div class="ledger-card-body">
                  <div class="d-grid gap-card grid-cols-3" id="mostrar_fotos_galeria">
                  </div>
               </div>
             </div>
             <div class="ledger-card">
               <div class="ledger-card-body text-center">
                  <label for="uploadGaleria" class="btn-ledger-outline" id="subir_galeria">
                     <i class="fa-solid fa-images"></i> Agregar imagen
                  </label>
               </div>
            </div>

          </div>
          <div class="col-lg-6">
             <div class="profile-content tab-content">

<div id="profile-friends" class="tab-pane fade">
                  <div class="ledger-card">
                     <div class="ledger-card-header">
                        <div class="ledger-section-label"><span class="diamond-bullet diamond-bullet--sm"></span> Listado de tareas</div>
                     </div>
                     <div class="ledger-card-body">
                        <ul class="list-inline m-0 p-0">
                             <div class="col-lg-12 group__item" id="card_task">
                               <div class="iq-media-group-1">

                           </div>
                            </div>

                        </ul>
                     </div>
                  </div>
               </div>
                 <div id="profile-mis-tareas" class="tab-pane fade">
                  <div class="ledger-card">
                     <div class="ledger-card-header">
                        <div class="ledger-section-label"><span class="diamond-bullet diamond-bullet--sm"></span> Mis tareas</div>
                     </div>
                     <div class="ledger-card-body">
                        <ul class="list-inline m-0 p-0">
                             <div class="col-lg-12 group__item" id="card_mis_task">
                               <div class="iq-media-group-1">

                           </div>
                            </div>

                        </ul>
                     </div>
                  </div>
               </div>
               <div id="profile-profile" class="tab-pane fade active show">
                  <div class="ledger-card mb-4">
                     <div class="ledger-card-header">
                        <div class="ledger-section-label"><span class="diamond-bullet diamond-bullet--sm"></span> Mi perfil</div>
                         <div class="edit-profile-btn" id="edit-perfil" title="Editar perfil">
                           <i class="fa-solid fa-user-pen"></i>
                        </div>
                     </div>
                     <div class="ledger-card-body">
                        <div class="text-center">
                           <div class="user-profile">
                              <img src="" alt="profile-img" class="rounded-pill avatar-130 img-fluid ledger-avatar-ring">
                           </div>
                           <div class="mt-3">
                              <h3 class="d-inline-block ledger-profile-name" id="user-name"></h3>
                              <p class="d-inline-block pl-3 ledger-profile-role" id="user-role"></p>
                              <p class="mb-0 ledger-profile-desc" id="user-description"></p>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="ledger-card">
                     <div class="ledger-card-header">
                        <div class="ledger-section-label"><span class="diamond-bullet diamond-bullet--sm"></span> Datos del usuario</div>
                     </div>
                     <div class="ledger-card-body ledger-data-list">
                        <div class="mt-2" id="user-created">
                        </div>
                        <div class="mt-2" id="user-direction">
                        </div>
                        <div class="mt-2" id="user-email">
                        </div>
                        <div class="mt-2" id="user-curp">
                        </div>
                        <div class="mt-2" id="user-telefono">
                        </div>
                     </div>
                  </div>
               </div>
            </div>
          </div>

         <div class="col-lg-3">
             <div class="ledger-card mb-4">
               <div class="ledger-card-header">
                  <div class="d-flex align-items-center gap-2">
                     <div class="diamond-bezel diamond-bezel--sm"><i class="fa-solid fa-store"></i></div>
                     <div class="header-title" id="sucursal-title"></div>
                  </div>
               </div>
               <div class="ledger-card-body" id="datos-tienda">
               </div>
             </div>

             <div class="ledger-card">
               <div class="ledger-card-header">
                  <div class="ledger-section-label"><span class="diamond-bullet diamond-bullet--sm"></span> Crear nuevas tareas</div>
               </div>
               <div class="ledger-card-body">
                  <ul class="list-inline m-0 p-0">
                        <button class="btn-ledger-primary w-100 justify-content-center" id="modal-asignar-tareas">
                           <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                           Crear tarea
                        </button>
                  </ul>
               </div>
             </div>

         </div>
      </div>
    </div>

 <!-- ========================================== -->
<!-- 1. MODAL PARA EDITAR EL USUARIO (REDISEÑADO) -->
<!-- ========================================== -->
<div class="modal fade" id="modal-perfil" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
      
      <!-- Header Moderno Glassmorphism / Clean Dark -->
      <div class="modal-header bg-dark text-white px-4 py-3 border-0">
        <h5 class="modal-title fw-bold d-flex align-items-center fs-5"  style="color: #fff;">
          <div class="bg-primary bg-opacity-25 p-2 rounded-3 me-3 text-primary d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
            <i class="fa-solid fa-user-pen fs-6"></i>
          </div>
          Editar Perfil de Usuario
        </h5>
        <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <form id="form-editar-usuario" enctype="multipart/form-data" method="post" class="needs-validation" novalidate>
        <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

        <!-- Hero Section del Perfil dentro del Modal -->
        <div class="position-relative bg-light border-bottom text-center py-4 px-3 overflow-hidden">
          <img src="{{ asset('img/01.png') }}" alt="Fondo difuminado" id="previw-fondo"
               class="position-absolute top-0 start-50 translate-middle-x w-100 h-100 object-fit-cover filter-blur-custom opacity-25 z-0">
          
          <div class="position-relative z-1 d-inline-block">
            <img src="{{ asset('img/01.png') }}" alt="Foto de perfil" id="foto-preview"
                 class="rounded-circle shadow-sm border border-4 border-white object-fit-cover bg-white"
                 style="width: 120px; height: 120px;">
            
            <label for="input-foto" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 shadow-sm d-flex align-items-center justify-content-center transition-all hover-scale" style="cursor: pointer; width: 36px; height: 36px;" title="Cambiar fotografía">
              <i class="fa-solid fa-camera fs-6"></i>
            </label>
            <input type="file" name="foto" id="input-foto" accept="image/*" class="d-none">
          </div>
          <div class="position-relative z-1 mt-2">
            <span class="badge bg-dark bg-opacity-75 text-white fw-normal px-3 py-1 rounded-pill small">Haz clic en la cámara para actualizar tu foto</span>
          </div>
        </div>

        <!-- Cuerpo del Formulario con Grid Estilizado -->
        <div class="modal-body p-4 bg-body">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label fw-semibold text-secondary small text-uppercase" for="modal-nombre">Nombre</label>
              <input type="text" class="form-control rounded-3 py-2 bg-light border-0 focus-bg-white" id="modal-nombre">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold text-secondary small text-uppercase" for="modal-apellido-paterno">Apellido Paterno</label>
              <input type="text" class="form-control rounded-3 py-2 bg-light border-0 focus-bg-white" id="modal-apellido-paterno">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold text-secondary small text-uppercase" for="modal-apellido-materno">Apellido Materno</label>
              <input type="text" class="form-control rounded-3 py-2 bg-light border-0 focus-bg-white" id="modal-apellido-materno">
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold text-secondary small text-uppercase" for="modal-correo">Correo Electrónico</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-0 text-muted px-3"><i class="fa-regular fa-envelope"></i></span>
                <input type="email" class="form-control rounded-end-3 py-2 bg-light border-0 focus-bg-white" id="modal-correo">
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold text-secondary small text-uppercase" for="modal-curp">CURP</label>
              <input type="text" class="form-control rounded-3 py-2 bg-light border-0 focus-bg-white text-uppercase" id="modal-curp">
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold text-secondary small text-uppercase" for="modal-telefono">Teléfono</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-0 text-muted px-3"><i class="fa-solid fa-phone"></i></span>
                <input type="tel" class="form-control rounded-end-3 py-2 bg-light border-0 focus-bg-white" id="modal-telefono">
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold text-secondary small text-uppercase" for="modal-direccion">Dirección Domiciliaria</label>
              <input type="text" class="form-control rounded-3 py-2 bg-light border-0 focus-bg-white" id="modal-direccion">
            </div>

            <!-- Sección de Seguridad -->
            <div class="col-12 pt-2">
              <div class="border-top pt-3 pb-1">
                <h6 class="fw-bold text-dark mb-1 fs-6"><i class="fa-solid fa-lock text-primary me-2"></i>Seguridad de la Cuenta</h6>
                <p class="text-muted small mb-3">Deja estos campos en blanco si no deseas modificar tu contraseña actual.</p>
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold text-secondary small text-uppercase" for="modal-password">Nueva Contraseña</label>
              <input type="password" class="form-control rounded-3 py-2 bg-light border-0 focus-bg-white" id="modal-password" placeholder="••••••••">
              <div id="bien_confirm_password" class="alert alert-success d-flex align-items-center p-2 mt-2 rounded-3 small border-0 bg-success bg-opacity-10 text-success" style="display: none;">
                <i class="fa-solid fa-circle-check me-2"></i><span>Las contraseñas coinciden correctamente.</span>
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold text-secondary small text-uppercase" for="modal-confirm-password">Confirmar Contraseña</label>
              <input type="password" class="form-control rounded-3 py-2 bg-light border-0 focus-bg-white" id="modal-confirm-password" placeholder="••••••••">
              <div id="error_confirm_password" class="alert alert-danger d-flex align-items-center p-2 mt-2 rounded-3 small border-0 bg-danger bg-opacity-10 text-danger" style="display: none;">
                <i class="fa-solid fa-circle-xmark me-2"></i><span>Las contraseñas no coinciden.</span>
              </div>
            </div>

            <!-- Asignaciones de Sistema -->
            <div class="col-12 pt-2">
              <div class="border-top pt-3 pb-1">
                <h6 class="fw-bold text-dark mb-3 fs-6"><i class="fa-solid fa-shield-halved text-primary me-2"></i>Permisos y Ubicación</h6>
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold text-secondary small text-uppercase" for="modal-rol">Rol del Sistema</label>
              <select class="form-select rounded-3 py-2 bg-light border-0 focus-bg-white shadow-none" id="modal-rol">
                <option disabled selected>Seleccione rol de usuario</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold text-secondary small text-uppercase" for="modal-sucursal">Sucursal Asignada</label>
              <select class="form-select rounded-3 py-2 bg-light border-0 focus-bg-white shadow-none" id="modal-sucursal">
                <option disabled selected>Seleccione sucursal operativa</option>
              </select>
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold text-secondary small text-uppercase" for="modal-descripcion">Descripción del Perfil</label>
              <textarea class="form-control rounded-3 bg-light border-0 focus-bg-white" id="modal-descripcion" rows="3" placeholder="Escribe una breve reseña o descripción profesional..."></textarea>
            </div>
          </div>

          <!-- Loader interno estilizado -->
          <div id="loader-actualizando" class="text-center py-4" style="display:none;">
            <div class="spinner-border text-primary spinner-border-sm me-2" role="status"></div>
            <span class="text-muted fw-medium small">Actualizando tu información en el sistema...</span>
          </div>
        </div>

        <!-- Footer Limpio con Botones Senior -->
        <div class="modal-footer bg-light px-4 py-3 border-0">
          <button type="button" class="btn btn-outline-secondary px-4 rounded-pill fw-semibold border-0 bg-transparent text-secondary hover-bg-light" data-bs-dismiss="modal">
            Cancelar
          </button>
          <button type="button" class="btn btn-primary px-4 rounded-pill fw-semibold shadow-sm d-flex align-items-center gap-2" id="guardar-perfil">
            <i class="fa-solid fa-floppy-disk" id="guardarPerfilIcon"></i>
            <span id="guardarPerfilText">Guardar Cambios</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- ======================================================= -->
<!-- 2. MODAL PARA SUBIR FOTOS A GALERÍA DEL USUARIO (MODERNO)-->
<!-- ======================================================= -->
<div class="modal fade" id="modal-subir-foto-galeria" tabindex="-1" aria-labelledby="modal-subir-foto-label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
      
      <div class="modal-header bg-dark text-white px-4 py-3 border-0">
        <h5 class="modal-title fw-bold d-flex align-items-center fs-5" id="modal-subir-foto-label">
          <div class="bg-primary bg-opacity-25 p-2 rounded-3 me-3 text-primary d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
            <i class="fa-regular fa-images fs-6"></i>
          </div>
          Subir Fotografías a la Galería
        </h5>
        <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body p-4 bg-body">
        <form id="form-subir-foto-galeria" enctype="multipart/form-data" method="post">
          <input type="hidden" name="_token" id="_token_galery" value="{{ csrf_token() }}">
          
          <div class="mb-3">
            <label for="titulo" class="form-label fw-semibold text-secondary small text-uppercase">Título de la Imagen</label>
            <input type="text" class="form-control rounded-3 py-2 bg-light border-0 focus-bg-white" id="titulo" name="titulo" required placeholder="Ej. Evento corporativo anual...">
            <div class="invalid-feedback text-danger small mt-1" id="err-titulo-galeria" style="display:none;">El título de la imagen es obligatorio.</div>
          </div>

          <!-- Widget de Carga de Imagen Estilizado -->
          <div class="position-relative text-center p-4 overflow-hidden rounded-4 border border-2 border-dashed bg-light my-3">
            <img src="{{ asset('img/01.png') }}" alt="Fondo difuminado" id="previw-fondo-galeria"
                 class="position-absolute top-0 start-50 translate-middle-x w-100 h-100 object-fit-cover filter-blur-custom opacity-10 z-0">

            <div class="position-relative z-1 d-inline-block my-2">
              <img src="{{ asset('img/01.png') }}" alt="Preview galería" id="foto-preview-galeria"
                   class="rounded-circle shadow-sm border border-3 border-white object-fit-cover bg-white"
                   style="width: 110px; height: 110px;">

              <label for="input-foto-galeria" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 shadow-sm d-flex align-items-center justify-content-center transition-all" style="cursor: pointer; width: 34px; height: 34px;" title="Seleccionar archivo">
                <i class="fa-solid fa-camera fs-6"></i>
              </label>
              <input type="file" name="foto" id="input-foto-galeria" accept="image/*" class="d-none">
            </div>
            <p class="text-muted mt-2 mb-0 small position-relative z-1 fw-medium">Haz clic en el ícono para buscar la fotografía</p>
          </div>

          <!-- Opciones de Visualización en Switches Modernos -->
          <div class="row g-3 my-2 bg-light p-3 rounded-4 align-items-center">
            <div class="col-12">
              <label class="form-label fw-semibold text-dark mb-2 small text-uppercase"><i class="fa-solid fa-eye text-primary me-2"></i>¿Mostrar en la vista principal?</label>
              <div class="d-flex gap-4">
                <div class="form-check form-switch">
                  <input class="form-check-input cursor-pointer" type="checkbox" id="mostrar_siempre" name="mostrar_siempre">
                  <label class="form-check-label text-secondary small fw-medium cursor-pointer" for="mostrar_siempre">Mostrar siempre</label>
                </div>
                <div class="form-check form-switch">
                  <input class="form-check-input cursor-pointer" type="checkbox" id="no_mostrar" name="no_mostrar" checked>
                  <label class="form-check-label text-secondary small fw-medium cursor-pointer" for="no_mostrar">Mantener oculta</label>
                </div>
              </div>
            </div>
          </div>

          <div class="mb-3 mt-3">
            <label for="descripcion_foto" class="form-label fw-semibold text-secondary small text-uppercase">Descripción de la Foto</label>
            <textarea class="form-control rounded-3 bg-light border-0 focus-bg-white" id="descripcion_foto" name="descripcion_foto" rows="2" placeholder="Agrega contexto sobre esta imagen..."></textarea>
          </div>

          <div class="modal-footer px-0 pb-0 pt-3 border-0 bg-transparent">
            <button type="button" class="btn btn-outline-secondary px-4 rounded-pill fw-semibold border-0 bg-transparent text-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary px-4 rounded-pill fw-semibold shadow-sm d-flex align-items-center gap-2" id="agregar-foto-galeria">
              <i class="fa-solid fa-cloud-arrow-up" id="agregarFotoIcon"></i>
              <span id="agregarFotoText">Subir Imagen</span>
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>


<!-- =============================================== -->
<!-- 3. MODAL PARA CREAR / ASIGNAR TAREAS (OPTIMIZADO) -->
<!-- =============================================== -->
<div class="modal fade" id="modalCrearTarea" tabindex="-1" aria-labelledby="modalCrearTareaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
      
      <div class="modal-header bg-dark text-white px-4 py-3 border-0">
        <h5 class="modal-title fw-bold d-flex align-items-center fs-5" id="modalCrearTareaLabel" style="color: #fff;">
          <div class="bg-primary bg-opacity-25 p-2 rounded-3 me-3 text-primary d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: linear-gradient(135deg, rgba(0, 123, 255, 0.2), rgba(0, 123, 255, 0.4));">
            <i class="fa-solid fa-list-check fs-6"></i>
          </div>
          Crear Nueva Tarea
        </h5>
        <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body p-4 bg-body">
        <form id="formCrearTarea" enctype="multipart/form-data" class="needs-validation" novalidate>
          <input type="hidden" name="_token" id="_token_tarea" value="{{ csrf_token() }}">

          <div class="mb-3">
            <label for="nombre_tarea" class="form-label fw-semibold text-secondary small text-uppercase">Nombre de la Tarea</label>
            <input type="text" class="form-control rounded-3 py-2 bg-light border-0 focus-bg-white" id="nombre_tarea" name="nombre_tarea" placeholder="Ej. Revisión mensual de inventario" required>
          </div>

          <div class="mb-3">
            <label for="descripcion_tarea" class="form-label fw-semibold text-secondary small text-uppercase">Descripción Detallada</label>
            <textarea class="form-control rounded-3 bg-light border-0 focus-bg-white" id="descripcion_tarea" name="descripcion_tarea" placeholder="Especifica las pautas a seguir..." rows="3"></textarea>
          </div>

          <!-- Automatización / Repetición -->
          <div class="row g-3 bg-light p-3 rounded-4 mb-3">
            <div class="col-12">
              <label for="repeticion_tipo" class="form-label fw-semibold text-dark small text-uppercase"><i class="fa-solid fa-repeat text-primary me-2"></i>Frecuencia de Repetición</label>
              <select class="form-select rounded-3 bg-white border-0 shadow-none" id="repeticion_tipo" name="repeticion_tipo">
                <option value="">No repetir (Única vez)</option>
                <option value="diario">Diario</option>
                <option value="semanal">Semanal</option>
                <option value="mensual">Mensual</option>
              </select>
            </div>

            <div class="col-md-6 d-none" id="config_semanal">
              <label for="repeticion_dia" class="form-label small fw-semibold text-secondary">Día de la semana</label>
              <select class="form-select rounded-3 bg-white border-0 shadow-none" id="repeticion_dia" name="repeticion_dia">
                <option value="lunes">Lunes</option>
                <option value="martes">Martes</option>
                <option value="miercoles">Miércoles</option>
                <option value="jueves">Jueves</option>
                <option value="viernes">Viernes</option>
                <option value="sabado">Sábado</option>
                <option value="domingo">Domingo</option>
              </select>
            </div>

            <div class="col-md-6 d-none" id="config_mensual">
              <label for="repeticion_dia_mes" class="form-label small fw-semibold text-secondary">Día del mes (1 - 31)</label>
              <input type="number" class="form-control rounded-3 bg-white border-0" id="repeticion_dia_mes" name="repeticion_dia_mes" min="1" max="31">
            </div>

            <div class="col-md-6 d-none" id="config_anticipacion">
              <label for="repeticion_anticipacion" class="form-label small fw-semibold text-secondary">Días de anticipación para crear</label>
              <input type="number" class="form-control rounded-3 bg-white border-0" id="repeticion_anticipacion" name="repeticion_anticipacion" min="0" max="10" value="2">
            </div>

            <div class="col-md-6 d-none" id="config_hasta">
              <label for="repeticion_hasta" class="form-label small fw-semibold text-secondary">Fecha de expiración de repetición</label>
              <input type="date" class="form-control rounded-3 bg-white border-0" id="repeticion_hasta" name="repeticion_hasta">
            </div>
          </div>

          <div class="mb-3" id="grupo_fecha_vencimiento">
            <label for="fecha_vencimiento" class="form-label fw-semibold text-secondary small text-uppercase">Fecha y Hora Límite</label>
            <input type="datetime-local" class="form-control rounded-3 py-2 bg-light border-0 focus-bg-white" id="fecha_vencimiento" name="fecha_vencimiento" required>
          </div>

          <!-- Drag and Drop Archivos -->
          <div class="mb-3">
            <label class="form-label fw-semibold text-secondary small text-uppercase">Archivos Adjuntos</label>
            <div class="file-drop-area border border-2 border-dashed rounded-4 p-4 text-center bg-light transition-all hover-border-primary" id="cargar_archivos" style="cursor: pointer;">
              <i class="fa-solid fa-cloud-arrow-up fs-2 text-primary mb-2"></i>
              <p class="mb-1 fw-medium text-dark small">Haz clic o arrastra tus archivos aquí</p>
              <span class="text-muted extra-small d-block">Formatos admitidos: PDF, Imágenes, Office (Max. varios MB)</span>
              <input type="file" class="form-control d-none" id="input_archivos_tarea" name="archivos_tarea[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
            </div>
            <div class="preview-container mt-2 d-flex flex-wrap gap-2" id="preview-container"></div>
          </div>

          <div class="form-check form-switch mb-3 pt-2">
            <input class="form-check-input cursor-pointer" type="checkbox" id="es_activa" name="es_activa" checked>
            <label class="form-check-label fw-semibold text-secondary cursor-pointer" for="es_activa">Marcar tarea como activa inmediatamente</label>
          </div>

          <div class="modal-footer px-0 pb-0 pt-3 border-0 bg-transparent">
            <button type="button" class="btn btn-outline-secondary px-4 rounded-pill fw-semibold border-0 bg-transparent text-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" form="formCrearTarea" class="btn btn-primary px-4 rounded-pill fw-semibold shadow-sm d-flex align-items-center gap-2" id="btnGuardarTarea">
              <i class="fa-solid fa-check" id="btnGuardarTareaIcon"></i>
              <span id="btnGuardarTareaText">Guardar Tarea</span>
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>


<!-- ================================================= -->
<!-- 4. MODAL PARA EDITAR TAREAS (ESTÉTICA PROFESIONAL) -->
<!-- ================================================= -->
<div class="modal fade" id="modalEditarTarea" tabindex="-1" aria-labelledby="modalEditarTareaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

      <div class="modal-header bg-dark text-white px-4 py-3 border-0">
        <h5 class="modal-title fw-bold d-flex align-items-center fs-5" id="modalEditarTareaLabel">
          <div class="bg-primary bg-opacity-25 p-2 rounded-3 me-3 text-primary d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
            <i class="fa-solid fa-pen-to-square fs-6"></i>
          </div>
          Editar Tarea Asignada
        </h5>
        <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body p-4 bg-body">
        <form id="formEditarTarea" enctype="multipart/form-data">
          <input type="hidden" name="_token" id="_token_tarea" value="{{ csrf_token() }}">
          <input type="hidden" name="id" id="editar-tarea-id">

          <div class="mb-3">
            <label for="editar-tarea-nombre" class="form-label fw-semibold text-secondary small text-uppercase">Nombre de la Tarea</label>
            <input type="text" class="form-control rounded-3 py-2 bg-light border-0 focus-bg-white" id="editar-tarea-nombre" name="nombre_tarea" required>
          </div>

          <div class="mb-3">
            <label for="editar-tarea-descripcion" class="form-label fw-semibold text-secondary small text-uppercase">Descripción</label>
            <textarea class="form-control rounded-3 bg-light border-0 focus-bg-white" id="editar-tarea-descripcion" name="descripcion_tarea" rows="3"></textarea>
          </div>

          <div class="mb-3">
            <label for="editar-tarea-fecha" class="form-label fw-semibold text-secondary small text-uppercase">Fecha y Hora Límite</label>
            <input type="datetime-local" class="form-control rounded-3 py-2 bg-light border-0 focus-bg-white" id="editar-tarea-fecha" name="fecha_vencimiento" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold text-secondary small text-uppercase">Archivos Adjuntos</label>
            <div class="file-drop-area border border-2 border-dashed rounded-4 p-4 text-center bg-light transition-all" id="editar_cargar_archivos" style="cursor: pointer;">
              <i class="fa-solid fa-cloud-arrow-up fs-2 text-primary mb-2"></i>
              <p class="mb-1 fw-medium text-dark small">Haz clic o arrastra nuevos archivos aquí</p>
              <input type="file" class="form-control d-none" id="editar_input_archivos_tarea" name="archivos_tarea_edit[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
            </div>
            <div class="preview-container mt-2 d-flex flex-wrap gap-2" id="editar-preview-container"></div>
          </div>
        </form>
      </div>

      <div class="modal-footer px-4 py-3 border-0 bg-light">
        <button type="button" class="btn btn-outline-secondary px-4 rounded-pill fw-semibold border-0 bg-transparent text-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" form="formEditarTarea" id="btnActualizarTarea" class="btn btn-primary px-4 rounded-pill fw-semibold shadow-sm d-flex align-items-center gap-2">
          <i class="fa-solid fa-rotate" id="btnActualizarTareaIcon"></i>
          <span id="btnActualizarTareaText">Actualizar Cambios</span>
        </button>
      </div>

    </div>
  </div>
</div>


<!-- ======================================================== -->
<!-- 5. MODAL DE LA LISTA DE USUARIOS (ASIGNACIÓN EFICIENTE)  -->
<!-- ======================================================== -->
<div class="modal fade" id="modalAsignarUsuarios" tabindex="-1" aria-labelledby="modalAsignarUsuariosLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
      
      <div class="modal-header bg-dark text-white px-4 py-3 border-0">
        <h5 class="modal-title fw-bold d-flex align-items-center fs-5" id="modalAsignarUsuariosLabel">
          <div class="bg-primary bg-opacity-25 p-2 rounded-3 me-3 text-primary d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
            <i class="fa-solid fa-users fs-6"></i>
          </div>
          Asignar Usuarios a la Tarea
        </h5>
        <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body p-4 bg-body">
        <!-- Barra de Búsqueda Moderna -->
        <div class="input-group mb-4 shadow-sm rounded-3 overflow-hidden">
          <span class="input-group-text bg-light border-0 ps-3 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
          <input type="text" class="form-control bg-light border-0 py-2 focus-bg-white" id="buscarUsuario" placeholder="Buscar por nombre, rol o correo de usuario...">
        </div>
        <div id="usuariosSeleccionadosWrap" class="ledger-selected-wrap d-none">
         <div class="ledger-selected-label">Usuarios seleccionados</div>
         <div id="usuariosSeleccionadosChips" class="ledger-chips"></div>
         </div>


        <!-- Estados Vacíos y de Búsqueda -->
        <div id="mensajeBuscarUsuario" class="text-center text-muted py-5">
          <div class="bg-light rounded-circle d-inline-flex p-3 mb-2 text-primary">
            <i class="fa-solid fa-address-book fs-3"></i>
          </div>
          <p class="fw-medium mb-0">Escribe en el campo superior para filtrar y seleccionar miembros del equipo.</p>
        </div>

        <div id="mensajeSinResultados" class="text-center text-muted py-5" style="display: none;">
          <div class="bg-light rounded-circle d-inline-flex p-3 mb-2 text-danger">
            <i class="fa-solid fa-user-xmark fs-3"></i>
          </div>
          <p class="fw-medium mb-0">No se encontraron usuarios activos que coincidan con la búsqueda.</p>
        </div>

        <!-- Grid de Resultados de Usuarios -->
        <div class="row g-3" id="listaUsuarios_task"></div>
      </div>

      <div class="modal-footer px-4 py-3 border-0 bg-light">
        <button type="button" class="btn btn-outline-secondary px-4 rounded-pill fw-semibold border-0 bg-transparent text-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary px-4 rounded-pill fw-semibold shadow-sm d-flex align-items-center gap-2" id="btnConfirmarAsignacion">
          <i class="fa-solid fa-check" id="btnConfirmarAsignacionIcon"></i>
          <span id="btnConfirmarAsignacionText">Asignar Seleccionados</span>
        </button>
      </div>

    </div>
  </div>
</div>

 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <link rel="stylesheet" href="{{ asset('css/miperfil/perfil.css') }}"> 
<script>


    const mensajeElemento = document.getElementById('mensaje_buenos_dias_tardes');

    const ahora = new Date();
    const hora = ahora.getHours();

    let saludo = "";

    if (hora >= 6 && hora < 12) {
        saludo = "Hola, Buenos días!";
    } else if (hora >= 12 && hora < 19) {
        saludo = "Hola, Buenas tardes!";
    } else {
        saludo = "Hola, Buenas noches!";
    }

    mensajeElemento.textContent = saludo;
    let usuarios_sistemas;
    let tareas_por_usuarios;
    fetch('/recupera-usuario-logueado').then(response => response.json()).then(respuesta =>{
      recuperaUsuariologueado(respuesta);
      Miperfil(respuesta);
      recuperarDatosModal(respuesta);
      mostrarListaTareas(respuesta);
      usuarios_sistemas = respuesta.usuarios_sistema;
      tareas_por_usuarios = respuesta.tareas;



    }).catch(error => {
      console.error('Error al recuperar el usuario logueado:', error);
    });


    function recuperaUsuariologueado(respuesta){
        let respuestaUsuario = respuesta.usuario;
         let tareas_usuarios = respuesta.tareas;
        const nombre_usuario = document.getElementById('usuario_logueado');
        nombre_usuario.innerHTML = respuestaUsuario.nombre + " " + respuestaUsuario.apellido_p + " " + respuestaUsuario.apellido_m;
    }

    function Miperfil(respuesta){
      let usuario = respuesta.usuario;
      let mis_tareas = respuesta.mis_tareas;
      const userName = document.getElementById('user-name');
      const userRole = document.getElementById('user-role');
      const userDescription = document.getElementById('user-description');

      userName.innerHTML = usuario.nombre + " " + usuario.apellido_p + " " + usuario.apellido_m;
      userRole.innerHTML = " - " + usuario.nombre_rol;
      userDescription.innerHTML = usuario.descripcion_usuario;

      const userCreated = document.getElementById('user-created');
      const fechaCreacion = new Date(usuario.created_at);

      const opciones = { year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true};
      const fecha_formateada = fechaCreacion.toLocaleDateString('en-US', opciones);

      userCreated.innerHTML = `
         <h6 class="mb-1">Creado:</h6>
         <p>${fecha_formateada}</p>
      `;

      const userDirection = document.getElementById('user-direction');
      userDirection.innerHTML = `
         <h6 class="mb-1">Domicilio:</h6>
         <p>${usuario.domicilio}</p>`;
      const userEmail = document.getElementById('user-email');
      userEmail.innerHTML = `
         <h6 class="mb-1">Correo:</h6>
         <p>${usuario.correo}</p>`;
      const userCurp = document.getElementById('user-curp');
      userCurp.innerHTML = `
         <h6 class="mb-1">CURP:</h6>
         <p>${usuario.curp}</p>`;
      const userTelefono = document.getElementById('user-telefono');
      userTelefono.innerHTML = `
         <h6 class="mb-1">Teléfono:</h6>
         <p>${usuario.telefono}</p>`;
      const datosTienda = document.getElementById('datos-tienda');
      const sucursalTitle = document.getElementById('sucursal-title');
      sucursalTitle.innerHTML = `<h4 class="card-title mb-0">${usuario.nombre_real}</h4>`;

      datosTienda.innerHTML = `
      <p style="font-size: 14px;" class="ledger-profile-desc">Al estar ligado a una sucursal, solo podra subir datos de esta misma.</p>
                  <div class="mb-1">Telefono: <a href="#" class="ms-3">${usuario.telefono_tienda}</a></div>
                  <div>Ubicacion: <span class="ms-3">${usuario.ubicacion}</span></div>`;
    }

    /* evento click boton edit perfil */

    const editPerfilBtn = document.getElementById('edit-perfil');
    editPerfilBtn.addEventListener('click', () => {
        // Antes esto podía tronar con "Cannot read properties of undefined" si se
        // hacía clic aquí antes de que /recupera-usuario-logueado terminara de responder.
        if (typeof user === 'undefined' || !user) {
            Swal.fire({
                title: 'Espera un momento',
                text: 'Tus datos todavía se están cargando, intenta de nuevo en un segundo.',
                icon: 'info'
            });
            return;
        }

        $('#modal-perfil').modal('show');

        document.getElementById('modal-password').value = '';
        document.getElementById('modal-confirm-password').value = '';
        document.getElementById('error_confirm_password').style.display = 'none';
        document.getElementById('bien_confirm_password').style.display = 'none';
        password = '';

        const usuarioFoto = user.foto_perfil ?? '{{ asset("img/default.png") }}';
        // Antes: document.getElementById('input-foto').src = '' — un <input type="file">
        // no tiene .src, así que esto nunca limpiaba el archivo seleccionado previamente.
        document.getElementById('input-foto').value = '';
        document.getElementById('foto-preview').src = usuarioFoto;
        document.getElementById('previw-fondo').src = usuarioFoto;
        document.getElementById('img_fondo_perfil').src = usuarioFoto;
        // Antes: document.getElementById('loader-actualizando').value = '' — es un <div>,
        // no tiene .value; si un guardado anterior había fallado, el loader podía
        // quedarse visible la próxima vez que se abría el modal.
        document.getElementById('loader-actualizando').style.display = 'none';
    });

   document.getElementById('input-foto').addEventListener('change', function (event) {
    const file = event.target.files[0];
    const preview = document.getElementById('foto-preview');
    const previewFondo = document.getElementById('previw-fondo');
    const fondo_wie = document.getElementById('img_fondo_perfil');

    if (file && file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = function (e) {
        preview.src = e.target.result;
        previewFondo.src = e.target.result;

      };
      reader.readAsDataURL(file);
    } else {
      Swal.fire({ title: 'Archivo no válido', text: 'Por favor selecciona un archivo de imagen.', icon: 'warning' });
    }
  });
   var user;
   function recuperarDatosModal(respuesta) {

      const usuario = respuesta.usuario;
      user = respuesta.usuario;
      document.getElementById('modal-nombre').value = usuario.nombre;
      document.getElementById('modal-apellido-paterno').value = usuario.apellido_p;
      document.getElementById('modal-apellido-materno').value = usuario.apellido_m;
      document.getElementById('modal-correo').value = usuario.correo;
      document.getElementById('modal-curp').value = usuario.curp;
      document.getElementById('modal-telefono').value = usuario.telefono;
      document.getElementById('modal-direccion').value = usuario.domicilio;

      document.getElementById('modal-descripcion').value = usuario.descripcion_usuario;

      $('#modal-rol').empty();

         $.each(respuesta.roles, function(index, rol){
            var selected = (rol.id == respuesta.tipo_rol) ? 'selected' : '';
            $('#modal-rol').append('<option value="' + rol.id + '" ' +
               selected + '>' + rol.nombre_rol + '</option>');
         });

         $('#modal-sucursal').empty();
         $.each(respuesta.sucursales, function(index, sucursal){
            var selected = (sucursal.id == respuesta.tipo_sucursal) ? 'selected' : '';
         $('#modal-sucursal').append('<option value="' + sucursal.id + '" ' +
               selected + '>' + sucursal.nombre_real + '</option>');
         });
         $('#modal-rol').val(usuario.tipo_rol);

         $('#modal-sucursal').val(usuario.tipo_sucursal);

         document.getElementById('foto-preview').src = usuario.foto_perfil ? usuario.foto_perfil : '{{ asset('img/01.png') }}';
         document.getElementById('previw-fondo').src = usuario.foto_perfil ? usuario.foto_perfil : '{{ asset('img/01.png') }}';
         document.getElementById('img_fondo_perfil').src = usuario.foto_perfil ? usuario.foto_perfil : '{{ asset('img/01.png') }}';
         document.getElementById('foto_perfil_actualizada').src = usuario.foto_perfil ? usuario.foto_perfil : '{{ asset('img/01.png') }}';

         document.querySelector('.theme-color-default-img').src = usuario.foto_perfil;
         document.querySelector('.user-profile img').src = usuario.foto_perfil;


   }

   var password = '';

   function verificarPassword() {
      password = document.getElementById('modal-password').value;
      var confirmPassword = document.getElementById('modal-confirm-password').value;

      if (password === confirmPassword && password.length > 0) {
               $('#bien_confirm_password').show();
               $('#error_confirm_password').hide();
            } else {
               $('#bien_confirm_password').hide();
            if (confirmPassword.length > 0) {
               $('#error_confirm_password').show();
            } else {
               $('#error_confirm_password').hide();
               }
         }
   }

         $('#modal-password').on('input', function() {
                  verificarPassword();
         });

         $('#modal-confirm-password').on('input', function() {
                  verificarPassword();
         });

         /* Bandera de estado: evita doble envío del formulario de perfil */
         let guardandoPerfil = false;

      $('#guardar-perfil').on('click', function () {
      if (guardandoPerfil) return;

      const inputFoto = document.getElementById('input-foto');
      const archivoSeleccionado = inputFoto.files[0];

      // Antes se usaba la variable global "password" tal cual quedó del último evento
      // "input" disparado. Es más confiable leer el valor directo del campo al momento
      // de guardar, por si algún gestor de contraseñas lo llenó sin disparar el evento.
      const passwordActual = document.getElementById('modal-password').value;
      const confirmPasswordActual = document.getElementById('modal-confirm-password').value;

      if (passwordActual.length > 0 && passwordActual !== confirmPasswordActual) {
         Swal.fire({ title: 'Las contraseñas no coinciden', text: 'Revisa el campo de confirmación.', icon: 'warning' });
         return;
      }

      const formData = new FormData();
      formData.append('_token', $('#_token').val());
      formData.append('nombre', $('#modal-nombre').val());
      formData.append('apellido_p', $('#modal-apellido-paterno').val());
      formData.append('apellido_m', $('#modal-apellido-materno').val());
      formData.append('correo', $('#modal-correo').val());
      formData.append('curp', $('#modal-curp').val());
      formData.append('domicilio', $('#modal-direccion').val());
      formData.append('telefono', $('#modal-telefono').val());
      formData.append('tipo_rol', $('#modal-rol').val());
      formData.append('tipo_sucursal', $('#modal-sucursal').val());
      formData.append('password', passwordActual || '');
      formData.append('descripcion_usuario', $('#modal-descripcion').val());

      if (archivoSeleccionado) {
         formData.append('foto', archivoSeleccionado);
      }

      guardandoPerfil = true;
      $('#guardar-perfil').prop('disabled', true);
      $('#guardarPerfilIcon').removeClass('fa-save').addClass('fa-spinner fa-spin');
      $('#guardarPerfilText').text('Guardando...');
      $('#loader-actualizando').fadeIn();


      $.ajax({
         url: '/actualizar-mis-datos-perfil',
         type: 'post',
         data: formData,
         contentType: false,
         processData: false,
         success: function (response) {
             $('#loader-actualizando').fadeOut();
             guardandoPerfil = false;
             $('#guardar-perfil').prop('disabled', false);
             $('#guardarPerfilIcon').removeClass('fa-spinner fa-spin').addClass('fa-save');
             $('#guardarPerfilText').text('Guardar Cambios');

               Swal.fire({
                  icon: 'success',
                  title: 'Exito',
                  text: response.message,
                  showConfirmButton: false,
                  timer: 1500
               }).then(() => {
                  fetch('/recupera-usuario-logueado')
                     .then(response => response.json())
                     .then(respuesta => {
                           recuperaUsuariologueado(respuesta);
                           Miperfil(respuesta);
                           recuperarDatosModal(respuesta);
                           $('#modal-perfil').modal('hide');
                     });
               });
         },
         error: function (xhr) {
               // Antes: el loader se quedaba visible para siempre si esto fallaba,
               // y no había ningún aviso de error para el usuario.
               console.log(xhr.responseText);
               $('#loader-actualizando').fadeOut();
               guardandoPerfil = false;
               $('#guardar-perfil').prop('disabled', false);
               $('#guardarPerfilIcon').removeClass('fa-spinner fa-spin').addClass('fa-save');
               $('#guardarPerfilText').text('Guardar Cambios');
               Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: 'No se pudo actualizar tu perfil. Inténtalo de nuevo.',
                  showConfirmButton: true
               });
         }
      });
   });
   document.getElementById('input-foto-galeria').addEventListener('change', function (event) {
    const file = event.target.files[0];
    const preview_galery = document.getElementById('foto-preview-galeria');
    const previewFondo_galery = document.getElementById('previw-fondo-galeria');


    if (file && file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = function (e) {
        preview_galery.src = e.target.result;
        previewFondo_galery.src = e.target.result;

      };
      reader.readAsDataURL(file);
    } else {
      Swal.fire({ title: 'Archivo no válido', text: 'Por favor selecciona un archivo de imagen.', icon: 'warning' });
    }
  });

   var id_user;
   fetch('/recupera-fotos-galeria-usuario').then(response => response.json()).then(respuesta =>{
      mostrarFotoGaleria(respuesta);
   });

   function mostrarFotoGaleria(respuesta){
      const galeriaContainer = document.getElementById('mostrar_fotos_galeria');
      galeriaContainer.innerHTML = '';
      const totalFotosGaleria = document.getElementById('total_fotos_galeria');
      totalFotosGaleria.innerHTML = respuesta.fotos.length;
      for (var i = 0; i < respuesta.fotos.length; i++) {
         galeriaContainer.innerHTML += `
            <div class="foto-wrapper">
               <a data-fslightbox="gallery" href="${respuesta.fotos[i].ruta_imagen}">
               <img src="${respuesta.fotos[i].ruta_imagen}" class="gallery-img" alt="profile-image">
               </a>
            </div>`;
         }
   }
   const inputSubirGaleria = document.getElementById('subir_galeria');
   inputSubirGaleria.addEventListener('click', ()=>{
      $('#modal-subir-foto-galeria').modal('show');
      id_user = user.id;
   });

   const inpuModalTareas = document.getElementById('modal-asignar-tareas');
   inpuModalTareas.addEventListener('click', ()=>{
      $('#modalCrearTarea').modal('show');
      document.getElementById('nombre_tarea').value = '';
      document.getElementById('descripcion_tarea').value = '';
      document.getElementById('fecha_vencimiento').value = '';
      document.getElementById('input_archivos_tarea').value = '';
      document.getElementById('repeticion_tipo').value = '';
      document.querySelector('[name="repeticion_dia"]').value = '';
      document.querySelector('[name="repeticion_dia_mes"]').value = '';
      document.querySelector('[name="repeticion_anticipacion"]').value = '2';

      document.getElementById('grupo_fecha_vencimiento').classList.remove('d-none');
      document.getElementById('config_semanal').classList.add('d-none');
      document.getElementById('config_mensual').classList.add('d-none');
      document.getElementById('config_anticipacion').classList.add('d-none');

   });

   /* Bandera de estado: evita doble envío al subir foto de galería */
   let subiendoFotoGaleria = false;

   $('#agregar-foto-galeria').on('click', function(event) {
      event.preventDefault();
      if (subiendoFotoGaleria) return;

      // Antes, al interceptar el "click" del botón (en vez del "submit" del formulario),
      // la validación nativa del atributo required en #titulo nunca llegaba a correr:
      // se podía subir una foto sin título.
      const tituloGaleria = $('#titulo').val().trim();
      if (!tituloGaleria) {
         $('#err-titulo-galeria').show();
         $('#titulo').addClass('is-invalid').focus();
         return;
      }
      $('#err-titulo-galeria').hide();
      $('#titulo').removeClass('is-invalid');

      const inputFotoGaleria = document.getElementById('input-foto-galeria');
      const archivoSeleccionadoGaleria = inputFotoGaleria.files[0];

         const formDataGaleria = new FormData();
         formDataGaleria.append('_token', $('#_token_galery').val());
         formDataGaleria.append('titulo_imagen', tituloGaleria);
         formDataGaleria.append('descripcion', $('#descripcion_foto').val());

         if(archivoSeleccionadoGaleria){
            formDataGaleria.append('ruta_imagen', archivoSeleccionadoGaleria);
         }

         let activo = 0;

         if ($('#mostrar_siempre').is(':checked')) {
            activo = 1;
         }
        formDataGaleria.append('img_activo', activo);
        formDataGaleria.append('usuario_id', id_user);

        subiendoFotoGaleria = true;
        $('#agregar-foto-galeria').prop('disabled', true);
        $('#agregarFotoIcon').removeClass('fa-cloud-arrow-up').addClass('fa-spinner fa-spin');
        $('#agregarFotoText').text('Subiendo...');

         $.ajax({
            url:'/guardar-foto-galeria',
            type: 'post',
            data: formDataGaleria,
            contentType: false,
            processData: false,
            success: function(response) {
               subiendoFotoGaleria = false;
               $('#agregar-foto-galeria').prop('disabled', false);
               $('#agregarFotoIcon').removeClass('fa-spinner fa-spin').addClass('fa-cloud-arrow-up');
               $('#agregarFotoText').text('Subir Foto');

               Swal.fire({
                  icon: 'success',
                  title: 'Exito',
                  text: response.message,
                  showConfirmButton: false,
                  timer: 1500
               }).then(() => {
                  fetch('/recupera-fotos-galeria-usuario')
                     .then(response => response.json())
                     .then(respuesta => {
                        mostrarFotoGaleria(respuesta);
                     });
               });
               $('#modal-subir-foto-galeria').modal('hide');
               document.getElementById('form-subir-foto-galeria').reset();
               $('#foto-preview-galeria').attr('src', '/img/01.png');

               $('#mostrar_siempre').prop('checked', false);
               $('#no_mostrar').prop('checked', true);
            },error: function(xhr) {
               console.log(xhr.responseText);
               subiendoFotoGaleria = false;
               $('#agregar-foto-galeria').prop('disabled', false);
               $('#agregarFotoIcon').removeClass('fa-spinner fa-spin').addClass('fa-cloud-arrow-up');
               $('#agregarFotoText').text('Subir Foto');
               Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: 'No se pudo subir la foto a la galería. Inténtalo de nuevo.',
                  showConfirmButton: true
               });
            }
         });
   });

   const carga_archivo_task = document.querySelector('.file-drop-area');
   const input_carga_task = document.getElementById('input_archivos_tarea');

   carga_archivo_task.addEventListener('click', () => {
      input_carga_task.click();
   });

   const previewContainer = document.getElementById('preview-container');
   let archivosSeleccionados = [];
   const iconosPorTipo = {
      'pdf': '📄',
      'doc': '📝',
      'docx': '📝',
      'xls': '📊 ',
      'xlsx': '📊',
      'ppt': '📽️',
      'pptx': '📽️',
      'jpg': '🖼️',
      'jpeg': '🖼️',
      'png': '🖼️'
   };

   input_carga_task.addEventListener('change', function () {
      const nuevosArchivos = Array.from(this.files);

      nuevosArchivos.forEach(nuevo => {
         if (!archivosSeleccionados.some(a => a.name === nuevo.name)) {
            archivosSeleccionados.push(nuevo);
         }
      });

      actualizarVistaPrevia();
   });

   function actualizarVistaPrevia() {
      previewContainer.innerHTML = '';

      archivosSeleccionados.forEach((archivo, index) => {
         const extension = archivo.name.split('.').pop().toLowerCase();
         const icono = iconosPorTipo[extension] || '📁';

         const item = document.createElement('div');
         item.classList.add('d-flex', 'align-items-center', 'mb-1', 'justify-content-between');

         const contenido = document.createElement('div');
         contenido.classList.add('d-flex', 'align-items-center');

         if (['jpg', 'jpeg', 'png'].includes(extension)) {
            const reader = new FileReader();
            reader.onload = function (e) {
               contenido.innerHTML = `
                  <img src="${e.target.result}" class="me-2" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                  <span>${archivo.name}</span>
               `;
            };
            reader.readAsDataURL(archivo);
         } else {
            contenido.innerHTML = `
               <span class="me-2 fs-5">${icono}</span>
               <span>${archivo.name}</span>
            `;
         }

         const btnEliminar = document.createElement('button');
         btnEliminar.classList.add('btn', 'btn-close');
         btnEliminar.style.fontSize = '11px';
         btnEliminar.style.padding = '9px';
         btnEliminar.title = 'Eliminar archivo';

         btnEliminar.addEventListener('click', () => {
            archivosSeleccionados.splice(index, 1);
            actualizarVistaPrevia();
         });

         item.appendChild(contenido);
         item.appendChild(btnEliminar);
         previewContainer.appendChild(item);
      });
   }

   $(function () {
      const $modal = $('#modalCrearTarea');

      function actualizarCamposRepeticion() {
         const tipo = ($modal.find('#repeticion_tipo').val() || '').toString().trim();
         $modal.find('#config_semanal, #config_mensual, #config_anticipacion, #config_hasta, #grupo_fecha_vencimiento')
            .addClass('d-none');
         $modal.find('#fecha_vencimiento').prop('required', false);

         switch (tipo) {
            case '':
            $modal.find('#grupo_fecha_vencimiento').removeClass('d-none');
            $modal.find('#fecha_vencimiento').prop('required', true);
            break;
            case 'diario':
            $modal.find('#config_hasta').removeClass('d-none');
            break;
            case 'semanal':
            $modal.find('#config_semanal').removeClass('d-none');
            $modal.find('#config_hasta').removeClass('d-none');
            break;
            case 'mensual':
            $modal.find('#config_mensual').removeClass('d-none');
            $modal.find('#config_hasta').removeClass('d-none');
            break;
         }
   }

      $modal.on('shown.bs.modal', actualizarCamposRepeticion);
      $modal.on('change', '#repeticion_tipo', actualizarCamposRepeticion);
      if ($modal.is(':visible')) actualizarCamposRepeticion();
   });


   /* Bandera de estado: evita doble envío al crear tarea */
   let creandoTarea = false;

   $('#formCrearTarea').off('submit').on('submit', function(event) {
      event.preventDefault();
      if (creandoTarea) return;

         const form = document.getElementById('formCrearTarea');
         const formData = new FormData(form);

         let tipo = $("#repeticion_tipo").val();

         if (tipo === "") {
            formData.delete("repeticion_dia");
            formData.delete("repeticion_dia_mes");
            formData.delete("repeticion_hasta");
            formData.delete("repeticion_anticipacion");
         } else if (tipo === "diario") {
            formData.delete("repeticion_dia");
            formData.delete("repeticion_dia_mes");
         } else if (tipo === "semanal") {
            formData.delete("repeticion_dia_mes");
         } else if (tipo === "mensual") {
            formData.delete("repeticion_dia");
         }

         const activa = $('#es_activa').is(':checked') ? 1 : 0;
         formData.set('es_activa', activa);

      creandoTarea = true;
      $('#btnGuardarTarea').prop('disabled', true);
      $('#btnGuardarTareaIcon').removeClass('fa-check').addClass('fa-spinner fa-spin');
      $('#btnGuardarTareaText').text('Guardando...');

      $.ajax({
         url: '/crear-tarea',
         type: 'POST',
         data: formData,
         processData: false,
         contentType: false,
         success: function(response) {
               creandoTarea = false;
               $('#btnGuardarTarea').prop('disabled', false);
               $('#btnGuardarTareaIcon').removeClass('fa-spinner fa-spin').addClass('fa-check');
               $('#btnGuardarTareaText').text('Guardar');

               Swal.fire({
                  icon: 'success',
                  title: 'Éxito',
                  text: response.message,
                  showConfirmButton: false,
                  timer: 1500
               }).then(() => {
               fetch('/recupera-usuario-logueado')
                  .then(response => response.json())
                  .then(respuesta => {
                     recuperaUsuariologueado(respuesta);
                     Miperfil(respuesta);
                     recuperarDatosModal(respuesta);
                     mostrarListaTareas(respuesta);
                  });
               $('#modalCrearTarea').modal('hide');
               document.getElementById('formCrearTarea').reset();
               archivosSeleccionados = [];
               actualizarVistaPrevia();
            });
         },
         error: function(xhr) {
               creandoTarea = false;
               $('#btnGuardarTarea').prop('disabled', false);
               $('#btnGuardarTareaIcon').removeClass('fa-spinner fa-spin').addClass('fa-check');
               $('#btnGuardarTareaText').text('Guardar');
               console.error("Status:", xhr.status);
               console.error("Response:", xhr.responseText);
               Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: 'No se pudo crear la tarea. Inténtalo de nuevo.',
                  showConfirmButton: true
               });
         }
      });
   });

   setInterval(() => {
      fetch('/recupera-usuario-logueado')
         .then(response => response.json())
         .then(respuesta => {
            misTareasMostrar(respuesta);
         });
   }, 3000);

   function misTareasMostrar(respuesta){
      let mis_tareas = respuesta.mis_tareas;
      const archivosPorTarea = respuesta.archivos_tareas || {};
      const card_mis_tareas = document.getElementById('card_mis_task');
      card_mis_tareas.innerHTML = '';
      let dias_horas_res_task;

      if(mis_tareas.length == 0){
         card_mis_tareas.innerHTML += `
            <div class="ledger-card mb-3">
               <div class="ledger-card-body">
                  <span class="ledger-profile-desc">SIN TAREAS ASIGNADAS</span>
               </div>
            </div>`;
      }
      function calcularTiempoRestante(tarea) {
            const ahora = new Date();
            let fechaVencimiento;

            if (tarea.repeticion_tipo) {
               const fechaHabilitacion = new Date(tarea.fecha_habilitacion);
               const diasEntrega = parseInt(tarea.repeticion_anticipacion ?? 0);
               fechaVencimiento = new Date(fechaHabilitacion);
               fechaVencimiento.setDate(fechaVencimiento.getDate() + diasEntrega);
            } else {
               fechaVencimiento = new Date(tarea.fecha_vencimiento);
            }

            const diferenciaMs = fechaVencimiento - ahora;
            const totalHoras = Math.floor(diferenciaMs / (1000 * 60 * 60));
            const dias = Math.floor(totalHoras / 24);
            const horas = totalHoras % 24;

            if (fechaVencimiento <= ahora) {
               return `<span class="badge text-bg-danger">Vencida hace ${Math.abs(dias)} días y ${Math.abs(horas)} horas</span>`;
            } else {
               return `<span class="badge text-bg-success">${dias} días y ${horas} horas restantes</span>`;
            }
      }

      for(var i = 0; i<mis_tareas.length; i++){
         const archivos = archivosPorTarea[mis_tareas[i].tarea_id] || [];
         let archivosHTML = '';

         archivos.forEach(archivo => {
            const extension = archivo.nombre_original.split('.').pop().toLowerCase();
            const icono = {
               'pdf': '📄', 'doc': '📝', 'docx': '📝', 'xls': '📊', 'xlsx': '📊',
               'ppt': '📽️', 'pptx': '📽️', 'jpg': '🖼️', 'jpeg': '🖼️', 'png': '🖼️'
            }[extension] || '📁';

            archivosHTML += `
               <li class="mb-1">
                  <span class="me-2">${icono}</span>
                  <a href="/storage/${archivo.ruta_archivo}" target="_blank">${archivo.nombre_original}</a>
               </li>
            `;
         });

         const dias_horas_res_task = calcularTiempoRestante(mis_tareas[i]);

         const tarea = mis_tareas[i];
         const esVisible = tarea.visible;
         const estiloDifuminado = esVisible ? '' : 'opacity-50 pointer-events-none';
         const mensajeBloqueo = esVisible ? '' : `
            <div class="text-center text-muted mt-2">
               <i class="fa-regular fa-clock me-1"></i>
               Esta tarea estará disponible el <strong>${tarea.fecha_habilitacion}</strong>
            </div>`;

         card_mis_tareas.innerHTML += `
            <div class="ledger-card mb-3 ${estiloDifuminado}" id="tarea-${tarea.tarea_id}">
               <div class="ledger-card-body">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                     <div>
                        <h5 class="mb-1 fw-bold" style="color: var(--primary); font-family:'Space Grotesk',sans-serif;">${tarea.nombre_tarea}</h5>
                        ${dias_horas_res_task}
                     </div>
                     <div class="dropdown">
                        <span class="h5 ${!esVisible ? 'disabled' : ''}" role="button" data-bs-toggle="dropdown">
                           <svg class="icon-24" xmlns="http://www.w3.org/2000/svg" width="24">
                              <circle cx="7" cy="12" r="1"/>
                              <circle cx="12" cy="12" r="1"/>
                              <circle cx="17" cy="12" r="1"/>
                           </svg>
                        </span>
                        <div class="dropdown-menu dropdown-menu-end">
                           <a class="task_realizada dropdown-item ${!esVisible ? 'disabled text-muted' : ''}" href="#">
                              <i class="fa-solid fa-check"></i> Marcar como hecha
                           </a>
                        </div>
                     </div>
                  </div>

                  <p class="mb-2 ledger-profile-desc">${tarea.descripcion_tarea}</p>
                  <span class="badge text-bg-info">Asignado (a) por ${tarea.asignador_nombre} ${tarea.asignador_apellido_p}</span>

                  ${archivos.length > 0 ? `
                  <div class="mt-3">
                     <h6 class="fw-bold mb-2">📎 Archivos adjuntos:</h6>
                     <ul class="list-unstyled ps-3">
                        ${archivosHTML}
                     </ul>
                  </div>` : ''}

                  ${mensajeBloqueo}

                  <div class="d-flex justify-content-end mt-3">
                     <button class="btn-ledger-secondary ${!esVisible ? 'disabled' : ''}" title="Agregar a la agenda">
                        <i class="fa-regular fa-calendar-days me-1"></i> Agendar
                     </button>
                  </div>
               </div>
            </div>
         `;
      }
   }

   function mostrarListaTareas(respuesta){
      const tareas_lisk = respuesta.listado_tareas;
      const tareas_archivos = respuesta.tareas_archivos;

      const card_task = document.getElementById('card_task');

      card_task.innerHTML = '';
      let estatus_task = '';
      let mensaje_status_task = '';
      let on_off_task = '';
      let nuevo_estado = '';

     const tareas_base = respuesta.listado_tareas;
     const tareas_con_usuarios = respuesta.tareas;

     if(tareas_lisk.length == 0){
          card_task.innerHTML += `
         <div class="ledger-card mb-3">
            <div class="ledger-card-body">
               <span class="ledger-profile-desc">NO CUENTAS CON TAREAS CREADAS, PARA CREAR UNA TAREA HAZ CLICK EN EL BOTON (CREAR NUEVA TAREA)</span>
            </div>
         </div>
      `;
      }

      tareas_base.forEach(tarea => {
      const tareaConUsuarios = tareas_con_usuarios.find(t => t.id === tarea.id);
      const usuariosAsignados = tareaConUsuarios?.usuarios_asignados || [];

      const estatus_task = tarea.es_activa == 1 ? 'Tarea activa' : 'Tarea inactiva';
      const mensaje_status_task = tarea.es_activa == 1
         ? `<span class="badge text-bg-success">${estatus_task}</span>`
         : `<span class="badge text-bg-warning">${estatus_task}</span>`;
      const nuevo_estado = tarea.es_activa == 1 ? 0 : 1;
      const on_off_task = tarea.es_activa == 1
         ? `<a class="on_of_activa_task dropdown-item" href="#" data-id="${tarea.id}" data-accion="${nuevo_estado}">
               <i class="fa-solid fa-x"></i> Deshabilitar
            </a>`
         : `<a class="on_of_activa_task dropdown-item" href="#" data-id="${tarea.id}" data-accion="${nuevo_estado}">
               <i class="fa-solid fa-check"></i> Habilitar
            </a>`;

      let usuariosHTML = '';
      usuariosAsignados.forEach(usuario => {
         usuariosHTML += `
            <a href="#" class="delete-task-user iq-media-1" title="${usuario.nombre} ${usuario.apellido_p}" data-id="${usuario.id}" data-nombre="${tarea.nombre_tarea}" data-task="${tarea.id}" data-user="${usuario.nombre}">
            <img src="${usuario.foto_perfil ? usuario.foto_perfil : 'img/01.png'}" class="img-fluid rounded-circle avatar-30" alt="${usuario.nombre}">
            </a>
         `;
      });

      let archivosHTML = '';
      const archivos = tareas_archivos[tarea.id] || [];

      archivos.forEach(archivo => {
         const extension = archivo.nombre_original.split('.').pop().toLowerCase();
         const icono = {
            'pdf': '📄', 'doc': '📝', 'docx': '📝', 'xls': '📊', 'xlsx': '📊',
            'ppt': '📽️', 'pptx': '📽️', 'jpg': '🖼️', 'jpeg': '🖼️', 'png': '🖼️'
         }[extension] || '📁';

         archivosHTML += `
            <div class="d-flex align-items-center mb-1">
               <span class="me-2">${icono}</span>
               <a href="/storage/${archivo.ruta_archivo}" target="_blank">${archivo.nombre_original}</a>
            </div>
         `;
      });
      card_task.innerHTML += `
         <div class="ledger-card mb-3">
            <div class="ledger-card-body">
            <div class="d-grid grid-flow-col align-items-center justify-content-between mb-2">
               <div class="d-flex align-items-center">
                  <p class="mb-0" style="font-family:'Space Grotesk',sans-serif; font-weight:600;">${tarea.nombre_tarea}</p>
                  <svg class="icon-20" width="20" viewBox="0 0 24 24"><path d="M8.5 5L15.5 12L8.5 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                  <p class="mb-0">${mensaje_status_task}</p>
               </div>
               <div class="dropdown">
                  <span class="h5" role="button" data-bs-toggle="dropdown">
                  <svg class="icon-24" xmlns="http://www.w3.org/2000/svg" width="24"><circle cx="7" cy="12" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="17" cy="12" r="1"/></svg>
                  </span>
                  <div class="dropdown-menu dropdown-menu-end">
                  <a class="edit_task dropdown-item" href="#" data-id="${tarea.id}" data-nombre="${tarea.nombre_tarea}" data-descripcion="${tarea.descripcion_tarea}" data-fecha="${tarea.fecha_vencimiento}">
                     <i class="fa-solid fa-pen-to-square"></i> Editar
                  </a>
                  <a class="eliminar_task dropdown-item" href="#" data-id="${tarea.id}" data-nombre="${tarea.nombre_tarea}">
                     <i class="fa-solid fa-trash"></i> Eliminar
                  </a>
                  ${on_off_task}
                  </div>
               </div>
            </div>


            <h6 class="mb-3 ledger-profile-desc">${tarea.descripcion_tarea}</h6>
            <div class="d-flex align-items-center mb-3">
               <div class="btn btn-icon btn-soft-light me-2"><div class="btn-inner"><i class="fa-regular fa-calendar-days"></i></div></div>
               <div class="dropdown">
                  <button class="btn btn-icon btn-soft-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">

                  </button>
                  <ul class="dropdown-menu archivos-dropdown" id="archivos-${tarea.id}">
                     ${archivosHTML
                        ? archivosHTML
                        : `<li class="dropdown-item text-muted">No hay archivos adjuntos</li>`}
                  </ul>
               </div>
            </div>
            ${tarea.repeticion_tipo
               ? `<div class="mb-3" style="font-size:15px;">
                     <h6 class="mb-1" style="font-size:15px; color: var(--primary);">🔁 Repetición: <strong>${tarea.repeticion_tipo}</strong></h6>
                     ${tarea.repeticion_dia ? `<h6 class="mb-1" style="font-size:15px;">📅 Día: <strong>${tarea.repeticion_dia}</strong></h6>` : ''}
                     ${tarea.repeticion_dia_mes ? `<h6 class="mb-1" style="font-size:15px;">📆 Día del mes: <strong>${tarea.repeticion_dia_mes}</strong></h6>` : ''}
                     ${tarea.repeticion_anticipacion ? `<h6 class="mb-1" style="font-size:15px;">⏳ Se crea <strong>${tarea.repeticion_anticipacion} días antes</strong></h6>` : ''}
                     ${tarea.repeticion_hasta ? `<h6 class="mb-1" style="font-size:15px;">🛑 Repite hasta: <strong>${tarea.repeticion_hasta}</strong></h6>` : ''}
                  </div>`
               : `<h6 class="mb-3 text-muted"></h6>`}
            <div class="iq-media-group-1">
               ${usuariosHTML}
               <a href="#" class="iq-media-1" id="btn_agregar_usuario" data-id="${tarea.id}" data-nombre="${tarea.nombre_tarea}">
                  <div class="icon iq-icon-box-2 text-danger">
                  <i class="fa-solid fa-plus"></i>
                  </div>
               </a>
            </div>
            </div>
         </div>`;

      });

     var tarea_id;
     var task_nombre;
     var task_descripcion;
     var task_fecha;
      document.querySelectorAll('.eliminar_task').forEach(button =>{
         button.addEventListener('click', function(event) {
            event.preventDefault();

            tarea_id = this.getAttribute('data-id');
            task_nombre = $(this).data('nombre');
            Swal.fire({
               title: '¿Estás seguro?',
               text: `¿Quieres eliminar la tarea "${task_nombre}"?`,
               icon: 'warning',
               showCancelButton: true,
               confirmButtonText: 'Sí, eliminar',
               cancelButtonText: 'Cancelar'
            }).then((result) => {
               if (result.isConfirmed) {
                  eliminarTarea(tarea_id);
               }
            });
         });
      });
        var id_user_task;
        var nombre_tarea_por_user;
        var usuario_nombre_task;
        var id_task_user;
      document.querySelectorAll('.delete-task-user').forEach(button =>{
         button.addEventListener('click', function(event){
            event.preventDefault();
            id_user_task = $(this).data('id');
            nombre_tarea_por_user = $(this).data('nombre');
            usuario_nombre_task =$(this).data('user');
            id_task_user = $(this).data('task');

            Swal.fire({
               title: '¿Estás seguro?',
               text: `¿Quieres quitar al usuario "${usuario_nombre_task}" de la tarea "${nombre_tarea_por_user}"?`,
               icon: 'warning',
               showCancelButton: true,
               confirmButtonText: 'Sí, quitar',
               cancelButtonText: 'Cancelar'
            }).then((result)=>{
               if(result.isConfirmed){
                  quitarTaskDeUsuario(id_user_task);
               }
            });

         });
      });

      function quitarTaskDeUsuario(id_user_task){
         $.ajax({
            url:'/quitar-usuario-tarea/' + id_user_task + '/' + id_task_user,
            type: 'DELETE',
            data: {
               _token: '{{ csrf_token() }}'
            },
            success: function(response){
               Swal.fire({
                  icon: 'success',
                  title: 'Éxito',
                  text: response.message,
                  showConfirmButton: false,
                  timer: 1500
               }).then(() => {
                  fetch('/recupera-usuario-logueado')
                     .then(response => response.json())
                     .then(respuesta => {
                        recuperaUsuariologueado(respuesta);
                        Miperfil(respuesta);
                        recuperarDatosModal(respuesta);
                        mostrarListaTareas(respuesta);
                     });
               });

            },
            error: function(xhr){
               console.log(xhr.responseText);
            }

         });
      }

      function eliminarTarea(tarea_id) {
         $.ajax({
            url: '/eliminar-tarea/' + tarea_id,
            type: 'DELETE',
            data: {
               _token: '{{ csrf_token() }}'
            },
            success: function(response) {
               Swal.fire({
                  icon: 'success',
                  title: 'Éxito',
                  text: response.message,
                  showConfirmButton: false,
                  timer: 1500
               }).then(() => {
                  fetch('/recupera-usuario-logueado').then(response => response.json()).then(respuesta =>{
                     recuperaUsuariologueado(respuesta);
                     Miperfil(respuesta);
                     recuperarDatosModal(respuesta);
                     mostrarListaTareas(respuesta);
                  });
               });
            },
            error: function(xhr) {
               console.log(xhr.responseText);
               Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: 'No se pudo eliminar la tarea. Inténtalo de nuevo.',
                  showConfirmButton: true
               });
            }
         });
      }
   }
   document.addEventListener('click', function (event) {
            const btn = event.target.closest('.on_of_activa_task');
            if (!btn) return;

            event.preventDefault();
            const tarea_id = btn.getAttribute('data-id');
            const accion = btn.getAttribute('data-accion');

            $.ajax({
               url: '/cambiar-estado-tarea/' + tarea_id,
               type: 'POST',
               data: {
                  _token: '{{ csrf_token() }}',
                  accion: accion
               },
               success: function(response) {
                  Swal.fire({
                  icon: 'success',
                  title: 'Éxito',
                  text: response.message,
                  showConfirmButton: false,
                  timer: 1500
                  }).then(() => {
                  fetch('/recupera-usuario-logueado')
                     .then(res => res.json())
                     .then(data => {
                        recuperaUsuariologueado(data);
                        Miperfil(data);
                        recuperarDatosModal(data);
                        mostrarListaTareas(data);
                     });
                  });
               },
               error: function(xhr) {
                  Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: 'No se pudo cambiar el estado de la tarea. Inténtalo de nuevo.',
                  showConfirmButton: true
                  });
               }
         });
      });

      let tarea_id;
      let archivosDesdeDB = [];
      let archivosUpdateSubir = [];
      let archivosAEliminar = [];
      let preview;
     document.addEventListener('click', function (event) {
         const btn = event.target.closest('.edit_task');
         if (!btn) return;

         event.preventDefault();

         tarea_id = btn.getAttribute('data-id');
         const tarea_nombre = btn.getAttribute('data-nombre');
         const tarea_descripcion = btn.getAttribute('data-descripcion');
         const tarea_fecha = btn.getAttribute('data-fecha');

         const modalEditarTarea = document.getElementById('modalEditarTarea');
         modalEditarTarea.querySelector('#editar-tarea-nombre').value = tarea_nombre;
         modalEditarTarea.querySelector('#editar-tarea-descripcion').value = tarea_descripcion;
         modalEditarTarea.querySelector('#editar-tarea-fecha').value = tarea_fecha;

         preview = document.getElementById('editar-preview-container');
         preview.innerHTML = '<div class="text-muted">Cargando archivos...</div>';

         archivosDesdeDB = [];
         archivosUpdateSubir = [];
         archivosAEliminar = [];

         document.getElementById('editar_input_archivos_tarea').value = '';

         fetch(`/tarea-archivos/${tarea_id}`)
            .then(res => res.json())
            .then(archivos => {
               archivosDesdeDB = archivos;
               renderizarVistaEditar();

               $('#modalEditarTarea').modal('show');
            });
      });

      const input_editar_carga_ar = document.getElementById('editar_input_archivos_tarea');
      const cargar_edit_ar = document.getElementById('editar_cargar_archivos');

      cargar_edit_ar.addEventListener('click', () => {
         input_editar_carga_ar.click();
      });

      input_editar_carga_ar.addEventListener('change', function () {
         const nuevos = Array.from(this.files);

         nuevos.forEach(nuevo => {
            if (!archivosUpdateSubir.some(a => a.name === nuevo.name && a.size === nuevo.size)) {
               archivosUpdateSubir.push(nuevo);
            }
         });

         renderizarVistaEditar();
      });

      const iconosPorTipoEdit = {
         'pdf': '📄', 'doc': '📝', 'docx': '📝', 'xls': '📊', 'xlsx': '📊',
         'ppt': '📽️', 'pptx': '📽️', 'jpg': '🖼️', 'jpeg': '🖼️', 'png': '🖼️'
      };

   function renderizarVistaEditar() {
         preview.innerHTML = '';

         archivosDesdeDB.forEach((archivo) => {
            const item = document.createElement('div');
            item.classList.add('d-flex', 'align-items-center', 'justify-content-between', 'mb-1');

            item.innerHTML = `
               <div>
                  <i class="fa-solid fa-file me-2 text-primary"></i>
                  <a href="/storage/${archivo.ruta_archivo}" target="_blank">${archivo.nombre_original}</a>
               </div>
               <button class="btn btn-sm btn-quitar-archivo-db" data-id="${archivo.id}" style="font-size:11px; padding:9px">✖</button>
            `;

            preview.appendChild(item);
         });

         archivosUpdateSubir.forEach((archivo, index) => {
            const extension = archivo.name.split('.').pop().toLowerCase();
            const icon = iconosPorTipoEdit[extension] || '📁';

            const item = document.createElement('div');
            item.classList.add('d-flex', 'align-items-center', 'justify-content-between', 'mb-1');

            const contenido = document.createElement('div');
            contenido.classList.add('d-flex', 'align-items-center');

            if (['jpg', 'jpeg', 'png'].includes(extension)) {
               const reader = new FileReader();
               reader.onload = function (e) {
                  contenido.innerHTML = `
                     <img src="${e.target.result}" class="me-2" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                     <span>${archivo.name}</span>
                  `;
               };
               reader.readAsDataURL(archivo);
            } else {
               contenido.innerHTML = `
                  <span class="me-2 fs-5">${icon}</span>
                  <span>${archivo.name}</span>
               `;
            }

            const btnEliminar = document.createElement('button');
            btnEliminar.classList.add('btn', 'btn-close');
            btnEliminar.style.fontSize = '11px';
            btnEliminar.style.padding = '9px';

            btnEliminar.addEventListener('click', () => {
               archivosUpdateSubir.splice(index, 1);
               renderizarVistaEditar();
            });

            item.appendChild(contenido);
            item.appendChild(btnEliminar);
            preview.appendChild(item);
         });
      }

      document.addEventListener('click', function (e) {
         if (e.target.classList.contains('btn-quitar-archivo-db')) {
            const id = e.target.getAttribute('data-id');
            archivosAEliminar.push(id);
            archivosDesdeDB = archivosDesdeDB.filter(a => a.id != id);
            renderizarVistaEditar();
         }
      });

      /* Bandera de estado: evita doble envío al actualizar tarea */
      let actualizandoTarea = false;

      $('#btnActualizarTarea').on('click', function (event) {
         event.preventDefault();
         if (actualizandoTarea) return;

         const formEditarTarea = document.getElementById('formEditarTarea');
         const formDataEditarTarea = new FormData(formEditarTarea);

         formDataEditarTarea.append('id', tarea_id);
         formDataEditarTarea.append('archivos_eliminar', JSON.stringify(archivosAEliminar));

         actualizandoTarea = true;
         $('#btnActualizarTarea').prop('disabled', true);
         $('#btnActualizarTareaIcon').removeClass('fa-rotate').addClass('fa-spinner fa-spin');
         $('#btnActualizarTareaText').text('Actualizando...');

         $.ajax({
            url: '/update-tarea/' + tarea_id,
            type: 'POST',
            data: formDataEditarTarea,
            contentType: false,
            processData: false,
            success: function (response) {
               actualizandoTarea = false;
               $('#btnActualizarTarea').prop('disabled', false);
               $('#btnActualizarTareaIcon').removeClass('fa-spinner fa-spin').addClass('fa-rotate');
               $('#btnActualizarTareaText').text('Actualizar');

               Swal.fire({
                  icon: 'success',
                  title: 'Éxito',
                  text: response.message,
                  showConfirmButton: false,
                  timer: 1500
               }).then(() => {
                  fetch('/recupera-usuario-logueado')
                     .then(response => response.json())
                     .then(respuesta => {
                        recuperaUsuariologueado(respuesta);
                        Miperfil(respuesta);
                        recuperarDatosModal(respuesta);
                        mostrarListaTareas(respuesta);
                     });
               });
               $('#modalEditarTarea').modal('hide');
            },
            error: function (xhr) {
               actualizandoTarea = false;
               $('#btnActualizarTarea').prop('disabled', false);
               $('#btnActualizarTareaIcon').removeClass('fa-spinner fa-spin').addClass('fa-rotate');
               $('#btnActualizarTareaText').text('Actualizar');
               console.log(xhr.responseText);
               Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: 'No se pudo actualizar la tarea. Inténtalo de nuevo.',
                  showConfirmButton: true
               });
            }
         });
      });

    let usuariosAsignados = [];

document.addEventListener('click', function (event) {
  const btn = event.target.closest('#btn_agregar_usuario');
  if (!btn) return;

  event.preventDefault();
  tarea_id = btn.getAttribute('data-id');
  task_nombre = btn.getAttribute('data-nombre');

  // FIX: cada tarea abre con su propia selección en blanco (antes se
  // arrastraban usuarios seleccionados de la última tarea que editaste)
  usuariosAsignados = [];
  renderSeleccionados();

  $('#tarea_id').val(tarea_id);
  $('#tarea_nombre').text(task_nombre);

  const buscarUsuarios = document.getElementById('buscarUsuario');
  const listaUsuarios = document.getElementById('listaUsuarios_task');
  const mensajeBuscarUsuario = document.getElementById('mensajeBuscarUsuario');
  const mensajeSinResultados = document.getElementById('mensajeSinResultados');
  document.getElementById('modalAsignarUsuariosLabel').textContent = `Asignar usuarios a la tarea: ${task_nombre}`;

  if (buscarUsuarios) {
    buscarUsuarios.value = '';
    // FIX: .oninput en vez de addEventListener — antes se acumulaba un
    // listener nuevo cada vez que abrías el modal
    buscarUsuarios.oninput = function () {
      const filtro = this.value.toLowerCase();
      const tarjetas = listaUsuarios.querySelectorAll('.col-md-4');
      let coincidencias = 0;

      tarjetas.forEach(card => {
        const coincide = card.textContent.toLowerCase().includes(filtro);
        card.style.display = coincide ? 'block' : 'none';
        if (coincide) coincidencias++;
      });

      mensajeBuscarUsuario.style.display = filtro === '' ? 'block' : 'none';
      mensajeSinResultados.style.display = (coincidencias === 0 && filtro !== '') ? 'block' : 'none';
    };

    mensajeBuscarUsuario.style.display = 'block';
    mensajeSinResultados.style.display = 'none';
  }

  // FIX: se quitó la llamada duplicada a '#modalAsignarUsuario' (id inexistente, sin "s")
  $('#modalAsignarUsuarios').modal('show');
  renderUsuariosDisponibles(tarea_id); // la grilla se construye UNA sola vez al abrir
});

function renderUsuariosDisponibles(tarea_id) {
  fetch('/recupera-usuario-logueado')
    .then(response => response.json())
    .then(respuesta => {
      tareas_por_usuarios = respuesta.tareas;
      const tareaActual = tareas_por_usuarios.find(t => t.id == tarea_id);
      const usuariosYaAsignados = tareaActual ? tareaActual.usuarios_asignados.map(u => u.id) : [];
      const usuariosDisponibles = usuarios_sistemas.filter(usuario => !usuariosYaAsignados.includes(usuario.id));

      const listaUsuarios = document.getElementById('listaUsuarios_task');
      listaUsuarios.innerHTML = '';

      usuariosDisponibles.forEach(usuario => {
        listaUsuarios.innerHTML += `
          <div class="col-sm-6 col-md-4 mb-3">
            <div class="usuario-card h-100" style="display:block;">
              <div class="d-flex align-items-center">
                <img src="${usuario.foto_perfil ? usuario.foto_perfil : '{{ asset("img/default.png") }}'}" class="usuario-avatar" alt="${usuario.nombre}">
                <div>
                  <h5 class="mb-0" style="font-size:16px">${usuario.nombre} ${usuario.apellido_p} ${usuario.apellido_m}</h5>
                  <p class="mb-0" style="font-size:14px">${usuario.correo}</p>
                </div>
              </div>
              <button type="button" class="btn-ledger-primary mt-2 w-100 justify-content-center asignar_usuario_tarea"
                      data-id="${usuario.id}" data-nombre="${usuario.nombre} ${usuario.apellido_p}" data-tarea-id="${tarea_id}">
                Asignar
              </button>
            </div>
          </div>`;
      });

      // Se ocultan solo al abrir; el buscador las va mostrando (comportamiento original)
      listaUsuarios.querySelectorAll('.col-md-4').forEach(card => card.style.display = 'none');
    });
}

document.addEventListener('click', function (e) {
  const boton = e.target.closest('.asignar_usuario_tarea');
  if (!boton) return;
  toggleUsuarioSeleccionado(boton);
});

function toggleUsuarioSeleccionado(boton) {
  const id = boton.dataset.id;
  const nombre = boton.dataset.nombre;
  const tarea_id_btn = boton.dataset.tareaId;
  const tarjeta = boton.closest('.usuario-card');
  const index = usuariosAsignados.findIndex(u => u.id === id);

  if (index === -1) {
    usuariosAsignados.push({ id, nombre, tarea_id: tarea_id_btn });
    boton.innerHTML = `<i class="fa-solid fa-check"></i> Asignado`;
    boton.classList.add('is-assigned');
    tarjeta?.classList.add('is-selected');
  } else {
    usuariosAsignados.splice(index, 1);
    boton.innerHTML = `Asignar`;
    boton.classList.remove('is-assigned');
    tarjeta?.classList.remove('is-selected');
  }

  // FIX PRINCIPAL: antes aquí se llamaba a renderUsuariosDisponibles()
  // completo, que volvía a pedir datos al servidor y reconstruía TODA
  // la grilla — eso reiniciaba la búsqueda y ocultaba todas las
  // tarjetas otra vez, dando la sensación de que el modal se acababa
  // de abrir. Ahora solo se actualiza esta tarjeta puntual y la franja
  // de seleccionados de arriba.
  renderSeleccionados();
}

function renderSeleccionados() {
  const wrap = document.getElementById('usuariosSeleccionadosWrap');
  const chipsContainer = document.getElementById('usuariosSeleccionadosChips');

  if (usuariosAsignados.length === 0) {
    wrap.classList.add('d-none');
    chipsContainer.innerHTML = '';
    return;
  }

  wrap.classList.remove('d-none');
  chipsContainer.innerHTML = usuariosAsignados.map(u => `
    <span class="ledger-chip">
      ${u.nombre}
      <button type="button" class="ledger-chip-remove" data-id="${u.id}" aria-label="Quitar">×</button>
    </span>
  `).join('');
}

// Quitar un usuario desde su chip (reutiliza el mismo toggle que el botón de la tarjeta)
document.addEventListener('click', function (e) {
  const removeBtn = e.target.closest('.ledger-chip-remove');
  if (!removeBtn) return;

  const id = removeBtn.dataset.id;
  const botonTarjeta = document.querySelector(`.asignar_usuario_tarea[data-id="${id}"]`);
  if (botonTarjeta) {
    toggleUsuarioSeleccionado(botonTarjeta);
  } else {
    // La tarjeta no está en pantalla por el filtro de búsqueda; se quita igual del arreglo
    usuariosAsignados = usuariosAsignados.filter(u => u.id !== id);
    renderSeleccionados();
  }
});

let asignandoUsuarios = false;

$('#btnConfirmarAsignacion').on('click', function () {
  if (asignandoUsuarios) return;

  if (usuariosAsignados.length == 0) {
    Swal.fire({
      icon: 'warning',
      title: 'Atención',
      text: 'No has asignado ningún usuario a la tarea ' + task_nombre,
      showConfirmButton: true
    });
    return;
  }

  const tareaIdInt = Number(tarea_id);
  const id_del_usuario = usuariosAsignados.map(usuario => Number(usuario.id)).filter(id => Number.isInteger(id));

  asignandoUsuarios = true;
  $('#btnConfirmarAsignacion').prop('disabled', true);
  $('#btnConfirmarAsignacionIcon').removeClass('fa-check').addClass('fa-spinner fa-spin');
  $('#btnConfirmarAsignacionText').text('Asignando...');

  $.ajax({
    url: '/asignar-usuarios-tarea',
    type: 'POST',
    data: { _token: '{{ csrf_token() }}', tarea_id: tareaIdInt, 'usuarios_ids[]': id_del_usuario },
    success: function (response) {
      asignandoUsuarios = false;
      $('#btnConfirmarAsignacion').prop('disabled', false);
      $('#btnConfirmarAsignacionIcon').removeClass('fa-spinner fa-spin').addClass('fa-check');
      $('#btnConfirmarAsignacionText').text('Asignar seleccionados');

      Swal.fire({ icon: 'success', title: 'Éxito', text: response.message, showConfirmButton: false, timer: 1500 }).then(() => {
        fetch('/recupera-usuario-logueado').then(response => response.json()).then(respuesta => {
          recuperaUsuariologueado(respuesta);
          Miperfil(respuesta);
          recuperarDatosModal(respuesta);
          mostrarListaTareas(respuesta);
        });
      });
      $('#modalAsignarUsuarios').modal('hide');
      usuariosAsignados = [];
      renderSeleccionados();
    },
    error: function (xhr) {
      asignandoUsuarios = false;
      $('#btnConfirmarAsignacion').prop('disabled', false);
      $('#btnConfirmarAsignacionIcon').removeClass('fa-spinner fa-spin').addClass('fa-check');
      $('#btnConfirmarAsignacionText').text('Asignar seleccionados');
      console.log(xhr.responseText);
      Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo asignar los usuarios a la tarea. Inténtalo de nuevo.', showConfirmButton: true });
    }
  });
});
      /*CARD PARA MOSTRAR LAS NOTIFICACIONES EN TIEMPO REAL*/
      function cargarNotificaciones() {
        fetch("{{ route('notificaciones.json') }}")
            .then(response => response.json())
            .then(data => {
               const notificaciones = Array.isArray(data) ? data : data.notifications || [];

               const contenedor = document.getElementById('lista-notificaciones');
               contenedor.innerHTML = '';

               if (notificaciones.length === 0) {
                  contenedor.innerHTML = `
                     <div class="empty-state">
                           <i class="far fa-bell-slash d-block mb-2" style="font-size: 1.5rem;"></i>
                           <p class="mb-0 small">Todo al día por aquí</p>
                     </div>`;
                     return;
               }
               notificaciones.forEach(n => {
                  const fecha = new Date(n.created_at);
                  const opciones = {
                     year: 'numeric', month: 'long', day: 'numeric',
                     hour: '2-digit', minute: '2-digit', hour12: true,
                     timeZone: 'America/Mexico_City'
                  };
                  const fecha_formateada = fecha.toLocaleString('es-MX', opciones);
                  const claseNotificacion = !n.leida ? 'notificacion-no-leida' : 'notificacion-leida';

                  const tituloNotif = n.data.titulo || 'Notificación';
                  const nombreTareaNotif = n.data.name_task_asing || 'General';

                  contenedor.innerHTML += `
                     <li class="d-flex mb-2  ${claseNotificacion}">
                        <div class="news-icon me-3">
                           <svg class="icon-20" width="20" viewBox="0 0 24 24">
                              <path fill="currentColor" d="M20,2H4A2,2 0 0,0 2,4V22L6,18H20A2,2 0 0,0 22,16V4C22,2.89 21.1,2 20,2Z" />
                           </svg>
                        </div>
                        <div class="news-detail">
                           <p class="mb-0">${tituloNotif}</p>
                           <small class="text-muted">Nombre: ${nombreTareaNotif} – ${fecha_formateada}</small><br>
                           <a href="#" class="ver-tarea" data-tarea-id="${n.data.tarea_id}" data-notificacion-id="${n.id}">Ver tarea</a>
                        </div>
                     </li>`;
            });
         });
      }

      document.addEventListener('click', function(e){
         if(e.target.classList.contains('ver-tarea')){

            const tareaId = e.target.dataset.tareaId;
            const notificacionId = e.target.dataset.notificacionId;

            const tabMisTareas = document.querySelector('[href="#profile-mis-tareas"]');
            if (tabMisTareas) {
               const tab = new bootstrap.Tab(tabMisTareas);
               tab.show();
            }

            setTimeout(() => {
               const tarjeta = document.getElementById(`tarea-${tareaId}`);
               if (tarjeta) {
                  tarjeta.classList.add('tarea-resaltada');
                  tarjeta.scrollIntoView({ behavior: 'smooth', block: 'center' });
                  setTimeout(() => tarjeta.classList.remove('tarea-resaltada'), 90000);
               }
            }, 300);

            fetch(`/notificaciones/${notificacionId}/leer`, {
               method: 'POST',
               headers: {
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                  'Content-Type': 'application/json'
               }
            }).then(response => response.json())
            .then(data => {
               cargarNotificaciones();
            });

         }
      });

      document.addEventListener("DOMContentLoaded", cargarNotificaciones);

      setInterval(cargarNotificaciones, 3000);

</script>

@endsection