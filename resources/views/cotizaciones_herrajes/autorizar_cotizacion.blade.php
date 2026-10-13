@extends('layout.index')
@section('content')
<main class="main-content">
    <br>
    <br>
    <br>
    <br>
  <div class="container-fluid content-inner mt-n5 py-0">
    @include('cotizaciones_herrajes.navAutorizacion')
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card border-0 shadow-lg p-4 rounded-4">
        <div class="d-flex align-items-center mb-3">
          <div class="me-3">
            <i class="fa-solid fa-clipboard-check" style="font-size: 35px"></i>
          </div>
          <div>
            <h4 class="mb-0 fw-bold text-primary">Foliar cotizaciones</h4>
            <small class="text-muted">Selecciona la cotizacion a foliar</small>
          </div>
          <div class="ms-auto">
            <span class="badge bg-light text-dark fw-semibold" id="selectedStoreBadge">Ningun proveedor</span>
          </div>
        </div>
 
        <!-- Formulario -->
        <form class="row g-3" enctype="multipart/form-data" >
          
          
          <div class="col-md-6">
            <label for="storeSelector" class="form-label">Buscar cotizacion</label>
            <select id="storeSelector" name="tienda" class="form-select" >
             
              
            </select>
          </div>

          <div class="col-md-6">
            <label for="uploadDate" class="form-label">Fecha de cotizacion</label>
            <input type="date" id="uploadDate" name="fecha_carga" class="form-control" value="{{ date('Y-m-d') }}">
          </div>

          <div class="col-12" >
             <div class="mt-3 d-flex gap-2">
              <button id="btnBuscar" type="button" class="btn btn-primary btn-sm"><i class="fa-solid fa-magnifying-glass"></i>  Buscar cotizacion</button>
         
              <button id="btnExcel" type="button" class="btn btn-light btn-sm" style="display: none;">
              <i class="fa-solid fa-file-excel" style="color: #26785f;"></i>
              Exportar excel</button>
              <button id="btnPDF" type="button" class="btn btn-light btn-sm" style="display: none;">
              <i class="fa-solid fa-file-pdf" style="color: #e54848;"></i>
              Exportar PDF</button>
              <button id="btnCruzarDatos" type="button" class="btn btn-outline-primary btn-sm" style="display: none;">Cruzar datos</button>
            </div>
            <br>
            <label class="form-label" id="notas_de_accion" style="color: black;"></label>

            <div class="contenido" style="display:none; position: absolute;width: 100%;height: auto;text-align: center; top:165px;" id="gif_carga">
              <img src="{{ asset('img/cargando.gif') }}" alt="">
              <p>Analizando los productos de la cotización...</p>
            </div>
            
            <div class="drop-area border border-2 border-primary rounded-4 p-4 shadow-sm bg-light mb-4" tabindex="0" id="car_datos" style="display: none;">
                <div class="row g-4 justify-content-center"> <div class="col-12 col-md-6">
                <form id="motivos_autorizacion">
                  <label for="selectorMotivo" class="form-label ">Motivo del pedido</label>
                    <select name="selectorMotivo" id="selectorMotivo" class="form-select">
                            <option value="" disabled selected>Selecciona un motivo</option>
                            <option value="Reposición de inventario">Reposición de inventario</option>
                            <option value="Venta realizada en su mayoria">Venta realizada en su mayoria</option>
                            <option value="Pedidos de proyectos">Pedidos de proyectos</option>
                            <option value="Material de reproceso">Material de reproceso</option>
                            <option value="Pedido de cliente">Pedido de cliente</option>
                            <option value="Pedido exclusivo para sub-distribuidor">Pedido exclusivo para sub-distribuidor</option>
                            <option value="Pedido de policarbonato">Pedido de policarbonato</option>
                            <option value="Otro">Otro</option>
                      </select>
                  </div>
                    
                    <div class="col-12 col-md-6">
                        <label for="selectorTipoPedido" class="form-label ">Tipo de pedido</label>
                        <select name="selectorTipoPedido" id="selectorTipoPedido" class="form-select">
                            <option value="" disabled selected>Selecciona un tipo</option>
                            <option value="Urgente">Urgente</option>
                            <option value="Normal">Normal</option>
                            <option value="Programado">Programado</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                
                    <div class="col-12"> <label for="comentarios_adicionales" class="form-label ">Comentarios adicionales</label>
                        <textarea name="comentarios_adicionales" id="comentarios_adicionales" class="form-control" rows="3" placeholder="Comentarios adicionales (opcional)"></textarea>
                    </div>
                    
                    <div class="col-12 gap-3 mt-3">
                      <button type="button" class="btn btn-success px-4 btn-sm" id="btnAutorizar">
                        <i class="fa-solid fa-barcode me-2"></i> Foliar Cotización
                      </button>

                      <button type="button" class="btn btn-outline-secondary  px-4 btn-sm" id="btnSinFolio" style="display: none;">
                        <i class="fa-solid fa-file-pdf me-2"></i> Descargar sin folio
                      </button>
                    </div>
                  </form>  
              </div>
            </div>  
            <br>
            <br>
            <br>
          </div>
          <!-- Tabla de previsualización MANDARLO A UN MODAL XL-->
          <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="previewModalLabel">
                    <i class="bi bi-table me-2"></i>Previsualización del Inventario
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body bg-light">
                    <div class="alert alert-info py-2 px-3 mb-3 border-start border-3 border-primary">
                    <small><i class="bi bi-info-circle-fill me-1"></i>Solo se muestran las primeras filas del archivo para validación previa.</small>
                    </div>

                    <div class="table-responsive" style="max-height: 420px;">
                    <table class="table table-sm table-striped table-inventario align-middle">
                        <thead id="previewEncabezado"></thead>
                        <tbody id="previewBody"></tbody>
                    </table>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <span class="text-muted small">Verifica los datos antes de subir el archivo.</span>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
                </div>
            </div>
            </div>


            <!-- Progreso -->
            <div class="col-12" style="display: none;" id="progressSection">
                <div class="progress" id="uploadProgressWrap">
                <div class="progress-bar" id="uploadProgressBar"></div>
                </div>
            </div>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
      const storeSelector = document.getElementById('storeSelector');
      const proveedorSpan = document.getElementById('selectedStoreBadge');
      const fechaInput = document.getElementById('uploadDate');
      autorizados();
      var id_cotizacion = 0;
      var id_usuario = 0;
      function autorizados(){
        fetch('/recupera-cotizaciones-autorizadas').then(response => response.json()).then(response => {
            console.log(response);
            id_usuario = response.usuario.id;
            console.log('usuario logeado', id_usuario);
            storeSelector.innerHTML = '<option value="" disabled selected>Selecciona una cotizacion</option>';
            const res = response.cotizacion_autorizada;
            const autorzacionMap = new Map();
            res.forEach(cotizacion => {
                const option = document.createElement('option');
                option.value = cotizacion.id;
                option.textContent = cotizacion.presupuesto;
                storeSelector.appendChild(option);

                autorzacionMap.set(cotizacion.id,{
                  proveedor: cotizacion.proveedor,
                  fecha: cotizacion.fecha_cotizacion

                });
            });
            storeSelector.addEventListener('change', function() {
                const selectedId = this.value;
                const data = autorzacionMap.get(Number(selectedId));

                if(data){
                  proveedorSpan.textContent = data.proveedor;
                  fecha_limpia = data.fecha.split('')[0];
                }
            });

        });
      }
      let existe_foliada;
      $('#btnBuscar').click(function(){
          id_cotizacion = storeSelector.value;
          console.log('id de la cotizacoin', id_cotizacion);
          if(!id_cotizacion){
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: `Selecciona una cotización para poder continuar.`,
            confirmButtonText: 'Aceptar'
          });
          return;
      }
      fetch('/verifica-si-hay-cotizacion-foliada/'+ id_cotizacion).then(response => response.json()).then(respuesta => {
                console.log('respuesta de la verificacion', respuesta);
                existe_foliada = respuesta;
                if(respuesta.status === 'success'){
                  Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: respuesta.message,
                    confirmButtonText: 'Aceptar'
                  });
                  return;

                  document.getElementById('btnPDF').style.display = 'block';
                }
                else{
                  //mostrar formulario de foliar cotizacion 
                  document.getElementById('car_datos').style.display = 'block';
                }
        }); 
          
      });

      //haciendo consulta para verificar si ya existe una cotizacion foliada con ese id
     
      //Evento de foliar cotizacion
      $('#btnAutorizar').click(function(){
          const motivo = document.getElementById('selectorMotivo').value;

          console.log('Seleccionando el motivo del pedido', motivo)
          const tipo_pedido = document.getElementById('selectorTipoPedido').value;
          id_cotizacion = storeSelector.value;
          const comentarios = document.getElementById('comentarios_adicionales').value;

          if(!motivo || !tipo_pedido || !id_cotizacion){
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: `Rellena todos los campos para poder continuar.`,
              confirmButtonText: 'Aceptar'
            });
            return;
            
          }else{
            //Enviar datos al controlador para foliar la cotizacion
            //recolecar datos a enviar
            const datos_enviar = {
              id_cotizacion: id_cotizacion,
              motivo: motivo,
              tipo_pedido: tipo_pedido,
              comentarios_adicionales: comentarios || 'Sin comentario',
              id_usuario: id_usuario,
              folio_autorizado: 1
              
            };
            console.log('datos a enviar', datos_enviar);

            $.ajax({
                url: '/foliar-cotizacion',
                type: 'POST',
                data: datos_enviar,
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                  console.log('Respuesta del servidor:', response);

                  Swal.fire({
                    icon: 'success',
                    title: 'Cotización foliada',
                    text: `Folio generado: ${response.folio}`,
                    confirmButtonText: 'Aceptar'
                  });

                  document.getElementById('selectorMotivo').value = '';
                  document.getElementById('selectorTipoPedido').value = '';
                  document.getElementById('comentarios_adicionales').value = '';
                  storeSelector.value = '';
                  autorizados();
                },
                error: function(xhr) {
                  console.error('Error al foliar:', xhr.responseText);
                  let mensaje = 'Ocurrió un error al foliar la cotización.';

                  if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errores = Object.values(xhr.responseJSON.errors).flat();
                    mensaje = errores.join('\n');
                  }
                  Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: mensaje,
                    confirmButtonText: 'Aceptar'
                  });
            }
          });
        }
      });

      $('#btnSinFolio').click(function(){
          console.log('El usuario quiere geerar sin folio');
      });
  });
    
</script>
</main>
@endsection
    