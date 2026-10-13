@extends('layout.index')
@section('content')
<main class="main-content">
    <br><br><br><br>
    <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row mb-4 animate__animated animate__fadeIn">
            <div class="col-12">
                <div class="d-flex align-items-center bg-white p-4 rounded-4 shadow-xs border border-light">
                    <div class="icon-box-premium text-primary me-3 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-file-invoice" style="font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <h4 class="fw-extrabold mb-1 text-dark tracking-tight">Carga de facturas (XML)</h4>
                        <p class="text-muted mb-0 small fw-medium">Cargas tus archivos XML de la factura para conciliarla con el presupuesto.</p>
                    </div>
                </div>
            </div>
        </div>
        @include('cotizaciones_herrajes.navConciliacionFactura')
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 animate__animated animate__fadeInUp">
            <div class="card-body p-4 bg-white">
                <form id="form_comparar" action="" method="POST" enctype="multipart/form-data">
                    @csrf         
                    <input type="hidden" id="rfc" name="rfc">
                    <input type="hidden" id="razon_social" name="nombre">
                    <input type="hidden" id="fecha_timbrado" name="fecha_timbrado">
                    <input type="hidden" id="uuid" name="uuid">
                    <input type="hidden" id="serie_folio" name="serie_folio">
                    <input type="hidden" id="subtotal" name="subtotal">
                    <input type="hidden" id="descuento" name="descuento">
                    <input type="hidden" id="neto" name="neto">
                    <input type="hidden" id="iva" name="iva">
                    <input type="hidden" id="total" name="total">    
                    <div class="row g-4">
                      <!-- PASO 1: IDENTIFICACIÓN -->
                        <div class="col-lg-4 border-end-lg">
                            <div class="p-2">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center">
                                        <span class="custom-indicator-line me-2"></span>
                                        <h5 class="fw-bold text-dark mb-0">Paso 1: Identificación</h5>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label id="label_presupuesto" for="codigo_presupuesto" class="form-label-custom">
                                        <span class="badge-step bg-primary">1</span> Folio de Presupuesto
                                    </label>
                                    <div class="input-group input-group-custom">
                                        <span class="input-group-text bg-light border-end-0 text-muted" id="prefix_q">
                                            <h3 style="font-size: 20px; margin-bottom: 0;">Q-</h3>
                                        </span>
                                        <input type="text" 
                                            class="form-control border-start-0 text-dark fw-bold placeholder-glow uppercase-input" 
                                            id="codigo_presupuesto" 
                                            name="codigo_presupuesto" 
                                            placeholder="182039" 
                                            value="{{ old('codigo_presupuesto', $presupuesto ?? '') }}" 
                                            required autocomplete="off">
                                    </div>
                                    <div id="feedback_presupuesto" class="form-feedback-text mt-2">
                                        <span class="text-muted" id="txt_feedback"><i class="bi bi-info-circle-fill me-1"></i>Ingrese el folio para activar la carga.</span>
                                    </div>
                                    <!-- Switch para habilitar carga sin presupuesto -->
                                <div class="form-check form-switch mb-3 bg-light p-2 ps-5 rounded-3 border">
                                    <input class="form-check-input" type="checkbox" id="check_sin_presupuesto" style="cursor: pointer;">
                                    <label class="form-check-label fw-bold text-dark small" for="check_sin_presupuesto" style="cursor: pointer;">
                                        Activar si no hay presupuesto ligado
                                    </label>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8" id="div_xml" style="display: none;">
                            <div class="p-2">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="custom-indicator-line bg-primary me-2"></span>
                                    <h5 class="fw-bold text-dark mb-0">Paso 2: Comprobante Fiscal</h5>
                                </div>
                                <label id="label_xml" class="form-label-custom mb-2">
                                    <span class="badge-step bg-primary" id="xml_badge">2</span> Archivo Digital XML
                                </label>                        
                                <div class="xml-drop-zone-large rounded-4 p-4 d-flex flex-column align-items-center justify-content-center position-relative transition-all mb-3 text-center">
                                    <input type="file" 
                                           class="position-absolute top-0 start-0 w-100 h-100 opacity-0" 
                                           id="xml_file" 
                                           name="xml_file" 
                                           accept=".xml" 
                                           disabled required style="cursor: pointer; z-index: 10;">
                                    <div class="icon-box-large bg-light text-primary rounded-circle mb-3 d-flex align-items-center justify-content-center" id="xml_icon_container">
                                        <i id="xml_icon" class="fa-solid fa-download" style="font-size: 2rem;"></i>
                                    </div>                                   
                                    <h6 id="xml_filename" class="fw-extrabold text-dark mb-1">Arrastra tu archivo XML aquí o haz clic para buscar</h6>
                                    <p id="xml_filesub" class="text-muted small mb-0">Solo se admiten archivos CFDI (.xml) válidos ante el SAT</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="preview_xml_container" class="mt-4 pt-3 border-top" style="display: none;">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-list-columns text-success fs-4 me-2"></i>
                                <h6 class="fw-bold text-dark mb-0">Vista previa de productos de la factura</h6>
                            </div>
                            <span class="badge bg-success-light text-success fw-bold px-3 py-1.5 rounded-pill" id="preview_invoice_total">$0.00 MXN</span>
                        </div>
                        <div class="table-responsive rounded-3 border border-light mb-3">
                            <table class="table table-sm align-middle mb-0 text-start" id="table_preview_xml">
                                <thead class="table-light text-secondary fs-7 fw-bold uppercase">
                                    <tr>
                                        <th class="ps-3 py-2">Clave/Código</th>
                                        <th class="py-2">Descripción del Producto/Servicio</th>
                                        <th class="text-center py-2">Cantidad</th>
                                        <th class="text-center py-2">UM</th>
                                        <th class="text-end py-2">P. Unitario</th>
                                        <th class="text-end pe-3 py-2">Importe</th>
                                        <th class="text-end py-2">Descuento</th>
                                        <th class="text-end py-2">%</th>
                                        <th class="text-end py-2">Total</th>
                                        <th class="text-end py-2">IVA</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <button type="button" id="btn_comparar" class="btn btn-premium-action shadow-sm px-5" disabled>
                                <i class="bi bi-lightning-charge-fill me-2"></i>Guardar productos
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Declaración de variables existentes
    const inputPresupuesto = $('#codigo_presupuesto');
    const divXml = $('#div_xml');
    const xmlFile = $('#xml_file');
    const btnComparar = $('#btn_comparar');
    const feedback = $('#feedback_presupuesto');
    const form = $('#form_comparar');
    
    // Switch "Sin Presupuesto" y Prefix
    const checkSinPresupuesto = $('#check_sin_presupuesto');
    const prefixQ = $('#prefix_q');
    
    // Campos para dar click y subir el xml
    const xmlDropZone = document.querySelector('.xml-drop-zone-large');
    if (xmlDropZone) {
        xmlDropZone.addEventListener('click', () => {
            if (!xmlFile.is(':disabled')) {
                xmlFile.click();
            }
        });
    }

    const previewContainer = $('#preview_xml_container');
    const tablePreviewBody = $('#table_preview_xml tbody');
    const previewTotal = $('#preview_invoice_total');

    let timeout = null;
    let idCotizacionFacturadaGlobal = null;

    // ==========================================
    // 1. MANEJO DEL SWITCH "SIN PRESUPUESTO"
    // ==========================================
    checkSinPresupuesto.on('change', function() {
        if ($(this).is(':checked')) {
            // Deshabilitar input y limpiar timers
            clearTimeout(timeout);
            inputPresupuesto.prop('disabled', true).val('').addClass('bg-light');
            prefixQ.addClass('bg-secondary text-white-50');

            // Feedback visual
            feedback.html('<span class="text-success fw-semibold"><i class="bi bi-check-circle-fill me-1"></i> Modo directo (Sin Presupuesto) activo.</span>');

            // Habilitar la zona de carga de XML inmediatamente
            xmlFile.removeAttr('disabled');
            divXml.removeClass('animate__fadeOut').addClass('animate__animated animate__fadeIn').show();
            $('#xml_badge').removeClass('bg-secondary').addClass('bg-primary');

            // Definir action genérico por si se usa submit por formulario
            form.attr('action', '/comparar/xml/DIRECTO');

        } else {
            // Restaurar input
            inputPresupuesto.prop('disabled', false).removeClass('bg-light');
            prefixQ.removeClass('bg-secondary text-white-50');
            
            // Resetear estado del XML hasta que digite un presupuesto válido
            resetearCamposXml();
            feedback.html('<span class="text-muted"><i class="bi bi-info-circle-fill me-1"></i> Ingrese el folio para activar la carga.</span>');
        }
    });

    inputPresupuesto.on('input', function() {
       
        if (checkSinPresupuesto.is(':checked')) return;

        clearTimeout(timeout);
        const presupuesto = $(this).val().trim();
        const presupuesto_num = 'Q-' + presupuesto;

        if (presupuesto.length >= 3) {
            feedback.html('<span class="text-primary fw-semibold"><i class="bi bi-arrow-repeat animate-spin me-1"></i> Validando folio...</span>');
            
            timeout = setTimeout(function() {
                $.ajax({
                    url: '/verifica/presupuesto/true/' + encodeURIComponent(presupuesto_num),
                    method: 'GET',
                    success: function(response) {
                        if(response.success){
                            feedback.html('<span class="text-success fw-semibold"><i class="bi bi-check-circle-fill me-1"></i> Presupuesto verificado en el sistema.</span>');
                            xmlFile.removeAttr('disabled');
                            divXml.removeClass('animate__fadeOut').addClass('animate__animated animate__fadeIn').show();
                            $('#xml_badge').removeClass('bg-secondary').addClass('bg-primary');
                            
                            form.attr('action', '/comparar/xml/' + encodeURIComponent(presupuesto_num));
                        }
                    },
                    error: function(xhr) {
                        resetearCamposXml();
                        if(xhr.status === 404) {
                            feedback.html('<span class="text-danger fw-semibold"><i class="bi bi-x-circle-fill me-1"></i> El presupuesto no existe en nuestros registros.</span>');
                        } else {
                            feedback.html('<span class="text-danger fw-semibold"><i class="bi bi-exclamation-triangle-fill me-1"></i> Error de servidor.</span>');
                        }
                    }
                });
            }, 350);
        } else {
            resetearCamposXml();
            feedback.html('<span class="text-muted"><i class="bi bi-info-circle-fill me-1"></i> Ingrese el folio para activar la carga.</span>');
        }
    });

    xmlFile.on('change', function(e) {
        if (this.files.length > 0) {
            const file = this.files[0];
            
            $('.xml-drop-zone-large').addClass('border-success active-success');
            $('#xml_icon_container').removeClass('bg-light text-primary').addClass('bg-success-light text-success');
            $('#xml_icon').removeClass('bi-cloud-arrow-up').addClass('bi-filetype-xml text-success');
            $('#xml_filename').html('<span class="text-success fw-extrabold">' + file.name + '</span>');
            $('#xml_filesub').text((file.size / 1024).toFixed(2) + ' KB - Archivo cargado localmente.');
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const xmlText = e.target.result;
                procesarPreviewXML(xmlText);
            };
            reader.readAsText(file);
            
        } else {
            inyectarEstadoInicialXml();
        }
    });
    function procesarPreviewXML(xmlString) {
        try {
            const parser = new DOMParser();
            const xmlDoc = parser.parseFromString(xmlString, "text/xml");
            
            const conceptos = xmlDoc.getElementsByTagName("cfdi:Concepto");
            const comprobante = xmlDoc.getElementsByTagName("cfdi:Comprobante")[0];
            const cfdi_comprobantes = xmlDoc.getElementsByTagName("cfdi:Comprobante");
            const cfdi_emisor = xmlDoc.getElementsByTagName("cfdi:Emisor")[0];
            const cfdi_complementos = xmlDoc.getElementsByTagName("cfdi:Complemento");

            const nodoImpuestosGlobal = xmlDoc.querySelector("Comprobante > Impuestos, cfdi\\:Comprobante > cfdi\\:Impuestos");
            const cfdi_traslado_global = nodoImpuestosGlobal?.querySelector("Traslado, cfdi\\:Traslado");

            const baseGeneralNeto = cfdi_traslado_global?.getAttribute("Base") || '0.00';
            const importeIvaGlobal = cfdi_traslado_global?.getAttribute("Importe") || '0.00';
            
            const serie = cfdi_comprobantes.length > 0 ? cfdi_comprobantes[0].getAttribute("Serie") || '' : '';
            const folio = cfdi_comprobantes.length > 0 ? cfdi_comprobantes[0].getAttribute("Folio") || '' : '';
            let folio_serie = (serie || folio) ? `${serie}-${folio}`.replace(/^-|-$/, '') : 'N/A';

            if (!comprobante) {
                throw new Error("Estructura XML inválida (No se encontró nodo Comprobante).");
            }

            const rfc = cfdi_emisor ? cfdi_emisor.getAttribute("Rfc") : 'N/A';
            const razon_social = cfdi_emisor ? cfdi_emisor.getAttribute("Nombre") : 'N/A';
            const fecha_timbrado = cfdi_comprobantes.length > 0 ? cfdi_comprobantes[0].getAttribute("Fecha") : 'N/A';
            
            let uuid = 'N/A';
            if (cfdi_complementos.length > 0) {
                const tfd = cfdi_complementos[0].getElementsByTagName("tfd:TimbreFiscalDigital")[0];
                if (tfd) uuid = tfd.getAttribute("UUID");
            }

            const subtotal = cfdi_comprobantes.length > 0 ? cfdi_comprobantes[0].getAttribute("SubTotal") : '0';
            const descuento = cfdi_comprobantes.length > 0 ? (cfdi_comprobantes[0].getAttribute("Descuento") || '0') : '0';
            const total = cfdi_comprobantes.length > 0 ? cfdi_comprobantes[0].getAttribute("Total") : '0';

            // Asignación a inputs del DOM
            $('#rfc').val(rfc);
            $('#razon_social').val(razon_social);
            $('#fecha_timbrado').val(fecha_timbrado);
            $('#uuid').val(uuid);
            $('#serie_folio').val(folio_serie);
            $('#subtotal').val(subtotal);
            $('#neto').val(baseGeneralNeto);
            $('#iva').val(importeIvaGlobal);
            $('#descuento').val(descuento);
            $('#total').val(total);

            Swal.fire({
                title: '<i class="fa-solid fa-shield-check text-success me-2"></i>Datos generales de la factura',
                html: `
                    <div class="p-2 border border-light rounded-3 bg-light text-start shadow-inner" style="font-size: 0.85rem; line-height: 1.6;">
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-2">
                            <span class="text-muted fw-bold">PROVEEDOR:</span>
                            <span class="text-dark fw-extrabold text-end" style="max-width: 200px;">${razon_social}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-2">
                            <span class="text-muted fw-bold">RFC EMISOR:</span>
                            <span class="text-primary fw-bold">${rfc}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-2">
                            <span class="text-muted fw-bold">FECHA EMISIÓN:</span>
                            <span class="text-dark fw-medium">${fecha_timbrado}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-2">
                            <span class="text-muted fw-bold">UUID FISCAL:</span>
                            <span class="text-secondary small fw-bold" style="font-size: 11px;">${uuid}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-2">
                            <span class="text-muted fw-bold">SERIE Y FOLIO:</span>
                            <span class="text-secondary small fw-bold" style="font-size: 12px;">${folio_serie}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                            <span class="text-muted font-weight-bold">SUBTOTAL:</span>
                            <span class="text-dark font-weight-bold">$${parseFloat(subtotal).toLocaleString('es-MX', {minimumFractionDigits: 2})}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-1 text-danger">
                            <span>DESCUENTO:</span>
                            <span>-$${parseFloat(descuento).toLocaleString('es-MX', {minimumFractionDigits: 2})}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-1 text-primary">
                            <span>NETO:</span>
                            <span>$${parseFloat(baseGeneralNeto).toLocaleString('es-MX', {minimumFractionDigits: 2})}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-1 mb-1 text-info">
                            <span>IVA:</span>
                            <span>$${parseFloat(importeIvaGlobal).toLocaleString('es-MX', {minimumFractionDigits: 2})}</span>
                        </div>
                        <div class="d-flex justify-content-between pt-2 mt-2 border-top border-2">
                            <span class="text-dark fw-extrabold fs-6">MONTO TOTAL CFDI:</span>
                            <span class="text-success fw-extrabold fs-6">$${parseFloat(total).toLocaleString('es-MX', {minimumFractionDigits: 2})} MXN</span>
                        </div>
                    </div>
                    <p class="text-muted small text-center mt-3 mb-0">¿Los datos generales de la cabecera son correctos?</p>
                `,
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa-solid fa-floppy-disk me-1"></i> Guardar y Continuar',
                cancelButtonText: 'Cancelar',
                allowOutsideClick: false,
                customClass: {
                    popup: 'rounded-4 shadow',
                    title: 'fw-extrabold tracking-tight text-dark pt-3',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Registrando los datos generales...',
                        text: 'Guardando la información de la factura en el sistema.',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });

                    // DETERMINAR QUÉ VALOR ENVIAR EN EL PRESUPUESTO
                    let valorPresupuestoEnviar = 'SIN_PRESUPUESTO';
                    if (!checkSinPresupuesto.is(':checked')) {
                        valorPresupuestoEnviar = 'Q-' + inputPresupuesto.val().trim();
                    }

                    // GUARDADO DE DATOS GENERALES VIA AJAX
                    $.ajax({
                        url: "{{ route('conciliacion.guardar.facturas.generales') }}",
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            presupuesto: valorPresupuestoEnviar,
                            rfc: rfc,
                            nombre: razon_social,
                            fecha_timbrado: fecha_timbrado,
                            uuid: uuid,
                            serie_folio: folio_serie,
                            descuento: descuento,
                            subtotal: subtotal, 
                            neto: baseGeneralNeto,
                            iva: importeIvaGlobal,
                            total: total
                        },
                        success: function(response) {
                            if(response.success) {
                                idCotizacionFacturadaGlobal = response.id_cotizacion_facturada;
                                
                                Swal.fire({
                                    title: '¡Datos Guardados!',
                                    text: response.message,
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                
                                renderizarTablaConceptos(conceptos);
                            }
                        },
                        error: function(xhr) {
                            inyectarEstadoInicialXml();
                            console.error('Error en la petición:', xhr.responseText);
                            
                            let tituloError = 'Error del Sistema';
                            let mensajeError = 'Ocurrió un problema interno al procesar el guardado.';
                            
                            if (xhr.status === 400) {
                                tituloError = 'Factura Duplicada';
                                mensajeError = xhr.responseJSON.message || 'Esta factura ya fue registrada.';
                            } else if (xhr.status === 422) {
                                tituloError = 'Datos del XML Incompletos';
                                if (xhr.responseJSON && xhr.responseJSON.errors) {
                                    mensajeError = Object.values(xhr.responseJSON.errors).map(err => err.join('<br>')).join('<br>');
                                } else {
                                    mensajeError = xhr.responseJSON.message || 'Los datos no cumplen los requisitos.';
                                }
                            } else if (xhr.status === 500) {
                                tituloError = 'Error de Base de Datos';
                                mensajeError = xhr.responseJSON.message || 'No se pudo escribir el registro en el servidor.';
                            } else if(xhr.status === 410) {
                                tituloError = 'Presupuesto Cerrado';
                                mensajeError = xhr.responseJSON.message || 'El presupuesto asociado se encuentra cerrado.';
                            }

                            Swal.fire({
                                title: `<span class="text-danger fw-bold"><i class="bi bi-x-circle-fill me-2"></i>${tituloError}</span>`,
                                html: `<div class="text-start p-2 small text-muted">${mensajeError}</div>`,
                                icon: 'error',
                                confirmButtonText: 'Entendido',
                                confirmButtonColor: '#dc3545',
                                customClass: {
                                    popup: 'rounded-4 shadow-sm'
                                }
                            });

                            if (xhr.status === 400) {
                                renderizarTablaConceptos(conceptos);
                                btnComparar.attr('disabled', 'true').removeClass('btn-premium-ready');
                            }
                        }
                    });
                } else {
                    inyectarEstadoInicialXml();
                }
            });

        } catch (error) {
            console.error(error);
            Swal.fire({
                title: 'Error al procesar el XML',
                text: 'Asegúrate de que el archivo sea un CFDI válido del SAT. Detalles: ' + error.message,
                icon: 'error',
                confirmButtonText: 'Cerrar'
            });
            inyectarEstadoInicialXml();
        }
    }

    // ==========================================
    // 5. RENDERIZAR CONCEPTOS Y BOTÓN PROCESAR
    // ==========================================
    function renderizarTablaConceptos(conceptos) {
        tablePreviewBody.empty();  
        if (conceptos.length === 0) return;

        $.each(conceptos, function(index, el) {
            let noIdentificacion = el.getAttribute("NoIdentificacion") || 'N/A';
            let descripcion = el.getAttribute("Descripcion") || 'Sin descripción';
            let cantidad = parseFloat(el.getAttribute("Cantidad") || 0);
            let um = el.getAttribute("Unidad") || '';
            let valorUnitario = parseFloat(el.getAttribute("ValorUnitario") || 0);
            let importe = parseFloat(el.getAttribute("Importe") || 0);
            let descuento = parseFloat(el.getAttribute("Descuento") || 0);
            let trasladoNodo = el.getElementsByTagName("cfdi:Traslado")[0] || el.getElementsByTagName("Traslado")[0];
            
            let base = trasladoNodo ? parseFloat(trasladoNodo.getAttribute("Base") || 0) : 0;
            let importeIva = trasladoNodo ? parseFloat(trasladoNodo.getAttribute("Importe") || 0) : 0;

            let porcentajeDescuento = 0;
            if (descuento > 0 && importe > 0) {
                porcentajeDescuento = (descuento / importe) * 100;
            }

            if (noIdentificacion.startsWith('1-')) {
                noIdentificacion = noIdentificacion.substring(2);
            }
            noIdentificacion = noIdentificacion.replace(/^\d+-/, '').trim();

            let rowHtml = `
                <tr>
                    <td class="ps-3 fw-bold text-dark fs-7">${noIdentificacion}</td>
                    <td class="text-muted small text-truncate" style="max-width: 350px;">${descripcion}</td>
                    <td class="text-center fw-bold">${cantidad}</td>
                    <td class="text-center fw-bold">${um}</td>
                    <td class="text-end">$${valorUnitario.toFixed(2)}</td>
                    <td class="text-end fw-extrabold pe-3">$${importe.toFixed(2)}</td>
                    <td class="text-end text-danger">$${descuento.toFixed(2)}</td>
                    <td class="text-end">${porcentajeDescuento > 0 ? `<br><small class="text-muted">(${porcentajeDescuento.toFixed(1)}%)</small>` : ''}</td>
                    <td class="text-end">$${base.toFixed(2)}</td>
                    <td class="text-end">$${importeIva.toFixed(2)}</td>
                </tr>
            `;
            tablePreviewBody.append(rowHtml);
        });

        const totalFactura = parseFloat($('#total').val()) || 0;
        previewTotal.text('Total Factura: $' + totalFactura.toLocaleString('es-MX', {minimumFractionDigits: 2}) + ' MXN');
        previewContainer.slideDown(300);
        btnComparar.removeAttr('disabled').addClass('btn-premium-ready');
    }

    // ==========================================
    // 6. GUARDAR PRODUCTOS FINALES
    // ==========================================
    $('#btn_comparar').on('click', function(){
        Swal.fire({
            title: 'Guardando productos de la factura...',
            text: 'Estamos registrando los conceptos del CFDI en el sistema.',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        var productos = [];
        $('#table_preview_xml tbody tr').each(function() {
            var producto = {
                codigo: $(this).find('td:eq(0)').text().trim(),
                nombre: $(this).find('td:eq(1)').text().trim(),
                cant_facturada: parseFloat($(this).find('td:eq(2)').text().trim()),
                um: $(this).find('td:eq(3)').text().trim(), 
                precio_unitario: parseFloat($(this).find('td:eq(4)').text().replace('$', '').trim()),
                importe: parseFloat($(this).find('td:eq(5)').text().replace('$', '').trim()),
                descuento: parseFloat($(this).find('td:eq(6)').text().replace('$', '').trim()),
                porcentaje_descuento: parseFloat($(this).find('td:eq(7)').text().replace('(', '').replace('%)', '').trim()) || 0,
                impor_con_des: parseFloat($(this).find('td:eq(8)').text().replace('$', '').trim()),
                iva: parseFloat($(this).find('td:eq(9)').text().replace('$', '').trim())
            };
            productos.push(producto);
        });

        $.ajax({
            url: '/conciliacion/facturas/guardar/productos/' + idCotizacionFacturadaGlobal,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                productos: productos
            },
            success: function(response){
                Swal.fire({
                    title: 'Productos guardados',
                    text: response.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
                setTimeout(function(){
                    location.reload();
                }, 2000);
            },
            error: function(xhr){
                console.error('Error al guardar productos:', xhr.responseText);
                Swal.fire({
                    title: 'Error al registrar productos',
                    text: 'Ocurrió un fallo en el servidor.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });

    // ==========================================
    // 7. FUNCIONES AUXILIARES DE RESETEO
    // ==========================================
    function inyectarEstadoInicialXml() {
        $('.xml-drop-zone-large').removeClass('border-success active-success');
        $('#xml_icon_container').addClass('bg-light text-primary').removeClass('bg-success-light text-success');
        $('#xml_icon').addClass('bi-cloud-arrow-up').removeClass('bi-filetype-xml text-success');
        $('#xml_filename').text('Arrastra tu archivo XML aquí o haz clic para buscar');
        $('#xml_filesub').text('Solo se admiten archivos CFDI (.xml) válidos ante el SAT');
        btnComparar.attr('disabled', 'true').removeClass('btn-premium-ready');
        previewContainer.slideUp(200);
    }

    function resetearCamposXml() {
        xmlFile.attr('disabled', 'true').val('');
        divXml.hide();
        $('#xml_badge').removeClass('bg-primary').addClass('bg-secondary');
        inyectarEstadoInicialXml();
        form.attr('action', '');
    }
});
</script>
<style>
    .fw-extrabold { font-weight: 800 !important; }
    .tracking-tight { letter-spacing: -0.5px; }
    .fs-7 { font-size: 0.72rem; }
    .transition-all { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
    
    .icon-box-premium {
        width: 48px;
        height: 48px;
        background-color: #f0f5ff;
        border-radius: 12px;
        border: 1px solid #dbe6ff;
    }
    .custom-indicator-line {
        display: inline-block;
        width: 4px;
        height: 18px;
        background-color: #0d6efd;
        border-radius: 10px;
    }
    .form-label-custom {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #5c6c84;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
    }
    .badge-step {
        width: 18px;
        height: 18px;
        font-size: 0.65rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-right: 6px;
        color: #fff;
    }
    .input-group-custom {
        height: 52px;
        border-radius: 10px;
        overflow: hidden;
    }
    .input-group-custom .form-control {
        height: 52px;
        border-color: #e2e8f0;
    }
    .uppercase-input { text-transform: uppercase; }

    .xml-drop-zone-large {
        border: 2px dashed #cbd5e1 !important;
        background-color: #f8fafc;
        min-height: 160px;
        cursor: pointer;
    }
    .xml-drop-zone-large:hover {
        background-color: #f1f5f9;
        border-color: #0d6efd !important;
    }
    .xml-drop-zone-large.active-success {
        background-color: #f0fdf4;
        border-style: solid !important;
        border-color: #22c55e !important;
    }
    .icon-box-large {
        width: 64px;
        height: 64px;
    }

    .btn-premium-action {
        padding: 12px 30px;
        background-color: #6c757d;
        color: #ffffff;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        cursor: not-allowed;
        opacity: 0.65;
        transition: all 0.2s ease;
    }
    .btn-premium-ready {
        background-color: #0d6efd !important;
        opacity: 1 !important;
        cursor: pointer !important;
    }
    .btn-premium-ready:hover {
        background-color: #0b5ed7 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2) !important;
    }

    @media (min-lg-width: 992px) {
        .border-end-lg { border-end: 1px solid #efefef !important; }
    }

    .custom-premium-table thead th {
        background-color: #f8fafc;
        color: #64748b;
        font-size: 0.7rem;
    }
    .row-premium-success { background-color: #ffffff; }
    .row-premium-success:hover { background-color: #f8fafc !important; }
    .row-premium-danger { background-color: rgba(239, 68, 68, 0.02); }
    .row-premium-danger:hover { background-color: rgba(239, 68, 68, 0.05) !important; }
    .badge-status { display: inline-flex; align-items: center; padding: 5px 10px; font-size: 0.75rem; font-weight: 700; border-radius: 6px; }
    .bg-success-light { background-color: #e6f4ea; color: #137333 !important; }
    .bg-danger-light { background-color: #fce8e6; color: #c5221f !important; }
    .badge-danger-highlight { background-color: #dc3545; color: #ffffff; font-weight: 700; padding: 2px 8px; border-radius: 4px; }
    .text-wrap-custom { display: block; max-width: 280px; white-space: normal; line-height: 1.3; }

    @keyframes spin { 100% { transform: rotate(360deg); } }
    .animate-spin { display: inline-block; animation: spin 0.8s linear infinite; }
</style>
@endsection