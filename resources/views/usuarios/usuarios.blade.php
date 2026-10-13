@extends('layout.index')
@section('content')
<main class="main-content">
    <br><br><br><br>
    <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm p-4 rounded-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary-subtle p-3 rounded-3 me-3 text-primary">
                                <i class="fa-solid fa-users-gear fa-2x"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-bold text-dark">Gestión de Usuarios</h4>
                                <p class="text-muted small mb-0">Control total de accesos, roles y sucursales del sistema.</p>
                            </div>
                        </div>
                        <button class="btn btn-primary rounded-pill px-4 shadow-sm" onclick="openModalUsuario()">
                            <i class="fa-solid fa-plus me-2"></i>Nuevo Usuario
                        </button>
                    </div>

                    <div class="row g-3 mb-4 p-3 bg-light rounded-3">
                        <div class="col-md-5">
                            <label class="form-label fw-bold small text-secondary">Buscar por Nombre/CURP</label>
                            <input type="text" id="searchUsuario" class="form-control border-0 shadow-sm" placeholder="Escribe para buscar...">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-secondary">Usuario Sistema</label>
                            <input type="text" id="searchNombreSistema" class="form-control border-0 shadow-sm" placeholder="@usuario">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-secondary">Estatus</label>
                            <select id="filterStatus" class="form-select border-0 shadow-sm">
                                <option value="">Todos los estados</option>
                                <option value="1">Activos</option>
                                <option value="0">Deshabilitados</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="text-uppercase text-secondary" style="font-size: 0.75rem; letter-spacing: 1px;">
                                <tr>
                                    <th>Usuario</th>
                                    <th>Contacto</th>
                                    <th>Rol / Sucursal</th>
                                    <th class="text-center">Estatus</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyUsuarios"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered shadow-lg">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header text-white p-3 rounded-top-2 border-0" style="background-color: #d9dfdf;">
                <div class="d-flex align-items-center">
                    <div class="bg-primary p-2 rounded-3 me-3 shadow">
                        <i class="fa-solid fa-user-plus fs-4 text-white"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0 text-dark" id="modalUsuarioTitle">Nuevo Usuario</h5>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formUsuario" enctype="multipart/form-data">
                <div class="modal-body p-0">
                    <div class="row g-0">
                        <div class="col-lg-3 bg-light p-4 border-end">
                            <div class="text-center mb-4">
                                <div class="position-relative d-inline-block">
                                    <img src="/img/01.png" id="previewImg" class="rounded-circle shadow border border-4 border-white" style="width: 160px; height: 160px; object-fit: cover;">
                                    <label for="fotoInput" class="btn btn-primary btn-sm position-absolute bottom-0 end-0 rounded-circle shadow">
                                        <i class="fa-solid fa-camera"></i>
                                    </label>
                                    <input type="file" id="fotoInput" name="foto_perfil" hidden accept="image/*" onchange="previewImage(this, 'previewImg')">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary text-uppercase"><i class="fa-solid fa-briefcase me-1"></i> Rol de Sistema</label>
                                <select class="form-select border-0 shadow-sm combo-roles" name="tipo_rol" required></select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-secondary text-uppercase"><i class="fa-solid fa-building me-1"></i> Sucursal</label>
                                <select class="form-select border-0 shadow-sm combo-sucursales" name="tipo_sucursal" required></select>
                            </div>
                        </div>
                        <div class="col-lg-9 p-4">
                            <div class="mb-4">
                                <h6 class="text-primary fw-bold mb-3 border-bottom pb-2"><i class="fa-solid fa-address-card me-2"></i>Información Personal</h6>
                                <div class="row g-3">
                                    <div class="col-md-4"><label class="form-label small fw-bold">Nombre(s)</label><input type="text" class="form-control" name="nombre" required></div>
                                    <div class="col-md-4"><label class="form-label small fw-bold">Apellido Paterno</label><input type="text" class="form-control" name="apellido_p" required></div>
                                    <div class="col-md-4"><label class="form-label small fw-bold">Apellido Materno</label><input type="text" class="form-control" name="apellido_m"></div>
                                    <div class="col-md-7"><label class="form-label small fw-bold">CURP</label><input type="text" class="form-control text-uppercase fw-bold" name="curp"></div>
                                    <div class="col-md-5"><label class="form-label small fw-bold">Teléfono</label><input type="tel" class="form-control" name="telefono"></div>
                                   
                                </div>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-primary fw-bold mb-3 border-bottom pb-2"><i class="fa-solid fa-shield-halved me-2"></i>Credenciales y Seguridad</h6>
                                <div class="row g-3">
                                    <div class="col-md-7"><label class="form-label small fw-bold">Correo Institucional</label><input type="email" class="form-control" name="correo" required></div>
                                    <div class="col-md-5"><label class="form-label small fw-bold">Usuario de Sistema</label><input type="text" class="form-control fw-bold" name="nombre_sistema" required></div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Contraseña</label>
                                        <input type="password" class="form-control" name="password" id="addPassword" required placeholder="••••••••">
                                        <div id="add_bien_confirm" class="alert alert-success shadow-sm mt-2" style="display: none; border-left: 5px solid #198754; background: rgba(25,135,84,0.1); padding: 6px 12px; font-size: 13px;">
                                            <i class="fa-solid fa-circle-check text-success"></i> Las contraseñas coinciden.
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Confirmar Contraseña</label>
                                        <input type="password" class="form-control" id="addConfirmPassword" required placeholder="••••••••">
                                        <div id="add_error_confirm" class="alert alert-danger shadow-sm mt-2" style="display: none; border-left: 5px solid #dc3545; background: rgba(220,53,69,0.1); padding: 6px 12px; font-size: 13px;">
                                            <i class="fa-solid fa-circle-xmark text-danger"></i> Las contraseñas no coinciden.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h6 class="text-primary fw-bold mb-3 border-bottom pb-2"><i class="fa-solid fa-location-dot me-2"></i>Detalles Adicionales</h6>
                                <div class="row g-3">
                                    <div class="col-md-12"><label class="form-label small fw-bold">Domicilio Particular</label><input type="text" class="form-control" name="domicilio"></div>
                                    <div class="col-md-12"><label class="form-label small fw-bold">Descripción / Notas</label><textarea class="form-control" name="descripcion_usuario" rows="2"></textarea></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3 border-0 rounded-bottom-4">
                    <button type="button" class="btn btn-link text-secondary text-decoration-none me-auto" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 shadow fw-bold">
                        <i class="fa-solid fa-floppy-disk me-2"></i>Registrar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered shadow-lg">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header text-white p-3 rounded-top-2 border-0" style="background-color: #d9dfdf;">
                <div class="d-flex align-items-center">
                    <div class="bg-primary p-2 rounded-3 me-3 shadow">
                        <i class="fa-solid fa-user-pen fs-4 text-white"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0 text-dark" id="modalEditarTitle">Configuración de Perfil</h5>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditarUsuario" enctype="multipart/form-data">
                <div class="modal-body p-0">
                    <div class="row g-0">
                        <div class="col-lg-3 bg-light p-4 border-end">
                            <div class="text-center mb-4">
                                <div class="position-relative d-inline-block">
                                    <img src="/img/01.png" id="editPreviewImg" class="rounded-circle shadow border border-4 border-white" style="width: 160px; height: 160px; object-fit: cover;">
                                    <label for="editFotoInput" class="btn btn-primary btn-sm position-absolute bottom-0 end-0 rounded-circle shadow">
                                        <i class="fa-solid fa-camera"></i>
                                    </label>
                                    <input type="file" id="editFotoInput" name="foto_perfil" hidden accept="image/*" onchange="previewImage(this, 'editPreviewImg')">
                                </div>
                                <h6 class="mt-3 mb-1 fw-bold text-primary" id="txtEditNombreCompleto">Nombre del Usuario</h6>
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3" id="txtEditRol">Rol</span>
                            </div>
                            <hr class="my-4 opacity-50">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary text-uppercase"><i class="fa-solid fa-toggle-on me-1"></i> Estatus de Cuenta</label>
                                <select class="form-select border-0 shadow-sm" name="estatus" id="editEstatus">
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary text-uppercase"><i class="fa-solid fa-briefcase me-1"></i> Rol de Sistema</label>
                                <select class="form-select border-0 shadow-sm" name="tipo_rol" id="editTipoRol" required></select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-secondary text-uppercase"><i class="fa-solid fa-building me-1"></i> Sucursal</label>
                                <select class="form-select border-0 shadow-sm" name="tipo_sucursal" id="editTipoSucursal" required></select>
                            </div>
                        </div>
                        <div class="col-lg-9 p-4">
                            <input type="hidden" id="editUserId" name="id">
                            <div class="mb-4">
                                <h6 class="text-primary fw-bold mb-3 border-bottom pb-2"><i class="fa-solid fa-address-card me-2"></i>Información Personal</h6>
                                <div class="row g-3">
                                    <div class="col-md-4"><label class="form-label small fw-bold">Nombre(s)</label><input type="text" class="form-control" name="nombre" id="editNombre" required></div>
                                    <div class="col-md-4"><label class="form-label small fw-bold">Apellido Paterno</label><input type="text" class="form-control" name="apellido_p" id="editApellidoP" required></div>
                                    <div class="col-md-4"><label class="form-label small fw-bold">Apellido Materno</label><input type="text" class="form-control" name="apellido_m" id="editApellidoM"></div>
                                    <div class="col-md-7"><label class="form-label small fw-bold">CURP</label><input type="text" class="form-control text-uppercase fw-bold" name="curp" id="editCurp"></div>
                                    <div class="col-md-5"><label class="form-label small fw-bold">Teléfono</label><input type="tel" class="form-control" name="telefono" id="editTelefono"></div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-primary fw-bold mb-3 border-bottom pb-2"><i class="fa-solid fa-shield-halved me-2"></i>Credenciales y Seguridad</h6>
                                <div class="row g-3">
                                    <div class="col-md-7"><label class="form-label small fw-bold">Correo Institucional</label><input type="email" class="form-control" name="correo" id="editCorreo" required></div>
                                    <div class="col-md-5"><label class="form-label small fw-bold">Usuario de Sistema</label><input type="text" class="form-control fw-bold" name="nombre_sistema" id="editNombreSistema" required></div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Nueva Contraseña (Opcional)</label>
                                        <input type="password" class="form-control" name="password" id="password" placeholder="••••••••">
                                        <div id="bien_confirm_password" class="alert alert-success shadow-sm mt-2" style="display: none; border-left: 5px solid #198754; background: rgba(25,135,84,0.1); padding: 6px 12px; font-size: 13px;">
                                            <i class="fa-solid fa-circle-check text-success"></i> Las contraseñas coinciden.
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Confirmar Nueva Contraseña</label>
                                        <input type="password" class="form-control" id="confirmPassword" placeholder="••••••••">
                                        <div id="error_confirm_password" class="alert alert-danger shadow-sm mt-2" style="display: none; border-left: 5px solid #dc3545; background: rgba(220,53,69,0.1); padding: 6px 12px; font-size: 13px;">
                                            <i class="fa-solid fa-circle-xmark text-danger"></i> Las contraseñas no coinciden.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h6 class="text-primary fw-bold mb-3 border-bottom pb-2"><i class="fa-solid fa-location-dot me-2"></i>Detalles Adicionales</h6>
                                <div class="row g-3">
                                    <div class="col-md-12"><label class="form-label small fw-bold">Domicilio Particular</label><input type="text" class="form-control" name="domicilio" id="editDomicilio"></div>
                                    <div class="col-md-12"><label class="form-label small fw-bold">Descripción del Usuario / Notas</label><textarea class="form-control" name="descripcion_usuario" id="editDescripcion" rows="2"></textarea></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3 border-0 rounded-bottom-4">
                    <button type="button" class="btn btn-link text-secondary text-decoration-none me-auto" data-bs-dismiss="modal">Cerrar sin guardar</button>
                    <button type="button" class="btn btn-primary rounded-pill px-5 shadow fw-bold" id="updateUsuarios">
                        <i class="fa-solid fa-circle-check me-2"></i>Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let allUsuarios = [];
    let roles = [];
    let sucursales = [];

    document.addEventListener('DOMContentLoaded', () => {
        fetchUsuarios();
        
        // Filtros en tiempo real
        document.getElementById('searchUsuario').addEventListener('input', applyFilters);
        document.getElementById('searchNombreSistema').addEventListener('input', applyFilters);
        document.getElementById('filterStatus').addEventListener('change', applyFilters);

        // Validaciones de contraseñas en tiempo real
        document.getElementById('password').addEventListener('input', validarContrasenasEdicion);
        document.getElementById('confirmPassword').addEventListener('input', validarContrasenasEdicion);
        document.getElementById('addPassword').addEventListener('input', validarContrasenasInsercion);
        document.getElementById('addConfirmPassword').addEventListener('input', validarContrasenasInsercion);

        // Envío AJAX - Formulario de registro (Nuevo Usuario)
        document.getElementById('formUsuario').addEventListener('submit', function(e) {
            e.preventDefault();
            if (!validarContrasenasInsercion()) {
                swal.fire({ icon: 'error', title: 'Error', text: 'Las contraseñas no coinciden.' });
                return;
            }

            let formData = new FormData(this);
            $.ajax({
                url: '/usuarios/guardar',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(res) {
                    swal.fire({ icon: 'success', title: '¡Éxito!', text: res.message });
                    bootstrap.Modal.getInstance(document.getElementById('modalUsuario')).hide();
                    fetchUsuarios();
                },
                error: function(xhr) {
                    swal.fire({ icon: 'error', title: 'Error en registro', text: xhr.responseJSON?.message || 'Error del servidor.' });
                }
            });
        });

        // Envío AJAX - Formulario de Edición
        document.getElementById('updateUsuarios').addEventListener('click', function() {
            if (!validarContrasenasEdicion()) {
                swal.fire({ icon: 'error', title: 'Error', text: 'Las contraseñas no coinciden.' });
                return;
            }

            let formElement = document.getElementById('formEditarUsuario');
            if (!formElement.checkValidity()) {
                formElement.reportValidity();
                return;
            }

            let formData = new FormData(formElement);
            $.ajax({
                url: '/usuarios/actualizar',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(res) {
                    swal.fire({ icon: 'success', title: '¡Actualizado!', text: res.message });
                    bootstrap.Modal.getInstance(document.getElementById('modalEditarUsuario')).hide();
                    fetchUsuarios();
                },
                error: function(xhr) {
                    swal.fire({ icon: 'error', title: 'Error al actualizar', text: xhr.responseJSON?.message || 'Error interno.' });
                }
            });
        });
    });

    function fetchUsuarios() {
        fetch('/usuarios/recuperar/listado')
            .then(res => res.json())
            .then(data => {
                allUsuarios = data.usuarios;
                roles = data.roles;
                sucursales = data.sucursales;
                renderTable(allUsuarios);
                populateCombos();
            });
    }

    function populateCombos() {
        // Población dinámica de Selects para el Modal de Crear
        $('.combo-roles').empty().append('<option value="" disabled selected>Seleccione un rol...</option>');
        roles.forEach(rol => {
            $('.combo-roles').append(`<option value="${rol.id}">${rol.nombre_rol}</option>`);
        });

        $('.combo-sucursales').empty().append('<option value="" disabled selected>Seleccione sucursal...</option>');
        sucursales.forEach(sucu => {
            $('.combo-sucursales').append(`<option value="${sucu.id}">${sucu.nombre_real}</option>`);
        });
    }

    function renderTable(usuarios) {
        const tbody = document.getElementById('tbodyUsuarios');
        tbody.innerHTML = '';

        usuarios.forEach(user => {
            const estatusBadge = user.estatus == 1 
                ? '<span class="badge rounded-pill bg-success-subtle text-success px-3">Activo</span>' 
                : '<span class="badge rounded-pill bg-danger-subtle text-danger px-3">Inactivo</span>';

            tbody.innerHTML += `
                <tr class="border-bottom">
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="${user.foto_perfil || '/img/01.png'}" class="rounded-circle me-3" width="40" height="40" style="object-fit:cover">
                            <div>
                                <div class="fw-bold text-dark">${user.nombre} ${user.apellido_p}</div>
                                <div class="text-muted small">@${user.nombre_sistema}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="small"><i class="fa-regular fa-envelope me-1"></i>${user.correo}</div>
                        <div class="small text-muted"><i class="fa-solid fa-phone me-1"></i>${user.telefono || 'N/A'}</div>
                    </td>
                    <td>
                        <div class="badge bg-light text-dark border text-uppercase" style="font-size: 0.65rem">${user.nombre_rol}</div>
                        <div class="small text-muted mt-1">${user.nombre_real}</div>
                    </td>
                    <td class="text-center">${estatusBadge}</td>
                    <td class="text-end">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-icon btn-light rounded-circle" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li><a class="dropdown-item" href="javascript:void(0)" onclick="editUsuario(${user.id})"><i class="fa-regular fa-pen-to-square me-2 text-primary"></i>Editar</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0)" onclick="toggleStatus(${user.id},${user.estatus})"><i class="fa-solid fa-power-off me-2 text-warning"></i>${user.estatus == 1 ? 'Deshabilitar' : 'Habilitar'}</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteUsuario(${user.id})"><i class="fa-solid fa-trash-can me-2"></i>Eliminar</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            `;
        });
    }

    function applyFilters() {
        const nameQuery = document.getElementById('searchUsuario').value.toLowerCase();
        const systemQuery = document.getElementById('searchNombreSistema').value.toLowerCase();
        const statusQuery = document.getElementById('filterStatus').value;

        const filtered = allUsuarios.filter(u => {
            const fullMatch = `${u.nombre} ${u.apellido_p}`.toLowerCase().includes(nameQuery);
            const systemMatch = u.nombre_sistema.toLowerCase().includes(systemQuery);
            const statusMatch = statusQuery === "" || u.estatus == statusQuery;
            return fullMatch && systemMatch && statusMatch;
        });
        renderTable(filtered);
    }

    function openModalUsuario() {
        document.getElementById('formUsuario').reset();
        document.getElementById('previewImg').src = '/img/01.png';
        document.getElementById('add_bien_confirm').style.display = 'none';
        document.getElementById('add_error_confirm').style.display = 'none';
        new bootstrap.Modal(document.getElementById('modalUsuario')).show();
    }

    function editUsuario(id) {
        const user = allUsuarios.find(u => u.id === id);
        if (!user) return;

        document.getElementById('editUserId').value = user.id;
        document.getElementById('txtEditNombreCompleto').innerText = `${user.nombre} ${user.apellido_p}`;
        document.getElementById('txtEditRol').innerText = user.nombre_rol;
        
        document.getElementById('editNombre').value = user.nombre;
        document.getElementById('editApellidoP').value = user.apellido_p;
        document.getElementById('editApellidoM').value = user.apellido_m;
        document.getElementById('editCurp').value = user.curp;
        document.getElementById('editTelefono').value = user.telefono;
        document.getElementById('editCorreo').value = user.correo;
        document.getElementById('editNombreSistema').value = user.nombre_sistema;
        document.getElementById('editDomicilio').value = user.domicilio;
        document.getElementById('editDescripcion').value = user.descripcion_usuario;
        document.getElementById('editEstatus').value = user.estatus;

        document.getElementById('password').value = '';
        document.getElementById('confirmPassword').value = '';
        document.getElementById('bien_confirm_password').style.display = 'none';
        document.getElementById('error_confirm_password').style.display = 'none';

        document.getElementById('editPreviewImg').src = user.foto_perfil ? user.foto_perfil : '/img/01.png';

        // Llenar combo de roles en edición
        $('#editTipoRol').empty();
        roles.forEach(rol => {
            let selected = (rol.id == user.tipo_rol) ? 'selected' : '';
            $('#editTipoRol').append(`<option value="${rol.id}" ${selected}>${rol.nombre_rol}</option>`);
        });

        // Llenar combo de sucursales en edición
        $('#editTipoSucursal').empty();
        sucursales.forEach(sucu => {
            let selected = (sucu.id == user.tipo_sucursal) ? 'selected' : '';
            $('#editTipoSucursal').append(`<option value="${sucu.id}" ${selected}>${sucu.nombre_real}</option>`);
        });

        new bootstrap.Modal(document.getElementById('modalEditarUsuario')).show();
    }

    function validarContrasenasEdicion() {
        const pass = document.getElementById('password').value;
        const confirmPass = document.getElementById('confirmPassword').value;
        const divBien = document.getElementById('bien_confirm_password');
        const divError = document.getElementById('error_confirm_password');

        if (pass === '' && confirmPass === '') {
            divBien.style.display = 'none';
            divError.style.display = 'none';
            return true;
        }
        if (pass === confirmPass) {
            divBien.style.display = 'block';
            divError.style.display = 'none';
            return true;
        } else {
            divBien.style.display = 'none';
            divError.style.display = 'block';
            return false;
        }
    }

    function validarContrasenasInsercion() {
        const pass = document.getElementById('addPassword').value;
        const confirmPass = document.getElementById('addConfirmPassword').value;
        const divBien = document.getElementById('add_bien_confirm');
        const divError = document.getElementById('add_error_confirm');

        if (pass === '' && confirmPass === '') {
            divBien.style.display = 'none';
            divError.style.display = 'none';
            return false;
        }
        if (pass === confirmPass) {
            divBien.style.display = 'block';
            divError.style.display = 'none';
            return true;
        } else {
            divBien.style.display = 'none';
            divError.style.display = 'block';
            return false;
        }
    }

    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function toggleStatus(id, estatus) {
        let nuevoEstatus = (estatus === 1) ? 0 : 1;
        $.ajax({
            url: `/usuarios/cambiar/estatus/${id}/${nuevoEstatus}`,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res) {
                swal.fire({ icon: 'success', title: '¡Bien!', text: res.message });
                fetchUsuarios();
            }
        });    
    }

    function deleteUsuario(id) {
        swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/usuarios/eliminar/${id}`,
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(res) {
                        swal.fire('¡Eliminado!', res.message, 'success');
                        fetchUsuarios();
                    }
                });
            }
        });
    }
</script>
@endsection