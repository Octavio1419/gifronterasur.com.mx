@extends('layout.index')
@section('content')
<main class="main-content">
    <div class="position-relative iq-banner iq-banner-exec">
        <div class="iq-navbar-header" style="height: 230px;">
            <div class="container-fluid iq-container">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="iq-header-title">
                                <span class="badge bg-white text-dark fw-semibold px-3 py-2 rounded-pill mb-2 d-inline-block">
                                    <i class="fas fa-chart-line me-1 text-primary"></i> Panel de control &middot; De ventas
                                </span>
                                <h1 class="display-5 text-white mb-0">Hola, {{ Auth::user()->nombre }} {{ Auth::user()->apellido_p }}</h1>
                                <p class="text-white opacity-75 mb-0">Ventas al momento...</p>
                            </div>
                            <div class="bg-white p-2 rounded shadow-sm">
                                <label for="reporte_seis_meses" class="form-label small mb-1 text-dark d-block">Consultar periodo:</label>
                                <select name="reporte_seis_meses" id="reporte_seis_meses" class="form-select form-select-sm border-0"></select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="iq-header-img" style="opacity: 0.35;">
                <img src="{{ asset('img/configuraciones/logo_frontera.jpeg') }}" alt="header" class="img-fluid w-100 h-100 object-cover">
            </div>
        </div>
    </div>

    <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm exec-strip" data-aos="fade-up">
                    <div class="card-body py-4">
                        <div class="row g-4" id="kpi_ejecutivo_strip">
                            <div class="col-6 col-lg-3 exec-kpi-item">
                                <div class="exec-kpi-icon bg-primary-subtle text-primary"><i class="fas fa-sack-dollar"></i></div>
                                <div>
                                    <span class="exec-kpi-label">Ingreso combinado del mes</span>
                                    <h3 class="exec-kpi-value kpi-skeleton" id="kpi_ingreso_total">&nbsp;</h3>
                                    <span class="exec-kpi-sub text-muted">Boulevard + Centro</span>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3 exec-kpi-item">
                                <div class="exec-kpi-icon bg-success-subtle text-success"><i class="fas fa-bullseye"></i></div>
                                <div class="w-100">
                                    <span class="exec-kpi-label">Meta global alcanzada</span>
                                    <h3 class="exec-kpi-value kpi-skeleton" id="kpi_meta_global">&nbsp;</h3>
                                    <div class="progress exec-kpi-progress" role="progressbar">
                                        <div class="progress-bar" id="kpi_meta_progress_bar" style="width:0%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3 exec-kpi-item">
                                <div class="exec-kpi-icon bg-info-subtle text-info"><i class="fas fa-file-invoice-dollar"></i></div>
                                <div>
                                    <span class="exec-kpi-label">Cotizaciones foliadas</span>
                                    <h3 class="exec-kpi-value kpi-skeleton" id="kpi_folios">&nbsp;</h3>
                                    <span class="exec-kpi-sub text-muted" id="kpi_folios_pendientes">Pendientes: $0.00</span>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3 exec-kpi-item">
                                <div class="exec-kpi-icon bg-warning-subtle text-warning"><i class="fas fa-piggy-bank"></i></div>
                                <div>
                                    <span class="exec-kpi-label">Ahorro por gestión</span>
                                    <h3 class="exec-kpi-value kpi-skeleton" id="kpi_ahorro">&nbsp;</h3>
                                    <span class="exec-kpi-sub text-muted">Vs. compra sin negociar</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm" id="tarjeta_proyeccion" data-aos="fade-up">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
                            <div>
                                <h5 class="card-title mb-0"><i class="fas fa-rocket me-2 text-primary"></i>Proyección de cierre de mes</h5>
                                <small class="text-muted">A este ritmo, así vas a cerrar el mes</small>
                            </div>
                            <span class="badge px-3 py-2 rounded-pill bg-secondary" id="proyeccion_badge_estado">Calculando…</span>
                        </div>
                        <div id="proyeccion_contenido">
                            <div class="kpi-skeleton" style="width: 100%; height: 60px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-4">
                <h6 class="text-muted text-uppercase mb-2" style="letter-spacing:1px; font-size:11px;">Desempeño por vendedor</h6>
                <div class="overflow-hidden d-slider1">
                    <ul class="p-0 m-0 mb-2 swiper-wrapper" id="banner_vendedor_mes"></ul>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="card shadow-sm" data-aos="fade-up" data-aos-delay="800">
                            <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom">
                                <div class="header-title">
                                    <h5 class="card-title mb-0">Gráfica de ventas</h5>
                                    <small class="text-muted">Comparativo diario por sucursal</small>
                                </div>
                            </div>
                            <div class="card-body">
                                <small class="text-muted d-block mb-2" id="grafica_ventas_aviso" style="display:none;">
                                    <i class="fas fa-info-circle me-1"></i><span></span>
                                </small>
                                <div id="d-main" style="min-height: 300px;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-4">
                        <div class="card shadow-sm" data-aos="fade-up" data-aos-delay="900">
                            <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom">
                                <div class="header-title">
                                    <h5 class="card-title mb-0">Más vendidos</h5>
                                </div>
                                <div class="dropdown">
                                    <a href="#" class="btn btn-outline-secondary btn-sm dropdown-toggle" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                         HERRALUM BOULEVARD
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" id="recu_top_5"></ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-7">
                                        <div id="myChart" class="myChart"></div>
                                    </div>
                                    <div class="col-md-5" id="nombre_top_5"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-4">
                        <div class="card shadow-sm border-0 cotizaciones-card" data-aos="fade-up" data-aos-delay="600">
                            <div class="card-header d-flex justify-content-between align-items-center bg-white py-3 flex-wrap gap-2">
                                <div class="header-title">
                                    <h5 class="card-title mb-0 fw-bold text-primary">
                                        <i class="fas fa-file-invoice-dollar me-2"></i>Control de Cotizaciones
                                    </h5>
                                    <div id="conteo_remisiones" class="mt-2 d-flex align-items-center gap-3 flex-wrap"></div>
                                </div>
                                <span class="badge bg-soft-warning text-warning px-3 py-2 rounded-pill">
                                    <i class="fas fa-clock me-1"></i> Esperando Autorización
                                </span>
                            </div>
                            <div class="card-body p-0">
                                <div id="alerta_venta_perdida" class="px-4 py-2 bg-soft-danger text-danger small border-bottom" style="display:none;">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Atención:</strong> Se estima una pérdida de <span id="monto_venta_perdida">$0.00</span> este mes por falta de stock en esta sucursal.
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0 tabla-cotizaciones">
                                        <thead class="bg-light">
                                            <tr class="text-muted text-uppercase small" id="filas_remisiones"></tr>
                                        </thead>
                                        <tbody id="datos_remisiones" class="border-top-0"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer bg-light-subtle border-top py-3">
                                <div class="d-flex justify-content-between align-items-center px-2 flex-wrap gap-3">
                                    <div class="d-flex align-items-center gap-4 flex-wrap" id="desglose_sucursales_footer"></div>
                                    <div class="d-flex align-items-center gap-4">
                                        <div class="text-end text-muted small">
                                            <span class="d-block text-uppercase" style="letter-spacing: 1px; font-size: 10px;">Inversión Autorizada</span>
                                            <h5 class="mb-0 fw-bold text-dark" id="total_inversion_tabla">$0.00</h5>
                                        </div>
                                        <div class="text-end text-muted small border-start ps-4">
                                            <span class="d-block text-uppercase" style="letter-spacing: 1px; font-size: 10px;">Ahorro por Gestión</span>
                                            <h5 class="mb-0 fw-bold text-success" id="total_ahorro_tabla">$0.00</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="card shadow-sm branch-card branch-card-primary" data-aos="fade-up" data-aos-delay="500">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h4 class="mb-0 text-primary text-uppercase" id="nombre_tienda_teran">HERRALUM BOULEVARD</h4>
                                    <span class="branch-dot bg-primary"></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div id="total_mensual" class="text-center w-50 border-end"></div>
                                    <div id="meta_teran" class="text-center w-50"></div>
                                </div>
                                <div class="progress branch-progress mt-3" role="progressbar">
                                    <div class="progress-bar bg-primary" id="progress_teran" style="width:0%"></div>
                                </div>
                                <hr>
                                <div class="mt-2 small" id="mes_actual">
                                    <svg class="me-1" width="18" height="18" viewBox="0 0 24 24" fill="#17904b">
                                        <path d="M13,20H11V8L5.5,13.5L4.08,12.08L12,4.16L19.92,12.08L18.5,13.5L13,8V20Z" />
                                    </svg>
                                </div>
                                <div class="mt-3 list-group list-group-flush" id="baner_tipo_pago"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-4">
                        <div class="card shadow-sm branch-card branch-card-success" data-aos="fade-up" data-aos-delay="600">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h4 class="mb-0 text-success text-uppercase">HERRALUM CENTRO</h4>
                                    <span class="branch-dot bg-success"></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div id="total_mensual_5ta" class="text-center w-50 border-end"></div>
                                    <div id="meta_5ta_norte" class="text-center w-50"></div>
                                </div>
                                <div class="progress branch-progress mt-3" role="progressbar">
                                    <div class="progress-bar bg-success" id="progress_5ta" style="width:0%"></div>
                                </div>
                                <hr>
                                <div class="mt-2 small" id="mes_actual_5ta">
                                    <svg class="me-1" width="18" height="18" viewBox="0 0 24 24" fill="#17904b">
                                        <path d="M13,20H11V8L5.5,13.5L4.08,12.08L12,4.16L19.92,12.08L18.5,13.5L13,8V20Z" />
                                    </svg>
                                </div>
                                <div class="mt-3 list-group list-group-flush" id="baner_tipo_pago_5ta"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>

.d-slider1 { width: 100%; height: auto; touch-action: pan-y; user-select: none; }
.swiper-slide { height: auto !important; }

.bg-soft-primary { background-color: rgba(58, 87, 232, 0.15) !important; }
.bg-soft-success { background-color: rgba(23, 144, 75, 0.15) !important; }
.bg-soft-warning { background-color: rgba(255, 193, 7, 0.15) !important; }
.bg-soft-danger  { background-color: rgba(242, 109, 125, 0.15) !important; }

.table-success-light { background-color: rgba(23, 144, 75, 0.05) !important; transition: background-color 0.3s ease; }
.table-success-light:hover { background-color: rgba(23, 144, 75, 0.1) !important; }

.text-xs { font-size: 0.75rem; }

.iq-banner-exec .iq-navbar-header {
    background: linear-gradient(120deg, #0b1f4d 0%, #16305f 55%, #1e3a70 100%);
}

.exec-strip { border-radius: 16px; }
.exec-kpi-item { display: flex; align-items: flex-start; gap: 14px; }
.exec-kpi-icon {
    width: 46px; height: 46px; min-width: 46px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center; font-size: 18px;
}
.exec-kpi-label {
    display: block; font-size: 11px; font-weight: 600; text-transform: uppercase;
    letter-spacing: .6px; color: #8891a5; margin-bottom: 2px;
}
.exec-kpi-value { font-size: 1.55rem; font-weight: 800; color: #16233f; margin: 0; line-height: 1.15; }
.exec-kpi-sub { font-size: 11.5px; }
.exec-kpi-progress { height: 6px; border-radius: 6px; background: #eef0f5; margin-top: 6px; }
.exec-kpi-progress .progress-bar { border-radius: 6px; background: linear-gradient(90deg,#17904b,#1fbf68); transition: width .6s ease; }

.bg-primary-subtle { background-color: rgba(58,87,232,0.12); }
.bg-success-subtle  { background-color: rgba(23,144,75,0.12); }
.bg-info-subtle     { background-color: rgba(75,199,210,0.15); }
.bg-warning-subtle  { background-color: rgba(255,193,7,0.18); }

.branch-card { border-radius: 14px; border-left: 5px solid transparent; }
.branch-card-primary { border-left-color: #3a57e8; }
.branch-card-success { border-left-color: #17904b; }
.branch-dot { width: 10px; height: 10px; border-radius: 50%; margin-top: 6px; }
.branch-progress { height: 8px; border-radius: 8px; background: #eef0f5; }
.branch-progress .progress-bar { border-radius: 8px; transition: width .6s ease; }

.kpi-delta { font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 20px; display: inline-flex; align-items: center; gap: 3px; }
.kpi-delta.up { background: rgba(23,144,75,0.12); color: #17904b; }
.kpi-delta.down { background: rgba(242,109,125,0.15); color: #d9364a; }

@media (max-width: 767px) {
    .exec-kpi-value { font-size: 1.25rem; }
}

.kpi-skeleton {
    display: inline-block;
    min-width: 90px;
    height: 1.55rem;
    border-radius: 6px;
    background: linear-gradient(90deg, #eef0f5 25%, #f7f8fb 37%, #eef0f5 63%);
    background-size: 400% 100%;
    animation: kpi-shimmer 1.4s ease infinite;
}
@keyframes kpi-shimmer {
    0% { background-position: 100% 50%; }
    100% { background-position: 0 50%; }
}
.card { transition: transform .18s ease, box-shadow .18s ease; }
.exec-kpi-item .card,
.card.shadow-sm:hover,
.branch-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(22, 35, 63, 0.08) !important;
}

.exec-kpi-item { position: relative; padding-top: 4px; }
.exec-kpi-item::before {
    content: "";
    position: absolute;
    top: -16px; left: 0; right: 12px;
    height: 3px; border-radius: 3px;
}
.exec-kpi-item:nth-child(1)::before { background: #3a57e8; }
.exec-kpi-item:nth-child(2)::before { background: #17904b; }
.exec-kpi-item:nth-child(3)::before { background: #4bc7d2; }
.exec-kpi-item:nth-child(4)::before { background: #ffc107; }

.vendedor-card { border-radius: 12px; border: 1px solid #eef0f5; }
.vendedor-avatar {
    width: 42px; height: 42px; min-width: 42px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 15px; color: #fff;
    background: linear-gradient(135deg, #3a57e8, #4bc7d2);
    position: relative;
}
.vendedor-avatar.top-vendedor { background: linear-gradient(135deg, #f0a500, #ffc107); }
.vendedor-avatar.bajo-vendedor { background: linear-gradient(135deg, #d9364a, #f26d7d); }
.vendedor-corona {
    position: absolute; top: -10px; right: -6px; font-size: 14px; color: #ffc107;
    filter: drop-shadow(0 1px 1px rgba(0,0,0,0.25));
}
.vendedor-rank-badge {
    font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px;
    padding: 1px 7px; border-radius: 20px;
}
.vendedor-rank-badge.top { background: rgba(255,193,7,0.18); color: #a6740a; }
.vendedor-rank-badge.normal { background: rgba(58,87,232,0.1); color: #3a57e8; }
.vendedor-rank-badge.bajo { background: rgba(242,109,125,0.15); color: #d9364a; }

.d-slider1 .swiper-button-next,
.d-slider1 .swiper-button-prev { display: none; }
.d-slider1.swiper-initialized .swiper-button-next,
.d-slider1.swiper-initialized .swiper-button-prev { display: flex; }

.exec-kpi-value.sin-meta { font-size: 1rem; color: #8891a5; font-weight: 600; }

.cotizaciones-card .table-responsive { scrollbar-width: thin; }
.cotizaciones-card .table-responsive::-webkit-scrollbar { height: 6px; }
.cotizaciones-card .table-responsive::-webkit-scrollbar-thumb {
    background: #d7dbe6; border-radius: 6px;
}
.tabla-cotizaciones thead th {
    font-size: 10.5px; font-weight: 700; letter-spacing: .6px;
    padding: 12px 16px; border-bottom: 1px solid #edeff5;
}
.tabla-cotizaciones tbody td { padding: 14px 16px; border-color: #f2f3f8; }
.tabla-cotizaciones tbody tr:hover { background: #f8f9fc; }

.folio-pill {
    font-size: 10px; font-weight: 700; letter-spacing: .4px;
    padding: 2px 8px; border-radius: 6px; display: inline-block;
}
.folio-pill.con-folio { background: rgba(58,87,232,0.1); color: #3a57e8; }
.folio-pill.sin-folio { background: #f1f2f6; color: #8891a5; }

.bg-light-subtle { background-color: #fafbfd; }

.d-slider1 .swiper-wrapper {
    display: flex !important;
    flex-wrap: nowrap !important;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: 16px;
    overflow-x: auto;
    scroll-snap-type: x proximity;
}
.d-slider1 .swiper-wrapper .swiper-slide {
    flex: 0 0 auto !important;
    width: 260px;
    scroll-snap-align: start;
}
@media (max-width: 575px) {
    .d-slider1 .swiper-wrapper .swiper-slide { width: 220px; }
}

.d-slider1.swiper-initialized .swiper-wrapper {
    overflow-x: visible;
    gap: 0;
}

.top5-bar-track {
    width: 100%; height: 6px; border-radius: 6px; background: #eef0f5; overflow: hidden;
}
.top5-bar-fill {
    height: 100%; border-radius: 6px; transition: width .6s ease;
}
</style>

<script>
    function asegurarSwiper(callback) {
        const necesitaCSS = !document.querySelector('link[data-swiper-css]');
        const necesitaJS = typeof Swiper === 'undefined';

        let cssListo = !necesitaCSS;
        let jsListo = !necesitaJS;

        function intentarContinuar() {
            if (cssListo && jsListo) callback();
        }

        if (necesitaCSS) {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css';
            link.setAttribute('data-swiper-css', 'true');
            link.onload = () => { cssListo = true; intentarContinuar(); };
            link.onerror = () => { cssListo = true; intentarContinuar(); }; 
            document.head.appendChild(link);
        }

        if (necesitaJS) {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js';
            script.onload = () => { jsListo = true; intentarContinuar(); };
            script.onerror = () => console.error('No se pudo cargar Swiper desde CDN.');
            document.head.appendChild(script);
        }

        intentarContinuar();
    }
</script>
<script>
    const meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
    const mesesNombres = meses;

    let periodoGlobal = 'actual';

    let sumaTotalTeranGlobal = 0;
    let sumaTotal5taGlobal = 0;
    let metaTeranGlobal = 0;
    let meta5taGlobal = 0;

    function formataMoneda(cantidad) {
        return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(cantidad || 0);
    }

    function animarNumero(idElemento, valorFinal, formatoFn, duracionMs = 700) {
        const el = document.getElementById(idElemento);
        if (!el) return;
        el.classList.remove('kpi-skeleton');

        const valorInicial = parseFloat(el.dataset.valorActual) || 0;
        const inicioTiempo = performance.now();

        function frame(ahora) {
            const progreso = Math.min((ahora - inicioTiempo) / duracionMs, 1);
            const avance = 1 - Math.pow(1 - progreso, 3); // ease-out
            const valorFrame = valorInicial + (valorFinal - valorInicial) * avance;
            el.innerText = formatoFn(valorFrame);
            if (progreso < 1) {
                requestAnimationFrame(frame);
            } else {
                el.dataset.valorActual = valorFinal;
            }
        }
        requestAnimationFrame(frame);
    }

    function actualizarMesEnElemento(idElemento) {
        const elemento = document.getElementById(idElemento);
        if (elemento) {
            const mesActual = new Date().getMonth();
            const svg = elemento.querySelector("svg");
            elemento.innerHTML = '';
            if (svg) elemento.appendChild(svg);
            elemento.innerHTML += meses[mesActual];
        }
    }

    const selectPeriodo = document.getElementById('reporte_seis_meses');

    function obtenerUltimosMeses(n) {
        const hoy = new Date();
        let lista = [];
        for (let i = 1; i <= n; i++) {
            let fecha = new Date(hoy.getFullYear(), hoy.getMonth() - i, 1);
            let mesNum = (fecha.getMonth() + 1).toString().padStart(2, '0');
            lista.push({
                texto: mesesNombres[fecha.getMonth()] + " " + fecha.getFullYear(),
                valor: `${fecha.getFullYear()}-${mesNum}`
            });
        }
        return lista;
    }

    function rellenarSelect(n) {
        selectPeriodo.innerHTML = '<option value="actual"> Mes Actual (' + meses[new Date().getMonth()] + ')</option>';
        obtenerUltimosMeses(n).forEach(mes => {
            const option = document.createElement("option");
            option.value = mes.valor;
            option.textContent = mes.texto;
            selectPeriodo.appendChild(option);
        });
    }

    actualizarMesEnElemento("mes_actual");
    actualizarMesEnElemento("mes_actual_5ta");

    let mesAnterior = new Date().getMonth();
    setInterval(() => {
        const mesActual = new Date().getMonth();
        if (mesActual !== mesAnterior) {
            mesAnterior = mesActual;
            actualizarMesEnElemento("mes_actual");
            actualizarMesEnElemento("mes_actual_5ta");
        }
    }, 60000); 

    function actualizarKpiEjecutivo() {
        const totalCombinado = sumaTotalTeranGlobal + sumaTotal5taGlobal;
        const metaCombinada = metaTeranGlobal + meta5taGlobal;

        animarNumero('kpi_ingreso_total', totalCombinado, formataMoneda);

        const elMeta = document.getElementById('kpi_meta_global');
        if (metaCombinada > 0) {
            elMeta.classList.remove('sin-meta');
            const porcentaje = Math.round((totalCombinado / metaCombinada) * 100);
            animarNumero('kpi_meta_global', porcentaje, (v) => Math.round(v) + '%');

            const barra = document.getElementById('kpi_meta_progress_bar');
            barra.style.width = Math.min(porcentaje, 100) + '%';
            barra.style.background = porcentaje >= 100 ? 'linear-gradient(90deg,#17904b,#1fbf68)'
                                    : porcentaje >= 70  ? 'linear-gradient(90deg,#f0a500,#ffc107)'
                                                        : 'linear-gradient(90deg,#d9364a,#f26d7d)';
        } else {
            elMeta.classList.remove('kpi-skeleton');
            elMeta.classList.add('sin-meta');
            elMeta.innerText = 'Sin meta configurada';
            document.getElementById('kpi_meta_progress_bar').style.width = '0%';
        }

        const progresoTeran = metaTeranGlobal > 0 ? Math.min((sumaTotalTeranGlobal / metaTeranGlobal) * 100, 100) : 0;
        const progreso5ta = meta5taGlobal > 0 ? Math.min((sumaTotal5taGlobal / meta5taGlobal) * 100, 100) : 0;
        const barraTeran = document.getElementById('progress_teran');
        const barra5ta = document.getElementById('progress_5ta');
        if (barraTeran) barraTeran.style.width = progresoTeran + '%';
        if (barra5ta) barra5ta.style.width = progreso5ta + '%';
    }

    function renderProyeccionCierre(data) {
        const badge = document.getElementById('proyeccion_badge_estado');
        const contenido = document.getElementById('proyeccion_contenido');
        if (!badge || !contenido) return;

        if (!data.es_mes_actual) {
            const huboMeta = data.meta_total > 0;
            const cumplioMeta = huboMeta ? data.venta_actual >= data.meta_total : null;

            badge.className = 'badge px-3 py-2 rounded-pill ' +
                (cumplioMeta === null ? 'bg-secondary' : cumplioMeta ? 'bg-success' : 'bg-danger');
            badge.innerText = cumplioMeta === null ? 'Mes cerrado' : (cumplioMeta ? 'Meta cumplida' : 'Meta no alcanzada');

            contenido.innerHTML = `
                <p class="mb-0 text-muted">
                    Mes cerrado. Ventas finales: <strong class="text-dark">${formataMoneda(data.venta_actual)}</strong>
                    ${huboMeta ? ' de una meta de <strong class="text-dark">' + formataMoneda(data.meta_total) + '</strong>.' : ' (sin meta configurada para ese mes).'}
                </p>`;
            return;
        }

        const vaAlcanzar = data.va_alcanzar_meta;
        badge.className = 'badge px-3 py-2 rounded-pill ' +
            (vaAlcanzar === null ? 'bg-secondary' : vaAlcanzar ? 'bg-success' : 'bg-danger');
        badge.innerText = vaAlcanzar === null ? 'Sin meta configurada' : (vaAlcanzar ? 'Vas en camino a la meta' : 'En riesgo de no llegar');

        const porcentajeTiempo = Math.min((data.dias_transcurridos / data.dias_en_mes) * 100, 100);
        const vaBienDeRitmo = data.ritmo_diario_actual >= data.ritmo_necesario_diario;

        contenido.innerHTML = `
            <div class="row g-4 mb-3">
                <div class="col-md-4">
                    <span class="d-block text-muted text-uppercase small mb-1" style="letter-spacing:.5px; font-size:10.5px;">Proyección de cierre</span>
                    <h3 class="mb-0 fw-bold text-dark">${formataMoneda(data.proyeccion_cierre)}</h3>
                </div>
                <div class="col-md-4">
                    <span class="d-block text-muted text-uppercase small mb-1" style="letter-spacing:.5px; font-size:10.5px;">Ritmo actual / día</span>
                    <h3 class="mb-0 fw-bold text-primary">${formataMoneda(data.ritmo_diario_actual)}</h3>
                </div>
                <div class="col-md-4">
                    <span class="d-block text-muted text-uppercase small mb-1" style="letter-spacing:.5px; font-size:10.5px;">Ritmo necesario / día</span>
                    <h3 class="mb-0 fw-bold ${vaBienDeRitmo ? 'text-success' : 'text-danger'}">${formataMoneda(data.ritmo_necesario_diario)}</h3>
                </div>
            </div>
            <div class="progress" style="height: 10px; border-radius: 8px; background:#eef0f5;">
                <div class="progress-bar bg-primary" style="width:${porcentajeTiempo}%; border-radius:8px;"></div>
            </div>
            <div class="d-flex justify-content-between mt-1">
                <small class="text-muted">Día ${data.dias_transcurridos} de ${data.dias_en_mes}</small>
                <small class="text-muted">${data.dias_restantes} días restantes</small>
            </div>
            ${data.meta_total > 0
                ? `<p class="mt-3 mb-0 small text-muted">Meta del mes: <strong class="text-dark">${formataMoneda(data.meta_total)}</strong> &middot; Llevas <strong class="text-dark">${formataMoneda(data.venta_actual)}</strong></p>`
                : `<p class="mt-3 mb-0 small text-muted">No hay meta configurada para este mes; la proyección se muestra sin comparar contra meta.</p>`
            }`;
    }

    function metasTiendas(respuesta) {
        let meta_teran = respuesta.meta_venta_teran;
        const meta_5ta = respuesta.meta_venta_5ta;
        const metaTeran = document.getElementById('meta_teran');
        const meta5ta = document.getElementById('meta_5ta_norte');

        metaTeranGlobal = 0;
        meta5taGlobal = 0;

        for (var i = 0; i < meta_teran.length; i++) {
            metaTeranGlobal += Number(meta_teran[i].maximo_ventas) || 0;
            const importe_venta = formataMoneda(meta_teran[i].maximo_ventas);
            metaTeran.innerHTML += `<h2 class="mb-2">${importe_venta}<small style="font-size: 13px;">Mx</small></h2><p class="mb-0 text-gray">Meta del mes</p>`;
        }
        for (var i = 0; i < meta_5ta.length; i++) {
            meta5taGlobal += Number(meta_5ta[i].maximo_ventas) || 0;
            const importe_venta = formataMoneda(meta_5ta[i].maximo_ventas);
            meta5ta.innerHTML += `<h2 class="mb-2">${importe_venta}<small style="font-size: 13px;">Mx</small></h2><p class="mb-0 text-gray">Meta del mes</p>`;
        }
        actualizarKpiEjecutivo();
    }

    function pormetododepago(respuesta) {
        let metodo_teran = respuesta.tabla_teran;
        let apartados_teran = respuesta.union_teran;
        let apartados_5ta = respuesta.union_5ta;
        let metodo_5ta = respuesta.tabla_5ta;

        const tableMetodo = document.getElementById('baner_tipo_pago');
        const tableMetodo5ta = document.getElementById('baner_tipo_pago_5ta');
        tableMetodo.innerHTML = '';
        tableMetodo5ta.innerHTML = '';

        const union_metodo_pagos_teran = apartados_teran.map(item => ({ tipo_pago: item.forma_pago, importe_total_por_tipo: item.importe_total }));
        const union_metodo_pagos_5ta = apartados_5ta.map(item => ({ tipo_pago: item.forma_pago, importe_total_por_tipo: item.importe_total }));

        const todos_metodo_pago_teran = [...metodo_teran, ...union_metodo_pagos_teran];
        const todos_metodo_pago_5ta = [...metodo_5ta, ...union_metodo_pagos_5ta];

        todos_metodo_pago_teran.forEach(item => {
            const importe_mx = formataMoneda(item.importe_total_por_tipo);
            tableMetodo.innerHTML += `
               <div class="mb-2 d-flex profile-media align-items-top">
                     <div class="mt-1 profile-dots-pills border-primary"></div>
                     <div class="ms-4">
                        <h6 class="mb-1">${item.tipo_pago}</h6>
                        <span class="mb-0">Total: ${importe_mx}</span>
                     </div>
               </div>`;
        });

        todos_metodo_pago_5ta.forEach(item => {
            const importe_mx = formataMoneda(item.importe_total_por_tipo);
            tableMetodo5ta.innerHTML += `
            <div class="mb-2 d-flex profile-media align-items-top">
                   <div class="mt-1 profile-dots-pills border-primary"></div>
                   <div class="ms-4">
                      <h6 class="mb-1">${item.tipo_pago}</h6>
                      <span class="mb-0">Total: ${importe_mx}</span>
                   </div>
            </div>`;
        });
    }

    function calculaElTotalMes(respuesta) {
        let metodo = respuesta.tabla_teran;
        let metodo_5ta = respuesta.tabla_5ta;

        sumaTotalTeranGlobal = 0;
        sumaTotal5taGlobal = 0;
        metodo.forEach(item => { sumaTotalTeranGlobal += Number(item.importe_total_por_tipo) || 0; });
        metodo_5ta.forEach(item => { sumaTotal5taGlobal += Number(item.importe_total_por_tipo) || 0; });

        const total_men = document.getElementById('total_mensual');
        const total_men_5ta = document.getElementById('total_mensual_5ta');
        total_men.innerHTML = `<h2 class="mb-2">${formataMoneda(sumaTotalTeranGlobal)}<small style="font-size: 13px;">Mx</small></h2><p class="mb-0 text-gray">Acumulado del mes</p>`;
        total_men_5ta.innerHTML = `<h2 class="mb-2">${formataMoneda(sumaTotal5taGlobal)}<small style="font-size: 13px;">Mx</small></h2><p class="mb-0 text-gray">Acumulado del mes</p>`;

        actualizarKpiEjecutivo();
    }

    let mySwiper = null;

    function recupera_vendedor_actual(respuesta) {
        let vendedor_mes_teran = respuesta.vendedores_teran;
        let vendedor_mes_5ta = respuesta.vendedores_5ta;
        let banner_vendedor = document.getElementById('banner_vendedor_mes');
        banner_vendedor.innerHTML = '';

        let allVendedores = [...vendedor_mes_teran, ...vendedor_mes_5ta];
        if (allVendedores.length === 0) return;

        const totals = allVendedores.map(item => item.total_vendedor);
        const maxTotal = Math.max(...totals);
        const minTotal = Math.min(...totals);

        allVendedores.forEach((vendedor, i) => {
            const vendedor_mx = formataMoneda(vendedor.total_vendedor);
            const total = vendedor.total_vendedor;
            const haySoloUno = allVendedores.length === 1;
            const esTop = total === maxTotal && !haySoloUno;
            const esBajo = total === minTotal && !haySoloUno;

            let progressColor = esTop ? "circle-progress-blue" : esBajo ? "circle-progress-red" : "circle-progress-yellow";
            let avatarClase = esTop ? "top-vendedor" : esBajo ? "bajo-vendedor" : "";
            let badgeClase = esTop ? "top" : esBajo ? "bajo" : "normal";
            let badgeTexto = esTop ? "Top del mes" : esBajo ? "A reforzar" : "En curso";

            const iniciales = (vendedor.vendedor || '?')
                .trim().split(/\s+/).slice(0, 2).map(p => p[0]).join('').toUpperCase();

            const slide = document.createElement('li');
            slide.className = "swiper-slide";
            slide.innerHTML = `
               <div class="card shadow-sm mb-0 vendedor-card">
                  <div class="card-body">
                     <div class="progress-widget d-flex align-items-center">
                        <div class="vendedor-avatar ${avatarClase}">
                           ${iniciales}
                           ${esTop ? '<i class="fas fa-crown vendedor-corona"></i>' : ''}
                        </div>
                        <div class="progress-detail ms-3 flex-grow-1">
                           <p class="mb-1 small text-muted">${vendedor.vendedor}</p>
                           <h5 class="counter mb-1" style="font-size: 1.05rem;">${vendedor_mx}</h5>
                           <span class="vendedor-rank-badge ${badgeClase}">${badgeTexto}</span>
                        </div>
                        <div id="circle-progress-${i}"
                           class="${progressColor} circle-progress flex-shrink-0"
                           data-min-value="0" data-max-value="${maxTotal}"
                           data-value="${total}" data-type="percent"
                           style="width: 40px; height: 40px;">
                        </div>
                     </div>
                  </div>
               </div>`;
            banner_vendedor.appendChild(slide);
        });

        inicializarCircleProgress();

        asegurarSwiper(() => {
            inicializarSwiper();
            if (mySwiper) mySwiper.update();
        });
    }

    function inicializarCircleProgress() {
        if (typeof $ === 'undefined' || typeof $.fn === 'undefined' || typeof $.fn.circleProgress !== 'function') {
            console.warn('[panel] No encontré el plugin circleProgress cargado globalmente; los círculos de vendedores no se pueden inicializar aquí. Dime qué librería usa tu tema para ajustarlo.');
            return;
        }
        document.querySelectorAll('#banner_vendedor_mes .circle-progress').forEach(el => {
            const max = parseFloat(el.dataset.maxValue) || 1;
            const valor = parseFloat(el.dataset.value) || 0;
            let color = '#ffc107';
            if (el.classList.contains('circle-progress-blue')) color = '#3a57e8';
            if (el.classList.contains('circle-progress-red')) color = '#f26d7d';

            $(el).circleProgress({
                value: max > 0 ? valor / max : 0,
                size: 40,
                thickness: 5,
                emptyFill: '#eef0f5',
                fill: { color: color }
            });
        });
    }

    function inicializarSwiper() {
        if (typeof Swiper === 'undefined') {
            console.error('[panel] Swiper no está disponible; no se puede inicializar el carrusel de vendedores.');
            return;
        }
        if (mySwiper !== null) mySwiper.destroy(true, true);
        mySwiper = new Swiper('.d-slider1', {
            observer: true, observeParents: true, watchOverflow: true,
            spaceBetween: 20, slidesPerView: 1,
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
            breakpoints: {
                320: { slidesPerView: 1.2, spaceBetween: 10 },
                550: { slidesPerView: 2 },
                991: { slidesPerView: 3 },
                1400: { slidesPerView: 4 },
                1920: { slidesPerView: 5 }
            }
        });
        setTimeout(() => { mySwiper.update(); }, 500);
    }

   
    let chartVentas = null;

    function construirSeriePorFecha(datosTeran, datos5ta) {
        const mapaFechas = new Map();
        datosTeran.forEach(d => {
            const f = d.fecha_pago || d.fecha;
            mapaFechas.set(f, { fecha: f, teran: Number(d.total_vendido) || 0, quinta: 0 });
        });
        datos5ta.forEach(d => {
            const f = d.fecha_pago || d.fecha;
            if (mapaFechas.has(f)) {
                mapaFechas.get(f).quinta = Number(d.total_vendido) || 0;
            } else {
                mapaFechas.set(f, { fecha: f, teran: 0, quinta: Number(d.total_vendido) || 0 });
            }
        });
        const filas = Array.from(mapaFechas.values()).sort((a, b) => new Date(a.fecha) - new Date(b.fecha));
        return {
            fechas: filas.map(r => r.fecha),
            serieTeran: filas.map(r => r.teran),
            serie5ta: filas.map(r => r.quinta)
        };
    }

    function graficapordefault(respuesta) {
        const container = document.querySelector("#d-main");
        if (!container) return;

        if (chartVentas) { chartVentas.destroy(); chartVentas = null; }
        container.innerHTML = "";

        const datosTeran = respuesta.totales_teran || [];
        const datos5ta = respuesta.totales_5ta || [];

        if (datosTeran.length === 0 && datos5ta.length === 0) {
            container.innerHTML = "<p style='text-align: center; padding: 20px;'>Mes nuevo, por esta razon no hay datos para mostrar la grafica.</p>";
            container.innerHTML += "<div style='text-align: center;'><i class='fas fa-sad-tear' style='font-size: 48px; color: #ccc;'></i></div>";
            return;
        }

        const { fechas, serieTeran, serie5ta } = construirSeriePorFecha(datosTeran, datos5ta);

        const avisoContainer = document.getElementById('grafica_ventas_aviso');
        if (avisoContainer) {
            const sumaTeran = serieTeran.reduce((a, b) => a + b, 0);
            const suma5ta = serie5ta.reduce((a, b) => a + b, 0);
            let aviso = '';
            if (sumaTeran === 0) aviso = 'Boulevard sin ventas registradas este periodo.';
            else if (suma5ta === 0) aviso = 'Centro sin ventas registradas este periodo.';
            avisoContainer.querySelector('span').innerText = aviso;
            avisoContainer.style.display = aviso ? 'block' : 'none';
        }

        const fechasCortas = fechas.map(f => {
            const d = new Date(f + 'T00:00:00');
            return isNaN(d) ? f : d.toLocaleDateString('es-MX', { day: '2-digit', month: 'short' }).replace('.', '');
        });

        const options = {
            series: [
                { name: 'HERRALUM BOULEVARD', data: serieTeran },
                { name: 'HERRALUM CENTRO', data: serie5ta }
            ],
            chart: {
                type: 'area', height: 300, toolbar: { show: false },
                zoom: { enabled: false },
                dropShadow: { enabled: true, top: 6, left: 0, blur: 6, opacity: 0.08 },
                fontFamily: 'inherit'
            },
            stroke: { curve: 'smooth', width: 3 },
            colors: ["#3a57e8", "#17904b"],
            fill: {
                type: 'gradient',
                gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.02, stops: [0, 90, 100] }
            },
            markers: {
                size: 0, strokeWidth: 3, strokeColors: '#fff',
                hover: { size: 6 }
            },
            legend: {
                position: 'top', horizontalAlign: 'right', fontSize: '12px',
                markers: { radius: 12 }, itemMargin: { horizontal: 10 }
            },
            xaxis: {
                categories: fechasCortas,
                axisBorder: { show: false }, axisTicks: { show: false },
                labels: { style: { colors: '#8891a5', fontSize: '11px' } }
            },
            yaxis: {
                labels: {
                    style: { colors: '#8891a5', fontSize: '11px' },
                    formatter: (value) => value >= 1000 ? `$${(value / 1000).toFixed(0)}k` : `$${value}`
                }
            },
            tooltip: {
                shared: true, intersect: false, theme: 'light',
                y: { formatter: (value) => formataMoneda(value) }
            },
            grid: {
                show: true, borderColor: '#f0f1f6', strokeDashArray: 4,
                padding: { left: 8, right: 8 }
            }
        };

        if (typeof ApexCharts !== 'undefined') {
            chartVentas = new ApexCharts(container, options);
            chartVentas.render();
        }
    }

    let chartTop5 = null;

    function renderTop5Chart(respuesta) {
        const top_5_des = document.getElementById('nombre_top_5');
        const container_top = document.getElementById('myChart');

        const colores = ['#3a57e8', '#4bc7d2', '#17904b', '#ffc107', '#f26d7d'];

        if (chartTop5) { chartTop5.destroy(); chartTop5 = null; }
        top_5_des.innerHTML = '';
        container_top.innerHTML = "";

        const descripcion_top_5 = respuesta.map(res => res.producto_descripcion);
        const cantida_vendida = respuesta.map(res => res.total);

        if (cantida_vendida.length === 0 || cantida_vendida.every(cant => cant === 0)) {
            container_top.innerHTML = "<p style='text-align: center; padding: 20px;'>Mes nuevo, por esta razon no hay datos para mostrar la grafica.</p>";
            container_top.innerHTML += "<div style='text-align: center;'><i class='fas fa-sad-tear' style='font-size: 48px; color: #ccc;'></i></div>";
            return;
        }

        const totalUnidades = cantida_vendida.reduce((a, b) => a + b, 0);

        const options = {
            series: cantida_vendida,
            colors: colores,
            chart: { width: '100%', height: '260px', type: 'donut', fontFamily: 'inherit' },
            labels: descripcion_top_5,
            legend: { show: false },
            stroke: { width: 3, colors: ['#fff'] },
            dataLabels: {
                enabled: true, style: { fontSize: '12px', fontWeight: 700 },
                dropShadow: { enabled: false },
                formatter: (val) => Math.round(val) + '%'
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '68%',
                        labels: {
                            show: true,
                            total: {
                                show: true, label: 'Total vendido',
                                fontSize: '11px', color: '#8891a5',
                                formatter: () => new Intl.NumberFormat('es-MX').format(totalUnidades) + ' pzas'
                            },
                            value: { fontSize: '20px', fontWeight: 800, color: '#16233f' }
                        }
                    }
                }
            },
            tooltip: { y: { formatter: (val) => new Intl.NumberFormat('es-MX').format(val) + ' vendidos' } }
        };

        chartTop5 = new ApexCharts(container_top, options);
        chartTop5.render();

        respuesta.forEach((item, i) => {
            const porcentaje = totalUnidades > 0 ? Math.round((item.total / totalUnidades) * 100) : 0;
            const totalFormateado = new Intl.NumberFormat('es-MX').format(item.total);
            const color = colores[i % colores.length];
            top_5_des.innerHTML += `
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-dark fw-bold" style="font-size:11.5px; line-height:1.2;">${item.producto_descripcion}</span>
                        <span class="text-muted flex-shrink-0 ms-2" style="font-size:10.5px;">${totalFormateado}</span>
                    </div>
                    <div class="top5-bar-track">
                        <div class="top5-bar-fill" style="width:${porcentaje}%; background:${color};"></div>
                    </div>
                </div>`;
        });
    }

    function top5ventas(respuesta) {
        const banner = document.getElementById('recu_top_5');
        banner.innerHTML = '';
        respuesta.forEach(item => {
            let nombre_real_corto = item.nombre_real.replace('TIENDA ', '');
            banner.innerHTML += `<li>
                <a class="graficar_top_5 dropdown-item" data-nombre="${item.nombre_interno}" data-id="${nombre_real_corto}">
                    ${nombre_real_corto}
                </a>
            </li>`;
        });

        document.querySelectorAll('.graficar_top_5').forEach(button => {
            button.addEventListener('click', function () {
                let nombre_interno = $(this).data('nombre');
                let nombre_tienda = $(this).data('id');
                document.querySelector('#dropdownMenuButton1').innerHTML = nombre_tienda;

                fetch('/recupera-top5/' + nombre_interno)
                    .then(response => response.json())
                    .then(respuesta => renderTop5Chart(respuesta))
                    .catch(err => console.error("Error al recuperar top 5 de la sucursal:", err));
            });
        });
    }

    function renderAlertaVentaPerdida(monto) {
        const alerta = document.getElementById('alerta_venta_perdida');
        const displayMonto = document.getElementById('monto_venta_perdida');
        if (monto > 0) {
            alerta.style.display = 'block';
            displayMonto.innerText = formataMoneda(monto);
        } else {
            alerta.style.display = 'none';
        }
    }

    function renderKPIsTabla(res) {
        const kpis = res.kpis;
        const sucursales = res.sucursales;

        const conteoContainer = document.getElementById('conteo_remisiones');
        const sucursalesFooter = document.getElementById('desglose_sucursales_footer');

        conteoContainer.innerHTML = `
            <span class="d-flex align-items-center gap-2">
                <i class="fas fa-check-circle text-primary"></i>
                <span><strong class="text-dark">${kpis.total_folios ?? 0}</strong> <span class="text-muted small">cotizaciones foliadas</span></span>
            </span>
            <span class="text-muted small border-start ps-3">
                Pendientes por autorizar: <strong class="text-dark">${formataMoneda(kpis.pipeline_pendiente)}</strong>
            </span>
        `;

        document.getElementById('total_inversion_tabla').innerText = formataMoneda(kpis.inversion_autorizada);
        document.getElementById('total_ahorro_tabla').innerText = formataMoneda(kpis.ahorro_total);

        animarNumero('kpi_folios', kpis.total_folios ?? 0, (v) => Math.round(v).toLocaleString('es-MX'));
        document.getElementById('kpi_folios_pendientes').innerText = 'Pendientes: ' + formataMoneda(kpis.pipeline_pendiente);
        animarNumero('kpi_ahorro', kpis.ahorro_total ?? 0, formataMoneda);

        sucursalesFooter.innerHTML = '';
        if (sucursales && sucursales.length > 0) {
            sucursales.forEach(s => {
                let nombre = s.sucursal_id == 1 ? 'BOULEVARD' : 'CENTRO';
                let color = s.sucursal_id == 1 ? 'text-primary' : 'text-success';
                sucursalesFooter.innerHTML += `
                    <div class="text-start">
                        <span class="d-block text-muted text-uppercase" style="font-size: 9px; letter-spacing: 0.5px;">Total ${nombre}</span>
                        <span class="${color} fw-bold small">${formataMoneda(s.total)}</span>
                    </div>`;
            });
        } else {
            sucursalesFooter.innerHTML = '<span class="text-muted small"><i class="fas fa-info-circle me-1"></i>Aún no hay compras autorizadas este periodo</span>';
        }
    }

    function renderFilasCotizaciones(datos) {
        const tbody = document.getElementById('datos_remisiones');
        const thead = document.getElementById('filas_remisiones');

        thead.innerHTML = `
            <tr class="text-muted text-uppercase small">
                <th>Cotización</th>
                <th>Sucursal / Proveedor</th>
                <th class="text-end">Monto</th>
                <th class="text-center">Estado</th>
            </tr>`;

        tbody.innerHTML = '';
        if (!datos || datos.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center py-5 text-muted"><i class="fas fa-inbox d-block mb-2" style="font-size:22px; opacity:.4;"></i>No hay movimientos registrados en este periodo.</td></tr>';
            return;
        }

        datos.forEach(c => {
            let etiquetaSucursal = c.sucursal_id == 1
                ? '<span class="badge bg-soft-primary text-primary border-primary-subtle">BOULEVARD</span>'
                : '<span class="badge bg-soft-success text-success border-success-subtle">CENTRO</span>';

            const ahorroNum = parseFloat(c.ahorro) || 0;
            const totalNum = parseFloat(c.total) || 0;
            let rowClass = "";
            let ahorroBadge = "";
            if (ahorroNum > totalNum) {
                rowClass = "table-success-light";
                ahorroBadge = '<i class="fas fa-rocket text-success me-1" title="Máximo ahorro detectado"></i>';
            }

            const folioHtml = c.folio
                ? `<span class="folio-pill con-folio">${c.folio}</span>`
                : `<span class="folio-pill sin-folio">SIN FOLIO</span>`;

            const badge = c.folio_autorizado == 1
                ? '<span class="badge bg-soft-success text-success border border-success-subtle px-3"><i class="fas fa-check-circle me-1"></i>Autorizado</span>'
                : '<span class="badge bg-soft-warning text-warning border border-warning-subtle px-3"><i class="fas fa-hourglass-half me-1"></i>Pendiente</span>';

            tbody.innerHTML += `
                <tr class="${rowClass}">
                    <td>
                        <div class="d-flex flex-column gap-1">
                            ${folioHtml}
                            <small class="text-muted">#${c.id} &middot; ${c.fecha}</small>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column gap-1">
                            ${etiquetaSucursal}
                            <span class="small fw-bold text-uppercase text-dark">${c.proveedor}</span>
                        </div>
                    </td>
                    <td class="text-end">
                        <div class="d-flex flex-column align-items-end">
                            <span class="fw-bold text-dark">${formataMoneda(c.total)}</span>
                            <small class="text-success fw-bold">${ahorroBadge}Ahorro: ${formataMoneda(c.ahorro)}</small>
                        </div>
                    </td>
                    <td class="text-center">${badge}</td>
                </tr>`;
        });
    }

    function cargarTodoElPanel(periodo) {
        periodoGlobal = periodo;
        const qs = `?periodo=${encodeURIComponent(periodo)}`;

        fetch('/salud/financiera' + qs)
            .then(res => res.json())
            .then(res => {
                renderAlertaVentaPerdida(res.venta_perdida);
                renderKPIsTabla(res);
                renderFilasCotizaciones(res.tabla_detallada);
            })
            .catch(err => console.error("Error en Salud Financiera Global:", err));

        fetch('/recupera-metodo-de-pago' + qs)
            .then(response => response.json())
            .then(respuesta => {
                document.getElementById('baner_tipo_pago').innerHTML = '';
                document.getElementById('baner_tipo_pago_5ta').innerHTML = '';
                pormetododepago(respuesta);
                calculaElTotalMes(respuesta);
            })
            .catch(err => console.error("Error en metodos de pago:", err));

        fetch('/recupera-meta-de-venta-teran' + qs)
            .then(response => response.json())
            .then(respuesta => {
                document.getElementById('meta_teran').innerHTML = '';
                document.getElementById('meta_5ta_norte').innerHTML = '';
                metasTiendas(respuesta);
            })
            .catch(err => console.error("Error en metas de venta:", err));

        fetch('/recupera-vendedores-mes-actual' + qs)
            .then(response => response.json())
            .then(respuesta => recupera_vendedor_actual(respuesta))
            .catch(err => console.error("Error en vendedores:", err));

        fetch('/grafica-default-ventas-dias-habiles' + qs)
            .then(response => response.json())
            .then(respuesta => graficapordefault(respuesta))
            .catch(err => console.error("Error en grafica de ventas:", err));

        fetch('/grafica-default-top-5-mes' + qs)
            .then(response => response.json())
            .then(respuesta => renderTop5Chart(respuesta))
            .catch(err => console.error("Error en top 5 productos:", err));

        fetch('/proyeccion-cierre-mes' + qs)
            .then(response => response.json())
            .then(respuesta => renderProyeccionCierre(respuesta))
            .catch(err => console.error("Error en proyección de cierre:", err));
    }

    document.addEventListener('DOMContentLoaded', () => {
        rellenarSelect(6);
        fetch('/recupera-sucursales')
            .then(response => response.json())
            .then(respuesta => top5ventas(respuesta))
            .catch(err => console.error("Error al recuperar sucursales:", err));

        cargarTodoElPanel('actual');

        selectPeriodo.addEventListener('change', function () {
            cargarTodoElPanel(this.value);
        });
    });
</script>
@endsection