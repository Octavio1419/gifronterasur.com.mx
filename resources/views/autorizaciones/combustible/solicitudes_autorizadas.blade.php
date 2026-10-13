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
            <i class="fa-solid fa-list-check" style="font-size: 35px"></i>
          </div>
          <div>
            <h4 class="mb-0 fw-bold text-primary">HIST. DE COMBUSTIBLES AUTORIZADOS</h4>
            <small class="text-muted">Descarga el concentrado de tus combustibles.</small>
          </div>
        </div>
        <hr class="my-4">
        <div class="row g-3">
            <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold small mb-1"># Folio:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-hashtag text-secondary"></i></span>
                                <input type="search" id="searchFolio" class="form-control border-start-0" placeholder="Ej. 5520...">
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold small mb-1">Pryecto / Obra:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-store"></i></span>
                                <input type="search" id="searchObra" class="form-control border-start-0" placeholder="Ej. Tienda Neto...">
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold small mb-1">Chofer:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-user text-secondary"></i></span>
                                <input type="search" id="searchChofer" class="form-control border-start-0" placeholder="Nombre chofer...">
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold small mb-1">Vehiculo:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-car-side"></i></span>
                                <input type="search" id="searchVehiculo" class="form-control border-start-0" placeholder="NISSAN NP300...">
                            </div>
                        </div>
                        <div class="col-6 col-md-2">
                            <label class="form-label fw-semibold small mb-1">Estatus:</label>
                            <select id="filterStatus" class="form-select shadow-sm">
                                <option value="">Todos</option>
                                <option value="autorizado">Autorizados</option>
                                <option value="rechazados">Rechazados</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-2">
                            <label class="form-label fw-semibold small mb-1">Combustible:</label>
                            <select id="filterColor" class="form-select shadow-sm">
                                <option value="">Todos</option>
                                <option value="verde">Magna(Verde)</option>
                                <option value="roja">Premium(Roja)</option>
                                <option value="diesel">Diesel</option>
                              
                            </select>
                        </div>
                        <div class="col-6 col-md-2">
                            <label class="form-label fw-semibold small mb-1">Fecha inicio:</label>
                            <input type="date" id="fechaInicio" class="form-control shadow-sm">
                        </div>
                        <div class="col-6 col-md-2">
                            <label class="form-label fw-semibold small mb-1">Fecha fin:</label>
                            <input type="date" id="fechaFin" class="form-control shadow-sm">
                        </div>
                        <div class="col-12 col-md-2 d-flex align-items-end">
                              <button class="btn btn-primary w-100 fw-bold shadow-sm" id="btnBuscar">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                Filtrar Resultados
                            </button>
                        </div>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-4 p-3 bg-light rounded-3 border">
            <div>
                <h6 class="mb-0 text-dark fw-bold"><i class="fa-solid fa-file-export me-2"></i>Exportar Resultados</h6>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-danger btn-sm shadow-sm px-3 fw-bold" id="btnPdfGeneral">
                    <i class="fa-solid fa-file-pdf me-2"></i>REPORTE GENERAL
                </button>
                <button class="btn btn-success btn-sm shadow-sm px-3 fw-bold" id="btnPdfConcentrado">
                    <i class="fa-solid fa-table-list me-2"></i>HISTÓRICO POR OBRA
                </button>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-hover align-middle border-0" id="tablaAutorizados">
                        <thead class="bg-light">
                            <tr class="text-secondary">
                                <th class="small fw-bold">FOLIO</th>
                                <th class="small fw-bold">FECHA</th>
                                <th class="small fw-bold">CHOFER / UNIDAD</th>
                                <th class="small fw-bold">PROYECTO / OBRA</th>
                                <th class="small fw-bold">EFECTICARD</th>
                                <th class="small fw-bold text-center">SOLICITADO</th>
                                <th class="small fw-bold text-center">AUTORIZADO</th>
                                <th class="small fw-bold">COMBUSTIBLE</th>
                                <th class="small fw-bold text-center">ESTADO SOLICITUD</th>
                                <th class="small fw-bold text-center">T.PAGO</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyAutorizados">
                        </tbody>
                        <tfoot class="bg-light fw-bold" id="tfootTotales" style="display: none;">
                            <tr>
                                <td colspan="6" class="text-end">TOTAL CONCENTRADO:</td>
                                <td class="text-center text-primary" id="totalGeneral">$0.00</td>
                                <td colspan="4"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>      
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modalEditSolicitud" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content modal-edit-sol">

      <div class="modal-edit-header">
        <button type="button" class="btn-close-custom" data-bs-dismiss="modal">
          <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="header-icon">
          <i class="fa-solid fa-gas-pump"></i>
        </div>
        <h5 class="modal-title" id="title_edit"></h5>
        <span class="modal-subtitle" id="title_subtitle"></span>
      </div>

      <div class="modal-body p-4 pt-3">
        <form id="formEditSolicitud">
          <input type="hidden" id="edit_id">

          <div class="field-group">
            <label class="field-label"><i class="fa-solid fa-money-bill-wave"></i> Monto Real Cargado</label>
            <div class="input-modern">
              <span class="prefix">$</span>
              <input type="number" step="0.01" min="0" class="form-control-modern" id="edit_monto_real_cargado" required placeholder="0.00">
            </div>
          </div>

          <div class="field-row">
            <div class="field-group">
              <label class="field-label"><i class="fa-solid fa-droplet"></i> Combustible</label>
              <div class="select-modern">
                <select class="form-select-modern" id="edit_tipo_combustible" required>
                  <option value="magna">Magna</option>
                  <option value="premium">Premium</option>
                  <option value="diesel">Diésel</option>
                </select>
                <i class="fa-solid fa-chevron-down select-caret"></i>
              </div>
            </div>

            <div class="field-group">
              <label class="field-label"><i class="fa-solid fa-clock"></i> Estatus</label>
              <div class="select-modern">
                <select class="form-select-modern" id="edit_estatus_solicitud" required>
                  <option value="A_TIEMPO">A Tiempo</option>
                  <option value="EXTRA_ORDINARIA">Extra Ordinaria</option>
                </select>
                <i class="fa-solid fa-chevron-down select-caret"></i>
              </div>
            </div>
          </div>
          <div class="field-group">
            <label class="field-label"><i class="fa-solid fa-building"></i> Obras Relacionadas</label>
            <div class="tags-input-wrapper" id="tagsWrapper">
                <input type="text" class="tags-input-field" id="obraTagInput" placeholder="Escribe una obra y presiona Enter...">
            </div>
            <input type="hidden" id="edit_nombre_obra">
        </div>
          <div class="field-group mb-0">
            <label class="field-label"><i class="fa-solid fa-arrows-rotate"></i> Método Recargado</label>
            <div class="select-modern">
              <select class="form-select-modern" id="edit_metodo_recargado" required>
                <option value="regular">Regular</option>
                <option value="reposicion_caja">Reposición de Caja</option>
              </select>
              <i class="fa-solid fa-chevron-down select-caret"></i>
            </div>
          </div>
        </form>
      </div>

      <div class="modal-edit-footer">
        <button type="button" class="btn-cancel-modern" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn-save-modern" id="btnGuardarEditSolicitud">
          <i class="fa-solid fa-check"></i> Guardar cambios
        </button>
      </div>

    </div>
  </div>
</div>
<style>
.modal-edit-sol {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
    animation: modalPopIn 0.25s ease-out;
}

@keyframes modalPopIn {
    from { opacity: 0; transform: scale(0.95) translateY(10px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}
.modal-edit-header {
    position: relative;
    background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
    padding: 32px 32px 24px;
    text-align: center;
    color: #fff;
}

.modal-edit-header .header-icon {
    width: 56px;
    height: 56px;
    margin: 0 auto 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 14px;
    font-size: 22px;
    backdrop-filter: blur(4px);
}

.modal-edit-header .modal-title {
    font-weight: 700;
    font-size: 1.15rem;
    margin-bottom: 4px;
    color: #fff;
}

.modal-edit-header .modal-subtitle {
    display: block;
    font-size: 0.82rem;
    color: rgba(255, 255, 255, 0.75);
    font-weight: 400;
}

.btn-close-custom {
    position: absolute;
    top: 16px;
    right: 16px;
    width: 30px;
    height: 30px;
    border: none;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.15);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.15s ease;
}
.btn-close-custom:hover { background: rgba(255, 255, 255, 0.28); }
.field-group {
    margin-bottom: 18px;
}

.field-row {
    display: flex;
    gap: 14px;
}
.field-row .field-group { flex: 1; }

.field-label {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 0.8rem;
    font-weight: 600;
    color: #4b5563;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    margin-bottom: 7px;
}
.field-label i { color: #6366f1; font-size: 0.85rem; }
.input-modern {
    display: flex;
    align-items: center;
    background: #f8fafc;
    border: 1.5px solid #e5e7eb;
    border-radius: 12px;
    padding: 0 14px;
    transition: border-color 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
}
.input-modern:focus-within {
    border-color: #6366f1;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
}
.input-modern .prefix {
    color: #9ca3af;
    font-weight: 600;
    margin-right: 6px;
}
.form-control-modern {
    border: none;
    background: transparent;
    padding: 12px 0;
    width: 100%;
    font-weight: 600;
    font-size: 0.95rem;
    color: #111827;
}
.form-control-modern:focus { outline: none; box-shadow: none; }
.select-modern {
    position: relative;
}
.form-select-modern {
    width: 100%;
    appearance: none;
    -webkit-appearance: none;
    background: #f8fafc;
    border: 1.5px solid #e5e7eb;
    border-radius: 12px;
    padding: 12px 36px 12px 14px;
    font-weight: 600;
    font-size: 0.9rem;
    color: #111827;
    cursor: pointer;
    transition: border-color 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
}
.form-select-modern:focus {
    outline: none;
    border-color: #6366f1;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
}
.select-caret {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 0.72rem;
    color: #9ca3af;
    pointer-events: none;
}
.modal-edit-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 18px 28px 26px;
    border-top: 1px solid #f1f1f4;
}

.btn-cancel-modern {
    border: none;
    background: #f3f4f6;
    color: #4b5563;
    font-weight: 600;
    font-size: 0.88rem;
    padding: 11px 20px;
    border-radius: 11px;
    transition: background 0.15s ease;
}
.btn-cancel-modern:hover { background: #e5e7eb; }

.btn-save-modern {
    border: none;
    background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
    color: #fff;
    font-weight: 600;
    font-size: 0.88rem;
    padding: 11px 22px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 6px 16px rgba(79, 70, 229, 0.3);
    transition: transform 0.12s ease, box-shadow 0.12s ease;
}
.btn-save-modern:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(79, 70, 229, 0.4);
}
.btn-save-modern:active { transform: translateY(0); }
.btn-save-modern:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }
.modal-backdrop.show { opacity: 0.55; }
.tags-input-wrapper {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 6px;
    background: #f8fafc;
    border: 1.5px solid #e5e7eb;
    border-radius: 12px;
    padding: 8px 10px;
    min-height: 46px;
    transition: border-color 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
}
.tags-input-wrapper:focus-within {
    border-color: #6366f1;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
}

.tag-chip {
    display: flex;
    align-items: center;
    gap: 6px;
    background: #eef2ff;
    color: #4338ca;
    font-weight: 600;
    font-size: 0.78rem;
    padding: 5px 8px 5px 10px;
    border-radius: 8px;
    white-space: nowrap;
}
.tag-chip .tag-remove {
    cursor: pointer;
    color: #818cf8;
    font-size: 0.7rem;
    display: flex;
    align-items: center;
    padding: 2px;
    border-radius: 50%;
    transition: background 0.15s ease, color 0.15s ease;
}
.tag-chip .tag-remove:hover {
    background: #c7d2fe;
    color: #3730a3;
}

.tags-input-field {
    border: none;
    background: transparent;
    outline: none;
    flex: 1;
    min-width: 140px;
    font-size: 0.88rem;
    font-weight: 500;
    color: #111827;
    padding: 6px 4px;
}
</style>
<img id="logo_vss" src="{{ asset('img/vss_logo.jpeg') }}" style="display:none;">
<img id="logo_frontera" src="{{ asset('img/logo_frontera_sin.png') }}" style="display:none;">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<script>
    let autorizadosOriginales = []; 
    let autorizadosFiltrados = [];

    $(document).ready(function() {
        cargarAutorizados();

        $('#btnBuscar').on('click', function() {
            aplicarFiltros();
        });
        $('#btnPdfGeneral').on('click', function() {
            generarReporteGlobal();
        });

        $('#btnPdfConcentrado').on('click', function() {
            if (autorizadosFiltrados.length === 0) {
                Swal.fire('Atención', 'No hay datos filtrados para exportar', 'warning');
                return;
            }

            generarPdfConcentrado(autorizadosFiltrados); 
        });
    });

    function cargarAutorizados() {
        const tbody = $('#tbodyAutorizados');
        tbody.html(`
            <tr>
                <td colspan="10" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted fw-bold">Consultando base de datos...</p>
                </td>
            </tr>
        `);
        fetch('/combustible/solicitudes/recuperar/autorizados')
            .then(response => response.json())
            .then(data => {
                autorizadosOriginales = data;
                renderizarTabla(data);
                autorizadosFiltrados = data; 
            });
    }
    let solicitudesDataCache = []; 
    
    function renderizarTabla(data) {
        let html = '';
        let sumaTotal = 0;
         solicitudesDataCache = data;
        console.log('datos',data);
        data.forEach(sol => {
            const montoAut = parseFloat(sol.monto_real_cargado || 0);
            sumaTotal += montoAut;

            let badgeColor = 'bg-success';
            if(sol.tipo_combustible.toLowerCase() === 'premium') badgeColor = 'bg-danger';
            if(sol.tipo_combustible.toLowerCase() === 'diesel') badgeColor = 'bg-dark';
            html += `
            <tr>
                <td class="ps-4 fw-extrabold text-primary fs-6 py-3 col-folio">
                    <a href="javascript:void(0);" 
                                   class="link-edit-sol text-decoration-none fw-bold" 
                                   data-id="${sol.id}" 
                                   data-folio="${sol.folio_autorizacion}">
                ${sol.folio_autorizacion}
                    </a>
                </td>
                <td class="small text-muted">${sol.created_at.split(' ')[0]}</td>
                <td>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">${sol.nombre_chofer} ${sol.apellido_p_chofer}</span>
                        <small class="text-muted">${sol.marca} ${sol.linea} - [${sol.placas}]</small>
                    </div>
                </td>
                <td>
                    <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${sol.nombre_obra}">
                        <span class="badge bg-soft-info text-info border">${sol.nombre_obra}</span>
                    </div>
                </td>
                <td class="text-center text-muted">${sol.efecticard ?? 'Sin tarjeta'} </td>
                <td class="text-center text-muted">$${parseFloat(sol.monto_solicitado).toFixed(2)}</td>
                <td class="text-center fw-bold text-success">$${montoAut.toFixed(2)}</td>
                <td><span class="badge ${badgeColor}">${sol.tipo_combustible}</span></td>
                <td class="text-center">
                        <span class="badge ${sol.estatus_solicitud === 'EXTRA_EXPONTANEA' ? 'bg-warning text-dark' : 'bg-success'}">${sol.estatus_solicitud.toUpperCase()}</span>
                </td>
                <td class="text-center">
                        <span class="badge ${sol.metodo_recargado === 'reposicion_caja' ? 'bg-warning text-dark' : 'bg-success'}">${sol.metodo_recargado}</span>
                </td>  
            </tr>`;
        });

        $('#tbodyAutorizados').html(html || '<tr><td colspan="9" class="text-center">No se encontraron registros</td></tr>');
        
        if(data.length > 0) {
            $('#tfootTotales').show();
            $('#totalGeneral').text(`$${sumaTotal.toFixed(2)}`);
        } else {
            $('#tfootTotales').hide();
        }
    }
    let obrasTags = []; 

    $(document).on('click', '.link-edit-sol', function(e){
        e.stopPropagation();
        e.preventDefault();
        const id_solicitud = $(this).data('id');
        const folio = $(this).data('folio');

        const sol = solicitudesDataCache.find(s => s.id == id_solicitud);
        console.log('estas son ', sol);
        if (!sol) {
            Swal.fire('Atención', 'No hay datos con este id', 'warning');
            return; 
        }

        $('#title_edit').html(`EDITANDO EL ${folio}`);
        $('#title_subtitle').html(`Actualizar los datos de carga del chofer (${sol.nombre_chofer} ${sol.apellido_p_chofer})`);

        $('#edit_id').val(sol.id);
        $('#edit_monto_real_cargado').val(parseFloat(sol.monto_real_cargado || 0).toFixed(2));
        $('#edit_tipo_combustible').val(sol.tipo_combustible.toLowerCase());
        $('#edit_estatus_solicitud').val(sol.estatus_solicitud);
        $('#edit_metodo_recargado').val(sol.metodo_recargado.toLowerCase());

        obrasTags = (sol.nombre_obra || '')
            .split(',')
            .map(o => o.trim())
            .filter(o => o.length > 0);

        renderTags(); 
        $('#edit_nombre_obra_principal').val(sol.nombre_obra_principal || '');

        const modal = new bootstrap.Modal(document.getElementById('modalEditSolicitud'));
        modal.show();
    });

    function renderTags() {
        $('#tagsWrapper .tag-chip').remove();
        obrasTags.forEach((obra, i) => {
            $('#obraTagInput').before(`
                <span class="tag-chip" data-index="${i}">
                    ${obra}
                    <span class="tag-remove"><i class="fa-solid fa-xmark"></i></span>
                </span>
            `);
        });
        $('#edit_nombre_obra').val(obrasTags.join(', '));
    }

    $(document).on('keydown', '#obraTagInput', function (e) {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            const val = $(this).val().trim();
            if (val && !obrasTags.includes(val)) {
                obrasTags.push(val);
                renderTags();
            }
            $(this).val('');
        }
        if (e.key === 'Backspace' && $(this).val() === '' && obrasTags.length) {
            obrasTags.pop();
            renderTags();
        }
    });

    $(document).on('click', '.tag-remove', function () {
        const index = $(this).closest('.tag-chip').data('index');
        obrasTags.splice(index, 1);
        renderTags();
    });

    $(document).on('click', '#btnGuardarEditSolicitud', function () {

        if (obrasTags.length === 0) {
            Swal.fire('Atención', 'Debes agregar al menos una obra', 'warning');
            return;
        }

        const payload = {
            id: $('#edit_id').val(),
            monto_real_cargado: parseFloat($('#edit_monto_real_cargado').val()),
            tipo_combustible: $('#edit_tipo_combustible').val(),
            estatus_solicitud: $('#edit_estatus_solicitud').val(),
            metodo_recargado: $('#edit_metodo_recargado').val(),
            nombre_obra: obrasTags.join(', '),
            nombre_obra_principal: $('#edit_nombre_obra_principal').val(),
            _token: '{{ csrf_token() }}',
        };

        console.log('Estos enviamos a editar', payload);

        const $btn = $(this).prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm me-1"></span> Guardando...');

        $.ajax({
            url: '/combustible/solicitudes/update',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(payload),
            success: function (resp) {
                bootstrap.Modal.getInstance(document.getElementById('modalEditSolicitud')).hide();
                Swal.fire({
                    title: '¡Datos Actualizados!',
                    text: resp.message,
                    icon: 'success',
                    confirmButtonColor: '#198754'
                }).then(() => {
                    cargarAutorizados();
                });
            },
            error: function (xhr) {
                console.log('el error es ', xhr.responseText);
                Swal.fire('Error', 'No se pudo actualizar la solicitud', 'error');
            },
            complete: function () {
                $btn.prop('disabled', false).html('<i class="fa-solid fa-floppy-disk me-1"></i> Guardar cambios');
            }
        });
    });

    function aplicarFiltros() {
        const folio = $('#searchFolio').val();
        const obra = $('#searchObra').val().toLowerCase();
        const chofer = $('#searchChofer').val().toLowerCase();
        const vehiculo = $('#searchVehiculo').val().toLowerCase();
        const status = $('#filterStatus').val();
        const combustible = $('#filterColor').val().toLowerCase();
        const fInicio = $('#fechaInicio').val();
        const fFin = $('#fechaFin').val();

        const filtrados = autorizadosOriginales.filter(sol => {
            const matchFolio = folio ? String(sol.id).includes(folio) : true;
            const matchObra = obra ? sol.nombre_obra.toLowerCase().includes(obra) : true;
            const matchChofer = chofer ? (sol.nombre_chofer + ' ' + sol.apellido_p_chofer).toLowerCase().includes(chofer) : true;
            const matchVehiculo = vehiculo ? (sol.marca + ' ' + sol.linea).toLowerCase().includes(vehiculo) : true;
            const matchStatus = status ? sol.estatus === status : true;
            const matchCombustible = combustible ? sol.tipo_combustible.toLowerCase().includes(combustible) : true;
            
            let matchFecha = true;
            const fechaSol = sol.created_at.split(' ')[0];
            if (fInicio && fFin) {
                matchFecha = fechaSol >= fInicio && fechaSol <= fFin;
            } else if (fInicio) {
                matchFecha = fechaSol >= fInicio;
            }

            return matchFolio && matchObra && matchChofer && matchVehiculo && matchStatus && matchCombustible && matchFecha;
        });
       
        renderizarTabla(filtrados);
        autorizadosFiltrados = filtrados;
        console.log('Datos filtrados', filtrados);
    }
   
    function generarReporteGlobal() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'l', unit: 'mm', format: 'a4' });
        
        const logoVss = document.getElementById('logo_vss');
        const logoFrontera = document.getElementById('logo_frontera');

        if(logoVss) doc.addImage(logoVss, 'JPEG', 15, 10, 50, 12); 
        if(logoFrontera) doc.addImage(logoFrontera, 'PNG', 245, 10, 35, 12); 

        doc.setFontSize(18);
        doc.setTextColor(2, 48, 71);
        doc.setFont("helvetica", "bold");
        doc.text("CONCENTRADO DE COMBUSTIBLES AUTORIZADOS", 153, 20, { align: 'center' });
        
        doc.setFontSize(10);
        doc.setFont("helvetica", "normal");
        doc.setTextColor(100);
        doc.text("REPORTE OPERATIVO CLASIFICADO", 148, 26, { align: 'center' });
        doc.text(`Fecha de emisión: ${new Date().toLocaleString()}`, 148, 31, { align: 'center' });

        const rowsRegulares = [];
        const rowsCajaChica = [];
        const rowsTiendas = [];
        
        let totalRegulares = 0;
        let totalCajaChica = 0;
        let totalTiendas = 0;

        $('#tbodyAutorizados tr').each(function() {
            const folio = $(this).find('td:eq(0)').text().trim();
            const fecha = $(this).find('td:eq(1)').text().trim();
            const choferUnidad = $(this).find('td:eq(2)').text().trim().replace(/\s+/g, ' ');
            
            const efecticard = $(this).find('td:eq(4)').text().trim();
            const solicitado = $(this).find('td:eq(5)').text().trim();
            const autorizado = $(this).find('td:eq(6)').text().trim();
            const combustible = $(this).find('td:eq(7)').text().trim();
            
            const estatusTipo = $(this).find('td:eq(8)').text().trim().toUpperCase();
            const estatusReposicion = $(this).find('td:eq(9)').text().trim().toUpperCase();
            const obra = $(this).find('td:eq(3)').text().trim().toUpperCase();

            const valorNumerico = parseFloat(autorizado.replace(/[^0-9.-]+/g, "")) || 0;
            const dataFila = [folio, fecha, choferUnidad, obra, efecticard, solicitado, autorizado, combustible];

            if (obra.includes('TIENDAS HERRALUM CHIAPAS')) {
                rowsTiendas.push(dataFila);
                totalTiendas += valorNumerico;
            } else if (estatusTipo.includes('EXTRA_ORDINARIA') || estatusReposicion.includes('REPOSICION_CAJA')) {
                rowsCajaChica.push(dataFila);
                totalCajaChica += valorNumerico;
            } else {
                rowsRegulares.push(dataFila);
                totalRegulares += valorNumerico;
            }
        });

        const tableStyles = {
            theme: 'striped',
            styles: { fontSize: 7, cellPadding: 2, valign: 'middle' },
            margin: { horizontal: 14 },
            headStyles: { 
                fillColor: [2, 48, 71], 
                textColor: [255, 255, 255], 
                fontStyle: 'bold',
                halign: 'center',
                fontSize: 7.5 
            },
            columnStyles: {
                0: { halign: 'center', cellWidth: 15 },
                1: { halign: 'center', cellWidth: 18 },
                2: { cellWidth: 42 },
                3: { cellWidth: 92 }, 
                4: { halign: 'center', fontStyle: 'bold', textColor: [0, 102, 204], cellWidth: 22 }, 
                5: { halign: 'right', cellWidth: 23 }, 
                6: { halign: 'right', fontStyle: 'bold', textColor: [16, 185, 129], cellWidth: 23 }, 
                7: { halign: 'center', cellWidth: 20 }
            }
        };

        let currentY = 40;

        doc.setFontSize(11);
        doc.setTextColor(16, 185, 129); 
        doc.setFont("helvetica", "bold");
        doc.text(`PEDIDOS REGULARES (JUEVES)`, 14, currentY);
        
        doc.autoTable({
            ...tableStyles,
            head: [['FOLIO', 'FECHA', 'CHOFER / UNIDAD', 'PROYECTOS / OBRAS CARGADAS', 'TARJETA', 'SOLICITADO', 'AUTORIZADO', 'TIPO']],
            body: rowsRegulares.length > 0 ? rowsRegulares : [['-', '-', 'No hay registros regulares en este bloque', '-', '-', '-', '-', '-']],
            startY: currentY + 3,
        });

        currentY = doc.lastAutoTable.finalY + 6;

        doc.setFontSize(9);
        doc.setTextColor(50);
        doc.setFont("helvetica", "bold");
        doc.text(`Subtotal Combustibles Regulares: $${totalRegulares.toLocaleString('es-MX', { minimumFractionDigits: 2 })}`, 283, currentY, { align: 'right' });
        
        currentY += 10;

        doc.setFontSize(11);
        doc.setTextColor(217, 119, 6);
        doc.setFont("helvetica", "bold");
        doc.text(`REPOSICIONES EXTRAORDINARIAS / CAJA CHICA`, 14, currentY);

        doc.autoTable({
            ...tableStyles,
            head: [['FOLIO', 'FECHA', 'RESPONSABLE / DETALLE', 'PROYECTOS / OBRAS CARGADAS', 'TARJETA/VALE', 'SOLICITADO', 'AUTORIZADO', 'TIPO']],
            body: rowsCajaChica.length > 0 ? rowsCajaChica : [['-', '-', 'No hay reposiciones extraordinarias en este bloque', '-', '-', '-', '-', '-']],
            startY: currentY + 3,
            headStyles: { ...tableStyles.headStyles, fillColor: [120, 130, 140] }
        });

        currentY = doc.lastAutoTable.finalY + 6;
        doc.setFontSize(9);
        doc.setTextColor(50);
        doc.setFont("helvetica", "bold");
        doc.text(`Subtotal Extraordinarios / Caja Chica: $${totalCajaChica.toLocaleString('es-MX', { minimumFractionDigits: 2 })}`, 283, currentY, { align: 'right' });

        currentY += 10;
        currentY += 10;
        const pageWidth = doc.internal.pageSize.getWidth();
        const margin = 14;
        const rectWidth = 90;
        const rectX = pageWidth - margin - rectWidth; 

        doc.setFillColor(2, 48, 71); 
        doc.rect(rectX, currentY, rectWidth, 12, 'F');
        
        doc.setFontSize(10);
        doc.setTextColor(255, 255, 255); 
        doc.setFont("helvetica", "bold");
        doc.text("TOTAL CONCENTRADO:", rectX + 5, currentY + 8);
        
        const granTotalTexto = `$ ${(totalRegulares + totalCajaChica).toLocaleString('es-MX', { minimumFractionDigits: 2 })}`;
        doc.text(granTotalTexto, pageWidth - margin - 5, currentY + 8, { align: 'right' });

        doc.addPage();
 
        if(logoVss) doc.addImage(logoVss, 'JPEG', 15, 10, 50, 12); 
        if(logoFrontera) doc.addImage(logoFrontera, 'PNG', 245, 10, 35, 12); 

        doc.setFontSize(15);
        doc.setTextColor(2, 48, 71);
        doc.setFont("helvetica", "bold");
        doc.text("CONCENTRADO DE COMBUSTIBLES (TIENDAS HERRALUM)", 153, 20, { align: 'center' });
        
        doc.setFontSize(10);
        doc.setFont("helvetica", "normal");
        doc.setTextColor(100);
        doc.text("REPORTE OPERATIVO CLASIFICADO", 148, 26, { align: 'center' });
        doc.text(`Fecha de emisión: ${new Date().toLocaleString()}`, 148, 31, { align: 'center' });

        doc.autoTable({
            ...tableStyles,
            head: [['FOLIO', 'FECHA', 'RESPONSABLE / DETALLE', 'ESTABLECIMIENTO COBRO', 'TARJETA/VALE', 'SOLICITADO', 'AUTORIZADO', 'TIPO']],
            body: rowsTiendas.length > 0 ? rowsTiendas : [['-', '-', 'No hay consumos de tiendas en este bloque', '-', '-', '-', '-', '-']],
            startY: 40 + 3,
            headStyles: { ...tableStyles.headStyles, fillColor: [47, 79, 79] } 
        });

        currentY = doc.lastAutoTable.finalY + 6;
        doc.setFontSize(9);
        doc.setTextColor(50);
        doc.setFont("helvetica", "bold");
        doc.text(`Subtotal Tiendas Herralum: $${totalTiendas.toLocaleString('es-MX', { minimumFractionDigits: 2 })}`, 283, currentY, { align: 'right' });
       
        doc.save(`REPORTE_DISTRIBUIDO_COMBUSTIBLE_${new Date().getTime()}.pdf`);
    }
    
    function generarPdfConcentrado(auth) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'l', unit: 'mm', format: 'a4' });
        const logoVss = document.getElementById('logo_vss');
        const logoFrontera = document.getElementById('logo_frontera');
        if(logoVss) doc.addImage(logoVss, 'JPEG', 15, 10, 45, 12); 
        if(logoFrontera) doc.addImage(logoFrontera, 'PNG', 240, 10, 35, 12);

        doc.setFontSize(16);
        doc.setTextColor(44, 62, 80); 
        doc.setFont('helvetica', 'bold');
        doc.text('DISPERSIÓN DE SALDOS AUTORIZADO POR OBRAS', 148, 22, { align: 'center' });

        let rowsRegulares = [];
        let rowsExtraordinarios = [];

        let totalImpReg = 0, totalComReg = 0, totalIvaReg = 0, granTotalReg = 0;
        let totalImpExt = 0, totalComExt = 0, totalIvaExt = 0, granTotalExt = 0;

        auth.forEach(registro => {
            const estatusTipo = (registro.estatus_solicitud || '').toUpperCase();
            const metodoRecargado = (registro.metodo_recargado || '').toUpperCase();
            const montoReal = parseFloat(registro.monto_real_cargado) || 0;
            const esExtraordinario = estatusTipo.includes('EXTRA_ORDINARIA') || metodoRecargado.includes('REPOSICION_CAJA');

            if (esExtraordinario) {
                const obraTexto = registro.nombre_obra_principal ? registro.nombre_obra_principal.toUpperCase() : (registro.notas_admin ? registro.notas_admin.toUpperCase() : 'REPOSICIÓN CAJA CHICA');
                
                const comision = montoReal * 0.03;
                const iva = comision * 0.16;
                const totalFila = montoReal + comision + iva;

                totalImpExt += montoReal;
                totalComExt += comision;
                totalIvaExt += iva;
                granTotalExt += totalFila;

                rowsExtraordinarios.push([
                    `FOLIO-${registro.id || registro.folio_autorizacion || ''}`,
                    registro.efecticard,
                    registro.updated_at ? registro.updated_at.split(' ')[0] : (registro.created_at ? registro.created_at.split(' ')[0] : ''),
                    `${registro.nombre_chofer} ${registro.apellido_p_chofer}`.toUpperCase(),
                    obraTexto,
                    `${registro.linea || ''} ${registro.marca || ''}`.toUpperCase().trim() || 'S/V',
                    `$ ${montoReal.toLocaleString('en-US', {minimumFractionDigits: 2})}`,
                    `$ ${comision.toLocaleString('en-US', {minimumFractionDigits: 2})}`,
                    `$ ${iva.toLocaleString('en-US', {minimumFractionDigits: 2})}`,
                    `$ ${totalFila.toLocaleString('en-US', {minimumFractionDigits: 2})}`
                ]);

            } else {
                const nombresObras = registro.nombre_obra ? registro.nombre_obra.split(',').map(o => o.trim()) : [];
                const totalObras = nombresObras.length;

                if (totalObras > 0) {
                    const cargadoPorObra = montoReal / totalObras;
                    const comisionPorObra = cargadoPorObra * 0.03;
                    const ivaPorObra = comisionPorObra * 0.16;
                    const totalFila = cargadoPorObra + comisionPorObra + ivaPorObra;

                    nombresObras.forEach(obra => {
                        totalImpReg += cargadoPorObra;
                        totalComReg += comisionPorObra;
                        totalIvaReg += ivaPorObra;
                        granTotalReg += totalFila;

                        rowsRegulares.push([
                            `FOLIO-${registro.id || registro.folio_autorizacion || ''}`,
                            registro.efecticard,
                            registro.updated_at ? registro.updated_at.split(' ')[0] : (registro.created_at ? registro.created_at.split(' ')[0] : ''),
                            `${registro.nombre_chofer} ${registro.apellido_p_chofer}`.toUpperCase(),
                            obra.toUpperCase(),
                            `${registro.linea || ''} ${registro.marca || ''}`.toUpperCase().trim() || 'S/V',
                            `$ ${cargadoPorObra.toLocaleString('en-US', {minimumFractionDigits: 2})}`,
                            `$ ${comisionPorObra.toLocaleString('en-US', {minimumFractionDigits: 2})}`,
                            `$ ${ivaPorObra.toLocaleString('en-US', {minimumFractionDigits: 2})}`,
                            `$ ${totalFila.toLocaleString('en-US', {minimumFractionDigits: 2})}`
                        ]);
                    });
                }
            }
        });

        const baseTableConfig = {
            theme: 'striped',
            styles: { fontSize: 7, overflow: 'linebreak', cellPadding: 2 },
            margin: { left: 10, right: 10 },
            headStyles: { textColor: 255, fontSize: 8, halign: 'center', fontStyle: 'bold' },
            footStyles: { fillColor: [240, 244, 248], textColor: [44, 62, 80], fontSize: 7.5, fontStyle: 'bold', halign: 'right' },
            columnStyles: {
                0: { cellWidth: 18, halign: 'center' },
                1: { cellWidth: 20, halign: 'center' },
                2: { cellWidth: 18, halign: 'center' },
                3: { cellWidth: 32 },
                4: { cellWidth: 'auto' },
                5: { cellWidth: 28 },
                6: { halign: 'right', cellWidth: 18 },
                7: { halign: 'right', cellWidth: 18 },
                8: { halign: 'right', cellWidth: 16 },
                9: { halign: 'right', cellWidth: 20 }
            }
        };

        let currentY = 32;

        // Tabla Regulares
        doc.setFontSize(10);
        doc.setTextColor(41, 128, 185);
        doc.setFont('helvetica', 'bold');
        doc.text('DISPERSIÓN DE GASTOS OPERATIVOS REGULARES (POR OBRA)', 10, currentY);

        doc.autoTable({
            ...baseTableConfig,
            startY: currentY + 2,
            head: [['#FOLIO', 'TARJETA', 'FECHA', 'RESPONSABLE', 'PROYECTO / OBRA', 'VEHICULO', 'IMPORTE', 'COMISIÓN', 'IVA', 'TOTAL']],
            body: rowsRegulares.length > 0 ? rowsRegulares : [['-', '-', '-', 'No se encontraron registros regulares', '-', '-', '$ 0.00', '$ 0.00', '$ 0.00', '$ 0.00']],
            headStyles: { ...baseTableConfig.headStyles, fillColor: [41, 128, 185] },
            foot: [[
                '', '', '', '', '', 'SUBTOTAL REGULARES:', 
                `$ ${totalImpReg.toLocaleString('en-US', {minimumFractionDigits: 2})}`,
                `$ ${totalComReg.toLocaleString('en-US', {minimumFractionDigits: 2})}`,
                `$ ${totalIvaReg.toLocaleString('en-US', {minimumFractionDigits: 2})}`,
                `$ ${granTotalReg.toLocaleString('en-US', {minimumFractionDigits: 2})}`
            ]]
        });

        currentY = doc.lastAutoTable.finalY + 8;

        // Tabla Extraordinarios
        doc.setFontSize(10);
        doc.setTextColor(127, 140, 141);
        doc.setFont('helvetica', 'bold');
        doc.text('REPOSICIONES EXTRAORDINARIAS / CAJA CHICA', 10, currentY);

        doc.autoTable({
            ...baseTableConfig,
            startY: currentY + 2,
            head: [['#FOLIO', 'TARJETA', 'FECHA', 'RESPONSABLE', 'MOTIVO DE REPOSICIÓN', 'VEHICULO', 'IMPORTE', 'COMISIÓN', 'IVA', 'TOTAL']],
            body: rowsExtraordinarios.length > 0 ? rowsExtraordinarios : [['-', '-', '-', 'No se encontraron reposiciones extraordinarias', '-', '-', '$ 0.00', '$ 0.00', '$ 0.00', '$ 0.00']],
            headStyles: { ...baseTableConfig.headStyles, fillColor: [108, 122, 137] },
            foot: [[
                '', '', '', '', '', 'SUBTOTAL REPOSICIONES:', 
                `$ ${totalImpExt.toLocaleString('en-US', {minimumFractionDigits: 2})}`,
                `$ ${totalComExt.toLocaleString('en-US', {minimumFractionDigits: 2})}`,
                `$ ${totalIvaExt.toLocaleString('en-US', {minimumFractionDigits: 2})}`,
                `$ ${granTotalExt.toLocaleString('en-US', {minimumFractionDigits: 2})}`
            ]]
        });

        // Gran Total Final
        const finalY = doc.lastAutoTable.finalY + 10;
        const totalFacturadoGeneral = granTotalReg + granTotalExt;
        const pageWidth = doc.internal.pageSize.getWidth();
        const marginHorizontal = 10;
        const rectWidth = 85;
        const rectX = pageWidth - marginHorizontal - rectWidth;

        doc.setFillColor(41, 128, 185);
        doc.rect(rectX, finalY - 5, rectWidth, 11, 'F'); 
        
        doc.setFontSize(10);
        doc.setTextColor(255);
        doc.setFont('helvetica', 'bold');
        doc.text(`TOTAL FACTURADO: $ ${totalFacturadoGeneral.toLocaleString('en-US', {minimumFractionDigits: 2})}`, pageWidth - marginHorizontal - 5, finalY + 2, { align: 'right' });

        // Paginación dinámica
        const totalPaginas = doc.internal.getNumberOfPages();
        for (let i = 1; i <= totalPaginas; i++) {
            doc.setPage(i);
            doc.setFontSize(8);
            doc.setTextColor(130);
            doc.setFont('helvetica', 'normal');
            doc.text(`Página ${i} de ${totalPaginas}`, marginHorizontal, doc.internal.pageSize.height - 10);
            let fechaHoy = new Date().toLocaleDateString();
            doc.text(`Impreso el: ${fechaHoy}`, pageWidth - marginHorizontal - 25, doc.internal.pageSize.height - 10);
        }

        doc.save(`CONCENTRADO_DISTRIBUIDO_OBRAS_${new Date().getTime()}.pdf`);
    }
</script>
</main>
@endsection