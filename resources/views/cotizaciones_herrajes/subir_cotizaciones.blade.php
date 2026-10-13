@extends('layout.index')
@section('content')
<main class="main-content">
    <br>
        <div class="text-center mb-5">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-opacity-10 p-4 mb-3" style="background-color: black;">
                <i class="fa-solid fa-file-invoice-dollar"style="font-size:40px;"></i>
            </div>
            <h2 class="fw-bold text-primary">Subir Cotización de Herrajes</h2>
            <p class="text-muted">Carga el archivo PDF de la cotización para procesar los productos y continuar con la autorización (Recuerda tener cargado anteriormente el inventario).</p>
        </div>
    <br>
    <br>
      
 <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5 text-center">
                            <!-- Zona Drag & Drop -->
                            <div class="upload-area border border-2 border-dashed rounded-4 p-5 mb-4" 
                                style="cursor: pointer; transition: 0.3s;">
                                <i class="bi bi-cloud-arrow-up text-primary mb-3" style="font-size: 3rem;"></i>
                                <h5 class="fw-bold">Arrastra tu archivo aquí</h5>
                                <p class="text-muted small">O haz clic para seleccionar un archivo PDF</p>
                                <input type="file" name="cotizacion_pdf" id="cotizacion_pdf" 
                                    class="form-control d-none" accept="application/pdf" required>
                            </div>
                            <div id="pdf-preview" class="d-none mt-4 position-relative">
                                <button type="button" id="close-preview" 
                                    class="btn btn-sm btn-danger rounded-circle position-absolute" 
                                    style="top: 40px; right: -10px; z-index: 10;">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                                <h6 class="fw-bold" style="font-size: 25px; background-color: #8eb2e9">Vista previa</h6>
                                <br>
                                <div class="border rounded-3 overflow-hidden" style="height: 500px;">
                                    <iframe id="pdf-frame" src="" class="w-100 h-100" style="border: none;"></iframe>
                                </div>
                            </div>
                            <br>
                            <br>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill" id="extraer-datos-btn" id="extrae_datos">
                                    <i class="bi bi-upload me-2"></i> Extraer datos del PDF 
                                </button>
                            </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <small class="text-muted">Solo se permite formato PDF.</small>
                </div>
            </div>
        </div>
    </div>
<div class="modal fade" id="modalDatosPDF" tabindex="-1" aria-labelledby="modalDatosPDFLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold" id="modalDatosPDFLabel">Datos extraídos de la cotización</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="row g-4">
          <div class="col-md-6">
            <div class="card shadow-sm">
              <div class="card-body border-bottom pb-3">
                <h6 class="card-title fw-bold text-primary">Datos Generales 
                   <a data-bs-toggle="modal" data-bs-target="#ayudaModal">
                  <i class="fas fa-question-circle"></i>
                  </a>
                </h6>
                <p><strong>Presupuesto:</strong> <span id="presupuesto"></span></p>
                <p><strong>Fecha:</strong> <span id="fecha_cotizacion"></span></p>
                <p><strong>Vendedor:</strong> <span id="vendedor"></span></p>
                <p><strong>Lista de precio:</strong> <span id="lista_precio"></span></p>
                <p><strong>Proveedor:</strong> HERRALUM INDUSTRIAL S.A</p>
              <br>
                  <button type="button" class="btn btn-success" id="guardarDatosGeneralesBtn">
                    <i class="bi bi-save me-2"></i> Guardar datos generales
                  </button>
                  <small id="estadoPresupuesto" class="text-muted"></small>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card shadow-sm">
              <div class="card-body border-bottom pb-3">
                <h6 class="card-title fw-bold text-success">Totales de la Cotización</h6>
                <p class="text-end"><strong>Suma:</strong> <span id="suma_cotizacion" class="text-success"></span></p>
                <p class="text-end"><strong>Descuento:</strong> <span id="descuento_cotizacion" class="text-danger"></span></p>
                <p class="text-end"><strong>Subtotal:</strong> <span id="subtotal_cotizacion"></span></p>
                <p class="text-end"><strong>Impuesto:</strong> <span id="impuesto_cotizacion"></span></p>
                <p class="text-end fw-bold"><strong>Total:</strong> <span id="total_cotizacion" class="text-primary"></span></p>
                <p class="text-danger">Nota: Asegúrate de revisar todos los datos antes de guardar la cotización. De lo contrario, al hacer el cruce de datos, podrías enfrentar inconsistencias de tus productos.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="table-responsive mt-4">
          <table class="table table-striped table-hover table-sm table-bordered align-middle" id="tabla-productos">
            <thead class="table-light">
              <tr>
                <th>Cant</th>
                <th style="width: 120px;">Código</th>
                <th style="width: 300px;">Nombre</th>
                <th>UM</th>
                <th>Precio lista</th>
                <th>Promoción</th>
                <th>Descuento (%)</th>
                <th>Precio Cliente</th>
                <th>Importe</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot class="table-light">
              <tr>
                <td colspan="8" class="text-end fw-bold">Total calculado:</td>
                <td><span id="total_cotizacion_footer" class="fw-bold text-success"></span></td>
              </tr>
            </tfoot>
          </table>
        </div>
        <div id="productosNoReconocidos" class="mt-4">
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="guardarDatosBtn" style="display: none">
          <i class="bi bi-save me-2"></i> Guardar Cotización
        </button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="productosNoGeneradosModal" tabindex="-1" aria-labelledby="productosNoGeneradosModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title fw-bold" id="productosNoGeneradosModalLabel">Productos no generados</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p style="font-size: 15px;">Los siguientes datos no se pudieron parsear. Por favor, ingresa manualmente los productos en la tabla inferior. (los productos que se muestra son referencias a lo que faltaron, para rellenarlos ten a la mano el PDF de tu cotizacion y busca el producto faltante).</p>
        <div class="alert alert-secondary" id="textoFallido" style="white-space: pre-wrap;"></div>
        <div class="table-responsive mt-3">
          <table class="table table-bordered table-sm" id="tabla-productos-manual">
            <thead class="table-warning">
              <tr>
                <th>Cant.</th>
                <th>Código</th>
                <th>Nombre</th>
                <th>UM</th>
                <th>Precio Lista</th>
                <th>Promoción</th>
                <th>Descuento</th>
                <th>Precio Cliente</th>
                <th>Importe</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
          <button class="btn btn-sm btn-outline-secondary mt-2" id="agregarFilaManual">+ Agregar fila</button>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" id="corregirProductosBtn">Agregar Productos</button>
      </div>
    </div>
  </div>
</div>
   <div class="modal fade" id="ayudaModal" tabindex="-1" aria-labelledby="ayudaModalLabel" aria-hidden="true" data-bs-backdrop="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ayudaModalLabel">Requerimientos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p>Este paso es fundamental para poder guardar la informacion de tu cotizacion, antes de que tu puedas guardar tu cotizacion debe de cumplir con los siguientes requisitos:</p>
                    <ol>
                        <li>Es de suma importancia guardar los datos generales de la cotizacion</li>
                        <li>Al no guardar los datos generales el boton "Guardar Cotización" estará deshabilitado.</li>
                        <li>Para habilitar el botón "Guardar Cotización", asegúrate de guardar los datos generales correctamente.</li>
                    </ol>
                   <p>Si tienes dudas sobre cómo completar algún campo, no dudes en consultar la documentación o pedir ayuda.</p> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
<div id="pantallaSpline" class="pantalla-spline">
  <div class="contenido">
    <img src="{{ asset('img/esperaa.gif') }}" alt="">
    <p>Estamos generando los datos, espera un momento por favor...</p>
  </div>
</div>
<style>
.pantalla-spline {
  display: none;
  position: fixed;
  top: 0; left: 0;
  width: 100vw; height: 100vh;
  background-color: #3d3a70a6;
  z-index: 9999;
  
  justify-content: center;
  align-items: center;
  flex-direction: column;
  text-align: center;
}

.pantalla-spline .contenido img {
  width: 200px; height: 200px;
  object-fit: cover;
  margin-bottom: 20px;
}

.pantalla-spline .contenido p {
  color: white;
  font-size: 1.2rem;
}
</style>
<script>
    const uploadArea = document.querySelector('.upload-area');
    const inputFile = document.querySelector('#cotizacion_pdf');
    const pdfPreview = document.querySelector('#pdf-preview');
    const pdfFrame = document.querySelector('#pdf-frame');
    const closePreview = document.querySelector('#close-preview');
    const extraerBtn = document.querySelector('#extraer-datos-btn');

    uploadArea.addEventListener('click', () => inputFile.click());

    inputFile.addEventListener('change', () => {
        if (inputFile.files.length > 0) {
            let file = inputFile.files[0];
            let fileURL = URL.createObjectURL(file);
            pdfFrame.src = fileURL;
            pdfPreview.classList.remove('d-none');
            uploadArea.style.display = 'none';
        }
    });

    closePreview.addEventListener('click', () => {
        inputFile.value = "";
        pdfFrame.src = "";
        pdfPreview.classList.add('d-none');
        uploadArea.style.display = 'block';
    });

    extraerBtn.addEventListener('click', () => {
        if (inputFile.files.length === 0) {
            alert("Primero selecciona un archivo PDF.");
            return;
        }
       
       document.getElementById("pantallaSpline").style.display = "flex";

        extraerBtn.style.display = 'none';
        let formData = new FormData();
        formData.append("cotizacion_pdf", inputFile.files[0]);
       $.ajax({
            url: "{{ route('cotizaciones.extraer') }}",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            success: function(data) {
                console.log(data);
                document.getElementById("pantallaSpline").style.display = "none";
                data.suma = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(data.suma);
                data.descuento = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(data.descuento);
                data.subtotal = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(data.subtotal);
                data.impuesto = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(data.impuesto);
                
                var sub_suma = data.subtotal.replace(/[^0-9.-]+/g,"");
                console.log(sub_suma);
                var impus_suma = data.impuesto.replace(/[^0-9.-]+/g,"");
                console.log(impus_suma);
                var sum_total_coti = parseFloat(sub_suma) + parseFloat(impus_suma);
                console.log(sum_total_coti);


                data.total = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(sum_total_coti);
                document.querySelector("#modalDatosPDFLabel").textContent = `Datos extraídos de la cotización - ${data.presupuesto}`;
                document.querySelector("#presupuesto").textContent = data.presupuesto;
                document.querySelector("#fecha_cotizacion").textContent = data.fecha_cotizacion;
                document.querySelector("#vendedor").textContent = data.vendedor;
                document.querySelector("#lista_precio").textContent = data.lista_precio;
                document.querySelector('#suma_cotizacion').textContent = data.suma;
                document.querySelector('#descuento_cotizacion').textContent = data.descuento;
                document.querySelector('#subtotal_cotizacion').textContent = data.subtotal;
                document.querySelector('#impuesto_cotizacion').textContent = data.impuesto;
                document.querySelector('#total_cotizacion').textContent = data.total;
                document.querySelector('#total_cotizacion_footer').textContent = data.total;

               let tbody = document.querySelector("#tabla-productos tbody");
                tbody.innerHTML = "";
                data.productos.forEach(prod => {
                   tbody.innerHTML += `
                        <tr>
                            <td><input type="number" style="padding: .25rem .25rem " class="form-control form-control-sm" value="${prod.cantidad}"></td>
                            <td><input type="text" style="padding: .25rem .25rem; width: 120px;" class="form-control form-control-sm" value="${prod.codigo}"></td>
                            <td><input type="text" style="padding: .25rem .25rem; width: 370px;" class="form-control form-control-sm" value="${prod.nombre}"></td>
                            <td><input type="text" style="padding: .25rem .25rem " class="form-control form-control-sm" value="${prod.um}"></td>
                            <td><input type="number" style="padding: .25rem .25rem " class="form-control form-control-sm" value="${prod.precio_lista}"></td>
                            <td><input type="number" style="padding: .25rem .25rem " class="form-control form-control-sm" value="${prod.precio_promocion}"></td>
                            <td><input type="number" style="padding: .25rem .25rem " class="form-control form-control-sm" value="${prod.descuento}"></td>
                            <td><input type="number" style="padding: .25rem .25rem " class="form-control form-control-sm" value="${prod.precio_cliente}"></td>
                            <td><input type="number" style="padding: .25rem .25rem " class="form-control form-control-sm" value="${prod.importe}"></td>
                        </tr>
                    `;
                });
              
                extraerBtn.style.display = 'block';
                // Mostrar modal
                let modal = new bootstrap.Modal(document.getElementById('modalDatosPDF'));
                modal.show();
                verificarPresupuesto(data.presupuesto);
                if(data.productos_no_reconocidos && data.productos_no_reconocidos.length > 0) {
                    
                    const productosFallidos = data.productos_no_reconocidos.slice(1);
                    let modalNoGenerados = new bootstrap.Modal(document.getElementById('productosNoGeneradosModal'));
                    modalNoGenerados.show();
                    mostrarModalManual(productosFallidos);
                    function mostrarModalManual(textoFallidoArray) {
                      const textoFallido = textoFallidoArray.join('\n');
                      document.getElementById("textoFallido").textContent = textoFallido;

                      const tbody = document.querySelector("#tabla-productos-manual tbody");
                      tbody.innerHTML = ""; // limpia por si ya se abrió antes

                      agregarFilaManual(); // agrega una fila vacía por defecto

                      const modal = new bootstrap.Modal(document.getElementById('productosNoGeneradosModal'));
                      modal.show();
                    }

          
    function agregarFilaManual() {
      const tbody = document.querySelector("#tabla-productos-manual tbody");
      tbody.insertAdjacentHTML('beforeend', `
          <tr>
              <td><input type="number" class="form-control form-control-sm"></td>
              <td><input type="text" class="form-control form-control-sm"></td>
              <td><input type="text" class="form-control form-control-sm"></td>
              <td><input type="text" class="form-control form-control-sm"></td>
              <td><input type="number" class="form-control form-control-sm"></td>
              <td><input type="number" class="form-control form-control-sm"></td>
              <td><input type="number" class="form-control form-control-sm"></td>
              <td><input type="number" class="form-control form-control-sm"></td>
              <td><input type="number" class="form-control form-control-sm"></td>
          </tr>
      `);
    }
      document.getElementById("agregarFilaManual").addEventListener("click", agregarFilaManual);
      document.getElementById("corregirProductosBtn").addEventListener("click", () => {
      const filas = document.querySelectorAll("#tabla-productos-manual tbody tr");
      const tbodyPrincipal = document.querySelector("#tabla-productos tbody");
      let filasHtml = '';
  
      filas.forEach(fila => {
          const inputs = fila.querySelectorAll("input");
          const valores = Array.from(inputs).map(input => input.value.trim());
  
          if (valores[1] && valores[2]) {
              filasHtml += `
                  <tr>
                      <td><input type="number" class="form-control form-control-sm" value="${valores[0]}"></td>
                      <td><input type="text" class="form-control form-control-sm" value="${valores[1]}"></td>
                      <td><input type="text" class="form-control form-control-sm" value="${valores[2]}"></td>
                      <td><input type="text" class="form-control form-control-sm" value="${valores[3]}"></td>
                      <td><input type="number" class="form-control form-control-sm" value="${valores[4]}"></td>
                      <td><input type="number" class="form-control form-control-sm" value="${valores[5]}"></td>
                      <td><input type="number" class="form-control form-control-sm" value="${valores[6]}"></td>
                      <td><input type="number" class="form-control form-control-sm" value="${valores[7]}"></td>
                      <td><input type="number" class="form-control form-control-sm" value="${valores[8]}"></td>
                  </tr>
              `;
          }
      });

      tbodyPrincipal.insertAdjacentHTML('beforeend', filasHtml);
  
      bootstrap.Modal.getInstance(document.getElementById('productosNoGeneradosModal')).hide();
  });
                                  
      } else {
          document.querySelector("#productosNoReconocidos").innerHTML = "";
      }
            let id_del_presupuesto = null;
               function verificarPresupuesto(presupuesto) {
                $.ajax({
                    url: "{{ route('cotizaciones.verificar.presupuesto.cotizaciones') }}",
                    method: "POST",
                    contentType: "application/json",
                    data: JSON.stringify({ presupuesto: presupuesto }),
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                      id_del_presupuesto = response.id_cotizacion.id;
                        const btnGuardarGenerales = document.querySelector("#guardarDatosGeneralesBtn");
                        const btnGuardarCotizacion = document.querySelector("#guardarDatosBtn");

                        if (response.presupuesto_existe) {
                            btnGuardarGenerales.disabled = true;
                            btnGuardarGenerales.textContent = "Ya registrado";
                        } else {
                            btnGuardarGenerales.disabled = false;
                            btnGuardarGenerales.textContent = "Guardar Datos Generales";
                        }
                        if (response.productos_registrados) {
                            btnGuardarCotizacion.style.display = 'none';
                            btnGuardarCotizacion.disabled = true;
                            btnGuardarCotizacion.textContent = "Cotización ya guardada";
                        } else {
                            btnGuardarCotizacion.style.display = 'inline-block';
                            btnGuardarCotizacion.disabled = false;
                            btnGuardarCotizacion.textContent = "Guardar Cotización";
                        }
                    },
                    error: function(err) {
                        console.error("Error al verificar presupuesto:", err);
                        alert("No se pudo verificar el estado del presupuesto.");
                    }
                });
            }
      document.querySelector("#guardarDatosGeneralesBtn").addEventListener("click", () => {

                  let sucursal_id = data.sucursal.id;
                  
                  function convertirFecha(fechaOriginal) {
                      if (!fechaOriginal) return null;
                      // Separar por espacio si no hay coma
                      const partes = fechaOriginal.includes(',') 
                          ? fechaOriginal.split(',') 
                          : fechaOriginal.split(' ');

                      const fecha = partes[0]?.trim();
                      const hora = partes[1]?.trim() || '00:00';

                      if (!fecha) return null;
                        const [dia, mes, año] = fecha.split('/');
                      if (!dia || !mes || !año) return null;

                      return `${año}-${mes}-${dia} ${hora}:00`;
                  }
                  const total_con_peso = data.total;

                  const total_limpio = total_con_peso.replace('$', '').replace(/,/g, '');

                  const precio_convertido = parseFloat(total_limpio);

                  data.fecha_cotizacion = convertirFecha(data.fecha_cotizacion);
                 let datosGenerales = {
                      presupuesto: data.presupuesto,
                      fecha_cotizacion: data.fecha_cotizacion,
                      vendedor: data.vendedor,
                      lista_precio: data.lista_precio,
                      proveedor: "HERRALUM INDUSTRIAL S.A",
                      total_cotizacion: total_limpio,
                      sucursal_id: sucursal_id,
                      vigente: true
                  };

                  $.ajax({
                      url: "{{ route('cotizacion.guardar.datos.generales') }}",
                      method: "POST",
                      contentType: "application/json",
                      data: JSON.stringify(datosGenerales),
                      headers: {
                          "X-CSRF-TOKEN": "{{ csrf_token() }}"
                      },
                      success: function(response) {
                          console.log("Datos generales guardados:", response);

                          switch(response.status) {
                              case 'success': 
                                Swal.fire({
                                      title: "Éxito!",
                                      text: response.message,
                                      icon: "success",
                                      showConfirmButton: false,
                                      timer: 2000
                                });
                                location.reload();
                                document.querySelector("#guardarDatosBtn").style.display = 'inline-block';
                                document.querySelector("#estadoPresupuesto").textContent = existe_presupuesto
                                  ? "Este presupuesto ya está registrado."
                                  : "Presupuesto disponible para guardar.";
                               
                                break;
                              case 'exists':
                                  Swal.fire({
                                      title: "Advertencia!",
                                      text: "Los datos generales ya existen. Puedes proceder a guardar la cotización.",
                                      icon: "warning",
                                      showConfirmButton: true,
                                      
                                  });
                                  document.querySelector("#guardarDatosBtn").style.display = 'inline-block';
                                  break;
                              default:
                                  alert("Respuesta inesperada del servidor.");
                          }
                      },
                      error: function(xhr, status, err) {
                          console.error("Error al guardar datos generales:", xhr.responseText);
                          alert("Ocurrió un error al guardar los datos generales.");
                      }
                  });
              });

              document.querySelector("#guardarDatosBtn").addEventListener("click", () => {
                  let productos = [];
                  document.querySelectorAll("#tabla-productos tbody tr").forEach(row => {
                      let prod = {
                          cant_a_pedir: row.cells[0].querySelector("input").value,
                          codigo: row.cells[1].querySelector("input").value,
                          nombre: row.cells[2].querySelector("input").value,
                          um: row.cells[3].querySelector("input").value,
                          precio_lista: row.cells[4].querySelector("input").value,
                          precio_promocion: row.cells[5].querySelector("input").value,
                          descuento: row.cells[6].querySelector("input").value,
                          precio_cliente: row.cells[7].querySelector("input").value,
                          importe: row.cells[8].querySelector("input").value
                      };
                      productos.push(prod);
                  });
                  
                  $.ajax({
                      url: "{{ route('cotizaciones.guardar.productos') }}",
                      method: "POST",
                      contentType: "application/json",
                      data: JSON.stringify({
                          presupuesto: id_del_presupuesto,
                          productos: productos
                      }),
                      headers: {
                          "X-CSRF-TOKEN": "{{ csrf_token() }}"
                      },
                      success: function(response) {
                          Swal.fire({
                              title: "Éxito!",
                              text: response.message,
                              icon: "success",
                              showConfirmButton: false,
                              timer: 2000
                          }).then(() => {
                              location.reload();
                          });
                      },
                      error: function(xhr, status, err) {
                          console.error("Error al guardar cotización:", xhr.responseText);
                          alert("Ocurrió un error al guardar la cotización.");
                      }
                  });

                  console.log("Productos a guardar:", productos);
              });
            },
            error: function(xhr, status, err) {
                console.error("Error al extraer datos:", xhr.responseText);
                document.getElementById("pantallaSpline").style.display = "none";
                extraerBtn.style.display = 'block';
                alert("Ocurrió un error al extraer los datos del PDF.");
            }
       });  
    });
</script>
</main>
@endsection
