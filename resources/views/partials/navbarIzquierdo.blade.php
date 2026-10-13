<aside class="sidebar sidebar-default sidebar-white sidebar-base navs-rounded-all ">
    <div class="sidebar-header d-flex align-items-center justify-content-start">
        <a href="" class="navbar-brand">
            <!--Logo start-->
            <div class="logo-main">
                <div class="logo-normal">
                    <img style="width: 40px; height:40px; display:flex;" src="{{asset('img/logo_frontera.png')}}" alt="">
                </div>

            </div>
            <!--logo End-->       
            <h4 class="logo-title" style="font-size:13px">
                GIFRONTERASUR.COM.MX
            </h4>
        </a>
        <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
            <i class="icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </i>
        </div>
    </div>
    <div class="sidebar-body pt-0 data-scrollbar">
        <div class="sidebar-list">
            <!-- Sidebar Menu Start -->
            <ul class="navbar-nav iq-main-menu" id="sidebar-menu">
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="#" tabindex="-1">
                        <span class="default-icon">Inicio</span>
                        
                        <span class="mini-icon">
                            <i class="fa-solid fa-house"></i>
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="@if (request()->route()->getName() === 'vista.panel') nav-link  active @else nav-link  @endif" aria-current="page" href="{{route('vista.panel')}}">
                        
                        <i class="icon">
                            <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="icon-20">
                                <path opacity="0.4" d="M16.0756 2H19.4616C20.8639 2 22.0001 3.14585 22.0001 4.55996V7.97452C22.0001 9.38864 20.8639 10.5345 19.4616 10.5345H16.0756C14.6734 10.5345 13.5371 9.38864 13.5371 7.97452V4.55996C13.5371 3.14585 14.6734 2 16.0756 2Z" fill="currentColor"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M4.53852 2H7.92449C9.32676 2 10.463 3.14585 10.463 4.55996V7.97452C10.463 9.38864 9.32676 10.5345 7.92449 10.5345H4.53852C3.13626 10.5345 2 9.38864 2 7.97452V4.55996C2 3.14585 3.13626 2 4.53852 2ZM4.53852 13.4655H7.92449C9.32676 13.4655 10.463 14.6114 10.463 16.0255V19.44C10.463 20.8532 9.32676 22 7.92449 22H4.53852C3.13626 22 2 20.8532 2 19.44V16.0255C2 14.6114 3.13626 13.4655 4.53852 13.4655ZM19.4615 13.4655H16.0755C14.6732 13.4655 13.537 14.6114 13.537 16.0255V19.44C13.537 20.8532 14.6732 22 16.0755 22H19.4615C20.8637 22 22 20.8532 22 19.44V16.0255C22 14.6114 20.8637 13.4655 19.4615 13.4655Z" fill="currentColor"></path>
                            </svg>
                        </i>
                        <span class="item-name">Panel de control</span>
                    </a>
                </li>
                <li class="nav-item" >
                    <a class="nav-link" data-bs-toggle="collapse" href="#horizontal-menu" role="button" aria-expanded="false" aria-controls="horizontal-menu">
                        <i class="fa-solid fa-file-excel"></i>
                        <span class="item-name">Exportar excel</span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="horizontal-menu" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="@if (request()->route()->getName() === 'exportar.caja') nav-link  active @else nav-link  @endif" href="{{route('exportar.caja')}}">
                             
                                <i class="fa-solid fa-box"></i>
                              <span class="item-name"> Cargar corte de caja </span>
                            </a>
                        </li> 
                        <li class="nav-item">
                            <a class="@if (request()->route()->getName() === 'exportar.remisiones') nav-link  active @else nav-link  @endif" href="{{route('exportar.remisiones')}}">
                                <i class="fa-solid fa-layer-group"></i>
                                <span class="item-name">Cargar remisiones</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="@if (request()->route()->getName()==='cargar.utilidades') nav-link active @else nav-link @endif" href="{{route('cargar.utilidades')}}">
                                <i class="fa-solid fa-file-signature"></i>
                                <span class="item-name">Cargar movimientos</span>
                            </a>
                        </li>
                      
                       
                    </ul>
                </li>
                 <li class="nav-item">

                     <a class="nav-link" data-bs-toggle="collapse" href="#generar-reportes" role="button" aria-expanded="false" aria-controls="generar-reportes">
                         <i class="fa-solid fa-file-pdf"></i>
                        <span class="item-name">Generar reportes</span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="generar-reportes" data-bs-parent="#generar-reportes">

                    <li class="nav-item">
                        <a class="@if (request()->route()->getName() === 'vista.reportes') nav-link  active @else nav-link  @endif"  href="{{route('vista.reportes')}}" >
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                            <span class="item-name">Ventas<span class="badge rounded-pill bg-success item-name"></span></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="@if (request()->route()->getName() === 'vista.reportes.comisiones') nav-link active @else nav-link @endif"  href="{{route('vista.reportes.comisiones')}}" >
                            <i class="fa-solid fa-users"></i>
                            <span class="item-name">Comisiones<span class="badge rounded-pill bg-success item-name"></span></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="@if (request()->route()->getName()=== 'calcula.utilidades') nav-link active @else nav-link @endif"  href="{{route('calcula.utilidades')}}" >
                            <i class="fa-solid fa-hand-holding-dollar"></i>
                            <span class="item-name">Utilidades<span class="badge rounded-pill bg-success item-name"></span></span>
                        </a>
                    </li>       
                    <li class="nav-item">
                        <a class="@if (request()->route()->getName()==='vista.tienda_vs_ventas') nav-link active @else nav-link @endif"  href="{{route('vista.tienda_vs_ventas')}}" >
                              <i class="fa-solid fa-chart-bar"></i>
                            <span class="item-name">Menos vendidos<span class="badge rounded-pill bg-success item-name"></span></span>
                        </a>
                    </li>
                     <li class="nav-item">
                        <a class="@if (request()->route()->getName()==='vista.cotizaciones_vs_ventas') nav-link active @else nav-link @endif"  href="{{route('vista.cotizaciones_vs_ventas')}}" >
                              <i class="fa-solid fa-clipboard-list"></i>
                            <span class="item-name">Cotizacion vs Ventas<span class="badge rounded-pill bg-success item-name"></span></span>
                        </a>
                    </li>
                    </ul>
                </li>
            
                <li><hr class="hr-horizontal"></li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="#" tabindex="-1">
                        <span class="default-icon">
                            Autorizar Herrajes
                        </span>
                        <span class="mini-icon">
                            <i class="fa-solid fa-check"></i>
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#sidebar-special" role="button" aria-expanded="false" aria-controls="sidebar-special">
                        <i class="fa-solid fa-tags"></i>
                        <span class="item-name">Cotizacion</span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="sidebar-special" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a @if (request()->route()->getName() === 'vista.cotizaciones_herrajes') class="nav-link  active" @else class="nav-link" @endif href="{{ route('vista.cotizaciones_herrajes') }}">
                               <i class="fa-solid fa-file-pdf"></i>
                              <span class="item-name">Subir cotizacion</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="@if (request()->route()->getName()==='vista.subir_inventario') nav-link active @else nav-link @endif" href="{{route('vista.subir_inventario')}}"  >
                              
                               <i class="fa-solid fa-boxes-stacked"></i>
                                <span class="item-name">Inventario</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="@if (request()->route()->getName()==='vista.pre_autorizacion_cotizacion') nav-link active @else nav-link @endif" href="{{route('vista.pre_autorizacion_cotizacion')}}">
                               <i class="fa-solid fa-hourglass-half"></i>
                                <span class="item-name">Pre-Autorizar</span>
                            </a>
                        </li>
                        <li  class="nav-item" >
                            <a class="@if (request()->route()->getName()==='vista.autorizacion_cotizacion') nav-link active @else nav-link @endif" href="{{route('vista.autorizacion_cotizacion')}}">
                               <i class="fa-solid fa-check"></i>
                                <span class="item-name">Autorizar</span>
                            </a>
                        </li>
                        <li  class="nav-item" >
                            <a class="@if (request()->route()->getName()==='vista.cotizaciones_facturadas') nav-link active @else nav-link @endif" href="{{route('vista.cotizaciones_facturadas')}}">
                              <i class="fa-solid fa-window-restore"></i>
                                <span class="item-name">Conciliar facturas</span>
                            </a>
                        </li>
                        <li  class="nav-item" >
                            <a class="@if (request()->route()->getName()==='vista.conciliar.facturas.reporte') nav-link active @else nav-link @endif" href="{{route('vista.conciliar.facturas.reporte')}}">
                              <i class="fa-solid fa-paste"></i>
                                <span class="item-name">Fact. Conciliadas</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li><hr class="hr-horizontal"></li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="#" tabindex="-1">
                        <span class="default-icon">Control vehicular</span>
                        <span class="mini-icon">

                            <i class="fa-solid fa-car"></i>
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#control-vehicular" role="button" aria-expanded="false" aria-controls="control-vehicular">
                        <i class="fa-solid fa-car-side"></i>
                        <span class="item-name">Vehiculos</span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                
                    <ul class="sub-nav collapse" id="control-vehicular" data-bs-parent="#control-vehicular">
                        <li class="nav-item">
                              <a class="@if (request()->route()->getName()==='cargar.cargar_rutas') nav-link active @else nav-link @endif" href="{{route('cargar.cargar_rutas')}}">
                                <i class="fa-solid fa-route"></i> 
                              <span class="item-name">Cargar viajes</span>
                            </a>
                        </li>
                        <li class="nav-item">
                              <a class="@if (request()->route()->getName()==='cargar.consumos') nav-link active @else nav-link @endif" href="{{route('cargar.consumos')}}">
                               <i class="fa-solid fa-gas-pump"></i>
                              <span class="item-name">Cargar Consumos</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                             <a class="@if (request()->route()->getName()==='vista.reporte_rutas') nav-link active @else nav-link @endif"  href="{{route('vista.reporte_rutas')}}" >
                              <i class="fa-solid fa-car"></i>
                                <span class="item-name">Verificar viajes</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li><hr class="hr-horizontal"></li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="#" tabindex="-1">
                        <span class="default-icon">
                            Control de inventario
                        </span>
                        <span class="mini-icon">
                            <i class="fa-solid fa-car"></i>
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#control-inventario" role="button" aria-expanded="false" aria-controls="control-inventario">
                        <i class="fa-solid fa-filter"></i>
                        <span class="item-name">Filtro inventario</span>
                    </a>
                    <ul class="sub-nav collapse" id="control-inventario" data-bs-parent="#control-inventario">
                        <li class="nav-item">
                            <a class="@if (request()->route()->getName()==='vista.mayorfamilia') nav-link active @else nav-link @endif"  href="{{route('vista.mayorfamilia')}}" >
                                <i class="fa-solid fa-file-contract"></i>
                              <span class="item-name">Reporte inventario</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li><hr class="hr-horizontal"></li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="#" tabindex="-1">
                        <span class="default-icon">
                            Gestiones & Autorizaciones
                        </span>
                        <span class="mini-icon">
                            <i class="fa-solid fa-address-card"></i>
                        </span>
                    </a>
                </li>
                   <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#centro-control" role="button" aria-expanded="false" aria-controls="centro-control">
                        <i class="fa-solid fa-gas-pump"></i>
                        <span class="item-name">Combustible</span>
                          <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                  
                    <ul class="sub-nav collapse" id="centro-control" data-bs-parent="#centro-control">
                        <li class="nav-item">
                            <a class="@if (request()->route()->getName()==='combustible.solicitudes.pendientes') nav-link active @else nav-link @endif"  href="{{route('combustible.solicitudes.pendientes')}}" >
                                <i class="fa-solid fa-stopwatch"></i>
                              <span class="item-name">
                                Solicitudes Pte.</span>
                            </a>
                        </li>
                         <li class="nav-item">
                            <a class="@if (request()->route()->getName()==='combustible.solicitudes.autorizadas') nav-link active @else nav-link @endif"  href="{{route('combustible.solicitudes.autorizadas')}}" >
                                <i class="fa-solid fa-check-double"></i>
                              <span class="item-name">Solicitudes Aut.</span>
                            </a>
                        </li>
                         <li class="nav-item">
                            <a class="@if (request()->route()->getName()==='combustible.solicitudes.fuera.plazo') nav-link active @else nav-link @endif"  href="{{route('combustible.solicitudes.fuera.plazo')}}" >
                               <i class="fa-solid fa-clock-rotate-left"></i>
                              <span class="item-name">Solicitudes F.P.</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="@if (request()->route()->getName()==='combustible.solicitudes.crear') nav-link active @else nav-link @endif"  href="{{route('combustible.solicitudes.crear')}}" >
                               <i class="fa-solid fa-circle-plus"></i>
                              <span class="item-name">Crear solicitud</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="@if (request()->route()->getName()==='combustible.solicitudes.reportes') nav-link active @else nav-link @endif"  href="{{route('combustible.solicitudes.reportes')}}" >
                              <i class="fa-solid fa-clock"></i>
                              <span class="item-name">Config. Horario</span>
                            </a>
                        </li>
                    </ul>
                </li>   

                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#centro-incidencias" role="button" aria-expanded="false" aria-controls="centro-incidencias">
                        <i class="fa-solid fa-wrench"></i>
                        <span class="item-name">Incidencias</span>
                          <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                  
                    <ul class="sub-nav collapse" id="centro-incidencias" data-bs-parent="#centro-incidencias">
                        <li class="nav-item">
                            <a class="@if (request()->route()->getName()==='incidencias.fallas.reportadas') nav-link active @else nav-link @endif"  href="{{route('incidencias.fallas.reportadas')}}" >
                                <i class="fa-solid fa-car-battery"></i>
                              <span class="item-name">
                                Fallas reportadas.</span>
                            </a>
                        </li>
                         <li class="nav-item">
                            <a class="@if (request()->route()->getName()==='combustible.solicitudes.autorizadas') nav-link active @else nav-link @endif"  href="{{route('combustible.solicitudes.autorizadas')}}" >
                                <i class="fa-solid fa-screwdriver"></i>
                              <span class="item-name">Fallas Revisadas.</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#documentacion-vehicular" role="button" aria-expanded="false" aria-controls="documentacion-vehicular">
                        <i class="fa-solid fa-folder"></i>
                        <span class="item-name">Documentación</span>
                          <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                  
                    <ul class="sub-nav collapse" id="documentacion-vehicular" data-bs-parent="#documentacion-vehicular">
                        <li class="nav-item">
                            <a class="@if (request()->route()->getName()==='cargar.documentacion.vehicular') nav-link active @else nav-link @endif"  href="{{route('cargar.documentacion.vehicular')}}" >
                                <i class="fa-solid fa-upload"></i>
                              <span class="item-name">
                                Cargar documentos</span>
                            </a>
                        </li>
                         <li class="nav-item">
                            <a class="@if (request()->route()->getName()==='documentacion.visualizar') nav-link active @else nav-link @endif"  href="{{route('documentacion.visualizar')}}" >
                                <i class="fa-solid fa-file"></i>
                              <span class="item-name">Documentacion</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li><hr class="hr-horizontal"></li>
                 <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="#" tabindex="-1">
                        <span class="default-icon">
                            Altas
                        </span>
                        <span class="mini-icon">
                            <i class="fa-solid fa-address-card"></i>
                        </span>
                    </a>
                </li>
                  <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#altas-obras" role="button" aria-expanded="false" aria-controls="altas-obras">
                        <i class="fa-solid fa-helmet-safety"></i>
                        <span class="item-name">obras</span>
                          <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                  
                    <ul class="sub-nav collapse" id="altas-obras" data-bs-parent="#altas-obras">
                        <li class="nav-item">
                            <a class="@if (request()->route()->getName()==='listar.obras') nav-link active @else nav-link @endif"  href="{{route('listar.obras')}}" >
                                <i class="fa-solid fa-map-location-dot"></i>
                              <span class="item-name">Visualizar obras</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="@if (request()->route()->getName()==='vista.alta_obras') nav-link active @else nav-link @endif"  href="{{route('vista.alta_obras')}}" >
                                <i class="fa-solid fa-plus"></i>
                              <span class="item-name">Agregar</span>
                            </a>
                        </li>
                    </ul>
                </li>
                 <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#altas-propietarios" role="button" aria-expanded="false" aria-controls="altas-propietarios">
                        <i class="fa-solid fa-user-shield"></i>
                        <span class="item-name">Propietario</span>
                          <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                  
                    <ul class="sub-nav collapse" id="altas-propietarios" data-bs-parent="#altas-propietarios">
                        <li class="nav-item">
                            <a class="@if (request()->route()->getName()==='listar.propietarios') nav-link active @else nav-link @endif"  href="{{route('listar.propietarios')}}" >
                              <i class="fa-solid fa-user-group"></i>
                              <span class="item-name">Visualizar propietarios</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="@if (request()->route()->getName()==='vista.crearPropietario') nav-link active @else nav-link @endif"  href="{{route('vista.crearPropietario')}}" >
                                <i class="fa-solid fa-plus"></i>
                              <span class="item-name">Agregar</span>
                            </a>
                        </li>
                    </ul>
                </li>
                

                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#altas-vehiculos" role="button" aria-expanded="false" aria-controls="altas-vehiculos">
                        <i class="fa-solid fa-car-rear"></i>
                        <span class="item-name">Vehiculos</span>
                          <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                  
                    <ul class="sub-nav collapse" id="altas-vehiculos" data-bs-parent="#altas-vehiculos">
                        <li class="nav-item">
                            <a class="@if (request()->route()->getName()==='listar.vehiculos') nav-link active @else nav-link @endif"  href="{{route('listar.vehiculos')}}" >
                               <i class="fa-solid fa-road"></i>
                              <span class="item-name">Visualizar vehiculos</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="@if (request()->route()->getName()==='vista.alta_vehiculos') nav-link active @else nav-link @endif"  href="{{route('vista.alta_vehiculos')}}" >
                                <i class="fa-solid fa-plus"></i>
                              <span class="item-name">Agregar</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#altas-choferes" role="button" aria-expanded="false" aria-controls="altas-choferes">
                        <i class="fa-solid fa-id-card"></i>
                        <span class="item-name">Choferes</span>
                          <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                  
                    <ul class="sub-nav collapse" id="altas-choferes" data-bs-parent="#altas-choferes">
                        <li class="nav-item">
                            <a class="@if (request()->route()->getName()==='choferes.index') nav-link active @else nav-link @endif"  href="{{route('choferes.index')}}" >
                               <i class="fa-solid fa-users"></i>
                              <span class="item-name">Choferes</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="@if (request()->route()->getName()==='choferes.create') nav-link active @else nav-link @endif"  href="{{route('choferes.create')}}" >
                                <i class="fa-solid fa-plus"></i>
                              <span class="item-name">Agregar</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#altas-usuarios" role="button" aria-expanded="false" aria-controls="altas-usuarios">
                        <i class="fa-solid fa-user"></i>
                        <span class="item-name">Usuario & Roles</span>
                          <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                  
                    <ul class="sub-nav collapse" id="altas-usuarios" data-bs-parent="#altas-usuarios">
                        <li class="nav-item">
                            <a class="@if (request()->route()->getName()==='visualizar.listado.usuarios') nav-link active @else nav-link @endif"  href="{{route('visualizar.listado.usuarios')}}" >
                              <i class="fa-solid fa-user-plus"></i>
                              <span class="item-name">Usuarios</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="@if (request()->route()->getName()==='choferes.create') nav-link active @else nav-link @endif"  href="{{route('choferes.create')}}" >
                                <i class="fa-solid fa-lock-open"></i>
                              <span class="item-name">Permisos</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="sidebar-footer"></div>
</aside> 