@extends('layout.index')

@section('content')
<main class="main-content">
    <br><br><br><br>
    <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card border-0 shadow-lg p-4 rounded-4">
                    
                    <div class="d-flex align-items-center mb-4 flex-wrap gap-3">
                        <div class="me-3 bg-primary-subtle p-3 rounded-circle">
                            <i class="fa-solid fa-address-book text-primary" style="font-size: 30px;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold text-primary">Directorio de Propietarios</h4>
                            <small class="text-muted">Gestión de dueños y representantes legales</small>
                        </div>
                        <div class="ms-auto">
                            <a href="{{ url('/propietarios/crear') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                                <i class="fa-solid fa-plus me-2"></i>Nuevo Propietario
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="tabla-propietarios">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 rounded-start">ID</th>
                                    <th class="border-0">Nombre Completo</th>
                                    <th class="border-0">RFC</th>
                                    <th class="border-0">Teléfono</th>
                                    <th class="border-0">Estatus</th>
                                    <th class="border-0 rounded-end text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="contenedor-propietarios">
                            </tbody>
                        </table>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {

        function mostrarSkeletonTabla() {
            let skeletonHtml = '';
            for (let i = 0; i < 5; i++) {
                skeletonHtml += `
                <tr class="placeholder-glow">
                    <td><span class="placeholder col-4"></span></td>
                    <td><span class="placeholder col-8"></span></td>
                    <td><span class="placeholder col-6"></span></td>
                    <td><span class="placeholder col-5"></span></td>
                    <td><span class="placeholder col-4 rounded-pill"></span></td>
                    <td class="text-center"><span class="placeholder col-8 rounded-pill h-100"></span></td>
                </tr>`;
            }
            $('#contenedor-propietarios').html(skeletonHtml);
        }

        function cargarPropietarios() {
            mostrarSkeletonTabla();

            fetch('/propietarios/recuperar')
                .then(response => response.json())
                .then(response => {
                    if (response.success) {
                        const propietarios = response.data;
                        let html = '';

                        if (propietarios.length === 0) {
                            $('#contenedor-propietarios').html('<tr><td colspan="6" class="text-center py-4 text-muted">No hay propietarios registrados</td></tr>');
                            return;
                        }

                        propietarios.forEach(p => {
                            const estatusBadge = p.estatus == 1 
                                ? '<span class="badge rounded-pill bg-success-subtle text-success px-3">Activo</span>' 
                                : '<span class="badge rounded-pill bg-danger-subtle text-danger px-3">Inactivo</span>';

                            html += `
                            <tr>
                                <td class="fw-bold text-secondary">#${p.id}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-info">
                                            <h6 class="mb-0 fw-bold text-dark">${p.nombre}</h6>
                                            <small class="text-muted">Registrado: ${new Date(p.created_at).toLocaleDateString()}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><code class="text-primary fw-bold">${p.rfc ? p.rfc.toUpperCase() : 'N/A'}</code></td>
                                <td>
                                    <a href="tel:${p.telefono}" class="text-decoration-none text-dark">
                                        <i class="fa-solid fa-phone me-1 text-muted small"></i> ${p.telefono ?? 'Sin número'}
                                    </a>
                                </td>
                                <td>${estatusBadge}</td>
                                <td class="text-center">
                                    <div class="btn-group shadow-sm rounded-pill bg-white p-1">
                                        <button class="btn btn-sm btn-outline-primary border-0 rounded-circle me-1 btn-edit" data-id="${p.id}">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger border-0 rounded-circle btn-delete" data-id="${p.id}">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>`;
                        });

                        $('#contenedor-propietarios').hide().html(html).fadeIn(500);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    $('#contenedor-propietarios').html('<tr><td colspan="6" class="text-center text-danger">Error al cargar datos</td></tr>');
                });
        }

        cargarPropietarios();
    });
</script>
@endsection