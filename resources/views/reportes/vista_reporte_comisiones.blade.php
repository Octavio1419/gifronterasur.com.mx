@extends('layout.index') 
@section('content')
<main class="main-content">
    <div class="position-relative iq-banner"> 
        <div class="iq-navbar-header dash-hero-ledger" style="height: 215px;">
            <div class="hero-dot-grid"></div>
            <div class="hero-glow"></div>
            <div class="hero-diamond-mark"></div>
            <div class="container-fluid iq-container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="flex-wrap d-flex justify-content-between align-items-center">
                            <div>
                                <span class="eyebrow-ledger"><span class="diamond-bullet"></span> Generar reportes</span>
                                <h1 class="hero-title-ledger">Genera tus reportes de comisiones</h1>
                                <p class="hero-subtitle-ledger" id="nombre_sucursal"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <div class="modal fade" id="ayudaModal" tabindex="-1" aria-labelledby="ayudaModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content ledger-modal">
                    <div class="modal-header ledger-modal-header">
                        <h5 class="modal-title fw-semibold" id="ayudaModalLabel">
                            <div class="diamond-bezel diamond-bezel--sm modal-title-bezel"><i class="fa-solid fa-circle-question"></i></div>
                            Instrucciones
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                <div class="modal-body">
                    Las comisiones se generar de acuerdo a las ventas por vendedor, el sistema te permite generar tus reportes por mes. 
                    <br>
                    <strong>Ejemplo:</strong> Si seleccionas el mes de enero, la fecha de inicio debe ser en el rango de enero , y la fecha final no puede ser mayor al 31 de enero.
                    <br>
                    <strong>Nota:</strong> El reporte se generara en formato PDF, y se descargara automaticamente.
                    <br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
 <div class="conatiner-fluid content-inner mt-n5 py-0">
      <div class="row">                  
         <div class="col-lg-12">
            <div class="ledger-card p-4">

                <div class="d-flex align-items-center mb-4 pb-3 ledger-card-header-line">
                  <div class="diamond-bezel me-3"><i class="fa-solid fa-store"></i></div>
                  <div>
                     <h4 class="mb-0 fw-bold ledger-page-title">Selecciona la sucursal</h4>
                     <small class="ledger-page-subtitle">El reporte se genera solo para la sucursal elegida</small>
                  </div>
               </div>

                <div class="row">
                  <div class="col-md-8">
                     <label class="ledger-field-label mb-2">Selector</label>
                     <select class="form-control" id="selector_tiendas"></select>
                  </div>
                  <div class="col-md-4 d-flex align-items-end">
                     <button class="btn-ledger-primary w-100 justify-content-center mt-3 mt-md-0" type="button" id="buscar_tiendas">
                        <i class="fa-solid fa-magnifying-glass" id="buscarTiendasIcon"></i>
                        <span id="buscarTiendasText">Buscar</span>
                     </button>
                  </div>
               </div>
               <div class="sucursal-selected-pill mt-3" id="sucursalSeleccionadaPill" style="display:none;"></div>
               <div class="mt-4" style="display:none;" id="fechas_rango">
                  <hr class="ledger-hr">
                  <div class="ledger-section-label mb-3">
                     <span class="diamond-bullet diamond-bullet--sm"></span> Selecciona el rango de fecha
                     <a href="#" class="help-icon-ledger" data-bs-toggle="modal" data-bs-target="#ayudaModal" title="Instrucciones">
                        <i class="fa-solid fa-circle-question"></i>
                     </a>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-6">
                        <label for="startDate" class="ledger-field-label">Fecha inicio</label>
                        <input id="startDate" class="form-control" type="date" max="{{ date('Y-m-d') }}" />
                  </div>
                  <div class="col-md-6">
                    <label for="endDate" class="ledger-field-label">Fecha final</label>
                    <input id="endDate" class="form-control" type="date" max="{{ date('Y-m-d') }}" />
                  </div>
                    </div>
                    <small class="field-hint-ledger d-block mt-2">Ambas fechas deben caer dentro del mismo mes.</small>
                    <div class="mt-4">
                        <button class="btn-ledger-primary w-100 justify-content-center" type="button"id="genera_reporte_comisiones">
                            <i class="fa-solid fa-file-pdf" id="generaReporteIcon"></i>
                             <span id="generaReporteText">Generar PDF</span>
                        </button>
                    </div>
                </div>
            </div>
         </div>                                    
      </div>
    </div>
</div>     
</main>    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <img id="logo" src="{{ asset('img/logotipo_herralum.png') }}" style="display:none;">
    <link rel="stylesheet" href="{{ asset('css/moduloreportes/ventascss.css') }}">

    <script>
        $(document).ready(function(){
            selector();
            var ver_nombre_interno = 0;
            var nombre_real = 0;
            function selector(){
                fetch('/sucursales-selector').then(response=>response.json()).then(respuesta=>{
                    let res = respuesta.sucursal;

                    let selectorSucursales = document.getElementById('selector_tiendas');
                    selectorSucursales.innerHTML = '<option value="">Seleccione una sucursal</option>';
                    res.forEach(sucursal => {
                        let option = document.createElement('option');
                        option.value = sucursal.nombre_interno; 
                        option.textContent = sucursal.nombre_real;
                        selectorSucursales.appendChild(option);
                    });
                    $('#selector_tiendas').val('').change();

                });
            }

            $('#buscar_tiendas').click(function(){
                ver_nombre_interno = $('#selector_tiendas').val();
                var nombre_real = $('#selector_tiendas option:selected').text();

                if(ver_nombre_interno){
                    buscandoSucursal = true;
                    $('#buscar_tiendas').prop('disabled', true);
                    $('#buscarTiendasIcon').removeClass('fa-magnifying-glass').addClass('fa-spinner fa-spin');
                    $('#buscarTiendasText').text('Buscando...');

                    /*mostrando el  rango de fechas*/
                    const rango_date = document.getElementById('fechas_rango');
                    rango_date.style.display = 'flex';

                    setTimeout(function(){
                        buscandoSucursal = false;
                        $('#buscar_tiendas').prop('disabled', false);
                        $('#buscarTiendasIcon').removeClass('fa-spinner fa-spin').addClass('fa-magnifying-glass');
                        $('#buscarTiendasText').text('Buscar');

                        const rango_date = document.getElementById('fechas_rango');
                        rango_date.style.display = "block";

                        $('#sucursalSeleccionadaPill').html(`<i class="fa-solid fa-circle-check"></i> Sucursal seleccionada: <strong>${nombre_real}</strong>`).show();
                    }, 200);

                    $('#genera_reporte_comisiones').off('click').click(function(){
                        const fecha_inicio = document.getElementById('startDate').value;
                        const fecha_final = document.getElementById('endDate').value;

                        var fecha_i = fecha_inicio;
                        var fecha_f = fecha_final;

                        if(!fecha_inicio || !fecha_final){
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Por favor, selecciona un rango de fechas.',
                                confirmButtonText: 'Aceptar'
                            });
                            return;
                        }

                        if(fecha_final < fecha_inicio){
                            Swal.fire({
                                icon: 'error',
                                title: 'Rango de fechas inválido',
                                text: 'La fecha final no puede ser menor a la fecha de inicio.',
                                confirmButtonText: 'Aceptar'
                            });
                            return;
                        }

                        console.log('Fecha inicio', fecha_i);
                        console.log('Fecha final', fecha_f);

                        /*enviando solicitud al servidor*/
    fetch('/generar-pdf-por-tienda/' + ver_nombre_interno + '/' + fecha_i + '/' + fecha_f).then(response => response.json()).then(respuesta =>{
        generarPDFComisiones(respuesta);
        console.log(respuesta);
    });

    const $btnGenerar = $('#genera_reporte');
    $btnGenerar.prop('disabled', true);
    $('#generaReporteIcon').removeClass('fa-file-pdf').addClass('fa-spinner fa-spin');
    $('#generaReporteText').text('Generando...');
          
    function generarPDFComisiones(respuesta) {
        let datos = respuesta;
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'landscape' });

        const pageWidth = doc.internal.pageSize.width;
        const pageHeight = doc.internal.pageSize.height;
        const marginX = 15;
        const contentWidth = pageWidth - marginX * 2;

        const azulMarca = [24, 138, 201];   
        const textoOscuro = [40, 44, 52];     
        const gris = [110, 110, 110];
        const grisClaro = [237, 238, 240];
        const grisLinea = [205, 205, 205];
        const negro = [25, 25, 25];
        const blanco = [255, 255, 255];

        const folio = `COM-${fecha_f.replace(/-/g, '')}-${String(Math.floor(Math.random() * 900) + 100)}`;

        const img1 = document.getElementById('logo');
        const canvas1 = document.createElement('canvas');
        canvas1.width = img1.naturalWidth;
        canvas1.height = img1.naturalHeight;
        const ctx1 = canvas1.getContext('2d');
        ctx1.drawImage(img1, 0, 0);
        const imgData1 = canvas1.toDataURL('image/png');
        doc.addImage(imgData1, 'PNG', marginX, 12, 32, 12);

        doc.setFontSize(15);
        doc.setFont(undefined, 'bold');
        doc.setTextColor(...textoOscuro);
        doc.text('REPORTE DE COMISIONES POR VENDEDORES', pageWidth - marginX, 16, { align: 'right' });

        doc.setFontSize(9);
        doc.setFont(undefined, 'normal');
        doc.setTextColor(...gris);
        doc.text(`Folio: ${folio}`, pageWidth - marginX, 22, { align: 'right' });
        doc.text(`Sucursal: ${nombre_real}`, pageWidth - marginX, 27, { align: 'right' });
        doc.text(`Período: ${fecha_i} al ${fecha_f}`, pageWidth - marginX, 32, { align: 'right' });

        doc.setDrawColor(...azulMarca);
        doc.setLineWidth(0.8);
        doc.line(marginX, 38, pageWidth - marginX, 38);

        let suma_total_del_mes = 0;
        let filas_metodo_pago = [];
        datos.metodo_pago.forEach(item => {
            suma_total_del_mes += item.importe_total_por_tipo;
            const nombreMetodo = item.metodo || item.nombre || item.tipo_pago || item.tipo || 'Método';
            const montoFormateado = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(item.importe_total_por_tipo);
            filas_metodo_pago.push([nombreMetodo, montoFormateado]);
        });
        const suma_mes_mx = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(suma_total_del_mes);

        var tabla_comisiones = [];
        let vendedores = datos.vendedor;
        let comisiones_porcentaje;
        let suma_comision = 0;

        const esTeran = (ver_nombre_interno == 'teran');
        const vendedorEspecial = esTeran ? 'ROSY' : 'GABY';
        const porcentajeEspecial = esTeran ? 1.50 : 1.25;

        for (var i = 0; i < vendedores.length; i++) {
            let total_sin_iva, sin_iva;
            if (vendedores[i].vendedor === vendedorEspecial) {
                comisiones_porcentaje = porcentajeEspecial;
                total_sin_iva = suma_total_del_mes;
            } else {
                comisiones_porcentaje = 0.75;
                total_sin_iva = vendedores[i].total_vendedor;
            }
            sin_iva = total_sin_iva / 1.16;

            const sin_iva_formateado = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(sin_iva);
            const resultado_comision = sin_iva * (comisiones_porcentaje / 100);
            suma_comision += resultado_comision;
            const comision_formateada = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(resultado_comision);

            tabla_comisiones.push([
                vendedores[i].vendedor,
                sin_iva_formateado,
                comisiones_porcentaje.toFixed(2) + '%',
                comision_formateada
            ]);
        }

        const total_comisiones_a_pagar = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(suma_comision);

        // ===== RESUMEN =====
        const resumenY = 46;
        doc.autoTable({
            startY: resumenY,
            margin: { left: marginX, right: marginX },
            body: [
                ['Total del mes de venta', suma_mes_mx, 'Total a pagar del mes', total_comisiones_a_pagar],
            ],
            theme: 'plain',
            styles: { fontSize: 10, cellPadding: { top: 2, bottom: 2, left: 0, right: 4 } },
            columnStyles: {
                0: { textColor: gris, cellWidth: 55 },
                1: { textColor: negro, fontStyle: 'bold', cellWidth: 55 },
                2: { textColor: gris, cellWidth: 55 },
                3: { textColor: negro, fontStyle: 'bold', cellWidth: 55 },
            },
        });

        const resumenEndY = doc.lastAutoTable.finalY + 2;
        doc.setDrawColor(...grisLinea);
        doc.setLineWidth(0.2);
        doc.line(marginX, resumenEndY, pageWidth - marginX, resumenEndY);

        tabla_comisiones.push([
            { content: 'TOTAL', styles: { fontStyle: 'bold', halign: 'center' } },
            '',
            '',
            { content: total_comisiones_a_pagar, styles: { fontStyle: 'bold', halign: 'right' } }
        ]);

        // ===== TABLA PRINCIPAL (comisiones) =====
        doc.autoTable({
            startY: resumenEndY + 8,
            margin: { left: marginX, right: marginX },
            head: [['Vendedor', 'Monto sin IVA', 'Comisión', 'A pagar']],
            body: tabla_comisiones,
            theme: 'grid',
            styles: {
                fontSize: 10.5,
                cellPadding: { top: 3.5, bottom: 3.5, left: 3, right: 3 },
                lineWidth: 0.1,
                lineColor: grisLinea,
                textColor: negro,
            },
            headStyles: {
                fillColor: azulMarca,
                halign: 'center',
                textColor: blanco,
                fontStyle: 'bold',
                fontSize: 10.5,
            },
            columnStyles: {
                0: { halign: 'left', cellWidth: 60 },
                1: { halign: 'right', cellWidth: 60 },
                2: { halign: 'center', cellWidth: 40 },
                3: { halign: 'right', cellWidth: 60 },
            },
            didParseCell: function (data) {
                const esFilaTotal = data.row.index === tabla_comisiones.length - 1 && data.section === 'body';
                if (esFilaTotal) data.cell.styles.fillColor = grisClaro;
            },
            didDrawCell: function (data) {
                const esFilaTotal = data.row.index === tabla_comisiones.length - 1 && data.section === 'body';
                if (esFilaTotal) {
                    doc.setDrawColor(...negro);
                    doc.setLineWidth(0.25);
                    doc.line(data.cell.x, data.cell.y - 1.2, data.cell.x + data.cell.width, data.cell.y - 1.2);
                    doc.line(data.cell.x, data.cell.y - 0.4, data.cell.x + data.cell.width, data.cell.y - 0.4);
                }
            },
        });

        const comisionesEndY = doc.lastAutoTable.finalY;

        doc.setFontSize(10);
        doc.setFont(undefined, 'bold');
        doc.setTextColor(...textoOscuro);
        doc.text('Desglose de ventas por método de pago', marginX, comisionesEndY + 12);

        doc.autoTable({
            startY: comisionesEndY + 16,
            margin: { left: marginX, right: marginX + contentWidth / 2 },
            head: [['Método de pago', 'Importe']],
            body: filas_metodo_pago,
            theme: 'grid',
            styles: {
                fontSize: 9.5,
                cellPadding: { top: 2.5, bottom: 2.5, left: 3, right: 3 },
                lineWidth: 0.1,
                lineColor: grisLinea,
                textColor: negro,
            },
            headStyles: {
                fillColor: grisClaro,
                textColor: textoOscuro,
                fontStyle: 'bold',
                halign: 'left',
                fontSize: 9.5,
            },
            columnStyles: {
                0: { halign: 'left' },
                1: { halign: 'right' },
            },
        });

        const desgloseEndY = doc.lastAutoTable.finalY;
        const firmasY = desgloseEndY + 28;

        if (firmasY < pageHeight - 24) {
            const firmaW = 65;
            const gap = (contentWidth - firmaW * 3) / 2;
            const xs = [marginX, marginX + firmaW + gap, marginX + (firmaW + gap) * 2];
            const labels = ['Elaborado por', 'Revisado por', 'Autorizado por'];

            xs.forEach((x, idx) => {
                doc.setDrawColor(...gris);
                doc.setLineWidth(0.3);
                doc.line(x, firmasY, x + firmaW, firmasY);

                doc.setFontSize(9);
                doc.setTextColor(...gris);
                doc.setFont(undefined, 'normal');
                doc.text(labels[idx], x + firmaW / 2, firmasY + 6, { align: 'center' });
            });
        }

        let usuario = respuesta.usuario.nombre;
        let apellido_p = respuesta.usuario.apellido_p;
        const fechaHora = new Date();
        const fechaFormateada = fechaHora.toLocaleDateString('es-MX', { year: 'numeric', month: '2-digit', day: '2-digit' });
        const horaFormateada = fechaHora.toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

        const pageCount = doc.getNumberOfPages();
        for (let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            doc.setDrawColor(...grisLinea);
            doc.setLineWidth(0.2);
            doc.line(marginX, pageHeight - 16, pageWidth - marginX, pageHeight - 16);

            doc.setFontSize(7.5);
            doc.setTextColor(...gris);
            doc.setFont(undefined, 'normal');
            doc.text(`Folio ${folio}  —  Generado por: ${usuario} ${apellido_p}  —  ${fechaFormateada} ${horaFormateada}`, marginX, pageHeight - 10);
            doc.text(`Página ${i} de ${pageCount}`, pageWidth - marginX, pageHeight - 10, { align: 'right' });
        }

        const nombre_archivo = `COMISIONES_${nombre_real}_${fecha_i}_${fecha_f}.pdf`;
        doc.save(nombre_archivo);
        $btnGenerar.prop('disabled', false);
        $('#generaReporteIcon').removeClass('fa-spinner fa-spin').addClass('fa-file-pdf');
        $('#generaReporteText').text('Generar PDF');
        }
    });

    } else {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Por favor, selecciona una sucursal.',
            confirmButtonText: 'Aceptar'
            });
                return;
            }
        });
    });
</script>
@endsection

