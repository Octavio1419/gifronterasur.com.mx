@extends('layout.index')
@section('content')
<main class="main-content">
    <br><br><br><br>
    <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div id="flota-app">
                    <!-- ===================== TOOLBAR ===================== -->
                    <div class="fl-toolbar">
                        <div class="fl-toolbar-title">
                            <div class="fl-icon-badge"><i class="fa-solid fa-car-side"></i></div>
                            <div>
                                <h4>Vehículos</h4>
                                <span>Flota registrada y su estado operativo</span>
                            </div>
                        </div>
                        <div class="fl-toolbar-actions">
                            <div class="fl-search">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="text" id="fl-search-input" placeholder="Buscar por placa, serie, marca o titular...">
                            </div>
                            <select id="fl-filter-obra" class="fl-select">
                                <option value="">Todas las obras</option>
                            </select>
                            <select id="fl-filter-seguro" class="fl-select">
                                <option value="">Cobertura: todas</option>
                                <option value="1">Asegurados</option>
                                <option value="0">Sin seguro</option>
                            </select>
                           
                        </div>
                    </div>
                    <!-- ===================== STAT STRIP ===================== -->
                    <div class="fl-stats">
                        <div class="fl-stat fl-stat-neutral">
                            <div class="fl-stat-icon"><i class="fa-solid fa-truck-fast"></i></div>
                            <div>
                                <span class="fl-stat-num fl-skeleton" id="stat-total">&nbsp;</span>
                                <span class="fl-stat-label">Vehículos en flota</span>
                            </div>
                        </div>
                        <div class="fl-stat fl-stat-ok">
                            <div class="fl-stat-icon"><i class="fa-solid fa-shield-halved"></i></div>
                            <div>
                                <span class="fl-stat-num fl-ok fl-skeleton" id="stat-asegurados">&nbsp;</span>
                                <span class="fl-stat-label">Con cobertura</span>
                            </div>
                        </div>
                        <div class="fl-stat fl-stat-bad">
                            <div class="fl-stat-icon"><i class="fa-solid fa-shield-heart"></i></div>
                            <div>
                                <span class="fl-stat-num fl-bad fl-skeleton" id="stat-sinseguro">&nbsp;</span>
                                <span class="fl-stat-label">Sin póliza</span>
                            </div>
                        </div>
                        <div class="fl-stat fl-stat-warn">
                            <div class="fl-stat-icon"><i class="fa-solid fa-location-crosshairs"></i></div>
                            <div>
                                <span class="fl-stat-num fl-warn fl-skeleton" id="stat-sinobra">&nbsp;</span>
                                <span class="fl-stat-label">Sin obra asignada</span>
                            </div>
                        </div>
                    </div>
                    <!-- ===================== GRID DE VEHÍCULOS ===================== -->
                    <div class="fl-loading-strip" id="fl-loading-strip">
                        <div class="fl-spinner"></div>
                        <span>Cargando flota...</span>
                    </div>
                    <div class="fl-grid" id="fl-grid"></div>
                    <div id="fl-empty" class="fl-empty" style="display:none;">
                        <i class="fa-solid fa-car-burst"></i>
                        <h5>No hay vehículos que coincidan</h5>
                        <p>Ajusta la búsqueda o los filtros para ver más resultados.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- ===================== DRAWER: DETALLE / EDICIÓN ===================== -->
<div class="fl-overlay" id="fl-overlay"></div>
<aside class="fl-drawer" id="fl-drawer">
    <div class="fl-drawer-header">
        <div class="fl-drawer-title">
            <span class="fl-drawer-eyebrow" id="dw-serie">—</span>
            <h5 id="dw-nombre">Vehículo</h5>
        </div>
        <button class="fl-icon-btn" id="dw-close"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="fl-drawer-body">
        <div class="fl-drawer-photo">
            <img id="dw-foto" src="" alt="Vehículo">
        </div>
        <form id="dw-form">
            <input type="hidden" id="dw-id" value="">

            <div class="fl-field-grid">
                <div class="fl-field">
                    <label>Marca</label>
                    <input type="text" id="dw-marca" disabled>
                </div>
                <div class="fl-field">
                    <label>Línea</label>
                    <input type="text" id="dw-linea" disabled>
                </div>
                <div class="fl-field">
                    <label>Modelo</label>
                    <input type="text" id="dw-modelo" disabled>
                </div>
                <div class="fl-field">
                    <label>Placas</label>
                    <input type="text" id="dw-placas" disabled>
                </div>
                <div class="fl-field">
                    <label>Serie</label>
                    <input type="text" id="dw-serie-input" disabled>
                </div>
                <div class="fl-field">
                    <label>Tarjeta de circulación</label>
                    <input type="text" id="dw-tarjeta" disabled>
                </div>
                <div class="fl-field">
                    <label>Kilometraje actual</label>
                    <input type="number" id="dw-km" disabled>
                </div>
                <div class="fl-field">
                    <label>Efecticard</label>
                    <input type="text" id="dw-efecticard" disabled>
                </div>
            </div>
            <div class="fl-divider"></div>
            <div class="fl-field-grid">
                <div class="fl-field fl-field-wide">
                    <label>Obra asignada</label>
                    <div class="fl-inline-field">
                        <select id="dw-obra" disabled></select>
                        <button type="button" class="fl-mini-btn fl-mini-danger" id="dw-quitar-obra" title="Quitar obra">
                            <i class="fa-solid fa-link-slash"></i>
                        </button>
                    </div>
                </div>
                <div class="fl-field fl-field-wide">
                    <label>Chofer asignado</label>
                    <div class="fl-inline-field">
                        <select id="dw-chofer" disabled></select>
                        <button type="button" class="fl-mini-btn fl-mini-danger" id="dw-quitar-chofer" title="Quitar chofer">
                            <i class="fa-solid fa-link-slash"></i>
                        </button>
                    </div>
                </div>
                <div class="fl-field fl-field-wide">
                    <label>Titular legal (propietario)</label>
                    <select id="dw-propietario" disabled></select>
                </div>
            </div>
            <div class="fl-divider"></div>
            <div class="fl-field-grid">
                <div class="fl-field">
                    <label>Estatus de seguro</label>
                    <select id="dw-estatus-seguro" disabled>
                        <option value="1">Asegurado</option>
                        <option value="0">Sin seguro</option>
                    </select>
                </div>
                <div class="fl-field">
                    <label>Compañía aseguradora</label>
                    <input type="text" id="dw-compania" disabled>
                </div>
                <div class="fl-field">
                    <label>Inicio de póliza</label>
                    <input type="date" id="dw-fecha-inicio" disabled>
                </div>
                <div class="fl-field">
                    <label>Fin de póliza</label>
                    <input type="date" id="dw-fecha-fin" disabled>
                </div>
            </div>
        </form>
    </div>
    <div class="fl-drawer-footer">
        <button class="fl-btn fl-btn-danger" id="dw-eliminar">
            <i class="fa-solid fa-trash-can"></i> Eliminar
        </button>
        <div class="fl-drawer-footer-right">
            <button class="fl-btn fl-btn-ghost" id="dw-cancelar" style="display:none;">Cancelar</button>
            <button class="fl-btn fl-btn-outline" id="dw-editar">
                <i class="fa-solid fa-pen"></i> Editar
            </button>
            <button class="fl-btn fl-btn-primary" id="dw-guardar" style="display:none;">
                <i class="fa-solid fa-check"></i> Guardar cambios
            </button>
        </div>
    </div>
</aside>
<!-- ===================== MODAL: CONFIRMAR ELIMINACIÓN ===================== -->
<div class="fl-modal-overlay" id="fl-confirm-overlay">
    <div class="fl-modal">
        <div class="fl-modal-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <h5>Eliminar vehículo</h5>
        <p id="fl-confirm-text">Esta acción no se puede deshacer. ¿Deseas continuar?</p>
        <div class="fl-modal-actions">
            <button class="fl-btn fl-btn-ghost" id="fl-confirm-cancel">Cancelar</button>
            <button class="fl-btn fl-btn-danger" id="fl-confirm-ok">Sí, eliminar</button>
        </div>
    </div>
</div>
<!-- ===================== TOASTS ===================== -->
<div class="fl-toast-stack" id="fl-toast-stack"></div>
<style>
:root{
    --fl-ink:#16233f;
    --fl-ink-soft:#4b5468;
    --fl-ink-faint:#8891a5;
    --fl-line:#e3e6ef;
    --fl-bg:#f8f9fc;
    --fl-surface:#FFFFFF;
    --fl-brand:#3a57e8;
    --fl-brand-soft:rgba(58,87,232,0.1);
    --fl-teal:#17904b;
    --fl-teal-soft:rgba(23,144,75,0.12);
    --fl-amber:#a6740a;
    --fl-amber-soft:rgba(255,193,7,0.18);
    --fl-danger:#d9364a;
    --fl-danger-soft:rgba(242,109,125,0.15);
    --fl-radius:14px;
    --fl-radius-sm:9px;
    --fl-shadow:0 1px 2px rgba(22,35,63,.04), 0 8px 24px -12px rgba(22,35,63,.10);
}

#flota-app{
    color:var(--fl-ink);
}

/* ---------- toolbar ---------- */
.fl-toolbar{
    display:flex;
    align-items:center;
    justify-content:space-between;
    flex-wrap:wrap;
    gap:16px;
    margin-bottom:20px;
}
.fl-toolbar-title{ display:flex; align-items:center; gap:14px; }
.fl-icon-badge{
    width:46px; height:46px; border-radius:12px;
    background:var(--fl-brand-soft); color:var(--fl-brand);
    display:flex; align-items:center; justify-content:center; font-size:19px;
}
.fl-toolbar-title h4{ margin:0; font-weight:700; font-size:1.28rem; letter-spacing:-0.01em; color:var(--fl-ink); }
.fl-toolbar-title span{ font-size:.82rem; color:var(--fl-ink-faint); }

.fl-toolbar-actions{ display:flex; align-items:center; gap:10px; flex-wrap:wrap; }

.fl-search{
    display:flex; align-items:center; gap:8px;
    background:var(--fl-surface); border:1px solid var(--fl-line);
    border-radius:10px; padding:0 12px; height:40px; min-width:240px;
    transition:border-color .15s ease, box-shadow .15s ease;
}
.fl-search:focus-within{ border-color:var(--fl-brand); box-shadow:0 0 0 3px rgba(58,87,232,0.12); }
.fl-search i{ color:var(--fl-ink-faint); font-size:.85rem; }
.fl-search input{
    border:none; outline:none; background:transparent; font-size:.88rem; width:100%; color:var(--fl-ink);
}

.fl-select{
    height:40px; border-radius:10px; border:1px solid var(--fl-line);
    background:var(--fl-surface); padding:0 12px; font-size:.85rem; color:var(--fl-ink-soft);
    transition:border-color .15s ease;
}
.fl-select:focus{ border-color:var(--fl-brand); outline:none; }

.fl-btn{
    height:40px; border-radius:10px; border:1px solid transparent;
    padding:0 16px; font-size:.86rem; font-weight:600; cursor:pointer;
    display:inline-flex; align-items:center; gap:8px; transition:filter .15s ease, background .15s ease;
    text-decoration:none;
}
.fl-btn-primary{ background:var(--fl-brand); color:#fff; }
.fl-btn-primary:hover{ filter:brightness(1.08); color:#fff; }
.fl-btn-outline{ background:var(--fl-surface); border-color:var(--fl-line); color:var(--fl-ink); }
.fl-btn-outline:hover{ background:#F8F9FB; }
.fl-btn-ghost{ background:transparent; color:var(--fl-ink-soft); }
.fl-btn-ghost:hover{ background:#F1F2F5; }
.fl-btn-danger{ background:var(--fl-danger-soft); color:var(--fl-danger); }
.fl-btn-danger:hover{ background:#F6D9D7; }

/* ---------- stat strip ---------- */
.fl-stats{
    display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:20px;
}
.fl-stat{
    background:var(--fl-surface); border:1px solid var(--fl-line); border-radius:var(--fl-radius);
    padding:16px 18px; display:flex; align-items:center; gap:14px;
    position:relative; overflow:hidden;
    transition:transform .15s ease, box-shadow .15s ease;
}
.fl-stat:hover{ transform:translateY(-2px); box-shadow:var(--fl-shadow); }
.fl-stat::before{ content:""; position:absolute; top:0; left:0; right:0; height:3px; background:var(--fl-ink-faint); }
.fl-stat-neutral::before{ background:#8b94a6; }
.fl-stat-ok::before{ background:var(--fl-teal); }
.fl-stat-bad::before{ background:var(--fl-danger); }
.fl-stat-warn::before{ background:var(--fl-amber); }

.fl-stat-icon{
    width:44px; height:44px; min-width:44px; border-radius:12px;
    display:flex; align-items:center; justify-content:center; font-size:17px;
    background:#eef0f5; color:#8b94a6;
}
.fl-stat-ok .fl-stat-icon{ background:var(--fl-teal-soft); color:var(--fl-teal); }
.fl-stat-bad .fl-stat-icon{ background:var(--fl-danger-soft); color:var(--fl-danger); }
.fl-stat-warn .fl-stat-icon{ background:var(--fl-amber-soft); color:var(--fl-amber); }

.fl-stat-num{ font-size:1.45rem; font-weight:800; letter-spacing:-0.02em; color:var(--fl-ink); display:inline-block; }
.fl-stat-num.fl-ok{ color:var(--fl-teal); }
.fl-stat-num.fl-bad{ color:var(--fl-danger); }
.fl-stat-num.fl-warn{ color:var(--fl-amber); }
.fl-stat-label{ display:block; font-size:.78rem; color:var(--fl-ink-faint); }

/* Skeleton de carga -- mismo patrón shimmer que el resto del sistema */
.fl-skeleton{
    min-width:44px; height:1.35rem; border-radius:6px;
    background:linear-gradient(90deg,#eef0f5 25%,#f7f8fb 37%,#eef0f5 63%);
    background-size:400% 100%; animation:fl-shimmer 1.4s ease infinite;
}
@keyframes fl-shimmer{ 0%{ background-position:100% 50%; } 100%{ background-position:0 50%; } }

/* ---------- grid de vehículos ---------- */
.fl-loading-strip{
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    padding:70px 0; color:var(--fl-ink-faint); gap:2px;
}
.fl-loading-strip .fl-spinner{ margin-bottom:8px; }

.fl-grid{
    display:grid; grid-template-columns:repeat(3, 1fr); gap:18px;
}
@media (max-width: 1199px){ .fl-grid{ grid-template-columns:repeat(2, 1fr); } }
@media (max-width: 640px){ .fl-grid{ grid-template-columns:1fr; } }

.fl-vehicle-card{
    background:var(--fl-surface); border:1px solid var(--fl-line); border-radius:var(--fl-radius);
    overflow:hidden; cursor:pointer; box-shadow:var(--fl-shadow);
    transition:transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    display:flex; flex-direction:column;
}
.fl-vehicle-card:hover{
    transform:translateY(-4px); border-color:#c9d2f5;
    box-shadow:0 16px 32px -14px rgba(58,87,232,0.22);
}

.fl-vc-photo{ position:relative; width:100%; height:150px; background:#eef0f5; overflow:hidden; }
.fl-vc-photo img{ width:100%; height:100%; object-fit:cover; display:block; transition:transform .3s ease; }
.fl-vehicle-card:hover .fl-vc-photo img{ transform:scale(1.05); }

.fl-vc-pill{
    position:absolute; top:10px; right:10px;
    display:inline-flex; align-items:center; gap:6px; padding:4px 10px; border-radius:99px;
    font-size:.7rem; font-weight:700; backdrop-filter:blur(2px);
}
.fl-pill-ok{ background:rgba(23,144,75,0.85); color:#fff; }
.fl-pill-bad{ background:rgba(217,54,74,0.88); color:#fff; }

.fl-vc-plate{
    position:absolute; bottom:10px; left:10px;
    background:rgba(16,24,40,0.72); color:#fff; font-weight:700; letter-spacing:.5px;
    font-size:.78rem; padding:3px 10px; border-radius:7px; font-variant-numeric:tabular-nums;
    backdrop-filter:blur(2px);
}

.fl-vc-body{ padding:14px 16px 16px; display:flex; flex-direction:column; gap:10px; flex:1; }
.fl-vc-name{ font-weight:700; font-size:.98rem; color:var(--fl-ink); line-height:1.2; }
.fl-vc-model{ font-size:.76rem; color:var(--fl-ink-faint); margin-top:1px; }
.fl-vc-serie{ font-size:.72rem; color:var(--fl-ink-faint); font-variant-numeric:tabular-nums; }

.fl-vc-info{ display:flex; flex-direction:column; gap:6px; font-size:.78rem; }
.fl-vc-info-row{ display:flex; align-items:center; gap:7px; color:var(--fl-ink-soft); }
.fl-vc-info-row i{ width:14px; color:var(--fl-brand); font-size:.75rem; text-align:center; flex-shrink:0; }
.fl-vc-info-row.fl-empty-info{ color:var(--fl-ink-faint); font-style:italic; }
.fl-vc-info-row.fl-empty-info i{ color:var(--fl-ink-faint); }

.fl-vc-footer{
    display:flex; align-items:center; justify-content:space-between;
    margin-top:auto; padding-top:10px; border-top:1px solid var(--fl-line);
}
.fl-vc-km{ font-size:.8rem; font-weight:700; color:var(--fl-ink); font-variant-numeric:tabular-nums; }
.fl-vc-km small{ font-weight:500; color:var(--fl-ink-faint); }
.fl-vc-open{
    width:28px; height:28px; border-radius:8px; border:1px solid var(--fl-line);
    background:#fff; color:var(--fl-ink-faint); display:flex; align-items:center; justify-content:center;
    transition:background .15s ease, color .15s ease;
}
.fl-vehicle-card:hover .fl-vc-open{ background:var(--fl-brand); color:#fff; border-color:var(--fl-brand); }

.fl-spinner{
    width:26px; height:26px; border-radius:50%; border:3px solid var(--fl-line); border-top-color:var(--fl-brand);
    margin:0 auto 10px; animation:fl-spin .7s linear infinite;
}
@keyframes fl-spin{ to{ transform:rotate(360deg); } }

.fl-empty{ text-align:center; padding:56px 20px; color:var(--fl-ink-faint); }
.fl-empty i{ font-size:1.8rem; margin-bottom:10px; color:var(--fl-line); }
.fl-empty h5{ color:var(--fl-ink-soft); font-size:.95rem; margin-bottom:4px; }
.fl-empty p{ font-size:.83rem; margin:0; }

/* ---------- drawer ---------- */
.fl-overlay{
    position:fixed; inset:0; background:rgba(16,24,40,.42); backdrop-filter:blur(1px);
    opacity:0; pointer-events:none; transition:opacity .2s ease; z-index:1040;
}
.fl-overlay.active{ opacity:1; pointer-events:auto; }

.fl-drawer{
    position:fixed; top:0; right:0; height:100%; width:460px; max-width:92vw;
    background:var(--fl-surface); box-shadow:-16px 0 40px rgba(16,24,40,.14);
    transform:translateX(100%); transition:transform .25s cubic-bezier(.4,0,.2,1);
    z-index:1050; display:flex; flex-direction:column;
}
.fl-drawer.active{ transform:translateX(0); }

.fl-drawer-header{
    display:flex; align-items:flex-start; justify-content:space-between;
    padding:20px 22px; border-bottom:1px solid var(--fl-line);
}
.fl-drawer-eyebrow{ font-size:.72rem; color:var(--fl-ink-faint); letter-spacing:.03em; }
.fl-drawer-title h5{ margin:2px 0 0; font-size:1.08rem; font-weight:700; color:var(--fl-ink); }
.fl-icon-btn{
    width:32px; height:32px; border-radius:8px; border:none; background:#F1F2F5; color:var(--fl-ink-soft);
    display:flex; align-items:center; justify-content:center;
}

.fl-drawer-body{ padding:20px 22px; overflow-y:auto; flex:1; }
.fl-drawer-photo{
    width:100%; height:170px; border-radius:12px; overflow:hidden; background:#EEF0F3; margin-bottom:18px;
    border:1px solid var(--fl-line);
}
.fl-drawer-photo img{ width:100%; height:100%; object-fit:cover; }

.fl-field-grid{ display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.fl-field{ display:flex; flex-direction:column; gap:5px; }
.fl-field-wide{ grid-column:1 / -1; }
.fl-field label{ font-size:.72rem; font-weight:600; color:var(--fl-ink-faint); text-transform:uppercase; letter-spacing:.03em; }
.fl-field input, .fl-field select{
    height:38px; border-radius:9px; border:1px solid var(--fl-line); padding:0 11px;
    font-size:.86rem; color:var(--fl-ink); background:#FBFBFC;
    transition:border-color .15s ease, box-shadow .15s ease;
}
.fl-field input:focus, .fl-field select:focus{
    outline:none; border-color:var(--fl-brand); box-shadow:0 0 0 3px rgba(58,87,232,0.12);
}
.fl-field input:disabled, .fl-field select:disabled{ color:var(--fl-ink-soft); background:#F6F7F9; }
.fl-field input:not(:disabled), .fl-field select:not(:disabled){
    background:#fff; border-color:#CBD3E1;
}
.fl-inline-field{ display:flex; gap:8px; }
.fl-inline-field select{ flex:1; }
.fl-mini-btn{
    width:38px; height:38px; border-radius:9px; border:1px solid var(--fl-line); background:#fff;
    color:var(--fl-ink-faint); flex-shrink:0;
}
.fl-mini-danger{ color:var(--fl-danger); border-color:var(--fl-danger-soft); background:var(--fl-danger-soft); }
.fl-mini-danger:disabled{ opacity:.4; }

.fl-divider{ height:1px; background:var(--fl-line); margin:18px 0; }

.fl-drawer-footer{
    display:flex; align-items:center; justify-content:space-between; gap:10px;
    padding:16px 22px; border-top:1px solid var(--fl-line);
}
.fl-drawer-footer-right{ display:flex; gap:8px; }

/* ---------- confirm modal ---------- */
.fl-modal-overlay{
    position:fixed; inset:0; background:rgba(16,24,40,.5); display:none;
    align-items:center; justify-content:center; z-index:1100;
}
.fl-modal-overlay.active{ display:flex; }
.fl-modal{
    background:#fff; border-radius:16px; padding:26px 28px; width:360px; max-width:90vw;
    text-align:center; box-shadow:0 24px 48px rgba(16,24,40,.24);
}
.fl-modal-icon{
    width:48px; height:48px; border-radius:50%; background:var(--fl-danger-soft); color:var(--fl-danger);
    display:flex; align-items:center; justify-content:center; margin:0 auto 14px; font-size:1.1rem;
}
.fl-modal h5{ font-size:1.02rem; font-weight:700; margin-bottom:6px; color:var(--fl-ink); }
.fl-modal p{ font-size:.85rem; color:var(--fl-ink-faint); margin-bottom:20px; }
.fl-modal-actions{ display:flex; gap:10px; justify-content:center; }
.fl-modal-actions .fl-btn{ flex:1; justify-content:center; }

/* ---------- toasts ---------- */
.fl-toast-stack{
    position:fixed; bottom:22px; right:22px; z-index:1200; display:flex; flex-direction:column; gap:10px;
}
.fl-toast{
    background:#101828; color:#fff; padding:12px 16px; border-radius:10px; font-size:.85rem;
    display:flex; align-items:center; gap:10px; box-shadow:0 12px 24px rgba(16,24,40,.24);
    animation:fl-toast-in .2s ease;
    min-width:220px;
}
.fl-toast i{ font-size:.95rem; }
.fl-toast.fl-toast-ok i{ color:#4ADE80; }
.fl-toast.fl-toast-err i{ color:#F87171; }
@keyframes fl-toast-in{ from{ opacity:0; transform:translateY(8px); } to{ opacity:1; transform:translateY(0); } }

/* ---------- responsive ---------- */
@media (max-width: 991px){
    .fl-stats{ grid-template-columns:repeat(2,1fr); }
    .fl-field-grid{ grid-template-columns:1fr; }
}
@media (max-width: 640px){
    .fl-toolbar-actions{ width:100%; }
    .fl-search{ min-width:0; flex:1; }
}
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
(function () {
    const state = {
        vehiculos: [],
        obras: [],
        choferes: [],
        propietarios: [],
        activo: null,
        editando: false,
    };

    const $grid       = $('#fl-grid');
    const $empty      = $('#fl-empty');
    const $search      = $('#fl-search-input');
    const $filtroObra  = $('#fl-filter-obra');
    const $filtroSeguro= $('#fl-filter-seguro');

    document.addEventListener('DOMContentLoaded', init);

    function init() {
        cargarFlota();
        cargarCatalogos();
        bindEvents();
    }

    function cargarFlota() {
        $('#fl-loading-strip').show();
        fetch('/vehiculos/recuperarvehiculos')
            .then(r => r.json())
            .then(res => {
                $('#fl-loading-strip').hide();
                if (res.success) {
                    state.vehiculos = res.data || [];
                    poblarFiltroObras();
                    renderTabla();
                    renderStats();
                } else {
                    toast('No se pudo cargar la flota', 'err');
                    $empty.show();
                }
            })
            .catch(() => {
                $('#fl-loading-strip').hide();
                toast('Error de conexión al cargar la flota', 'err');
                $empty.show();
            });
    }

    function cargarCatalogos() {
        fetch('/obras/listar').then(r => r.json()).then(res => {
            state.obras = res.data || res || [];
            poblarSelect($('#dw-obra'), state.obras, 'id', 'nombre_obra', 'Sin obra asignada');
        }).catch(() => {});

        fetch('/choferes/listar').then(r => r.json()).then(res => {
            state.choferes = res.data || res || [];
            poblarSelect($('#dw-chofer'), state.choferes, 'id', 'nombre_completo', 'Sin chofer asignado');
        }).catch(() => {});

        fetch('/propietarios/listar').then(r => r.json()).then(res => {
            state.propietarios = res.data || res || [];
            poblarSelect($('#dw-propietario'), state.propietarios, 'id', 'nombre_completo', 'Pendiente');
        }).catch(() => {});
    }

    function poblarSelect($select, items, valueKey, labelKey, emptyLabel) {
        $select.empty();
        $select.append(`<option value="">${emptyLabel}</option>`);
        items.forEach(it => {
            $select.append(`<option value="${it[valueKey]}">${it[labelKey] ?? ''}</option>`);
        });
    }

    function poblarFiltroObras() {
        const obras = [...new Set(state.vehiculos.map(v => v.nombre_obra).filter(Boolean))];
        $filtroObra.find('option:not(:first)').remove();
        obras.forEach(o => $filtroObra.append(`<option value="${o}">${o}</option>`));
    }

    function filtrar() {
        const q = $search.val().trim().toLowerCase();
        const obra = $filtroObra.val();
        const seguro = $filtroSeguro.val();

        return state.vehiculos.filter(v => {
            const texto = `${v.marca} ${v.linea} ${v.placas} ${v.serie} ${v.nombre_propietario ?? ''}`.toLowerCase();
            const matchTexto = !q || texto.includes(q);
            const matchObra = !obra || v.nombre_obra === obra;
            const matchSeguro = seguro === '' || String(v.estatus_seguro) === seguro;
            return matchTexto && matchObra && matchSeguro;
        });
    }

    function renderTabla() {
        const lista = filtrar();

        if (lista.length === 0) {
            $grid.empty();
            $empty.show();
            return;
        }
        $empty.hide();

        let html = '';
        lista.forEach(v => {
            const img = resolverFotoVehiculo(v);
            const asegurado = String(v.estatus_seguro) === '1';
            const pillClase = asegurado ? 'fl-pill-ok' : 'fl-pill-bad';
            const pillTexto = asegurado ? 'Asegurado' : 'Sin seguro';

            const filaObra = v.nombre_obra
                ? `<div class="fl-vc-info-row"><i class="fa-solid fa-location-dot"></i>${escapeHtml(v.nombre_obra)}</div>`
                : `<div class="fl-vc-info-row fl-empty-info"><i class="fa-solid fa-location-dot"></i>Sin obra asignada</div>`;

            const filaChofer = v.nombre_chofer
                ? `<div class="fl-vc-info-row"><i class="fa-solid fa-id-card"></i>${escapeHtml(v.nombre_chofer)}</div>`
                : `<div class="fl-vc-info-row fl-empty-info"><i class="fa-solid fa-id-card"></i>Sin chofer asignado</div>`;

            const filaTitular = `<div class="fl-vc-info-row"><i class="fa-solid fa-user-tie"></i>${escapeHtml(v.nombre_propietario ?? 'Pendiente')}</div>`;

            html += `
            <div class="fl-vehicle-card" data-id="${v.id}">
                <div class="fl-vc-photo">
                    <img src="${img}" onerror="this.src='${placeholderImg()}'">
                    <span class="fl-vc-pill ${pillClase}"><i class="fa-solid fa-shield-halved"></i>${pillTexto}</span>
                    <span class="fl-vc-plate">${escapeHtml(v.placas ?? 'SIN PLACA')}</span>
                </div>
                <div class="fl-vc-body">
                    <div>
                        <div class="fl-vc-name">${escapeHtml(v.marca ?? '')} ${escapeHtml(v.linea ?? '')}</div>
                        <div class="fl-vc-model">Modelo ${escapeHtml(v.modelo ?? '—')}</div>
                        <div class="fl-vc-serie">${escapeHtml(v.serie ?? '—')}</div>
                    </div>
                    <div class="fl-vc-info">
                        ${filaObra}
                        ${filaTitular}
                        ${filaChofer}
                    </div>
                    <div class="fl-vc-footer">
                        <span class="fl-vc-km"><i class="fa-solid fa-gauge-high me-1"></i>${new Intl.NumberFormat().format(v.km_actual ?? 0)} <small>km</small></span>
                        <span class="fl-vc-open fl-open-btn"><i class="fa-solid fa-chevron-right"></i></span>
                    </div>
                </div>
            </div>`;
        });

        $grid.html(html);
    }

    function renderStats() {
        const total = state.vehiculos.length;
        const asegurados = state.vehiculos.filter(v => String(v.estatus_seguro) === '1').length;
        $('.fl-skeleton').removeClass('fl-skeleton');
        $('#stat-total').text(total);
        $('#stat-asegurados').text(asegurados);
        $('#stat-sinseguro').text(total - asegurados);
        $('#stat-sinobra').text(state.vehiculos.filter(v => !v.nombre_obra).length);
    }

    function placeholderImg() {
        return 'data:image/svg+xml;utf8,' + encodeURIComponent(
            `<svg xmlns="http://www.w3.org/2000/svg" width="120" height="90"><rect width="120" height="90" fill="#EEF0F3"/></svg>`
        );
    }


    function resolverFotoVehiculo(v) {
        return v && v.foto_vehiculo ? `/storage/${v.foto_vehiculo}` : placeholderImg();
    }

    function escapeHtml(str) {
        return String(str ?? '').replace(/[&<>"']/g, s => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
        }[s]));
    }

    function bindEvents() {
        $search.on('input', renderTabla);
        $filtroObra.on('change', renderTabla);
        $filtroSeguro.on('change', renderTabla);

        $grid.on('click', '.fl-vehicle-card', function () {
            const id = $(this).data('id');
            abrirDrawer(id);
        });

        $('#dw-close, #fl-overlay').on('click', cerrarDrawer);

        $('#dw-editar').on('click', () => toggleEdicion(true));
        $('#dw-cancelar').on('click', () => { toggleEdicion(false); abrirDrawer(state.activo.id); });
        $('#dw-guardar').on('click', guardarCambios);

        $('#dw-eliminar').on('click', () => confirmar(
            `Se eliminará ${state.activo.marca} ${state.activo.linea} (${state.activo.placas}) de forma permanente.`,
            eliminarVehiculo
        ));

        $('#dw-quitar-obra').on('click', () => confirmar(
            'El vehículo dejará de estar asignado a su obra actual.',
            quitarObra,
            'Quitar obra'
        ));
        $('#dw-quitar-chofer').on('click', () => confirmar(
            'El vehículo dejará de estar asignado a su chofer actual.',
            quitarChofer,
            'Quitar chofer'
        ));

        $('#fl-confirm-cancel').on('click', cerrarConfirmacion);
    }

    function abrirDrawer(id) {
        const v = state.vehiculos.find(x => x.id == id);
        if (!v) return;
        state.activo = v;
        toggleEdicion(false);

        $('#dw-id').val(v.id);
        $('#dw-serie').text(v.serie ?? '—');
        $('#dw-nombre').text(`${v.marca ?? ''} ${v.linea ?? ''}`.trim() || 'Vehículo');
        $('#dw-foto').attr('src', resolverFotoVehiculo(v));

        $('#dw-marca').val(v.marca ?? '');
        $('#dw-linea').val(v.linea ?? '');
        $('#dw-modelo').val(v.modelo ?? '');
        $('#dw-placas').val(v.placas ?? '');
        $('#dw-serie-input').val(v.serie ?? '');
        $('#dw-tarjeta').val(v.tarjeta_circulacion ?? '');
        $('#dw-km').val(v.km_actual ?? 0);
        $('#dw-efecticard').val(v.efecticard ?? '');

        $('#dw-obra').val(v.obra_id ?? '');
        $('#dw-chofer').val(v.chofer_id ?? '');
        $('#dw-propietario').val(v.propietario_id ?? '');

        $('#dw-estatus-seguro').val(String(v.estatus_seguro ?? '0'));
        $('#dw-compania').val(v.compania_seguro ?? '');
        $('#dw-fecha-inicio').val(v.fecha_inicio_seguro ?? '');
        $('#dw-fecha-fin').val(v.fecha_fin_seguro ?? '');

        $('#dw-quitar-obra').prop('disabled', !v.obra_id);
        $('#dw-quitar-chofer').prop('disabled', !v.chofer_id);

        $('#fl-overlay').addClass('active');
        $('#fl-drawer').addClass('active');
    }

    function cerrarDrawer() {
        $('#fl-overlay').removeClass('active');
        $('#fl-drawer').removeClass('active');
        toggleEdicion(false);
    }

    function toggleEdicion(on) {
        state.editando = on;
        $('#dw-form input, #dw-form select').not('#dw-id').prop('disabled', !on);
        $('#dw-editar').toggle(!on);
        $('#dw-guardar, #dw-cancelar').toggle(on);
    }

    function guardarCambios() {
        const id = $('#dw-id').val();
        const payload = {
            marca: $('#dw-marca').val(),
            linea: $('#dw-linea').val(),
            modelo: $('#dw-modelo').val(),
            placas: $('#dw-placas').val(),
            serie: $('#dw-serie-input').val(),
            tarjeta_circulacion: $('#dw-tarjeta').val(),
            km_actual: $('#dw-km').val(),
            efecticard: $('#dw-efecticard').val(),
            obra_id: $('#dw-obra').val() || null,
            chofer_id: $('#dw-chofer').val() || null,
            propietario_id: $('#dw-propietario').val() || null,
            estatus_seguro: $('#dw-estatus-seguro').val(),
            compania_seguro: $('#dw-compania').val(),
            fecha_inicio_seguro: $('#dw-fecha-inicio').val(),
            fecha_fin_seguro: $('#dw-fecha-fin').val(),
        };

        peticion(`/vehiculos/actualizar/${id}`, 'PUT', payload)
            .then(() => {
                toast('Vehículo actualizado correctamente', 'ok');
                cerrarDrawer();
                cargarFlota();
            })
            .catch(() => toast('No se pudo guardar el vehículo', 'err'));
    }

    function eliminarVehiculo() {
        const id = state.activo.id;
        peticion(`/vehiculos/eliminar/${id}`, 'DELETE')
            .then(() => {
                toast('Vehículo eliminado', 'ok');
                cerrarConfirmacion();
                cerrarDrawer();
                cargarFlota();
            })
            .catch(() => toast('No se pudo eliminar el vehículo', 'err'));
    }

    function quitarObra() {
        const id = state.activo.id;
        peticion(`/vehiculos/quitar-obra/${id}`, 'POST')
            .then(() => {
                toast('Obra removida del vehículo', 'ok');
                cerrarConfirmacion();
                cargarFlota();
                cerrarDrawer();
            })
            .catch(() => toast('No se pudo quitar la obra', 'err'));
    }

    function quitarChofer() {
        const id = state.activo.id;
        peticion(`/vehiculos/quitar-chofer/${id}`, 'POST')
            .then(() => {
                toast('Chofer removido del vehículo', 'ok');
                cerrarConfirmacion();
                cargarFlota();
                cerrarDrawer();
            })
            .catch(() => toast('No se pudo quitar el chofer', 'err'));
    }

    function peticion(url, method, body) {
        return fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '',
            },
            body: body ? JSON.stringify(body) : undefined,
        }).then(r => {
            if (!r.ok) throw new Error('Request failed');
            return r.json();
        });
    }

    let confirmCallback = null;

    function confirmar(texto, callback, titulo) {
        $('#fl-confirm-text').text(texto);
        $('.fl-modal h5').text(titulo ?? 'Eliminar vehículo');
        confirmCallback = callback;
        $('#fl-confirm-overlay').addClass('active');
    }

    function cerrarConfirmacion() {
        $('#fl-confirm-overlay').removeClass('active');
        confirmCallback = null;
    }

    $('#fl-confirm-ok').on('click', function () {
        if (confirmCallback) confirmCallback();
    });

    function toast(msg, tipo) {
        const icon = tipo === 'ok' ? 'fa-circle-check' : 'fa-circle-exclamation';
        const $t = $(`<div class="fl-toast fl-toast-${tipo}"><i class="fa-solid ${icon}"></i>${msg}</div>`);
        $('#fl-toast-stack').append($t);
        setTimeout(() => $t.fadeOut(200, () => $t.remove()), 3200);
    }
})();
</script>
@endsection