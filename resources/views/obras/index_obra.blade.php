@extends('layout.index')
@section('content')
<main class="main-content">
    <br>
    <br>
    <br>
    <br>
 <div class="container-fluid content-inner mt-n5 py-0">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card border-0 shadow-lg p-4 rounded-4">
        <div class="d-flex align-items-center mb-3">
          <div class="me-3">
            <i class="fa-solid fa-toolbox" style='font-size:35px;'></i>
          </div>
          <div>
            <h4 class="mb-0 fw-bold text-primary">Listado de obras</h4>
            <small class="text-muted">Lista de todas las obras registradas</small>
          </div>
        </div>

        <div class="row g-4 mt-2" id="contenedor-obras">
        </div>
      </div>
    </div>
  </div>
</div>
<!-- MODAL PARA ACTUALIZAR DATOS DE LA OBRA -->
<div class="modal fade" id="modalActualizarObra" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold text-white" id="tituloModalEdit"></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3 p-2" id="formulario_actualizar_obra">
            @csrf
            <input type="hidden" name="id" id="edit_id">
            <div class="col-md-12">
                <label class="form-label fw-semibold">Nombre de la Obra</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-trowel-bricks text-primary"></i></span>
                    <input type="text" name="nombre_obra" id="edit_nombre" class="form-control" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Estado</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-map text-primary"></i></span>
                    <input type="text" name="estado" id="edit_estado" class="form-control" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Municipio</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-city text-primary"></i></span>
                    <input type="text" name="municipio" id="edit_municipio" class="form-control" required>
                </div>
            </div>
            <div class="col-md-12">
                <label class="form-label fw-semibold">Ubicación / Dirección</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-location-dot text-primary"></i></span>
                    <input type="text" name="ubicacion" id="edit_ubicacion" class="form-control" required>
                </div>
            </div>
            <div class="col-md-8">
                <label class="form-label fw-semibold">Enlace de Mapa (Iframe)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-code text-primary"></i></span>
                    <textarea name="iframe" id="edit_iframe" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Estatus de Obra</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-toggle-on text-primary"></i></span>
                    <select name="estatus" id="edit_estatus" class="form-select">
                        <option value="1">Activa</option>
                        <option value="0">Inactiva / Finalizada</option>
                    </select>
                </div>
            </div>
            <div class="col-12 mt-4 d-flex justify-content-end">
                <button type="button" class="btn btn-light me-2 fw-bold text-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-warning px-4 fw-bold shadow-sm text-dark" id="actualizar_datos">
                    <i class="fa-solid fa-rotate me-2"></i>Actualizar Cambios
                </button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- end Modal actualizar obra -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    $(document).ready(function() {
    function mostrarSkeletons() {
        let skeletonHtml = '';
        for (let i = 0; i < 6; i++) {
            skeletonHtml += `
            <div class="col-md-6 col-xl-4 skeleton-loader">
                <div class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden" aria-hidden="true">
                    <div class="ratio ratio-16x9 placeholder-glow bg-light border-bottom">
                        <span class="placeholder col-12 h-100"></span>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-3 placeholder-glow">
                            <span class="placeholder col-7 rounded"></span>
                            <span class="placeholder col-3 rounded"></span>
                        </div>
                        <p class="placeholder-glow mb-3"><span class="placeholder col-11"></span></p>
                        <div class="row g-0 py-2 border-top border-light placeholder-glow">
                            <div class="col-6 pe-2"><span class="placeholder col-8"></span></div>
                            <div class="col-6 ps-2"><span class="placeholder col-8"></span></div>
                        </div>
                    </div>
                </div>
            </div>`;
        }
        $('#contenedor-obras').html(skeletonHtml);
    }
    function cargarObras(conSkeleton = true) {
        if(conSkeleton) mostrarSkeletons();

        fetch('/obras/recuperar/obras-listadas')
            .then(response => response.json())
            .then(response => {
                if (response.success) {
                    const obras = response.data;
                    let htmlCards = '';

                    if(obras.length === 0) {
                        $('#contenedor-obras').html('<div class="col-12 text-center py-5"><h4>No hay obras registradas</h4></div>');
                        return;
                    }

                    obras.forEach(obra => {
                        const borderClass = obra.estatus == 1 ? 'border-success' : 'border-danger';
                        const badgeClass = obra.estatus == 1 ? 'bg-success' : 'bg-danger';
                        const badgeText = obra.estatus == 1 ? 'Activa' : 'Inactiva/Finalizada';

                        htmlCards += `
                        <div class="col-md-6 col-xl-4">
                            <div class="card h-100 shadow-sm border-start border-4 ${borderClass} rounded-3 overflow-hidden">
                                <div class="card-body p-0">
                                    <div class="ratio ratio-16x9 bg-light border-bottom">${obra.iframe ? obra.iframe : '<div class="d-flex align-items-center justify-content-center text-muted">Sin mapa</div>'}</div>
                                    <div class="p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="fw-bold text-dark mb-0 text-truncate" style="max-width: 70%;">${obra.nombre_obra}</h5>
                                            <span class="badge ${badgeClass}">${badgeText}</span>
                                        </div>
                                        <p class="text-muted small mb-3 text-truncate"><i class="fa-solid fa-location-dot text-primary me-1"></i> ${obra.ubicacion}</p>
                                        <div class="row g-0 py-2 border-top border-light">
                                            <div class="col-6 pe-1"><small class="text-muted d-block">Municipio</small><span class="fw-semibold small">${obra.municipio}</span></div>
                                            <div class="col-6 ps-1"><small class="text-muted d-block">Estado</small><span class="fw-semibold small">${obra.estado}</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-0 p-3 pt-0 d-flex justify-content-between align-items-center">
                                    <small class="text-muted">Alta: ${new Date(obra.created_at).toLocaleDateString()}</small>
                                    <button class="btn-ver-detalles btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm" data-id="${obra.id}">Ver detalles</button>
                                </div>
                            </div>
                        </div>`;
                    });

                    $('#contenedor-obras').hide().html(htmlCards).fadeIn(500);
                }
            });
    }

    cargarObras();

    $(document).on('click', '.btn-ver-detalles', function() {
        const obra_id = $(this).data('id');
        
        fetch('/obras/datos/' + obra_id).then(response => response.json()).then(response => {
            if(response.success) {
                const obra = response.data;
                $('#edit_id').val(obra.id);
                $('#edit_nombre').val(obra.nombre_obra);
                $('#edit_estado').val(obra.estado);
                $('#edit_municipio').val(obra.municipio);
                $('#edit_ubicacion').val(obra.ubicacion);
                $('#edit_iframe').val(obra.iframe);
                $('#edit_estatus').val(obra.estatus);
                $('#tituloModalEdit').text('Actualizar datos: ' + obra.nombre_obra);
                
                const modalElement = document.getElementById('modalActualizarObra');
                const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
                modal.show();
            }
        });
    });
    $('#actualizar_datos').off('click').on('click', function(e) {
        e.preventDefault();
        const obra_id = $('#edit_id').val();
        const formData = new FormData($('#formulario_actualizar_obra')[0]);
        $.ajax({
            url: '/obras/actualizar/' + obra_id,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Actualizado!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalActualizarObra'));
                    modal.hide();
                    cargarObras(false); 
                }
            }
        });
    });
});
</script>
</main>
@endsection