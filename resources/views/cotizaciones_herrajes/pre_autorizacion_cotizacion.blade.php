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
            <i class="fa-solid fa-clipboard-check" style="font-size: 35px"></i>
          </div>
          <div>
            <h4 class="mb-0 fw-bold text-primary">Pre-Autorizar cotizaciones</h4>
            <small class="text-muted">Selecciona la cotizacion y analiza los productos</small>
          </div>
          <div class="ms-auto">
            <span class="badge bg-light text-dark fw-semibold" id="selectedStoreBadge">Ningun proveedor</span>
          </div>
        </div>
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
              <button id="btnRevisar" type="button" class="btn btn-primary btn-sm"><i class="fa-solid fa-magnifying-glass"></i> ANALIZAR PRODUCTOS</button>
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
            <div class="drop-area border border-2 border-dashed rounded-3 p-4 text-center bg-light-subtle" tabindex="0" id="car_datos" style="display: none;">
              <div class="table-responsive"> 
                  <table class="table table-striped table-hover table-sm table-bordered align-middle" id="tabla-productos">
                    <thead class="table-light" id="incide_tabla_productos"></thead>
                    <tbody id="tbody_tabla"></tbody>
                  </table>
              </div>
            </div>
            <br>
            <br>
            <br>
          </div>
          <!-- Tabla de previsualización-->
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
<!-- Modal -->

<!-- modal agregar producto-->
<div class="modal fade" id="modalDatosPDF" tabindex="-1" aria-labelledby="modalDatosPDFLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold" id="modalDatosPDFLabel">Datos extraídos de la cotización</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <div class="row g-4">
          <!-- Datos generales -->
          <div class="col-md-6">
            <div class="card shadow-sm">
              <div class="card-body border-bottom pb-3">
                <h6 class="card-title fw-bold text-primary">Datos Generales 
                   <a data-bs-toggle="modal" data-bs-target="#ayudaModal">
                  <i class="fas fa-question-circle"></i>
                  </a>
                </h6>
                 
                        <!-- Ícono de ayuda -->
                 
                <p><strong>Presupuesto:</strong> <span id="presupuesto"></span></p>
                <p><strong>Fecha:</strong> <span id="fecha_cotizacion"></span></p>
                <p><strong>Vendedor:</strong> <span id="vendedor"></span></p>
                <p><strong>Lista de precio:</strong> <span id="lista_precio"></span></p>
                <p><strong>Proveedor:</strong> HERRALUM INDUSTRIAL S.A</p>
               <!--BOTON DE GUARDAR DATOS GENERALES, LA LOGICA SERA GUARDAR LOS DATOS PARA HACER RELACION A ESA COTIZACION, PRIMERO EL USUARIO GUARDARA LOS DATOS GENERALES, CUANOD ESTE GUARDE SE HABILITA EL BOTON DE GUARDAR COTIZACION-->
              <br>
                  <button type="button" class="btn btn-success" id="guardarDatosGeneralesBtn">
                    <i class="bi bi-save me-2"></i> Guardar datos generales
                  </button>
                  <small id="estadoPresupuesto" class="text-muted"></small>
              </div>
            </div>
          </div>

          <!-- Totales -->
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

        <!-- Tabla de productos -->
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
              <!-- Se llena dinámicamente -->
            </tbody>
            <tfoot class="table-light">
              <tr>
                <td colspan="8" class="text-end fw-bold">Total calculado:</td>
                <td><span id="total_cotizacion_footer" class="fw-bold text-success"></span></td>
              </tr>
            </tfoot>
          </table>
        </div>

        <!-- Productos no reconocidos -->
        <div id="productosNoReconocidos" class="mt-4">
          <!-- Se llena dinámicamente si hay errores -->
        </div>
      </div>

      <div class="modal-footer">
        <!--
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
        -->
        <button type="button" class="btn btn-success" id="guardarDatosBtn" style="display: none">
          <i class="bi bi-save me-2"></i> Guardar Cotización
        </button>
      </div>
    </div>
  </div>
</div>

    <img id="logo" src="{{ asset('img/logotipo_herralum.png') }}" style="display:none;">
    <img id="logo_frontera" src="{{ asset('img/logo_frontera_sin.png') }}" style="display:none;">
  <style>
    .skeleton {
    position: relative;
    overflow: hidden;
    background-color: #e9ecef;
    border-radius: 4px;
    min-height: 18px;
    }
    .skeleton::after {
      content: "";
      position: absolute;
      top: 0;
      left: -150px;
      height: 100%;
      width: 150px;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
      animation: loading 1.2s infinite;
    }

    @keyframes loading {
      100% {
        left: 100%;
      }
    }


  </style>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const storeSelector = document.getElementById('storeSelector'); // <select>
    const proveedorSpan = document.getElementById('selectedStoreBadge');     // <span id="proveedor">
    const fechaCotizacionSpan = document.getElementById('uploadDate'); // <span id="uploadDate">
    const suc_asociada = document.getElementById('sucursal_asociada');

    const notas = document.getElementById('notas_de_accion');
    
    var id_presupuesto = 0;
    var codigo_presupuesto = 0;
    var datos_generales;
    var sucursal;
    var fechaLimpia;
    var vendedor;
    var total_cotizacion_previa;
    function cotizaciones() {
        fetch('/recupera-cotizaciones-vigente')
        .then(r => r.json())
        .then(respuesta => {
            console.log('Respuesta del servidor:', respuesta);
            datos_generales = respuesta;

            storeSelector.innerHTML = '<option value="" selected disabled>-- Selecciona el número de cotización --</option>';

            const cotizacionesMap = new Map();

            (respuesta.cotizacion_vigente || []).forEach(c => {
                const opt = document.createElement('option');
                opt.value = c.id;
                opt.textContent = c.presupuesto;
                storeSelector.appendChild(opt);
                
                cotizacionesMap.set(c.id, {
                    proveedor: c.proveedor || 'N/A',
                    fecha: c.fecha_cotizacion || 'Sin fecha',
                    vendedor: c.vendedor,
                    total_cotizacion: c.total_cotizacion
                });
            });

            // Cuando el usuario seleccione una cotización
            storeSelector.addEventListener('change', function() {
                const selectedId = this.value;
                console.log('este es el id', selectedId);
                const data = cotizacionesMap.get(Number(selectedId));
                console.log('mapeando', cotizacionesMap);
                
                if (data) {
                    proveedorSpan.textContent = data.proveedor;
                    // Formatear la fecha si viene con hora
                    fechaLimpia = data.fecha.split(' ')[0];
                    vendedor = data.vendedor;
                    total_cotizacion_previa = data.total_cotizacion;
                    console.log('Esta es la fecha de limpia', fechaLimpia);
                    fechaCotizacionSpan.value = fechaLimpia;
                }
            });
        })
        .catch(error => {
            console.error('Error al recuperar cotizaciones:', error);
        });
    }

    cotizaciones();
    var datos_pdf;
    $('#btnRevisar').click(function(){
        console.log('El usuario quiere revisar los productos');
        id_presupuesto = $('#storeSelector').val();
        codigo_presupuesto = $('#storeSelector option:selected').text();

        if(!id_presupuesto){
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: `Para poder revisar los productos es necesario tener seleccionado en el selector una cotizacion.`,
                confirmButtonText: 'Aceptar'
              });
              return;
        }

        const gif_carga = document.getElementById('gif_carga');
        notas.innerHTML = '';
        gif_carga.style.display = 'block';

        mostrarSkeleton();
        
        fetch('/buscar-cotizacion/' + id_presupuesto)
            .then(response => response.json())
            .then(respuesta => {
                productosRelacionados(respuesta);
                console.log('datos recividos', respuesta);
                datos_pdf = respuesta;
                gif_carga.style.display = 'none';
                notas.innerHTML = 'Nota: <small>Revisa bien los productos antes de dar click en (Autorizar).</small>';
        });
    });

    function mostrarSkeleton() {
        let tbody = document.getElementById("tbody_tabla");
        tbody.innerHTML = '';
        for (let i = 0; i < 15; i++) {
            tbody.innerHTML += `
                <tr>
                    <td><div class="skeleton" style="width:100px; height:20px;"></div></td>
                    <td><div class="skeleton" style="width:250px; height:20px;"></div></td>
                    <td><div class="skeleton" style="width:60px; height:20px;"></div></td>
                    <td><div class="skeleton" style="width:80px; height:20px;"></div></td>
                    <td><div class="skeleton" style="width:80px; height:20px;"></div></td>
                    <td><div class="skeleton" style="width:80px; height:20px;"></div></td>
                    <td><div class="skeleton" style="width:80px; height:20px;"></div></td>
                    <td><div class="skeleton" style="width:100px; height:20px;"></div></td>
                    <td><div class="skeleton" style="width:100px; height:20px;"></div></td>
                    <td><div class="skeleton" style="width:80px; height:20px;"></div></td>
                    <td><div class="skeleton" style="width:80px; height:20px;"></div></td>
                    <td><div class="skeleton" style="width:80px; height:20px;"></div></td>
                    <td><div class="skeleton" style="width:80px; height:20px;"></div></td>
                    <td><div class="skeleton" style="width:80px; height:20px;"></div></td>
                    <td><div class="skeleton" style="width:80px; height:20px;"></div></td>
                    <td><div class="skeleton" style="width:80px; height:20px;"></div></td>
                </tr>
            `;
        }
    }
    var sucursal_usuario_global = '';
    function productosRelacionados(respuesta){
        console.log('Estos son los productos base a la cotizacion enviada', respuesta);

        console.log('estas son los datos analisados', respuesta);
        //mapeando para sacar la sucursal del usuario
        const sucursal_usuario = respuesta.length > 0 ? respuesta[0].sucursal.nombre_real : 'N/A';
        //el dato es o
        console.log('Sucursal del usuario para el PDF', sucursal_usuario);

        sucursal_usuario_global = sucursal_usuario;

        let tbody = document.getElementById("tbody_tabla");
        tbody.innerHTML = ''; //  aquí sí borramos skeleton y cargamos los datos reales
        const card_visible = document.getElementById('car_datos');
        card_visible.style.display = 'flex';

        let indice_productos = document.getElementById('incide_tabla_productos');
        const btnExcel = document.getElementById('btnExcel');
        const btnPDF = document.getElementById('btnPDF');
        btnPDF.style.display = 'block';
        btnExcel.style.display = 'block';
        

        indice_productos.innerHTML = '';

        indice_productos.innerHTML += ` <tr> 
                    <th style="width: 120px;">Código</th>
                    <th style="width: 300px;">Nombre</th>
                    <th >UM</th>
                    <th>Cant a pedir</th>
                    <th>Precio lista</th>
                    <th>Promoción</th>
                    <th>Descuento (%)</th>
                    <th>Precio Cliente</th>
                    <th>Importe</th>
                    <th>Existencia en suc</th>
                    <th>Stock total</th>
                    <th>Vendido 6m</th>
                    <th>Promedio mensual</th>
                    <th>Sugerencia</th>
                    <th>Análisis obtenido</th>
                    <th>Accion</th>
                  </tr>`;
        let datos = respuesta;        

        datos.forEach(p => {
              let existenciasHTML = '';
              if(p.existencias && Object.keys(p.existencias).length > 0){
                  for(const [sucursal, cant] of Object.entries(p.existencias)){
                      existenciasHTML += `<div><b>Suc ${sucursal}:</b> ${cant}</div>`;
                  }
              } else {
                  existenciasHTML = '<span class="text-muted">Sin existencias</span>';
              }

         
            tbody.innerHTML += `
                <tr class="fade-in">
                    <td><input type="text" class="form-control form-control-sm" style="padding: .25rem .25rem; width:130px;" value="${p.codigo}"></td>
                    <td><input type="text" class="form-control form-control-sm" style="padding: .25rem .25rem; width:380px;" value="${p.producto}"></td>
                    <td><input type="text" class="form-control form-control-sm" style="padding: .25rem .25rem; width:40px;" value="${p.um}"></td>
                    <td><input type="number" class="form-control form-control-sm" value="${p.cantidad_cotizada}"></td>
                    <td><input type="number" class="form-control form-control-sm" value="${p.precio_lista}"></td>
                    <td><input type="number" class="form-control style="padding: .25rem .25rem; width:115px;"  form-control-sm" value="${p.precio_promocion}"></td>
                    <td><input type="number" class="form-control form-control-sm" value="${p.descuento}"></td>
                    <td><input type="number" class="form-control form-control-sm" value="${p.precio_cliente}"></td>
                    <td><input type="number" class="form-control" style="padding: .25rem .25rem; width:100px;" form-control-sm" value="${p.importe}"></td>
                    <td>${existenciasHTML}</td>
                    <td>${p.stock_total}</td>
                    <td><span class="badge bg-info">${p.ventas_6m}</span></td>
                    <td>${p.promedio_mensual}</td>
                    <td><span class="badge bg-warning">${p.sugerencia_compra}</span></td>
                    <td>
                      <span class="badge ${p.accion === 'Comprar' ? 'bg-danger' : 'bg-success'}">
                            ${p.accion}
                      </span>
                    </td> 
                    <td>
                      <button data-id="${p.id}" data-nombre="${p.producto}" class="actualizar_produc btn btn-success btn-sm" type="button"><i class="fa-solid fa-rotate-right"></i></button>
                      <button data-id="${p.id}" data-nombre="${p.producto}" class="delete_product btn btn-danger btn-sm " type="button"><i class="fas fa-trash-alt"></i></button>
                    </td>
                </tr>
            `;
           
        });
        //abarcara el ancho de toda la tabla
         tbody.innerHTML += `
                <tr class="fade-in">
                    <td colspan="18" class="text-center">
                      <button id="agregarProductoBtn" class="btn btn-primary btn-sm" type="button">
                        <i class="fas fa-plus-circle me-2"></i> Agregar nuevo producto
                      </button>
                    </td>
                </tr>
          `;

function agregarFilaVacia() {
    const tbody = document.getElementById('tbody_tabla');
    const filaBoton = tbody.querySelector('tr:last-child'); 
    const nuevaFila = document.createElement('tr');
    nuevaFila.classList.add('fade-in', 'fila-nueva');
    nuevaFila.innerHTML = `
        <td><input type="text" class="form-control form-control-sm nuevo-codigo" placeholder="Código"></td>
        <td><input type="text" class="form-control form-control-sm nuevo-nombre" placeholder="Nombre del producto"></td>
        <td><input type="text" class="form-control form-control-sm nuevo-um" placeholder="UM"></td>
        <td><input type="number" class="form-control form-control-sm nuevo-cantidad" value="0"></td>
        <td><input type="number" class="form-control form-control-sm nuevo-precio-lista" value="0"></td>
        <td><input type="number" class="form-control form-control-sm nuevo-promocion" value="0"></td>
        <td><input type="number" class="form-control form-control-sm nuevo-descuento" value="0"></td>
        <td><input type="number" class="form-control form-control-sm nuevo-precio-cliente" value="0"></td>
        <td><input type="number" class="form-control form-control-sm nuevo-importe" value="0"></td>
        <td><span class="text-muted">-</span></td>
        <td><span class="text-muted">-</span></td>
        <td><span class="text-muted">-</span></td>
        <td><span class="text-muted">-</span></td>
        <td><span class="text-muted">Pendiente</span></td>
        <td>
            <button class="btn btn-primary btn-sm guardar_nueva_fila" type="button" title="Guardar">
                <i class="fa-solid fa-check"></i>
            </button>
            <button class="btn btn-secondary btn-sm cancelar_nueva_fila" type="button" title="Cancelar">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </td>
    `;

    tbody.insertBefore(nuevaFila, filaBoton);
    nuevaFila.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

$(document).on('click', '#agregarProductoBtn', function () {
    agregarFilaVacia();
});

$(document).on('click', '.cancelar_nueva_fila', function () {
    $(this).closest('tr').remove();
});

$(document).on('click', '.guardar_nueva_fila', function () {
    const fila = $(this).closest('tr');

    const producto = {
        codigo: fila.find('.nuevo-codigo').val().trim(),
        producto: fila.find('.nuevo-nombre').val().trim(),
        um: fila.find('.nuevo-um').val().trim(),
        cantidad_cotizada: parseFloat(fila.find('.nuevo-cantidad').val()) || 0,
        precio_lista: parseFloat(fila.find('.nuevo-precio-lista').val()) || 0,
        precio_promocion: parseFloat(fila.find('.nuevo-promocion').val()) || 0,
        descuento: parseFloat(fila.find('.nuevo-descuento').val()) || 0,
        precio_cliente: parseFloat(fila.find('.nuevo-precio-cliente').val()) || 0,
        importe: parseFloat(fila.find('.nuevo-importe').val()) || 0,
        id_presupuesto: id_presupuesto
    };

    if (!producto.codigo || !producto.producto) {
        Swal.fire('Faltan datos', 'El código y el nombre del producto son obligatorios.', 'warning');
        return;
    }

    $.ajax({
        url: '/agregar-producto-pre-autorizacion', 
        method: 'POST',
        data: producto,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (p) {
            let existenciasHTML = '';
            if (p.existencias && Object.keys(p.existencias).length > 0) {
                for (const [suc, cant] of Object.entries(p.existencias)) {
                    existenciasHTML += `<div><b>Suc ${suc}:</b> ${cant}</div>`;
                }
            } else {
                existenciasHTML = '<span class="text-muted">Sin existencias</span>';
            }

            fila.removeClass('fila-nueva');
            fila.html(`
                <td><input type="text" class="form-control form-control-sm" value="${p.codigo}"></td>
                <td><input type="text" class="form-control form-control-sm" value="${p.producto}"></td>
                <td><input type="text" class="form-control form-control-sm" value="${p.um}"></td>
                <td><input type="number" class="form-control form-control-sm" value="${p.cantidad_cotizada}"></td>
                <td><input type="number" class="form-control form-control-sm" value="${p.precio_lista}"></td>
                <td><input type="number" class="form-control form-control-sm" value="${p.precio_promocion}"></td>
                <td><input type="number" class="form-control form-control-sm" value="${p.descuento}"></td>
                <td><input type="number" class="form-control form-control-sm" value="${p.precio_cliente}"></td>
                <td><input type="number" class="form-control form-control-sm" value="${p.importe}"></td>
                <td>${existenciasHTML}</td>
                <td>${p.stock_total}</td>
                <td><span class="badge bg-info">${p.ventas_6m}</span></td>
                <td>${p.promedio_mensual}</td>
                <td><span class="badge bg-warning">${p.sugerencia_compra}</span></td>
                <td><span class="badge ${p.accion === 'Comprar' ? 'bg-danger' : 'bg-success'}">${p.accion}</span></td>
                <td>
                    <button data-id="${p.id}" data-nombre="${p.producto}" class="actualizar_produc btn btn-success btn-sm" type="button"><i class="fa-solid fa-rotate-right"></i></button>
                    <button data-id="${p.id}" data-nombre="${p.producto}" class="delete_product btn btn-danger btn-sm" type="button"><i class="fas fa-trash-alt"></i></button>
                </td>
            `);

            datos.push(p); 
            Swal.fire('Listo', 'Producto agregado correctamente.', 'success');
        },
        error: function (xhr) {
            console.error('Error al agregar producto:', xhr.responseText);
            Swal.fire('Error', 'No se pudo agregar el producto.', 'error');
        }
    });
});
    document.getElementById('btnCruzarDatos').style.display = 'block'; 
    document.getElementById('btnExcel').addEventListener('click', function (){
    const wsData = [];

      wsData.push([
          "Código",
          "Nombre",
          "UM",
          "Precio lista",
          "Promoción",
          "Cant a pedir",
          "Importe",
          "Existencia en sucursales",
          "Stock Total",
          "Vendido meses atras",
          "Sugerencia de pedido",
          "Análisis obtenido"
      ]);

      // Filas
      datos.forEach(p => {
          let existencias = "Sin existencias";
          if (p.existencias && Object.keys(p.existencias).length > 0) {
              existencias = Object.entries(p.existencias)
                  .map(([sucursal, cant]) => `Suc ${sucursal}: ${cant}`)
                  .join(" | ");
          }

          wsData.push([
              p.codigo,
              p.producto,
              p.um,
              p.precio_lista,
              p.precio_promocion,
              p.cantidad_cotizada,
              p.importe,
              existencias,
              p.stock_total,
              p.ventas_6m,
              p.sugerencia_compra,
              p.accion
          ]);
      });

          // Creamos el libro y hoja
          const wb = XLSX.utils.book_new();
          const ws = XLSX.utils.aoa_to_sheet(wsData);

          ws['!cols'] = [
              { wch: 15 }, // Código
              { wch: 50 }, // Nombre
              { wch: 8 },  // UM
              { wch: 12 }, // Precio lista
              { wch: 12 }, // Promoción
              { wch: 12 }, // Cant a pedir
              { wch: 50 }, // Existencia
              { wch: 10 }, // Vendido
              { wch: 20 }  // Análisis
          ];
 
          // Agregamos la hoja al libro
          XLSX.utils.book_append_sheet(wb, ws, "Productos");
          codigo_presupuesto = $('#storeSelector option:selected').text();
          console.log('Recuperando el codigo al genera excel', codigo_presupuesto);

          const nombre_excel = 'pre_autorizacion_'+ codigo_presupuesto + '.xlsx';

          // Descargamos
          XLSX.writeFile(wb,  nombre_excel);
      });

      //generando PDF
      document.getElementById('btnPDF').addEventListener('click', function(){
          console.log('El usuario quiere generar el PDF');
          const { jsPDF } = window.jspdf;
          const doc = new jsPDF({orientation: 'l'});

          console.log('Generando el PDF con los siguientes datos', datos_pdf);
          const img1 = document.getElementById('logo');
          const img2 = document.getElementById('logo_frontera');

          const canvas1 = document.createElement('canvas');
          const canvas2 = document.createElement('canvas');
          canvas1.width = img1.naturalWidth;
          canvas2.width = img2.naturalWidth;
          canvas1.height = img1.naturalHeight;
          canvas2.height = img2.naturalHeight;
          const ctx1 = canvas1.getContext('2d');
          const ctx2 = canvas2.getContext('2d');
          ctx1.drawImage(img1, 0 ,0);
          ctx2.drawImage(img2,0,0);
          const imgData1 = canvas1.toDataURL('image/png');
          const imgData2 = canvas2.toDataURL('image/png');

          doc.addImage(imgData1, 'PNG', 230,10,40,15);
          doc.addImage(imgData2, 'PNG', 15,10,60,15);
          const datos_g = datos_generales.cotizacion_vigente;
          console.log('al dar click seguimos recuperando la sucursal', sucursal_usuario_global);
          console.log(datos_g);
          doc.setFontSize(16);
          doc.setTextColor(0,0,0);
          doc.setFont("helvetica", "bold");
          codigo_presupuesto = $('#storeSelector option:selected').text();
          const nombre_coti = "ANALISIS DE APROBACION DE PRODUCTOS (AAP)";
          doc.text(nombre_coti.toUpperCase(), 80,20,{aling:'center'});
          doc.setFontSize(14);
          doc.setFont("helvetica");
          doc.setTextColor(100); // gris
          doc.text(sucursal_usuario_global.toUpperCase(), 15,32,{aling:'center'});
          //datos generales de la cotizacion
          doc.setFontSize(11);
          doc.setFont("helvetica");
          doc.setTextColor(100); // gris
          doc.text("N° cotizacion: " + codigo_presupuesto, 15,40);
          doc.text("Fecha cotizacion: " + fechaLimpia,15,45);
          doc.text("Proveedor: HERRALUM INDUSTRIAL S.A DE C.V.", 15, 50);
          doc.text("Vendedor: " + vendedor, 200, 40);
          //formatenado  a pesos mexicanos
          const consto_coti_previa = new Intl.NumberFormat('es-MX', {
              style:'currency',
              currency: 'MXN',
          }).format(total_cotizacion_previa);
          

          // Recuperar todos los importes
          const importes = respuesta.map(item => parseFloat(item.importe));

          // Mostrar la lista de importes
          console.log("Importes individuales:", importes);

          // Calcular la suma total de todos los importes
          const totalImporte = importes.reduce((acum, valor) => acum + valor, 0);
          console.log("Total de importes:", totalImporte.toFixed(2));

          if (total_cotizacion_previa.toFixed(2) !== totalImporte.toFixed(2)) {
              const diferencia = total_cotizacion_previa - totalImporte;
              const diferenciaConIVA = diferencia * 1.16;
              const diferenciaFormateada = new Intl.NumberFormat('es-MX', {
                  style: 'currency',
                  currency: 'MXN',
              }).format(diferenciaConIVA);

              doc.setFontSize(11);
              doc.setFont("helvetica", "bold");
              const total_masiva = totalImporte * 1.16;
              const consto_coti_cruzado = new Intl.NumberFormat('es-MX', {
              style:'currency',
              currency: 'MXN',
          }).format(total_masiva);    
              doc.setTextColor(100); 
              doc.text("Total cruzado: " + consto_coti_cruzado, 200, 50);
          }
          doc.text("Total cotizacion: " + consto_coti_previa, 200,45);

          const columns = [
              { header: 'Código', dataKey: 'codigo', cellWidth: 15 },
              { header: 'Producto', dataKey: 'producto', cellWidth: 50 }, 
              { header: 'U.M.', dataKey: 'um', cellWidth: 8 },
              { header: 'Ventas 6m', dataKey: 'ventas_6m', cellWidth: 15 },
              { header: 'Prom. Mens.', dataKey: 'promedio_mensual',cellWidth:15 },
              { header: 'Stock Total', dataKey: 'stock_total', cellWidth: 15 },
              { header: 'Sug. Compra', dataKey: 'sugerencia_compra', cellWidth: 15 },
              { header: 'Existencia En Suc', dataKey: 'existencias', cellWidth: 30 }, // Existencias detalladas
              { header: 'P. Lista', dataKey: 'precio_lista', cellWidth: 15 },
              { header: 'Prom. Act.', dataKey: 'precio_promocion', cellWidth: 15 },
              { header: 'Desc (%)', dataKey: 'descuento', cellWidth: 15 },
              { header: 'P. Cliente', dataKey: 'precio_cliente', cellWidth: 15 },
              { header: 'Cant. Cot.', dataKey: 'cantidad_cotizada', cellWidth: 15 },
              { header: 'Importe', dataKey: 'importe', cellWidth: 18 },
              { header: 'Acción', dataKey: 'accion', cellWidth: 15 }
          ];

          const data = respuesta.map(item => {

          let existenciasTexto = '';
             if (item.existencias) {
             
 if (item.existencias[1]) existenciasTexto += `Suc 1: ${parseFloat(item.existencias[1]).toFixed(2)}\n`;
 if (item.existencias[2]) existenciasTexto += `Suc 2: ${parseFloat(item.existencias[2]).toFixed(2)}`;
              }


      return {
      codigo: item.codigo,
      producto: item.producto,
      um: item.um,
      ventas_6m: item.ventas_6m,
      promedio_mensual: parseFloat(item.promedio_mensual).toFixed(2),
      stock_total: item.stock_total,
      sugerencia_compra: parseFloat(item.sugerencia_compra).toFixed(2),
      existencias: existenciasTexto.trim(), // Existencias detalladas
      precio_lista: new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2 }).format(parseFloat(item.precio_lista)),
      precio_promocion: new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2 }).format(parseFloat(item.precio_promocion)),
      descuento: parseFloat(item.descuento).toFixed(2),
      precio_cliente: new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2 }).format(parseFloat(item.precio_cliente)),
      cantidad_cotizada: parseFloat(item.cantidad_cotizada).toFixed(2),
      importe: new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2 }).format(parseFloat(item.importe)),
                  accion: item.accion 
          }
      });

      doc.autoTable(columns, data, {
         startY: 60,
         margin: { left: 5, right: 5 },
       styles: { 
         font: "helvetica",
         fontSize: 7,
         cellPadding: 1,
         textColor: [50, 50, 50]
       },
         headStyles: {
         fillColor: [44, 62, 80],
         textColor: [255, 255, 255],
         fontStyle: 'bold',
         fontSize: 8
       },
         alternateRowStyles: {
         fillColor: [245, 245, 245]
       },
       columnStyles: {
                 
        3: { halign: 'right' }, 
        4: { halign: 'right' }, 
        5: { halign: 'right', fontStyle: 'bold' }, 
        7: { fontStyle: 'normal' }, // Existencia En Suc (Texto multilínea)
        8: { halign: 'right' }, // P. Lista
        9: { halign: 'right' }, // P. Promo
        10: { halign: 'right' }, // Descuento
        11: { halign: 'right' }, // P. Cliente
        12: { halign: 'right' }, // Cant. Cot.
        13: { halign: 'right', fontStyle: 'bold' }, // Importe
        14: { halign: 'center', textColor: [255, 255, 255],             fontStyle: 'bold' }
         },
          
           didParseCell: (data) => {
           if (data.column.index === 3) {
             data.cell.styles.fillColor = [79, 195, 247]; // Azul/Celeste claro
             data.cell.styles.textColor = [255, 255, 255]; // Texto blanco
             data.cell.styles.fontStyle = 'bold';
            }
          if (data.column.index === 6) {
            data.cell.styles.fillColor = [255, 175, 76]; // Naranja/Café
            data.cell.styles.textColor = [255, 255, 255]; // Texto blanco
            data.cell.styles.fontStyle = 'bold';
          }

                  // Columna Acción (Index 14)
         if (data.column.index === 14 && data.cell.raw) {
           const accion = data.cell.raw.toUpperCase();           
         if (accion === 'COMPRAR') {
                          // Verde para "Comprar"
           data.cell.styles.fillColor = [76, 175, 80]; 
           data.cell.styles.textColor = [255, 255, 255];
         } else if (accion === 'SURTIR' || accion !== 'COMPRAR') {
                        
         data.cell.styles.textColor = [255, 255, 255];
            }
           }
          }
        });

        const nombre_pdf = 'analisis_preautorizacion_'+ codigo_presupuesto + '.pdf';
          //pie de pagina
        const pageCount = doc.getNumberOfPages();
        for(let i =1; i <= pageCount; i++){
          doc.setPage(i);
           const disclaimer = "Análisis generado por el sistema Grupo Industrial Frontera Sur. " + "Los resultados son referenciales y pueden contener errores o imprecisiones en la interpretación de datos.";
           doc.setFontSize(8);
           doc.text(disclaimer, 10, doc.internal.pageSize.getHeight() - 10)
          doc.setFontSize(10);
          doc.setTextColor(150);
          doc.text(`Página ${i} de ${pageCount}`, doc.internal.pageSize.getWidth() - 30, doc.internal.pageSize.getHeight() - 10);
        }

        const pdf = 'pdfprueba.pdf';
          //NOS AYUDA A DEPURAR EL PDF PARA NO ESTAR DESCARGANDPO 
        doc.save(nombre_pdf);
          

      });

      let id_product_cotizacion; 
      let nombre_producto;
      //actualizar productos

     document.querySelectorAll('.actualizar_produc').forEach(button => {
      button.addEventListener('click', function(){
      let id_product_cotizacion = $(this).data('id'); 
      let nombre_producto = $(this).data('nombre');
      console.log('El usuario dio click ' + id_product_cotizacion + ' nombre ' + nombre_producto);
      console.log('Este es el id de la cotizacion', id_presupuesto);

      // obtener la fila del botón clickeado
      const fila = this.closest('tr');

      // helper para leer y parsear valores
      const val = (sel, num = false) => {
        const el = fila.querySelector(sel);
        if (!el) return num ? 0 : '';
        const v = el.value?.trim() ?? '';
        return num ? (v === '' ? 0 : parseFloat(v)) : v;
      };

      // recolectar campos del producto desde los inputs de la fila
      let producto = {
        codigo: val('td:nth-child(1) input'),
        producto: val('td:nth-child(2) input'),
        um: val('td:nth-child(3) input'),
        cantidad_cotizada: val('td:nth-child(4) input', true),
        precio_lista: val('td:nth-child(5) input', true),
        precio_promocion: val('td:nth-child(6) input', true),
        descuento: val('td:nth-child(7) input', true),
        precio_cliente: val('td:nth-child(8) input', true),
        importe: val('td:nth-child(9) input', true),
        id_presupuesto: id_presupuesto // se lo mandamos también
      };

      console.log('Producto recolectado:', producto);

      $.ajax({
          url: '/actualizar-producto-pre-autorizacion/' + id_product_cotizacion,
          method: 'GET',
          data: producto,
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(p) {
              console.log("Respuesta del servidor:", p);

              // reconstruir existencias
              let existenciasHTML = '';
              if(p.existencias && Object.keys(p.existencias).length > 0){
                  for(const [sucursal, cant] of Object.entries(p.existencias)){
                      existenciasHTML += `<div><b>Suc ${sucursal}:</b> ${cant}</div>`;
                  }
              } else {
                  existenciasHTML = '<span class="text-muted">Sin existencias</span>';
              }
              

              // actualizar la fila con los nuevos valores procesados
              fila.innerHTML = `
                  <td><input type="text" class="form-control form-control-sm" value="${p.codigo}"></td>
                  <td><input type="text" class="form-control form-control-sm" value="${p.producto}"></td>
                  <td><input type="text" class="form-control form-control-sm" value="${p.um}"></td>
                  <td><input type="number" class="form-control form-control-sm" value="${p.cantidad_cotizada}"></td>
                  <td><input type="number" class="form-control form-control-sm" value="${p.precio_lista}"></td>
                  <td><input type="number" class="form-control form-control-sm" value="${p.precio_promocion}"></td>
                  <td><input type="number" class="form-control form-control-sm" value="${p.descuento}"></td>
                  <td><input type="number" class="form-control form-control-sm" value="${p.precio_cliente}"></td>
                  <td><input type="number" class="form-control form-control-sm" value="${p.importe}"></td>
                  <td>${existenciasHTML}</td>
                  <td>${p.stock_total}</td>
                  <td><span class="badge bg-info">${p.ventas_6m}</span></td>
                  <td>${p.promedio_mensual}</td>
                  <td><span class="badge bg-warning">${p.sugerencia_compra}</span></td>
                  <td><span class="badge ${p.accion === 'Comprar' ? 'bg-danger' : 'bg-success'}">${p.accion}</span></td>
                  <td>
                      <button data-id="${p.id}" data-nombre="${p.producto}" class="actualizar_produc btn btn-success btn-sm" type="button"><i class="fa-solid fa-rotate-right"></i></button>
                      <button data-id="${p.id}" data-nombre="${p.producto}" class="delete_product btn btn-danger btn-sm " type="button"><i class="fas fa-trash-alt"></i></button>
                  </td>
              `;
          },
          error: function(xhr) {
              console.error("Error en la actualización:", xhr.responseText);
          }
        });
      });
    });
    document.querySelectorAll('.delete_product').forEach(button => { button.addEventListener('click', function() { 
          id_product_cotizacion = $(this).data('id'); 
          nombre_producto = $(this).data('nombre'); 
          const confirmacion_produc = document.getElementById('nombre_producto_modal'); 
          console.log('El usuario dio click para borrar el producto: ' + nombre_producto + ' con el id ' + id_product_cotizacion); 
          
        Swal.fire({
          title: '¿Estas seguro de eliminar al producto (' + nombre_producto + ')',
          text: "Esta accion no podra deshacerse despues de dar click en confirmar, ¿Deseas continuar?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Confirmar',
          cancelButtonText: 'Cancelar'
        }).then((result)=>{
          if (result.isConfirmed) {
            eliminarProducto(id_product_cotizacion, nombre_producto, this);
          }  
        });
        function eliminarProducto(id, nombre, boton) {
            $.ajax({
              url: '/eliminar-productos-de-cotizacion/' + id,
              type: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Asegúrate de tener este meta en tu <head>
              },
              success: function(response) {
                Swal.fire({
                  title: 'Producto eliminado',
                  text: response.message,
                  icon: 'success',
                  confirmButtonText: 'Aceptar'
                });

                //  eliminar el elemento del DOM
                $(boton).closest('tr').fadeOut(300, function() {
                  $(this).remove();
                });
              },
              error: function(xhr, status, error) {
                Swal.fire({
                  title: 'Error',
                  text: 'No se pudo eliminar el producto. Intenta nuevamente.',
                  icon: 'error',
                  confirmButtonText: 'Aceptar'
                });
                console.error('Error al eliminar producto:', error);
              }
            });
          }
    
        });
      });
    }

  //****************Cruzando datos *****************************//
    $('#btnCruzarDatos').click(function(){
        Swal.fire({
          title: '¿Estás seguro de autorizar la cotización?',
          text: "Esta acción cruzará los datos para la autorización de tu cotización, y no podrá deshacerse.¿Deseas continuar?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Sí, cruzar datos',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            cruzarDatosAutorization();
          }
        });

      function cruzarDatosAutorization(){
          console.log('El usuario quiere revisar los productos');
          id_presupuesto = $('#storeSelector').val();
          codigo_presupuesto = $('#storeSelector option:selected').text();

          console.log('El usuario dio click en cruzar datos para autorizacion', id_presupuesto + ' codigo ' + codigo_presupuesto);

          const gif_carga = document.getElementById('gif_carga');
          notas.innerHTML = '';
          gif_carga.style.display = 'block';

          //datos recolectados para guardar de la autorizacion
          const guardar_cruzados = datos_pdf;
         
          const productos = [];
          guardar_cruzados.forEach(p => {
            productos.push({
              codigo: p.codigo,
              nombre: p.producto,
              um: p.um,
              precio_lista: p.precio_lista,
              precio_promocion: p.precio_promocion,
              cant_a_pedir: p.cantidad_cotizada,
              existencia_en_sucur: p.existencias,
              stock_total: p.stock_total,
              vendido_anteriormente: p.ventas_6m,
              promedio_mensual_ventas: p.promedio_mensual,
              sugerencia_pedido: p.sugerencia_compra,
              analisi_obtenido: p.accion,
              importe: p.importe,
              
            });
          });

          //enviando 
          console.log('Datos a enviar para cruzar y autorizar', productos);
          $.ajax({
            url:'/cruzar-datos-para-autorizar-cotizacion/' + id_presupuesto,
            method: 'post',
            data: {productos: productos, vigente: 0},
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response){
              console.log('Datos cruzados y autorizados', response);
              swal.fire({
                title: 'Éxito',
                text: response.message,
                icon: 'success',
                confirmButtonText: 'Aceptar'
              });
              location.reload();
            },
            error: function(xhr){
              console.log('Error al cruzar y autorizar', xhr.responseText);
            }
          });
        }
        
    });  
  });
</script>
</main>
@endsection
    