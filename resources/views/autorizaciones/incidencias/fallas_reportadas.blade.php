@extends('layout.index')
@section('content')
<main class="main-content">

<style>
    /* Tarjeta de Incidencia Premium */
    .falla-card {
        background: #ffffff;
        border: 1px solid #eef2f6;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        margin-bottom: 24px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: calc(100% - 24px);
    }

    .falla-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(67, 97, 238, 0.12);
        border-color: #dbe4ff;
    }

    /* Contenedor de Imagen y Badges */
    .falla-image-container {
        position: relative;
        height: 190px;
        overflow: hidden;
        background-color: #f8fafc;
    }

    .falla-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .falla-card:hover .falla-image-container img {
        transform: scale(1.06);
    }

    /* Badges flotantes de prioridad */
    .priority-floating-badge {
        position: absolute;
        top: 14px;
        right: 14px;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 0.68rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #fff;
        z-index: 2;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .f-alta { background: #ef4444; }
    .f-media { background: #f59e0b; }
    .f-baja { background: #10b981; }

    /* Cuerpo de la Tarjeta */
    .falla-body {
        padding: 1.25rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .falla-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .falla-desc {
        font-size: 0.85rem;
        color: #64748b;
        line-height: 1.5;
        margin-bottom: 12px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 38px;
    }

    /* Chips informativos */
    .info-chip {
        display: inline-flex;
        align-items: center;
        background: #f1f5f9;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.72rem;
        font-weight: 600;
        color: #475569;
        max-width: fit-content;
    }

    /* Footer con Chofer y Botón */
    .falla-footer {
        padding: 1rem 1.25rem;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .chofer-pill {
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 0;
    }

    .chofer-pill img {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        flex-shrink: 0;
    }

    /* Estados de Badges de Reportes */
    .badge-status-remodel {
        font-size: 0.65rem !important;
        font-weight: 700;
        padding: 5px 10px;
        border-radius: 6px;
        letter-spacing: 0.3px;
    }
    
    .bg-soft-abierto { background-color: #e0e7ff; color: #4338ca; }
    .bg-soft-espera { background-color: #fef3c7; color: #92400e; }
    .bg-soft-revision { background-color: #e0f2fe; color: #0369a1; }
    .bg-soft-aprobada { background-color: #dcfce7; color: #166534; }
    .bg-soft-reparado { background-color: #f1f5f9; color: #334155; }
    .bg-soft-rechazado { background-color: #fee2e2; color: #991b1b; }

    .sin-foto-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        color: #94a3b8;
    }

    /* Estilos para la barra de filtros ejecutiva */
    .filter-card {
        background: #ffffff;
        border: 1px solid #eef2f6;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
</style>

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid content-inner py-4 mt-4">
    <div class="row">
        <div class="col-lg-12">
            
            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-3">
                <div>
                    <h3 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">INCIDENCIA VEHICULAR</h3>
                    <p class="text-muted small mb-0 d-flex align-items-center gap-2">
                        <span><i class="fa-solid fa-circle text-success" style="font-size: 8px;"></i> Monitoreo de incidencias y mantenimiento vehicular.</span>
                    </p>
                </div>
            </div>

            <div class="filter-card p-3 mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-bold text-secondary text-uppercase mb-1" style="letter-spacing: 0.3px;">Buscar Reporte</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" id="filterBuscar" class="form-control bg-light border-start-0 ps-1 rounded-end-3" placeholder="Chofer, vehículo, título...">
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="form-label small fw-bold text-secondary text-uppercase mb-1" style="letter-spacing: 0.3px;">Filtrar por Estatus</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-layer-group"></i></span>
                            <select id="filterEstatus" class="form-select bg-light border-start-0 ps-1 rounded-end-3 fw-semibold text-dark">
                                <option value="todos">-- Todos los Estados --</option>
                                <option value="abierto">Abierto (Nuevos)</option>
                                <option value="en_espera_cotizacion">Espera de Cotización</option>
                                <option value="en_revision">En Revisión (Validar Gasto)</option>
                                <option value="cotizacion_aprobada">En Reparación</option>
                                <option value="reparado">Reparado (Historial)</option>
                                <option value="rechazado">Rechazado</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="form-label small fw-bold text-secondary text-uppercase mb-1" style="letter-spacing: 0.3px;">Prioridad</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-triangle-exclamation"></i></span>
                            <select id="filterPrioridad" class="form-select bg-light border-start-0 ps-1 rounded-end-3 fw-semibold text-dark">
                                <option value="todos">-- Todas --</option>
                                <option value="alta">Alta</option>
                                <option value="media">Media</option>
                                <option value="baja">Baja</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-xl-3 row-cols-xxl-4 g-1" id="contenedorIncidencias">
                </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRevisionTecnica" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white p-3 border-0">
                <div class="d-flex align-items-center ms-2">
                    <div class="bg-secondary bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                        <i class="fas fa-file-invoice-dollar text-warning fs-6"></i>
                    </div>
                    <h5 class="modal-title fw-bold text-white mb-0" style="letter-spacing: -0.3px;">Revisión de Gasto / Cotización</h5>
                </div>
                <button type="button" class="btn-close btn-close-white me-2" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-white text-center">
                <div class="bg-light rounded-4 py-3 px-2 mb-4 border border-light-subtle">
                    <p class="text-secondary small mb-1 text-uppercase fw-bold" style="letter-spacing: 0.5px;">Monto Total Presupuestado</p>
                    <h1 class="fw-extrabold text-success mb-0" id="m_monto" style="font-size: 2.5rem; letter-spacing: -1px;">$0.00</h1>
                </div>
                
                <div class="d-grid gap-2 mx-1">
                    <a id="m_pdf_link" target="_blank" class="btn btn-outline-danger py-2-5 rounded-3 fw-bold d-flex align-items-center justify-content-center border-2">
                        <i class="fas fa-file-pdf me-2 fs-5"></i> VER DOCUMENTO PDF ADJUNTO
                    </a>
                    
                    <div class="my-3 border-top border-light"></div>
                    
                    <input type="hidden" id="m_incidencia_id">
                    
                    <button class="btn btn-success py-3 rounded-3 fw-bold shadow-sm d-flex align-items-center justify-content-center text-white" onclick="autorizarReparacion()">
                        <i class="fa-solid fa-square-check me-2 fs-5"></i> AUTORIZAR GASTO Y MANDAR A TALLER
                    </button>
                    
                    <button type="button" class="btn btn-light mt-1 fw-semibold text-secondary py-2 rounded-3" data-bs-dismiss="modal">Regresar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Variable global para almacenar las incidencias que vengan del servidor
    let cacheIncidencias = [];

    $(document).ready(function() {
        cargarIncidencias();

        // Escuchar los eventos de los filtros para actualizar la vista instantáneamente
        $('#filterBuscar').on('input', filtrarYRenderizar);
        $('#filterEstatus').on('change', filtrarYRenderizar);
        $('#filterPrioridad').on('change', filtrarYRenderizar);
    });

    function cargarIncidencias() {
        $('#contenedorIncidencias').html('<div class="col-12 text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>');

        fetch('/incidencias/fallas/reportadas/all-fallas')
            .then(response => response.json())
            .then(data => {
                // Guardamos en nuestra variable global
                cacheIncidencias = data.allincidencias;
                filtrarYRenderizar();
            });
    }

    function filtrarYRenderizar() {
        const busqueda = $('#filterBuscar').val().toLowerCase().trim();
        const estatusSel = $('#filterEstatus').val();
        const prioridadSel = $('#filterPrioridad').val();

        // Aplicamos los filtros sobre el arreglo en memoria
        let itemsFiltrados = cacheIncidencias.filter(inc => {
            // 1. Filtro por buscador de texto
            const matchTexto = !busqueda || 
                (inc.nombre_chofer && inc.nombre_chofer.toLowerCase().includes(busqueda)) ||
                (inc.marca_vehiculo && inc.marca_vehiculo.toLowerCase().includes(busqueda)) ||
                (inc.placas && inc.placas.toLowerCase().includes(busqueda)) ||
                (inc.titulo_reporte && inc.titulo_reporte.toLowerCase().includes(busqueda)) ||
                (inc.descripcion && inc.descripcion.toLowerCase().includes(busqueda));

            // 2. Filtro por Estatus
            const matchEstatus = (estatusSel === 'todos') || (inc.estatus_reporte === estatusSel);

            // 3. Filtro por Prioridad
            const matchPrioridad = (prioridadSel === 'todos') || (inc.prioridad === prioridadSel);

            return matchTexto && matchEstatus && matchPrioridad;
        });

        // Renderizar el HTML de los elementos filtrados
        let html = '';

        if (itemsFiltrados.length === 0) {
            html = `
            <div class="col-12 text-center py-5 w-100">
                <div class="text-muted mb-2"><i class="fa-solid fa-filter-circle-xmark fs-2 text-light"></i></div>
                <h6 class="text-secondary fw-semibold">No se encontraron resultados con los filtros aplicados</h6>
            </div>`;
        } else {
            itemsFiltrados.forEach(inc => {
                const pClass = inc.prioridad === 'alta' ? 'f-alta' : (inc.prioridad === 'media' ? 'f-media' : 'f-baja');
                
                const fotoHtml = inc.foto_evidencia 
                    ? `<img src="{{ asset('') }}${inc.foto_evidencia}" onclick="window.open('{{ asset('') }}${inc.foto_evidencia}')" style="cursor:zoom-in" alt="Evidencia">`
                    : `<div class="sin-foto-placeholder"><i class="fa-solid fa-camera fs-2 mb-2"></i><span class="small fw-semibold">Sin evidencia fotográfica</span></div>`;
                
                const choferFoto = inc.foto_chofer ? `{{ asset('storage') }}/${inc.foto_chofer}` : `{{ asset('assets/img/01.png') }}`;

                let textoBoton = 'REVISAR';
                let colorBoton = 'btn-primary';
                let softStatusClass = 'bg-soft-abierto';

                switch (inc.estatus_reporte) {
                    case 'abierto':
                        textoBoton = 'REVISAR FALLA';
                        colorBoton = 'btn-primary';
                        softStatusClass = 'bg-soft-abierto';
                        break;
                    case 'en_espera_cotizacion':
                        textoBoton = 'ESPERANDO CHOFER';
                        colorBoton = 'btn-warning text-dark';
                        softStatusClass = 'bg-soft-espera';
                        break;
                    case 'en_revision':
                        textoBoton = 'VALIDAR GASTO';
                        colorBoton = 'btn-info text-white';
                        softStatusClass = 'bg-soft-revision';
                        break;
                    case 'cotizacion_aprobada':
                        textoBoton = 'EN REPARACIÓN';
                        colorBoton = 'btn-success text-white';
                        softStatusClass = 'bg-soft-aprobada';
                        break;
                    case 'reparado':
                        textoBoton = 'VER HISTORIAL';
                        colorBoton = 'btn-dark';
                        softStatusClass = 'bg-soft-reparado';
                        break;
                    case 'rechazado':
                        textoBoton = 'RECHAZADO';
                        colorBoton = 'btn-danger';
                        softStatusClass = 'bg-soft-rechazado';
                        break;
                    default:
                        textoBoton = 'GESTIONAR';
                        colorBoton = 'btn-secondary';
                        softStatusClass = 'bg-soft-abierto';
                }

                let disabledAttr = (inc.estatus_reporte === 'en_espera_cotizacion') ? 'style="opacity: 0.65; cursor: not-allowed;"' : '';
                let cleanStatusText = inc.estatus_reporte.replace(/_/g, ' ').toUpperCase();

                html += `
                <div class="col">
                    <div class="falla-card">
                        <div class="falla-image-container">
                            <span class="priority-floating-badge ${pClass}">${inc.prioridad}</span>
                            ${fotoHtml}
                        </div>
                        
                        <div class="falla-body">
                            <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-1">
                                <div class="info-chip">
                                    <i class="fas fa-truck-pickup me-1 text-secondary"></i> 
                                    <span class="text-dark fw-bold">${inc.marca_vehiculo}</span> 
                                    <span class="text-muted ms-1" style="font-family: monospace;">(${inc.placos || inc.placas || 'S/P'})</span>
                                </div>
                                <span class="badge badge-status-remodel ${softStatusClass}">${cleanStatusText}</span>
                            </div>

                            <h5 class="falla-title text-truncate" title="${inc.titulo_reporte}">${inc.titulo_reporte}</h5>
                            <p class="falla-desc text-muted mb-3">${inc.descripcion || 'Sin descripción detallada disponible.'}</p>
                            
                            <div class="mt-auto pt-2 border-top border-light-subtle d-flex align-items-center text-muted" style="font-size: 0.75rem;">
                                <i class="far fa-calendar-alt me-1.5"></i> Reportado: ${new Date(inc.created_at).toLocaleDateString('es-MX')}
                            </div>
                        </div>

                        <div class="falla-footer">
                            <div class="chofer-pill">
                                <img src="${choferFoto}" onerror="this.src='{{ asset('assets/img/01.png') }}'" alt="Chofer">
                                <div class="lh-sm text-truncate" style="max-width: 110px;">
                                    <div class="fw-bold text-dark text-truncate" style="font-size: 0.78rem;">${inc.nombre_chofer}</div>
                                    <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.6rem; letter-spacing:0.3px;">Operador</small>
                                </div>
                            </div>
                            <button class="btn ${colorBoton} fw-bold shadow-sm px-3 d-flex align-items-center justify-content-center" 
                                    style="border-radius:8px; font-size: 0.68rem; height: 34px; letter-spacing: 0.2px;" 
                                    onclick="gestionarIncidencia(${inc.id})" ${disabledAttr}>
                                ${textoBoton}
                            </button>
                        </div>
                    </div>
                </div>`;
            });
        }
        $('#contenedorIncidencias').html(html);
    }

    function gestionarIncidencia(id) {
        fetch(`/incidencias/detalle/${id}`)
            .then(response => response.json())
            .then(res => {
                const inc = res.revision[0];
                const chofer_id = res.revision[0].chofer_id;

                if (inc.estatus_reporte === 'abierto') {
                    Swal.fire({
                        title: '¿Iniciar Revisión?',
                        text: "Se le solicitará formalmente al operador que asista al taller mecánico para subir el PDF de la cotización.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#4361ee',
                        confirmButtonText: 'Sí, solicitar cotización',
                        cancelButtonText: 'En otro momento',
                        customClass: { popup: 'rounded-4' }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Enviando notificación...',
                                allowOutsideClick: false,
                                didOpen: () => Swal.showLoading()
                            });
                            actualizarAEspera(id, chofer_id);
                        }
                    });
                } 
                else if (inc.estatus_reporte === 'cotizacion_aprobada') {
                    Swal.fire({
                        title: 'Unidad en Proceso de Reparación 🛠️',
                        html: `
                            <div class="text-start px-2">
                                <p class="mb-2">Esta reparación ya fue autorizada previamente por la administración con un monto de: <b class="text-success">$ ${parseFloat(inc.monto_cotizado).toLocaleString('es-MX', {minimumFractionDigits: 2})}</b></p>
                                <p class="text-muted small mb-0">El vehículo se encuentra físicamente en el taller. Una vez concluidos los trabajos, el operador deberá marcar la salida desde su módulo móvil para cerrar el ciclo.</p>
                            </div>
                        `,
                        icon: 'info',
                        confirmButtonColor: '#10b981',
                        confirmButtonText: 'Entendido, dar seguimiento'
                    });
                }
                else {
                    abrirModalExpediente(inc);
                }
            });
    }

    function actualizarAEspera(id, chofer_id) {
        $.ajax({
            url: '/incidencias/cambiar/estatus/' + id + '/' + chofer_id,
            type: 'POST', 
            data: {
                _token: '{{ csrf_token() }}', 
                estatus: 'en_espera_cotizacion'
            },
            success: function (response){
                Swal.fire({
                    title: '¡Estatus Actualizado!',
                    text: 'Se ha abierto la solicitud. El operador recibirá una notificación.',
                    icon: 'success',
                    confirmButtonColor: '#4361ee'
                });
                cargarIncidencias();
            },
            error: function (xhr){
                console.error('Error:', xhr.responseText);
                Swal.fire('Error', 'Ocurrió un inconveniente al cambiar el estatus operativo.', 'error');
            }
        });
    }

    function abrirModalExpediente(inc) {
        if (!inc.pdf_cotizacion) {
            Swal.fire({
                icon: 'info',
                title: 'Cotización no disponible',
                text: 'El chofer se encuentra gestionando el documento en el taller. Te notificaremos de inmediato en cuanto sea cargada al sistema.',
                confirmButtonColor: '#4361ee'
            });
            return;
        }

        $('#m_monto').text('$' + parseFloat(inc.monto_cotizado).toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        $('#m_pdf_link').attr('href', `{{ asset('storage') }}/${inc.pdf_cotizacion}`);
        $('#m_incidencia_id').val(inc.id);
        
        $('#modalRevisionTecnica').modal('show');
    }

    function autorizarReparacion() {
        const idIncidencia = $('#m_incidencia_id').val();

        Swal.fire({
            title: '¿Confirmar Autorización de Gasto?',
            text: "Esta acción liberará el presupuesto de mantenimiento y notificará al chofer para proceder de inmediato con las reparaciones físicas.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Sí, liberar presupuesto',
            cancelButtonText: 'Cancelar verificación'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/incidencias/autorizar-cotizacion/${idIncidencia}`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Liberando fondos de reparación...',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });
                    },
                    success: function(res) {
                        Swal.fire('¡Autorizado con Éxito!', res.message, 'success');
                        $('#modalRevisionTecnica').modal('hide');
                        cargarIncidencias(); 
                    },
                    error: function(xhr) {
                        console.error('error es', xhr.responseText);
                        Swal.fire('Error de Transacción', 'No pudimos validar la orden. Verifique la consola o logs del servidor.', 'error');
                    }
                });
            }
        });
    }
</script>
</main>
@endsection