@extends('layout.index') <!-- Extiende del layout base -->
@section('content')
<main class="main-content">
    <div class="position-relative iq-banner">
       <!-- Nav Header Component Start -->
        <div class="iq-navbar-header" style="height: 230px;">
            <div class="container-fluid iq-container">
                <div class="row">
                    <div class="col-md-12" style="justify-items: end; ">
                        <div class="flex-wrap d-flex justify-content-between align-items-center" >
                            <div class="iq-header-title" >
                                <h1>Hola,  {{ Auth::user()->nombre }} {{ Auth::user()->apellido_p }} {{Auth::user()->apellido_m}}</h1>
                                <!--SELECTOR PARA QUE EL USURIO PUEDA MOSTRAR REPORTES HASTA 6 MESES ATRAS-->
                                <div class="mt-2" style="text-align: justify;float: right;"> 
                                 <select name="reporte_seis_meses" id="reporte_seis_meses" class="custom-select-months">
                                      
                                    </select>
                                 </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="iq-header-img" style="opacity: 0.5;">
               <img src="{{ asset('img/configuraciones/logo_frontera.jpeg') }}" alt="header" class="theme-color-default-img img-fluid w-100 h-100 ">       
           </div>
        </div>        
    </div>
    <div class="conatiner-fluid content-inner mt-n5 py-0">
<div class="row">
 <div class="col-md-12 col-lg-12">
    <div class="row row-cols-1">
       <div class="overflow-hidden d-slider1 " >
          <ul  class="p-0 m-0 mb-2 swiper-wrapper list-inline" id="banner_vendedor_mes">
           
          </ul>
          <div class="swiper-button swiper-button-next"></div>
          <div class="swiper-button swiper-button-prev"></div>
       </div>
    </div>
 </div>
 <div class="col-md-12 col-lg-8">
    <div class="row">
       <div class="col-md-12">
         <div class="card" data-aos="fade-up" data-aos-delay="800">
            <div class="flex-wrap card-header d-flex justify-content-between align-items-center">
                <div class="header-title">
                    <p class="mb-0">Grafica | Ventas </p>          
                </div>
                <div class="d-flex align-items-center align-self-center">
                  <h4 class="card-title"></h4>
                </div>
                <div class="dropdown">
                    <a href="#" class="text-gray dropdown-toggle" id="dropdownMenuButton22" data-bs-toggle="dropdown" aria-expanded="false">
                     GRAFICA COMPARATIVAS REALES DE VENTAS
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div id="d-main" class="d-main"></div>
            </div>
        </div>
       </div>
       <div class="col-md-12 col-xl-6">
          <div class="card" data-aos="fade-up" data-aos-delay="900">
             <div class="flex-wrap card-header d-flex justify-content-between">
                <div class="header-title">
                     
                </div>   
                <div class="dropdown">
                   <a href="#" class="text-gray dropdown-toggle" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                     TIENDA HERRALUM BOULEVARD
                   </a>
                   <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1" id="recu_top_5">
                      
                   </ul>
                </div>
             </div>
             <div class="card-body">
                <div class="flex-wrap d-flex align-items-center justify-content-between">
                   <div id="myChart" class="col-md-8 col-lg-8 myChart "></div>
                   <div class="d-grid gap col-md-4 col-lg-4" id="nombre_top_5">
                      
                   </div>
                </div>
             </div>
          </div>
       </div>
       <div class="col-md-12 col-xl-6">
          <div class="card" data-aos="fade-up" data-aos-delay="1000">
             <div class="flex-wrap card-header d-flex justify-content-between">
                <div class="header-title">
                   <h4 class="card-title">Comisiones</h4>            
                </div>
                <div class="dropdown">
                   <a href="#" class="text-gray dropdown-toggle" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">
                      TIENDA HERRALUM BOULEVARD
                   </a>
                  <!-- 
                   <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton3">
                      <li><a class="dropdown-item" href="#">This Week</a></li>
                      <li><a class="dropdown-item" href="#">This Month</a></li>
                      <li><a class="dropdown-item" href="#">This Year</a></li>
                   </ul>
                  -->
                </div>
             </div>
             <div class="mt-4 table-responsive">
               <table  class="table mb-0 table-striped" role="grid">
                  <thead id="filas_comisiones">
                    
                  </thead>
                  <tbody id="datos_comisiones">
                     
                  </tbody>
               </table>
               
               <div class="div_pago" id="total_a_pagar_comisiones">
              
               </div>
            </div>
          </div>
       </div>         
       <div class="col-md-12 col-lg-12">
          <div class="overflow-hidden card" data-aos="fade-up" data-aos-delay="600">
             <div class="flex-wrap card-header d-flex justify-content-between">
                <div class="header-title" id="conteo_remisiones">
                            
                </div>
                <div class="dropdown">
                  <a href="#" class="text-gray dropdown-toggle" id="remisiones_tienda" data-bs-toggle="dropdown" aria-expanded="false" >
                     TIENDA HERRALUM BOULEVARD
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1" id="seleccione_tienda_remisiones">
                     
                  </ul>
               </div>
             </div>
             <div class="p-0 card-body">
                <div class="mt-4 table-responsive">
                   <table  class="table mb-0 table-striped" role="grid">
                      <thead id="filas_remisiones">
                       
                      </thead>
                      <tbody id="datos_remisiones">
                         <tr>
                            <td>
                              <!-- <div class="d-flex align-items-center">
                                  <img class="rounded bg-soft-primary img-fluid avatar-40 me-3" src="../assets/images/shapes/01.png" alt="profile">
                                  <h6>Addidis Sportwear</h6>
                              -->
                               </div>
                            </td>
                      
                            
                         </tr>
                         
                      </tbody>
                   </table>
                </div>
             </div>
          </div>
       </div>
       <div class="col-md-12 col-lg-12">
         <div class="overflow-hidden card" data-aos="fade-up" data-aos-delay="600">
            <div class="flex-wrap card-header d-flex justify-content-between">
               <div class="header-title" id="conteo_remisiones_canceladas">
                           
               </div>
               <div class="dropdown">
                 <a href="#" class="text-gray dropdown-toggle" id="remisiones_tienda_cancelaciones" data-bs-toggle="dropdown" aria-expanded="false" >
                    TIENDA HERRALUM BOULEVARD
                 </a>
                 <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1" id="seleccione_tienda_remisiones_canceladas">
                    
                 </ul>
              </div>
            </div>
            <div class="p-0 card-body">
               <div class="mt-4 table-responsive">
                  <table  class="table mb-0 table-striped" role="grid">
                     <thead id="filas_remisiones_canceladas">
                      
                     </thead>
                     <tbody id="datos_remisiones_canceladas">
                        <tr>
                           <td>
                              
                           </td>
                     
                           
                        </tr>
                        
                     </tbody>
                  </table>
               </div>
               <br>
               <ul class="pagination pagination-sm" id="pagination-links">

               </ul>
            </div>
           
         </div>
      </div>
    </div>
 </div>
 <div class="col-md-12 col-lg-4">
    <div class="row">
    
       <div class="col-md-12 col-lg-12">
           <div class="card" data-aos="fade-up" data-aos-delay="500">
         <div class="text-center card-body d-flex justify-content-around" >
            <div class="counter" id="total_mensual">
               
            </div>
            <hr class="hr-vertial">
            <div class="counter" id="meta_teran">
               
            </div>
         </div>
      </div> 
         <div class="col-md-12 col-lg-12">
            <div class="card" data-aos="fade-up" data-aos-delay="600">
               <div class="flex-wrap card-header d-flex justify-content-between">
                  <div class="header-title">
                     <h4 class="mb-2 card-title" id="nombre_tienda_teran">TIENDA HERRALUM BOULEVARD</h4>
                     <p class="mb-0" id="mes_actual">
                        <svg class ="me-2 icon-24" width="24" height="24" viewBox="0 0 24 24">
                           <path fill="#17904b" d="M13,20H11V8L5.5,13.5L4.08,12.08L12,4.16L19.92,12.08L18.5,13.5L13,8V20Z" />
                        </svg>
                       
                     </p>
                  </div>
               </div>
               <div class="card-body" id="baner_tipo_pago">
                      
               </div>
            </div>
         </div>
          <div class="card" data-aos="fade-up" data-aos-delay="500">
             <div class="text-center card-body d-flex justify-content-around" >
                <div class="counter" id="total_mensual_5ta">
                   
                </div>
                <hr class="hr-vertial">
                <div class="counter" id="meta_5ta_norte"> 
                   
                </div>
             </div>
          </div> 
          
       </div>
       <div class="col-md-12 col-lg-12">
          <div class="card" data-aos="fade-up" data-aos-delay="600">
             <div class="flex-wrap card-header d-flex justify-content-between">
                <div class="header-title">
                   <h4 class="mb-2 card-title">TIENDA HERRALUM CENTRO</h4>
                   <p class="mb-0" id="mes_actual_5ta">
                      <svg class ="me-2 icon-24" width="24" height="24" viewBox="0 0 24 24">
                         <path fill="#17904b" d="M13,20H11V8L5.5,13.5L4.08,12.08L12,4.16L19.92,12.08L18.5,13.5L13,8V20Z" />
                      </svg>
                     
                   </p>
                </div>
             </div>
             <div class="card-body" id="baner_tipo_pago_5ta">
                    
             </div>
          </div>
       </div>
  
    </div>
 </div> 
</div>
    </div>

 
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    // --- VARIABLES GLOBALES ---
    const mesesNombres = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    let periodoGlobal = 'actual'; // Controla el mes seleccionado
    let mySwiper = null;
    let sumaTotal = 0; // Para el acumulado mensual

    const selectPeriodo = document.getElementById('reporte_seis_meses');

    // --- 1. LÓGICA DEL SELECTOR DE MESES ---
    function obtenerUltimosMeses(n) {
        const hoy = new Date();
        let lista = [];
        // Empezamos en i=1 para saltar el mes actual
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
        selectPeriodo.innerHTML = '<option value="actual">Mes Actual (En curso)</option>';
        const listaMeses = obtenerUltimosMeses(n);
        listaMeses.forEach(mes => {
            const option = document.createElement("option");
            option.value = mes.valor;
            option.textContent = mes.texto;
            selectPeriodo.appendChild(option);
        });
    }

    // --- 2. FUNCIÓN MAESTRA DE CARGA (REFRESCA TODO EL DASHBOARD) ---
    function cargarDashboard(periodo) {

        const query = periodo === 'actual' ? '' : `?periodo=${periodo}`;

        //icono de carga mientras se actualiza todo el dashboard
        
         document.getElementById('d-main').innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';

        
        // Actualizar etiquetas de texto de mes
        actualizarEtiquetasMesUI(periodo);

        // A. Métodos de Pago y Totales Mensuales
        fetch('/recupera-metodo-de-pago' + query).then(res => res.json()).then(res => {
            document.getElementById('baner_tipo_pago').innerHTML = "";
            document.getElementById('baner_tipo_pago_5ta').innerHTML = "";
            pormetododepago(res);
            calculaElTotalMes(res);
        });

        // B. Metas de Venta
        fetch('/recupera-meta-de-venta-teran' + query).then(res => res.json()).then(res => {
            document.getElementById('meta_teran').innerHTML = "";
            document.getElementById('meta_5ta_norte').innerHTML = "";
            metasTiendas(res);
        });

        // C. Vendedores y Comisiones
        fetch('/recupera-vendedores-mes-actual' + query).then(res => res.json()).then(res => {
            document.getElementById('banner_vendedor_mes').innerHTML = "";
            recupera_vendedor_actual(res);
            tableComisiones(res);
        });

        // D. Gráficas y Tablas Default
        fetch('/grafica-default-ventas-dias-habiles' + query).then(res => res.json()).then(res => graficapordefault(res));
        fetch('/grafica-default-top-5-mes' + query).then(res => res.json()).then(res => graficatop5default(res));
        fetch('/tabla-dafult-remisiones' + query).then(res => res.json()).then(res => defaulttabla(res));

        // E. Remisiones Canceladas (Página 1)
        paginados(1);
    }

    // --- 3. FUNCIONES DE APOYO Y UI ---

    function actualizarEtiquetasMesUI(periodo) {
        let texto = "";
        if (periodo === 'actual') {
            texto = mesesNombres[new Date().getMonth()];
        } else {
            let [anio, mes] = periodo.split('-');
            texto = mesesNombres[parseInt(mes) - 1];
        }
        const el1 = document.getElementById("mes_actual");
        const el2 = document.getElementById("mes_actual_5ta");
        // Preservamos el SVG y actualizamos el texto
        if(el1) { const svg = el1.querySelector("svg"); el1.innerHTML = ''; if(svg) el1.appendChild(svg); el1.innerHTML += texto; }
        if(el2) { const svg = el2.querySelector("svg"); el2.innerHTML = ''; if(svg) el2.appendChild(svg); el2.innerHTML += texto; }
    }

    // Evento Change del Selector
    selectPeriodo.addEventListener('change', function() {
        periodoGlobal = this.value;
        cargarDashboard(periodoGlobal);
    });

    function paginados(pagina = 1) {
        const queryBase = periodoGlobal === 'actual' ? '' : `&periodo=${periodoGlobal}`;
        fetch(`/remisiones-canceladas-default?page=${pagina}${queryBase}`)
            .then(response => response.json())
            .then(respuesta => {
                paginacionUpdate(respuesta);
                muestratabledefaultremisionescanceladas(respuesta);
            });
    }

    // --- 4. CARGA INICIAL ---
    document.addEventListener('DOMContentLoaded', () => {
        rellenarSelect(6);
        cargarDashboard('actual');

        // Sucursales se cargan una vez para llenar los dropdowns
        fetch('/recupera-sucursales').then(response => response.json()).then(respuesta => {
            grafica_por_sucursal(respuesta);
            top5ventas(respuesta);
            tablaremisionesconsaldo(respuesta);
            creatableremisionescanceladas(respuesta);
        });
    });

    // --- 5. TUS FUNCIONES DE PINTADO (MANTENIDAS CON LIMPIEZA) ---

    function metasTiendas(respuesta){
        const metaTeran = document.getElementById('meta_teran');
        const meta5ta = document.getElementById('meta_5ta_norte');
        metaTeran.innerHTML = ""; meta5ta.innerHTML = "";
        
        respuesta.meta_venta_teran.forEach(meta => {
            const importe = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(meta.maximo_ventas);
            metaTeran.innerHTML += `<h2 class="mb-2">${importe}<small style="font-size: 13px;">Mx</small></h2><p class="mb-0 text-gray">Meta del mes</p>`;
        });
        respuesta.meta_venta_5ta.forEach(meta => {
            const importe = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(meta.maximo_ventas);
            meta5ta.innerHTML += `<h2 class="mb-2">${importe}<small style="font-size: 13px;">Mx</small></h2><p class="mb-0 text-gray">Meta del mes</p>`;
        });
    }

    function pormetododepago(respuesta){
        const tableMetodo = document.getElementById('baner_tipo_pago');
        const tableMetodo5ta = document.getElementById('baner_tipo_pago_5ta');
        tableMetodo.innerHTML = ""; tableMetodo5ta.innerHTML = "";

        const todos_teran = [...respuesta.tabla_teran, ...respuesta.union_teran.map(i => ({tipo_pago: i.forma_pago, importe_total_por_tipo: i.importe_total}))];
        const todos_5ta = [...respuesta.tabla_5ta, ...respuesta.union_5ta.map(i => ({tipo_pago: i.forma_pago, importe_total_por_tipo: i.importe_total}))];

        todos_teran.forEach(item => {
            const importe = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(item.importe_total_por_tipo);
            tableMetodo.innerHTML += `<div class="mb-2 d-flex profile-media align-items-top"><div class="mt-1 profile-dots-pills border-primary"></div><div class="ms-4"><h6 class="mb-1">${item.tipo_pago}</h6><span class="mb-0">Total: ${importe}</span></div></div>`;
        });
        todos_5ta.forEach(item => {
            const importe = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(item.importe_total_por_tipo);
            tableMetodo5ta.innerHTML += `<div class="mb-2 d-flex profile-media align-items-top"><div class="mt-1 profile-dots-pills border-primary"></div><div class="ms-4"><h6 class="mb-1">${item.tipo_pago}</h6><span class="mb-0">Total: ${importe}</span></div></div>`;
        });
    }

    function calculaElTotalMes(respuesta) {
        sumaTotal = 0; // Importante resetear
        let sumaTotal5ta = 0;
        respuesta.tabla_teran.forEach(item => { sumaTotal += item.importe_total_por_tipo; });
        respuesta.tabla_5ta.forEach(item => { sumaTotal5ta += item.importe_total_por_tipo; });

        const total_men = document.getElementById('total_mensual');
        const total_men_5ta = document.getElementById('total_mensual_5ta');
        total_men.innerHTML = `<h2 class="mb-2">${new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(sumaTotal)}<small style="font-size: 13px;">Mx</small></h2><p class="mb-0 text-gray">Acumulado del mes</p>`;
        total_men_5ta.innerHTML = `<h2 class="mb-2">${new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(sumaTotal5ta)}<small style="font-size: 13px;">Mx</small></h2><p class="mb-0 text-gray">Acumulado del mes</p>`;
    }

    function tableComisiones(respuesta){
        const datos_comisiones = document.getElementById('datos_comisiones');
        const filas_comisiones = document.getElementById('filas_comisiones');
        filas_comisiones.innerHTML = `<tr><th>Nombre</th><th>Monto sin iva</th><th style="display:none;">%</th><th>A pagar</th></tr>`;
        datos_comisiones.innerHTML = "";
        let suma_comision_total = 0;

        respuesta.vendedores_teran.forEach(vendedor => {
            let porcentaje = (vendedor.vendedor === "ROSY") ? 1.50 : 0.75;
            let total_base = (vendedor.vendedor === "ROSY") ? sumaTotal : vendedor.total_vendedor;
            let sin_iva = total_base / 1.16;
            let a_pagar = sin_iva * (porcentaje / 100);
            suma_comision_total += a_pagar;

            datos_comisiones.innerHTML += `<tr><td>${vendedor.vendedor}</td><td>${new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(sin_iva)}</td><td style="display:none;">${porcentaje}</td><td>${new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(a_pagar)}</td></tr>`;
        });
        document.getElementById('total_a_pagar_comisiones').innerHTML = `<p style="margin-top:5px">Total a pagar: ${new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(suma_comision_total)}Mx</p>`;
    }

    // --- 6. GRÁFICAS (APEXCHARTS) --- VL103M-23810
   function graficapordefault(respuesta) {
    console.log('respuesta del metodo de grafica defualt', respuesta);
    
    // 1. Extraer los arreglos de cada tienda
    const datos_teran = respuesta.totales_teran || [];
    const datos_5ta = respuesta.totales_5ta || [];

    const container = document.querySelector("#d-main");
    if (!container) return;
    container.innerHTML = "";

    // 2. Obtener las fechas (X-Axis). 
    // Usamos las fechas de Terán como base (asumiendo que coinciden en el periodo)
    const dates = datos_teran.map(d => d.fecha_pago || d.fecha);

    // 3. Mapear los totales de cada tienda
    const totals_teran = datos_teran.map(d => d.total_vendido);
    const totals_5ta = datos_5ta.map(d => d.total_vendido);

    const options = {
        // Definimos las dos series aquí
        series: [
            { 
                name: 'TIENDA HERRALUM BOULEVARD', 
                data: totals_teran 
            },
            { 
                name: 'TIENDA HERRALUM CENTRO', 
                data: totals_5ta 
            }
        ],
        chart: { 
            type: 'line', 
            height: 350, 
            toolbar: { show: false },
            zoom: { enabled: true }
        },
        // Colores: Azul para Terán, Verde para 5ta (ajusta a tu preferencia)
        colors: ["#3a57e8", "#17904b"], 
        stroke: { 
            curve: 'smooth', 
            width: 3 
        },
        dataLabels: { 
            enabled: false 
        },
        markers: {
            size: 4
        },
        xaxis: { 
            categories: dates,
            title: { text: 'Fechas del Mes' }
        },
        yaxis: { 
            title: { text: 'Monto de Ventas' },
            labels: { 
                formatter: (v) => `$${new Intl.NumberFormat('es-MX').format(v)}` 
            } 
        },
        tooltip: {
            shared: true, // Para ver ambos valores al pasar el mouse
            intersect: false,
            y: {
                formatter: (v) => `$${new Intl.NumberFormat('es-MX').format(v)}`
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right'
        }
    };

    const chart = new ApexCharts(container, options);
    chart.render();
   }

    function graficatop5default(respuesta){
        const container = document.getElementById('myChart');
        const desContainer = document.getElementById('nombre_top_5');
        container.innerHTML = ""; desContainer.innerHTML = "";

        const labels = respuesta.map(r => r.producto_descripcion);
        const series = respuesta.map(r => r.total);

        new ApexCharts(container, {
            series: series,
            labels: labels,
            chart: { type: 'pie', height: 250 },
            legend: { show: false }
        }).render();

        respuesta.forEach(r => {
            desContainer.innerHTML += `<div class="d-flex align-items-start"><div class="ms-3"><span class="text-gray" style="font-size:11px;">${r.producto_descripcion}</span></div></div>`;
        });
    }

    // --- 7. TABLAS DE REMISIONES ---

    function defaulttabla(res){
        const datosRemisiones = document.getElementById('datos_remisiones');
        const conteoRemisiones = document.getElementById('conteo_remisiones');
        document.getElementById('filas_remisiones').innerHTML = `<tr><th>Num remision</th><th>Fecha</th><th>Importe</th><th>Cliente</th><th>Vendedor</th></tr>`;
        datosRemisiones.innerHTML = "";

        if(res.length === 0){
            datosRemisiones.innerHTML = `<tr><td colspan="5" style="text-align: center;">Sin remisiones con saldo pendiente</td></tr>`;
            conteoRemisiones.innerHTML = `<h4 class="mb-2 card-title">Remisiones con saldo pendiente</h4><p class="mb-0">No hay pendientes</p>`;
        } else {
            res.forEach(item => {
                datosRemisiones.innerHTML += `<tr><td><h6>${item.num_remision}</h6></td><td>${item.fecha}</td><td>${new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(item.importe_total)}</td><td>${item.cliente}</td><td>${item.vendedor}</td></tr>`;
            });
            conteoRemisiones.innerHTML = `<h4 class="mb-2 card-title">Remisiones con saldo pendiente</h4><p class="mb-0">${res.length} saldo pendiente</p>`;
        }
    }

    function muestratabledefaultremisionescanceladas(respuesta){
        const datos = respuesta.paginado.data;
        const container = document.getElementById('datos_remisiones_canceladas');
        container.innerHTML = "";
        
        datos.forEach(item => {
            container.innerHTML += `<tr><td><h6>${item.num_remision}</h6></td><td>${item.fecha}</td><td>${new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(item.importe_total)}</td><td>${item.cliente}</td><td>${item.vendedor}</td></tr>`;
        });
        document.getElementById('conteo_remisiones_canceladas').innerHTML = `<h4 class="mb-2 card-title">Remisiones canceladas</h4><p class="mb-0">${respuesta.paginado.total} cancelaciones</p>`;
    }

    function paginacionUpdate(respuesta) {
        let html = '';
        for (let i = 1; i <= respuesta.paginado.last_page; i++) {
            html += `<li class="page-item ${i === respuesta.paginado.current_page ? 'active' : ''}"><a class="page-link" href="javascript:void(0)" onclick="paginados(${i})">${i}</a></li>`;
        }
        document.getElementById('pagination-links').innerHTML = html;
    }

    // --- 8. FUNCIONES DE SWIPER ---
    
   function inicializarSwiper() {
      if (mySwiper !== null) {
         mySwiper.destroy(true, true);
      }

      mySwiper = new Swiper('.d-slider1', {
         loop: false,
         spaceBetween: 32,
         autoplay: false,
         slidesPerView: 1,
         observer: true,
         observeParents: true,
         navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
         },
         breakpoints: {
            320: { slidesPerView: 1 },
            550: { slidesPerView: 2 }, 
            991: { slidesPerView: 3 },
            1400: { slidesPerView: 3 },
            1500: { slidesPerView: 4 },
            1920: { slidesPerView: 6 },
            2040: { slidesPerView: 7 },
            2440: { slidesPerView: 8 }
         }
      });
   }

    function recupera_vendedor_actual(respuesta) {
        const banner = document.getElementById('banner_vendedor_mes');
        const all = [...respuesta.vendedores_teran, ...respuesta.vendedores_5ta];
        const max = Math.max(...all.map(v => v.total_vendedor));

        all.forEach((v, i) => {
            const slide = document.createElement('li');
            slide.className = "swiper-slide card card-slide";
            slide.innerHTML = `<div class="card-body"><div class="progress-widget"><div class="progress-detail"><p class="mb-2">${v.vendedor}</p><h4 class="counter">${new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(v.total_vendedor)}</h4></div></div></div>`;
            banner.appendChild(slide);
        });
        setTimeout(inicializarSwiper, 100);
    }
</script>  
 </main>
@endsection
                                





