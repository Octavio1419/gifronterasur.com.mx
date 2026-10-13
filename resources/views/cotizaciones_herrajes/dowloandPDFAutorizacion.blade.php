@extends('layout.index')
@section('content')
<main class="main-content">
<br><br><br>

  <div class="container-fluid content-inner mt-n5 py-0">
    @include('cotizaciones_herrajes.navAutorizacion')

    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card border-0 dash-card p-4">

          <div class="d-flex align-items-center mb-4 pb-3 dash-card-header-line">
            <div class="header-icon-bezel me-3">
              <i class="fa-solid fa-cloud-arrow-down"></i>
            </div>
            <div>
              <h4 class="mb-0 fw-bold page-title">Descarga de Autorizaciones de Compra</h4>
              <small class="page-subtitle">Selecciona una o más cotizaciones autorizadas para generar el documento oficial.</small>
            </div>
          </div>

          <form class="row g-4" enctype="multipart/form-data" id="pdfForm">

            <div class="col-md-7">
              <label class="field-label mb-2">Cotización(es) seleccionada(s)</label>

              <div class="cotizacion-trigger" id="selectedCotizacionesContainer" data-bs-toggle="modal" data-bs-target="#cotizacionesModal">
                <div class="cotizacion-trigger__placeholder" id="placeholderCotizaciones">
                  <i class="fa-regular fa-square-check"></i>
                  <span>Haga clic aquí para seleccionar cotizaciones...</span>
                </div>
              </div>
              <small class="field-hint">Presiona el área para abrir la lista y seleccionar varias cotizaciones.</small>
            </div>

            <div class="col-md-5">
              <label for="uploadDate" class="field-label mb-2">Fecha de emisión del reporte</label>
              <div class="date-readonly-wrap">
                <i class="fa-regular fa-calendar"></i>
                <input type="date" id="uploadDate" name="fecha_carga" class="field-control" value="{{ date('Y-m-d') }}" readonly>
                <span class="auto-pill">Automática</span>
              </div>
              <small class="field-hint">Esta es la fecha que se incluirá en el PDF de autorización.</small>
            </div>

            <div class="col-12 mt-2">
              <div class="d-flex align-items-center gap-3 flex-wrap">
                <button id="btnGeneraPDF" type="button" class="btn-primary-admin">
                  <i class="fa-solid fa-file-pdf" id="btnGeneraPDFIcon"></i>
                  <span id="btnGeneraPDFText">Generar PDF de autorización</span>
                </button>

                <span class="selection-summary-pill" id="notas_de_accion"></span>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="cotizacionesModal" tabindex="-1" aria-labelledby="cotizacionesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
      <div class="modal-content admin-modal">
        <div class="modal-header admin-modal__header">
          <h5 class="modal-title" id="cotizacionesModalLabel"><i class="fa-solid fa-layer-group me-2"></i> Seleccionar cotizaciones autorizadas</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body admin-modal__body">
          <div class="search-wrap mb-3">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" class="field-control search-input-modal" id="modalSearchInput" placeholder="Filtrar por presupuesto, folio, sucursal...">
          </div>

          <div class="cotizacion-grid" id="cotizacionesList">
            <div class="skeleton-card"></div>
            <div class="skeleton-card"></div>
            <div class="skeleton-card"></div>
          </div>
        </div>
        <div class="modal-footer admin-modal__footer">
          <div class="me-auto d-flex align-items-center gap-2 flex-wrap">
            <span class="count-pill" id="modalSelectedCount">0 cotizaciones seleccionadas</span>
            <span class="total-pill" id="modalSelectedTotal" style="display:none;"></span>
          </div>
          <button type="button" class="btn-secondary-admin" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn-primary-admin" data-bs-dismiss="modal" id="confirmSelectionBtn">
            <i class="fa-solid fa-check"></i> Confirmar selección
          </button>
        </div>
      </div>
    </div>
  </div>
<div id="modalPdf" style="display:none;">
    <iframe id="pdfFrame" style="width:100%; height:500px;" frameborder="0"></iframe>
</div>
<img id="logo" src="{{ asset('img/logotipo_herralum.png') }}" style="display:none;">
<img id="logo_frontera" src="{{ asset('img/logo_frontera_sin.png') }}" style="display:none;">

<style>
    /* ==========================================================
       TEMA ADMINISTRATIVO — mismos tokens que navbarIzquierdo / navbarSuperior / dashboard_admin
       ========================================================== */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    :root {
        --surface:      #FFFFFF;
        --surface-alt:  #F8FAFC;
        --border:       #E7EAF0;
        --ink:          #0F172A;
        --ink-dim:      #55607A;
        --ink-faint:    #97A1B7;

        --primary:      #4F46E5;
        --primary-dim:  rgba(79, 70, 229, 0.10);
        --primary-strong: #4338CA;

        --success:      #059669;
        --success-dim:  rgba(5, 150, 105, 0.10);
        --danger:       #DC2626;
        --danger-dim:   rgba(220, 38, 38, 0.10);
        --warning:      #D97706;
        --warning-dim:  rgba(217, 119, 6, 0.10);
    }

    body { font-family: 'Inter', -apple-system, sans-serif; background: var(--surface-alt); }

    .dash-card {
        background: var(--surface);
        border-radius: 20px !important;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06) !important;
    }

    .dash-card-header-line { border-bottom: 1px solid var(--border); }

    .header-icon-bezel {
        width: 52px; height: 52px;
        border-radius: 14px;
        background: var(--primary-dim);
        color: var(--primary);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    .page-title { color: var(--ink); font-size: 1.15rem; }
    .page-subtitle { color: var(--ink-faint); font-size: 0.82rem; }

    .field-label {
        display: block;
        font-weight: 600;
        font-size: 0.82rem;
        color: var(--ink);
    }

    .field-hint { color: var(--ink-faint); font-size: 0.76rem; display: block; margin-top: 6px; }

    .field-control {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 10px;
        color: var(--ink);
        font-size: 0.9rem;
        padding: 10px 14px;
        width: 100%;
    }

    .field-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-dim); }

    /* ---------- SELECTOR DE COTIZACIONES (trigger del modal) ---------- */
    .cotizacion-trigger {
        min-height: 54px;
        border: 1.5px dashed var(--border);
        border-radius: 12px;
        padding: 10px 14px;
        background: var(--surface-alt);
        cursor: pointer;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 6px;
        transition: border-color 0.15s ease, background 0.15s ease;
    }

    .cotizacion-trigger:hover { border-color: var(--primary); background: var(--primary-dim); }

    .cotizacion-trigger__placeholder {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--ink-faint);
        font-size: 0.88rem;
        width: 100%;
        justify-content: center;
        padding: 4px 0;
    }

    /* Chips de cotizaciones seleccionadas */
    .cotizacion-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--primary);
        color: #fff;
        border-radius: 999px;
        padding: 6px 8px 6px 12px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .cotizacion-chip .remove-chip {
        width: 18px; height: 18px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        font-size: 0.65rem;
    }

    .cotizacion-chip .remove-chip:hover { background: rgba(255,255,255,0.35); }

    /* ---------- FECHA (readonly) ---------- */
    .date-readonly-wrap {
        position: relative;
        display: flex;
        align-items: center;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: var(--surface-alt);
        padding: 0 12px;
    }

    .date-readonly-wrap i { color: var(--ink-faint); margin-right: 8px; }
    .date-readonly-wrap .field-control { border: none; background: transparent; padding-left: 0; }
    .date-readonly-wrap .field-control:focus { box-shadow: none; }

    .auto-pill {
        font-size: 0.66rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--ink-faint);
        background: var(--surface);
        border: 1px solid var(--border);
        padding: 3px 8px;
        border-radius: 999px;
        flex-shrink: 0;
    }

    /* ---------- BOTONES ---------- */
    .btn-primary-admin {
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 12px 22px;
        font-weight: 600;
        font-size: 0.88rem;
        display: inline-flex;
        align-items: center;
        gap: 9px;
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.22);
        transition: background 0.15s ease, transform 0.1s ease;
    }

    .btn-primary-admin:hover { background: var(--primary-strong); color: #fff; }
    .btn-primary-admin:active { transform: scale(0.98); }
    .btn-primary-admin:disabled { opacity: 0.65; box-shadow: none; cursor: not-allowed; }

    .btn-secondary-admin {
        background: var(--surface);
        color: var(--ink-dim);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 10px 18px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .btn-secondary-admin:hover { background: var(--surface-alt); color: var(--ink); }

    .selection-summary-pill {
        font-size: 0.82rem;
        color: var(--ink-dim);
        font-weight: 500;
    }

    /* ---------- MODAL ---------- */
    .admin-modal { border: none; border-radius: 20px; overflow: hidden; }

    .admin-modal__header {
        background: var(--primary);
        color: #fff;
        border: none;
        padding: 18px 24px;
    }

    .admin-modal__header .modal-title { font-weight: 700; font-size: 1.02rem; }

    .admin-modal__body { background: var(--surface-alt); padding: 20px 24px; }

    .search-wrap { position: relative; }
    .search-wrap i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--ink-faint); }
    .search-input-modal { padding-left: 38px; background: var(--surface); }

    .admin-modal__footer {
        background: var(--surface);
        border-top: 1px solid var(--border);
        padding: 14px 24px;
    }

    .count-pill {
        background: var(--surface-alt);
        border: 1px solid var(--border);
        color: var(--ink-dim);
        font-size: 0.8rem;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 999px;
    }

    .total-pill {
        background: var(--success-dim);
        border: 1px solid rgba(5,150,105,0.25);
        color: var(--success);
        font-size: 0.8rem;
        font-weight: 700;
        padding: 6px 12px;
        border-radius: 999px;
    }

    /* ---------- GRID DE TARJETAS DE COTIZACIÓN ---------- */
    .cotizacion-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 14px;
    }

    .cotizacion-card {
        position: relative;
        background: var(--surface);
        border: 1.5px solid var(--border);
        border-radius: 16px;
        padding: 16px;
        cursor: pointer;
        transition: border-color 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
        display: block;
        text-align: left;
        width: 100%;
    }

    .cotizacion-card:hover { border-color: var(--primary); box-shadow: 0 6px 18px rgba(79, 70, 229, 0.1); }

    .cotizacion-card.selected {
        border-color: var(--primary);
        background: var(--primary-dim);
        box-shadow: 0 6px 18px rgba(79, 70, 229, 0.15);
    }

    .cotizacion-card__check {
        position: absolute;
        top: 12px; right: 12px;
        width: 22px; height: 22px;
        border-radius: 50%;
        border: 2px solid var(--border);
        background: var(--surface);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.65rem;
        color: transparent;
        transition: all 0.15s ease;
    }

    .cotizacion-card.selected .cotizacion-card__check {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
    }

    .cotizacion-card__branch {
        font-size: 0.66rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--ink-faint);
        margin-bottom: 6px;
        padding-right: 30px;
    }

    .cotizacion-card__title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--ink);
        margin-bottom: 2px;
    }

    .cotizacion-card__title small { color: var(--ink-faint); font-weight: 500; font-size: 0.72rem; }

    .cotizacion-card__meta {
        font-size: 0.78rem;
        color: var(--ink-dim);
        margin: 8px 0;
        line-height: 1.5;
    }

    .cotizacion-card__row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px dashed var(--border);
    }

    .cotizacion-card__total {
        font-weight: 700;
        font-size: 0.98rem;
        color: var(--ink);
    }

    .date-badge {
        background: var(--success-dim);
        color: var(--success);
        font-size: 0.68rem;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 999px;
        display: inline-block;
    }

    .cotizacion-card__actions {
        display: flex;
        gap: 6px;
        margin-top: 10px;
    }

    .icon-action-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 7px;
        border-radius: 8px;
        font-size: 0.74rem;
        font-weight: 600;
        border: 1px solid var(--border);
        background: var(--surface);
        color: var(--ink-dim);
        text-decoration: none !important;
    }

    .icon-action-btn.btn-ver-pdf:hover { background: var(--primary-dim); color: var(--primary); border-color: rgba(79,70,229,0.3); }
    .icon-action-btn.remove-folio-btn:hover { background: var(--danger-dim); color: var(--danger); border-color: rgba(220,38,38,0.3); }

    /* ---------- SKELETON / VACÍO ---------- */
    .skeleton-card {
        height: 168px;
        border-radius: 16px;
        background: linear-gradient(90deg, var(--surface) 0%, var(--surface-alt) 50%, var(--surface) 100%);
        background-size: 200% 100%;
        animation: sk-pulse 1.3s ease-in-out infinite;
        border: 1px solid var(--border);
    }

    @keyframes sk-pulse { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

    .grid-empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 50px 20px;
        color: var(--ink-faint);
    }

    .grid-empty-state i { font-size: 2rem; margin-bottom: 10px; opacity: 0.5; display: block; }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 

<script>
    // Variables globales para la gestión de cotizaciones
    let allCotizaciones = []; 
    let selectedCotizacionIds = [];
    const $listContainer = $('#cotizacionesList');
    const $chipsContainer = $('#selectedCotizacionesContainer');
    const $placeholder = $('#placeholderCotizaciones');

    document.addEventListener('DOMContentLoaded', function(){
        
        foliados(); // Carga inicial de datos

        // 1. CARGA DE DATOS DESDE EL SERVIDOR
        function foliados(){
            fetch('/cotizaciones-foliadas-true')
                .then(response => {
                    if (!response.ok) throw new Error('Error al cargar las cotizaciones');
                    return response.json();
                })
                .then(data => {
                    allCotizaciones = data.foliadas;
                    renderModalList(allCotizaciones);
                })
                .catch(error => {
                    console.error('Error:', error);
                    $listContainer.html(`<div class="grid-empty-state"><i class="fa-solid fa-triangle-exclamation" style="color: var(--danger);"></i><p class="mb-0">Error al cargar las cotizaciones: ${error.message}</p></div>`);
                });
        }

        // 2. RENDERIZADO DE LA GRILLA DE TARJETAS EN EL MODAL
        function renderModalList(cotizaciones) {
            $listContainer.empty();
            if (cotizaciones.length === 0) {
                $listContainer.append('<div class="grid-empty-state"><i class="fa-regular fa-folder-open"></i><p class="mb-0">No se encontraron cotizaciones autorizadas.</p></div>');
                return;
            }

            cotizaciones.forEach(cotizacion => {
                const isSelected = selectedCotizacionIds.includes(cotizacion.id);
                const item = `
                    <div class="cotizacion-card ${isSelected ? 'selected' : ''}" 
                            data-id="${cotizacion.id}" data-presupuesto="${cotizacion.presupuesto}" data-sucursal="${cotizacion.nombre_real}">
                        <div class="cotizacion-card__check"><i class="fa-solid fa-check"></i></div>
                        <div class="cotizacion-card__branch">${cotizacion.nombre_real}</div>
                        <div class="cotizacion-card__title">Presupuesto ${cotizacion.presupuesto} <small>(${cotizacion.id})</small></div>
                        <div class="cotizacion-card__meta">
                            Folio: ${cotizacion.folio}<br>
                            Autorizó: ${cotizacion.nombre} ${cotizacion.apellido_p} ${cotizacion.apellido_m}
                        </div>
                        <div class="cotizacion-card__row">
                            <span class="date-badge">${formatearFechaCorta(cotizacion.fecha_cotizacion)}</span>
                            <span class="cotizacion-card__total">${formatearMoneda(cotizacion.total_cotizacion)}</span>
                        </div>
                        <div class="cotizacion-card__actions">
                            <a target="_blank" class="icon-action-btn btn-ver-pdf" data-id="${cotizacion.id}" data-presupuesto="${cotizacion.presupuesto}">
                                <i class="fa-solid fa-file-pdf"></i> Ver PDF
                            </a>
                            <button type="button" class="icon-action-btn remove-folio-btn" data-id="${cotizacion.id}" data-presupuesto="${cotizacion.presupuesto}" data-folio="${cotizacion.folio}">
                                <i class="fa-solid fa-trash-can"></i> Quitar
                            </button>
                        </div>
                    </div>
                `;
                $listContainer.append(item);
            });
            updateModalCount();

            //logica para la creacion de ver pdf,
            $('.btn-ver-pdf').off('click').on('click', function(e) {
                e.stopPropagation(); // Evitar que el clic seleccione/deseleccione la cotización
                const id = $(this).data('id');
                const presupuesto = $(this).data('presupuesto');
                console.log(`Ver PDF para Cotización ID: ${id}, Presupuesto: ${presupuesto}`);
                        
                //recuperado datos del pdf
                fetch('/ver-pdf-cotizacion/' + id).then(response => response.json()).then(data => {

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

                    console.log('Estos son los datos para generar el pdf de la cotización:', generales, productos);

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

                    // Preparar los datos para la tabla
                   const producto = productos.map(item => {
                    let existenciasTexto = '';

                    if (item.existencia_en_sucur) {
                        try {
                            // Convertimos el string JSON a un objeto real
                            const sucursales = JSON.parse(item.existencia_en_sucur);

                            // Accedemos a las propiedades del objeto parseado
                            if (sucursales["1"] !== undefined) {
                                existenciasTexto += `Suc 1: ${parseFloat(sucursales["1"]).toFixed(2)}\n`;
                            }
                            if (sucursales["2"] !== undefined) {
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
                          // Alineación de texto y números
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
                    // 4. Estilos Condicionales para los colores de la imagen
                   didParseCell: (data) => {
                          // Columna de Ventas 6m (Index 3)
                   if (data.column.index === 3) {
                     data.cell.styles.fillColor = [79, 195, 247]; // Azul/Celeste claro
                     data.cell.styles.textColor = [255, 255, 255]; // Texto blanco
                     data.cell.styles.fontStyle = 'bold';
                    }

                          // Columna Sugerencia (Index 6)
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
                                  // Rojo para "Surtir" o cualquier otra acción
                 data.cell.styles.fillColor = [244, 67, 54]; 
                 data.cell.styles.textColor = [255, 255, 255];
                    }
                   }
                  }
                });

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

             //visualizar pdf
            const nombrepdf = generales.presupuesto + '_cotizacion.pdf';
                    //ver el pdf, sin guardarrlo     
            //window.open(doc.output('bloburl'), '_blank');
          
                
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
   
            // LÓGICA PARA EL BOTÓN DE QUITAR FOLIO
            $('.remove-folio-btn').off('click').on('click', function(e) {
                e.stopPropagation(); // Evitar que el clic seleccione/deseleccione la cotización
                //vamos a enviar 0 para que quite el folio

                const id = $(this).data('id');
                const presupuesto = $(this).data('presupuesto');
                const folio = $(this).data('folio');

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: `Se quitará el folio "${folio}" de la cotización "${presupuesto}". Esta acción no se puede deshacer.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, quitar folio',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#DC2626',
                    cancelButtonColor: '#55607A',
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/quitar-folio-cotizacion/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) throw new Error('Error al quitar el folio');
                            return response.json();
                        })
                        .then(data => {
                            Swal.fire('¡Folio quitado!', data.message, 'success');
                            foliados(); // Recargar la lista después de quitar el folio
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error', 'No se pudo quitar el folio: ' + error.message, 'error');
                        });
                    }
                });
            });
        }

        // 3. LÓGICA DE SELECCIÓN EN LA GRILLA DE TARJETAS
        $listContainer.on('click', '.cotizacion-card', function() {
            const id = parseInt($(this).data('id'));
            const index = selectedCotizacionIds.indexOf(id);

            if (index > -1) {
                // Deseleccionar
                selectedCotizacionIds.splice(index, 1);
                $(this).removeClass('selected');
            } else {
                // Seleccionar
                selectedCotizacionIds.push(id);
                $(this).addClass('selected');
            }
            updateModalCount();
        });

        // 4. FILTRO DE BÚSQUEDA EN EL MODAL
        $('#modalSearchInput').on('keyup', function() {
            const searchText = $(this).val().toLowerCase();
            const filteredCotizaciones = allCotizaciones.filter(c => {
                return (
                    c.presupuesto.toLowerCase().includes(searchText) ||
                    c.nombre_real.toLowerCase().includes(searchText) ||
                    c.folio.toLowerCase().includes(searchText) ||
                    c.id.toString().includes(searchText) 
                );
            });
            renderModalList(filteredCotizaciones);
        });
        
        $('#confirmSelectionBtn').on('click', function() {
            renderChips();
        });

        function renderChips() {
            $chipsContainer.empty();
            if (selectedCotizacionIds.length === 0) {
                $chipsContainer.append($placeholder);
                actualizarResumenSeleccion();
                return;
            }
            
            // Ocultar placeholder
            $placeholder.detach(); 

            selectedCotizacionIds.forEach(id => {
                const cotizacion = allCotizaciones.find(c => c.id === id);
                if (cotizacion) {
                    const chip = `
                        <span class="cotizacion-chip" data-id="${cotizacion.id}">
                            ${cotizacion.presupuesto} (${cotizacion.nombre_real})
                            <span class="remove-chip"><i class="fa-solid fa-xmark"></i></span>
                        </span>
                    `;
                    $chipsContainer.append(chip);
                }
            });
            actualizarResumenSeleccion();
        }
        // 6. ELIMINAR CHIPS INDIVIDUALMENTE
        $chipsContainer.on('click', '.remove-chip', function(e) {
            e.stopPropagation(); // Evitar que el clic abra el modal
            const $chip = $(this).closest('.cotizacion-chip');
            const id = parseInt($chip.data('id'));
            
            // Eliminar de la lista de IDs
            selectedCotizacionIds = selectedCotizacionIds.filter(itemId => itemId !== id);
            
            // Eliminar el chip visualmente
            $chip.remove();
            
            // Re-renderizar si no queda nada
            if (selectedCotizacionIds.length === 0) {
                $chipsContainer.append($placeholder);
            }
            actualizarResumenSeleccion();
        });
        
        // 7. ACTUALIZAR CONTADOR Y TOTAL DEL MODAL
        function updateModalCount() {
            $('#modalSelectedCount').text(`${selectedCotizacionIds.length} cotizaciones seleccionadas`);

            const $totalPill = $('#modalSelectedTotal');
            if (selectedCotizacionIds.length === 0) {
                $totalPill.hide();
                return;
            }
            const totalSeleccionado = selectedCotizacionIds.reduce((sum, id) => {
                const cot = allCotizaciones.find(c => c.id === id);
                return sum + (cot ? parseFloat(cot.total_cotizacion || 0) : 0);
            }, 0);
            $totalPill.text('Total: ' + formatearMoneda(totalSeleccionado)).show();
        }

        // Resumen fuera del modal, junto al botón de generar PDF
        function actualizarResumenSeleccion() {
            const $resumen = $('#notas_de_accion');
            if (selectedCotizacionIds.length === 0) {
                $resumen.text('');
                return;
            }
            const totalSeleccionado = selectedCotizacionIds.reduce((sum, id) => {
                const cot = allCotizaciones.find(c => c.id === id);
                return sum + (cot ? parseFloat(cot.total_cotizacion || 0) : 0);
            }, 0);
            $resumen.html(`<i class="fa-solid fa-circle-check" style="color: var(--success);"></i> ${selectedCotizacionIds.length} cotización(es) por un total de ${formatearMoneda(totalSeleccionado)}`);
        }

        // **********************************************
        // ******* LÓGICA DE GENERACIÓN DE PDF **********
        // **********************************************

        $('#btnGeneraPDF').click(function(){
            
            if(selectedCotizacionIds.length === 0){
                Swal.fire({
                    icon: 'warning',
                    title: 'Selecciona al menos una cotización',
                    text: 'Usa el área gris para abrir la lista y elegir las cotizaciones a foliar.',
                    confirmButtonText: 'Aceptar',
                });
                return;
            }

            // Usamos la variable global de IDs
            generarPDF(selectedCotizacionIds);
        });

        // Funciones de utilidad (Mantenidas de tu código original)
        function obtenerFechaLarga() {
            
            const fecha = new Date();
            const dia = fecha.getDate();
            const anio = fecha.getFullYear();
            const opcionesMes = { month: 'long' }; 
            const nombreMes = fecha.toLocaleDateString('es-ES', opcionesMes);
            const opcionesDiaSemana = { weekday: 'long' };
            let diaSemana = fecha.toLocaleDateString('es-ES', opcionesDiaSemana);
            diaSemana = diaSemana.charAt(0).toUpperCase() + diaSemana.slice(1);
            return `${diaSemana}, a ${dia} de ${nombreMes} del ${anio}`;
        } 
        function formatearMoneda(amount) {
           
            const value = typeof amount === 'string' ? parseFloat(amount) : amount;
            if (isNaN(value)) return '$0.00 MXN';

            return value.toLocaleString('es-MX', {
                style: 'currency',
                currency: 'MXN',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        } 
        function formatearFechaCorta(dateString) {
            
            if (!dateString) return '';
            const datePart = dateString.split(' ')[0]; 
            const parts = datePart.split('-');
            if (parts.length === 3) {
                return `${parts[2]}/${parts[1]}/${parts[0]}`;
            }
            return dateString;
        }
        function generarCodigoAleatorio(length = 12) {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let result = '';
            for (let i = 0; i < length; i++) {
                result += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return result;
        }

        // Función principal de generación de PDF
        function generarPDF(idsArray) {
            // Convierte el array de IDs a string separado por comas para la URL
            const ids = idsArray.join(','); 
            console.log('IDs para enviar y consultar:', ids);

            // Estado de carga real en el botón (antes apuntaba a #gif_carga, que no existe en el DOM)
            const $btn = $('#btnGeneraPDF');
            $btn.prop('disabled', true);
            $('#btnGeneraPDFIcon').removeClass('fa-file-pdf').addClass('fa-spinner fa-spin');
            $('#btnGeneraPDFText').text('Generando PDF...');

            fetch(`/generar-pdf-cotizaciones-autorizadas/${ids}`)
              .then(response => {
                  $btn.prop('disabled', false);
                  $('#btnGeneraPDFIcon').removeClass('fa-spinner fa-spin').addClass('fa-file-pdf');
                  $('#btnGeneraPDFText').text('Generar PDF de autorización');
                  if (!response.ok) throw new Error('Error en la respuesta del servidor: ' + response.status);
                  return response.json();
              })
              .then(respuesta => {
                
                  // **************** INICIO LÓGICA JSDPF ****************
                  let datos_pdf = respuesta.pdf;
                  //SACANDOO EL CODIGO DE LA COTIZACION
                  console.log('Datos recibidos para el PDF:', datos_pdf);
                  const { jsPDF } = window.jspdf;
                  const doc = new jsPDF({ orientation: 'p', unit: 'mm', format: 'a4' });
                  
                  const MARGIN_X = 10;
                  let startY = 10; 
                  
                  // Conversión de imágenes a DataURL
                  const getImageData = (imgElement) => {
                      const canvas = document.createElement('canvas');
                      canvas.width = imgElement.naturalWidth;
                      canvas.height = imgElement.naturalHeight;
                      const ctx = canvas.getContext('2d');
                      ctx.drawImage(imgElement, 0, 0);
                      return canvas.toDataURL('image/png');
                  };
                  const img1 = document.getElementById('logo');
                  const img2 = document.getElementById('logo_frontera');
                  
                  if (img1) doc.addImage(getImageData(img1), 'PNG', 150, startY, 40, 15);
                  if (img2) doc.addImage(getImageData(img2), 'PNG', MARGIN_X, startY, 60, 15);

                  // Resto de la lógica del PDF (Título, Fecha, Tabla, Footer, Firmas)
                  
                  startY += 25; 
                  
                  // TÍTULO PRINCIPAL
                  doc.setFontSize(16);
                  doc.setTextColor(0, 0, 0);
                  doc.setFont("helvetica", "bold");
                  doc.text('AUTORIZACION DE COMPRAS', 105, startY, { align: 'center' });
                  startY += 7;
                  doc.setFontSize(14);
                  doc.text('TIENDAS HERRALUM CHIAPAS, MX.', 105, startY, { align: 'center' });
                  startY += 10;

                  // FECHA DE EMISIÓN DEL PDF
                  const fechaFormateada = obtenerFechaLarga();
                  doc.setFontSize(9);
                  doc.setTextColor(0, 0, 0);
                  doc.setFont("helvetica", "normal");
                  doc.text(`Fecha de Emisión: ${fechaFormateada.toUpperCase()}`, MARGIN_X, startY, { align: 'left' });
                  startY += 10; 

                  // PREPARACIÓN DE DATOS PARA LA TABLA
                  const head = [
                      [
                          'Sucursal', 'Motivo del pedido', 'Tipo de pedido', 
                          'Fecha Autorizada', 'Presupuesto', 'Folio', 
                          'Autorizó', 'Comentario', 'Total (MXN)'
                      ]
                  ];

                  //nombre del pdf
                  const nombrepdf = datos_pdf.map(item => item.presupuesto).join('_');
                  console.log('Nombre del PDF:', nombrepdf);

                  const body = datos_pdf.map(item => [
                      item.nombre_real, 
                      item.motivo, 
                      item.tipo_pedido, 
                      formatearFechaCorta(item.fecha_de_autorizacion),
                      item.presupuesto,
                      item.folio,
                      item.nombre_completo,
                      item.comentarios_adicionales,
                      formatearMoneda(item.total_cotizacion),
                      
                  ]);

                  const grandTotal = datos_pdf.reduce((sum, item) => sum + item.total_cotizacion, 0);
                  const formattedGrandTotal = formatearMoneda(grandTotal);

                  const foot = [
                      [
                          { 
                              content: 'TOTAL AUTORIZADO', 
                              colSpan: 8, 
                              styles: { halign: 'right', fontStyle: 'bold', fontSize: 9, fillColor: [200, 200, 200], textColor: [0, 0, 0] } 
                          }, 
                          {
                              content: formattedGrandTotal, 
                              styles: { halign: 'right', fontStyle: 'bold', fontSize: 9, fillColor: [200, 200, 200], textColor: [0, 0, 0] }
                          }
                      ]
                  ];

                  // GENERACIÓN DE LA TABLA con AutoTable
                  doc.autoTable({
                      startY: startY,
                      head: head,
                      body: body,
                      foot: foot,
                      theme: 'grid',
                      headStyles: { 
                          fillColor: [14, 116, 144], 
                          textColor: 255, 
                          fontStyle: 'bold', 
                          fontSize: 8,
                          halign: 'center'
                      },
                      styles: { 
                          fontSize: 7, 
                          cellPadding: 1, 
                          minCellHeight: 6,
                          halign: 'center'
                      },
                      columnStyles: {
                          7: { halign: 'right' } 
                      },
                      margin: { left: MARGIN_X, right: MARGIN_X },
                      didDrawPage: function (data) {
                          // Texto de Legalidad y Sistema
                          doc.setFontSize(7);
                          doc.setTextColor(100, 100, 100);
                          const disclaimerText = 'GENERADO POR EL SISTEMA GRUPO FRONTERA INDUSTRIAL SUR.NO SE PERMITE MANIPULACIÓN EN EL DOCUMENTO DESPUES DE FOLIAR.';
                          const centerX = doc.internal.pageSize.width / 2;
                          doc.text(disclaimerText, centerX, doc.internal.pageSize.height - 14, { align: 'center' });
                          
                          // Footer (Numeración de página)
                          let pageCount = doc.internal.getNumberOfPages();
                          doc.setFontSize(8);
                          doc.setTextColor(0, 0, 0);
                          doc.text('Página ' + data.pageNumber + ' de ' + pageCount, 200, doc.internal.pageSize.height - 10, { align: 'right' });
                      }
                  });

                  // SECCIÓN DE FIRMA/AUTORIZACIÓN (Código de Folio Digital)
                  let finalY = doc.autoTable.previous.finalY;
                  finalY += 25;

                  const codigoUnico = generarCodigoAleatorio();
                  doc.setFontSize(14);
                  doc.setTextColor(14, 116, 144);
                  doc.setFont("helvetica", "bold");
                  doc.text(codigoUnico, doc.internal.pageSize.width / 2, finalY, { align: 'center' });

                  finalY += 4;

                  const nombreAutorizo = datos_pdf.length > 0 ? datos_pdf[0].nombre_completo : 'USUARIO AUTORIZÓ';

                  const lineWidth = 90;
                  const lineX1 = doc.internal.pageSize.width / 2 - lineWidth / 2;
                  const lineX2 = doc.internal.pageSize.width / 2 + lineWidth / 2;
                  
                  // Línea de "Foliado"
                  doc.setLineWidth(0.5);
                  doc.line(lineX1, finalY, lineX2, finalY);

                  finalY += 4;

                  // Leyenda "FOLIADO POR"
                  doc.setFontSize(8);
                  doc.setTextColor(50, 50, 50);
                  doc.setFont("helvetica", "normal");
                  doc.text('FOLIADO POR', doc.internal.pageSize.width / 2, finalY, { align: 'center' });

                  finalY += 4;

                  // Nombre del Autorizador
                  doc.setFontSize(10);
                  doc.setTextColor(0, 0, 0);
                  doc.setFont("helvetica", "bold");
                  doc.text(nombreAutorizo.toUpperCase(), doc.internal.pageSize.width / 2, finalY, { align: 'center' });

                  // GUARDAR/MOSTRAR PDF
                  const nombre_real = nombrepdf + '_autorizada_foleada.pdf';
                  doc.save(nombre_real);
                  
                  // **************** FIN LÓGICA JSDPF ****************
                  console.log("PDF generado con éxito.");
                  
              })
              .catch(error => {
                  console.error('Error al generar PDF:', error);
                  $btn.prop('disabled', false);
                  $('#btnGeneraPDFIcon').removeClass('fa-spinner fa-spin').addClass('fa-file-pdf');
                  $('#btnGeneraPDFText').text('Generar PDF de autorización');
                  Swal.fire({
                      icon: 'error',
                      title: 'Error al generar PDF',
                      text: 'Hubo un problema al consultar los datos. Inténtalo de nuevo. Detalle: ' + error.message,
                      confirmButtonText: 'Aceptar',
                });
            });
        }
    });
</script>
</main>
@endsection