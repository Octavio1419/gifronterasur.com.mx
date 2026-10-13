@extends('layout.index')
@section('content')

<main class="main-content">
  <br><br><br><br>
  <div class="container-fluid content-inner mt-n5 py-0">
    <div class="row justify-content-center">
      <div class="col-lg-12"> 
        <div class="card border-0 shadow-lg p-4 rounded-4">
          
          <div class="d-flex align-items-center mb-4 border-bottom pb-3">
            <div class="me-3 bg-primary-subtle p-3 rounded-circle">
              <i class="fa-solid fa-user-tie text-primary" style="font-size: 30px;"></i>
            </div>
            <div>
              <h4 class="mb-0 fw-bold text-primary">Registrar Nuevo Propietario</h4>
              <small class="text-muted">Gestión de identidad y estatus del dueño del vehículo</small>
            </div>
          </div>

          <form class="row g-4" method="POST" enctype="multipart/form-data" id="formulario_registro_propietario">
            @csrf

            <div class="col-md-8">
              <label class="form-label fw-bold small text-uppercase text-muted">Nombre(s)</label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="fa-solid fa-signature text-primary"></i></span>
                <input type="text" name="nombre" class="form-control" placeholder="Ej. Juan Manuel" required>
              </div>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-bold small text-uppercase text-muted">Teléfono</label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="fa-solid fa-phone text-primary"></i></span>
                <input type="text" name="telefono" class="form-control" placeholder="961 000 0000">
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold small text-uppercase text-muted">Apellido Paterno</label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="fa-solid fa-address-card text-primary"></i></span>
                <input type="text" name="apellido_p" class="form-control" placeholder="Ej. González" required>
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold small text-uppercase text-muted">Apellido Materno</label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="fa-solid fa-address-card text-primary"></i></span>
                <input type="text" name="apellido_m" class="form-control" placeholder="Ej. Pérez" required>
              </div>
            </div>

            <div class="col-md-8">
              <label class="form-label fw-bold small text-uppercase text-muted">RFC (Registro Federal de Contribuyentes)</label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="fa-solid fa-fingerprint text-primary"></i></span>
                <input type="text" name="rfc" class="form-control" placeholder="AAAA000000XXX" maxlength="13" style="text-transform: uppercase;" required>
              </div>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-bold small text-uppercase text-muted">Estatus</label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="fa-solid fa-circle-check text-primary"></i></span>
                <select name="estatus" class="form-select border-start-0">
                  <option value="1" selected>Activo / Vigente</option>
                  <option value="0">Inactivo</option>
                </select>
              </div>
            </div>

            <div class="col-12 mt-5 d-flex justify-content-end align-items-center gap-3">
              <button type="reset" class="btn btn-link text-muted fw-bold text-decoration-none">
                <i class="fa-solid fa-eraser me-2"></i>Limpiar Formulario
              </button>
              <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm rounded-pill" id="btn_guardar_propietario">
                <i class="fa-solid fa-floppy-disk me-2"></i>Guardar Propietario
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
        $('#btn_guardar_propietario').click(function(e){
            e.preventDefault();
            //datos del forrmulaio
            var formData = new FormData($('#formulario_registro_propietario')[0]);
            console.log('Datos del formulario:', Object.fromEntries(formData.entries()));

            //ENVIANDO DATOS
            $.ajax({
                url: "{{ route('guardar.propietario') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                    sweetAlert.fire({
                        title: 'Propietario registrada!',
                        text: 'El propietario ha sido registrada exitosamente.',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    });
                    $('#formulario_registro_propietario')[0].reset();
                },
                error: function(xhr){
                    console.error('Error al registrar propietario:', xhr.responseText);
                    sweetAlert.fire({
                        title: 'Error',
                        text: 'Ocurrió un error al registrar la propietario. Por favor, intenta nuevamente.',
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