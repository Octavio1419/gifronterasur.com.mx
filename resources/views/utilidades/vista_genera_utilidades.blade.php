@extends('layout.index')
@section('content')
<main class="main-content">
    <br><br><br>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <div class="modal fade" id="ayudaModal" tabindex="-1" aria-labelledby="ayudaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content modal-rc">
                <div class="modal-header modal-rc__header">
                    <h5 class="modal-title" id="ayudaModalLabel"><i class="fa-solid fa-circle-info me-2"></i>Cómo funciona este reporte</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-4">
                    <p>El costo real de cada línea se busca directo en las facturas capturadas (por código de producto), tomando la factura vigente más cercana a la fecha de venta. Si no hay ninguna factura antes de esa fecha, el sistema usa la más antigua disponible como estimación y la marca como <strong>"Estimado"</strong>.</p>
                    <p>Si un costo se ve incorrecto, puedes corregirlo directamente desde los paneles de <strong>"Sin costo"</strong>, <strong>"Estimados"</strong> o <strong>"Por revisar"</strong> — la corrección aplica solo a esa línea específica, no a todas las ventas de ese producto.</p>
                    <div class="help-note help-note--req">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <div><strong>Requisito:</strong> el reporte solo puede generarse dentro de un mismo mes. Si mezclas fechas de meses distintos, el sistema te avisará.</div>
                    </div>
                    <div class="help-note help-note--info">
                        <i class="fa-solid fa-file-export"></i>
                        <div><strong>Exportación:</strong> el reporte se genera en PDF o XLSX y se descarga automáticamente al dar clic en el botón correspondiente.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-console-outline" data-bs-dismiss="modal">Entendido</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-xl" id="ModalTotalMayor" tabindex="-1" aria-labelledby="ModalTotalMayorLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable">
         <div class="modal-content modal-rc">
            <div class="modal-header modal-rc__header modal-rc__header--warning">
                <h5 class="modal-title" id="ModalTotalMayorLabel"><i class="fa-solid fa-circle-exclamation me-2"></i>Costos por revisar</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
               <div class="table-responsive">
                     <table class="table table-rc mb-0">
                              <thead>
                                 <tr>
                                    <th>N. rem</th>
                                    <th>Fecha</th>
                                    <th>Vendedor</th>
                                    <th>Cliente</th>
                                    <th>ID prod.</th>
                                    <th>Descripción</th>
                                    <th>Línea</th>
                                    <th>Unidad</th>
                                    <th class="text-end">Cant.</th>
                                    <th class="text-end">Precio</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-end">IVA</th>
                                    <th class="text-end">Total</th>
                                    <th>Vigente</th>
                                    <th>Forma pago</th>
                                    <th class="text-end">Últ. costo</th>
                                    <th class="text-end">Costo real</th>
                                    <th class="text-center">Guardar</th>
                                 </tr>
                              </thead>
                              <tbody id="datos_con_mayores_costos"></tbody>
                        </table>
                  </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-console-outline" data-bs-dismiss="modal">Cerrar</button>
            </div>
         </div>
      </div>
    </div>
    <div class="modal fade modal-xl" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable">
         <div class="modal-content modal-rc">
            <div class="modal-header modal-rc__header modal-rc__header--danger">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa-solid fa-ban me-2"></i>Productos sin costo registrado</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
             <div class="table-responsive">
                     <table class="table table-rc mb-0">
                              <thead>
                                 <tr>
                                    <th>N. rem</th>
                                    <th>Fecha</th>
                                    <th>ID prod.</th>
                                    <th>Descripción</th>
                                    <th>Línea</th>
                                    <th>Unidad</th>
                                    <th class="text-end">Cant.</th>
                                    <th class="text-end">Precio</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-end">IVA</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end">Últ. costo</th>
                                    <th class="text-end">Costo real</th>
                                    <th class="text-center">Guardar</th>
                                 </tr>
                              </thead>
                              <tbody id="datos_sin_costos"></tbody>
                        </table>
                  </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-console-outline" data-bs-dismiss="modal">Cerrar</button>
            </div>
         </div>
      </div>
    </div>
    <div class="modal fade modal-xl" id="ModalEstimados" tabindex="-1" aria-labelledby="ModalEstimadosLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable">
         <div class="modal-content modal-rc">
            <div class="modal-header modal-rc__header modal-rc__header--info">
                <h5 class="modal-title" id="ModalEstimadosLabel"><i class="fa-solid fa-clock-rotate-left me-2"></i>Costos estimados (sin factura previa a la venta)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
               <div class="table-responsive">
                     <table class="table table-rc mb-0">
                              <thead>
                                 <tr>
                                    <th>N. rem</th>
                                    <th>Fecha</th>
                                    <th>Vendedor</th>
                                    <th>Cliente</th>
                                    <th>ID prod.</th>
                                    <th>Descripción</th>
                                    <th>Línea</th>
                                    <th>Unidad</th>
                                    <th class="text-end">Cant.</th>
                                    <th class="text-end">Precio</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-end">IVA</th>
                                    <th class="text-end">Total</th>
                                    <th>Vigente</th>
                                    <th>Forma pago</th>
                                    <th class="text-end">Últ. costo</th>
                                    <th class="text-end">Costo real</th>
                                    <th class="text-center">Guardar</th>
                                 </tr>
                              </thead>
                              <tbody id="datos_estimados"></tbody>
                        </table>
                  </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-console-outline" data-bs-dismiss="modal">Cerrar</button>
            </div>
         </div>
      </div>
    </div>
    <div class="container-fluid content-inner mt-n5 py-0 reports-console">
      <div class="row justify-content-center">
        <div class="col-lg-12">
          <div class="console-shell">
            <div class="console-header">
                <div class="console-header__left">
                    <div class="console-header__icon"><i class="fa-solid fa-chart-line"></i></div>
                    <div>
                        <h4 class="console-header__title">
                            Reporte de utilidades
                            <a href="#" data-bs-toggle="modal" data-bs-target="#ayudaModal" class="help-trigger" title="Cómo funciona">
                                <i class="fa-solid fa-circle-question"></i>
                            </a>
                        </h4>
                        <span class="console-header__subtitle">Calcula la utilidad neta por sucursal comparando venta contra el último costo real</span>
                    </div>
                </div>
                <div class="console-export__actions" id="acciones_botones_generar" style="opacity:0.35; pointer-events:none;">
                    <button class="btn-console-outline" id="btnDescargarExcel" type="button">
                        <i class="fa-solid fa-file-excel" style="color:#1C8A4B;"></i> Excel
                    </button>
                    <button class="btn-console-outline" id="btnDescargarPdf" type="button">
                        <i class="fa-solid fa-file-pdf" style="color:#C1272D;"></i> PDF
                    </button>
                </div>
            </div>
            <div class="console-toolbar">
                <div class="toolbar-label"><i class="fa-solid fa-store"></i> Paso 1 · Selecciona la sucursal</div>
                <div class="toolbar-grid" style="grid-template-columns: 1fr auto; align-items:end;">
                    <div class="field-underline">
                        <label for="selector_tiendas">Sucursal</label>
                        <div class="field-underline__row field-underline__row--select">
                            <select id="selector_tiendas"></select>
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                    </div>
                    <button class="btn-console-primary" type="button" id="buscar_tiendas">
                        <i class="fa-solid fa-magnifying-glass"></i> Buscar
                    </button>
                </div>
            </div>
            <div class="console-toolbar" id="fechas_rango" style="opacity:0.4; pointer-events:none;">
                <div class="toolbar-label"><i class="fa-solid fa-calendar-days"></i> Paso 2 · Rango de fechas (mismo mes)</div>
                <div class="toolbar-grid" style="grid-template-columns: 1fr 1fr auto; align-items:end;">
                    <div class="field-underline">
                        <label for="startDate">Fecha inicio</label>
                        <div class="field-underline__row">
                            <i class="fa-solid fa-calendar-day"></i>
                            <input id="startDate" type="date" max="{{ date('Y-m-d') }}" disabled>
                        </div>
                    </div>
                    <div class="field-underline">
                        <label for="endDate">Fecha final</label>
                        <div class="field-underline__row">
                            <i class="fa-solid fa-calendar-check"></i>
                            <input id="endDate" type="date" disabled>
                        </div>
                    </div>
                    <button class="btn-console-primary" type="button" id="genera_consulta_utilidades" disabled>
                        <i class="fa-solid fa-table-list"></i> Generar tabla
                    </button>
                </div>
            </div>
            <div class="console-summary" id="resumen_utilidades"></div>
            <div class="console-alerts">
                <div class="alert-card alert-card--danger" id="sin_ultimo_costo" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <div class="alert-card__icon"><i class="fa-solid fa-ban"></i></div>
                    <div class="alert-card__body">
                        <strong id="sin_ultimo_costo_count">0</strong>
                        <span>Productos sin costo</span>
                    </div>
                    <i class="fa-solid fa-chevron-right alert-card__go"></i>
                </div>
                <div class="alert-card alert-card--info" id="costos_estimados" data-bs-toggle="modal" data-bs-target="#ModalEstimados">
                    <div class="alert-card__icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
                    <div class="alert-card__body">
                        <strong id="costos_estimados_count">0</strong>
                        <span>Costos estimados</span>
                    </div>
                    <i class="fa-solid fa-chevron-right alert-card__go"></i>
                </div>
                <div class="alert-card alert-card--warning" id="costos_altos" data-bs-toggle="modal" data-bs-target="#ModalTotalMayor">
                    <div class="alert-card__icon"><i class="fa-solid fa-circle-exclamation"></i></div>
                    <div class="alert-card__body">
                        <strong id="costos_altos_count">0</strong>
                        <span>Costos por revisar</span>
                    </div>
                    <i class="fa-solid fa-chevron-right alert-card__go"></i>
                </div>
            </div>
            <div class="console-toolbar console-toolbar--search">
                <div class="field-underline__row field-underline__row--search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="search" id="busqueda-utilidades" placeholder="Buscar por remisión, producto, vendedor...">
                </div>
            </div>
            <div class="console-table-wrap">
                <div class="table-responsive table-scroll-sticky">
                    <table class="table table-rc mb-0" id="tabla_utilidades">
                        <thead>
                            <tr>
                                <th class="sticky-col sticky-col--1">N. rem</th>
                                <th class="sticky-col sticky-col--2">Fecha</th>
                                <th>Vendedor</th>
                                <th>ID prod.</th>
                                <th>Descripción</th>
                                <th>Línea</th>
                                <th>Unidad</th>
                                <th class="text-end">Cant.</th>
                                <th class="text-end">Precio</th>
                                <th class="text-end">Subtotal</th>
                                <th class="text-end">IVA</th>
                                <th class="text-end">Total</th>
                                <th class="text-center">Vigente</th>
                                <th>Forma pago</th>
                                <th class="text-end">Últ. costo</th>
                                <th class="text-end">Costo real</th>
                                <th class="text-end">Utilidad</th>
                            </tr>
                        </thead>
                        <tbody id="datos_utilidades_tabla">
                            <tr class="table-empty-state">
                                <td colspan="17">
                                    <i class="fa-solid fa-store"></i>
                                    Selecciona una sucursal y un rango de fechas para generar la tabla
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div id="loading-spinner" style="display:none;">
                        <div class="spinner"></div>
                        <p>Generando tabla...</p>
                    </div>
                </div>
            </div>

          </div>
        </div>
      </div>
    </div>
     <style>
    .reports-console{
        --ink: #101826; --ink-soft: #1E293B; --canvas: #F2F4F7; --surface: #FFFFFF;
        --line: #E2E5EB; --line-soft: #ECEEF2; --muted: #667085;
        --accent: #D98A2B; --accent-ink: #7C4A0F; --accent-soft: #FBEBD3;
        --ok: #1C8A4B; --ok-soft: #E5F5EC;
        --danger: #C1272D; --danger-soft: #FBE7E8;
        --warning: #B8730A; --warning-soft: #FCEFD9;
        --radius: 14px;
        font-family: 'Inter', -apple-system, sans-serif;
        color: var(--ink);
    }
    .reports-console *{ box-sizing: border-box; }
    .reports-console input, .reports-console select, .reports-console button{ font-family: inherit; }

    .console-shell{ background: var(--canvas); border-radius: 20px; padding: 0 0 4px; overflow: hidden; border: 1px solid var(--line); }

    .console-header{
        background: linear-gradient(135deg, #101826 0%, #1B2740 100%);
        padding: 26px 30px; display:flex; align-items:center; justify-content:space-between; flex-wrap: wrap; gap: 14px;
    }
    .console-header__left{ display:flex; align-items:center; gap:16px; }
    .console-header__icon{
        width:52px; height:52px; border-radius:12px; background: var(--accent-soft); color: var(--accent-ink);
        display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0;
    }
    .console-header__title{
        font-family:'Oswald', sans-serif; font-weight:600; letter-spacing:0.01em; font-size:1.35rem;
        color:#fff; margin:0; text-transform:uppercase; display:flex; align-items:center; gap:10px;
    }
    .console-header__subtitle{ display:block; color:#AAB4C6; font-size:0.85rem; margin-top:2px; max-width: 520px; }
    .help-trigger{ color: var(--accent); font-size:1.05rem; }
    .help-trigger:hover{ color:#fff; }

    /* Toolbar */
    .console-toolbar{ background: var(--surface); margin: 18px 24px 0; padding: 20px 22px; border-radius: var(--radius); border: 1px solid var(--line); transition: opacity .2s ease; }
    .console-toolbar--search{ padding: 12px 18px; }
    .toolbar-label{ display:flex; align-items:center; gap:8px; font-weight:600; font-size:0.9rem; color: var(--ink-soft); margin-bottom:16px; }
    .toolbar-label i{ color: var(--accent); }
    .toolbar-grid{ display:grid; gap: 18px 20px; }
    @media (max-width: 800px){ .toolbar-grid{ grid-template-columns: 1fr !important; } }

    .field-underline label{ display:block; font-size:0.74rem; font-weight:600; color: var(--muted); margin-bottom:6px; }
    .field-underline__row{ display:flex; align-items:center; gap:10px; border-bottom:1.5px solid var(--line); padding-bottom:8px; }
    .field-underline__row:focus-within{ border-color: var(--accent); }
    .field-underline__row i{ color:#98A2B3; font-size:0.85rem; }
    .field-underline__row:focus-within i{ color: var(--accent); }
    .field-underline__row input, .field-underline__row select{
        border:none; outline:none; background:transparent; flex:1; font-size:0.9rem; font-weight:500; color: var(--ink); padding:2px 0;
    }
    .field-underline__row--select select{ appearance:none; -webkit-appearance:none; cursor:pointer; }
    .field-underline__row--select i{ font-size:0.68rem; }
    .field-underline__row--search{ border-bottom:none; padding:4px 6px; }
    .field-underline__row--search i{ font-size:0.95rem; }
    .field-underline__row--search input{ font-size:0.92rem; }

    .btn-console-primary{
        border:none; background: var(--ink); color:#fff; font-weight:600; font-size:0.86rem; padding:12px 20px;
        border-radius:10px; display:flex; align-items:center; justify-content:center; gap:8px; white-space:nowrap;
        transition: background .15s ease;
    }
    .btn-console-primary:hover{ background: var(--accent-ink); }
    .btn-console-primary:disabled{ background:#B4BAC6; cursor:not-allowed; }

    .btn-console-outline{
        border:1.5px solid var(--line); background:#fff; color: var(--ink-soft); font-weight:600; font-size:0.82rem;
        padding:10px 16px; border-radius:10px; display:flex; align-items:center; gap:8px; transition: all .15s ease;
    }
    .btn-console-outline:hover{ border-color: var(--accent); color: var(--accent-ink); }
    .console-export__actions{ display:flex; gap:10px; flex-wrap:wrap; }

    /* Resumen */
    .console-summary{
        margin: 16px 24px 0; padding: 16px 22px; background: var(--ink); border-radius: var(--radius);
        display:grid; grid-template-columns: repeat(4, 1fr); gap: 14px;
    }
    .console-summary:empty{ display:none; }
    .summary-item{ display:flex; flex-direction:column; gap:2px; }
    .summary-item span{ font-size:0.72rem; color:#AAB4C6; font-weight:500; }
    .summary-item strong{ font-size:1.1rem; color:#fff; font-variant-numeric: tabular-nums; }
    .summary-item strong.positivo{ color:#7BE0A8; }
    .summary-item strong.negativo{ color:#F29B9E; }
    @media (max-width: 800px){ .console-summary{ grid-template-columns: repeat(2, 1fr); } }

    /* Alertas */
    .console-alerts{ margin: 16px 24px 0; display:grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
    @media (max-width: 700px){ .console-alerts{ grid-template-columns: 1fr; } }
    .alert-card{
        background: var(--surface); border:1px solid var(--line); border-radius: var(--radius);
        padding: 16px 18px; display:flex; align-items:center; gap:14px; cursor:pointer;
        transition: border-color .15s ease, transform .1s ease;
    }
    .alert-card:hover{ transform: translateY(-1px); }
    .alert-card__icon{ width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0; }
    .alert-card--danger{ border-left: 3px solid var(--danger); }
    .alert-card--danger .alert-card__icon{ background: var(--danger-soft); color: var(--danger); }
    .alert-card--danger:hover{ border-color: var(--danger); }
    .alert-card--warning{ border-left: 3px solid var(--warning); }
    .alert-card--warning .alert-card__icon{ background: var(--warning-soft); color: var(--warning); }
    .alert-card--warning:hover{ border-color: var(--warning); }
    .alert-card__body{ display:flex; flex-direction:column; flex:1; }
    .alert-card__body strong{ font-size:1.3rem; line-height:1.1; color: var(--ink); font-variant-numeric: tabular-nums; }
    .alert-card__body span{ font-size:0.78rem; color: var(--muted); font-weight:500; }
    .alert-card__go{ color:#C0C6D1; font-size:0.8rem; }

    /* Tabla */
    .console-table-wrap{ margin: 16px 24px 22px; background: var(--surface); border:1px solid var(--line); border-radius: var(--radius); overflow:hidden; position: relative; }
    .table-scroll-sticky{ max-height: 620px; overflow: auto; }
    .table-rc{ font-size:0.84rem; margin:0; font-variant-numeric: tabular-nums; }
    .table-rc thead th{
        background: var(--ink); color:#C7CFDD; font-weight:600; font-size:0.72rem; letter-spacing:0.02em;
        border:none; padding:12px 14px; white-space:nowrap; position: sticky; top:0; z-index: 2;
    }
    .table-rc tbody td{ padding:11px 14px; vertical-align:middle; white-space:nowrap; border-bottom:1px solid var(--line-soft); }
    .table-rc tbody tr:hover{ background:#FAFBFC; }
    .table-rc tbody tr.table-warning{ background: var(--warning-soft) !important; }
    .table-rc tbody tr.table-secondary{ background: var(--danger-soft) !important; }

    /* columnas fijas al hacer scroll horizontal en la tabla principal */
    .sticky-col{ position: sticky; left: 0; background: var(--ink); z-index: 3; }
    .sticky-col--2{ left: 90px; }
    tbody .sticky-col{ background: var(--surface); z-index: 1; }
    tbody tr:hover .sticky-col{ background:#FAFBFC; }
    tbody tr.table-warning .sticky-col{ background: var(--warning-soft); }
    tbody tr.table-secondary .sticky-col{ background: var(--danger-soft); }

    .table-empty-state td{ text-align:center; padding: 50px 20px !important; color: var(--muted); font-size:0.9rem; white-space: normal !important; }
    .table-empty-state i{ display:block; font-size:1.8rem; margin-bottom:10px; color:#C0C6D1; }

    #loading-spinner{ position:absolute; top:0; left:0; right:0; bottom:0; background: rgba(255,255,255,0.85); display:flex; flex-direction:column; align-items:center; justify-content:center; gap:14px; z-index: 5; }
    .spinner{ width:46px; height:46px; border:4px solid var(--line); border-top:4px solid var(--accent); border-radius:50%; animation: spin 0.9s linear infinite; }
    #loading-spinner p{ font-size:0.86rem; font-weight:600; color: var(--ink-soft); margin:0; }
    @keyframes spin{ from{ transform: rotate(0deg);} to{ transform: rotate(360deg);} }

    .input-costo-real{ width:110px; border:1.5px solid var(--line); border-radius:8px; padding:6px 8px; font-weight:600; }
    .input-costo-real:focus{ outline:none; border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-soft); }
    .btn-guardar-costo{ border:none; background: var(--ok); color:#fff; width:32px; height:32px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; }
    .btn-guardar-costo:hover{ background:#166B3B; }
    .btn-guardar-costo:disabled{ opacity:.6; }

    /* Modales */
    .modal-rc{ border:none; border-radius:16px; overflow:hidden; }
    .modal-rc__header{ background: linear-gradient(135deg, #101826 0%, #1B2740 100%); color:#fff; border:none; }
    .modal-rc__header--danger{ background: linear-gradient(135deg, #7A1B20 0%, #C1272D 100%); }
    .modal-rc__header--warning{ background: linear-gradient(135deg, #7C4A0F 0%, #B8730A 100%); }
    .modal-rc__header .modal-title{ color:#fff; font-weight:600; }
    .help-note{ display:flex; gap:12px; padding:12px 14px; border-radius:10px; font-size:0.86rem; margin-top:14px; }
    .help-note i{ margin-top:2px; }
    .help-note--req{ background: var(--warning-soft); color: var(--warning); }
    .help-note--info{ background: var(--accent-soft); color: var(--accent-ink); }
    .help-note div{ color: var(--ink-soft); }
    .help-note strong{ color: inherit; }
    </style>

 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<script>
        $(document).ready(function(){
            selector();
            var nombre_interno = 0;
            var nombre_real = 0;
            var datosGlobalesRemisiones = [];

            function selector(){
                fetch('/sucursales-selector').then(response => response.json()).then(respuesta => {
                    let selector_suc = document.getElementById('selector_tiendas');
                    selector_suc.innerHTML = '<option value="">Selecciona la sucursal</option>';
                    let resp = respuesta.sucursal;
                    resp.forEach(sucursal => {
                        let option = document.createElement('option');
                        option.value = sucursal.nombre_interno;
                        option.textContent = sucursal.nombre_real;
                        selector_suc.appendChild(option);
                    });
                    $('#selector_tiendas').val('').change();
                });
            }

            $('#buscar_tiendas').click(function(){
                nombre_interno = $('#selector_tiendas').val();
                nombre_real = $('#selector_tiendas option:selected').text();

                if(nombre_interno){
                    $('#fechas_rango').css({'opacity': '1', 'pointer-events': 'auto'});
                    $('#startDate, #endDate, #genera_consulta_utilidades').prop('disabled', false);

                    $('#genera_consulta_utilidades').off('click').click(function(){

                        const fecha_inicio = document.getElementById('startDate').value;
                        const fecha_final = document.getElementById('endDate').value;

                        if (!fecha_inicio || !fecha_final) {
                            Swal.fire({ icon: 'warning', title: 'Fechas requeridas', text: 'Seleccione ambas fechas antes de continuar.', confirmButtonText: 'Aceptar' });
                            return;
                        }
                        if (fecha_final < fecha_inicio) {
                            Swal.fire({ icon: 'error', title: 'Rango inválido', text: 'La fecha final no puede ser menor a la fecha de inicio.', confirmButtonText: 'Aceptar' });
                            return;
                        }
                        const [anioInicio, mesInicio] = fecha_inicio.split('-');
                        const [anioFinal, mesFinal] = fecha_final.split('-');
                        if (mesInicio !== mesFinal || anioInicio !== anioFinal) {
                            Swal.fire({ icon: 'error', title: 'Mes distinto', text: 'Los reportes solo pueden generarse por mes.', confirmButtonText: 'Aceptar' });
                            return;
                        }

                        const spinner = document.getElementById('loading-spinner');
                        if (spinner) spinner.style.display = "flex";

                        fetch('/recupera-datos-utilidades/' + nombre_interno + '/' + fecha_inicio + '/' + fecha_final)
                            .then(response => response.json())
                            .then(respuesta => {
                                datosGlobalesRemisiones = respuesta.remisiones;
                                generaTablaDinamicaUtilidades(respuesta);
                            });

                        $('#acciones_botones_generar').css({'opacity':'1', 'pointer-events':'auto'});
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Por favor, seleccione la sucursal en el selector.', confirmButtonText: 'Aceptar' });
                }
            });

     
            function etiquetaCosto(d) {
                if (d.ajustado_manual) return ' <span class="badge bg-primary ms-1" style="font-size:9px;">Ajustado</span>';
                if (d.costo_estimado) return ' <span class="badge bg-info text-dark ms-1" style="font-size:9px;">Estimado</span>';
                return '';
            }

            function generaTablaDinamicaUtilidades(respuesta){
                console.log('que devuelve', respuesta);
                let datos = respuesta.remisiones;
                let totales = respuesta.totales;

                const tbody = document.getElementById('datos_utilidades_tabla');
                tbody.innerHTML = '';
                const fragment = document.createDocumentFragment();

                let almacena_null = [];
                let almacena_sospechosos = [];
                let almacena_estimados = [];

                if (datos.length === 0) {
                    tbody.innerHTML = `<tr class="table-empty-state"><td colspan="17"><i class="fa-solid fa-circle-info"></i>No se encontraron movimientos en el rango seleccionado</td></tr>`;
                } else {
                    datos.forEach(d => {
                        const fila = document.createElement('tr');
                        if (d.sospechoso) fila.classList.add('table-warning');
                        else if (!d.tiene_costo) fila.classList.add('table-secondary');
                        else if (d.costo_estimado) fila.classList.add('table-info');

                        fila.innerHTML = `
                            <td class="sticky-col sticky-col--1 fw-bold">${d.num_remision}</td>
                            <td class="sticky-col sticky-col--2 text-muted">${d.fecha}</td>
                            <td>${d.vendedor}</td>
                            <td>${d.producto_id}</td>
                            <td>${d.producto_descripcion}</td>
                            <td>${d.producto_linea}</td>
                            <td class="text-muted">${d.unidad_compra ?? '-'}</td>
                            <td class="text-end">${d.cantidad_producto}</td>
                            <td class="text-end">$${parseFloat(d.precio).toFixed(2)}</td>
                            <td class="text-end">$${parseFloat(d.subtotal).toFixed(2)}</td>
                            <td class="text-end">$${parseFloat(d.iva).toFixed(2)}</td>
                            <td class="text-end fw-bold">$${parseFloat(d.total).toFixed(2)}</td>
                            <td class="text-center">${d.vigente}</td>
                            <td>${d.forma_pago}</td>
                            <td class="text-end">$${parseFloat(d.ultimo_costo).toFixed(2)}</td>
                            <td class="text-end">${d.tiene_costo ? '$' + parseFloat(d.costo_real_unitario).toFixed(2) + etiquetaCosto(d) : '<span class="text-danger fw-bold">Sin costo</span>'}</td>
                            <td class="text-end fw-bold">${d.tiene_costo ? '$' + d.utilidad_linea.toFixed(2) : '-'}</td>
                        `;
                        fragment.appendChild(fila);

                        if (!d.tiene_costo) almacena_null.push(d);
                        if (d.sospechoso) almacena_sospechosos.push(d);
                        if (d.costo_estimado) almacena_estimados.push(d);
                    });
                    tbody.appendChild(fragment);
                }

                const formatoMXN = new Intl.NumberFormat('es-MX', {
                    style: 'currency',
                    currency: 'MXN',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                const margen = totales.total_ventas > 0 ? ((totales.utilidad_neta / totales.total_ventas) * 100).toFixed(1) : '0.0';

                document.getElementById('resumen_utilidades').innerHTML = `
                    <div class="summary-item"><span>Total ventas</span><strong>${formatoMXN.format(totales.total_ventas)}</strong></div>
                    <div class="summary-item"><span>Costo (c/IVA)</span><strong>${formatoMXN.format(totales.total_costo_iva)}</strong></div>
                    <div class="summary-item"><span>Utilidad neta</span><strong class="${totales.utilidad_neta >= 0 ? 'positivo' : 'negativo'}">${formatoMXN.format(totales.utilidad_neta)}</strong></div>
                    <div class="summary-item"><span>Margen</span><strong class="${totales.utilidad_neta >= 0 ? 'positivo' : 'negativo'}">${margen}%</strong></div>
                `;

                document.getElementById('sin_ultimo_costo_count').textContent = almacena_null.length;
                document.getElementById('costos_altos_count').textContent = almacena_sospechosos.length;
                document.getElementById('costos_estimados_count').textContent = almacena_estimados.length;
                document.getElementById('loading-spinner').style.display = 'none';

                $('#sin_ultimo_costo').off('click.modalfill').on('click.modalfill', function(){
                    renderModalEditable('datos_sin_costos', almacena_null, false);
                });
                $('#costos_altos').off('click.modalfill').on('click.modalfill', function(){
                    renderModalEditable('datos_con_mayores_costos', almacena_sospechosos, true);
                });
                $('#costos_estimados').off('click.modalfill').on('click.modalfill', function(){
                    renderModalEditable('datos_estimados', almacena_estimados, true);
                });
            }

            function renderModalEditable(idTbody, lista, conVendedorYCliente){
                const tbody = document.getElementById(idTbody);
                tbody.innerHTML = '';

                if (lista.length === 0) {
                    const colspan = conVendedorYCliente ? 18 : 14;
                    tbody.innerHTML = `<tr><td colspan="${colspan}" class="text-center text-muted py-4">No hay registros en esta categoría</td></tr>`;
                    return;
                }

                lista.forEach(d => {
                    const fila = document.createElement('tr');
                    const columnasExtra = conVendedorYCliente ? `<td>${d.vendedor}</td><td>${d.cliente ?? ''}</td>` : '';

                    fila.innerHTML = `
                        <td class="fw-bold">${d.num_remision}</td>
                        <td>${d.fecha}</td>
                        ${columnasExtra}
                        <td>${d.producto_id}</td>
                        <td>${d.producto_descripcion}</td>
                        <td>${d.producto_linea}</td>
                        <td class="text-muted">${d.unidad_compra ?? '-'}</td>
                        <td class="text-end">${d.cantidad_producto}</td>
                        <td class="text-end">$${parseFloat(d.precio).toFixed(2)}</td>
                        <td class="text-end">$${parseFloat(d.subtotal).toFixed(2)}</td>
                        <td class="text-end">$${parseFloat(d.iva).toFixed(2)}</td>
                        <td class="text-end fw-bold">$${parseFloat(d.total).toFixed(2)}</td>
                        ${conVendedorYCliente ? `<td class="text-center">${d.vigente}</td><td>${d.forma_pago}</td>` : ''}
                        <td class="text-end">$${parseFloat(d.ultimo_costo).toFixed(2)}</td>
                        <td>
                            <input type="number" step="0.01" min="0" class="input-costo-real"
                                   data-remision-id="${d.id}"
                                   data-producto-id="${d.producto_id}"
                                   value="${d.costo_real_unitario ?? ''}">
                        </td>
                        <td class="text-center">
                            <button class="btn-guardar-costo" data-remision-id="${d.id}" data-producto-id="${d.producto_id}" title="Guardar costo de esta línea">
                                <i class="fa-solid fa-floppy-disk"></i>
                            </button>
                        </td>
                    `;
                    tbody.appendChild(fila);
                });
            }

            $(document).on('click', '.btn-guardar-costo', function(){
                const $btn = $(this);
                const $fila = $btn.closest('tr');
                const remisionId = $btn.data('remision-id');
                const productoId = $btn.data('producto-id');
                const nuevoCosto = $fila.find('.input-costo-real').val();

                if (!nuevoCosto || nuevoCosto <= 0) {
                    Swal.fire('Atención', 'Ingresa un costo real válido', 'warning');
                    return;
                }
                if (!nombre_interno) {
                    Swal.fire('Atención', 'No se detectó la sucursal activa, vuelve a buscar', 'warning');
                    return;
                }

                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

                fetch(`/utilidades/corregir-costo-remision/${nombre_interno}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({
                        remision_id: remisionId,
                        producto_id: productoId,
                        costo_real_unitario: parseFloat(nuevoCosto),
                    })
                })
                .then(r => r.json())
                .then(resp => {
                    Swal.fire({ title: 'Guardado', text: resp.message, icon: 'success', timer: 1500, showConfirmButton: false });
                    $fila.css('background', 'var(--ok-soft)'); $btn.prop('disabled', false).html('<i class="fa-solid fa-floppy-disk"></i>');
                })
                .catch(() => {
                    Swal.fire('Error', 'No se pudo guardar el costo', 'error');
                    $btn.prop('disabled', false).html('<i class="fa-solid fa-floppy-disk"></i>');
                });
            });

            $('#btnDescargarExcel').click(function(){
                if (!datosGlobalesRemisiones || datosGlobalesRemisiones.length === 0) {
                    Swal.fire('Aviso', 'No hay datos cargados para exportar.', 'warning');
                    return;
                }

                let htmlTabla = `
                    <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
                    <head>
                        <meta charset="UTF-8">
                        <style>
                            .titulo { font-size: 16px; font-weight: bold; background-color: #0f172a; color: #ffffff; text-align: center; vertical-align: middle; height: 40px; }
                            .header-table { background-color: #1e293b; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #0f172a; font-family: 'Inter', Arial, sans-serif; }
                            .celda { font-family: 'Inter', Arial, sans-serif; font-size: 10pt; border: 1px solid #cbd5e1; vertical-align: middle; }
                            .alerta-amarilla { background-color: #fef3c7; color: #92400e; }
                            .alerta-gris { background-color: #f1f5f9; color: #475569; }
                            .alerta-azul { background-color: #dbeafe; color: #1e40af; }
                            .text-right { text-align: right; }
                            .text-center { text-align: center; }
                            .bold { font-weight: bold; }
                        </style>
                    </head>
                    <body>
                        <table>
                            <tr>
                                <td colspan="17" class="titulo">Reporte de Utilidades - ${nombre_real} (${document.getElementById('startDate').value} al ${document.getElementById('endDate').value})</td>
                            </tr>
                            <tr><td colspan="17"></td></tr>
                            <tr>
                                <th class="header-table">N. Remisión</th>
                                <th class="header-table">Fecha</th>
                                <th class="header-table">Vendedor</th>
                                <th class="header-table">ID Producto</th>
                                <th class="header-table">Descripción</th>
                                <th class="header-table">Línea</th>
                                <th class="header-table">Unidad</th>
                                <th class="header-table">Cantidad</th>
                                <th class="header-table">Precio</th>
                                <th class="header-table">Subtotal</th>
                                <th class="header-table">IVA</th>
                                <th class="header-table">Total</th>
                                <th class="header-table">Vigente</th>
                                <th class="header-table">Forma Pago</th>
                                <th class="header-table">Último Costo</th>
                                <th class="header-table">Costo Real</th>
                                <th class="header-table">Utilidad</th>
                            </tr>`;

                datosGlobalesRemisiones.forEach(d => {
                    let claseFondo = "";
                    if (d.sospechoso) {
                        claseFondo = "alerta-amarilla";
                    } else if (!d.tiene_costo) {
                        claseFondo = "alerta-gris";
                    } else if (d.costo_estimado) {
                        claseFondo = "alerta-azul";
                    }

                    htmlTabla += `
                        <tr>
                            <td class="celda bold ${claseFondo}">${d.num_remision}</td>
                            <td class="celda text-center ${claseFondo}">${d.fecha}</td>
                            <td class="celda ${claseFondo}">${d.vendedor}</td>
                            <td class="celda text-center ${claseFondo}">${d.producto_id}</td>
                            <td class="celda ${claseFondo}">${d.producto_descripcion}</td>
                            <td class="celda ${claseFondo}">${d.producto_linea}</td>
                            <td class="celda text-center ${claseFondo}">${d.unidad_compra ?? '-'}</td>
                            <td class="celda text-right ${claseFondo}">${d.cantidad_producto}</td>
                            <td class="celda text-right ${claseFondo}">$${parseFloat(d.precio).toFixed(2)}</td>
                            <td class="celda text-right ${claseFondo}">$${parseFloat(d.subtotal).toFixed(2)}</td>
                            <td class="celda text-right ${claseFondo}">$${parseFloat(d.iva).toFixed(2)}</td>
                            <td class="celda text-right bold ${claseFondo}">$${parseFloat(d.total).toFixed(2)}</td>
                            <td class="celda text-center ${claseFondo}">${d.vigente}</td>
                            <td class="celda ${claseFondo}">${d.forma_pago}</td>
                            <td class="celda text-right ${claseFondo}">$${parseFloat(d.ultimo_costo).toFixed(2)}</td>
                            <td class="celda text-right ${claseFondo}">${d.tiene_costo ? '$' + parseFloat(d.costo_real_unitario).toFixed(2) : 'Sin costo'}</td>
                            <td class="celda text-right bold ${claseFondo}">${d.tiene_costo ? '$' + d.utilidad_linea.toFixed(2) : '-'}</td>
                        </tr>
                    `;
                });

                htmlTabla += `</table></body></html>`;

                let blob = new Blob([htmlTabla], { type: 'application/vnd.ms-excel' });
                let url = URL.createObjectURL(blob);
                let a = document.createElement('a');
                a.href = url;
                a.download = `Reporte_Utilidades_${nombre_real.replace(/\s+/g, '_')}_${document.getElementById('startDate').value}.xls`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            });

            $('#btnDescargarPdf').click(function(){
                if (!datosGlobalesRemisiones || datosGlobalesRemisiones.length === 0) {
                    Swal.fire('Aviso', 'No hay datos cargados para exportar.', 'warning');
                    return;
                }

                const { jsPDF } = window.jspdf;
                const doc = new jsPDF('landscape');

                doc.setFontSize(16);
                doc.text(`Reporte de Utilidades - ${nombre_real}`, 14, 15);

                doc.setFontSize(10);
                doc.text(`Periodo: ${document.getElementById('startDate').value} al ${document.getElementById('endDate').value}`, 14, 22);

                let columnas = [
                    "N. Rem", "Fecha", "Vendedor", "ID Prod", "Descripción", "Unidad",
                    "Cant", "Precio", "Subtotal", "Total", "Últ. Costo", "Costo Real", "Utilidad"
                ];

                let filas = datosGlobalesRemisiones.map(d => [
                    d.num_remision,
                    d.fecha,
                    d.vendedor,
                    d.producto_id,
                    d.producto_descripcion,
                    d.unidad_compra ?? '-',
                    d.cantidad_producto,
                    `$${parseFloat(d.precio).toFixed(2)}`,
                    `$${parseFloat(d.subtotal).toFixed(2)}`,
                    `$${parseFloat(d.total).toFixed(2)}`,
                    `$${parseFloat(d.ultimo_costo).toFixed(2)}`,
                    d.tiene_costo ? `$${parseFloat(d.costo_real_unitario).toFixed(2)}` : 'Sin costo',
                    d.tiene_costo ? `$${d.utilidad_linea.toFixed(2)}` : '-'
                ]);

                doc.autoTable({
                    head: [columnas],
                    body: filas,
                    startY: 28,
                    styles: { fontSize: 8 },
                    headStyles: { fillColor: [30, 41, 59] }
                });

                let nombreArchivo = `Reporte_Utilidades_${nombre_real.replace(/\s+/g, '_')}_${document.getElementById('startDate').value}.pdf`;
                doc.save(nombreArchivo);
            });

        });

        document.getElementById('busqueda-utilidades').addEventListener('keyup', function () {
            const texto = this.value.toLowerCase();
            const filas = document.querySelectorAll('#datos_utilidades_tabla tr');
            filas.forEach(fila => {
                const celdas = fila.querySelectorAll('td');
                const coincide = Array.from(celdas).some(td => td.textContent.toLowerCase().includes(texto));
                fila.style.display = coincide ? '' : 'none';
            });
        });
    </script>
</main>
@endsection