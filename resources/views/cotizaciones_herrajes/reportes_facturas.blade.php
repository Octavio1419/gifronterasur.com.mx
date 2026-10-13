@extends('layout.index')
@section('content')
<main class="main-content">
    <div class="container-fluid content-inner py-4">

        {{-- ============ ENCABEZADO ============ --}}
        <div class="d-flex flex-wrap justify-content-between align-items-start mb-4 gap-3">
            <div>
                <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill mb-2 d-inline-block">
                    <i class="fa-solid fa-scale-balanced me-1"></i> Auditoría de Conciliaciones
                </span>
                <h1 class="h3 fw-bold mb-1 text-dark">Facturas Conciliadas</h1>
                <p class="text-muted mb-0">Revisa tus facturas y genera los reportes detallados</p>
            </div>
            <div class="text-end">
                <span class="d-block text-muted text-uppercase" style="letter-spacing:1px; font-size:10.5px;">Actualizado</span>
                <span class="fw-semibold text-dark" id="lbl-ultima-actualizacion">—</span>
            </div>
        </div>

        {{-- ============ KPIs ============ --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm kpi-card-fc">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="kpi-icon-fc bg-soft-secondary text-secondary"><i class="fa-solid fa-list-check"></i></div>
                        <div>
                            <span class="kpi-label-fc">Conciliaciones</span>
                            <h3 class="kpi-value-fc kpi-skeleton-fc" id="kpi-cantidad">&nbsp;</h3>
                            <span class="kpi-sub-fc">en la vista actual</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm kpi-card-fc">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="kpi-icon-fc bg-soft-primary text-primary"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                        <div>
                            <span class="kpi-label-fc">Total Cotizado</span>
                            <h3 class="kpi-value-fc kpi-skeleton-fc" id="kpi-cotizado">&nbsp;</h3>
                            <span class="kpi-sub-fc">monto autorizado</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm kpi-card-fc">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="kpi-icon-fc bg-soft-info text-info"><i class="fa-solid fa-receipt"></i></div>
                        <div>
                            <span class="kpi-label-fc">Total Facturado</span>
                            <h3 class="kpi-value-fc kpi-skeleton-fc" id="kpi-facturado">&nbsp;</h3>
                            <span class="kpi-sub-fc">monto real recibido</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm kpi-card-fc">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="kpi-icon-fc bg-soft-danger text-danger"><i class="fa-solid fa-scale-unbalanced"></i></div>
                        <div>
                            <span class="kpi-label-fc">Desviación Neta</span>
                            <h3 class="kpi-value-fc kpi-skeleton-fc" id="kpi-desviacion">&nbsp;</h3>
                            <span class="kpi-sub-fc" id="kpi-desviacion-pct">0% del cotizado</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============ FILTROS ============ --}}
        <div class="card border-0 shadow-sm filter-card-fc mb-4">
            <div class="card-body p-3">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="filter-label-fc">Buscar folio / presupuesto</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" id="filtro-buscar" class="form-control bg-light border-start-0" placeholder="Ej. CONC-2026-00009…">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="filter-label-fc">Estatus auditoría</label>
                        <select id="filtro-estatus" class="form-select form-select-sm bg-light">
                            <option value="TODOS">Todos los estatus</option>
                            <option value="CUADRADO">Cuadrado (margen seguro)</option>
                            <option value="MAS">Cobro de más (alerta fuga)</option>
                            <option value="PENDIENTE">Pendiente XML (por surtir)</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="filter-label-fc">Sucursal</label>
                        <select id="filtro-sucursal" class="form-select form-select-sm bg-light">
                            <option value="">Todas las sucursales</option>
                            <option value="BOULEVARD">Tienda Herralum Boulevard</option>
                            <option value="CENTRO">Tienda Herralum Centro</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="filter-label-fc">Desde fecha</label>
                        <input type="date" id="filtro-fecha-inicio" class="form-control form-control-sm bg-light">
                    </div>
                    <div class="col-md-2">
                        <label class="filter-label-fc">Hasta fecha</label>
                        <input type="date" id="filtro-fecha-fin" class="form-control form-control-sm bg-light">
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3 pt-3 border-top">
                    <span class="text-muted small">
                        <i class="fa-solid fa-circle-info me-1"></i>Los reportes exportados respetan los filtros aplicados arriba.
                        <span class="text-muted" title="El PDF de conciliaciones también incluye presupuestos aún no facturados, que no aparecen en esta tabla.">
                            <i class="fa-solid fa-circle-question ms-1" style="cursor:help;"></i>
                        </span>
                    </span>
                    <div class="d-flex gap-2 flex-wrap">
                        <button class="btn btn-sm btn-outline-secondary rounded-3 fw-semibold px-3" id="btn-limpiar-filtros">
                            <i class="fa-solid fa-filter-circle-xmark me-1"></i> Limpiar
                        </button>
                        <button class="btn btn-sm btn-success text-white rounded-3 fw-bold px-3" id="btn-exportar-excel">
                            <i class="fa-solid fa-file-excel me-1"></i> Excel
                        </button>
                        <button class="btn btn-sm btn-danger text-white rounded-3 fw-bold px-3" id="btn-exportar-pdf-general-vs">
                            <i class="fa-solid fa-file-pdf me-1"></i> PDF Conc.
                        </button>
                        <button class="btn btn-sm btn-danger text-white rounded-3 fw-bold px-3" id="btn-exportar-pdf-general-pagos">
                            <i class="fa-solid fa-file-pdf me-1"></i> PDF Pago
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============ SALUD DE AUDITORÍA ============ --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100 salud-card-fc">
                    <div class="card-body">
                        <h6 class="fw-bold text-dark mb-1"><i class="fa-solid fa-shield-heart text-primary me-2"></i>Efectividad de cierre</h6>
                        <small class="text-muted d-block mb-2">Qué tan sano está el proceso de conciliación</small>
                        <div id="grafica_efectividad" style="min-height:320px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100 salud-card-fc">
                    <div class="card-body">
                        <h6 class="fw-bold text-dark mb-1"><i class="fa-solid fa-magnifying-glass-dollar text-danger me-2"></i>Mayores desviaciones</h6>
                        <small class="text-muted d-block mb-3">Los presupuestos con más diferencia entre lo cotizado y lo facturado, en la vista actual</small>
                        <div id="lista_top_desviaciones"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============ TABLA ============ --}}
        <div class="card border-0 shadow-sm table-card-fc">
            <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="card-title text-dark fw-bold mb-0">
                    <i class="fa-solid fa-table-list text-primary me-2"></i>Conciliaciones Encontradas
                </h5>
                <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill fw-semibold" id="contador-registros">0 conciliaciones</span>
            </div>
            <div class="table-responsive tabla-fc-scroll px-3 pb-3">
                <table class="table align-middle table-hover mb-0 tabla-fc" id="tabla-maestra-auditoria" style="width:100%">
                    <thead>
                        <tr>
                            <th width="3%"></th>
                            <th>Folio Conciliación</th>
                            <th># Presupuesto</th>
                            <th>Sucursal</th>
                            <th class="text-end">Monto Cotizado</th>
                            <th class="text-end">Monto Facturado</th>
                            <th class="text-center">Estatus</th>
                            <th class="text-end">Diferencia</th>
                            <th>Fecha Presupuesto</th>
                            <th width="6%" class="text-center">Reporte</th>
                            <th>Fecha Cierre</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-auditoria">
                        <tr>
                            <td colspan="11" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-spinner fa-spin fa-2x mb-3 d-block text-primary"></i>
                                Cargando información operacional…
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<img id="logo_frontera" src="{{ asset('img/logo_frontera_sin.png') }}" style="display:none;">
<img id="logo_vss" src="{{ asset('img/vss_logo.jpeg') }}" style="display:none;">
<img id="icono_gas" src="{{ asset('img/icono_gas.svg') }}" style="display:none;">
<img id="logo" src="{{ asset('img/logotipo_herralum.png') }}" style="display:none;">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<style>
.bg-soft-primary { background-color: rgba(58,87,232,0.1); }
.bg-soft-secondary { background-color: rgba(139,148,166,0.15); }
.bg-soft-info { background-color: rgba(75,199,210,0.15); }
.bg-soft-danger { background-color: rgba(242,109,125,0.15); }
.bg-soft-success { background-color: rgba(23,144,75,0.12); }
.bg-soft-warning { background-color: rgba(255,193,7,0.18); }

/* ============ KPIs ============ */
.kpi-card-fc { border-radius: 14px; transition: transform .15s ease, box-shadow .15s ease; }
.kpi-card-fc:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(22,35,63,0.08) !important; }
.kpi-icon-fc {
    width: 46px; height: 46px; min-width: 46px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center; font-size: 18px;
}
.kpi-label-fc { display:block; font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#8891a5; }
.kpi-value-fc { font-size:1.35rem; font-weight:800; color:#16233f; margin:2px 0 0; line-height:1.1; }
.kpi-sub-fc { font-size:11px; color:#8891a5; }

/* ============ FILTROS ============ */
.filter-card-fc { border-radius: 14px; }
.filter-label-fc { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: #8891a5; margin-bottom: 4px; display: block; }

/* ============ TABLA ============ */
.table-card-fc { border-radius: 14px; }
.tabla-fc thead th {
    font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px;
    color: #8891a5; padding: 12px 14px; border-bottom: 1px solid #edeff5; white-space: nowrap;
}
.tabla-fc tbody td { padding: 13px 14px; border-color: #f2f3f8; font-size: 13px; }
.tabla-fc tbody tr.fila-master:hover { background: #f8f9fc; }

.row-folio { font-weight: 700; color: #16233f; }
.row-folio-badge {
    font-size: 11px; font-weight: 700; background: #f1f2f6; color: #4b5468;
    padding: 3px 10px; border-radius: 6px; display: inline-block;
}

/* ============ ESTATUS (pills unificados) ============ */
.status-pill {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 11px; font-weight: 700; padding: 5px 12px; border-radius: 20px;
    text-transform: uppercase; letter-spacing: .3px;
}
.status-pill.status-ok { background: rgba(23,144,75,0.12); color: #17904b; }
.status-pill.status-danger { background: rgba(242,109,125,0.15); color: #d9364a; }
.status-pill.status-warn { background: rgba(255,193,7,0.18); color: #a6740a; }

/* Los mismos 3 colores se usan para el texto "Diferencia", tomados
   SIEMPRE del mismo estatus.key que pinta el badge (ver JS) -- ya no
   hay un umbral aparte que pueda contradecir al badge. */
.text-status-ok { color: #17904b !important; }
.text-status-danger { color: #d9364a !important; }
.text-status-warn { color: #a6740a !important; }

/* ============ FILA EXPANDIBLE ============ */
.btn-icon-action {
    width: 30px; height: 30px; border-radius: 8px; border: 1px solid #e3e6ef;
    background: #fff; color: #4b5468; display: inline-flex; align-items: center; justify-content: center;
    transition: background .15s ease, transform .15s ease;
}
.btn-icon-action:hover { background: #eef0f5; }
.btn-toggle-detalle i { transition: transform .2s ease; }
.btn-toggle-detalle.is-open i { transform: rotate(90deg); }
.btn-toggle-detalle.is-open { background: #3a57e8; color: #fff; border-color: #3a57e8; }

.detalle-wrapper { background: #f8f9fc; }
.text-monospace { font-variant-numeric: tabular-nums; }

/* ============ SKELETON DE CARGA (KPIs) ============ */
.kpi-skeleton-fc {
    display: inline-block; min-width: 80px; height: 1.35rem; border-radius: 6px;
    background: linear-gradient(90deg, #eef0f5 25%, #f7f8fb 37%, #eef0f5 63%);
    background-size: 400% 100%; animation: kpi-shimmer-fc 1.4s ease infinite;
}
@keyframes kpi-shimmer-fc { 0% { background-position: 100% 50%; } 100% { background-position: 0 50%; } }

/* Acento de color arriba de cada KPI, igual que en el panel principal */
.kpi-card-fc { position: relative; overflow: hidden; }
.kpi-card-fc::before { content:""; position:absolute; top:0; left:0; right:0; height:3px; }
.kpi-card-fc:nth-of-type(1)::before, .col-lg-3:nth-child(1) .kpi-card-fc::before { background:#8b94a6; }
.col-lg-3:nth-child(2) .kpi-card-fc::before { background:#3a57e8; }
.col-lg-3:nth-child(3) .kpi-card-fc::before { background:#4bc7d2; }
.col-lg-3:nth-child(4) .kpi-card-fc::before { background:#d9364a; }

/* ============ TABLA: encabezado fijo + borde de color por fila ============ */
.tabla-fc-scroll { max-height: 640px; overflow-y: auto; }
.tabla-fc thead th { position: sticky; top: 0; background: #fff; z-index: 2; }
.fila-master td:first-child { border-left: 3px solid transparent; }
.fila-master.borde-ok td:first-child { border-left-color: #17904b; }
.fila-master.borde-danger td:first-child { border-left-color: #d9364a; }
.fila-master.borde-warn td:first-child { border-left-color: #ffc107; }

/* ============ SALUD DE AUDITORÍA ============ */
.salud-card-fc { border-radius: 14px; }
.top-desviacion-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 12px; border-radius: 10px; margin-bottom: 8px; background: #f8f9fc;
}
.top-desviacion-item:last-child { margin-bottom: 0; }
.top-desviacion-rank {
    width: 26px; height: 26px; border-radius: 50%; background: #fff; border: 1px solid #e3e6ef;
    display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 800; color: #4b5468;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let datasetConciliaciones = [];
    let datasetFiltradoActual = [];

    const formatMoney = (amount) => new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(amount || 0);

    const formatDate = (dateString) => {
        if (!dateString) return 'S/F';
        let date = new Date(dateString);
        if (isNaN(date)) return 'S/F';
        return date.toLocaleDateString('es-MX', { day: '2-digit', month: '2-digit', year: 'numeric' }) + ' ' +
               date.toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit', hour12: true });
    };

    // BUG FIX: los valores que vienen de la base de datos (comentarios,
    // proveedor, folios) se insertaban directo en innerHTML sin escapar.
    // Si algún campo de texto libre (comentarios, sobre todo) llegara a
    // contener HTML, eso se ejecutaba en el navegador de quien viera el
    // panel -- riesgo de XSS. Ahora todo texto que no sea 100% generado
    // por el sistema pasa por aquí antes de insertarse.
    function escapeHtml(valor) {
        if (valor === null || valor === undefined) return '';
        return String(valor)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    // BUG FIX (criterio único de estatus): antes existían 4 criterios
    // distintos y a veces contradictorios para lo mismo:
    //  - el badge de "Estatus" usaba estos umbrales (10.58 / 120.58)
    //  - el color del texto "Diferencia" usaba otro umbral (0.99)
    //  - el filtro del combo "Estatus Auditoría" usaba otro (0.99)
    //  - el PDF general (-vs) reimplementaba su propio criterio (0.99)
    // Resultado: una fila podía verse "Cuadrado" en verde pero con la
    // diferencia en rojo, o desaparecer al filtrar por "Cuadrado"
    // aunque el badge dijera que lo era. Ahora TODO (badge, color del
    // texto, filtro, exportaciones) llama a esta única función.
    function calcularEstatus(dif) {
        if (Math.abs(dif) <= 10.58) {
            return { key: 'CUADRADO', label: 'Cuadrado', clase: 'status-ok', textoClase: 'text-status-ok', bordeClase: 'borde-ok', colorHex: '#17904b', icono: 'fa-circle-check' };
        } else if (dif > 120.58) {
            return { key: 'MAS', label: 'Cobro de Más', clase: 'status-danger', textoClase: 'text-status-danger', bordeClase: 'borde-danger', colorHex: '#d9364a', icono: 'fa-triangle-exclamation' };
        } else {
            return { key: 'PENDIENTE', label: 'Pendiente XML', clase: 'status-warn', textoClase: 'text-status-warn', bordeClase: 'borde-warn', colorHex: '#ffc107', icono: 'fa-clock-rotate-left' };
        }
    }

    function procesarYRenderizar(conciliaciones) {
        const tbody = $('#tbody-auditoria');
        tbody.empty();

        if (!conciliaciones || conciliaciones.length === 0) {
            tbody.html(`
                <tr>
                    <td colspan="11" class="text-center py-5 text-muted">
                        <i class="fa-solid fa-filter-circle-xmark fa-3x mb-3 d-block text-secondary opacity-50"></i>
                        <span class="fw-bold d-block h5">Sin coincidencias</span>
                        Ningún registro cumple con los criterios de filtrado seleccionados.
                    </td>
                </tr>
            `);
            actualizarKPIs(0, 0, 0, 0);
            renderizarSaludAuditoria([]);
            return;
        }

        let acumuladoCotizado = 0;
        let acumuladoFacturado = 0;
        let desviacionTotal = 0;

        conciliaciones.forEach(c => {
            let cotizado = parseFloat(c.monto_presupuesto || 0);
            let facturado = parseFloat(c.monto_factura || 0);
            let dif = parseFloat(c.diferencia || 0);

            acumuladoCotizado += cotizado;
            acumuladoFacturado += facturado;
            desviacionTotal += dif;

            const estatus = calcularEstatus(dif);
            let prefix = dif > 0 ? '+' : '';

            let filasDetalle = '';
            if (c.detalles_facturas && c.detalles_facturas.length > 0) {
                c.detalles_facturas.forEach(det => {
                    filasDetalle += `
                        <tr class="fs-7">
                            <td class="text-muted fw-bold">#${escapeHtml(det.id)}</td>
                            <td><span class="badge bg-light text-dark border px-2 py-1 fw-bold"><i class="fa-solid fa-receipt me-1 text-secondary"></i>${escapeHtml(det.serie_folio)}</span></td>
                            <td class="text-monospace text-secondary fs-8">${escapeHtml(det.uuid)}</td>
                            <td class="text-end fw-bold text-dark text-monospace">${formatMoney(det.monto_individual_factura)}</td>
                        </tr>
                    `;
                });
            } else {
                filasDetalle = `<tr><td colspan="4" class="text-center text-muted py-3 fs-7 bg-white"><i class="fa-solid fa-circle-info text-info me-1"></i> Sin CFDI físicos asociados.</td></tr>`;
            }

            let filaMaster = `
                <tr class="align-middle fila-master ${estatus.bordeClase}">
                    <td class="text-center">
                        <button class="btn btn-sm btn-icon-action btn-toggle-detalle toggle-detalle" data-id="${c.id}">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </td>
                    <td>
                        <span class="row-folio">${escapeHtml(c.folio_conciliacion)}</span>
                        ${c.comentarios ? `<i class="fa-regular fa-comment-dots text-primary ms-1 cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top" title="${escapeHtml(c.comentarios)}"></i>` : ''}
                    </td>
                    <td><span class="row-folio-badge">${escapeHtml(c.folio_presupuesto)}</span></td>
                    <td><span class="fw-semibold text-dark fs-7">${escapeHtml(c.nombre_sucursal)}</span></td>
                    <td class="text-end fw-semibold text-dark text-monospace">${formatMoney(cotizado)}</td>
                    <td class="text-end fw-semibold text-primary text-monospace">${formatMoney(facturado)}</td>
                    <td class="text-center">
                        <span class="status-pill ${estatus.clase}"><i class="fa-solid ${estatus.icono}"></i>${estatus.label}</span>
                    </td>
                    <td class="text-end text-monospace fw-bold ${estatus.textoClase}">${prefix}${formatMoney(dif)}</td>
                    <td class="text-muted fs-7">${formatDate(c.fecha_presupuesto)}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-icon-action btn-pdf-row btn-exportar-auditoria-pdf"
                                data-id="${c.id}" data-presupuesto="${c.id}"
                                data-detalles='${JSON.stringify(c.detalles_facturas)}'
                                title="Generar PDF de Auditoría">
                            <i class="fa-solid fa-file-pdf"></i>
                        </button>
                    </td>
                    <td class="text-muted fs-7">${formatDate(c.fecha_conciliado)}</td>
                </tr>
                <tr id="detalle-${c.id}" class="d-none">
                    <td colspan="11" class="p-0">
                        <div class="px-5 py-3 detalle-wrapper">
                            <div class="table-responsive col-lg-11 rounded-3 border bg-white shadow-sm">
                                <table class="table table-sm table-striped hover align-middle mb-0">
                                    <thead class="table-dark fs-8 text-uppercase">
                                        <tr>
                                            <th width="12%"># ID Identificador</th>
                                            <th width="20%">Serie / Folio</th>
                                            <th>UUID (Folio Fiscal SAT)</th>
                                            <th width="20%" class="text-end pe-3">Monto Consumido</th>
                                        </tr>
                                    </thead>
                                    <tbody>${filasDetalle}</tbody>
                                </table>
                            </div>
                        </div>
                    </td>
                </tr>
            `;
            tbody.append(filaMaster);
        });

        actualizarKPIs(conciliaciones.length, acumuladoCotizado, acumuladoFacturado, desviacionTotal);
        renderizarSaludAuditoria(conciliaciones);
    }

    $(document).off('click', '.btn-exportar-auditoria-pdf').on('click', '.btn-exportar-auditoria-pdf', function() {
        const idConciliacion = $(this).data('id');
        const detalles = $(this).data('detalles');
        const idPresupuesto = $(this).data('presupuesto');

        if (!detalles || detalles.length === 0) {
            Swal.fire('Atención', 'Esta conciliación no tiene detalles de facturas asociados.', 'warning');
            return;
        }

        const idsDetalles = detalles.map(det => det.id);
        const idsString = idsDetalles.join(',');

        Swal.fire({
            title: 'Generando el reporte de auditoria',
            text: 'Estamos recuperando todos los datos de las facturas...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        $.ajax({
            url: '/cotizaciones/conciliar/reporte/dictamen/final',
            method: 'GET',
            data: {
                id_conciliacion: idConciliacion,
                ids_detalles: idsString,
                id_presupuesto: idPresupuesto
            },
            dataType: 'json',
            success: function(response) {
                Swal.close();
                ejecutarRenderizadoJsPDF(response);
            },
            error: function(xhr) {
                Swal.close();
                console.error('Error al obtener auditoría:', xhr.responseText);
                Swal.fire('Error de Procesamiento', 'Ocurrió un error al cruzar los catálogos de productos.', 'error');
            }
        });
    });

    // Convierte una <img> del DOM a base64 sin tronar si la imagen no
    // cargó (antes esto no tenía try/catch aquí -- un logo roto tiraba
    // todo el PDF de auditoría de la fila).
    function imagenABase64(idElemento) {
        try {
            const img = document.getElementById(idElemento);
            if (!img || !img.complete || img.naturalWidth === 0) return null;
            const canvas = document.createElement('canvas');
            canvas.width = img.naturalWidth;
            canvas.height = img.naturalHeight;
            canvas.getContext('2d').drawImage(img, 0, 0);
            return canvas.toDataURL('image/png');
        } catch (e) {
            console.warn('No se pudo procesar el logo', idElemento, e);
            return null;
        }
    }

    function ejecutarRenderizadoJsPDF(data) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'p', unit: 'mm', format: 'a4' });

        const conciliacion = data.conciliacion;
        const facturas = data.facturas;
        const sucursal = data.sucursal;
        const folioPresupuesto = data.folio_presupuesto;

        const formatMoney = (val) => new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(parseFloat(val || 0));

        let totalSobreprecio = 0;
        let totalPiezasDemas = 0;
        let totalNoCotizado = 0;
        let totalCorrecto = 0;

        facturas.forEach(f => {
            f.productos.forEach(p => {
                const varianza = parseFloat(p.desviacion_monetaria || 0);
                if (p.dictamen_tecnico === 'PRECIO ALTERADO') totalSobreprecio += varianza;
                else if (p.dictamen_tecnico === 'PIEZAS DE MÁS') totalPiezasDemas += varianza;
                else if (p.dictamen_tecnico === 'PRODUCTO NO COTIZADO') totalNoCotizado += varianza;
                else totalCorrecto += varianza;
            });
        });

        doc.setFillColor(30, 41, 59);
        doc.rect(0, 0, 210, 35, 'F');

        doc.setTextColor(255, 255, 255);
        doc.setFont("helvetica", "bold");
        doc.setFontSize(16);
        doc.text("DICTAMEN DE PRESUPUESTO CONCILIADO", 14, 13);

        doc.setFont("helvetica", "normal");
        doc.setFontSize(9);
        doc.text(`Folio Interno: ${conciliacion.folio_conciliacion}`, 14, 21);
        doc.text(`Fecha Cierre: ${conciliacion.fecha_conciliado || 'En Proceso'}  |  Auditoría: ${new Date().toLocaleString('es-MX')}`, 14, 26);
        doc.text(`Sucursal: ${sucursal || 'S/N'}  |  Presupuesto: ${folioPresupuesto || 'S/N'}`, 14, 31);

        const imgData1 = imagenABase64('logo_vss');
        if (imgData1) doc.addImage(imgData1, 'PNG', 165, 8, 35, 10);

        doc.setFillColor(248, 250, 252);
        doc.rect(14, 40, 182, 22, 'F');
        doc.setDrawColor(226, 232, 240);
        doc.rect(14, 40, 182, 22, 'D');

        doc.setTextColor(71, 85, 105);
        doc.setFont("helvetica", "bold");
        doc.setFontSize(9);
        doc.text("PRESUPUESTO AUTORIZADO", 18, 46);
        doc.text("TOTAL FACTURADO", 80, 46);
        doc.text("VARIACIÓN GLOBAL", 140, 46);

        doc.setTextColor(15, 23, 42);
        doc.setFontSize(12);
        doc.text(formatMoney(conciliacion.monto_presupuesto), 18, 53);
        doc.text(formatMoney(conciliacion.monto_factura), 80, 53);

        const diff = parseFloat(conciliacion.diferencia);
        const estatusPdf = calcularEstatus(diff);
        if (estatusPdf.key === 'MAS') doc.setTextColor(220, 53, 69);
        else if (estatusPdf.key === 'PENDIENTE') doc.setTextColor(243, 156, 18);
        else doc.setTextColor(40, 167, 69);
        doc.text(`${formatMoney(diff)}`, 140, 53);

        let currentY = 68;
        doc.setTextColor(51, 65, 85);
        doc.setFont("helvetica", "bold");
        doc.setFontSize(10);
        doc.text("DESGLOSE DE ANOMALÍAS DETECTADAS (FUGAS DE CAPITAL)", 14, currentY);
        currentY += 4;

        doc.setFillColor(254, 242, 242); doc.setDrawColor(252, 165, 165);
        doc.rect(14, currentY, 58, 16, 'FD');
        doc.setTextColor(153, 27, 27); doc.setFontSize(7); doc.text("SOBREPRECIOS", 18, currentY + 5);
        doc.setFontSize(10); doc.text(formatMoney(totalSobreprecio), 18, currentY + 11);

        doc.setFillColor(255, 251, 235); doc.setDrawColor(252, 211, 77);
        doc.rect(76, currentY, 58, 16, 'FD');
        doc.setTextColor(146, 64, 14); doc.setFontSize(7); doc.text("PIEZAS EN EXCESO", 80, currentY + 5);
        doc.setFontSize(10); doc.text(formatMoney(totalPiezasDemas), 80, currentY + 11);

        doc.setFillColor(240, 253, 250); doc.setDrawColor(153, 246, 228);
        doc.rect(138, currentY, 58, 16, 'FD');
        doc.setTextColor(17, 94, 89); doc.setFontSize(7); doc.text("FUERA DE CATÁLOGO", 142, currentY + 5);
        doc.setFontSize(10); doc.text(formatMoney(totalNoCotizado), 142, currentY + 11);

        currentY += 24;

        if (conciliacion.comentarios) {
            doc.setTextColor(100, 116, 139);
            doc.setFont("helvetica", "italic");
            doc.setFontSize(8.5);
            doc.text(`Nota de auditoría: "${conciliacion.comentarios}"`, 14, currentY);
            currentY += 8;
        }

        if (facturas.length === 0) {
            doc.setTextColor(148, 163, 184);
            doc.text("No se encontraron desgloses de productos para auditar.", 14, currentY);
        } else {
            facturas.forEach((factura) => {
                if (currentY > 240) { doc.addPage(); currentY = 20; }

                doc.setFillColor(241, 245, 249);
                doc.rect(14, currentY, 182, 6, 'F');
                doc.setTextColor(51, 65, 85);
                doc.setFont("helvetica", "bold");
                doc.setFontSize(9);
                doc.text(`Serie y folio: ${factura.serie_folio ?? 'S/F'} |  UUID: ${factura.folio_factura}`, 17, currentY + 4.5);
                currentY += 8;

                const filasTabla = factura.productos.map(p => [
                    p.codigo,
                    p.nombre,
                    `${parseFloat(p.cant_cotizada)} / ${parseFloat(p.cant_facturada)}`,
                    formatMoney(p.precio_cotizado),
                    formatMoney(p.precio_unitario),
                    formatMoney(p.impor_con_des),
                    p.dictamen_tecnico,
                    formatMoney(p.desviacion_monetaria)
                ]);

                doc.autoTable({
                    startY: currentY,
                    head: [['Código', 'Descripción', 'Cant (Cot/Fac)', 'P. Cotizado', 'P. Unit Fac', 'Subtotal Fac', 'Dictamen', 'Varianza']],
                    body: filasTabla,
                    theme: 'striped',
                    headStyles: { fillColor: [71, 85, 105], fontSize: 7.5, fontStyle: 'bold' },
                    bodyStyles: { fontSize: 7.5 },
                    columnStyles: {
                        2: { halign: 'center' },
                        3: { halign: 'right' },
                        4: { halign: 'right' },
                        5: { halign: 'right' },
                        6: { halign: 'center', fontStyle: 'bold' },
                        7: { halign: 'right' }
                    },
                    didParseCell: function(cellData) {
                        if (cellData.section === 'body' && cellData.column.index === 6) {
                            const val = cellData.cell.raw;
                            if (val === 'CORRECTO') cellData.cell.styles.textColor = [21, 128, 61];
                            else if (val === 'PRECIO ALTERADO' || val === 'PRODUCTO NO COTIZADO') cellData.cell.styles.textColor = [185, 28, 28];
                            else cellData.cell.styles.textColor = [180, 83, 9];
                        }
                    }
                });

                currentY = doc.lastAutoTable.finalY + 12;
            });
        }
        window.open(doc.output('bloburl'), '_blank');
    }

    // BUG FIX: se eliminó por completo el listener que estaba aquí,
    // enganchado a "#btn-exportar-pdf-general" -- ese id NO EXISTE en
    // el HTML (el botón real es "#btn-exportar-pdf-general-vs"), así
    // que era una función entera (generación de PDF landscape) que
    // nunca se ejecutaba: código muerto. La funcionalidad real vive
    // en el handler de "#btn-exportar-pdf-general-vs" más abajo.

    function actualizarKPIs(cantidad, cotizado, facturado, desviacion) {
        $('#contador-registros').text(`${cantidad} conciliaciones filtradas`);
        $('.kpi-skeleton-fc').removeClass('kpi-skeleton-fc');

        $('#kpi-cantidad').text(cantidad);
        $('#kpi-cotizado').text(formatMoney(cotizado));
        $('#kpi-facturado').text(formatMoney(facturado));

        let kpiDesviacion = $('#kpi-desviacion');
        kpiDesviacion.text((desviacion > 0 ? '+' : '') + formatMoney(desviacion));

        // Mismo criterio unificado también aquí (antes eran umbrales
        // sueltos de 1 y -1 que no coincidían con calcularEstatus()).
        const estatusGlobal = calcularEstatus(desviacion);
        kpiDesviacion.removeClass('text-status-ok text-status-danger text-status-warn');
        kpiDesviacion.addClass(estatusGlobal.textoClase);

        let pct = cotizado > 0 ? (desviacion / cotizado * 100) : 0;
        $('#kpi-desviacion-pct').text(`${pct > 0 ? '+' : ''}${pct.toFixed(1)}% del cotizado`);

        $('#lbl-ultima-actualizacion').text(new Date().toLocaleString('es-MX', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit', hour12:true }));
    }

    // ============================================================
    //  SALUD DE AUDITORÍA (dona de efectividad + top desviaciones)
    //  Da al gerente, de un vistazo, qué tan sano está el proceso de
    //  conciliación y dónde están concentradas las fugas -- sin tener
    //  que leer fila por fila de la tabla.
    // ============================================================
    let chartEfectividad = null;

    function renderizarSaludAuditoria(conciliaciones) {
        const contDona = document.getElementById('grafica_efectividad');
        const contLista = document.getElementById('lista_top_desviaciones');
        if (!contDona || !contLista) return;

        if (chartEfectividad) { chartEfectividad.destroy(); chartEfectividad = null; }
        contDona.innerHTML = '';
        contLista.innerHTML = '';

        if (!conciliaciones || conciliaciones.length === 0) {
            contDona.innerHTML = '<p class="text-center text-muted py-4 small mb-0">Sin datos para este filtro.</p>';
            contLista.innerHTML = '<p class="text-center text-muted py-4 small mb-0">Sin datos para este filtro.</p>';
            return;
        }

        let conteos = { CUADRADO: 0, MAS: 0, PENDIENTE: 0 };
        conciliaciones.forEach(c => {
            const key = calcularEstatus(parseFloat(c.diferencia || 0)).key;
            conteos[key]++;
        });

        if (typeof ApexCharts !== 'undefined') {
            const opciones = {
                series: [conteos.CUADRADO, conteos.MAS, conteos.PENDIENTE],
                labels: ['Cuadrado', 'Cobro de más', 'Pendiente XML'],
                colors: ['#17904b', '#d9364a', '#ffc107'],
                chart: { type: 'donut', height: 320, fontFamily: 'inherit' },
                stroke: { width: 2, colors: ['#fff'] },
                legend: { position: 'bottom', fontSize: '12.5px', markers: { size: 8 }, itemMargin: { horizontal: 8, vertical: 4 } },
                dataLabels: { enabled: true, style: { fontSize: '13px', fontWeight: 700 }, formatter: (val) => Math.round(val) + '%' },
                plotOptions: {
                    pie: { donut: { size: '66%', labels: {
                        show: true,
                        total: { show: true, label: 'Conciliaciones', fontSize: '12px', color: '#8891a5', formatter: () => conciliaciones.length },
                        value: { fontSize: '26px', fontWeight: 800, color: '#16233f' }
                    } } }
                }
            };
            chartEfectividad = new ApexCharts(contDona, opciones);
            chartEfectividad.render();
        }

        // Top 5 por desviación absoluta -- lo que más le urge revisar
        // al gerente, sin importar si es cobro de más o factura corta.
        const top5 = [...conciliaciones]
            .map(c => ({ ...c, difAbs: Math.abs(parseFloat(c.diferencia || 0)) }))
            .sort((a, b) => b.difAbs - a.difAbs)
            .slice(0, 5);

        if (top5.every(c => c.difAbs <= 10.58)) {
            contLista.innerHTML = '<p class="text-center text-muted py-4 small mb-0"><i class="fa-solid fa-circle-check text-success me-1"></i>Todo dentro de rango, sin desviaciones relevantes.</p>';
            return;
        }

        top5.forEach((c, i) => {
            const dif = parseFloat(c.diferencia || 0);
            const estatus = calcularEstatus(dif);
            const prefix = dif > 0 ? '+' : '';
            contLista.innerHTML += `
                <div class="top-desviacion-item">
                    <div class="d-flex align-items-center gap-3">
                        <span class="top-desviacion-rank">${i + 1}</span>
                        <div>
                            <span class="d-block fw-bold text-dark" style="font-size:12.5px;">${escapeHtml(c.folio_presupuesto || c.folio_conciliacion)}</span>
                            <span class="d-block text-muted" style="font-size:11px;">${escapeHtml(c.nombre_sucursal)}</span>
                        </div>
                    </div>
                    <span class="fw-bold ${estatus.textoClase}" style="font-size:13px;">${prefix}${formatMoney(dif)}</span>
                </div>`;
        });
    }


    function ejecutarFiltradoEstrategico() {
        let busqueda = $('#filtro-buscar').val().toLowerCase().trim();
        let estatusSeleccionado = $('#filtro-estatus').val();
        let sucursalFiltro = $('#filtro-sucursal').val().toLowerCase();
        let fechaInicio = $('#filtro-fecha-inicio').val();
        let fechaFin = $('#filtro-fecha-fin').val();

        let datosFiltrados = datasetConciliaciones.filter(c => {

            let matchTexto = true;
            if (busqueda !== "") {
                matchTexto = c.folio_conciliacion.toLowerCase().includes(busqueda) ||
                             String(c.id_presupuesto).includes(busqueda) ||
                             (c.comentarios && c.comentarios.toLowerCase().includes(busqueda));
            }

            // BUG FIX: antes este filtro usaba su propio umbral (0.99)
            // distinto al que usa calcularEstatus() para pintar el
            // badge (10.58 / 120.58) -- una fila podía verse "Cuadrado"
            // en la tabla y no aparecer al filtrar por "Cuadrado".
            // Ahora usa exactamente la misma función.
            let matchEstatus = true;
            if (estatusSeleccionado !== 'TODOS') {
                let dif = parseFloat(c.diferencia || 0);
                matchEstatus = calcularEstatus(dif).key === estatusSeleccionado;
            }

            let matchSucursal = true;
            if (sucursalFiltro !== "") {
                matchSucursal = c.nombre_sucursal.toLowerCase().includes(sucursalFiltro);
            }

            // Nombre corregido: este campo compara contra la fecha del
            // PRESUPUESTO, no la de cierre (el nombre original decía
            // "fechaCierreStr" pero guardaba fecha_presupuesto).
            let matchFecha = true;
            if (c.fecha_presupuesto) {
                let fechaPresupuestoStr = c.fecha_presupuesto.split(' ')[0];
                if (fechaInicio && fechaPresupuestoStr < fechaInicio) matchFecha = false;
                if (fechaFin && fechaPresupuestoStr > fechaFin) matchFecha = false;
            }

            return matchTexto && matchEstatus && matchFecha && matchSucursal;
        });

        datasetFiltradoActual = datosFiltrados;
        procesarYRenderizar(datosFiltrados);
    }

    $('#filtro-buscar').on('input', ejecutarFiltradoEstrategico);
    $('#filtro-estatus,#filtro-sucursal,#filtro-fecha-inicio, #filtro-fecha-fin').on('change', ejecutarFiltradoEstrategico);

    $('#btn-limpiar-filtros').on('click', function() {
        $('#filtro-buscar').val('');
        $('#filtro-estatus').val('TODOS');
        $('#filtro-sucursal').val('');
        $('#filtro-fecha-inicio').val('');
        $('#filtro-fecha-fin').val('');
        datasetFiltradoActual = datasetConciliaciones;
        procesarYRenderizar(datasetConciliaciones);
    });

    function cargarHistorialAuditoria() {
        $.ajax({
            url: '/cotizaciones/conciliar/facturas/reportes/get/datos',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                datasetConciliaciones = response.conciliaciones || [];
                datasetFiltradoActual = datasetConciliaciones;
                procesarYRenderizar(datasetConciliaciones);
            },
            error: function(xhr) {
                console.error('Error al cargar conciliaciones:', xhr.responseText);
                $('#tbody-auditoria').html(`
                    <tr><td colspan="11" class="text-center py-5 text-muted">
                        <i class="fa-solid fa-triangle-exclamation fa-2x mb-3 d-block text-warning"></i>
                        No se pudo cargar la información. Intenta recargar la página.
                    </td></tr>`);
            }
        });
    }

    $('#btn-exportar-pdf-general-pagos').on('click', function () {
        const f_inicial = document.getElementById('filtro-fecha-inicio').value;
        const f_final = document.getElementById('filtro-fecha-fin').value;

        if (!f_inicial || !f_final) {
            Swal.fire({ icon: 'warning', title: 'Fechas requeridas', text: 'Para generar el reporte de pago es necesario filtrar un rango de fechas.', confirmButtonText: 'Aceptar' });
            return;
        }
        if (f_final < f_inicial) {
            Swal.fire({ icon: 'error', title: 'Rango inválido', text: 'La fecha final no puede ser menor a la fecha de inicio.', confirmButtonText: 'Aceptar' });
            return;
        }

        fetch(`/cotizaciones/conciliar/reporte/pago/${f_inicial}/${f_final}`)
            .then(response => {
                if (!response.ok) throw new Error('Error al consultar el servidor');
                return response.json();
            })
            .then(respuesta => {
                if (!respuesta.success || Object.keys(respuesta.data).length === 0) {
                    Swal.fire('Sin Datos', 'No se encontraron facturas timbradas en el rango seleccionado.', 'info');
                    return;
                }
                pdfPagos(respuesta);
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error', 'Ocurrió un problema al obtener los datos de pago.', 'error');
            });

        function pdfPagos(respuesta) {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'letter' });
            const formatMoney = (val) => new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(val || 0);

            const imgData1 = imagenABase64('logo');
            if (imgData1) doc.addImage(imgData1, 'PNG', 225, 5, 40, 12);

            let nombre = respuesta.usuario.nombre;
            let apellido_p = respuesta.usuario.apellido_p;
            const { resumen, rango, data } = respuesta;

            doc.setFont('helvetica', 'bold');
            doc.setFontSize(14);
            doc.setTextColor(30, 41, 59);
            doc.text('REPORTE DE PAGOS AL MES - TIENDAS HERRALUM CHIAPAS.', 14, 13);

            doc.setFont('helvetica', 'normal');
            doc.setFontSize(8.5);
            doc.setTextColor(100, 116, 139);
            doc.text(`PERIODO DE CONSULTA: ${rango.inicio} al ${rango.fin}`, 14, 18);

            doc.setDrawColor(226, 232, 240);
            doc.setLineWidth(0.6);
            doc.line(14, 22, 265, 22);

            const drawCard = (x, title, value, statusColor = [30, 41, 59]) => {
                doc.setFillColor(248, 250, 252);
                doc.roundedRect(x, 25, 58, 15, 2, 2, 'F');
                doc.setDrawColor(226, 232, 240);
                doc.roundedRect(x, 25, 58, 15, 2, 2, 'S');
                doc.setFontSize(7);
                doc.setFont('helvetica', 'bold');
                doc.setTextColor(100, 116, 139);
                doc.text(title, x + 4, 30);
                doc.setFontSize(10);
                doc.setFont('helvetica', 'bold');
                doc.setTextColor(...statusColor);
                doc.text(value, x + 4, 37);
            };

            drawCard(14, 'TOTAL A PAGAR DEL MES', formatMoney(resumen.monto_total_pagar), [15, 23, 42]);
            drawCard(76, 'TOTAL PRESUPUESTOS AUTORIZADOS', formatMoney(resumen.monto_mes_actual), [22, 163, 74]);
            drawCard(138, 'TOTAL REZAGOS / MESES ANTERIORES', formatMoney(resumen.monto_rezagado), [217, 119, 6]);
            drawCard(200, 'TOTAL DE FACTURAS TIMBRADAS', `${resumen.total_facturas} Facturas`, [71, 85, 105]);

            let startY = 45;

            Object.keys(data).forEach((nombreSucursal) => {
                const grupoSucursal = data[nombreSucursal];

                doc.setFont('helvetica', 'bold');
                doc.setFontSize(10);
                doc.setTextColor(30, 41, 59);

                if (startY > 175) { doc.addPage(); startY = 15; }

                doc.setFillColor(241, 245, 249);
                doc.rect(14, startY, 251, 6, 'F');
                doc.text(`${nombreSucursal.toUpperCase()} — Total a Pagar: ${formatMoney(grupoSucursal.total_sucursal)} (Rezagado: ${formatMoney(grupoSucursal.total_rezagado)})`, 16, startY + 4.5);

                startY += 8;
                let tableRows = [];

                Object.keys(grupoSucursal.presupuestos).forEach((folioPresupuesto) => {
                    const facturasPresupuesto = grupoSucursal.presupuestos[folioPresupuesto];
                    const primerRegistro = facturasPresupuesto[0];
                    tableRows.push([{
                        content: `PRESUPUESTO: ${folioPresupuesto} | Fecha Cotización: ${primerRegistro.fecha_cotizacion ? primerRegistro.fecha_cotizacion.slice(0, 10) : 'N/A'} | Origen: ${primerRegistro.origen_presupuesto}`,
                        colSpan: 8,
                        styles: { fontStyle: 'bold', fillColor: [226, 232, 240], textColor: [30, 41, 59] }
                    }]);
                    facturasPresupuesto.forEach(f => {
                        tableRows.push([
                            f.serie_folio || 'S/F',
                            f.uuid ? f.uuid.substring(0, 20) + '...' : '—',
                            f.fecha_timbrado ? f.fecha_timbrado.slice(0, 10) : '—',
                            f.proveedor_factura || f.rfc || '—',
                            formatMoney(f.subtotal), formatMoney(f.iva), formatMoney(f.retencion), formatMoney(f.total_factura)
                        ]);
                    });
                });

                doc.autoTable({
                    startY: startY,
                    head: [['Serie / Folio', 'UUID', 'F. Timbrado', 'Proveedor / RFC', 'Subtotal', 'IVA', 'Retención', 'Total Factura']],
                    body: tableRows,
                    theme: 'grid',
                    styles: { fontSize: 6.5, cellPadding: 1.5, textColor: [51, 65, 85], valign: 'middle' },
                    headStyles: { fillColor: [51, 65, 85], textColor: [255, 255, 255], fontStyle: 'bold', halign: 'center' },
                    columnStyles: {
                        0: { fontStyle: 'bold', cellWidth: 25 }, 1: { cellWidth: 32 }, 2: { halign: 'center', cellWidth: 22 },
                        4: { halign: 'right' }, 5: { halign: 'right' }, 6: { halign: 'right' }, 7: { halign: 'right', fontStyle: 'bold' }
                    },
                    didParseCell: function (cellData) {
                        if (cellData.section === 'body' && cellData.row.raw[0] && cellData.row.raw[0].content && cellData.row.raw[0].content.includes('REZAGADO')) {
                            cellData.cell.styles.fillColor = [254, 237, 213];
                        }
                    },
                    didDrawPage: function (pageData) {
                        let pageCount = doc.internal.getNumberOfPages();
                        doc.setFontSize(7);
                        doc.setFont('helvetica', 'normal');
                        doc.setTextColor(148, 163, 184);
                        doc.text(`Página ${pageData.pageNumber} de ${pageCount}`, 265, 205, { align: 'right' });
                        doc.text(`Generado por el sistema GIFRONTERASUR.COM.MX -- EMISION: ${new Date().toLocaleString('es-MX')} | Genero: ${nombre} ${apellido_p}`, 14, 205);
                    }
                });

                startY = doc.lastAutoTable.finalY + 6;
            });

            doc.save(`Reporte_Pagos_Mes_${rango.inicio}_a_${rango.fin}.pdf`);
        }
    });

    $('#btn-exportar-pdf-general-vs').on('click', function () {
        const busqueda = $('#filtro-buscar').val() ? $('#filtro-buscar').val().toString().trim().toLowerCase() : '';
        const estatusFiltro = $('#filtro-estatus').val() || 'TODOS';
        const sucursalFiltro = $('#filtro-sucursal').val() ? $('#filtro-sucursal').val().toString().trim().toLowerCase() : '';
        const fechaInicioStr = $('#filtro-fecha-inicio').val() || null;
        const fechaFinStr = $('#filtro-fecha-fin').val() || null;

        const logoFronteraB64 = imagenABase64('logo_frontera');
        const logoVssB64 = imagenABase64('logo_vss');

        $.ajax({
            url: '/cotizaciones/conciliar/facturas/reportes/get/datos',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                let conciliaciones = response.conciliaciones || [];
                let presupuestosGroup = response.presupuestos || {};
                let datasetUnificado = [];

                conciliaciones.forEach(c => {
                    let mCotizado = parseFloat(c.monto_presupuesto || 0);
                    let mFacturado = parseFloat(c.monto_factura || 0);
                    let dif = parseFloat(c.diferencia || (mFacturado - mCotizado));
                    let rawFechaPresupuesto = c.fecha_presupuesto || '';
                    let fechaPresupuestoStr = rawFechaPresupuesto ? rawFechaPresupuesto.split(' ')[0] : '';

                    // BUG FIX: usa el mismo calcularEstatus() que el resto
                    // del panel en vez de su propio criterio hardcodeado.
                    datasetUnificado.push({
                        folio_conciliacion: c.folio_conciliacion || 'S/F',
                        id_presupuesto: c.id_presupuesto || '#',
                        folio_presupuesto: c.folio_presupuesto || '—',
                        nombre_sucursal: c.nombre_sucursal || '—',
                        monto_cotizado: mCotizado,
                        monto_facturado: mFacturado,
                        diferencia: dif,
                        estatusKey: calcularEstatus(dif).key,
                        estatus: c.estatus || calcularEstatus(dif).label,
                        fecha_cierre: c.fecha_conciliado ? c.fecha_conciliado.slice(0, 10) : '—',
                        fecha_evaluar_str: fechaPresupuestoStr,
                        comentarios: c.comentarios || 'Cierre completado'
                    });
                });

                Object.keys(presupuestosGroup).forEach(folio => {
                    let items = presupuestosGroup[folio];
                    if (!items || items.length === 0) return;

                    let primerRegistro = items[0];
                    let mCotizado = parseFloat(primerRegistro.total_cotizacion || 0);
                    let mFacturado = items.reduce((acc, curr) => acc + parseFloat(curr.total_factura || 0), 0);
                    let dif = mFacturado - mCotizado;

                    let estatusPendiente = 'PENDIENTE DE CIERRE';
                    if (mFacturado === 0) estatusPendiente = 'SIN FACTURAR';
                    else if (dif < -0.99) estatusPendiente = 'PARCIALMENTE FACTURADO';
                    else if (Math.abs(dif) <= 0.99) estatusPendiente = 'FACTURADO (PEND. CIERRE)';
                    else estatusPendiente = 'SOBRE-FACTURADO (PEND. CIERRE)';

                    let rawFecha = primerRegistro.fecha_cotizacion || '';
                    let fechaPresupuestoStr = rawFecha ? rawFecha.split(' ')[0] : '';

                    datasetUnificado.push({
                        folio_conciliacion: 'SIN-FOLIO',
                        id_presupuesto: primerRegistro.cotizacion_id || '#',
                        folio_presupuesto: primerRegistro.presupuesto_folio,
                        nombre_sucursal: primerRegistro.nombre_sucursal || '—',
                        monto_cotizado: mCotizado,
                        monto_facturado: mFacturado,
                        diferencia: dif,
                        estatusKey: null, // pendientes de cierre no entran en el filtro de estatus (no tienen conciliación aún)
                        estatus: estatusPendiente,
                        fecha_cierre: 'SIN-CERRAR',
                        fecha_evaluar_str: fechaPresupuestoStr,
                        comentarios: 'En proceso de facturación/cierre'
                    });
                });

                let datosFiltrados = datasetUnificado.filter(item => {
                    let matchTexto = true;
                    if (busqueda !== '') {
                        matchTexto = item.folio_conciliacion.toLowerCase().includes(busqueda) ||
                                     String(item.id_presupuesto).toLowerCase().includes(busqueda) ||
                                     item.folio_presupuesto.toLowerCase().includes(busqueda) ||
                                     item.comentarios.toLowerCase().includes(busqueda);
                    }

                    // BUG FIX: mismo criterio que el filtro en pantalla.
                    // Los renglones "pendientes de cierre" (sin conciliación
                    // aún) se muestran solo cuando el filtro está en
                    // "Todos los estatus", porque no tienen un estatus
                    // conciliado que comparar.
                    let matchEstatus = true;
                    if (estatusFiltro !== 'TODOS') {
                        matchEstatus = item.estatusKey === estatusFiltro;
                    }

                    let matchSucursal = true;
                    if (sucursalFiltro !== '') matchSucursal = item.nombre_sucursal.toLowerCase().includes(sucursalFiltro);

                    let matchFecha = true;
                    if (item.fecha_evaluar_str) {
                        if (fechaInicioStr && item.fecha_evaluar_str < fechaInicioStr) matchFecha = false;
                        if (fechaFinStr && item.fecha_evaluar_str > fechaFinStr) matchFecha = false;
                    }

                    return matchTexto && matchEstatus && matchSucursal && matchFecha;
                });

                if (datosFiltrados.length === 0) {
                    Swal.fire('Atención', 'No se encontraron registros que coincidan con los filtros seleccionados.', 'warning');
                    return;
                }

                const { jsPDF } = window.jspdf;
                const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'letter' });
                const formatMoney = (val) => new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(val);

                if (logoFronteraB64) doc.addImage(logoFronteraB64, 'PNG', 14, 8, 32, 14);
                if (logoVssB64) doc.addImage(logoVssB64, 'JPEG', 230, 8, 30, 14);

                doc.setFont('helvetica', 'bold');
                doc.setFontSize(14);
                doc.setTextColor(30, 41, 59);
                doc.text('REPORTE DE PRESUPUESTOS CONCILIADOS Y DIFERENCIAS', 50, 13);

                doc.setFont('helvetica', 'normal');
                doc.setFontSize(8.5);
                doc.setTextColor(100, 116, 139);

                let textoSucursal = sucursalFiltro ? ($('#filtro-sucursal option:selected').text() || sucursalFiltro) : 'TODAS LAS SUCURSALES';
                let textoRango = (fechaInicioStr || fechaFinStr) ? `${fechaInicioStr || 'Inicio'} al ${fechaFinStr || 'Hoy'}` : 'TODOS LOS REGISTROS';
                let infoLinea = `${textoSucursal}  |  FECHA: ${textoRango}  |  GENERADO: ${new Date().toLocaleString('es-MX')}`;
                doc.text(infoLinea, 50, 18);

                doc.setDrawColor(226, 232, 240);
                doc.setLineWidth(0.6);
                doc.line(14, 25, 265, 25);

                let totalCotizado = datosFiltrados.reduce((acc, d) => acc + d.monto_cotizado, 0);
                let totalFacturado = datosFiltrados.reduce((acc, d) => acc + d.monto_facturado, 0);
                let totalDiferencia = totalFacturado - totalCotizado;
                let conciliadosCount = datosFiltrados.filter(d => Math.abs(d.diferencia) <= 0.99).length;

                const drawCard = (x, title, value, statusColor = [30, 41, 59]) => {
                    doc.setFillColor(248, 250, 252);
                    doc.roundedRect(x, 28, 58, 15, 2, 2, 'F');
                    doc.setDrawColor(226, 232, 240);
                    doc.roundedRect(x, 28, 58, 15, 2, 2, 'S');
                    doc.setFontSize(7);
                    doc.setFont('helvetica', 'bold');
                    doc.setTextColor(100, 116, 139);
                    doc.text(title, x + 4, 33);
                    doc.setFontSize(10);
                    doc.setFont('helvetica', 'bold');
                    doc.setTextColor(...statusColor);
                    doc.text(value, x + 4, 40);
                };

                drawCard(14, 'TOTAL COTIZADO', formatMoney(totalCotizado));
                drawCard(76, 'TOTAL FACTURADO', formatMoney(totalFacturado));

                let colorDif = totalDiferencia < -0.99 ? [220, 38, 38] : (totalDiferencia > 0.99 ? [217, 119, 6] : [22, 163, 74]);
                drawCard(138, 'DESVIACIÓN NETA', formatMoney(totalDiferencia), colorDif);
                drawCard(200, 'EFECTIVIDAD DE CIERRE', `${conciliadosCount} de ${datosFiltrados.length} Conciliados`, [15, 23, 42]);

                let tableBody = datosFiltrados.map(d => [
                    d.folio_conciliacion, d.id_presupuesto, d.folio_presupuesto, d.nombre_sucursal,
                    formatMoney(d.monto_cotizado), formatMoney(d.monto_facturado), formatMoney(d.diferencia),
                    d.estatus, d.fecha_cierre, d.comentarios
                ]);

                tableBody.push([
                    { content: 'TOTALES GENERALES', colSpan: 4, styles: { fontStyle: 'bold', halign: 'right', fillColor: [241, 245, 249] } },
                    { content: formatMoney(totalCotizado), styles: { fontStyle: 'bold', fillColor: [241, 245, 249] } },
                    { content: formatMoney(totalFacturado), styles: { fontStyle: 'bold', fillColor: [241, 245, 249] } },
                    { content: formatMoney(totalDiferencia), styles: { fontStyle: 'bold', fillColor: [241, 245, 249], textColor: colorDif } },
                    { content: '', colSpan: 3, styles: { fillColor: [241, 245, 249] } }
                ]);

                doc.autoTable({
                    startY: 47,
                    head: [['Folio Conc.', 'ID', '# Presupuesto', 'Sucursal', 'Monto Cotizado', 'Monto Facturado', 'Diferencia', 'Estatus Financiero', 'Fecha Cierre', 'Comentarios']],
                    body: tableBody,
                    theme: 'grid',
                    styles: { fontSize: 7, cellPadding: 2, textColor: [51, 65, 85], valign: 'middle' },
                    headStyles: { fillColor: [30, 41, 59], textColor: [255, 255, 255], fontStyle: 'bold', halign: 'center' },
                    columnStyles: {
                        0: { fontStyle: 'bold', cellWidth: 26 }, 1: { halign: 'center', cellWidth: 12 }, 2: { cellWidth: 22 },
                        4: { halign: 'right' }, 5: { halign: 'right' }, 6: { halign: 'right', fontStyle: 'bold' },
                        7: { halign: 'center', fontStyle: 'bold', cellWidth: 30 }, 8: { halign: 'center', cellWidth: 20 }
                    },
                    didParseCell: function (data) {
                        if (data.section === 'body' && data.row.raw[0] === 'SIN-FOLIO') {
                            data.cell.styles.fillColor = [254, 243, 199];
                        }
                        if (data.section === 'body' && data.column.index === 6 && data.row.index < datosFiltrados.length) {
                            let val = datosFiltrados[data.row.index].diferencia;
                            if (val < -0.99) data.cell.styles.textColor = [220, 38, 38];
                            else if (val > 0.99) data.cell.styles.textColor = [217, 119, 6];
                            else data.cell.styles.textColor = [22, 163, 74];
                        }
                    },
                    didDrawPage: function (data) {
                        let pageCount = doc.internal.getNumberOfPages();
                        doc.setFontSize(7);
                        doc.setFont('helvetica', 'normal');
                        doc.setTextColor(148, 163, 184);
                        doc.text(`Página ${data.pageNumber} de ${pageCount}`, 265, 202, { align: 'right' });
                        doc.text('REPORTE GENERADO POR EL SISTEMA DE GIFRONTERASUR', 14, 202);
                    }
                });

                let nomSuc = sucursalFiltro ? sucursalFiltro.replace(/\s+/g, '_') : 'GENERAL';
                doc.save(`reporte_Facturas_presupuestos_${nomSuc}_${new Date().toISOString().slice(0,10)}.pdf`);
            },
            error: function (err) {
                console.error('Error al consultar datos:', err);
                Swal.fire('Error', 'No se pudieron recuperar los datos para el reporte.', 'error');
            }
        });
    });

    $('#btn-exportar-excel').on('click', function() {
        if (!datasetFiltradoActual || datasetFiltradoActual.length === 0) {
            Swal.fire('Atención', 'No hay registros visibles en este momento para exportar.', 'warning');
            return;
        }

        let filasExcel = datasetFiltradoActual.map(c => ({
            'Folio Conciliación': c.folio_conciliacion,
            'ID Presupuesto': c.id_presupuesto,
            '# Presupuesto': c.folio_presupuesto,
            'Sucursal': c.nombre_sucursal,
            'Monto Cotizado (MXN)': parseFloat(c.monto_presupuesto || 0),
            'Monto Facturado (MXN)': parseFloat(c.monto_factura || 0),
            'Diferencia Auditoría': parseFloat(c.diferencia || 0),
            'Estatus Financiero': calcularEstatus(parseFloat(c.diferencia || 0)).label,
            'Fecha de Cierre': c.fecha_conciliado,
            'comentarios': c.comentarios,
        }));

        let wb = XLSX.utils.book_new();
        let ws = XLSX.utils.json_to_sheet(filasExcel);
        XLSX.utils.book_append_sheet(wb, ws, "Auditoría Filtrada");
        XLSX.writeFile(wb, `Reporte_Auditoria_Conciliaciones_${new Date().toISOString().slice(0,10)}.xlsx`);
    });

    cargarHistorialAuditoria();
    $('#btn-refresh').on('click', cargarHistorialAuditoria);

    $(document).on('click', '.toggle-detalle', function() {
        let btn = $(this);
        let id = btn.data('id');
        let filaDetalle = $(`#detalle-${id}`);
        if (filaDetalle.hasClass('d-none')) {
            filaDetalle.removeClass('d-none');
            btn.addClass('is-open');
        } else {
            filaDetalle.addClass('d-none');
            btn.removeClass('is-open');
        }
    });
});
</script>
@endsection