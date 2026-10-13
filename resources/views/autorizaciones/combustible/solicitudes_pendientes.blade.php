@extends('layout.index')
@section('content')
<div class="main-content">
    <div class="container-fluid content-inner py-4">

        {{-- ============ ENCABEZADO ============ --}}
        <div class="d-flex flex-wrap justify-content-between align-items-start mb-4 gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="cb-icon-badge"><i class="fa-solid fa-clock-rotate-left"></i></div>
                <div>
                    <h1 class="h3 fw-bold mb-1 text-dark">Solicitudes pendientes</h1>
                    <p class="text-muted mb-0">Autoriza las solicitudes y compara el rendimiento base el GPS</p>
                </div>
            </div>
        </div>
        {{-- ============ FILTROS ============ --}}
        <div class="card border-0 shadow-sm cb-filter-card mb-4">
            <div class="card-body p-3">
                <div class="row g-3 align-items-end">
                    <div class="col-6 col-md-2">
                        <label class="cb-filter-label"># Solicitud</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-hashtag"></i></span>
                            <input type="search" id="serachsolicitud" class="form-control bg-light border-start-0" placeholder="Ej. 80">
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="cb-filter-label">Chofer</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-user"></i></span>
                            <input type="search" id="searchChofer" class="form-control bg-light border-start-0" placeholder="Ej. Octavio">
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="cb-filter-label">Obra</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-building"></i></span>
                            <input type="search" id="searchObra" class="form-control bg-light border-start-0" placeholder="Ej. Tienda Neto">
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="cb-filter-label">Vehículo</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-truck-moving"></i></span>
                            <input type="search" id="searchVehiculo" class="form-control bg-light border-start-0" placeholder="Ej. Nissan">
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="cb-filter-label">Estatus</label>
                        <select id="filterStatus" class="form-select form-select-sm bg-light">
                            <option value="">Todos</option>
                            <option value="PENDIENTE">Pendiente</option>
                            <option value="AUTORIZADO">Autorizado</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="cb-filter-label">Desde fecha</label>
                        <input type="date" id="fechaInicio" class="form-control form-control-sm bg-light">
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="cb-filter-label">Hasta fecha</label>
                        <input type="date" id="fechaFin" class="form-control form-control-sm bg-light">
                    </div>
                    <div class="col-12 col-md-4 d-flex gap-2">
                        <button class="btn btn-outline-secondary rounded-3 fw-semibold flex-shrink-0" id="btnLimpiar" title="Limpiar filtros">
                            <i class="fa-solid fa-filter-circle-xmark me-1"></i> Limpiar
                        </button>
                        <button class="btn btn-primary rounded-3 fw-bold flex-grow-1" id="btnFiltrar">
                            <i class="fa-solid fa-filter me-2"></i>Filtrar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============ TABLA ============ --}}
        <div class="card border-0 shadow-sm cb-table-card">
            <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="card-title text-dark fw-bold mb-0">
                    Solicitudes de combustible
                </h5>
                <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill fw-semibold" id="contador-solicitudes">0 solicitudes</span>
            </div>
            <div class="table-responsive cb-table-scroll px-3 pb-3">
                <table class="table align-middle table-hover mb-0 cb-table" id="tabla-combustible">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Fecha</th>
                            <th>Personal</th>
                            <th>Unidad</th>
                            <th style="min-width:180px;">Obra</th>
                            <th class="text-end">Solicitado</th>
                            <th class="text-end">Audit. GPS</th>
                            <th class="text-center">Tipo</th>
                            <th class="text-center">Estatus</th>
                            <th class="text-center">Estado solicitud</th>
                            <th class="text-center">Gestión</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo-solicitudes">
                        <tr>
                            <td colspan="11" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-spinner fa-spin fa-2x mb-3 d-block text-primary"></i>
                                Consultando base de datos…
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ============ MODAL AUTORIZAR ============ --}}
<div class="modal fade" id="modalAutorizar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg cb-modal">
            <div class="modal-header border-0 p-4 pb-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="cb-modal-icon"><i class="fa-solid fa-check-double"></i></div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Autorizar dispersión</h5>
                        <small class="text-muted">Confirma el monto final antes de autorizar</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 pt-3">
                <form id="formulario_autorizar_combustible" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="sol_id">
                    <input type="hidden" id="id_chofer">

                    <div class="cb-alert-soft d-flex align-items-center gap-2 mb-3">
                        <i class="fa-solid fa-circle-info"></i>
                        <span>Si ya auditaste por GPS, el monto se precarga con ese valor; puedes ajustarlo si es necesario.</span>
                    </div>

                    <div class="mb-3">
                        <label class="cb-filter-label mb-1">Monto a autorizar ($)</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white">$</span>
                            <input type="number" step="0.01" name="monto_final" id="monto_final" class="form-control fw-bold text-success">
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="cb-filter-label mb-1">Observaciones</label>
                        <textarea name="notas_admin" id="notas_admin" class="form-control" rows="2" placeholder="Opcional…"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light border-0 p-3">
                <button class="btn btn-link text-muted fw-bold text-decoration-none" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-success px-4 fw-bold rounded-3" id="btnAutorizar">
                    <i class="fa-solid fa-check me-1"></i>Confirmar y autorizar
                </button>
            </div>
        </div>
    </div>
</div>

<img id="logo_vss" src="{{ asset('img/vss_logo.jpeg') }}" style="display:none;">
<img id="logo_frontera" src="{{ asset('img/logo_frontera_sin.png') }}" style="display:none;">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

<style>
.bg-soft-primary { background-color: rgba(58,87,232,0.1); }
.bg-soft-secondary { background-color: rgba(139,148,166,0.15); }
.bg-soft-info { background-color: rgba(75,199,210,0.15); }
.bg-soft-danger { background-color: rgba(242,109,125,0.15); }
.bg-soft-success { background-color: rgba(23,144,75,0.12); }
.bg-soft-warning { background-color: rgba(255,193,7,0.18); }

/* ============ ENCABEZADO ============ */
.cb-icon-badge {
    width: 52px; height: 52px; min-width: 52px; border-radius: 14px;
    background: rgba(58,87,232,0.1); color: #3a57e8;
    display: flex; align-items: center; justify-content: center; font-size: 22px;
}

/* ============ KPIs ============ */
.cb-kpi-card { border-radius: 14px; position: relative; overflow: hidden; transition: transform .15s ease, box-shadow .15s ease; }
.cb-kpi-card:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(22,35,63,0.08) !important; }
.cb-kpi-card::before { content:""; position:absolute; top:0; left:0; right:0; height:3px; }
.col-lg-3:nth-child(1) .cb-kpi-card::before { background:#8b94a6; }
.col-lg-3:nth-child(2) .cb-kpi-card::before { background:#ffc107; }
.col-lg-3:nth-child(3) .cb-kpi-card::before { background:#3a57e8; }
.col-lg-3:nth-child(4) .cb-kpi-card::before { background:#d9364a; }
.cb-kpi-icon {
    width: 46px; height: 46px; min-width: 46px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center; font-size: 18px;
}
.cb-kpi-label { display:block; font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#8891a5; }
.cb-kpi-value { font-size:1.35rem; font-weight:800; color:#16233f; margin:2px 0 0; line-height:1.1; display:inline-block; }
.cb-kpi-sub { font-size:11px; color:#8891a5; }

.cb-skeleton {
    min-width: 70px; height: 1.35rem; border-radius: 6px;
    background: linear-gradient(90deg,#eef0f5 25%,#f7f8fb 37%,#eef0f5 63%);
    background-size: 400% 100%; animation: cb-shimmer 1.4s ease infinite;
}
@keyframes cb-shimmer { 0% { background-position: 100% 50%; } 100% { background-position: 0 50%; } }

/* ============ FILTROS ============ */
.cb-filter-card { border-radius: 14px; }
.cb-filter-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: #8891a5; margin-bottom: 4px; display: block; }

/* ============ TABLA ============ */
.cb-table-card { border-radius: 14px; }
.cb-table-scroll { max-height: 640px; overflow-y: auto; scrollbar-width: thin; }
.cb-table-scroll::-webkit-scrollbar { height: 6px; width: 6px; }
.cb-table-scroll::-webkit-scrollbar-thumb { background: #d7dbe6; border-radius: 6px; }
.cb-table thead th {
    font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px;
    color: #8891a5; padding: 12px 14px; border-bottom: 1px solid #edeff5; white-space: nowrap;
    position: sticky; top: 0; background: #fff; z-index: 2;
}
.cb-table tbody td { padding: 13px 14px; border-color: #f2f3f8; font-size: 13px; }
.cb-table tbody tr:hover { background: #f8f9fc; }

.cb-folio { font-weight: 700; color: #16233f; }
.cb-obra-badge {
    font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px; display: inline-block;
    max-width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}

/* ============ PILLS DE ESTATUS ============ */
.status-pill {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 11px; font-weight: 700; padding: 5px 12px; border-radius: 20px; text-transform: uppercase; letter-spacing: .3px;
}
.status-pill.status-ok { background: rgba(23,144,75,0.12); color: #17904b; }
.status-pill.status-danger { background: rgba(242,109,125,0.15); color: #d9364a; }
.status-pill.status-warn { background: rgba(255,193,7,0.18); color: #a6740a; }
.status-pill.status-neutral { background: rgba(139,148,166,0.15); color: #4b5468; }

/* ============ BOTONES DE GESTIÓN ============ */
.cb-actions { display: inline-flex; gap: 6px; }
.cb-icon-btn {
    width: 30px; height: 30px; border-radius: 8px; border: 1px solid #e3e6ef;
    background: #fff; display: inline-flex; align-items: center; justify-content: center;
    transition: background .15s ease, transform .15s ease;
}
.cb-icon-btn:hover { transform: translateY(-1px); }
.cb-icon-btn.cb-gps { color: #3a57e8; }
.cb-icon-btn.cb-gps:hover { background: rgba(58,87,232,0.1); }
.cb-icon-btn.cb-pdf { color: #d9364a; }
.cb-icon-btn.cb-pdf:hover { background: rgba(242,109,125,0.15); }
.cb-icon-btn.cb-ok { color: #fff; background: #17904b; border-color: #17904b; }
.cb-icon-btn.cb-ok:hover { filter: brightness(1.08); }
.cb-icon-btn.cb-cancel { color: #fff; background: #d9364a; border-color: #d9364a; }
.cb-icon-btn.cb-cancel:hover { filter: brightness(1.08); }

.cb-modal { border-radius: 16px; }
.cb-modal-icon {
    width: 44px; height: 44px; min-width: 44px; border-radius: 12px;
    background: rgba(23,144,75,0.12); color: #17904b;
    display: flex; align-items: center; justify-content: center; font-size: 18px;
}
.cb-alert-soft {
    background: rgba(58,87,232,0.08); color: #3a57e8; border-radius: 10px; padding: 10px 14px; font-size: 12.5px;
}

.cb-fila-alerta { background: rgba(242,109,125,0.06); }
.cb-fila-alerta:hover { background: rgba(242,109,125,0.1) !important; }

.cb-refresh-btn {
    width: 38px; height: 38px; border-radius: 10px; border: 1px solid #e3e6ef;
    background: #fff; color: #4b5468; display: inline-flex; align-items: center; justify-content: center;
    transition: background .15s ease, transform .15s ease;
}
.cb-refresh-btn:hover { background: #f1f2f5; }
.cb-refresh-btn.cb-spinning i { animation: cb-spin .6s linear infinite; }
@keyframes cb-spin { to { transform: rotate(360deg); } }
</style>

<script>
    let solicitudesCache = [];
    let usuario = [];

    $(document).ready(function() {
        solictiudesPendientes();

        $('#btnFiltrar').on('click', function() {
            ejecutarFiltros();
        });

        $('#btnLimpiar').on('click', function() {
            $('#serachsolicitud, #searchChofer, #searchObra, #searchVehiculo, #fechaInicio, #fechaFin').val('');
            $('#filterStatus').val('');
            renderizarTabla(solicitudesCache);
        });

        $('#btnActualizar').on('click', function() {
            $(this).addClass('cb-spinning');
            solictiudesPendientes();
        });
    });

    function escapeHtml(valor) {
        if (valor === null || valor === undefined) return '';
        return String(valor)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    const formatMoney = (val) => '$' + parseFloat(val || 0).toFixed(2);

    function formatearEstadoSolicitud(valor) {
        const mapa = {
            'A_TIEMPO': { texto: 'A tiempo', clase: 'status-ok' },
            'EXTRA_EXPONTANEA': { texto: 'Extra espontánea', clase: 'status-warn' },
        };
        if (mapa[valor]) return mapa[valor];
        const texto = String(valor || 'Sin dato').replaceAll('_', ' ').toLowerCase();
        return { texto: texto.charAt(0).toUpperCase() + texto.slice(1), clase: 'status-neutral' };
    }

    function solictiudesPendientes() {
        const tbody = $('#cuerpo-solicitudes');
        tbody.html(`
            <tr>
                <td colspan="11" class="text-center py-5 text-muted">
                    <i class="fa-solid fa-spinner fa-spin fa-2x mb-3 d-block text-primary"></i>
                    Consultando base de datos…
                </td>
            </tr>
        `);

        fetch('/combustible/solicitudes/recuperar')
            .then(response => response.json())
            .then(res => {
                solicitudesCache = res.solicitudes || [];
                usuario = res.user;
                renderizarTabla(solicitudesCache);
                $('#btnActualizar').removeClass('cb-spinning');
                $('#lbl-ultima-actualizacion').text(new Date().toLocaleString('es-MX', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit', hour12:true }));
            })
            .catch(err => {
                console.error('Error al recuperar solicitudes:', err);
                $('#btnActualizar').removeClass('cb-spinning');
                tbody.html(`
                    <tr><td colspan="11" class="text-center py-5 text-muted">
                        <i class="fa-solid fa-triangle-exclamation fa-2x mb-3 d-block text-warning"></i>
                        No se pudo cargar la información. Intenta recargar la página.
                    </td></tr>`);
            });
    }

    function actualizarKPIs(datos) {
        $('.cb-skeleton').removeClass('cb-skeleton');
        const total = datos.length;
        const pendientes = datos.filter(s => s.estatus === 'PENDIENTE').length;
        const montoTotal = datos.reduce((acc, s) => acc + (parseFloat(s.monto_solicitado) || 0), 0);
        const espontaneas = datos.filter(s => s.estatus_solicitud === 'EXTRA_EXPONTANEA').length;

        $('#kpi-total').text(total);
        $('#kpi-pendientes').text(pendientes);
        $('#kpi-monto').text(formatMoney(montoTotal));
        $('#kpi-espontaneas').text(espontaneas);
        $('#contador-solicitudes').text(`${total} solicitud${total === 1 ? '' : 'es'}`);
    }

    function renderizarTabla(datos) {
        actualizarKPIs(datos);

        let html = '';
        if (datos.length === 0) {
            html = `
                <tr><td colspan="11" class="text-center py-5 text-muted">
                    <i class="fa-solid fa-filter-circle-xmark fa-2x mb-3 d-block text-secondary opacity-50"></i>
                    <span class="fw-bold d-block mb-1">Sin coincidencias</span>
                    Ningún registro cumple con los filtros seleccionados.
                </td></tr>`;
        } else {
            datos.forEach(sol => {
                let fecha = new Date(sol.created_at);
                let fechaFormateada = `${String(fecha.getDate()).padStart(2, '0')}/${String(fecha.getMonth() + 1).padStart(2, '0')}/${fecha.getFullYear()}`;

                const estatusPill = sol.estatus === 'PENDIENTE'
                    ? '<span class="status-pill status-warn"><i class="fa-solid fa-hourglass-half"></i>Pendiente</span>'
                    : '<span class="status-pill status-ok"><i class="fa-solid fa-circle-check"></i>Autorizado</span>';

                const tipoPill = sol.tipo_combustible === 'Premium'
                    ? '<span class="status-pill status-neutral"><i class="fa-solid fa-gas-pump"></i>Premium</span>'
                    : `<span class="status-pill status-ok"><i class="fa-solid fa-gas-pump"></i>${escapeHtml(sol.tipo_combustible)}</span>`;

                const estadoInfo = formatearEstadoSolicitud(sol.estatus_solicitud);

                html += `
                <tr id="fila-${sol.id}">
                    <td class="cb-folio">#${sol.id}</td>
                    <td><small class="text-muted">${fechaFormateada}</small></td>
                    <td>${escapeHtml(sol.nombre_chofer)} ${escapeHtml(sol.apellido_p_chofer)}</td>
                    <td>
                        <span class="d-block fw-bold text-dark">${escapeHtml(sol.marca_vehiculo)}</span>
                        <small class="text-muted">${escapeHtml(sol.linea_vehiculo)}</small>
                    </td>
                    <td>
                        <span class="cb-obra-badge bg-soft-info text-info" title="${escapeHtml(sol.nombre_obra || 'Sin obra asignada')}">
                            ${escapeHtml(sol.nombre_obra) || 'N/A'}
                        </span>
                    </td>
                    <td class="text-end fw-bold text-dark">${formatMoney(sol.monto_solicitado)}</td>
                    <td class="text-end fw-bold text-primary" id="gps-${sol.id}">---</td>
                    <td class="text-center">${tipoPill}</td>
                    <td class="text-center">${estatusPill}</td>
                    <td class="text-center"><span class="status-pill ${estadoInfo.clase}">${estadoInfo.texto}</span></td>
                    <td class="text-center">
                        <div class="cb-actions">
                            <button class="cb-icon-btn cb-gps" title="Analizar GPS" onclick="analizarGps('${sol.nombre_gps_vehiculo}', '${sol.created_at}', ${sol.id_vehiculo_gps}, ${sol.id})">
                                <i class="fa-solid fa-satellite"></i>
                            </button>
                            <button class="cb-icon-btn cb-pdf" title="Descargar PDF" onclick="prepararPdf(${sol.id})">
                                <i class="fa-solid fa-file-pdf"></i>
                            </button>
                            <button class="cb-icon-btn cb-ok" title="Autorizar" onclick="abrirModal(${sol.id}, ${sol.monto_solicitado}, ${sol.chofer_id})">
                                <i class="fa-solid fa-check"></i>
                            </button>
                            <button class="cb-icon-btn cb-cancel" title="Rechazar" onclick="RechazarSolicitud(${sol.id}, ${sol.monto_solicitado}, ${sol.chofer_id})">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </td>
                </tr>`;
            });
        }
        $('#cuerpo-solicitudes').html(html);
    }

    function RechazarSolicitud(id_solicitud, monto_solicitado, id_chofer) {
        var usuario_id = usuario.id;
        Swal.fire({
            title: '¿Estás seguro de rechazar esta solicitud?',
            text: `Esta acción no se puede deshacer. Solicitud Fol-${id_solicitud}`,
            icon: 'warning',
            input: 'text',
            inputLabel: 'Motivo del rechazo',
            inputPlaceholder: 'Ingresa el motivo del rechazo...',
            showCancelButton: true,
            confirmButtonText: 'Sí, rechazar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/combustible/solicitudes/rechazar/' + id_solicitud + '/' + id_chofer + '/' + usuario_id,
                    method: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        notas_admin: result.value
                    },
                    success: function(res) {
                        Swal.fire({ icon: 'success', title: 'Solicitud Rechazada', text: res.message });
                        solictiudesPendientes();
                    },
                    error: function(xhr) {
                        console.error('Error al rechazar la solicitud:', xhr.responseText);
                        Swal.fire({ icon: 'error', title: 'Error en el rechazo', text: 'Ocurrió un error al rechazar la solicitud. Por favor, intenta nuevamente.' });
                    }
                });
            }
        });
    }

    function abrirModal(id_solicitud, monto_solicitado, id_chofer) {
        $('#sol_id').val(id_solicitud);
        const montoGps = $(`#gps-${id_solicitud}`).text().trim().replace('$', '');
        const hayMontoGps = montoGps && montoGps !== '---' && !isNaN(parseFloat(montoGps));
        $('#monto_final').val(hayMontoGps ? parseFloat(montoGps).toFixed(2) : monto_solicitado);
        $('#id_chofer').val(id_chofer);
        $('#modalAutorizar').modal('show');
    }

    $('#btnAutorizar').on('click', function(e){
        e.preventDefault();
        const id_solicitud = $('#sol_id').val();
        const monto_final = $('#monto_final').val();
        const id_chofer = $('#id_chofer').val();

        const folio_generado = 'FOL-' + id_solicitud.padStart(3, '0');

        if (!monto_final || monto_final <= 0) {
            Swal.fire({ icon: 'error', title: 'Monto inválido', text: 'Por favor, ingresa un monto válido mayor a 0.' });
            return;
        }
        var usuario_id = usuario.id;
        var formData = new FormData($('#formulario_autorizar_combustible')[0]);
        formData.append('autorizador_id', usuario_id);
        formData.append('folio_autorizacion', folio_generado);
        Swal.fire({ title: 'Enviando solicitud...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        $.ajax({
            url: '/combustible/solicitudes/autorizar/' + id_solicitud + '/' + id_chofer,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res){
                Swal.fire({ icon: 'success', title: 'Has autorizado esta solicitud', text: res.message });
                $('#modalAutorizar').modal('hide');
                $('#notas_admin').val('');
                solictiudesPendientes();
            },
            error: function(xhr){
                console.error('Error en la autorización:', xhr.responseText);
                Swal.fire({ icon: 'error', title: 'Error en la autorización', text: 'Ocurrió un error al autorizar la solicitud. Por favor, intenta nuevamente.' });
            }
        });
    });

    function ejecutarFiltros() {
        const id = $('#serachsolicitud').val().toLowerCase();
        const chofer = $('#searchChofer').val().toLowerCase();
        const obra = $('#searchObra').val().toLowerCase();
        const vehiculo = $('#searchVehiculo').val().toLowerCase();
        const estatus = $('#filterStatus').val();
        const f_inicio = $('#fechaInicio').val();
        const f_fin = $('#fechaFin').val();

        const filtrados = solicitudesCache.filter(sol => {
            const matchId = sol.id.toString().includes(id);
            const matchChofer = (sol.nombre_chofer + ' ' + sol.apellido_p_chofer).toLowerCase().includes(chofer);
            const matchObra = (sol.nombre_obra || "").toLowerCase().includes(obra);
            const matchVehiculo = (sol.marca_vehiculo + " " + sol.linea_vehiculo).toLowerCase().includes(vehiculo);
            const matchEstatus = estatus === "" || sol.estatus === estatus;

            const fechaSol = sol.created_at.split(' ')[0];
            let matchFecha = true;
            if (f_inicio && f_fin) matchFecha = (fechaSol >= f_inicio && fechaSol <= f_fin);
            else if (f_inicio) matchFecha = (fechaSol >= f_inicio);
            else if (f_fin) matchFecha = (fechaSol <= f_fin);

            return matchId && matchChofer && matchObra && matchVehiculo && matchEstatus && matchFecha;
        });

        renderizarTabla(filtrados);
    }

    function analizarGps(tabla, fecha, idVehiculo, idSol) {
        let fechaBase = new Date(fecha.split(' ')[0]);
        let d_fin = new Date(fechaBase);
        d_fin.setDate(d_fin.getDate() - 1);
        let f_fin = d_fin.toISOString().split('T')[0];

        let d_ini = new Date(d_fin);
        d_ini.setDate(d_ini.getDate() - 7);
        let f_ini = d_ini.toISOString().split('T')[0];

        $(`#gps-${idSol}`).html('<i class="fas fa-spinner fa-spin text-muted"></i>');

        if (!tabla || tabla.trim() === '' || idVehiculo === null || idVehiculo === undefined) {
            $(`#gps-${idSol}`).html('<span class="status-pill status-neutral"><i class="fa-solid fa-satellite-dish"></i>Sin GPS</span>');
            let solicitud = solicitudesCache.find(s => s.id == idSol);
            if (solicitud) {
                solicitud.datos_gps = {
                    sin_gps: true,
                    distancia_total_km: 0,
                    ultimo_viaje: { costo_total: 0, rendimiento_x_litro: 0, precio_combustible: 0, litros_totales: 0 },
                    duracion_total: "00:00:00",
                    viajes: [],
                    detenciones: []
                };
            }
            return;
        }

        $.get(`/recupera-datos-de-rutas/${tabla}/${f_ini}/${f_fin}/${idVehiculo}`, function (res) {
            let solicitud = solicitudesCache.find(s => s.id == idSol);
            if (solicitud) {
                solicitud.datos_gps = res;
            }

            if (!res.ultimo_viaje || isNaN(res.ultimo_viaje.costo_total)) {
                $(`#gps-${idSol}`).html('<span class="status-pill status-neutral"><i class="fa-solid fa-circle-question"></i>Sin datos</span>');
                return;
            }

            let sugerido = parseFloat(res.ultimo_viaje.costo_total);
            let montoSol = solicitud ? parseFloat(solicitud.monto_solicitado) : NaN;
            let hayExceso = !isNaN(montoSol) && sugerido < montoSol;

            const pillClase = hayExceso ? 'status-danger' : 'status-ok';
            const icono = hayExceso ? 'fa-triangle-exclamation' : 'fa-circle-check';
            $(`#gps-${idSol}`).html(`<span class="status-pill ${pillClase}"><i class="fa-solid ${icono}"></i>${formatMoney(sugerido)}</span>`);

            $(`#fila-${idSol}`).toggleClass('cb-fila-alerta', hayExceso);
        }).fail(function () {
            $(`#gps-${idSol}`).html('<span class="status-pill status-danger"><i class="fa-solid fa-triangle-exclamation"></i>Error</span>');
        });
    }

    function prepararPdf(idSol) {
        let sol = solicitudesCache.find(s => s.id == idSol);
        if (!sol) return;
        if (!sol.datos_gps) {
            Swal.fire({
                icon: 'warning',
                title: 'Análisis GPS requerido',
                text: 'Por favor, primero audita el consumo con el botón de satélite.',
                confirmButtonText: 'Entendido'
            });
            return;
        }
        descargarPdf(sol);
    }

    function descargarPdf(sol) {
        const gps = sol.datos_gps;
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'l', unit: 'mm', format: 'a4' });

        const navyBlue = [2, 48, 71];
        const softGray = [245, 245, 245];
        const successGreen = [39, 174, 96];
        const alertRed = [192, 57, 43];

        doc.setDrawColor(...navyBlue);
        doc.setLineWidth(0.5);
        doc.rect(5, 5, 287, 200);

        const img1 = document.getElementById('logo_vss');
        const img2 = document.getElementById('logo_frontera');
        if (img1 && img2) {
            doc.addImage(img1, 'PNG', 245, 10, 35, 12);
            doc.addImage(img2, 'PNG', 15, 10, 50, 12);
        }

        doc.setFillColor(...navyBlue);
        doc.rect(10, 28, 277, 18, 'F');
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(16);
        doc.setFont('helvetica', 'bold');
        doc.text(`SOLICITUD DE DISPERSIÓN DE COMBUSTIBLE (EXTRAORDINARIA) - FOLIO ${String(sol.id).padStart(4, '0')}`, 148, 38, { align: 'center' });

        doc.setFillColor(...softGray);
        doc.rect(10, 46, 277, 10, 'F');
        doc.setTextColor(50, 50, 50);
        doc.setFontSize(9);
        doc.setFont('helvetica', 'bold');

        let fechaObj = new Date(sol.created_at);
        let meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        let fechaTxt = `${fechaObj.getDate()} de ${meses[fechaObj.getMonth()]} del ${fechaObj.getFullYear()}`;

        doc.text(`EMISIÓN: ${fechaTxt.toUpperCase()}`, 15, 52);
        doc.text(`ODÓMETRO: ${sol.km_actual || '---'} KM`, 148, 52, { align: 'center' });
        doc.text(`EFECTICARD: ${sol.efecticard_vehiculo || 'N/A'}`, 272, 52, { align: 'right' });

        doc.setTextColor(...navyBlue);
        doc.setFontSize(11);
        doc.text('DATOS DEL SOLICITANTE', 10, 60);
        doc.setDrawColor(...navyBlue);
        doc.line(10, 61, 61, 61);

        doc.setTextColor(0, 0, 0);
        doc.setFont('helvetica', 'bold');
        doc.text('PERSONAL:', 10, 68);
        doc.setFont('helvetica', 'normal');
        doc.text(`${sol.apellido_p_chofer} ${sol.apellido_m_chofer}, ${sol.nombre_chofer}`.toUpperCase(), 35, 68);

        doc.setFont('helvetica', 'bold');
        doc.text('PROYECTO:', 10, 73);
        doc.setFont('helvetica', 'normal');
        doc.text(`${sol.nombre_obra}`.toUpperCase(), 35, 73);

        const montoSolicitado = parseFloat(sol.monto_solicitado).toLocaleString('es-MX', { style: 'currency', currency: 'MXN' });
        const costo_viaje = gps.sin_gps ? 'SIN GPS' : parseFloat(gps.ultimo_viaje.costo_total).toLocaleString('es-MX', { style: 'currency', currency: 'MXN' });
        const precio_combustible = gps.sin_gps ? 'SIN GPS' : parseFloat(gps.ultimo_viaje.precio_combustible).toLocaleString('es-MX', { style: 'currency', currency: 'MXN' });

        doc.autoTable({
            startY: 78,
            head: [['VEHÍCULO', 'MONTO SOLICITADO', 'MONTO AUDITADO GPS', 'DISTANCIA RECO.', 'PRECIO UNIT.', 'LITROS CONSU.']],
            body: [[
                `${sol.marca_vehiculo} ${sol.linea_vehiculo} ${sol.modelo_vehiculo}`.toUpperCase(),
                `${montoSolicitado}`,
                `${costo_viaje}`,
                `${gps.sin_gps ? 'SIN GPS' : `${gps.distancia_total_km} KM`}`,
                `${precio_combustible} / L`,
                `${gps.sin_gps ? 'SIN GPS' : `${gps.ultimo_viaje.litros_totales} L`}`
            ]],
            theme: 'striped',
            headStyles: { fillColor: navyBlue, textColor: 255, fontStyle: 'bold', halign: 'center' },
            styles: { fontSize: 10, halign: 'center', cellPadding: 6 },
            columnStyles: { 2: { fontStyle: 'bold', textColor: successGreen } }
        });

        let finalY = doc.lastAutoTable.finalY + 4;
        let diferencia = sol.monto_solicitado - gps.ultimo_viaje.costo_total;
        const diferencia_mx = diferencia.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' });

        doc.setFillColor(...softGray);
        doc.rect(180, finalY, 107, 20, 'F');
        doc.setFont('helvetica', 'bold');
        doc.text('DIFERENCIA DETECTADA:', 185, finalY + 12);

        if (diferencia > 0) {
            doc.setTextColor(...alertRed);
            doc.setFontSize(14);
            if (gps.sin_gps) {
                doc.text('SIN CALCULAR', 272, finalY + 13, { align: 'right' });
            } else {
                doc.text(` ${diferencia_mx}`, 272, finalY + 13, { align: 'right' });
            }
        } else {
            doc.setTextColor(...successGreen);
            doc.text('VALOR ÓPTIMO', 272, finalY + 13, { align: 'right' });
        }

        doc.setTextColor(...navyBlue);
        doc.setFontSize(10);
        doc.text('DICTAMEN TÉCNICO DE TELEMETRÍA', 10, finalY + 5);
        doc.setTextColor(80, 80, 80);
        doc.setFont('helvetica', 'normal');

        const ultimoMontoReal = parseFloat(sol.ultimo_monto_real) || 0;
        const kmAuditadosActuales = parseFloat(gps.distancia_total_km);

        let dictamenRendimiento = "";
        let notaTexto = "";

        if (gps.sin_gps === true) {
            notaTexto = `La unidad no cuenta con datos de GPS disponibles para el periodo auditado, por lo que no es posible realizar un análisis detallado de rendimiento. Se recomienda validar la instalación y funcionamiento del dispositivo GPS para futuras solicitudes. El monto auditado se muestra como referencia, pero no se puede verificar su correspondencia con el desempeño real del vehículo durante este periodo.`;
        } else {
            const litrosUltimaCarga = ultimoMontoReal / gps.ultimo_viaje.precio_combustible;
            const kmEsperadosUltimaCarga = litrosUltimaCarga * gps.ultimo_viaje.rendimiento_x_litro;
            const ratioCumplimiento = kmEsperadosUltimaCarga > 0 ? (kmAuditadosActuales / kmEsperadosUltimaCarga) * 100 : 100;

            const tiempoPartes = gps.duracion_total.split(':');
            const segundosTotales = (parseInt(tiempoPartes[0]) * 3600) + (parseInt(tiempoPartes[1]) * 60) + parseInt(tiempoPartes[2]);
            const horasTotalesDecimal = segundosTotales / 3600;
            const velocidadPromedio = (gps.distancia_total_km / horasTotalesDecimal).toFixed(1);

            const litrosQueCompra = parseFloat(sol.monto_solicitado) / gps.ultimo_viaje.precio_combustible;
            const autonomiaKm = (litrosQueCompra * gps.ultimo_viaje.rendimiento_x_litro).toFixed(0);

            if (ultimoMontoReal > 0) {
                dictamenRendimiento = `Respecto a la última carga autorizada de $${ultimoMontoReal.toFixed(2)}, se esperaba un rendimiento de ${kmEsperadosUltimaCarga.toFixed(1)} KM; la unidad registró un cumplimiento del ${ratioCumplimiento.toFixed(1)}% de dicha meta operativa. `;
            }

            notaTexto = `Durante el periodo auditado, la unidad mantuvo una velocidad promedio de operación de ${velocidadPromedio} km/h. ${dictamenRendimiento}Se validaron ${gps.total_viajes} trayectos con un promedio de ${(gps.distancia_total_km / gps.total_viajes).toFixed(2)} km por viaje. Basado en el rendimiento de ${gps.ultimo_viaje.rendimiento_x_litro} km/l, la dispersión solicitada de ${montoSolicitado} equivale a una autonomía proyectada de ${autonomiaKm} KM. Se registraron ${gps.detenciones.length} eventos de ralentí (motor encendido sin movimiento) que impactan en el consumo final. Este análisis sugiere que el consumo auditado es ${diferencia > 0 ? 'superior' : 'dentro de'} los parámetros esperados.`;
        }

        const splitNota = doc.splitTextToSize(notaTexto, 160);
        doc.text(splitNota, 15, finalY + 12);

        let firmaY = 175;
        doc.setDrawColor(200, 200, 200);
        const usuarios_auth = (usuario.nombre || '---') + ' ' + (usuario.apellido_p || '') + ' ' + (usuario.apellido_m || '');
        doc.setFontSize(10);
        doc.setTextColor(100);
        doc.text(usuarios_auth.toUpperCase(), 35, firmaY - 3);

        doc.line(20, firmaY, 110, firmaY);
        doc.setFontSize(9);
        doc.setTextColor(100);
        doc.text('ADMINISTRADOR DE CONTROL VEHICULAR', 65, firmaY + 5, { align: 'center' });
        doc.setFont('helvetica', 'bold');
        doc.setTextColor(0);
        doc.text('ANALISIS GENERADO POR', 65, firmaY + 10, { align: 'center' });

        doc.line(180, firmaY, 270, firmaY);
        doc.setTextColor(100);
        doc.setFont('helvetica', 'normal');
        doc.text('DEPARTAMENTO DE FINANZAS / OBRA', 225, firmaY + 5, { align: 'center' });
        doc.setFont('helvetica', 'bold');
        doc.setTextColor(0);
        doc.text('AUTORIZACIÓN FINAL', 225, firmaY + 10, { align: 'center' });

        doc.setFillColor(...navyBlue);
        doc.rect(0, 202, 297, 8, 'F');
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(8);
        doc.text('ESTE DOCUMENTO ES UNA REPRESENTACIÓN GRÁFICA DE DATOS DIGITALES DE TELEMETRÍA - AUDITADO POR EL SISTEMA DE GRUPO INDUSTRIAL FRONTERA SUR', 148, 207, { align: 'center' });

        window.open(doc.output('bloburl'), '_blank');
    }
</script>
@endsection