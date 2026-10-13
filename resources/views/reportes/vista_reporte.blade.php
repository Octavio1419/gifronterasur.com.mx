@extends('layout.index') <!-- Extiende del layout base -->
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
                                <h1 class="hero-title-ledger">Genera tus reportes de venta</h1>
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
                        <ul class="help-list-ledger">
                            <li>El sistema genera <strong>un reporte por mes</strong>. La fecha de inicio no puede ser mayor a la fecha de fin.</li>
                            <li>Si no se cumplen los requisitos, el sistema mostrará un mensaje de advertencia.</li>
                            <li><strong>Ejemplo:</strong> si seleccionas enero, la fecha de inicio debe estar dentro de enero y la fecha final no puede pasar del 31 de enero.</li>
                            <li><strong>Nota:</strong> el reporte se genera en formato PDF y se descarga automáticamente al dar clic en "Generar PDF".</li>
                        </ul>
                    </div>
                    <div class="modal-footer ledger-modal-footer">
                        <button type="button" class="btn-ledger-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
      </div>

    <div class="container-fluid content-inner mt-n5 py-0">
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
                     <button class="btn-ledger-primary w-100 justify-content-center" type="button" id="genera_reporte">
                        <i class="fa-solid fa-file-pdf" id="generaReporteIcon"></i>
                        <span id="generaReporteText">Generar PDF</span>
                     </button>
                  </div>
              </div>

            </div>
         </div>
      </div>
    </div>

        <div id="grafico-container" style="display: none;" >
            <div id="d-main" style="width: 800px; height: 400px;"></div>
        </div>
        <div style="position: absolute; left: -9999px;">
            <canvas id="canvasGrafica" width="400" height="280"></canvas>
        </div>
      </div>
</main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
     <link rel="stylesheet" href="{{ asset('css/moduloreportes/ventascss.css') }}">

    <img id="logo" src="{{ asset('img/logotipo_herralum.png') }}" style="display:none;">
    <img id="logo_frontera" src="{{ asset('img/logo_frontera.png') }}" style="display:none;">

    <script>
        $(document).ready(function() {
            selector();
            var ver_nombre_interno = 0;
            var id_sucursal;

            function selector(){
                fetch('/sucursales-selector').then(response=>response.json()).then(respuesta=>{
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
                });
            }

            // Bandera de estado: evita doble envío al buscar sucursal
            let buscandoSucursal = false;

            $('#buscar_tiendas').click(function(){
                if (buscandoSucursal) return;

                ver_nombre_interno = $('#selector_tiendas').val();
                var nombre_real = $('#selector_tiendas option:selected').text();
                id_sucursal = $('#selector_tiendas option:selected').data('id');

                if(ver_nombre_interno){
                    buscandoSucursal = true;
                    $('#buscar_tiendas').prop('disabled', true);
                    $('#buscarTiendasIcon').removeClass('fa-magnifying-glass').addClass('fa-spinner fa-spin');
                    $('#buscarTiendasText').text('Buscando...');

                    // Sin llamada real al servidor en este paso (la sucursal ya viene
                    // cargada en el selector), pero se deja el estado de carga por
                    // consistencia y para casos donde a futuro sí se agregue una petición.
                    setTimeout(() => {
                        buscandoSucursal = false;
                        $('#buscar_tiendas').prop('disabled', false);
                        $('#buscarTiendasIcon').removeClass('fa-spinner fa-spin').addClass('fa-magnifying-glass');
                        $('#buscarTiendasText').text('Buscar');

                        const rango_date = document.getElementById('fechas_rango');
                        rango_date.style.display = "block";

                        $('#sucursalSeleccionadaPill').html(`<i class="fa-solid fa-circle-check"></i> Sucursal seleccionada: <strong>${nombre_real}</strong>`).show();
                    }, 200);

                    $('#genera_reporte').off('click').click(function(){
                        const fecha_inicio = document.getElementById('startDate').value;
                        const fecha_final = document.getElementById('endDate').value;
                        var fecha_i = fecha_inicio;
                        var fecha_f = fecha_final;

                        if (!fecha_inicio || !fecha_final) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Fechas requeridas',
                                text: 'Seleccione ambas fechas antes de continuar.',
                                confirmButtonText: 'Aceptar'
                            });
                            return;
                        }

                        if (fecha_final < fecha_inicio) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Rango invalido',
                                text: 'La fecha final no puede ser menor a la fecha de inicio.',
                                confirmButtonText: 'Aceptar'
                            });
                            return;
                        }
                        // Validar que ambas fechas esten en el mismo mes y año
                        const [anioInicio, mesInicio] = fecha_inicio.split('-');
                        const [anioFinal, mesFinal] = fecha_final.split('-');

                        if (mesInicio !== mesFinal || anioInicio !== anioFinal) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Mes distinto',
                                text: 'Los reportes solo pueden generarse por mes.',
                                confirmButtonText: 'Aceptar'
                            });
                            return;
                        }
                        /*enviando al solicituda al servidor */
                        fetch('/generar-pdf-por-tienda/' + ver_nombre_interno + '/' + fecha_i + '/' + fecha_f).then(response => response.json()).then(respuesta =>{
                            generaPDF(respuesta);
                        });

                        const $btnGenerar = $('#genera_reporte');
                        $btnGenerar.prop('disabled', true);
                        $('#generaReporteIcon').removeClass('fa-file-pdf').addClass('fa-spinner fa-spin');
                        $('#generaReporteText').text('Generando...');

                        /* enviando la solicitud al servidor */
                        function generaPDF(respuesta){
                            let datos = respuesta;
                            const { jsPDF } = window.jspdf;
                            const doc = new jsPDF({orientation: 'p' });
                            
                            const img1 = document.getElementById('logo'); // Derecha
                            const img2 = document.getElementById('logo_frontera'); // Izquierda

                            const canvas1 = document.createElement('canvas');
                            canvas1.width = img1.naturalWidth;
                            canvas1.height = img1.naturalHeight;
                            const ctx1 = canvas1.getContext('2d');
                            ctx1.drawImage(img1, 0, 0);
                            const imgData1 = canvas1.toDataURL('image/png');

                            doc.addImage(imgData1, 'PNG', 160, 10, 40, 15);  

                            doc.setFontSize(16);
                            doc.setTextColor(0, 0, 0); 
                            doc.setFont("helvetica", "bold"); 
                            doc.text(nombre_real.toUpperCase(), 105, 20, { align: 'center' });

                            doc.setDrawColor(200, 200, 200); 
                            doc.line(10, 30, 200, 30); 
                            var fecha_i = fecha_inicio; 
                            var fecha_f = fecha_final;

                            let fechaActual = fecha_i;
                            let anio = fechaActual.split('-')[0]; 
                            let mes = parseInt(fechaActual.split('-')[1], 10); 
                            let dia = parseInt(fecha_f.split('-')[2], 10);
                            mes = mes - 1; 

                            let diasHabiles = 0;
                    
                            let fecha_habiles = new Date(anio, mes, 1);

                            while (fecha_habiles.getMonth() === mes) {
                                let diaSemana = fecha_habiles.getDay();

                                if (diaSemana >= 1 && diaSemana <= 6) {
                                    diasHabiles++;
                                }
                                fecha_habiles.setDate(fecha_habiles.getDate() + 1);
                            }

                            doc.autoTable({
                                head: [['Fecha Inicio', 'Fecha Final']], // Encabezado de la tabla
                                body: [
                                    [fecha_i, fecha_f], 
                                ],
                                startY: 40,
                                startX: 10, // Mueve la tabla más a la izquierda
                                theme: 'grid',
                                styles: {  
                                    fontSize: 12, 
                                    cellPadding: .5, 
                                    lineWidth: .1, 
                                    lineColor: [0, 0, 0] 
                                },
                                headStyles: { fillColor: [41, 128, 185], halign: 'center' }, // Centra el texto del encabezado
                                bodyStyles: { halign: 'center',textColor: [30, 30, 30]  }, 
                                columnStyles: {
                                    0: { cellWidth: 40, halign: 'center' }, 
                                    1: { cellWidth: 40, halign: 'center' }  
                                }
                            });

                            let suma_total_por_metodo = 0;
                            let metodo_pago = datos.metodo_pago;
                            console.log('Este recupera el meotod de pago',metodo_pago);

                            metodo_pago.forEach(item =>{
                                suma_total_por_metodo += item.importe_total_por_tipo;
                            });
                                 
                           
                            let total_descuento = 0;
                            let descuen = respuesta.descuentosAplicados;
                            descuen.forEach(item =>{
                                total_descuento += item.descuento_aplicado;
                            });
                            
                            let iva_des = total_descuento * 0.16;
                            let total_con_iva_des = total_descuento + iva_des;
                        
                            const mx_total_descuento = new Intl.NumberFormat('es-MX', {
                                style: 'currency',
                                currency: 'MXN',
                            }).format(total_con_iva_des);

                            // ver cual % alcanzo de descuento con la venta total
                            let porcentaje_descuento = (total_descuento / suma_total_por_metodo) * 100;
                            //convertir a %
                            let porcentaje_descuento_final = porcentaje_descuento.toFixed(2) + "%";
                            doc.autoTable({
                                head: [
                                    [
                                        { content: 'Total descuento:', styles: { fillColor: [41, 128, 185], textColor: [255, 255, 255], fontStyle: 'bold', halign: 'center' } }, 
                                        { content: mx_total_descuento, styles: { fillColor: [255, 255, 255], textColor: [0, 0, 0], halign: 'center', borderWidth: 1 } }
                                    ]
                                ],
                                body: [
                                    [
                                        { content: '% total de venta', styles: { fillColor: [41, 128, 185], textColor: [255, 255, 255], fontStyle: 'bold', halign: 'center' } }, 
                                        { content: porcentaje_descuento_final, styles: { fillColor: [255, 255, 255], textColor: [0, 0, 0], halign: 'center', borderWidth: .5 } }
                                    ]
                                ],
                                startY: 40,
                                margin: { left: 120 }, 
                                theme: 'grid',
                                styles: {  
                                    fontSize: 12,
                                    cellPadding: .5, 
                                    lineWidth: .1, 
                                    lineColor: [0, 0, 0] 
                                },
                                columnStyles: {
                                    0: { cellWidth: 40, halign: 'center' }, // Ajusta el ancho de la primera columna
                                    1: { cellWidth: 40, halign: 'center' }
                                }
                            });

                            /*conviertiendo el total pa pesos*/
                            const pesos_mx = new Intl.NumberFormat('es-MX', {
                                style: 'currency',
                                currency: 'MXN',
                            }).format(suma_total_por_metodo);
                            console.log('Este es el total en pesos', pesos_mx);
                            
                            /*creando tabla historico*/
                            doc.autoTable({
                                head: [['Historico', '$']], 
                                body: [
                                    ['Total', pesos_mx], 
                                ],
                                startY: 60, // cambia la posicion altura
                                startX: 10, // mover 10 a la izquierda
                                theme: 'grid',
                                styles: {  
                                    fontSize: 12, 
                                    lineWidth: .1, 
                                    lineColor: [0, 0, 0] 
                                },
                                headStyles: { fillColor: [41, 128, 185], halign: 'center' }, // Centra el texto del encabezado
                                bodyStyles: { halign: 'center' ,textColor: [30, 30, 30] }, 
                                columnStyles: {
                                    0: { cellWidth: 40, halign: 'center' },
                                    1: { cellWidth: 40, halign: 'center' } 
                                }
                            });
                            const meta_al_mes = datos.meta_tiendas?.[0]?.maximo_ventas ?? 0;
                            const sacando_meta_al_dia = meta_al_mes / diasHabiles;

                            const formato_moneda_mx = new Intl.NumberFormat('es-MX', {
                                style: 'currency',
                                currency: 'MXN',
                            });

                            let acumulado_faltante = 0;
                            let resumen_metas = [];

                            datos.ventashabiles.forEach((dia) => {
                                let fecha = dia.fecha;
                                let vendido = dia.total_vendido;

                                let meta_ajustada = sacando_meta_al_dia + acumulado_faltante;

                                // Diferencia entre meta ajustada y lo vendido
                                let diferencia = meta_ajustada - vendido;

                                if (diferencia > 0) {
                                    acumulado_faltante = diferencia;
                                } else {
                                    acumulado_faltante = 0;
                                }

                                resumen_metas.push({
                                    fecha: fecha,
                                    vendido: formato_moneda_mx.format(vendido),
                                    meta_diaria: formato_moneda_mx.format(sacando_meta_al_dia),
                                    meta_ajustada: formato_moneda_mx.format(meta_ajustada),
                                    faltante_dia: diferencia > 0 ? formato_moneda_mx.format(diferencia) : 'Cumplida',
                                    acumulado_pendiente: formato_moneda_mx.format(acumulado_faltante),
                                    acumulado_sin_mx : acumulado_faltante
                                });
                            });

                            /*CALCULANDO LA META A VENDE DEL DIA DE HOY*/
                            const meta_al_dia_mx = new Intl.NumberFormat('es-MX', {
                                style: 'currency',
                                currency: 'MXN',
                            }).format(sacando_meta_al_dia);
                            const obtener_ultim_fecha = fecha_f;
                            
                            let vender_hoy;
                            console.table(resumen_metas);

                            const ultimoDia = resumen_metas[resumen_metas.length - 1];
                            console.log('Acumulado pendiente del último día:', ultimoDia.acumulado_sin_mx);

                            const acum = ultimoDia.acumulado_pendiente;

                            let vende_hoy_mx;

                        const acumulado_total = datos.ventashabiles.reduce((total, dia) => total + dia.total_vendido, 0);
                            if (acumulado_total >= meta_al_mes) {
                                vender_hoy = 0;
                                vende_hoy_mx = formato_moneda_mx.format(vender_hoy);
                            } else if (ultimoDia.faltante_dia === 'Cumplida') {
                                vender_hoy = sacando_meta_al_dia;
                                vende_hoy_mx = formato_moneda_mx.format(vender_hoy);
                            } else {
                                vender_hoy = ultimoDia.acumulado_sin_mx + sacando_meta_al_dia;
                                vende_hoy_mx = formato_moneda_mx.format(vender_hoy);
                            }
                            let tableAvenderHoy = [];

                            console.log('Vender hoy:', vender_hoy);
                               doc.autoTable({
                                    startY: 80, 
                                    startX: 10, 
                                    head: [['TU META A VENDER HOY ES:']],
                                    theme: 'grid',
                                    styles: {
                                        fontSize: 12,
                                        fontStyle: 'bold',
                                        fillColor: [121, 228, 252],
                                        textColor: [255, 255, 255], 
                                        halign: 'center',
                                        lineWidth: 0.1,
                                    lineColor: [0, 0, 0],
                                        cellPadding: 0.5,
                                        
                                    },
                                    columnStyles: {
                                        0: { cellWidth: 'auto' }
                                    }
                            });

                    tableAvenderHoy.push([meta_al_dia_mx, obtener_ultim_fecha, acum ,vende_hoy_mx]);
      
                        let body = [];

                        let diferencia_meta_if = meta_al_mes - acumulado_total;

                        if (acumulado_total >= meta_al_mes) {
                          
                            body.push([{
                                content: 'METAS AUN NO DEFINIDAS PARA ESTE MES',
                                colSpan: 4,
                                styles: {
                                    halign: 'center',
                                    fontStyle: 'bold',
                                    textColor: [0, 0, 0],
                                    fillColor: [255, 255, 255],
                                }
                            }]);
                        } else if (diferencia_meta_if <= 55173.53) {
                      
                            const faltante_mx = new Intl.NumberFormat('es-MX', {
                                style: 'currency',
                                currency: 'MXN'
                            }).format(diferencia_meta_if);

                            body.push([{
                                content: `Meta del mes no cumplida. Acumulado al corte ${faltante_mx}. ¡Sigue esforzándote!`,
                                colSpan: 4,
                                styles: {
                                    halign: 'center',
                                    fontStyle: 'bold',
                                    textColor: [0, 0, 0], // naranja oscuro
                                    fillColor: [255, 255, 255], 
                                }
                            }]);
                        } else {
                            body.push([
                                meta_al_dia_mx,
                                obtener_ultim_fecha,
                                acum,
                                vende_hoy_mx
                            ]);
                        }
                        doc.autoTable({
                                head: [['Meta diaria', 'Fecha', 'Acumulado', 'Meta del día']],
                                body: body,
                                startY: 86,
                                startX: 10,
                                theme: 'grid',
                                styles: {
                                    fontSize: 12,
                                    cellPadding: 0.5,
                                    lineWidth: 0.1,
                                    lineColor: [0, 0, 0]
                            },
                            headStyles: { fillColor: [121, 228, 252], halign: 'center' },
                            bodyStyles: { halign: 'center', textColor: [30, 30, 30] },
                            columnStyles: {
                                0: { cellWidth: 40 },
                                1: { cellWidth: 40 }
                            }
                        });
                        
                        const fecha_meta = datos.meta_tiendas[0]?.fecha.data_meta ?? 0;
                        const fecha = new Date(fecha_meta + "T00:00:00"); 
                        const numeroMes = fecha.getUTCMonth(); 
                           
                        const nombresMeses = [
                                "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                                "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
                        ];

                        const nombreMes = nombresMeses[numeroMes];
 
                        const mx_meta_al_mes = new Intl.NumberFormat('es-MX', {
                            style: 'currency',
                            currency: 'MXN',
                        }).format(meta_al_mes);

                        if (!fecha_meta) {
                            console.log("Fecha de meta no disponible");
                        }

                        doc.autoTable({
                            head: [['Mes', 'Meta alcanzar']], 
                                body: [
                                    [ nombreMes, mx_meta_al_mes], 
                                ],
                                startY: 60, // cambia la posicion altura
                                margin: { left: 120 },
                                theme: 'grid',
                                styles: {  
                                    fontSize: 12, 
                                    cellPadding: .5, 
                                    lineWidth: .1, 
                                    lineColor: [0, 0, 0] 
                                },
                                headStyles: { fillColor: [41, 128, 185], halign: 'center' }, // Centra el texto del encabezado
                                bodyStyles: { halign: 'center',textColor: [30, 30, 30]  }, // Centra el texto en el cuerpo de la tabla
                                columnStyles: {
                                    0: { cellWidth: 40, halign: 'center' }, // Centra el contenido de la primera columna
                                    1: { cellWidth: 40, halign: 'center' }  // Centra el contenido de la segunda columna
                                }
                            });
                            
                            let apartados = datos.apartados;
                            console.log('Este es el apartado', apartados);
    
                            const uniendo_lista_metodo = apartados.map(item => ({
                                tipo_pago: item.forma_pago,
                                importe_total_por_tipo: item.importe_total
                            }));

                            console.log('aca cambiamos el nombre de la propiedad', uniendo_lista_metodo);

                            /*uniendo las listas*/
                            const forma_de_pagos = [...metodo_pago, ...uniendo_lista_metodo];
                            console.log('Este es el metodo de pago con las listas unidas', forma_de_pagos);

                            var tablaMetodos = [];

                            let totalImporte = forma_de_pagos.reduce((sum, pagos) => sum + pagos.importe_total_por_tipo, 0); // Sumar el total

                            forma_de_pagos.forEach((pagos) => {
                                const mx_importe = new Intl.NumberFormat('es-MX', {
                                    style: 'currency',
                                    currency: 'MXN',
                                }).format(pagos.importe_total_por_tipo);

                                let porcentajeCalculado = (pagos.importe_total_por_tipo / totalImporte) * 100; // Calculando %
                                
                                let datosTabla = [pagos.tipo_pago, mx_importe, porcentajeCalculado.toFixed(2) + "%"];
                                tablaMetodos.push(datosTabla);
                            });

                            // Agregar fila TOTAL con porcentaje 100%
                            const mx_totalImporte = new Intl.NumberFormat('es-MX', {
                                style: 'currency',
                                currency: 'MXN',
                            }).format(totalImporte);

                            tablaMetodos.push(["TOTAL", mx_totalImporte, "100%"]); // Última fila con total y 100%

                            doc.autoTable({
                                head: [["Método de pago", "Monto", "%"]],
                                body: tablaMetodos,
                                startY: 105,
                                startX: 10,
                                theme: "grid",
                                styles: {  
                                    fontSize: 12,
                                    cellPadding: 0.5,
                                    lineWidth: 0.1,
                                    lineColor: [0, 0, 0],
                                    
                                },
                                headStyles: { fillColor: [41, 128, 185], halign: "center" },
                                bodyStyles: { halign: "center",textColor: [30, 30, 30] },
                                columnStyles: {
                                    0: { cellWidth: 40, halign: "center" },
                                    1: { cellWidth: 40, halign: "center" },
                                    2: { cellWidth: 40, halign: "center" }
                                }
                            });
                            
                            var tablePorcentajes = [];
                            const acumulado = ((suma_total_por_metodo / meta_al_mes) * 100).toFixed(2) + "%"; // Calculando 

                            console.log('Este es el acumulado', acumulado);

                           
                           const acumulado_numero = parseFloat(acumulado);
                            let diferencia = ((meta_al_mes - suma_total_por_metodo) / meta_al_mes) * 100;
                             let por_mes = (diferencia >= 0 ? -Math.abs(diferencia) : Math.abs(diferencia)).toFixed(2) + "%";

                            if (acumulado_numero > 100) {
                                console.log("El acumulado es mayor a 100%");
                                por_mes = "+" + por_mes;
                            } else {
                               
                               por_mes = por_mes;
                                console.log("No es mayor a 100%, diferencia:", por_mes);
                            }
                        
                            let diferencia_meta = (meta_al_mes -suma_total_por_metodo);

                            let porCubrir = (diferencia_meta >= 0 ? -Math.abs(diferencia_meta) : Math.abs(diferencia_meta)).toFixed(2); // Calculando por cubrir

                            let mx_porcubrir = new Intl.NumberFormat('es-MX', {
                                style: 'currency',
                                currency: 'MXN',
                            }).format(porCubrir);

                            if (suma_total_por_metodo > meta_al_mes) {
                                
                                mx_porcubrir = "+" + mx_porcubrir;

                            } else {

                                mx_porcubrir = mx_porcubrir;
                                
                            }
                            
                            tablePorcentajes.push([pesos_mx, acumulado, por_mes, mx_porcubrir]);

                          
                           let startYRemHistorico= doc.lastAutoTable.finalY + 10; 
                            
                            doc.autoTable({
                                    startY: startYRemHistorico, // o donde necesites antes del contenido real
                                    head: [['HISTORICO DE CUMPLIMIENTO']],
                                    theme: 'grid',
                                    styles: {
                                        fontSize: 12,
                                        fontStyle: 'bold',
                                        fillColor: [41, 128, 185], // Azul
                                        textColor: [255, 255, 255], // Blanco
                                        halign: 'center',
                                        lineWidth: 0.1,
                                    lineColor: [0, 0, 0],
                                        cellPadding: 0.5,
                                        
                                    },
                                    columnStyles: {
                                        0: { cellWidth: 'auto' }
                                    }
                            });

                            let startYSaldo = doc.lastAutoTable.finalY ;
                            doc.autoTable({
                                head: [["Acumulado", "% Cubierto", "% Mes", "Por cubrir"]],
                                body:  tablePorcentajes,
                                startY: startYSaldo,
                                startX: 10,
                                theme: "grid",
                                styles: {  
                                    fontSize: 12,
                                    cellPadding: 0.5,
                                    lineWidth: 0.1,
                                    lineColor: [0, 0, 0],
                                    
                                },
                                headStyles: { fillColor: [41, 128, 185], halign: "center" },
                                bodyStyles: { halign: "center",textColor: [30, 30, 30] },
                                columnStyles: {
                                    0: { cellWidth: 40, halign: "center" },
                                    1: { cellWidth: 40, halign: "center" },
                                    2: { cellWidth: 40, halign: "center" }
                                }
                            });

                         var tablaVendedores = [];
                         let vendedores = datos.vendedor;

                            let total_vendedores = 0;
                            total_vendedores = vendedores.reduce((sum, pagos) => sum + pagos.total_final_vendedor, 0); // Sumar el total
                            

                            vendedores.forEach((pagos) => {
                                const mx_importe_vendedor = new Intl.NumberFormat('es-MX', {
                                    style: 'currency',
                                    currency: 'MXN',
                                }).format(pagos.total_final_vendedor);

                                let porcentajeCalculadoVend = (pagos.total_final_vendedor / totalImporte) * 100; // Calculando %
                                
                                let datosVendedor = [pagos.vendedor, mx_importe_vendedor, porcentajeCalculadoVend.toFixed(2) + "%"];
                                tablaVendedores.push(datosVendedor);
                            });

                            const mx_total_vendedor = new Intl.NumberFormat('es-MX', {
                                style: 'currency',
                                currency: 'MXN',
                            }).format(total_vendedores);

                            tablaVendedores.push(["TOTAL", mx_total_vendedor, "100%"]); // Última fila con total y 100%
                            let startYVendedores= doc.lastAutoTable.finalY + 10;
                            doc.autoTable({
                                startY: startYVendedores,
                                startX: 10,
                                head: [
                                    [{ content: "TOTAL POR VENDEDORES", colSpan: 3, styles: { 
                                        fontSize: 14, 
                                        fontStyle: 'bold', 
                                        fillColor: [41, 128, 185], 
                                        textColor: [255, 255, 255], 
                                        halign: 'center' 
                                    }}],
                                    ["Vendedor", "Venta", "% Mes"]
                                ],
                                body: tablaVendedores,
                                theme: "grid",
                                styles: {
                                    fontSize: 12,
                                    cellPadding: 0.5,
                                    lineWidth: 0.1,
                                    lineColor: [0, 0, 0],
                                },
                                headStyles: { fillColor: [41, 128, 185], textColor: [255, 255, 255], halign: "center" },
                                bodyStyles: { halign: "center", textColor: [30, 30, 30] },
                                columnStyles: {
                                    0: { cellWidth: 40 },
                                    1: { cellWidth: 40 },
                                    2: { cellWidth: 40 }
                                }
                            });

                            let remisiones_con_saldo_p = datos.remisiones_con_saldo;
                                
                            let remisiones_con_saldo_tabla = [];

                                let startYRemSaldo = doc.lastAutoTable.finalY + 10; // Espacio de 10 unidades debajo de la anterior
                                doc.autoTable({
                                    startY: startYRemSaldo,
                                    head: [['LISTADO DE REMISIONES CON SALDO PENDIENTE']],
                                    theme: 'grid',
                                    styles: {
                                        fontSize: 12,
                                        fontStyle: 'bold',
                                        fillColor: [41, 128, 185],
                                        textColor: [255, 255, 255],
                                        halign: 'center',
                                        lineWidth: 0.1,
                                        lineColor: [0, 0, 0],
                                        cellPadding: 0.5,
                                    },
                                    columnStyles: {
                                        0: { cellWidth: 'auto' }
                                    }
                                });

                                let startYTablaSaldo = doc.lastAutoTable.finalY ;

                                if (remisiones_con_saldo_p.length === 0) {
                                    let mensajeSinRemisiones = [
                                        [{
                                            content: "No hay remisiones con saldo pendiente.",
                                            colSpan: 5,
                                            styles: { halign: 'center', fontStyle: 'bold' }
                                        }]
                                    ];

                                    doc.autoTable({
                                        head: [["Num remision", "Cliente", "Vendedor", "Fecha", "Total"]],
                                        body: mensajeSinRemisiones,
                                        startY: startYTablaSaldo,
                                        theme: "grid",
                                        styles: {
                                            fontSize: 12,
                                            cellPadding: 0.5,
                                            lineWidth: 0.1,
                                            lineColor: [0, 0, 0],
                                        },
                                        headStyles: {
                                            fillColor: [41, 128, 185],
                                            halign: "center",
                                            textColor: [255, 255, 255]
                                        },
                                        bodyStyles: {
                                            halign: "center",
                                            textColor: [30, 30, 30]
                                        },
                                        columnStyles: {
                                            0: { cellWidth: 40 },
                                            1: { cellWidth: 40 },
                                            2: { cellWidth: 40 },
                                            3: { cellWidth: 40 },
                                        
                                        }
                                    });
                                } else {
                                    for (let i = 0; i < remisiones_con_saldo_p.length; i++) {
                                        const mx_importe_con_saldo = new Intl.NumberFormat('es-MX', {
                                            style: 'currency',
                                            currency: 'MXN',
                                        }).format(remisiones_con_saldo_p[i].importe_total);

                                        let datos_remisiones_con_saldo = [
                                            remisiones_con_saldo_p[i].num_remision,
                                            remisiones_con_saldo_p[i].cliente,
                                            remisiones_con_saldo_p[i].vendedor,
                                            remisiones_con_saldo_p[i].fecha,
                                            mx_importe_con_saldo
                                        ];
                                        remisiones_con_saldo_tabla.push(datos_remisiones_con_saldo);
                                    }

                                    doc.autoTable({
                                        head: [["Num remision", "Cliente", "Vendedor", "Fecha", "Total"]],
                                        body: remisiones_con_saldo_tabla,
                                        startY: startYTablaSaldo,
                                        theme: "grid",
                                        styles: {
                                            fontSize: 12,
                                            cellPadding: 0.5,
                                            lineWidth: 0.1,
                                            lineColor: [0, 0, 0],
                                        },
                                        headStyles: {
                                            fillColor: [41, 128, 185],
                                            halign: "center",
                                            textColor: [255, 255, 255]
                                        },
                                        bodyStyles: {
                                            halign: "center",
                                            textColor: [30, 30, 30]
                                        },
                                        columnStyles: {
                                            0: { cellWidth: 40 },
                                            1: { cellWidth: 40 },
                                            2: { cellWidth: 40 },
                                            3: { cellWidth: 40 },
                                            
                                        }
                                    });
                                }
            
                            let usuario = respuesta.usuario.nombre;
                            let apellido_p = respuesta.usuario.apellido_p;                  
                            const fechaHora = new Date();
                            const opcionesFecha = { year: 'numeric', month: '2-digit', day: '2-digit' };
                            const opcionesHora = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
                            const fechaFormateada = fechaHora.toLocaleDateString('es-MX', opcionesFecha);
                            const horaFormateada = fechaHora.toLocaleTimeString('es-MX', opcionesHora);
                          
                            doc.addPage();

                            doc.addImage(imgData1, 'PNG', 160, 10, 40, 15);
                            doc.setFontSize(16);
                            doc.setTextColor(0, 0, 0);
                            doc.setFont("helvetica", "bold");
                            doc.text(nombre_real, 55, 20);

                            const nombre_archivo = `${nombre_real}_${fecha_i}_${fecha_f}.pdf`;

                            /*CALCULANDO LA TABLA DE METAS DIARIAS PARA VENDER*/
                              doc.autoTable({
                                    startY: 44, 
                                    startX: 10, 
                                    head: [['HISTORICO DE CUMPLIMIENTO AL DIA']],
                                    theme: 'grid',
                                    styles: {
                                        fontSize: 12,
                                        fontStyle: 'bold',
                                        fillColor: [121, 228, 252],
                                        textColor: [255, 255, 255], 
                                        halign: 'center',
                                        lineWidth: 0.1,
                                    lineColor: [0, 0, 0],
                                        cellPadding: 0.5,
                                        
                                    },
                                    columnStyles: {
                                        0: { cellWidth: 'auto' }
                                    }
                            });
                            doc.autoTable({
                                head: [['Meta diaria', 'Meta ajustada', 'Venta anterior', 'faltante anterior','Acumulado de la meta']], // Encabezado de  tabla
                                body: resumen_metas.map((item) => [
                                    item.meta_diaria,
                                    item.meta_ajustada,
                                    item.vendido,
                                    item.faltante_dia,
                                    item.acumulado_pendiente
                                ]),
                                startY: 50,
                                startX: 10, 
                                theme: 'grid',
                                styles: {  
                                    fontSize: 12, 
                                    cellPadding: .5, 
                                    lineWidth: .1, 
                                    lineColor: [0, 0, 0] 
                                },
                                headStyles: { fillColor: [121, 228, 252], halign: 'center' }, // Centra el texto del encabezado
                                bodyStyles: { halign: 'center',textColor: [30, 30, 30]  }, // Centra el texto en el cuerpo de la tabla
                                columnStyles: {
                                    0: { cellWidth: 40, halign: 'center' }, // Centra el contenido de la primera columna
                                    1: { cellWidth: 40, halign: 'center' }  // Centra el contenido de la segunda columna
                                }
                            });
 
                            let remisiones_canceladas = datos.remisiones_canceladas;
                            console.log('Este es el listado de remisiones canceladas', remisiones_canceladas);
                            let remisiones_canceladas_tabla = [];
                            
                            /*recuperando datos para tabla cremisiones canceldas*/
                            let startYCanceladas = doc.lastAutoTable.finalY + 10;
                            
                            doc.autoTable({
                                    startY: startYCanceladas,
                                    head: [['REMISIONES CANCELADAS']],
                                    theme: 'grid',
                                    styles: {
                                        fontSize: 12,
                                        fontStyle: 'bold',
                                        fillColor: [41, 128, 185], // Azul
                                        textColor: [255, 255, 255], // Blanco
                                        halign: 'center',
                                        lineWidth: 0.1,
                                    lineColor: [0, 0, 0],
                                        cellPadding: 0.5,
                                        
                                    },
                                    columnStyles: {
                                        0: { cellWidth: 'auto' }
                                    }
                            });
                            let startYCan = doc.lastAutoTable.finalY ;
                            if(remisiones_canceladas.length === 0){
                                let mensajeSinRemisiones_c = [
                                        [{
                                            content: "No hay remisiones canceladas.",
                                            colSpan: 5,
                                            styles: { halign: 'center', fontStyle: 'bold' }
                                        }]
                                    ];

                                    doc.autoTable({
                                        head: [["Num remision", "Cliente", "Vendedor", "Fecha", "Total"]],
                                        body: mensajeSinRemisiones_c,
                                        startY: startYCan,
                                        startX: 10,
                                        theme: "grid",
                                        styles: {
                                            fontSize: 12,
                                            cellPadding: 0.5,
                                            lineWidth: 0.1,
                                            lineColor: [0, 0, 0],
                                        },
                                        headStyles: {
                                            fillColor: [41, 128, 185],
                                            halign: "center",
                                            textColor: [255, 255, 255]
                                        },
                                        bodyStyles: {
                                            halign: "center",
                                            textColor: [30, 30, 30]
                                        },
                                        columnStyles: {
                                            0: { cellWidth: 40 },
                                            1: { cellWidth: 40 },
                                            2: { cellWidth: 40 },
                                            3: { cellWidth: 40 },
                                        
                                        }
                                    });
                            }else{
                                for(var i = 0; i<= remisiones_canceladas.length - 1; i++){
                                const mx_importe_remision = new Intl.NumberFormat('es-MX', {
                                    style: 'currency',
                                    currency: 'MXN',
                                }).format(remisiones_canceladas[i].importe_total);

                                let datosRemision = [remisiones_canceladas[i].num_remision, remisiones_canceladas[i].cliente, remisiones_canceladas[i].vendedor, remisiones_canceladas[i].fecha, mx_importe_remision];
                                remisiones_canceladas_tabla.push(datosRemision);
                            }

                            doc.autoTable({
                                    head: [["Num remision", "Cliente", "Vendedor", "Fecha", "Total"]],
                                    body: remisiones_canceladas_tabla,
                                    startY: startYCan, 
                                    startX: 10,
                                    theme: "grid",
                                    margin: { bottom: 35 }, 
                                    styles: {
                                        fontSize: 12,
                                        cellPadding: 0.5,
                                        lineWidth: 0.1,
                                        lineColor: [0, 0, 0],
                                    },
                                    headStyles: {
                                        fillColor: [41, 128, 185],
                                        halign: "center",
                                        textColor: [255, 255, 255]
                                    },
                                    bodyStyles: {
                                        halign: "center",
                                        textColor: [30, 30, 30]
                                    },
                                    columnStyles: {
                                        0: { cellWidth: 40, halign: "center" },
                                        1: { cellWidth: 40, halign: "center" },
                                        2: { cellWidth: 40, halign: "center" },
                                        3: { cellWidth: 40, halign: "center" }
                                    }
                                });
                            }

                            let startYDescuentos = doc.lastAutoTable.finalY + 10;

                            doc.autoTable({
                                    startY: startYDescuentos, 
                                    head: [['DESCUENTOS APLICADOS EN REMISIONES']],
                                    theme: 'grid',
                                    styles: {
                                        fontSize: 12,
                                        fontStyle: 'bold',
                                        fillColor: [41, 128, 185], // Azul
                                        textColor: [255, 255, 255], // Blanco
                                        halign: 'center',
                                        lineWidth: 0.1,
                                    lineColor: [0, 0, 0],
                                        cellPadding: 0.5,
                                        
                                    },
                                    columnStyles: {
                                        0: { cellWidth: 'auto' }
                                    }
                            });
                           
                            let descuentos_aplicados = datos.descuentosAplicados;
                            console.log('Este es el listado de descuentos aplicados en remisiones', descuentos_aplicados);
                            let descuentos_aplicados_tabla = [];
                            let startYDesc = doc.lastAutoTable.finalY ;
                            if(descuentos_aplicados.length === 0){
                                let mensajeSinDescuentos = [
                                        [{
                                            content: "No hay descuentos aplicados en remisiones.",
                                            colSpan: 5,
                                            styles: { halign: 'center', fontStyle: 'bold' }
                                        }]
                                    ];
                                    doc.autoTable({
                                        head: [["Num remision", "Cliente", "Vendedor", "Fecha", "Aplicado"]],
                                        body: mensajeSinDescuentos,
                                        startY: startYDesc,
                                        startX: 10,
                                        theme: "grid",
                                        styles: {
                                            fontSize: 12,
                                            cellPadding: 0.5,
                                            lineWidth: 0.1,
                                            lineColor: [0, 0, 0],
                                        },
                                        headStyles: {
                                            fillColor: [41, 128, 185],
                                            halign: "center",
                                            textColor: [255, 255, 255]
                                        },
                                        bodyStyles: {
                                            halign: "center",
                                            textColor: [30, 30, 30]
                                        },
                                        columnStyles: {
                                            0: { cellWidth: 40 },
                                            1: { cellWidth: 40 },
                                            2: { cellWidth: 40 },
                                            3: { cellWidth: 40 },
                                        
                                        }
                                    });
                                    doc.lastAutoTable = doc.autoTable.previous;
                            }else{
                                for(var i = 0; i<= descuentos_aplicados.length - 1; i++){
                                const mx_importe_descuento = new Intl.NumberFormat('es-MX', {
                                    style: 'currency',
                                    currency: 'MXN',
                                }).format(descuentos_aplicados[i].descuento_aplicado);
                                let datosDescuento = [descuentos_aplicados[i].num_remision, descuentos_aplicados[i].cliente, descuentos_aplicados[i].vendedor, descuentos_aplicados[i].fecha,
                                mx_importe_descuento];
                                descuentos_aplicados_tabla.push(datosDescuento);
                            }
                            doc.autoTable({
                                    head: [["Num remision", "Cliente", "Vendedor", "Fecha","Aplicado"]],
                                    body: descuentos_aplicados_tabla,
                                    startY: startYDesc, 
                                    startX: 10,
                                    theme: "grid",
                                    margin: { bottom: 35 }, 
                                    styles: {
                                        fontSize: 12,
                                        cellPadding: 0.5,
                                        lineWidth: 0.1,
                                        lineColor: [0, 0, 0],
                                    },
                                    headStyles: {
                                        fillColor: [41, 128, 185],
                                        halign: "center",
                                        textColor: [255, 255, 255]
                                    },
                                    bodyStyles: {
                                        halign: "center",
                                        textColor: [30, 30, 30]
                                    },
                                    columnStyles: {
                                        0: { cellWidth: 40, halign: "center" },
                                        1: { cellWidth: 40, halign: "center" },
                                        2: { cellWidth: 40, halign: "center" },
                                        3: { cellWidth: 40, halign: "center" }
                                    }
                                });
                                doc.lastAutoTable = doc.autoTable.previous;
                            }

                            doc.addPage('a4', 'l'); 

                            console.log(doc.internal.pages);

                            doc.addImage(imgData1, 'PNG', 220, 10, 50, 10);
                            doc.setFontSize(16);
                            doc.setTextColor(0, 0, 0);
                            doc.setFont("helvetica", "bold");

                            const pageWidth = doc.internal.pageSize.getWidth();
                            const nombreWidth = doc.getTextWidth(nombre_real);
                            doc.text(nombre_real, (pageWidth - nombreWidth) / 2, 20);
                            doc.setFontSize(14);
                            doc.setFont("helvetica", "bold");

                            const graficaWidth = doc.getTextWidth("GRAFICA DE VENTAS");
                            doc.text("GRAFICA DE VENTAS", (pageWidth - graficaWidth) / 2, 28);
                           
                            doc.setFontSize(10);
                            doc.setFont("helvetica", "normal");

                            let grafica_ventas = datos.ventashabiles;
                            console.log('Este es el listado de ventas de dias habiles', grafica_ventas);
                            const fechas = grafica_ventas.map(dato => dato.fecha); 
                            console.log('Estas son las fechas de las ventas de dias habiles', fechas);
                            const totalesVendidos = grafica_ventas.map(dato => dato.total_vendido); 
                            console.log('Estos son los totales de las ventas de dias habiles', totalesVendidos);
                            const container_grafica = document.querySelector("#d-main");
                            container_grafica.innerHTML = "";
                              const options = {
                                series: [{
                                    name: 'Total vendido',
                                    data: totalesVendidos // Totales vendidos
                                }],
                                chart: {
                                    type: 'line', 
                                     height: 300,   // Tamaño fijo
                                     width: 1600,
                                    renderer: 'svg',
                                    toolbar: {
                                        show: false
                                    }
                                },
                                stroke: {
                                    curve: 'smooth',
                                    width: 4
                                },
                               dataLabels: {
                                    enabled: true,
                                    formatter: function (value) {
                                        return `$${value.toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                                    }
                                },
                                xaxis: {
                                    categories: fechas, 
                                    title: {
                                        text: 'Fecha de ventas'
                                    }
                                    
                                },
                               yaxis: {
                                    title: { text: 'Monto Máximo' },
                                    labels: {
                                        formatter: function (value) {
                                            return `$${value.toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                                        },
                                       
                                    }
                                },
                                tooltip: {
                                    enabled: true,
                                    shared: false,
                                    followCursor: true,
                                    x: {
                                        format: 'dd/MM/yyyy'
                                    },
                                    y: {
                                        formatter: function(value) {
                                            return `$${value.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                                        }
                                    }
                                },
                                grid: {
                                    show: false
                                },
                                fill: {
                                    type: 'gradient',
                                    gradient: {
                                        shade: 'light',
                                        type: "vertical",
                                        shadeIntensity: 0.4,   
                                    }
                                }
                            };  
                            
                    const chart = new ApexCharts(document.querySelector("#d-main"), options);
                    const grafica_visual = document.querySelector('#grafico-container');
                    grafica_visual.style.display = "flex";
                     chart.render().then(() => {
                        setTimeout(() => {
                            chart.dataURI({ scale: 5 }).then(({ imgURI }) => {
                                if (imgURI) {      
                                doc.addImage(imgURI, 'PNG', 10, 40, 260, 120, undefined, 'NONE'); 

                                } else {
                                    console.error("La gráfica no generó un dataURI válido.");
                                }

                                const pageCount = doc.getNumberOfPages();
                                for (let i = 1; i <= pageCount; i++) {
                                    doc.setPage(i);
                                    const pageHeight = doc.internal.pageSize.height;
                                    const pageWidth = doc.internal.pageSize.width;

                                   const textoCompleto = `Generado por: ${usuario} ${apellido_p}, ${fechaFormateada} ${horaFormateada}, Sitio oficial: gifronterasur.com.mx`;

                                    doc.text(textoCompleto, 15, pageHeight - 20);

                                    const anchoTexto = doc.getTextWidth(textoCompleto);
                                    doc.link(15, pageHeight - 25, anchoTexto, 10, { url: 'https://gifronterasur.com.mx' });
                                }
                                grafica_visual.style.display = "none";
                                doc.save(nombre_archivo);
                                $btnGenerar.prop('disabled', false);
                                $('#generaReporteIcon').removeClass('fa-spinner fa-spin').addClass('fa-file-pdf');
                                $('#generaReporteText').text('Generar PDF');
                            }).catch(error => console.error("Error al obtener dataURI:", error));
                                $btnGenerar.prop('disabled', false);
                                $('#generaReporteIcon').removeClass('fa-spinner fa-spin').addClass('fa-file-pdf');
                                $('#generaReporteText').text('Generar PDF');
                        }, 500); 
                    });

                    }

                    });

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: `Por favor, seleccione la sucursal en el selector.`,
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        });
    </script>
@endsection
