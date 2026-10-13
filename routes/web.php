<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\CorteCajeController;
use App\Http\Controllers\RemisionesController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\GenerarReportesController;
use App\Http\Controllers\ControlVehicularController;
use App\Http\Controllers\CostosController;
use App\Http\Controllers\MiPerfilController;
use App\Http\Controllers\TiendavsventasController;
use App\Http\Controllers\CotizacionesHerrajesController;
use App\Http\Controllers\ControlInventarioController;
use App\Http\Controllers\ObrasController;
use App\Http\Controllers\VehiculosController;
use App\Http\Controllers\PropietarioController;
use App\Http\Controllers\ChoferesController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\IncidenciasController;
use App\Http\Controllers\SolicitudesCombustibleController;
use App\Http\Controllers\DocumentacionDigitalController;

Route::get('', function () {
    return redirect('login');
});

Route::get('/dashboard', [InicioController::class, 'vistapanel'])->name('vista.panel');
/*ruta para la vista de exportar corte de caja */
Route::get('/grupoindustrialfronterasur/exportar-corte-caja', [CorteCajeController::class, 'vista_corte_caja'])->name('exportar.caja');

/*RUTA QUE CARGA LOS CORTE DE CAJA A LA DB */
Route::post('/cargar-datos-excel-corte-caja/{nombre}', [CorteCajeController::class, 'subirdatos']);
/*PARA RECUPERAR DATOS DEL USUARIO LOGUEADO */
Route::get('/recuperar-usuario-logueado', [CorteCajeController::class, 'datosusuariologueado']);
/*RUTA PARA LA VISTGA DE  EXPORTAR LAS REMISIONES */
Route::get('/grupoindustrialfronterasur/exportar-remisiones', [RemisionesController::class,'subiremisiones'])->name('exportar.remisiones');

/*RUTA PARA CARGAR LAS REMISIONES A LA BASE DE DATOS */
Route::post('/cargar-datos-excel-remisiones/{nombre}', [RemisionesController::class, 'cargarremisiones']);

/*********************************************************************************************************************************************************/
/*RUTAS PARA RECUPERAR DATOS PARA LA VISTA PRINCIPAL DEL PANEL  */
Route::get('/recupera-metodo-de-pago', [PanelController::class, 'metodopago']);
Route::get('/recupera-vendedores-mes-actual', [PanelController::class, 'vendedorMes']);
Route::get('/ventas-dias-habiles-del-mes/{nombre}', [PanelController::class, 'ventasdiashabiles']);
Route::get('/grafica-default-ventas-dias-habiles', [PanelController::class, 'ventasdiashabilesdefault']);
Route::get('/recupera-sucursales',[PanelController::class, 'recuperarsucursal']);
Route::get('/recupera-top5/{nombre}', [PanelController::class, 'top5mes']);
Route::get('/recupera-meta-de-venta-teran',[PanelController::class,'metadeventateran']);
Route::get('/busca-remisiones-con-saldo-pendiente/{nombre}', [PanelController::class,'remisionesconsaldopendiente']);
Route::get('/tabla-dafult-remisiones',[PanelController::class, 'tabladefaultremisiones']);
Route::get('/grafica-default-top-5-mes',[PanelController::class,'top5mesDefault']);
Route::get('/remisiones-canceladas-default',[PanelController::class,'remisionescanceladasdefault']);
Route::get('/busca-remisiones-canceldas-por-tienda/{nombre}', [PanelController::class,'portiendaremisionescanceladas']);
Route::get('/proyeccion-cierre-mes',[PanelController::class,'proyeccionCierreMes']);

/*END RUTAS PARA RECUPERAR DATOS PARA LA VISTA PRINCIPAL DEL PANEL  */

/*********************************************************************************************************************************************************/

Route::get('/login',[UsuariosController::class, 'vistalogin'])->name('login');
Route::get('/validar-login',[UsuariosController::class,'validarlogin'])->name('validar.login');


Route::get('/cerrar-sesion', function () {
    Auth::logout(); // Cerrar la sesión

    return redirect('/login'); // Redirigir a la vista principal
})->name('logout');

/*********************************************************************************************************************************************************/

/* RUTAS PARA GENERAR LOS REPORTES   PDF BASE A FECHAS */

Route::get('/grupoindustrialfronterasur/generar-reportes',[GenerarReportesController::class,'vistaprincipalreportes'])->name('vista.reportes');

Route::get('/sucursales-selector',[GenerarReportesController::class,'selectorTienda'])->name('recupera.sucursal');

Route::get('/generar-pdf-por-tienda/{nombre_interno}/{fecha_i}/{fecha_f}',[GenerarReportesController::class, 'recuperadatospdf']);

Route::get('/productos-mas-vendidos/{nombre_interno}',[GenerarReportesController::class, 'productosMasVendidos'])->name('productos.mas.vendidos');

Route::get('/grupoindustrialfronterasur/generar-reportes-comisiones',[GenerarReportesController::class,'vistaprincipalcomisiones'])->name('vista.reportes.comisiones');

/*END RUTAS PARA GENERAR LOS REPORTES PDF BASE A FECHAS  */

/*********************************************************************************************************************************************************/

/*********************************************************************************************************************************************************/

/* RUTAS PARA EL CONTROL VEHICULAR */

Route::get('/exportar-rutas-de-viajes',[ControlVehicularController::class,'cargar_rutas'])->name('cargar.cargar_rutas');

Route::get('/recuperar-vehiculo',[ControlVehicularController::class,'selectorVehiculo'])->name('recupera.vehiculo');

Route::post('/guardar-rutas-vehiculos/{vehiculo}',[ControlVehicularController::class,'guardarRutasVehiculos'])->name('guardar.viajes');

Route::get('/genera-reporte-rutas-de-viajes',[ControlVehicularController::class,'vistaReporteRutas'])->name('vista.reporte_rutas');

Route::get('/mostrar-kml',[ControlVehicularController::class,'mostrarKml'])->name('mostrar.kml');

Route::get('/recupera-datos-de-rutas/{vehiculo}/{fecha_i}/{fecha_f}/{id_vehiculo}', [ControlVehicularController::class,'generaReporteRuta']);

/*ESTA VISTA DONDE SE PODRA VISUALIZAR EL MAPA DEL RECORRIDO COMPLETO  AL
SCANEAR UN CÓDIGO QR DE UN VEHÍCULO
*/
Route::get('/view/gps/ruta/{encodedParams}', [ControlVehicularController::class, 'vistaRecorridoCodificado'])->name('gps.vista');

Route::get('/view/datos/recuperados/qr', [ControlVehicularController::class, 'recuperaDatosQr'])->name('gps.datos.json');

/*END RUTAS PARA EL CONTROL VEHICULAR */

/*********************************************************************************************************************************************************/

Route::post('/subir-xml', [ControlVehicularController::class, 'upload'])->name('xml.upload');


/******************************* ************************************ */
/* RUTAS PARA CARGAR UTILIDADES DE LOS PRODUCTOS A LA BASE DE DATOS */
Route::get('/grupoindustrialfronterasur/cargar-movimientos', [CostosController::class, 'cargarUtilidades'])->name('cargar.utilidades');
Route::post('/subir-datos-utilidades/{sucursal}', [CostosController::class, 'subirUtilidades'])->name('subir.utilidades');

Route::get('/grupoindustrialfronterasur/generar-reportes-utilidades', [CostosController::class, 'VistacalculaUtilidades'])->name('calcula.utilidades');

Route::get('/recupera-datos-utilidades/{sucursal}/{fecha_inicio}/{fecha_final}', [CostosController::class, 'recuperaUtilidades']);


Route::get('/grupoindustrialfronterasur/reporte-cotizaciones-vs-ventas', [GenerarReportesController::class, 'vistaCotizacionesvsVentas'])->name('vista.cotizaciones_vs_ventas');

Route::get('/generar-analisis-cotizaciones-vs-ventas/{id}/{nombre_interno}/{fecha_i}/{fecha_f}', [GenerarReportesController::class, 'generarAnalisisCotizacionesvsVentas']);

/******************************************************************* */


/**********************************************************************/

Route::get('/grupoindustrialfronterasur/mi-perfil', [MiPerfilController::class, 'vistaMiPerfil'])->name('mi.perfil');


Route::get('/recupera-usuario-logueado',[MiPerfilController::class,'datosUsuarioLogueado'])->name('datos.usuario.logueado');

Route::post('/actualizar-mis-datos-perfil',[MiPerfilController::class,'updateMiPerfil']);

Route::post('/guardar-foto-galeria', [MiPerfilController::class, 'guardarFotoGaleria']);

Route::get('/recupera-fotos-galeria-usuario', [MiPerfilController::class, 'recuperarFotosGaleria'])->name('recupera.fotos.galeria.usuario');

Route::post('/crear-tarea', [MiPerfilController::class, 'crearTarea'])->name('crear.tarea');  

/*CRUD TAREAS */
Route::delete('/eliminar-tarea/{id}', [MiPerfilController::class, 'eliminarTarea'])->name('eliminar.tarea');
Route::post('/cambiar-estado-tarea/{id}', [MiPerfilController::class, 'cambiarEstadoTarea'])->name('cambiar.estado.tarea');
Route::post('/update-tarea/{id}', [MiPerfilController::class, 'updateTarea'])->name('editar.tarea');
Route::get('/tarea-archivos/{id}', function ($id) {
    return DB::table('tarea_archivos')
        ->where('tarea_id_archivo', $id)
        ->get();
});
/*END CRU TAREAS*/

Route::post('/asignar-usuarios-tarea', [MiPerfilController::class, 'asignarUsuariosATarea'])->name('asignar.usuarios.tarea');

Route::delete('/quitar-usuario-tarea/{id_user_task}/{id_task}', [MiPerfilController::class, 'quitarUsuarioTarea']);

/*RUTA QUE NOS AYUDA EN LAS NOTIFICACIONES DE ASIGNACION DE TAREAS */
Route::middleware('auth')->group(function () {
    Route::get('/notificaciones/json', function () {
        return response()->json(auth()->user()->unreadNotifications);
    })->name('notificaciones.json');

    Route::post('/notificaciones/{id}/leer', function ($id) {
        $notificacion = Auth::user()->notifications()->findOrFail($id);
        $notificacion->markAsRead();
        return response()->json(['ok' => true]);
    })->name('notificaciones.leer');

    Route::get('/notificaciones/contador', function () {
        return response()->json(['contador' => auth()->user()->unreadNotifications()->count()]);
    });

    Route::get('/notificaciones/lista', function () {
        return response()->json(['notificaciones' => auth()->user()->notifications()->get()]);
    })->name('notificaciones.lista');
});

/*END RUTA QUE NOS AYUDA EN LAS NOTIFICACIONES DE ASIGNACION DE TAREAS */
/******************************************************************** */

Route::get('/grupoindustrialfronterasur/tienda-vs-ventas', [TiendavsventasController::class, 'tiendasvsventas'])->name('vista.tienda_vs_ventas');

/*funcion que recupera todas las tiendas */
Route::get('/sucursales-destinos',[TiendavsventasController::class, 'recuperaTiendas'])->name('recupera.sucursales.destinos');

/*manda a traer el rango de fechas de ventas en dias habiles */
Route::get('/recupera-todos-los-dias-ventas/{fecha_inicio}/{fecha_final}/{teran}/{quinta}', [TiendavsventasController::class, 'recuperaDiasVentas']);


/********************************************************************* */

Route::get('/grupoindustrialfronterasur/cotizaciones-herrajes', [CotizacionesHerrajesController::class, 'vista_cotizaciones_herrajes'])->name('vista.cotizaciones_herrajes');

Route::post('/cotizaciones/extraer', [CotizacionesHerrajesController::class, 'extraer'])->name('cotizaciones.extraer');
Route::post('/cotizaciones/guardar', [CotizacionesHerrajesController::class, 'guardar'])->name('cotizaciones.guardar');
Route::post('/cotizaciones/reprocesar', [CotizacionesHerrajesController::class, 'reprocesarProductos'])->name('cotizaciones.reprocesar');

Route::post('/cotizacion/guardar-datos-generales', [CotizacionesHerrajesController::class, 'guardarDatosGenerales'])->name('cotizacion.guardar.datos.generales');
Route::post('/cotizaciones/verificar-presupuesto', [CotizacionesHerrajesController::class, 'verificarPresupuesto'])->name('cotizaciones.verificar.presupuesto.cotizaciones');

Route::post('/cotizacion/guardar-productos', [CotizacionesHerrajesController::class, 'guardarProductos'])->name('cotizaciones.guardar.productos');

/*ruta subir inventario */
Route::get('/grupoindustrialfronterasur/subir-inventario', [CotizacionesHerrajesController::class, 'vista_subir_inventario'])->name('vista.subir_inventario');

Route::post('/subir-inventario-tiendas', [CotizacionesHerrajesController::class, 'subirInventarioTiendas'])->name('subir.inventario.tiendas');

Route::get('/grupoindustrialfronterasur/pre-autorizar-cotizaciones', [CotizacionesHerrajesController::class, 'vistaPreAutorizacionCotizacion'])->name('vista.pre_autorizacion_cotizacion');

Route::get('/grupoindustrialfronterasur/autorizar-cotizaciones', [CotizacionesHerrajesController::class, 'vistaAutorizacionCotizacion'])->name('vista.autorizacion_cotizacion');

Route::get('/recupera-cotizaciones-vigente',[CotizacionesHerrajesController::class, 'recuperaTodasCotizacion']);

Route::get('/buscar-cotizacion/{id_presupuesto}',[CotizacionesHerrajesController::class, 'buscaProductos']);

Route::post('/cruzar-datos-para-autorizar-cotizacion/{id_presupuesto}',[CotizacionesHerrajesController::class,'cruzarDatos']);

Route::post('/enviar-pdf', [CotizacionesHerrajesController::class, 'enviarPDF']);

Route::get('/ultima-carga-de-inventario',[CotizacionesHerrajesController::class,'ultimaVezInventario']);

Route::post('/eliminar-inventario-temporal',[CotizacionesHerrajesController::class, 'eliminarInventarioTemporal']);

Route::get('/actualizar-producto-pre-autorizacion/{id_product_cotizacion}',[CotizacionesHerrajesController::class,'actualizarProductoPreAutorizacion']);

Route::delete('/eliminar-productos-de-cotizacion/{id_product_cotizacion}',[CotizacionesHerrajesController::class,'deleteProductoCotizacion']);

/********************************************************************* */
/*****************************INVENTARIO RUTS*************************** */
Route::get('/grupoindustrialfronterasur/grupo-familia-costos',[ControlInventarioController::class,'vistaMayorFamilia'])->name('vista.mayorfamilia');


Route::get('/reporte-productos-no-vendidos/{nombre_interno}/{fecha_inicio}/{fecha_final}/{idSucursal}',[ControlInventarioController::class,'ProducNoVendidos']);

Route::get('/analisis-movimiento-productos/{nombre_interno}/{fecha_inicio}/{fecha_final}/{idSucursal}',[ControlInventarioController::class,'AnalisisMovimientoProductos']);

Route::get('/familias-con-mayor-costo/{nombre_interno}/{fecha_inicio}/{fecha_final}/{idSucursal}',[ControlInventarioController::class,'FamiliasMayorCosto']);

Route::get('/productos-mas-vendidos-por-familia/{nombre_interno}/{fecha_inicio}/{fecha_final}/{idSucursal}',[ControlInventarioController::class,'ProductosMasVendidosPorFamilia']);

/********************************************************************* */
Route::get('/recupera-cotizaciones-autorizadas',[CotizacionesHerrajesController::class,'cotizacionAutorizada']);

Route::post('/foliar-cotizacion', [CotizacionesHerrajesController::class,'foliarCotizacion'])->name('foliar.cotizacion');

Route::get('/verifica-si-hay-cotizacion-foliada/{id_cotizacion}', [CotizacionesHerrajesController::class,'verificaFoliacion'])->name('verifica.cotizacion.foliada');

///vista para descargar el pdf de la cotizacion foliada
Route::get('/grupoindustrialfronterasur/descargar-pdf-foliado', [CotizacionesHerrajesController::class,'vistaDescargarPDFFoliado'])->name('vista.descargar_pdf_foliado');

//ruta de presupuesto foliado
Route::get('/cotizaciones-foliadas-true',[CotizacionesHerrajesController::class,'recuperarCotizacionesFoliadas']);

//ruta para consultar los datos del pdf
Route::get('/generar-pdf-cotizaciones-autorizadas/{ids}', [CotizacionesHerrajesController::class, 'generarPDFAutorizados']);

//ruta quitar folio de cotizacion
Route::delete('/quitar-folio-cotizacion/{id}', [CotizacionesHerrajesController::class, 'quitarFolioCotizacion']);

/*ver pdf ya creado de la tabla datos_cruzados */
Route::get('/ver-pdf-cotizacion/{id}', [CotizacionesHerrajesController::class,'verPDFCotizacion']);



Route::get('/comparador', [CotizacionesHerrajesController::class, 'comparar1'])->name('cotizaciones.comparar');




//DATO PANEL COTIZACOINES
Route::get('/ff',[CotizacionesHerrajesController::class,'sat'])->name('vigencia.status.sat');

Route::get('/salud/financiera',[PanelController::class,'getReporteDirectivo'])->name('reporte.financiero');


//*********     RUTAS PARA EL CONTRO DIRECTO DE VEHICULOS DE OBRAS   ********//
Route::get('/dashboard/control-vehicular',[ControlVehicularController::class,'dashboardVehiculos'])->name('vista.control_vehicular_obras');

//*********     RUTAS PARA DAR DE ALTA LAS OBRAS   ********//
Route::get('/obras/listar', [ObrasController::class, 'listarObras'])->name('listar.obras');
Route::get('/obras/recuperar/obras-listadas',[ObrasController::class,'recuperarObrasListadas'])->name('recuperar.obras.listadas');
Route::get('/obras/crear',[ObrasController::class,'createObra'])->name('vista.alta_obras');
Route::post('/guardar-obra',[ObrasController::class,'guardarObra'])->name('guardar.obra');
Route::get('/obras/datos/{id}', [ObrasController::class, 'storeObra'])->name('recuperar.obra');
Route::post('/obras/actualizar/{id}', [ObrasController::class, 'updateObra'])->name('actualizar.obra');

//*********     RUTAS PARA DAR DE ALTA LOS PROPIETARIOS  
//  
//********//
Route::get('/propietarios/listar', [PropietarioController::class, 'listaPropietarios'])->name('listar.propietarios');
Route::get('/propietarios/crear', [PropietarioController::class,'createPropietario'])->name('vista.crearPropietario');
Route::post('/crear-propietario', [PropietarioController::class,'crear'])->name('guardar.propietario');
Route::get('/propietarios/recuperar',[PropietarioController::class,'recuperaPropietarios'])->name('recuperar.propietarios');

//*********     RUTAS PARA DAR DE ALTA LOS VEHICULOS   ********//
Route::get('/vehiculos/listar', [VehiculosController::class, 'listarVehiculos'])->name('listar.vehiculos');
Route::get('/vehiculos/crear',[VehiculosController::class,'createVehiculo'])->name('vista.alta_vehiculos');
Route::post('/vehiculos/guardar', [VehiculosController::class, 'guardar'])->name('vehiculos.guardar');
Route::get('/vehiculos/recuperarvehiculos',[VehiculosController::Class,'vehiculosRegistrados'])->name('vehiculos.recuperar');


//*********************************************************************/



Route::get('/vehiculos/recuperarvehiculos', [VehiculosController::class, 'vehiculosRegistrados']);
Route::put('/vehiculos/actualizar/{id}',     [VehiculosController::class, 'actualizar']);
Route::delete('/vehiculos/eliminar/{id}',    [VehiculosController::class, 'eliminar']);
Route::post('/vehiculos/quitar-obra/{id}',   [VehiculosController::class, 'quitarObra']);
Route::post('/vehiculos/quitar-chofer/{id}', [VehiculosController::class, 'quitarChofer']);


//*********     RUTAS PARA DAR DE ALTA LOS CHOFERES   ********//
Route::get('/choferes/registrar',[ChoferesController::class,'vistaRegistrar'])->name('choferes.create');
Route::get('/choferes/index',[ChoferesController::class,'index'])->name('choferes.index');

//updatye datos
Route::post('/choferes/actualizar', [ChoferesController::class,'update'])->name('choferes.update');
//recuperar choferes
Route::get('/choferes/recuperar/nombre', [ChoferesController::class,'recuperarChoferes'])->name('choferes.recuperar');
//eliminar ch

//panel chofer
Route::get('/choferes/dashboard',[ChoferesController::class, 'dashobarChofer'])->name('choferes.dashboard');

//ruta para cuando estemos acutalizando el sistema el chofer no puedo manipular la vista, si no presesentar un mensaje de que el sistema esta en mantenimiento
Route::get('/choferes/dashboard/mantenimiento', [ChoferesController::class, 'vistaMantenimiento'])->name('choferes.dashboard.mantenimiento');


Route::get('/choferes/checkin/{tipo}', [BitacoraController::class, 'index'])->name('chofer.checkin');

Route::post('/checkin/guardar/{tipoId}', [BitacoraController::class, 'storeCheckIn'])->name('chofer.guardar');

//PANEL INCIDENCIAS
Route::get('/choferes/incidencias/reportar', [IncidenciasController::class, 'index'])->name('choferes.reportar_incidencia');
Route::post('/incidencia/guardar', [IncidenciasController::class, 'store'])->name('incidencia.store');
Route::get('/choferes/recuperar-incidencias', [IncidenciasController::class, 'getIncidencias'])->name('choferes.recuperar_incidencias');

//VISTA SOLICITUD DE COMBUSTIBLE
Route::get('/choferes/combustible/solicitar',[SolicitudesCombustibleController::class, 'index'])->name('choferes.solicitud_combustible');
Route::post('/choferes/combustible/guardar/solicitud/{estatus}/{metodo}',[SolicitudesCombustibleController::class, 'store'])->name('combustible.guardar');
//historial de solicitudes de combustible
Route::get('/choferes/combustible/historial',[SolicitudesCombustibleController::class, 'vistaHistorialCombustible'])->name('choferes.historial_combustible');
//para enviar peticion de autorizacion
Route::post('/combustible/peticion-permiso',[SolicitudesCombustibleController::class,'autorizarPeticionRetardoCombustible']);
//rua para visualizar los estados de las solicitudes de combustible
Route::get('/choferes/combustible/solicitudes',[SolicitudesCombustibleController::class, 'index'])->name('choferes.estatus_combustible');
Route::get('/choferes/combustible/recuperar-solicitudes',[SolicitudesCombustibleController::class, 'historialCombustible'])->name('choferes.recuperar_solicitudes_combustible');

//ruta al panel del administrador donde este recibira las solicitudes de combustible para su autorizacion o rechazo
Route::get('/combustible/solicitudes/pendientes', [SolicitudesCombustibleController::class, 'viewSolicitudesPendientes'])->name('combustible.solicitudes.pendientes');
Route::get('/combustible/solicitudes/autorizadas',[SolicitudesCombustibleController::class, 'vistaAutorizados'])->name('combustible.solicitudes.autorizadas');
Route::get('/combustible/solicitudes/recuperar', [SolicitudesCombustibleController::class, 'solicitudesCombustible'])->name('combustible.solicitudes.recuperar');
Route::get('/combustible/solicitudes/recuperar/autorizados',[SolicitudesCombustibleController::class,'recuperarAutorizados'])->name('combustible.recuperarAutorizados');
Route::get('/combustible/solicitudes/fuera-plazo',[SolicitudesCombustibleController::class, 'solicitudesFueraPlazo'])->name('combustible.solicitudes.fuera.plazo');
Route::post('/combustible/solicitudes/fuera-plazo/solicitar/autorizacion',[SolicitudesCombustibleController::class, 'solicitarAutorizacionFueraPlazo'])->name('combustible.solicitudes.fuera.plazo.solicitar.autorizacion');
//recuperar solicituded fuera de plazo
Route::get('/combustible/solicitudes/fuera-plazo/recuperar',[SolicitudesCombustibleController::class,'recuperarSolicitudesFueraPlazo']);
//mandamos activart el boton al chofer
Route::post('/combustible/solicitudes/fuera-plazo/habilitar',[SolicitudesCombustibleController::class,'habilitarBotonChofer'])->name('combustible.solicitudes.fuera.plazo.habilitar');
//auotizar solicitudes de combustible
Route::post('/combustible/solicitudes/autorizar/{id}/{id_chofer}', [SolicitudesCombustibleController::class, 'autorizarSolicitud'])->name('combustible.solicitudes.autorizar');
//rechazr spñoicitud
Route::post('/combustible/solicitudes/rechazar/{id_solicitud}/{id_chofer}/{usuarios_id}',[SolicitudesCombustibleController::class,'rechazarSolicitud']);


Route::post('/combustible/solicitudes/update',[SolicitudesCombustibleController::class,'updateSolicitud']);

////INCIDENCIAS
Route::get('/incidencias/fallas/reportadas',[IncidenciasController::class,'vistaIncidencias'])->name('incidencias.fallas.reportadas');
Route::get('/incidencias/fallas/reportadas/all-fallas',[IncidenciasController::class,'solicitudesIncidencias']);
//VERIFICAR EL STATUS DEL REPORTE 
Route::get('/incidencias/detalle/{id}',[IncidenciasController::class,'statusGet']);
//cambiando estatus de la incidencia
Route::post('/incidencias/cambiar/estatus/{id}/{chofer_id}',[IncidenciasController::class,'cambiarEstatus']);
//MANDANDO A GUARDAR EL CAMPO DE PFD O IMAGEN
Route::post('/incidencias/guardar/cotizacion',[IncidenciasController::class,'guardarCotizacion'])->name('choferes.guardar_cotizacion');
//AUTORIZAR QUE ELÑ CHOFER LO LLEVE AL TALLER
Route::post('/incidencias/autorizar-cotizacion/{id}',[IncidenciasController::class, 'autorizarCotizacion']);
//aca terminando el ciclo de los vehiculos
Route::post('/incidencias/fallas/vehiculo/reparado',[IncidenciasController::class,'cerrarCiclos']);



// *********************** RUTAS PARA LA DOCUMNTACION DIGITAL **************
Route::get('/choferes/documentacion', [DocumentacionDigitalController::class, 'vistaDocumentacion'])->name('choferes.vista.documentacion');
//route para cargar documentos a la db
Route::get('/documentacion/cargar/vehicular',[DocumentacionDigitalController::class, 'vistaCargaDocumentos'])->name('cargar.documentacion.vehicular');
Route::post('/subir/documentacion/vehicular/{id}',[DocumentacionDigitalController::class, 'subirDocumentos'])->name('subir.documentacion.vehicular');
//funcion para recuperar los documentos
Route::get('/recuperar/documentacion/vehicular',[DocumentacionDigitalController::class, 'recuperarDocumentos'])->name('recuperar.documentacion.vehicular');
//recuperar todo los documentos para hacer crud
Route::get('/documentacion/visualizar',[DocumentacionDigitalController::class,'vistaDocumentacionVisualizar'])->name('documentacion.visualizar');
Route::get('/documentacion/recuperar/admin',[DocumentacionDigitalController::class,'recuperaAllDocumentos']);

//crear solicitud extraordinaria
Route::get('/combustible/solicitudes/crear',[SolicitudesCombustibleController::class,'vistaCrearSolicitud'])->name('combustible.solicitudes.crear');
Route::post('/solicitudes/extraordinaria/guardar', [SolicitudesCombustibleController::class, 'guardarExtraordinaria'])->name('solicitudes.extraordinaria.guardar');
// *********************** USUARIOS **************
Route::get('/usuarios/listar',[UsuariosController::class,'vistaUsuarios'])->name('visualizar.listado.usuarios');
Route::get('/usuarios/recuperar/listado',[UsuariosController::class,'listarUsuarios']);
Route::post('/usuarios/cambiar/estatus/{id}/{estatus}',[UsuariosController::class,'cambiarEstatus']);
Route::post('/usuarios/actualizar',[UsuariosController::class,'update']);


Route::post('/usuarios/guardar', [UsuariosController::class, 'guardar']);
Route::post('/usuarios/actualizar', [UsuariosController::class, 'actualizar']);
Route::delete('/usuarios/eliminar/{id}', [UsuariosController::class, 'eliminar']);


//retomando para el controlador de coizacionesherrajes
//ruta para subir el xml de las cotizaciones
Route::get('/cotizaciones/conciliar/facturas/upload/xml',[CotizacionesHerrajesController::class,'vistaCotizacionesFacturadas'])->name('vista.cotizaciones_facturadas');

Route::get('/verifica/presupuesto/true/{presupuesto}', [CotizacionesHerrajesController::class, 'verificarPresupuestoExis'])->name('cotizaciones.verificar.presupuesto');

Route::post('/cotizaciones/facturadas/guardar/generales',[CotizacionesHerrajesController::class,'guardarDatosGeneralesFacturas'])->name('conciliacion.guardar.facturas.generales');
//guardamos los productos de lña factura
Route::post('/conciliacion/facturas/guardar/productos/{idcotizacion}',[CotizacionesHerrajesController::class,'guardarProductosFacturas']);
//vista para ver los presupuesto y ver cfuantas facturas cargada tiene de cada presupuesto
Route::get('/cotizaciones/conciliar/facturas/cruzar/presupuestos',[CotizacionesHerrajesController::class,'vistaPresupuestoConciliacion'])->name('vista.presupuesto_conciliacion');
Route::post('/conciliar/facturas/presupuesto/detalle',[CotizacionesHerrajesController::class,'detallePresupuestoConciliacion'])->name('conciliacion.buscar.presupuesto');
//aca recuperamos cada cotizacion que ya tierne su factura ligada
Route::get('/conciliacion/facturas/presupuesto/xml', [CotizacionesHerrajesController::class,'recuperaCotizacionConXml']);
//funcion para analizar partidas
Route::post('/conciliacion/facturas/analizar/partidas',[CotizacionesHerrajesController::class,'analizarPartidasFacturas'])->name('conciliacion.analizar.partidas');


Route::post('/conciliacion/facturas/guardar', [CotizacionesHerrajesController::class, 'guardarConciliacionFactura']);

//para cerrar el ciclo de la auditoria
Route::post('/conciliacion/presupuestos/cerrar/proceso/auditoria',[CotizacionesHerrajesController::class,'guardarMatch']);

//vista para generar reportes de las facturas
Route::get('/cotizaciones/conciliar/facturas/reportes',[CotizacionesHerrajesController::Class,'vistaReporteFactura'])->name('vista.conciliar.facturas.reporte');
//datos recuperados del reporte
Route::get('/cotizaciones/conciliar/facturas/reportes/get/datos',[CotizacionesHerrajesController::class,'auditoriaConciliaciones']);
//ruta dictamen final
Route::get('/cotizaciones/conciliar/reporte/dictamen/final',[CotizacionesHerrajesController::class,'obtenerDictamenInteligente']);

//cpara cargar el excel del consumos
Route::get('/gps/cargar/consumos', [ControlVehicularController::class,'cargarConsumos'])->name('cargar.consumos');
//para guardar los consumoa la db
Route::post('/guardar/consumos/efectivale',[ControlVehicularController::Class,'guardarConsumos']);

//ruta para la vista de generar reportes de dispercion
Route::get('/combustible/solicitudes/configurar/horario',[SolicitudesCombustibleController::class,'VistaConfigurarHorario'])->name('combustible.solicitudes.reportes');
//ruta para actualizar el rangp de horario
Route::post('/combustible/solicitudes/configurar/horario/actualizar',[SolicitudesCombustibleController::class,'actualizarHorario'])->name('combustible.solicitudes.reportes.actualizar');
//recuperar el dia y rango de horario
Route::get('/combustible/solicitudes/configurar/horario/recuperar',[SolicitudesCombustibleController::class,'recuperarHorario']);
//ruta para los reportes
Route::get('/combustible/solicitudes/reportes/datos',[SolicitudesCombustibleController::class,'datosReportes']);


//ruta mi perfil
Route::get('/choferes/perfil',[ChoferesController::Class,'miPerfil'])->name('choferes.perfil');
//recupera el datos del chofer logueado
Route::get('/choferes/perfil/datos/logueado',[ChoferesController::Class,'recuperarMisDatos']);

//aca vemos el reporte de pagos
Route::get('/cotizaciones/conciliar/reporte/pago/{fecha_inicio}/{fecha_final}',[CotizacionesHerrajesController::class,'reportePagos']);

//para actualizar el costo
Route::post('/utilidades/corregir-costo-remision/{sucursal}',[CostosController::class,'corregirCostoRemision']);
