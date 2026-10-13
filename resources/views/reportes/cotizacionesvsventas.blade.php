@extends('layout.index') 
@section('content')
     <main class="main-content">
      <div class="position-relative iq-banner">         
        <!-- Nav Header Component Start -->
        <div class="iq-navbar-header" style="height: 215px;">
            <div class="container-fluid iq-container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="flex-wrap d-flex justify-content-between align-items-center">
                            <div>
                                <h1>Conoce tus ventas reales</h1>
                               
                                <p style="font-size: 20px" id="nombre_sucursal"></p>
                            </div>
                         
                        </div>
                    </div>
                </div>
            </div>
            <div class="iq-header-img">
                <img src="{{ asset('img/dashboard/top-header.png') }}" alt="header" class="theme-color-default-img img-fluid w-100 h-100 animated-scaleX">       
            </div>
        </div>  
             <!-- Modal de ayuda -->
    <div class="modal fade" id="ayudaModal" tabindex="-1" aria-labelledby="ayudaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ayudaModalLabel">Instrucciones</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    El sistema genera un reporte por mes, y la fecha de inicio no debe de ser mayor a la fecha de fin, el sistema mostrara un mensaje de advertencia si no se cumplen los requisitos.
                    <br>
                    <strong>Ejemplo:</strong> Si seleccionas el mes de enero, la fecha de inicio debe ser en el rango de enero , y la fecha final no puede ser mayor al 31 de enero.
                    <br>
                    <strong>Nota:</strong> El reporte se generara en formato PDF, y se descargara automaticamente despues de dar click en el boton (Generer PDF).
                    <br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="container-fluid content-inner mt-n5 py-0">
      <div class="row">                  
         <div class="col-lg-12">
            <div class="card   rounded">
               <div class="card-body">
                  <div class="row">
                     <div class="col-sm-12">    
                        <h4 class="mb-2">Selecciona la sucursal </h4>                              
                        <div class="form-group">
                            <label class="form-label">Selector</label>
                            <select class="form-select mb-3 shadow-none" id="selector_tiendas">
                                
                            </select>
                        </div>
                        <div class="d-grid gap-2 col-6 mx-auto">
                            <button class="btn btn-primary" type="button" id="buscar_tiendas">Buscar</button>
                        </div>
                     </div>
                  </div>
                  <br>
                  <div class="row mt-4" style="display:none;"  id="fechas_rango">
                    <strong>Seleccione el rango de fecha 
                        <a href="#" data-bs-toggle="modal" data-bs-target="#ayudaModal">
                        <i class="fas fa-question-circle"></i>
                        </a>
                        <!-- Ícono de ayuda -->
                    </a></strong>
                    <br>
                    <br>
                    <div class="col-md-6">
                        <label for="startDate"><strong>FECHA INICIO</strong></label>
                        <input id="startDate" class="form-control" type="date" max="<?php echo date('Y-m-d'); ?>" />
                    </div>
                
                    <div class="col-md-6">
                        <label for="endDate"><strong>FECHA FINAL</strong></label>
                        <input id="endDate" class="form-control" type="date" />
                    </div>
                    <br>
                    <br>
                    <br>
                    <br>
                    <div class="d-grid gap-2 col-6 mx-auto">
                        <button class="btn btn-primary" type="button" id="genera_reporte">Generar PDF <i class="fa-solid fa-file-pdf"></i></button>
                    </div>

                </div>
            </div>
         </div>                                    
      </div>
      </div>
        <div id="grafico-container" style="display: none;" >
            <div id="d-main" style="width: 800px; height: 400px;"></div>
        </div>
      </div>
    </main>
   
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <img id="logo" src="{{ asset('img/logotipo_herralum.png') }}" style="display:none;">
    <img id="logo_frontera" src="{{ asset('img/logo_frontera.png') }}" style="display:none;">

<script>
$(document).ready(function() {

    // -------------------------------------------
    // Cargar Sucursales (Se mantiene igual)
    // -------------------------------------------
    function cargarSucursales() {
        fetch('/sucursales-selector')
        .then(response => response.json())
        .then(respuesta => {

            let selectorSuc = document.getElementById('selector_tiendas');
            selectorSuc.innerHTML = '<option value="">Selecciona la sucursal</option>';

            let resp = respuesta.sucursal;
            resp.forEach(sucursal => {
                let option = document.createElement('option');
                option.setAttribute('data-id', sucursal.id);
                option.value = sucursal.nombre_interno;
                option.textContent = sucursal.nombre_real;
                selectorSuc.appendChild(option);
            });

            $('#selector_tiendas').val('').change();
        })
        .catch(error => {
            console.error('Error al cargar las sucursales:', error);
        });
    }

    cargarSucursales();
    
    $('#buscar_tiendas').click(function() {
        var id = $('#selector_tiendas option:selected').data('id');
        var nombre_real_sucursal = $('#selector_tiendas option:selected').text();

        console.log('Sucursal seleccionada ID:', id);

        $('#nombre_sucursal').text('Sucursal seleccionada: ' + nombre_real_sucursal);
        $('#fechas_rango').show();
    });

    $('#genera_reporte').click(function() {
    var nombre_interno_sucursal = $('#selector_tiendas').val();
    var nombre_real_sucursal = $('#selector_tiendas option:selected').text();
    var id = $('#selector_tiendas option:selected').data('id');
    var startDate = $('#startDate').val();
    var endDate = $('#endDate').val();

    if (!startDate || !endDate) {
        alert('Por favor, seleccione ambas fechas.');
        return;
    }

    fetch(`/generar-analisis-cotizaciones-vs-ventas/${id}/${nombre_interno_sucursal}/${startDate}/${endDate}`)
    .then(response => response.json())
    .then(data => {
        // Llamamos a la función con el objeto 'data' que devuelve tu controlador
        reportePDF(data, nombre_real_sucursal);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Hubo un error al procesar el reporte.');
    });
});

function reportePDF(data, nombre_real_sucursal) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({ orientation: 'l', unit: 'mm', format: 'a4' });
    
    const moneda = (v) => {
        let n = parseFloat(v);
        return isNaN(n) ? "$ 0.00" : `$ ${n.toLocaleString('es-MX', {minimumFractionDigits: 2})}`;
    };

    let y = 20;

    // --- ENCABEZADO ---
    doc.setFontSize(14).setFont(undefined, 'bold');
    doc.text('ANÁLISIS FINANCIERO: CAPITAL COTIZADO VS RECUPERADO', 148, y, { align: 'center' });
    y += 8;
    doc.setFontSize(10).setFont(undefined, 'normal');
    doc.text(`Sucursal: ${nombre_real_sucursal} | Periodo: ${data.periodo.inicio} al ${data.periodo.fin}`, 148, y, { align: 'center' });
    y += 10;

    // --- RESUMEN DE CAPITAL ---
    
    doc.autoTable({
        startY: y,
        head: [['Categoría de Productos', 'Inversión (Compra)', 'Ingreso (Venta Real)', 'Estatus']],
        body: [
            ['1. Desplazamiento Total (O Stock Negativo)', moneda(data.totales.compra_exitosos), moneda(data.totales.venta_exitosos), 'RECUPERADO'],
            ['2. Venta Parcial', moneda(data.totales.compra_parciales), moneda(data.totales.venta_parciales), 'EN RIESGO'],
            ['3. Sin Venta', moneda(data.totales.compra_almacenado), '$ 0.00', 'ATRAPADO'],
            ['TOTALES', moneda(data.totales.compra_total), moneda(data.totales.venta_total_real), 'CUADRE GLOBAL']
        ],
        theme: 'grid',
        headStyles: { fillColor: [44, 62, 80] },
        columnStyles: { 1: { halign: 'right' }, 2: { halign: 'right' }, 3: { fontStyle: 'bold' } }
    });

    y = doc.lastAutoTable.finalY + 12;

    // --- FUNCIÓN DE TABLAS ---
    const crearTabla = (titulo, productos, color, totalC, totalV) => {
        if (!productos || productos.length === 0) return;

        if (y > 170) { doc.addPage(); y = 20; }

        doc.setFontSize(11).setFont(undefined, 'bold').text(String(titulo), 10, y);
        y += 4;

        const body = productos.map(p => [
            p.codigo, p.nombre, p.um, p.comprado_u, p.vendido_u, p.rendimiento + '%',
            moneda(p.importe_compra), moneda(p.importe_venta), p.stock_actual
        ]);

        body.push([
            { content: 'TOTALES DEL GRUPO', colSpan: 6, styles: { halign: 'right', fontStyle: 'bold' } },
            { content: moneda(totalC), styles: { fontStyle: 'bold' } },
            { content: moneda(totalV), styles: { fontStyle: 'bold' } },
            ''
        ]);

        doc.autoTable({
            startY: y,
            head: [['Código', 'Producto', 'U.M', 'Cant. C', 'Cant. V', 'Rend %', 'Total Compra', 'Total Venta', 'Stock']],
            body: body,
            theme: 'striped',
            headStyles: { fillColor: color, fontSize: 8 },
            styles: { fontSize: 7 },
            columnStyles: { 6: { halign: 'right' }, 7: { halign: 'right' }, 8: { halign: 'center' } },
            didParseCell: function(d) {
                // Si el stock es negativo, pintar la celda de rojo para alertar desorden administrativo
                if (d.column.index === 8 && parseFloat(d.cell.raw) < 0) {
                    d.cell.styles.textColor = [255, 0, 0];
                    d.cell.styles.fontStyle = 'bold';
                }
            }
        });
        y = doc.lastAutoTable.finalY + 12;
    };

    crearTabla("1. PRODUCTOS CON DESPLAZAMIENTO TOTAL / STOCK NEGATIVO", data.data.exitosos, [39, 174, 96], data.totales.compra_exitosos, data.totales.venta_exitosos);
    crearTabla("2. PRODUCTOS CON VENTA PARCIAL", data.data.parciales, [211, 84, 0], data.totales.compra_parciales, data.totales.venta_parciales);
    crearTabla("3. PRODUCTOS SIN VENTA (ALMACENADO)", data.data.sin_venta, [192, 57, 43], data.totales.compra_almacenado, 0);

    window.open(doc.output('bloburl'), '_blank');
}
    
});
</script>
@endsection