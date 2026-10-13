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
            <i class="fa-solid fa-cart-flatbed" style="font-size: 35px"></i>
          </div>
          <div>
            <h4 class="mb-0 fw-bold text-primary">Reportes de inventarios</h4>
            <small class="text-muted">Selecciona la tienda a la cual requieres conocer el concentrado</small>
          </div>
          <div class="ms-auto">
            <span class="badge bg-light text-dark fw-semibold" id="selectedStoreBadge" style="font-size:15px; background-color: rgb(146 255 175) !important;"></span>
          </div>
        </div>

        <!-- Formulario -->
        <form id="uploadForm" class="row g-3" method="POST" enctype="multipart/form-data" action="">
          @csrf
          
          <div class="col-md-6">
            <label for="storeSelector" class="form-label">Seleccionar tienda</label>
            <select id="storeSelector" name="tienda" class="form-select" required>
             
              
            </select>
          </div>

          <div class="col-md-6">
            <label for="selectorReport" class="form-label">Selecciona el reporte </label>
            <select  id="selectorReport" name="selector_reporte" class="form-select">
            
            </select>
          </div>
           <div class="col-md-6">
              <label for="fechaInicio" class="form-label fw-semibold">
                <i class="fa-solid fa-calendar-day me-1 text-secondary"></i>Fecha de inicio
              </label>
              <input type="date" id="fechaInicio" name="fecha_inicio" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label for="fechaFinal" class="form-label fw-semibold">
                <i class="fa-solid fa-calendar-check me-1 text-secondary"></i>Fecha final
              </label>
              <input type="date" id="fechaFinal" name="fecha_final" class="form-control" required>
            </div>
          <div class="col-12">
              <div class="mt-3 d-flex gap-2">
             
              <button id="btnRevisar" type="button" class="btn btn-primary btn-sm mt-3" disabled>
                Generar datos del reporte
              </button>
              <button id="btnExcel" type="button" class="btn btn-light btn-sm mt-3" style="display: none;">
              <i class="fa-solid fa-file-excel" style="color: #26785f;"></i>
              Exportar excel</button>
              <button id="btnPDF" type="button" class="btn btn-light btn-sm mt-3" style="display: none;">
              <i class="fa-solid fa-file-pdf" style="color: #e54848;"></i>
              Exportar PDF</button>
            </div>
            
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const storeSelector = document.getElementById('storeSelector');
        const selectedStoreBadge = document.getElementById('selectedStoreBadge');
        const selectorReporte = document.getElementById('selectorReport');
        var nombre_interno;
        function selectorSucursales(){
            fetch('/sucursales-selector')
            .then(r => r.json())
            .then(respuesta => {
                storeSelector.innerHTML = '<option value=\"\" selected disabled>-- Selecciona la tienda --</option>';
                const mapeoInterno = new Map();
                (respuesta.sucursal || []).forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.id;
                opt.textContent = s.nombre_real;
                storeSelector.appendChild(opt);
                
                mapeoInterno.set(s.id,{
                    nombre_interno: s.nombre_interno
                });
              });

              storeSelector.addEventListener('change', function() {
                  const idSeleccionado = storeSelector.value;
                  const datosSucursal = mapeoInterno.get(Number(idSeleccionado));
                  nombre_interno = datosSucursal.nombre_interno;
                  selectedStoreBadge.textContent = datosSucursal.nombre_interno || '';
              });
                
            });
        }

        function selectorReportes() {
            const reportes = [
                { id: 1, nombre: "Productos no vendidos" },
                { id: 2, nombre: "Familia con mayor costo" },
                { id: 3, nombre: "Productos con poca rotación" },
                { id: 5, nombre: "Mas vendidos por familia" },
                { id: 6, nombre: "Productos sin movimiento" },
            ];

            selectorReporte.innerHTML = '<option value="" selected disabled>-- Selecciona el reporte --</option>';

            reportes.forEach(r => {
                const opt = document.createElement('option');
                opt.value = r.id;
                opt.textContent = r.nombre;
                selectorReporte.appendChild(opt);
            });
        }

        function verificarSelectores() {
            const tieneSucursal = storeSelector.value && storeSelector.value !== "";
            const tieneReporte = selectorReporte.value && selectorReporte.value !== "";
            const tieneInicio = fechaInicio.value !== "";
            const tieneFinal = fechaFinal.value !== "";
            btnRevisar.disabled = !(tieneSucursal && tieneReporte && tieneInicio && tieneFinal);
            
        }

        // Escuchar cambios del selector 
        storeSelector.addEventListener('change', verificarSelectores);
        selectorReporte.addEventListener('change', verificarSelectores);
        fechaInicio.addEventListener('change', verificarSelectores);
        fechaFinal.addEventListener('change', verificarSelectores);

        selectorSucursales(); 
        selectorReportes();

        btnRevisar.addEventListener('click', () => {
            const idSucursal = storeSelector.value;
            const idReporte = selectorReporte.value;
            const inicio = fechaInicio.value;
            const final = fechaFinal.value;
            console.log("Periodo:", inicio, "a", final);
            console.log("Sucursal seleccionada:", idSucursal);
            console.log("Reporte seleccionado:", idReporte);
            console.log("nombre_interno:", nombre_interno);
            var nombre_real = $('#selector_tiendas option:selected').text();
            switch (parseInt(idReporte)) {
                case 1:
                    console.log('Generar reporte de Productos no vendidos');
                    //vamos a enviar la solcitud al servidor para generar el reporte de productos no vendidos, para eso tenemos que enviar nombre_interno, inicio y final, idSucursal
                    fetch('/reporte-productos-no-vendidos/' + nombre_interno + '/' + inicio + '/' + final + '/' + idSucursal).then(response => response.json()).then(respuesta =>{
                      console.log('Respuesta del servidor:', respuesta);
                      //habilitar el boton de descargar PDF y Excel
                      const btnPDF = document.getElementById('btnPDF');
                      const btnExcel = document.getElementById('btnExcel');
                      btnPDF.style.display = 'block';
                      btnExcel.style.display = 'block';

                      btnPDF.addEventListener('click', function(){
                        console.log('El usuario quiere descargar el reporte en PDF');
                      });
                      btnExcel.addEventListener('click', function(){
                        console.log('El usuario quiere descargar el reporte en Excel');
                        //datos guardados
                        const wsData = [];
                        //agregando encabezados
                        /*
                        clave_producto
                        : 
                        "0"
                        color
                        : 
                        ""
                        costo_inventario
                        : 
                        0
                        created_at
                        : 
                        "2025-10-09 13:09:17"
                        descripcion_producto
                        : 
                        "PRODUCTO GENERICO"
                        estado_venta
                        : 
                        "NO SE HA VENDIDO"
                        existencia
                        : 
                        "0.00"
                        id
                        : 
                        40021
                        linea
                        : 
                        ""
                        producto_id
                        : 
                        "1"
                        sub_linea
                        : 
                        ""
                        sucursal_id
                        : 
                        1
                        total_vendido
                        : 
                        0
                        ultimo_costo
                        : 
                        0
                        */
                        wsData.push([
                          "producto_id",
                          "clave_producto",
                          "descripcion_producto",
                          "existencia",
                          "color",
                          "linea",
                          "sub_linea",
                          "linea",
                          "ultimo_costo",
                          "costo_inventario",
                          "total_vendido",
                          "estado_venta",
                        ]);

                        respuesta.data.forEach(item => {
                          wsData.push([
                            item.producto_id,
                            item.clave_producto,
                            item.descripcion_producto,
                            item.existencia,
                            item.color,
                            item.linea,
                            item.sub_linea,
                            item.linea,
                            item.ultimo_costo,
                            item.costo_inventario,
                            item.total_vendido,
                            item.estado_venta,
                          ]);
                        });
                        // Crear libro de trabajo y hoja de cálculo
                        const wb = XLSX.utils.book_new();
                        const ws = XLSX.utils.aoa_to_sheet(wsData);
                        //crear encabezados con autofiltro
                        const range = XLSX.utils.decode_range(ws['!ref']);
                        //PONER NOMBRE DENTRO DE LA HOJA QUE ABARQUE EL TOTAL DE COLUMNAS QUE PONGAMOS FECHA INICIO A FINAL
                      
                        ws['!name'] = `Productos_No_Vendidos_${nombre_interno}_${inicio}_a_${final}`;

                        XLSX.utils.book_append_sheet(wb, ws, "Productos_No_Vendidos");
                        // Exportar a archivo Excel
                        XLSX.writeFile(wb, `Reporte_Productos_No_Vendidos_${nombre_interno}_${inicio}_a_${final}.xlsx`);

                      });
                       
                    }).catch(error => {
                        console.error('Error al generar el reporte:', error);
                    });
                break;
                case 2:
                    console.log('Generar reporte de Familia con mayor costo');
                break;
                case 3:
                 console.log('Generar reporte de Productos con poca rotación');
                 fetch('/analisis-movimiento-productos/' + nombre_interno + '/' + inicio + '/' + final + '/' + idSucursal).then(response => response.json()).then(respuesta =>{
                      console.log('Respuesta del servidor:', respuesta);
                      //habilitar el boton de descargar PDF y Excel
                      const btnPDF = document.getElementById('btnPDF');
                      const btnExcel = document.getElementById('btnExcel');
                      btnPDF.style.display = 'block';
                      btnExcel.style.display = 'block';

                      btnPDF.addEventListener('click', function(){
                        console.log('El usuario quiere descargar el reporte en PDF');
                        let datos_pdf = respuesta.data;
                        // Filtrar, aunque ya lo haces, es buen recordatorio.
                        datos_pdf = datos_pdf.filter(item => item.estado_movimiento === 'Baja rotación');
                        
                        const { jsPDF } = window.jspdf;
                        const doc = new jsPDF({ orientation: 'l', unit: 'mm', format: 'letter' }); // Usar orientación horizontal (l) para más columnas
                        
                        // --- 1. CONFIGURACIÓN DE COLORES Y FUENTES EJECUTIVAS ---
                        const primaryColor = [44, 62, 80]; // Azul Oscuro (casi negro)
                        const secondaryColor = [236, 240, 241]; // Gris claro para filas alternas
                        const warningColor = [255, 204, 0]; // Amarillo para resaltar la Rotación Baja
                        
                        // --- 2. ENCABEZADO Y TÍTULO (Más moderno) ---
                        doc.setFontSize(18);
                        doc.setTextColor(primaryColor[0], primaryColor[1], primaryColor[2]);
                        doc.setFont("helvetica", "bold");
                        doc.text("Análisis de Inventario: Productos de Baja Rotación", 15, 20); // Título alineado a la izquierda
                        
                        // Subtítulo con información del período
                        doc.setFontSize(10);
                        doc.setFont("helvetica", "normal");
                        // Usamos 'nombre_real', 'inicio' y 'final' de tu código original
                        doc.text(`Período del Reporte: ${inicio} al ${final} | Generado por: ${nombre_real}`, 15, 27);
                        
                        // Línea separadora
                        doc.setDrawColor(primaryColor[0], primaryColor[1], primaryColor[2]);
                        doc.line(15, 30, doc.internal.pageSize.getWidth() - 15, 30); // Dibuja una línea horizontal
                        
                        // --- 3. DEFINICIÓN DE COLUMNAS (Header y Data) ---
                        
                        const head = [
                            { header: 'Clave', dataKey: 'producto_id' },
                            { header: 'Descripción', dataKey: 'descripcion_producto' },
                            { header: 'Stock', dataKey: 'existencia' },
                            { header: 'Último Costo', dataKey: 'ultimo_costo' },
                            { header: 'Total Vendido (Uds)', dataKey: 'total_vendido' },
                            { header: 'Prom. Mensual (Uds)', dataKey: 'promedio_mensual' },
                            { header: 'Rotación', dataKey: 'rotacion_mensual' },
                            { header: 'Valor Inventario', dataKey: 'valor_inventario' }
                        ];
                        
                        const body = datos_pdf.map(item => ({
                            producto_id: item.producto_id,
                            descripcion_producto: item.descripcion_producto,
                            existencia: item.existencia,
                            ultimo_costo: parseFloat(item.ultimo_costo).toLocaleString('es-MX', { style: 'currency', currency: 'MXN' }), // Formato de moneda
                            total_vendido: item.total_vendido,
                            promedio_mensual: parseFloat(item.promedio_mensual).toFixed(2),
                            rotacion_mensual: item.estado_movimiento,
                            valor_inventario: parseFloat(item.valor_inventario).toLocaleString('es-MX', { style: 'currency', currency: 'MXN' }) // Formato de moneda
                        }));
                        
                        // --- 4. CREACIÓN DE LA TABLA CON ESTILOS ---
                        doc.autoTable({
                            head: [head.map(c => c.header)], // Solo los títulos
                            body: body.map(row => head.map(c => row[c.dataKey])), // Mapear datos a la estructura
                            startY: 35, // Comienza debajo de la línea separadora
                            theme: 'striped',
                            styles: { 
                                fontSize: 8, 
                                cellPadding: 2,
                                overflow: 'linebreak', // Permite que el texto largo se ajuste
                            },
                            headStyles: { 
                                fillColor: primaryColor, // Encabezado de color oscuro
                                textColor: 255, // Texto blanco
                                fontStyle: 'bold',
                                halign: 'center' 
                            },
                            alternateRowStyles: { 
                                fillColor: secondaryColor 
                            },
                            columnStyles: {
                                // Alineaciones y anchos
                                '0': { cellWidth: 15, halign: 'right' }, // ID Producto
                                '1': { cellWidth: 60, halign: 'left' }, // Descripción
                                '2': { halign: 'right' }, // Stock
                                '3': { halign: 'right' }, // Último Costo (Moneda a la derecha)
                                '4': { halign: 'right' }, // Total Vendido
                                '5': { halign: 'right' }, // Promedio Mensual
                                '6': { halign: 'center', cellWidth: 20 }, // Rotación
                                '7': { halign: 'right', fontStyle: 'bold' }, // Valor Inventario (Resaltar con negrita)
                            },
                            // Estilo de celda para resaltar la rotación
                            didParseCell: function(data) {
                                // La columna 6 es 'Rotación'
                                if (data.column.index === 6 && data.cell.text[0] === 'Baja rotación') {
                                    data.cell.styles.fillColor = warningColor;
                                    data.cell.styles.textColor = [0, 0, 0]; // Texto negro para contraste
                                    data.cell.styles.fontStyle = 'bold';
                                }
                                // La columna 7 es 'Valor Inventario'
                                if (data.column.index === 7) {
                                    data.cell.styles.fontStyle = 'bold';
                                }
                            },
                            // Pie de página (Footer)
                            didDrawPage: function (data) {
                                doc.setFontSize(8);
                                doc.text('Página ' + doc.internal.getNumberOfPages(), data.settings.margin.left, doc.internal.pageSize.getHeight() - 10);
                            }
                        });

                        window.open(doc.output('bloburl'));
                    });
                      btnExcel.addEventListener('click', function(){
                        // Crear libro de trabajo y hoja de cálculo
                        const wb = XLSX.utils.book_new();
                        const ws = XLSX.utils.aoa_to_sheet([]);

                        // Título institucional
                        const titulo = `Reporte de Productos con Poca Rotación\n${nombre_interno} | Del ${inicio} al ${final}`;
                        XLSX.utils.sheet_add_aoa(ws, [[titulo]], { origin: "A1" });

                        // Combinar celdas desde A1 hasta H3 (ajusta según el número de columnas)
                        ws['!merges'] = [
                          { s: { r: 0, c: 0 }, e: { r: 2, c: 7 } } // A1:H3
                        ];

                        // Estilo centrado (requiere librerías adicionales como xlsx-style si quieres aplicar estilos visuales)
                        // Pero puedes usar esta estructura si usas una versión que soporte estilos:
                        ws['A1'].s = {
                          alignment: {
                            horizontal: "center",
                            vertical: "center",
                            wrapText: true
                          },
                          font: {
                            bold: true,
                            sz: 14
                          }
                        };

                        // Agregar encabezados debajo del título (fila 4)
                        XLSX.utils.sheet_add_aoa(ws, [[
                          "producto_id",
                          "descripcion_producto",
                          "existencia",
                          "ultimo_costo",
                          "total_vendido",
                          "promedio_mensual",
                          "rotacion_mensual",
                          "valor_inventario"
                        ]], { origin: "A4" });

                        // Agregar los datos desde la fila 5
                        respuesta.data.forEach((item, index) => {
                          XLSX.utils.sheet_add_aoa(ws, [[
                            item.producto_id,
                            item.descripcion_producto,
                            item.existencia,
                            item.ultimo_costo,
                            item.total_vendido,
                            item.promedio_mensual,
                            item.estado_movimiento,
                            item.valor_inventario
                          ]], { origin: `A${5 + index}` });
                        });

                        // Agregar hoja al libro
                        XLSX.utils.book_append_sheet(wb, ws, "Reporte_Rotacion_De_Productos_");

                        // Exportar
                        XLSX.writeFile(wb, `Reporte_Rotacion_De_Productos_${nombre_interno}_${inicio}_a_${final}.xlsx`);

                      });
                       
                    }).catch(error => {
                        console.error('Error al generar el reporte:', error);
                    });
                break;
                  case 5:
                    console.log('Generar reporte de Mas vendidos por familia');

                    fetch('/productos-mas-vendidos-por-familia/' + nombre_interno + '/' + inicio + '/' + final + '/' + idSucursal)
                        .then(response => response.json())
                        .then(respuesta => {
                            if (!respuesta.success) {
                                console.error('Error del servidor:', respuesta.error);
                                return;
                            }

                            console.log('Respuesta del servidor mas vendidos por familia:', respuesta);
                            
                            const btnPDF = document.getElementById('btnPDF');
                            const btnExcel = document.getElementById('btnExcel');
                            btnPDF.style.display = 'block';
                            btnExcel.style.display = 'block';

                            // Usamos una función nombrada para evitar duplicar eventos si se da click varias veces
                            btnExcel.onclick = function() {
                                console.log('El usuario quiere descargar el reporte en Excel');
                                
                                const wsData = [];
                                
                                // 1. Agregamos un Título General que abarque las columnas
                                wsData.push([`REPORTE DE PRODUCTOS MÁS VENDIDOS POR FAMILIA (${nombre_interno.toUpperCase()})`]);
                                wsData.push([`Periodo: ${inicio} al ${final}`]);
                                wsData.push([]); // Fila vacía separadora

                                // 2. Encabezados de la tabla
                                wsData.push([
                                    "ID Producto",
                                    "Descripción",
                                    "Línea / Familia",
                                    "Inversión Generada (Costo)"
                                ]);

                                // 3. Llenado de datos (Corregido item.linea -> item.producto_linea)
                                respuesta.data.forEach(item => {
                                    wsData.push([
                                        item.producto_id,
                                        item.descripcion_producto,
                                        item.linea, // <-- CAMBIO AQUÍ
                                        parseFloat(item.total_generado)
                                    ]);
                                });

                                // 4. Crear libro y hoja
                                const wb = XLSX.utils.book_new();
                                const ws = XLSX.utils.aoa_to_sheet(wsData);

                                // 5. Configuración de diseño (Mejoras visuales)
                                
                                // Unir celdas para el título (A1 a D1 y A2 a D2)
                                ws['!merges'] = [
                                    { s: { r: 0, c: 0 }, e: { r: 0, c: 3 } }, 
                                    { s: { r: 1, c: 0 }, e: { r: 1, c: 3 } }
                                ];

                                // Auto-ajuste de ancho de columnas
                                const wscols = [
                                    { wch: 15 }, // ID
                                    { wch: 50 }, // Descripción
                                    { wch: 20 }, // Línea
                                    { wch: 25 }  // Total
                                ];
                                ws['!cols'] = wscols;

                                // 6. Exportar
                                XLSX.utils.book_append_sheet(wb, ws, "Reporte");
                                XLSX.writeFile(wb, `Reporte_Mas_Vendidos_${nombre_interno}_${inicio}_${final}.xlsx`);
                            };

                        }).catch(error => {
                            console.error('Error al generar el reporte:', error);
                        });
                  break;
                  
            }

            //alert(`Generando reporte PDF del tipo ${idReporte} para la tienda ${idSucursal}`);
        });
    });
</script>

</main>

@endsection
    