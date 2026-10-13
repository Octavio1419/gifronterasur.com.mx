@extends('layout.index')
@section('content')
<main class="main-content">
    <br><br><br><br>
    <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row mb-4 animate__animated animate__fadeIn">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between bg-white p-4 rounded-4 shadow-xs border border-light">
                    <div class="d-flex align-items-center">
                        <div class="icon-box-premium text-primary me-3 d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-magnifying-glass-chart" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h4 class="fw-extrabold mb-1 text-dark tracking-tight">Conciliación y Auditoría de Facturas</h4>
                            <p class="text-muted mb-0 small fw-medium">Monitoreo de desviaciones entre importes cotizados y comprobantes XML</p>
                        </div>
                    </div>
                    <div>
                        @include('cotizaciones_herrajes.navConciliacionFactura')
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4 g-3 animate__animated animate__fadeIn">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 bg-white">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="p-3 bg-primary-subtle rounded-3 text-primary me-3">
                            <i class="fa-solid fa-folder-open fa-lg"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block fw-bold" style="font-size: 11px;">FOLIOS PENDIENTES</small>
                            <h4 class="fw-extrabold text-dark mb-0" id="kpi-pendientes">0</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 bg-white">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="p-3 bg-danger-subtle rounded-3 text-danger me-3">
                            <i class="fa-solid fa-triangle-exclamation fa-lg"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block fw-bold" style="font-size: 11px;">CON DESVIACIÓN / ALERTAS</small>
                            <h4 class="fw-extrabold text-danger mb-0" id="kpi-alertas">0</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 bg-white">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="p-3 bg-success-subtle rounded-3 text-success me-3">
                            <i class="fa-solid fa-circle-check fa-lg"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block fw-bold" style="font-size: 11px;">CONCILIADOS / CUADRADOS</small>
                            <h4 class="fw-extrabold text-success mb-0" id="kpi-conciliados">0</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 bg-white">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="p-3 bg-warning-subtle rounded-3 text-warning me-3">
                            <i class="fa-solid fa-money-bill-trend-up fa-lg"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block fw-bold" style="font-size: 11px;">MONTO AUDITADO XML</small>
                            <h4 class="fw-extrabold text-warning mb-0" id="kpi-monto-total">$0.00</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm border-0 rounded-4 mb-4 animate__animated animate__fadeIn">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-dark">Buscar Presupuesto / Proveedor</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" id="filtro-buscador" class="form-control border-start-0 ps-0" placeholder="Ej. Q-187464 ">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-dark">Filtrar por Sucursal</label>
                        <select id="filtro-sucursal" class="form-select form-select-sm">
                            <option value="">Todas las sucursales</option>
                            <option value="BOULEVARD">Tienda Herralum Boulevard</option>
                            <option value="CENTRO">Tienda Herralum Centro</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-dark">Estatus de Auditoría</label>
                        <select id="filtro-estatus" class="form-select form-select-sm">
                            <option value="">Todos los estados</option>
                            <option value="PENDIENTE">Facturas Pendientes</option>
                            <option value="CONCILIADO">Conciliación Completa</option>
                            <option value="DESVIACION">Con Diferencias/Alertas</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-dark">Fecha Inicial (Desde)</label>
                        <input type="date" id="filtro-fecha-inicio" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-dark">Fecha Final (Hasta)</label>
                        <input type="date" id="filtro-fecha-fin" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4 d-flex align-items-end gap-2">
                        <button type="button" id="btn-limpiar-filtros" class="btn btn-sm btn-light border w-50 fw-bold rounded-2 py-2">
                            <i class="fa-solid fa-broom me-1"></i> Limpiar
                        </button>
                        <button type="button" id="btn-descargar-faltantes" class="btn btn-sm btn-primary w-50 fw-bold rounded-2 py-2">
                           <i class="fa-solid fa-file-pdf" style="color: rgb(194, 9, 9);"></i> Faltantes
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row animate__animated animate__fadeIn">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white py-3 px-4 border-bottom border-light d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">
                                <i class="fa-solid fa-folder-tree text-primary me-2"></i>Bandeja de Presupuestos y Comprobantes
                            </h5>
                            <small class="text-muted fw-medium">Despliegue las carpetas para auditar individualmente cada XML asociado</small>
                        </div>
                        <span class="badge bg-dark-subtle text-dark fw-bold px-3 py-2 rounded-pill" style="font-size: 11px;" id="contador-registros">
                            Mostrando: 0 folios
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0" style="border-collapse: collapse;">
                                <thead class="table-dark small uppercase fw-bold" style="font-size: 0.73rem;">
                                    <tr>
                                        <th class="ps-4 py-3" style="width: 15%;">Presupuesto</th>
                                        <th class="py-3" style="width: 18%; text-align: center;">Fecha Cotización</th>
                                        <th class="py-3" style="width: 22%;">Proveedor</th>
                                        <th class="py-3 text-end" style="width: 15%;">Total Cotizado</th>
                                        <th class="py-3" style="width: 20%;">Sucursal</th>
                                        <th class="pe-4 py-3 text-center" style="width: 10%;">Estatus</th>
                                    </tr>
                                </thead>
                                <tbody id="tabla-auditoria-maestra">
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <i class="fa-solid fa-spinner fa-spin fa-2x text-primary mb-2"></i>
                                            <p class="text-muted small mb-0">Cargando la bandeja de presupuesto...</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       <div class="modal fade" id="modalResultadosConciliacion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header bg-dark text-white p-4 rounded-top-4">
                    <h5 class="modal-title fw-extrabold text-white" id="modal-titulo-auditoria">
                        <i class="fa-solid fa-chart-line text-success me-2"></i>Resultado del Análisis Inteligente
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4" id="modal-cuerpo-auditoria">
                </div>
            </div>
        </div>
    </div>
    </div>
</main>
<div id="modalPdf" style="display:none;">
<iframe id="pdfFrame" style="width:100%; height:500px;" frameborder="0"></iframe>
</div>
<img id="logo" src="{{ asset('img/logotipo_herralum.png') }}" style="display:none;">
<img id="logo_frontera" src="{{ asset('img/logo_frontera_sin.png') }}" style="display:none;">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<script>
$(document).ready(function() {

    cargarBandejaAuditoria();
    function cargarBandejaAuditoria() {
    let tbody = $('#tabla-auditoria-maestra');
    $.ajax({
        url: "/conciliacion/facturas/presupuesto/xml",
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            tbody.empty();
            let presupuestos = response.presupuestos;
            console.log('Datos recibidos :', presupuestos);

            if (Object.keys(presupuestos).length === 0) {
                tbody.html(`
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-circle-check text-success opacity-50 mb-3" style="font-size: 3rem;"></i>
                            <h6 class="fw-bold text-dark mb-1">¡Al día con las conciliaciones!</h6>
                            <p class="text-muted small mb-0">No se encontraron presupuestos con facturas XML registradas.</p>
                        </td>
                    </tr>
                `);
                actualizarContadoresDashboard(0, 0, 0, 0);
                return;
            }

            let totalPendientes = 0;
            let totalAlertas = 0;
            let totalConciliados = 0;
            let acumuladoMontoXml = 0;
            let totalFoliosContados = 0;

            $.each(presupuestos, function(folioPresupuesto, facturas) {
                totalFoliosContados++;
                let infoPadre = facturas[0];
                
                // =========================================================
                // CAMBIO 1: DETECTAR SI ES UN GRUPO "SIN PRESUPUESTO"
                // =========================================================
                let esSinPresupuesto = (folioPresupuesto === 'SIN_PRESUPUESTO' || folioPresupuesto === 'SIN PRESUPUESTO' || !folioPresupuesto);
                
                let fechaFiltroISO = infoPadre.fecha_cotizacion ? infoPadre.fecha_cotizacion.split(' ')[0] : '';
                
                // Si es sin presupuesto, asignamos valores por defecto seguros
                let fechaCot = esSinPresupuesto ? 'N/A' : formatearFecha(infoPadre.fecha_cotizacion);
                let montoCotizadoFlotante = esSinPresupuesto ? 0 : parseFloat(infoPadre.total_cotizacion || 0);
                let totalCot = esSinPresupuesto ? '0.00' : montoCotizadoFlotante.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                let sucursalNombre = infoPadre.nombre_sucursal || (esSinPresupuesto ? 'DIRECTO' : 'Sin Sucursal');
                let proveedorNombre = infoPadre.proveedor || (esSinPresupuesto ? (infoPadre.nombre || 'Proveedor Directo') : 'Sin Proveedor');

                // EVALUACIÓN DE CIERRE TOTAL 
                let presupuestoYaCerradoDefinitivo = parseInt(infoPadre.emision_completa_cotizaciones || 0) === 1;
                
                // =========================================================
                // CAMBIO 2: BADGE DE AUTORIZACIÓN PERSONALIZADO
                // =========================================================
                let badgeAutorizado = '';
                if (esSinPresupuesto) {
                    badgeAutorizado = `<span class="badge bg-secondary-subtle text-secondary px-2 py-1-5 rounded fw-bold" style="font-size: 10px;"><i class="fa-solid fa-bolt"></i> DIRECTO</span>`;
                } else if (infoPadre.folio_autorizado == 1) {
                    badgeAutorizado = `<span class="badge bg-success-subtle text-success px-2 py-1-5 rounded fw-bold" style="font-size: 10px;"><i class="fa-solid fa-check-double"></i> AUTORIZADO</span>`;
                } else {
                    badgeAutorizado = `<span class="badge bg-warning-subtle text-warning px-2 py-1-5 rounded fw-bold" style="font-size: 10px;"><i class="fa-solid fa-hourglass"></i> PENDIENTE</span>`;
                }
                
                let filasFacturasHtml = '';
                let sumaTotalFacturas = 0; 
                let todasConciliadas = true; 

                $.each(facturas, function(index, factura) {
                    let fechaTimbrado = formatearFechaHora(factura.fecha_timbrado);
                
                    let subtotal = parseFloat(factura.subtotal).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    let descuento = parseFloat(factura.descuento).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    let neto = parseFloat(factura.neto).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    let iva = parseFloat(factura.iva).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    let retencion = parseFloat(factura.retencion || 0).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    let totalFactura = parseFloat(factura.total_factura).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                    sumaTotalFacturas += parseFloat(factura.total_factura || 0);
                    acumuladoMontoXml += parseFloat(factura.total_factura || 0);

                    let estiloTr = '';
                    let badgeFactura = '';
                    let botonAccion = '';
                    if (factura.emision_completa == 1 || presupuestoYaCerradoDefinitivo) {
                        estiloTr = 'style="background-color: #f1f5f9; opacity: 0.85;"';
                        badgeFactura = `
                            <span class="badge bg-success text-white px-2 py-1-5 rounded fw-bold d-block mb-1" style="font-size: 10px; letter-spacing: 0.3px;">
                                <i class="fa-solid fa-circle-check me-1"></i> CONCILIADO
                            </span>`;
                        botonAccion = `
                            <button type="button" class="btn btn-xs btn-light text-muted rounded-2 px-2 py-1 w-100 fw-medium" style="font-size: 11px;" disabled>
                                <i class="fa-solid fa-lock me-1"></i> Cerrado
                            </button>`;
                    } else {
                        todasConciliadas = false; 
                        badgeFactura = `
                            <span class="badge bg-warning-subtle text-warning px-2 py-1-5 rounded fw-bold d-block mb-1" style="font-size: 10px; letter-spacing: 0.3px;">
                                <i class="fa-solid fa-clock-rotate-left me-1"></i> PENDIENTE
                            </span>`;
                        botonAccion = `
                            <button type="button" 
                                    class="btn btn-xs btn-success text-white rounded-2 px-2 py-1 w-100 fw-bold btn-abrir-conciliacion"
                                    data-factura-id="${factura.factura_id}"
                                    data-presupuesto="${folioPresupuesto}"
                                    data-sucursal="${sucursalNombre}" style="font-size: 11px;">
                                <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Conciliar
                            </button>`;
                    }

                    filasFacturasHtml += `
                        <tr ${estiloTr} data-factura-id-fila="${factura.factura_id}">
                            <td class="ps-3 fw-semibold text-dark">${fechaTimbrado}</td>
                            <td><span class="text-muted font-monospace small bg-light p-1 rounded border" title="${factura.uuid}">${factura.uuid}</span></td>
                            <td><span class="text-muted font-monospace p-1" title="${factura.serie_folio}">${factura.serie_folio || 'S/N'}</span></td>
                            <td class="text-muted">$${subtotal}</td>
                            <td class="text-danger">$${descuento}</td>
                            <td class="text-dark fw-medium">$${neto}</td>
                            <td class="text-muted">$${iva}</td>
                            <td class="text-warning">$${retencion}</td>
                            <td class="fw-extrabold text-dark fs-6" style="background-color: #f8fafc;">$${totalFactura}</td>
                            <td class="text-center pe-3">${badgeFactura} ${botonAccion}</td>
                        </tr>`;
                });

                let sumaTotalFormateada = sumaTotalFacturas.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                let htmlBloqueAccionFinal = '';
                
                let diferenciaMontos = Math.abs(sumaTotalFacturas - montoCotizadoFlotante);
                let montosCuadran = diferenciaMontos < 5.65;
                let estatusAuditoriaFila = 'PENDIENTE';

                // =========================================================
                // CAMBIO 3: CONTROL DE FLUJO Y ALERTAS DE CIERRE
                // =========================================================
                if (presupuestoYaCerradoDefinitivo) {
                    estatusAuditoriaFila = 'CONCILIADO';
                    totalConciliados++;
                    htmlBloqueAccionFinal = `
                        <div class="alert alert-secondary d-flex align-items-center justify-content-between mb-0 mt-3 p-2 px-3 rounded-3 shadow-sm border-secondary" style="background-color: #e2e8f0;">
                            <div class="small text-secondary fw-bold" style="color: #475569 !important;">
                                <i class="fa-solid fa-box-archive fs-5 me-2 align-middle"></i>
                                <strong>Proceso Concluido:</strong> Este registro ha sido auditado y cerrado.
                            </div>
                            <span class="badge bg-secondary text-white px-3 py-2 rounded-2 fw-bold"><i class="fa-solid fa-lock me-1"></i> ARCHIVADO</span>
                        </div>`;
                } else if (todasConciliadas) {
                    // Si no tiene presupuesto, no comparamos diferencia contra $0.00, directamente permitimos cerrar
                    if (montosCuadran || esSinPresupuesto) {
                        estatusAuditoriaFila = 'CONCILIADO';
                        totalConciliados++;
                        htmlBloqueAccionFinal = `
                            <div class="alert alert-success d-flex align-items-center justify-content-between mb-0 mt-3 p-2 px-3 rounded-3 shadow-sm border-success">
                                <div class="small text-success fw-semibold">
                                    <i class="fa-solid fa-square-check fs-5 me-2 align-middle"></i>
                                    <strong>Auditoría Completa:</strong> ${esSinPresupuesto ? 'Conceptos validados correctamente en modo directo.' : 'Los montos cuadran perfectamente contra el presupuesto base.'}
                                </div>
                                <button type="button" class="btn btn-sm btn-success fw-bold text-white px-3 shadow-sm btn-finalizar-amarre" data-folio="${folioPresupuesto}" data-requiere-nota="0">
                                    <i class="fa-solid fa-lock-open me-1"></i> Todo listo
                                </button>
                            </div>`;
                    } else {
                        estatusAuditoriaFila = 'DESVIACION';
                        totalAlertas++;
                        htmlBloqueAccionFinal = `
                            <div class="alert alert-warning mb-0 mt-3 p-3 rounded-3 shadow-sm border-warning">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="small text-dark fw-bold">
                                        <i class="fa-solid fa-circle-exclamation fs-5 text-warning me-2 align-middle"></i>
                                        Diferencia detectada ($${(sumaTotalFacturas - montoCotizadoFlotante).toLocaleString('es-MX', { minimumFractionDigits: 2 })}) - El dinero facturado no es igual al cotizado.
                                    </div>
                                    <button type="button" class="btn btn-sm btn-warning fw-extrabold text-dark px-3 shadow-sm btn-finalizar-amarre" data-folio="${folioPresupuesto}" data-requiere-nota="1">
                                        <i class="fa-solid fa-comment-slash me-1"></i> Forzar Cierre con Motivo
                                    </button>
                                </div>
                                <div class="collapse container-nota-cierre-${folioPresupuesto}">
                                    <div class="bg-white p-2 rounded border shadow-xs mt-2">
                                        <label class="form-label text-muted fw-bold mb-1" style="font-size: 11px;">Escribe el motivo del desfase:</label>
                                        <textarea class="form-control form-control-sm input-nota-auditoria-${folioPresupuesto}" rows="2" placeholder="Escribe detalladamente la justificación aquí..."></textarea>
                                    </div>
                                </div>
                            </div>`;
                    }
                } else {
                    totalPendientes++;
                    htmlBloqueAccionFinal = `
                        <div class="text-center py-2 mt-2 border-top border-dashed">
                            <span class="text-muted small fw-medium">
                                <i class="fa-solid fa-ban me-1 text-danger"></i> Podrás cerrar el proceso de auditoría cuando concilies todas tus facturas.
                            </span>
                        </div>`;
                }

                // =========================================================
                // CAMBIO 4: PINTADO DE LA FILA PADRE (CARPETA)
                // =========================================================
                let iconoFolio = esSinPresupuesto ? 'fa-folder-minus text-secondary' : 'fa-folder-open text-warning';
                let etiquetaFolio = esSinPresupuesto 
                    ? `<span class="badge bg-dark text-white font-monospace px-2 py-1"><i class="fa-solid fa-slash-forward me-1"></i>SIN PRESUPUESTO</span>`
                    : folioPresupuesto;

                let filaMaestro = `
                    <tr class="bg-white fila-presupuesto-master" 
                        data-estatus-auditoria="${estatusAuditoriaFila}" 
                        data-fecha="${fechaFiltroISO}" 
                        style="${esSinPresupuesto ? 'background-color: #f8fafc;' : ''} border-top: 2px solid #e2e8f0; border-bottom: none; text-align: center">
                        <td class="ps-4 fw-extrabold text-primary fs-6 py-3 col-folio">
                            <i class="fa-solid ${iconoFolio} me-2"></i>
                            ${esSinPresupuesto ? etiquetaFolio : `
                                <a href="javascript:void(0);" 
                                   class="link-presupuesto text-decoration-none fw-bold" 
                                   data-id="${infoPadre.cotizacion_id}" 
                                   data-presupuesto="${folioPresupuesto}">
                                    ${folioPresupuesto}
                                </a>
                            `}
                        </td>
                        <td class="fw-bold text-dark col-fecha">${fechaCot}</td>
                        <td class="fw-bold text-secondary text-start col-proveedor">${proveedorNombre}</td>
                        <td class="text-end fw-extrabold text-dark fs-6">$${totalCot}</td>
                        <td class="fw-semibold text-muted col-sucursal"><i class="fa-solid fa-shop me-1 small"></i>${sucursalNombre}</td>
                        <td class="text-center pe-4">${badgeAutorizado}</td>
                    </tr>
                `;
                tbody.append(filaMaestro);

                // Sub-tabla de facturas ligadas
                let textoSubtabla = esSinPresupuesto 
                    ? `FACTURAS EN MODO DIRECTO (${facturas.length})`
                    : `FACTURAS LIGADAS A ESTE PRESUPUESTO (${facturas.length})`;

                let filaDetalle = `
                    <tr class="fila-detalle-xml" style="border-bottom: 2px solid #cbd5e1;">
                        <td colspan="6" class="p-0 bg-light-subtle">
                            <div class="px-5 py-3 bg-light border-start border-4 ${esSinPresupuesto ? 'border-secondary' : 'border-primary'} rounded-end-3 m-3 shadow-xs">
                                <div class="d-flex align-items-center mb-2 text-secondary fw-bold" style="font-size: 0.78rem; letter-spacing: 0.5px;">
                                    <i class="fa-solid fa-arrow-turn-down-right ${esSinPresupuesto ? 'text-secondary' : 'text-primary'} me-2 fs-6"></i>
                                    <span>${textoSubtabla}</span>
                                </div>
                                <table class="table table-sm table-bordered bg-white align-middle mb-0 shadow-xs rounded-3 overflow-hidden">
                                    <thead class="bg-secondary uppercase" style="font-size: 0.70rem; background-color: #475569;">
                                        <tr>
                                            <th class="ps-3 py-2" style="text-align: center">Fecha Timbrado</th>
                                            <th class="py-2" style="text-align: center">Folio Fiscal (UUID)</th>
                                            <th class="py-2" style="text-align: center">Serie folio</th>
                                            <th class="py-2" style="text-align: center">Subtotal</th>
                                            <th class="py-2" style="text-align: center">Descuento</th>
                                            <th class="py-2" style="text-align: center">Neto</th>
                                            <th class="py-2" style="text-align: center">IVA</th>
                                            <th class="py-2" style="text-align: center">Retención</th>
                                            <th class="py-2" style="text-align: center">Total Factura</th>
                                            <th class="pe-3 py-2 text-center" style="text-align: center">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 0.80rem; text-align:center;">
                                        ${filasFacturasHtml}
                                    </tbody>
                                    <tfoot style="font-size: 0.82rem; background-color: #f8fafc; border-top: 2px solid #cbd5e1;">
                                        <tr>
                                            <td colspan="8" class="text-end fw-extrabold text-secondary py-2 pe-3">
                                                <i class="fa-solid fa-calculator me-1"></i> TOTAL DE LAS FACTURAS:
                                            </td>
                                            <td class="text-center fw-black text-primary fs-6 bg-primary-subtle border-start border-end" style="color: #1e3a8a !important; font-weight: 900;">
                                                $${sumaTotalFormateada}
                                            </td>
                                            <td class="bg-light"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                ${htmlBloqueAccionFinal}
                            </div>
                        </td>
                    </tr>
                `;
                tbody.append(filaDetalle);
            });

            actualizarContadoresDashboard(totalPendientes, totalAlertas, totalConciliados, acumuladoMontoXml);
            $('#contador-registros').text(`Mostrando: ${totalFoliosContados} folios`);
        }
    });
}

    function actualizarContadoresDashboard(pendientes, alertas, conciliados, monto) {
        $('#kpi-pendientes').text(pendientes);
        $('#kpi-alertas').text(alertas);
        $('#kpi-conciliados').text(conciliados);
        $('#kpi-monto-total').text(monto.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' }));
    }

    $(document).ready(function() {
        $('#filtro-buscador').on('keyup', filtrarBandejaAuditoria);
        $('#filtro-sucursal, #filtro-estatus, #filtro-fecha-inicio, #filtro-fecha-fin').on('change', filtrarBandejaAuditoria);

        function filtrarBandejaAuditoria() {
            let busqueda = $('#filtro-buscador').val().toLowerCase().trim();
            let sucursalSelected = $('#filtro-sucursal').val().toLowerCase();
            let estatusSelected = $('#filtro-estatus').val();
            let fechaInicio = $('#filtro-fecha-inicio').val(); 
            let fechaFin = $('#filtro-fecha-fin').val();     

            let contadorVisibles = 0;

            $('.fila-presupuesto-master').each(function() {
                let filaMaestra = $(this);
                let filaDetalleHijo = filaMaestra.next('.fila-detalle-xml'); 

                let textoFolio = filaMaestra.find('.col-folio').text().toLowerCase();
                let textoProveedor = filaMaestra.find('.col-proveedor').text().toLowerCase();
                let textoSucursal = filaMaestra.find('.col-sucursal').text().toLowerCase();

                let dataEstatus = filaMaestra.data('estatus-auditoria');
                let dataFecha = filaMaestra.data('fecha'); 

                //Criterio de búsqueda (Buscador por texto) 
                let coincideBusqueda = busqueda === "" || textoFolio.includes(busqueda) || textoProveedor.includes(busqueda);

                //Criterio de Sucursal
                let coincideSucursal = sucursalSelected === "" || textoSucursal.includes(sucursalSelected);

                //Criterio de Estatus de Auditoría 
                let coincideEstatus = estatusSelected === "" || dataEstatus === estatusSelected;

                //Criterio de Rangos de Fechas
                let coincideFecha = true;
                if (dataFecha) {
                    if (fechaInicio && dataFecha < fechaInicio) coincideFecha = false;
                    if (fechaFin && dataFecha > fechaFin) coincideFecha = false;
                } else if (fechaInicio || fechaFin) {
                    coincideFecha = false;
                }

                // Si el folio pasa todas las condiciones, se muestra junto a su subtabla
                if (coincideBusqueda && coincideSucursal && coincideEstatus && coincideFecha) {
                    filaMaestra.show();
                    filaDetalleHijo.show(); 
                    contadorVisibles++;
                } else {
                    filaMaestra.hide();
                    filaDetalleHijo.hide();
                }
            });

            // Refrescar contador dinámico en el Card Header
            $('#contador-registros').text(`Mostrando: ${contadorVisibles} folios`);
        }

            // Botón Limpiar Filtros
            $('#btn-limpiar-filtros').on('click', function() {
                $('#filtro-buscador').val('');
                $('#filtro-sucursal').val('');
                $('#filtro-estatus').val('');
                $('#filtro-fecha-inicio').val('');
                $('#filtro-fecha-fin').val('');
                filtrarBandejaAuditoria();
            });
    });

    //
    //vamos a generar el historial de pz faltante
    $('#btn-descargar-faltantes').on('click', function(){
        console.log('vemos el comportamiento');
        //vamos a evualar que tenga filtros seleccionados si no no lo podra hacer, primero el rango de fechas
        let fechaInicio = $('#filtro-fecha-inicio').val(); 
        let fechaFin = $('#filtro-fecha-fin').val();     

        console.log('fecha inicio: ' + fechaInicio + ' fecha final: ' + fechaFin);

        if(!fechaInicio || !fechaFin){
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor, selecciona un rango de fechas.',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        //ahora la fecha final no debe de ser mayor que la ficha inicio
        if(fechaFin < fechaInicio){
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'La fecha final no debe de ser menor a la fecha de inicio',
                confirmButtonText: 'Aceptar'
            });
            return;
        }




    });


    $(document).on('click', '.btn-finalizar-amarre', function(e) {
        let boton = $(this);
        let folioPresupuesto = boton.data('folio'); 
        let requiereNota = parseInt(boton.data('requiere-nota'));
        let contenedorPadre = boton.closest('.px-5'); 
        let textoCotizado = contenedorPadre.closest('.fila-detalle-xml').prev('.fila-presupuesto-master').find('.text-end').text().replace(/[^0-9.-]/g, '');
        let textoFacturado = contenedorPadre.find('tfoot td').eq(1).text().replace(/[^0-9.-]/g, '');
        
        let totalCotizado = parseFloat(textoCotizado || 0);
        let totalFacturado = parseFloat(textoFacturado || 0);

        let facturasLigadas = [];
        
    
        contenedorPadre.find('table tbody tr').each(function() {
            let filaFactura = $(this);
            let botonConciliacion = filaFactura.find('.btn-abrir-conciliacion');
            
            let idFact = botonConciliacion.data('factura-id');
        
            if (!idFact) {
              
                idFact = botonConciliacion.attr('data-factura-id');
            }

            let totalIndividualTexto = filaFactura.find('td').eq(8).text().replace(/[^0-9.-]/g, '');
            let totalIndividual = parseFloat(totalIndividualTexto || 0);

            if (idFact) {
                facturasLigadas.push({
                    id_factura: parseInt(idFact),
                    monto: totalIndividual
                });
            }
        });

        if (facturasLigadas.length === 0) {
          
            contenedorPadre.find('table tbody tr').each(function() {
                let idFactFila = $(this).data('factura-id-fila'); 
                let totalIndividual = parseFloat($(this).find('td').eq(8).text().replace(/[^0-9.-]/g, '') || 0);
                if (idFactFila) {
                    facturasLigadas.push({ id_factura: parseInt(idFactFila), monto: totalIndividual });
                }
            });
        }

        if (requiereNota === 1) {
            let contenedorNota = $(`.container-nota-cierre-${folioPresupuesto}`);
            
            if (!contenedorNota.hasClass('show')) {
                contenedorNota.collapse('show');
                boton.html('<i class="fa-solid fa-paper-plane me-1"></i> Confirmar Cierre con Motivo');
                return;
            } 
        
            let notaTexto = $(`.input-nota-auditoria-${folioPresupuesto}`).val().trim();
            if (notaTexto === "") {
                Swal.fire({
                    title: 'Falta Justificación',
                    text: 'Es obligatorio escribir el motivo del desfase antes de cerrar.',
                    icon: 'warning',
                    confirmButtonColor: '#ffc107'
                });
                return;
            }       
            procesarCierreCiclo(folioPresupuesto, totalCotizado, totalFacturado, facturasLigadas, notaTexto);
        } else {
            Swal.fire({
                title: '¿Desea cerrar la conciliación de sus facturas?',
                text: `El presupuesto ${folioPresupuesto} cuadra perfectamente. Al realizar esta acción bloqueará el ciclo y no se podrán subir más XML.`,
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, todo listo',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    procesarCierreCiclo(folioPresupuesto, totalCotizado, totalFacturado, facturasLigadas, 'Cierre automático: Montos conciliados al 100%.');
                }
        });
    }

    function procesarCierreCiclo(folio, cotizado, facturado, arrayFacturas, nota) {
            let diferencia = facturado - cotizado;

            let dataEnviar = {
                _token: $('meta[name="csrf-token"]').attr('content'), 
                folio_presupuesto: folio,
                monto_presupuesto: cotizado,   
                monto_factura: facturado,       
                diferencia: diferencia,         
                comentarios: nota,             
                facturas: arrayFacturas         
            };

            console.log('Objeto procesado listo para enviar:', dataEnviar);

            $.ajax({
                url: "/conciliacion/presupuestos/cerrar/proceso/auditoria",
                method: 'POST',
                dataType: 'json',
                data: dataEnviar, 
                beforeSend: function() {
                    Swal.fire({
                        title: 'Guardando Auditoría...',
                        text: 'Espere un momento mientras se congela el ciclo.',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: '¡Ciclo Cerrado!',
                            text: response.message || 'La auditoría se guardó correctamente y el proceso fue bloqueado.',
                            icon: 'success',
                            confirmButtonColor: '#198754'
                        }).then(() => {
                            cargarBandejaAuditoria(); 
                        });
                    } else {
                        Swal.fire('Error Administrativo', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    let mensajeError = 'No se pudo comunicar con el servidor para cerrar la auditoría.';
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        mensajeError = xhr.responseJSON.message;
                    }
                    Swal.fire('Error de Conexión', mensajeError, 'error');
                }
            });
        }
    });
    
    // Variable de control global para almacenar el contexto del análisis activo
    let datosInstanciaConciliacion = null;
    let presupuestoActivoGlobal = "";
    let sucursal_activa = "";
    let facturasPresupuesto = [];
    let facturaId = "";

    $(document).on('click', '.btn-abrir-conciliacion', function() {
    facturaId = $(this).data('factura-id');
    let presupuesto = $(this).data('presupuesto');
    sucursal_activa = $(this).data('sucursal');
    console.log('Verificando que nos este llegando la sucursal para mostrar ene el pdf', sucursal_activa);
    presupuestoActivoGlobal = presupuesto;
    
    console.log('Iniciando auditoría de Factura ID: ' + facturaId + ' sobre Presupuesto: ' + presupuesto);
    
    $('#modal-titulo-auditoria').html(`
        <i class="fa-solid fa-magnifying-glass-chart text-success me-2"></i> 
        ANALISIS DEL PRESUPUESTO <span class="text-info">${presupuesto}</span>
    `);
    
    $('#modal-cuerpo-auditoria').html(`
        <div class="text-center py-5">
            <i class="fa-solid fa-spinner fa-spin fa-3x text-primary mb-3"></i>
            <h5 class="fw-bold text-dark">Ejecutando cruce de conceptos y validación de importes...</h5>
            <p class="text-muted small">Por favor espere, contrastando XML contra partidas cotizadas.</p>
        </div>
    `);
    
    var modalElement = document.getElementById('modalResultadosConciliacion');
    var modalAuditoria = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
    modalAuditoria.show();

    $.ajax({
        url: "/conciliacion/facturas/analizar/partidas",
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            factura_id: facturaId,
            presupuesto: presupuesto
        },
        success: function(response) {
            if (!response.success) return;

            datosInstanciaConciliacion = response;
            
            let resumen = response.resumen;
            let partidas = response.partidas;
            facturasPresupuesto = response.facturas_presupuesto || []; // <-- Lista de UUIDs y Folios
            
            let totalCotizado  = Number(resumen.total_cotizado).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            let totalFacturado = Number(resumen.total_facturado).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            let difGlobalValue = Number(resumen.desviacion_global);
                        
            let tarjetaDesviacionHtml = '';

            if (difGlobalValue > 0.01) {
                tarjetaDesviacionHtml = `
                    <div class="bg-danger-subtle p-3 rounded-3 border border-danger shadow-xs">
                        <small class="text-danger fw-bold d-block mb-1" style="font-size: 11px;"><i class="fa-solid fa-triangle-exclamation"></i> ALERTA: COBRO DE MÁS</small>
                        <h4 class="fw-extrabold text-danger mb-0">+$${difGlobalValue.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</h4>
                    </div>`;
            } else if (difGlobalValue < -0.01) {
                tarjetaDesviacionHtml = `
                    <div class="bg-warning-subtle p-3 rounded-3 border border-warning shadow-xs">
                        <small class="text-warning fw-bold d-block mb-1" style="font-size: 11px;"><i class="fa-solid fa-circle-minus"></i> DIFERENCIA EN MENOS / PENDIENTE</small>
                        <h4 class="fw-extrabold text-warning mb-0">-$${Math.abs(difGlobalValue).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</h4>
                    </div>`;
            } else {
                tarjetaDesviacionHtml = `
                    <div class="bg-success-subtle p-3 rounded-3 border border-success shadow-xs">
                        <small class="text-success fw-bold d-block mb-1" style="font-size: 11px;"><i class="fa-solid fa-circle-check"></i> IMPORTES CUADRADOS</small>
                        <h4 class="fw-extrabold text-success mb-0">$0.00</h4>
                    </div>`;
            }

           
            // NUEVO DISEÑO MINIMALISTA: Barra integrada de historial de facturas
            let htmlFacturasVinculadas = '';
            if (facturasPresupuesto.length > 0) {
                htmlFacturasVinculadas += `
                    <div class="mb-3 px-1">
                        <div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-2">
                            <span class="text-secondary fw-bold " style="font-size: 14px;">
                            </i> FACTURAS LIGADAS AL PRESUPUESTO
                            </span>
                            <span class="text-muted" style="font-size: 14px;">${facturasPresupuesto.length} factura(s)</span>
                        </div>
                        
                        <div class="d-flex flex-wrap gap-1.5 align-items-center">`;
                
                $.each(facturasPresupuesto, function(i, f) {
                    let esActual = (f.id == facturaId);
                    let labelFolio = f.serie_folio ? f.serie_folio : 'S/F';
                    let labelUuid = f.uuid ? f.uuid.substring(0, 8).toUpperCase() : 'SIN UUID';
                    let montoFormateado = Number(f.total).toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2});

                    if (esActual) {
                        htmlFacturasVinculadas += `
                            <div class="d-flex align-items-center bg-primary text-white rounded-2 px-2 py-1 shadow-xs border border-primary-subtle" 
                                style="font-size: 12px; line-height: 1;" title="UUID: ${f.uuid || 'N/A'}">
                                <i class="fa-solid fa-eye me-2 text-dark animate__flash animate__infinite" style="font-size: 12px;"></i>
                                <strong class="font-monospace me-1">${labelFolio}</strong>
                                <span class="text-white-50 font-monospace me-2">(${labelUuid})</span>
                                <span class="fw-extrabold bg-white text-primary px-1.5 py-0.5 rounded-1" style="font-size: 12px;">$${montoFormateado}</span>
                            </div>`;
                    } else {
                        // Facturas previas: Píldoras limpias, discretas y profesionales
                        htmlFacturasVinculadas += `
                            <div class="d-flex align-items-center bg-light text-dark rounded-2 px-2 py-1 border border-secondary-subtle hover-strip" 
                                style="font-size: 12px; line-height: 1; transition: all 0.15s ease;" title="UUID: ${f.uuid || 'N/A'}">
                                <i class="fa-solid fa-circle-check text-success me-2" style="font-size: 12px;"></i>
                                <strong class="font-monospace me-1 text-dark-emphasis">${labelFolio}</strong>
                                <span class="text-muted font-monospace me-2">(${labelUuid})</span>
                                <span class="fw-bold text-secondary-emphasis">$${montoFormateado}</span>
                            </div>`;
                    }
                });

                htmlFacturasVinculadas += `
                        </div>
                    </div>
                    
                    <style>
                        .gap-1\\.5 { gap: 6px !important; }
                        .hover-strip:hover {
                            background-color: #f1f3f5 !important;
                            border-color: #adb5bd !important;
                            cursor: default;
                        }
                    </style>
                `;
            }

            // Estructura principal
            let html = `
                <div class="row g-3 mb-3 animate__animated animate__fadeIn">
                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded-3 border">
                            <small class="text-muted fw-bold d-block mb-1" style="font-size: 11px;">MONTO DE COTIZACION</small>
                            <h4 class="fw-extrabold text-dark mb-0">$${totalCotizado}</h4>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded-3 border">
                            <small class="text-muted fw-bold d-block mb-1" style="font-size: 11px;">MONTO DE FACTURADO</small>
                            <h4 class="fw-extrabold text-primary mb-0">$${totalFacturado}</h4>
                        </div>
                    </div>
                    <div class="col-md-4">${tarjetaDesviacionHtml}</div>
                </div>

                ${htmlFacturasVinculadas} <!-- Inyección de las facturas cruzadas -->

                <div class="table-responsive rounded-3 border border-light shadow-xs animate__animated animate__fadeIn">
                    <table class="table table-bordered table-sm align-middle mb-0" style="font-size: 12px;">
                        <thead class="table-dark small text-center" style="font-size: 11px;">
                            <tr>
                                <th rowspan="2" class="align-middle ps-2 text-start">Herraje / Concepto</th>
                                <th colspan="2" class="bg-secondary text-white py-1">1. Cotizado Base</th>
                                <th colspan="2" class="bg-primary text-white py-1">2. Facturado XML</th>
                                <th colspan="3" class="bg-dark text-white py-1">3. Desviaciones</th>
                                <th rowspan="2" class="align-middle pe-2">Dictamen</th>
                            </tr>
                            <tr style="font-size: 10px;">
                                <th class="bg-secondary-subtle text-dark py-1">Cant</th>
                                <th class="bg-secondary-subtle text-dark py-1">Precio</th>
                                <th class="bg-primary-subtle text-dark py-1">Cant</th>
                                <th class="bg-primary-subtle text-dark py-1">Precio</th>
                                <th class="py-1">Cant</th>
                                <th class="py-1">Precio</th>
                                <th class="py-1">Importe</th>
                            </tr>
                        </thead>
                        <tbody>`;
            
            $.each(partidas, function(index, p) {
                let claseFila = '';
                let badgeDictamen = '';

                switch(p.estatus) {
                    case 'PRECIO_INFLADO':
                        claseFila = 'table-danger';
                        badgeDictamen = '<span class="badge bg-danger text-white w-100"><i class="fa-solid fa-arrow-trend-up me-1"></i> PRECIO ALTO</span>';
                        break;
                    case 'CANTIDAD_EXCEDIDA':
                        claseFila = 'table-danger';
                        badgeDictamen = '<span class="badge bg-danger text-white w-100"><i class="fa-solid fa-boxes-packing me-1"></i> PIEZAS DE MÁS</span>';
                        break;
                    case 'EXCEDENTE_PRODUCTO':
                        claseFila = 'table-warning';
                        badgeDictamen = '<span class="badge bg-dark text-white w-100"><i class="fa-solid fa-plus me-1"></i> NO COTIZADO</span>';
                        break;
                    case 'SURTIDO_INCOMPLETO':
                        claseFila = 'table-warning';
                        badgeDictamen = '<span class="badge bg-warning text-dark w-100"><i class="fa-solid fa-minus me-1"></i> INCOMPLETO</span>';
                        break;
                    case 'NO_FACTURADO':
                        claseFila = 'text-muted bg-light-subtle';
                        badgeDictamen = '<span class="badge bg-secondary text-white w-100"><i class="fa-solid fa-ban me-1"></i> NO SURTIDO</span>';
                        break;
                    default:
                        badgeDictamen = '<span class="badge bg-success text-white w-100"><i class="fa-solid fa-check me-1"></i> CORRECTO</span>';
                }

                let pCotizado  = p.precio_cotizado.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                let pFacturado = p.precio_facturado.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                let dPrecio    = p.dif_precio.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                let dTotal     = p.dif_total.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                html += `
                    <tr class="${claseFila}">
                        <td class="ps-2">
                            <strong class="text-dark d-block">${p.clave}</strong>
                            <small class="text-muted text-truncate d-block" style="max-width: 280px;" title="${p.descripcion}">${p.descripcion}</small>
                        </td>
                        <td class="text-center text-muted">${p.cant_cotizada}</td>
                        <td class="text-end text-muted">$${pCotizado}</td>
                        <td class="text-center fw-bold text-dark">${p.cant_facturada}</td>
                        <td class="text-end fw-bold text-dark">$${pFacturado}</td>
                        <td class="text-center fw-bold ${p.dif_cantidad > 0 ? 'text-danger' : (p.dif_cantidad < 0 ? 'text-warning' : 'text-muted')}">
                            ${p.dif_cantidad > 0 ? '+' : ''}${p.dif_cantidad}
                        </td>
                        <td class="text-end fw-bold ${p.dif_precio > 0 ? 'text-danger' : (p.dif_precio < 0 ? 'text-warning' : 'text-muted')}">
                            ${p.dif_precio > 0 ? '+' : ''}$${dPrecio}
                        </td>
                        <td class="text-end fw-extrabold ${p.dif_total > 0 ? 'text-danger' : (p.dif_total < 0 ? 'text-success' : 'text-muted')}">
                            ${p.dif_total > 0 ? '+' : ''}$${dTotal}
                        </td>
                        <td class="pe-2 text-center">${badgeDictamen}</td>
                    </tr>`;
            });

            html += `
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end mt-4 pt-3 border-top border-light">
                <button type="button" class="btn btn-secondary me-2 rounded-3 fw-bold shadow-xs" data-bs-dismiss="modal">Cerrar</button>
                
                <button type="button" class="btn btn-danger me-2 rounded-3 fw-bold shadow-sm px-3" id="btn-exportar-pdf-modal">
                    <i class="fa-solid fa-file-pdf me-1"></i>Exportar PDF
                </button>
                <button type="button" class="btn btn-success text-white rounded-3 fw-bold shadow-xs px-4" id="btn-finalizar-conciliacion" data-factura="${response.factura_id}">
                    <i class="fa-solid fa-file-invoice-dollar me-1"></i>Conciliar factura
                </button>
            </div>`;

            $('#modal-cuerpo-auditoria').html(html);
        },
        error: function(xhr, status, error) {
            console.error('Error en auditoría:', xhr.responseText);
            $('#modal-cuerpo-auditoria').html(`
                <div class="alert alert-danger rounded-3 p-4 text-center border-0 shadow-xs m-3">
                    <i class="fa-solid fa-triangle-exclamation fa-3x text-danger mb-3"></i>
                    <h5 class="fw-bold text-dark">Falla en el Cruce de Datos</h5>
                    <p class="text-muted mb-0 small">Ocurrió un error al intentar contrastar las claves de herrajes en el servidor.</p>
                </div>
            `);
        }
    });
});
$(document).on('click', '#btn-exportar-pdf-modal', function() {
    let btn = $(this);
    if (!datosInstanciaConciliacion) {
        Swal.fire('Error', 'No hay datos cargados para generar un comprobante.', 'error');
        return;
    }

    console.log('aca estamos en el pdf', sucursal_activa);

    let originalHtml = btn.html();
    btn.prop('disabled', true).html(`<i class="fa-solid fa-spinner fa-spin me-1"></i> Generando archivo...`);

    try {
        const { jsPDF } = window.jspdf;
    
        const doc = new jsPDF('l', 'pt', 'letter');
        doc.setFillColor(255, 255, 255); 
        doc.rect(0, 0, 792, 65, 'F'); 

        const img1 = document.getElementById('logo'); 
        const img2 = document.getElementById('logo_frontera'); 

        if (img1 && img2) {
            const canvas1 = document.createElement('canvas');
            const canvas2 = document.createElement('canvas');
            canvas1.width = img1.naturalWidth;
            canvas2.width = img2.naturalWidth;
            canvas1.height = img1.naturalHeight;
            canvas2.height = img2.naturalHeight;
            
            const ctx1 = canvas1.getContext('2d');
            const ctx2 = canvas2.getContext('2d');
            ctx1.drawImage(img1, 0, 0);
            ctx2.drawImage(img2, 0, 0);
            
            const imgData1 = canvas1.toDataURL('image/png');
            const imgData2 = canvas2.toDataURL('image/png');

            doc.addImage(imgData2, 'PNG', 600, 20, 70, 35); 
            doc.addImage(imgData1, 'PNG', 700, 20, 70, 35); 
        }

        doc.setFont("helvetica", "bold");
        doc.setFontSize(16);
        doc.setTextColor(0, 0, 0); 
        doc.text("ANALISIS DE PRODUCTOS COTIZADOS VS FACTURAS", 40, 40);

        doc.setFont("helvetica", "normal");
        doc.setFontSize(9);
        doc.setTextColor(100); // gris
        doc.text(`# PRESUPUESTO: (${presupuestoActivoGlobal})`, 40, 55);
        doc.setFontSize(12);
        doc.text(`Sucursal del presupuesto: ${sucursal_activa}`, 40, 70);

        let resumen = datosInstanciaConciliacion.resumen;
        let partidas = datosInstanciaConciliacion.partidas;

        // --- BLOQUE 1: INDICADORES SUPERIORES ---
        doc.setFillColor(248, 249, 250); 
        doc.setDrawColor(226, 232, 240);
        doc.roundedRect(40, 85, 210, 55, 4, 4, 'FD');
        doc.setFont("helvetica", "bold");
        doc.setFontSize(8);
        doc.setTextColor(100, 116, 139); 
        doc.text("MONTO DE COTIZACIÓN", 52, 101);
        doc.setFontSize(13);
        doc.setTextColor(30, 41, 59); 
        doc.text(`$${resumen.total_cotizado.toLocaleString('es-MX', { minimumFractionDigits: 2 })}`, 52, 126);

        doc.setFillColor(248, 249, 250);
        doc.roundedRect(265, 85, 210, 55, 4, 4, 'FD');
        doc.setFont("helvetica", "bold");
        doc.setFontSize(8);
        doc.setTextColor(100, 116, 139);
        doc.text("MONTO DE FACTURADO", 277, 101);
        doc.setFontSize(13);
        doc.setTextColor(13, 110, 253);
        let totalFacturado = Number(resumen.total_facturado).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); 
        doc.text(`$${totalFacturado}`, 277, 126);

        let difGlobalValue = resumen.desviacion_global;
        let rBkg = 240, gBkg = 253, bBkg = 244; 
        let rBrd = 187, gBrd = 247, bBrd = 208;
        let rTxt = 21,  gTxt = 128, bTxt = 61;
        let lblAlerta = "IMPORTES TOTALES CUADRADOS";
        let signoMonto = "";

        if (difGlobalValue > 0.01) {
            rBkg = 254; gBkg = 242; bBkg = 242; 
            rBrd = 254; gBrd = 202; bBrd = 202;
            rTxt = 185; gTxt = 28;  bTxt = 28;
            lblAlerta = "ALERTA: COBRO DE MÁS EN FACTURACIÓN";
            signoMonto = "+";
        } else if (difGlobalValue < -0.01) {
            rBkg = 254; gBkg = 243; bBkg = 199; 
            rBrd = 252; gBrd = 211; bBrd = 77;
            rTxt = 180; gTxt = 83;  bTxt = 9;
            lblAlerta = "DIFERENCIA EN MENOS / PENDIENTE DE SURTIR";
        }

        doc.setFillColor(rBkg, gBkg, bBkg);
        doc.setDrawColor(rBrd, gBrd, rBrd);
        doc.roundedRect(490, 85, 262, 55, 4, 4, 'FD');
        
        doc.setFont("helvetica", "bold");
        doc.setFontSize(8);
        doc.setTextColor(rTxt, gTxt, bTxt);
        doc.text(lblAlerta, 502, 101);
        doc.setFontSize(13);
        doc.text(`${signoMonto}$${difGlobalValue.toLocaleString('es-MX', { minimumFractionDigits: 2 })}`, 502, 126);

        // --- NUEVO BLOQUE: COMPROBANTES FACTURADOS VINCULADOS (MDOO PRO) ---
        let tablaStartY = 160; // Posición base de respaldo
        
        if (typeof facturasPresupuesto !== 'undefined' && facturasPresupuesto.length > 0) {
            doc.setFont("helvetica", "bold");
            doc.setFontSize(9);
            doc.setTextColor(30, 41, 59); // Slate 800
            doc.text(`FACTURAS LIGADAS AL PRESUPUESTO (${facturasPresupuesto.length})`, 40, 160);

            // Construimos las columnas y filas de la mini-tabla de facturas, VAMOS A CENTRARLO
            const headersFacturas = [['Serie / Folio', 'UUID (Folio Fiscal Completo)', 'Monto Facturado', 'Origen del Reporte']];
            const filasFacturas = [];

            $.each(facturasPresupuesto, function(i, f) {
                let esActual = (f.id == facturaId);
                let labelFolio = f.serie_folio ? f.serie_folio : 'Sin Folio (S/F)';
                let labelUuid = f.uuid ? f.uuid.toUpperCase() : 'SIN COMPROBANTE DIGITAL / EMISIÓN DIRECTA';
                let montoF = `$${Number(f.total).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                let estatusTexto = esActual ? 'EN REVISION' : 'CARGADOS A ESTE PRESUPUESTO';

                filasFacturas.push([labelFolio, labelUuid, montoF, estatusTexto]);
            });

            // Renderizamos la mini-tabla de forma compacta e impecable
            doc.autoTable({
                startY: 170,
                head: [
                    [
                        { content: 'Serie / Folio', styles: { halign: 'center', fontStyle: 'bold' } },
                        { content: 'UUID (Folio Fiscal Completo)', styles: { halign: 'center', fontStyle: 'bold' } },
                        { content: 'Monto Facturado', styles: { halign: 'center', fontStyle: 'bold' } },
                        { content: 'Origen del Reporte', styles: { halign: 'center', fontStyle: 'bold' } }
                    ]
                ],
                body: filasFacturas,
                theme: 'grid',
                styles: { haling:'center', fontSize: 8, cellPadding: 4, drawColor: [226, 232, 240] },
                columnStyles: {
                    0: { cellWidth: 124, halign: 'center',fontStyle: 'bold' },
                    1: { cellWidth: 304,halign: 'center', },
                    2: { cellWidth: 115, halign: 'center', fontStyle: 'bold' },
                    3: { cellWidth: 168, halign: 'center' }
                },
                headStyles: { fillColor: [71, 85, 105], textColor: [255, 255, 255], fontStyle: 'bold' },
                didParseCell: function(data) {
                    // Si es la fila de la factura actual en pantalla, le damos un toque sutil
                    if (data.section === 'body') {
                        let esFilaActual = data.row.raw[3].includes('ACTUANDO');
                        if (esFilaActual) {
                            data.cell.styles.fillColor = [240, 246, 255]; // Azul tenue de enfoque
                            if (data.column.index === 3) {
                                data.cell.styles.textColor = [13, 110, 253]; // Texto azul dinámico
                                data.cell.styles.fontStyle = 'bold';
                            }
                        }
                    }
                }
            });

            // El truco maestro: la tabla principal iniciará exactamente 20 puntos abajo de donde termine esta
            tablaStartY = doc.lastAutoTable.finalY + 20;
        }

        // --- CONSTRUCCIÓN DE AUTOTABLE ---
        const lasCabeceras = [
            [
                { content: 'Clave / Descripcion', rowSpan: 2, styles: { valign: 'middle', halign: 'left' } },
                { content: '1. Cotizado', colSpan: 2, styles: { halign: 'center', fillColor: [71, 85, 105] } },
                { content: '2. Facturado', colSpan: 2, styles: { halign: 'center', fillColor: [26, 54, 93] } },
                { content: '3. Desviaciones', colSpan: 3, styles: { halign: 'center', fillColor: [33, 37, 41] } },
                { content: 'Dictamen', rowSpan: 2, styles: { valign: 'middle', halign: 'center' } }
            ],
            [
                'Cant', 'Precio', 
                'Cant', 'Precio', 
                'Cant', 'Precio', 'Importe'
            ]
        ];

        const lasFilas = [];
        
        $.each(partidas, function(index, p) {
            let textoDictamen = "";
            switch(p.estatus) {
                case 'PRECIO_INFLADO': textoDictamen = 'PRECIO ALTO'; break;
                case 'CANTIDAD_EXCEDIDA': textoDictamen = 'PIEZAS DE MÁS'; break;
                case 'EXCEDENTE_PRODUCTO': textoDictamen = 'NO COTIZADO'; break;
                case 'SURTIDO_INCOMPLETO': textoDictamen = 'INCOMPLETO'; break;
                case 'NO_FACTURADO': textoDictamen = 'NO SURTIDO'; break;
                default: textoDictamen = 'CORRECTO';
            }

            lasFilas.push([
                { content: `${p.clave}\n${p.descripcion}`, rawEstatus: p.estatus, difCant: p.dif_cantidad, difPrec: p.dif_precio, difTot: p.dif_total },
                { content: Number(p.cant_cotizada).toFixed(2), rawEstatus: p.estatus },
                { content: `$${Number(p.precio_cotizado).toLocaleString('es-MX', { minimumFractionDigits: 2 })}`, rawEstatus: p.estatus },
                { content: Number(p.cant_facturada).toFixed(2), rawEstatus: p.estatus },
                { content: `$${Number(p.precio_facturado).toLocaleString('es-MX', { minimumFractionDigits: 2 })}`, rawEstatus: p.estatus },
                { content: `${p.dif_cantidad > 0 ? '+' : ''}${p.dif_cantidad}`, rawEstatus: p.estatus, difCant: p.dif_cantidad },
                { content: `${p.dif_precio > 0 ? '+' : ''}$${p.dif_precio.toLocaleString('es-MX', { minimumFractionDigits: 2 })}`, rawEstatus: p.estatus, difPrec: p.dif_precio },
                { content: `${p.dif_total > 0 ? '+' : ''}$${p.dif_total.toLocaleString('es-MX', { minimumFractionDigits: 2 })}`, rawEstatus: p.estatus, difTot: p.dif_total },
                { content: textoDictamen, rawEstatus: p.estatus }
            ]); 
        }); 
        
        doc.autoTable({
            startY: tablaStartY, // Usa el inicio calculado dinámicamente
            head: lasCabeceras,
            body: lasFilas,
            theme: 'plain', 
            styles: { fontSize: 7.5, cellPadding: 5, drawColor: [226, 232, 240], lineWidth: 0.5 },
            columnStyles: {
                0: { cellWidth: 220, halign: 'left' },
                1: { halign: 'center' }, 2: { halign: 'right' },
                3: { halign: 'center' }, 4: { halign: 'right' },
                5: { halign: 'center', fontStyle: 'bold' }, 
                6: { halign: 'right', fontStyle: 'bold' },
                7: { halign: 'right', fontStyle: 'bold' },
                8: { halign: 'center', fontStyle: 'bold' }
            },
            headStyles: { fillColor: [30, 41, 59], textColor: [255, 255, 255], fontStyle: 'bold', fontSize: 8 },
        
            didParseCell: function(data) {
                if (data.section === 'body') {
                    let cellData = data.cell.raw;
                    let rawEst = cellData.rawEstatus;

                    if (rawEst === 'PRECIO_INFLADO' || rawEst === 'CANTIDAD_EXCEDIDA') {
                        data.cell.styles.fillColor = [254, 242, 242]; 
                    } else if (rawEst === 'SURTIDO_INCOMPLETO' || rawEst === 'EXCEDENTE_PRODUCTO') {
                        data.cell.styles.fillColor = [255, 251, 235]; 
                    } else if (rawEst === 'NO_FACTURADO') {
                        data.cell.styles.fillColor = [248, 249, 250];
                        data.cell.styles.textColor = [148, 163, 184]; 
                    } else {
                        data.cell.styles.fillColor = (data.row.index % 2 === 0) ? [255, 255, 255] : [248, 250, 252];
                    }

                    if (rawEst !== 'NO_FACTURADO') {
                        if (data.column.index === 5) {
                            let difCant = cellData.difCant;
                            if (difCant > 0) data.cell.styles.textColor = [220, 38, 38]; 
                            else if (difCant < 0) data.cell.styles.textColor = [217, 119, 6]; 
                        }
                        if (data.column.index === 6) {
                            let difPrec = cellData.difPrec;
                            if (difPrec > 0) data.cell.styles.textColor = [220, 38, 38];
                        }
                        if (data.column.index === 7) {
                            let difTot = cellData.difTot;
                            if (difTot > 0) data.cell.styles.textColor = [220, 38, 38]; 
                            else if (difTot < 0) data.cell.styles.textColor = [217, 119, 6]; 
                        }
                    }
                    if (data.column.index === 8) {
                        if (rawEst === 'PRECIO_INFLADO' || rawEst === 'CANTIDAD_EXCEDIDA') {
                            data.cell.styles.textColor = [153, 27, 27];
                        } else if (rawEst === 'SURTIDO_INCOMPLETO' || rawEst === 'EXCEDENTE_PRODUCTO') {
                            data.cell.styles.textColor = [146, 64, 14];
                        } else if (rawEst === 'CORRECTO') {
                            data.cell.styles.textColor = [22, 163, 74];
                        }
                    }
                }
            }
        });

        const pageCount = doc.getNumberOfPages();
        for(let i = 1; i <= pageCount; i++){
            doc.setPage(i);
            doc.setTextColor(150);
            const disclaimer = "Los productos mostrados han sido analizados y conciliados con sus respectivas facturas mediante (XML), el sistema puede cometer errores previos a la conciliacion.";
            doc.setFontSize(8);
            doc.text(disclaimer, 39, doc.internal.pageSize.getHeight() - 15)
            doc.setFontSize(10);
            doc.setTextColor(150);
            doc.text(`Página ${i} de ${pageCount}`, doc.internal.pageSize.getWidth() - 80, doc.internal.pageSize.getHeight() - 15);
        } 

        doc.save(`PRESUPUESTO_ANALIZADO_${presupuestoActivoGlobal}.pdf`);

    } catch (err) {
        console.error("Falla crítica al armar AutoTable jsPDF: ", err);
        Swal.fire('Error', 'Ocurrió un error inesperado al procesar la conversión del PDF.', 'error');
    } finally {
        btn.prop('disabled', false).html(originalHtml);
    }
});

        $(document).on('click', '#btn-finalizar-conciliacion', function() {
            let btn = $(this);
            let facturaId = btn.data('factura');
            let presupuesto = $(this).data('presupuesto');
            
            btn.prop('disabled', true).html(`
                <i class="fa-solid fa-spinner fa-spin me-1"></i> Guardando...
            `);
        
            console.log('Enviando confirmación de conciliación para Factura ID: ' + facturaId);
        
            $.ajax({
                url: "/conciliacion/facturas/guardar", 
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', 
                    factura_id: facturaId,
                    presupuesto_folio: presupuesto

                },
                success: function(response) {
                    if (response.success) {
                        // Cerramos el modal de auditoría de forma limpia
                        var modalElement = document.getElementById('modalResultadosConciliacion');
                        var modalAuditoria = bootstrap.Modal.getInstance(modalElement);
                        if (modalAuditoria) {
                            modalAuditoria.hide();
                        }
                        Swal.fire({
                                title: '¡Datos Guardado!',
                                text: response.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                        });
        
                        cargarBandejaAuditoria();
                    } else {
                        alert('Atención: ' + response.message);
                        btn.prop('disabled', false).html(`
                            <i class="fa-solid fa-file-invoice-dollar me-1"></i>Conciliar factura
                        `);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al guardar conciliación:', xhr.responseText);
                    alert('Ocurrió un error crítico en el servidor al intentar registrar los saldos de la factura.');
                    btn.prop('disabled', false).html(`
                        <i class="fa-solid fa-file-invoice-dollar me-1"></i>Conciliar factura
                    `);
                }
            });
        });

        function formatearFecha(stringFecha) {
            if(!stringFecha) return '---';
            
            let soloFecha = stringFecha.split(' ')[0]; 
            
            let f = soloFecha.split('-');
            return f.length === 3 ? `${f[2]}/${f[1]}/${f[0]}` : stringFecha;
        }

        function formatearFechaHora(stringFechaHora) {
            if(!stringFechaHora) return '---';
            let t = stringFechaHora.split(' ');
            if(t.length >= 1) {
                let f = t[0].split('-');
                let hora = t[1] ? t[1].substring(0, 5) : '';
                return f.length === 3 ? `${f[2]}/${f[1]}/${f[0]} ${hora}` : stringFechaHora;
            }
            return stringFechaHora;
        }
        // Delegación de eventos: Escucha siempre, sin importar si la tabla se redibuja por AJAX
        $(document).on('click', '.link-presupuesto', function(e) {
            e.stopPropagation();
            e.preventDefault();
            // Recuperamos el ID directamente del atributo data-id
            const id_cotizacion = $(this).data('id');
            const folio = $(this).data('presupuesto');

            console.log('El usuario quiere generar el PDF del presupuesto:', folio, '(ID:', id_cotizacion + ')');

            fetch('/ver-pdf-cotizacion/' + id_cotizacion).then(response => response.json()).then(data => {
                console.log('Datos recibidos para el PDF de la cotización:', data);
                verPDF(data.productos,data.cotizacion);
            }).catch(error => {
                console.error('Error al recuperar datos del PDF:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error al recuperar PDF',
                    text: 'Hubo un problema al recuperar el PDF. Inténtalo de nuevo. Detalle: ' + error.message,
                    confirmButtonText: 'Aceptar',
                });
            });
            
            function verPDF(productos,generales){
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF({orientation: 'l'});
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
                doc.setFontSize(16);
                doc.setTextColor(0,0,0);
                doc.setFont("helvetica", "bold");
                // TÍTULO PRINCIPAL
                const nombre_coti = "ANALISIS DE APROBACION DE PRODUCTOS (AAP)";
                doc.text(nombre_coti.toUpperCase(), 80,20,{aling:'center'});
                doc.setFontSize(14);
                doc.setFont("helvetica");
                doc.setTextColor(100); // gris
                doc.text(generales.nombre_real.toUpperCase(), 15,32,{aling:'center'});
                //datos generales de la cotizacion
                doc.setFontSize(11);
                doc.setFont("helvetica");
                doc.setTextColor(100); // gris
                doc.text("N° cotizacion: " + generales.presupuesto, 15,40);
                doc.text("Fecha cotizacion: " + generales.fecha_cotizacion,15,45);
                doc.text("Proveedor: HERRALUM INDUSTRIAL S.A DE C.V.", 15, 50);
                doc.text("Vendedor: " + generales.vendedor, 200, 40);
                //pesos mx al total de la cotizacion
                const consto_coti_previa = new Intl.NumberFormat('es-MX', {
                    style:'currency',
                    currency: 'MXN',
                }).format(generales.total_cotizacion);
                doc.text("Total cotizacion: " + consto_coti_previa, 200,45);
                //generrando los encabezados de la tabla
                const columns = [
                    { header: 'Codigo', dataKey: 'codigo', cellWidth:15},
                    { header: 'Producto', dataKey: 'producto', cellWidth: 50},
                    { header: 'U.M.', dataKey: 'um', cellWidth: 8},
                    { header: 'Ventas 6m', dataKey: 'ventas_6m', cellWidth: 15},
                    { header: 'Prom. Mens.', dataKey: 'promedio_mensual', cellWidth: 15},
                    { header: 'Stock Total', dataKey: 'stock_total', cellWidth: 15},
                    { header: 'Sug. Compra', dataKey: 'sugerencia_comprar', cellWidth: 15},
                    { header: 'Existencia Sucursal', dataKey: 'existencias', cellWidth: 20},
                    { header: 'P. Lista.', dataKey: 'precio_lista', cellWidth: 20},
                    { header: 'Prom. Act.', dataKey: 'precio_promocion', cellWidth: 20},
                    { header: 'Desc (%)', dataKey: 'descuento', cellWidth: 20},
                    { header: 'P. Cliente', dataKey: 'precio_cliente', cellWidth: 20},
                    { header: 'Cant. Cot.', dataKey: 'cantidad_cotizada', cellWidth: 20},
                    { header: 'Importe', dataKey: 'importe', cellWidth: 18 },
                    { header: 'Acción', dataKey: 'accion', cellWidth: 15 }
                ];
                
                const producto = productos.map(item => {
                let existenciasTexto = '';

                if (item.existencia_en_sucur) {
                    try {
                        // Convertimos el string JSON a un objeto real
                        const sucursales = JSON.parse(item.existencia_en_sucur);
                        // Accedemos a las propiedades del objeto parseado
                        if (sucursales["1"] !== undefined) {
                            existenciasTexto += `Suc 1: ${parseFloat(sucursales["1"]).toFixed(2)}\n`;
                        }if (sucursales["2"] !== undefined) {
                            existenciasTexto += `Suc 2: ${parseFloat(sucursales["2"]).toFixed(2)}`;
                        }
                    } catch (e) {
                        console.error("Error parseando existencia_en_sucur", e);
                            existenciasTexto = 'Error de formato';
                        }
                    }
                return {
                    codigo: item.codigo,
                    producto: item.nombre,
                    um: item.um,
                    ventas_6m: item.vendido_anteriormente,
                    // Usamos || 0 para evitar errores si el campo viene vacío
                    promedio_mensual: parseFloat(item.promedio_mensual_ventas || 0).toFixed(2),
                    stock_total: item.stock_total,
                    sugerencia_comprar: item.sugerencia_pedido,
                    existencias: existenciasTexto.trim() || 'SIN DATOS',
                    precio_lista: new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2 }).format(parseFloat(item.precio_lista || 0)),
                    precio_promocion: new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2 }).format(parseFloat(item.precio_promocion || 0)),
                    descuento: parseFloat(item.descuento || 0).toFixed(2),
                    precio_cliente: new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2 }).format(parseFloat(item.precio_cliente || 0)),
                    cantidad_cotizada: parseFloat(item.cant_a_pedir || 0).toFixed(2),
                    importe: new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2 }).format(parseFloat(item.importe || 0)),
                    accion: item.analisi_obtenido ? item.analisi_obtenido : 'N/A'
                    };
                });                          
              // 3. Estilos y aplicación de la tabla
                  doc.autoTable(columns, producto, {
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
                    3: { halign: 'right' }, // Ventas 6m
                    4: { halign: 'right' }, // Prom. Mensual
                    5: { halign: 'right', fontStyle: 'bold' }, // Stock Total
                    7: { fontStyle: 'normal' }, // Existencia En Suc (Texto multilínea)
                    8: { halign: 'right' }, // P. Lista
                    9: { halign: 'right' }, // P. Promo
                    10: { halign: 'right' }, // Descuento
                    11: { halign: 'right' }, // P. Cliente
                    12: { halign: 'right' }, // Cant. Cot.
                    13: { halign: 'right', fontStyle: 'bold' }, // Importe
                    14: { halign: 'center', textColor: [255, 255, 255],             fontStyle: 'bold' } // Acción (Centrada, texto blanco)
                     },
                       didParseCell: (data) => {
                       if (data.column.index === 3) {
                         data.cell.styles.fillColor = [79, 195, 247]; // Azul/Celeste 
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
                               
                     data.cell.styles.fillColor = [244, 67, 54]; 
                     data.cell.styles.textColor = [255, 255, 255];
                        }
                     }
                   }
                 });
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
                const nombrepdf = generales.presupuesto + '_cotizacion.pdf';        
                const blobURL = doc.output('bloburl');
                    
                Swal.fire({
                        title: `Cotización: ${generales.presupuesto}`,
                        html: `<iframe src="${blobURL}" style="width:100%; height:70vh;" frameborder="0"></iframe>`,
                        width: '90%',
                        confirmButtonText: 'Cerrar',
                        customClass: {
                        container: 'my-swal-pdf'
                    }
                });
            }
        });
    });
</script>

@endsection