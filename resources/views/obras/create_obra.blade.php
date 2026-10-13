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
            <i class="fa-solid fa-helmet-safety" style="font-size: 35px;"></i>
          </div>
          <div>
            <h4 class="mb-0 fw-bold text-primary">Registrar nueva obra</h4>
            <small class="text-muted">Completa los datos de la nueva obra</small>
          </div>
        </div>

        <!-- Formulario -->
        <form class="row g-3" method="POST" enctype="multipart/form-data" id="formulario_registro_obra">
          @csrf

          <div class="col-md-12">
            <label class="form-label fw-semibold">Nombre de la Obra</label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="fa-solid fa-trowel-bricks text-primary"></i></span>
              <input type="text" name="nombre_obra" class="form-control" placeholder="Ej. Pavimentación Calle Central" required>
            </div>
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Estado</label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="fa-solid fa-map text-primary"></i></span>
              <input type="text" name="estado" class="form-control" placeholder="Ej. Chiapas" required>
            </div>
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Municipio</label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="fa-solid fa-city text-primary"></i></span>
              <input type="text" name="municipio" class="form-control" placeholder="Ej. Tuxtla Gutiérrez" required>
            </div>
          </div>

          <div class="col-md-12">
            <label class="form-label fw-semibold">Ubicación / Dirección</label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="fa-solid fa-location-dot text-primary"></i></span>
              <input type="text" name="ubicacion" class="form-control" placeholder="Dirección completa de la obra" required>
            </div>
          </div>

          <div class="col-md-8">
            <label class="form-label fw-semibold">Enlace de Mapa (Iframe)</label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="fa-solid fa-code text-primary"></i></span>
              <textarea name="iframe" class="form-control" rows="1" placeholder="Pegue el código <iframe> aquí..."></textarea>
            </div>
            <small class="text-muted">Opcional: Compartir desde Google Maps > Insertar mapa.</small>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Estatus de Obra</label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="fa-solid fa-toggle-on text-primary"></i></span>
              <select name="estatus" class="form-select">
                <option value="1" selected>Activa</option>
                <option value="0">Inactiva / Finalizada</option>
              </select>
            </div>
          </div>

          <div class="col-12 mt-4 d-flex justify-content-end">
            <button type="reset" class="btn btn-light me-2 fw-bold text-secondary">Limpiar</button>
            <button class="btn btn-primary px-4 fw-bold shadow-sm" id="btn_guardar_obra">
              <i class="fa-solid fa-floppy-disk me-2"></i>Guardar Obra
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>

    $(document).ready(function(){
        //recuperar datos del formulario
        $('#btn_guardar_obra').click(function(e){
            e.preventDefault();
            var formData = new FormData($('#formulario_registro_obra')[0]);
            console.log('Datos del formulario:', Object.fromEntries(formData.entries())); // Verificar datos en consola

            //enviar datos al controlador
            $.ajax({
                url: "{{ route('guardar.obra') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                    console.log('Respuesta del servidor:', response);
                    sweetAlert.fire({
                        title: '¡Obra registrada!',
                        text: 'La obra ha sido registrada exitosamente.',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    });
                    $('#formulario_registro_obra')[0].reset();
                },
                error: function(xhr){
                    console.error('Error al registrar obra:', xhr.responseText);
                    sweetAlert.fire({
                        title: 'Error',
                        text: 'Ocurrió un error al registrar la obra. Por favor, intenta nuevamente.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });    
        });
    });

</script>

</main>

@endsection