<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\SolicitudCombustibleNotification;
use App\Models\Usuario; 
use Illuminate\Support\Facades\Notification;
use App\Models\Chofer;
use App\Notifications\AutCombustibleNotification;
use App\Notifications\SolicitudPlazoVencidoiNotification;
use App\Notifications\BotonHabilitadoNotification;
     

class SolicitudesCombustibleController extends Controller
{
    //vits para la solicitud de combustible
    public function index(){

        $chofer = Auth::guard('chofer')->user();
        $vehiculo = DB::table('vehiculos_panel')->where('id', $chofer->vehiculo_id)->first();

        //dd($vehiculo);

        return view('choferes.combustible.solicitudes', compact('chofer', 'vehiculo'));
    }
    /*
    public function store(Request $request){
        // recuperamos al chofer logueado
        $chofer = Auth::guard('chofer')->user();

        //consulta para obtener el vehículo del chofer
        $vehiculo = DB::table('vehiculos_panel')->where('id', $chofer->vehiculo_id)->first();
        //dd($vehiculo);

        //recuperando el id para enviarlo a guardar
        $vehiculo_id = $vehiculo->id;
        $obra_id = $vehiculo->obra_id; // Si el vehículo está asignado a una obra, lo asociamos a la solicitud

        $request->validate([
            'monto_solicitado' => 'required|numeric',
            'tipo_combustible' => 'required|string',
            'km_actual' => 'required|numeric',
            'notas_chofer' => 'nullable|string|max:255',
        ]);

        //enviando el estatus de la solicitud a pendiente, para que el admin pueda autorizar o rechazar
        DB::table('solicitudes_combustible')->insert([
            'chofer_id' => $chofer->id,
            'vehiculo_id' => $vehiculo_id,
            'obra_id' => $obra_id ?? null, // Si no hay obra asignada, dejamos null
            'monto_solicitado' => $request->monto_solicitado,
            'tipo_combustible' => $request->tipo_combustible,
            'km_actual' => $request->km_actual,
            'notas_chofer' => $request->notas_chofer,
            'estatus' => 'pendiente',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Solicitud de combustible enviada']);
    }
    */
    public function store(Request $request, $estatus_solicitud, $metodo) {
        $chofer = Auth::guard('chofer')->user();
        $vehiculo = DB::table('vehiculos_panel')->where('id', $chofer->vehiculo_id)->first();
    
        if (!$vehiculo) return response()->json(['error' => 'Vehículo no encontrado'], 404);
    
        $request->validate([
            'monto_solicitado' => 'required|numeric',
            'tipo_combustible' => 'required|string',
            'km_actual' => 'required|numeric',
            'notas_chofer' => 'nullable|string|max:255',
            'obras_complemento' => 'nullable|array', 
        ]);
    
        $obraPrincipalId = $vehiculo->obra_id;
        
  
        $obrasEnviadas = $request->obras_complemento ?? [];
    
        // Filtramos para no duplicar la obra que ya tiene el vehículo
        $obrasSinDuplicados = array_diff($obrasEnviadas, [$obraPrincipalId]);
    
        // Convertimos a string solo si hay obras extra
        $obrasComplementoString = !empty($obrasSinDuplicados) ? implode(',', $obrasSinDuplicados) : null;

        $dataInsert = [
            'chofer_id' => $chofer->id,
            'vehiculo_id' => $vehiculo->id,
            'obra_id' => $obraPrincipalId, 
            'obras_complemento' => $obrasComplementoString, 
            'monto_solicitado' => $request->monto_solicitado,
            'tipo_combustible' => $request->tipo_combustible,
            'km_actual' => $request->km_actual,
            'notas_chofer' => $request->notas_chofer,
            'estatus_solicitud'=>$estatus_solicitud,
            'metodo_recargado'=>$metodo,
            'estatus' => 'pendiente',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Insertamos en la BD
        DB::table('solicitudes_combustible')->insert($dataInsert);

        DB::table('permisos_botones')
            ->where('chofer_id', $chofer->id)
            ->where('estatus', 'APROBADO') // Buscamos el que el admin activó
            ->update([
                'estatus' => 'USADO',
                'updated_at' => now()
            ]);

        $admins = Usuario::where('tipo_rol', 1)->get();

        Notification::send($admins, new SolicitudCombustibleNotification($dataInsert, $chofer));

        return response()->json(['message' => 'Solicitud enviada y administrador notificado']);
    }
    
    /*
    public function historialCombustible(){
        $chofer = Auth::guard('chofer')->user();

        $solicitudes = DB::table('solicitudes_combustible')
            ->where('chofer_id', $chofer->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['solicitudes' => $solicitudes]);
    }
    */
    public function vistaHistorialCombustible(){
        return view('choferes.combustible.historial_combustible');
    }

    public function historialCombustible(){
        $chofer = Auth::guard('chofer')->user();

        $solicitudes = DB::table('solicitudes_combustible as sc')
            ->leftJoin('usuarios as u', 'sc.autorizador_id', '=', 'u.id')
            ->join('vehiculos_panel as vp', 'sc.vehiculo_id', '=', 'vp.id')
            ->select(
                'sc.*', 
                'u.nombre as nombre_admin', 
                'u.apellido_p as apellido_admin_p',
                'vp.marca', 
                'vp.linea'
            )
            ->where('sc.chofer_id', $chofer->id)
            ->orderBy('sc.created_at', 'desc')
            ->get();

        return response()->json(['solicitudes' => $solicitudes]);
    }
    //vista para las solicitudes pendientes de combustible, para que el admin pueda autorizar o rechazar
    public function viewSolicitudesPendientes(){

        return view('autorizaciones.combustible.solicitudes_pendientes');
    }

    //recuperando todas la solicitudes de combustible de cada chofer
    /*
    public function solicitudesCombustible(){
        $solicitudes = DB::table('solicitudes_combustible')
            ->join('choferes', 'solicitudes_combustible.chofer_id', '=', 'choferes.id')
            ->join('vehiculos_panel', 'solicitudes_combustible.vehiculo_id', '=', 'vehiculos_panel.id')->join('obras', 'vehiculos_panel.obra_id', '=', 'obras.id')
            ->select('solicitudes_combustible.*', 'choferes.nombre as nombre_chofer','choferes.apellido_p as apellido_p_chofer',  'vehiculos_panel.marca as marca_vehiculo', 'vehiculos_panel.linea as linea_vehiculo', 'vehiculos_panel.nombre_gps as nombre_gps_vehiculo', 'obras.nombre_obra as nombre_obra')
            ->where('solicitudes_combustible.estatus', 'pendiente')
            ->orderBy('solicitudes_combustible.created_at', 'desc')
            ->get();

        return response()->json(['solicitudes' => $solicitudes]);
    }
    */
    /*
    public function solicitudesCombustible(){
        $solicitudes = DB::table('solicitudes_combustible')
            ->join('choferes', 'solicitudes_combustible.chofer_id', '=', 'choferes.id')
            ->join('vehiculos_panel', 'solicitudes_combustible.vehiculo_id', '=', 'vehiculos_panel.id')
            ->join('obras', 'solicitudes_combustible.obra_id', '=', 'obras.id')
            // HACEMOS EL PUENTE CON LA TABLA ORIGINAL DE VEHICULOS (GPS)
            ->leftJoin('vehiculos', 'vehiculos_panel.nombre_gps', '=', 'vehiculos.nombre_in_vehiculo')
            ->select(
                'solicitudes_combustible.*', 
                'choferes.nombre as nombre_chofer',
                'choferes.apellido_p as apellido_p_chofer',
                'choferes.apellido_m as apellido_m_chofer',  
                'vehiculos_panel.marca as marca_vehiculo', 
                'vehiculos_panel.efecticard as efecticard_vehiculo',
                'vehiculos_panel.linea as linea_vehiculo', 
                'vehiculos_panel.modelo as modelo_vehiculo',
                'vehiculos_panel.nombre_gps as nombre_gps_vehiculo',
                'obras.nombre_obra as nombre_obra',
                'vehiculos.id as id_vehiculo_gps' // <--- id de la tabla de vehiculos para poder mostrar el analisis de combustible
            )
            ->where('solicitudes_combustible.estatus', 'pendiente')
            ->orderBy('solicitudes_combustible.created_at', 'desc')
            ->get();

            $user = Auth::user();
        
        return response()->json(['solicitudes' => $solicitudes, 'user' => $user]);
    }*/

        /*
    public function solicitudesCombustible(){

        $solicitudes = DB::table('solicitudes_combustible as sc')
            ->join('choferes as c', 'sc.chofer_id', '=', 'c.id')
            ->join('vehiculos_panel as vp', 'sc.vehiculo_id', '=', 'vp.id')
            ->join('obras as o', 'sc.obra_id', '=', 'o.id')
            ->leftJoin('vehiculos as v', 'vp.nombre_gps', '=', 'v.nombre_in_vehiculo')
            ->select(
                'sc.*', 
                'c.nombre as nombre_chofer', 'c.apellido_p as apellido_p_chofer', 'c.apellido_m as apellido_m_chofer',  
                'vp.marca as marca_vehiculo', 'vp.efecticard as efecticard_vehiculo', 
                'vp.linea as linea_vehiculo', 'vp.modelo as modelo_vehiculo',
                'vp.nombre_gps as nombre_gps_vehiculo',
                'o.nombre_obra as nombre_obra',
                'v.id as id_vehiculo_gps'
            )
            ->where('sc.estatus', 'pendiente')
            ->orderBy('sc.created_at', 'desc')
            ->get();

        // Inyectamos el último monto real cargado por cada vehículo
        foreach ($solicitudes as $solicitud) {
            $ultimoRegistro = DB::table('solicitudes_combustible')
                ->where('vehiculo_id', $solicitud->vehiculo_id)
                ->where('estatus', 'autorizado')
                ->whereNotNull('monto_real_cargado')
                ->orderBy('updated_at', 'desc')
                ->first();
            
            // Si existe, lo mandamos, si no, mandamos 0
            $solicitud->ultimo_monto_real = $ultimoRegistro ? $ultimoRegistro->monto_real_cargado : 0;
            $solicitud->fecha_ultima_carga = $ultimoRegistro ? $ultimoRegistro->updated_at : 'N/A';
        }

        return response()->json([
            'solicitudes' => $solicitudes, 
            'user' => Auth::user()
        ]);
    }
        */
    public function solicitudesCombustible(){

        $solicitudes = DB::table('solicitudes_combustible as sc')
            ->join('choferes as c', 'sc.chofer_id', '=', 'c.id')
            ->join('vehiculos_panel as vp', 'sc.vehiculo_id', '=', 'vp.id')
            ->join('obras as o', 'sc.obra_id', '=', 'o.id') 
            ->leftJoin('vehiculos as v', 'vp.nombre_gps', '=', 'v.nombre_in_vehiculo')
            ->select(
                'sc.*', 
                'c.nombre as nombre_chofer', 'c.apellido_p as apellido_p_chofer', 'c.apellido_m as apellido_m_chofer',  
                'vp.marca as marca_vehiculo', 'vp.efecticard as efecticard_vehiculo', 
                'vp.linea as linea_vehiculo', 'vp.modelo as modelo_vehiculo',
                'vp.nombre_gps as nombre_gps_vehiculo',
                'o.nombre_obra as nombre_obra_principal', 
                'v.id as id_vehiculo_gps'
            )
            ->where('sc.estatus', 'pendiente')
            ->orderBy('sc.created_at', 'desc')
            ->get();

        foreach ($solicitudes as $solicitud) {
            // --- PROCESAR NOMBRES DE TODAS LAS OBRAS ---
            $nombresFinales = [$solicitud->nombre_obra_principal]; 

            if (!empty($solicitud->obras_complemento)) {
                $idsComplemento = explode(',', $solicitud->obras_complemento);
                $nombresExtras = DB::table('obras')
                    ->whereIn('id', $idsComplemento)
                    ->pluck('nombre_obra')
                    ->toArray();
                
                // Combinamos los nombres de ley con los extras
                $nombresFinales = array_merge($nombresFinales, $nombresExtras);
            }

            $solicitud->nombre_obra = implode(', ', $nombresFinales);

            $ultimoRegistro = DB::table('solicitudes_combustible')
                ->where('vehiculo_id', $solicitud->vehiculo_id)
                ->where('estatus', 'autorizado')
                ->whereNotNull('monto_real_cargado')
                ->orderBy('updated_at', 'desc')
                ->first();
            
            $solicitud->ultimo_monto_real = $ultimoRegistro ? $ultimoRegistro->monto_real_cargado : 0;
            $solicitud->fecha_ultima_carga = $ultimoRegistro ? $ultimoRegistro->updated_at : 'N/A';
        }

        return response()->json([
            'solicitudes' => $solicitudes, 
            'user' => Auth::user()
        ]);
    }

    //function para autorizar la solicitud del combustible

    /*
    function autorizarSolicitud(Request $request, $id_solicitud){

        $request->validate([
            'autorizador_id' => 'required|numeric',
            'monto_final' => 'required|numeric',
            'folio_autorizacion' => 'required|string|max:255',
            'notas_admin'=> 'nullable|string|max:255',
        ]);

        $insert = [
            'estatus' => 'autorizado',
            'autorizador_id' => $request->autorizador_id,
            'monto_real_cargado' => $request->monto_final,
            'folio_autorizacion' => $request->folio_autorizacion,
            'notas_admin' => $request->notas_admin,
            'updated_at' => now(),
        ];

    
        //actualizamos el estatus de la solicitud a autorizado, y guardamos el monto final autorizado, el folio de autorizacion y las notas del admin
        DB::table('solicitudes_combustible')
            ->where('id', $id_solicitud)
            ->update($insert);

        return response()->json(['message' => 'Solicitud autorizada exitosamente']);   
    }
        */
   function autorizarSolicitud(Request $request, $id_solicitud, $id_chofer) {

        $request->validate([
            'autorizador_id' => 'required|numeric',
            'monto_final' => 'required|numeric',
            'folio_autorizacion' => 'required|string|max:255',
            'notas_admin'=> 'nullable|string|max:255',
        ]);



        $insert = [
            'estatus' => 'autorizado',
            'autorizador_id' => $request->autorizador_id,
            'monto_real_cargado' => $request->monto_final,
            'folio_autorizacion' => $request->folio_autorizacion,
            'notas_admin' => $request->notas_admin,
            'updated_at' => now(),
        ];

  
        DB::table('solicitudes_combustible')
            ->where('id', $id_solicitud)
            ->update($insert);

     
        $chofer = DB::table('choferes')->where('id', $id_chofer)->first(); 
        
        
        $solicitudData = $insert;
        $solicitudData['id'] = $id_solicitud; 
        $solicitudData['vehiculo_id'] = DB::table('solicitudes_combustible')->where('id', $id_solicitud)->value('vehiculo_id');

        //  Validamos y enviamos
        if ($chofer && !empty($chofer->correo)) {
           
            Notification::route('mail', $chofer->correo)
                        ->notify(new AutCombustibleNotification($solicitudData, $chofer));          
        }

        return response()->json(['message' => 'Solicitud autorizada exitosamente']);   
    }

    public function vistaAutorizados(){

        return view('autorizaciones.combustible.solicitudes_autorizadas');
    }
    /*

    public function recuperarAutorizados() {
        $autorizados = DB::table('solicitudes_combustible as sc')
            ->join('choferes as c', 'sc.chofer_id', '=', 'c.id')
            ->join('vehiculos_panel as vp', 'sc.vehiculo_id', '=', 'vp.id')
            ->join('obras as o', 'sc.obra_id', '=', 'o.id') 
            ->select(
                'sc.*', 
                'c.nombre as nombre_chofer', 'c.apellido_p as apellido_p_chofer',
                'vp.marca', 'vp.linea', 'vp.placas', 'vp.efecticard',
                'o.nombre_obra as nombre_obra_principal' 
            )
            ->where('sc.estatus', 'autorizado')
            ->orderBy('sc.updated_at', 'desc')
            ->get();

        // --- PROCESAMIENTO DE OBRAS COMPLEMENTARIAS ---
        foreach ($autorizados as $item) {
            $nombresTodasLasObras = [$item->nombre_obra_principal];

            if (!empty($item->obras_complemento)) {

                $idsExtras = explode(',', $item->obras_complemento);
                
                // Buscamos los nombres de esas obras extras en la DB
                $nombresExtras = DB::table('obras')
                    ->whereIn('id', $idsExtras)
                    ->pluck('nombre_obra')
                    ->toArray();
                
                // Mezclamos la principal con las extras
                $nombresTodasLasObras = array_merge($nombresTodasLasObras, $nombresExtras);
            }

            $item->nombre_obra = implode(', ', $nombresTodasLasObras);
        }

        return response()->json($autorizados);
    }
        */

    public function recuperarAutorizados() {
        $autorizados = DB::table('solicitudes_combustible as sc')
            ->leftJoin('choferes as c', 'sc.chofer_id', '=', 'c.id')
            ->leftJoin('vehiculos_panel as vp', 'sc.vehiculo_id', '=', 'vp.id')
            ->leftJoin('obras as o', 'sc.obra_id', '=', 'o.id') 
            ->select(
                'sc.*', 
                'c.nombre as nombre_chofer', 
                'c.apellido_p as apellido_p_chofer',
                'vp.marca', 
                'vp.linea', 
                'vp.placas', 
                'vp.efecticard',
                'o.nombre_obra as nombre_obra_principal' 
            )
            ->where('sc.estatus', 'autorizado')
            ->orderBy('sc.updated_at', 'desc')
            ->get();

        // --- PROCESAMIENTO DE OBRAS Y CAMPOS TEMPORALES ---
        foreach ($autorizados as $item) {
            
            // Validar Chofer (Si es NULL, es porque es extraordinaria)
            if (empty($item->chofer_id)) {
                $item->nombre_chofer = $item->chofer_temporal ?? 'ADMINISTRACIÓN';
                $item->apellido_p_chofer = '';
            }

            // Validar Vehículo
            if (empty($item->vehiculo_id)) {
                $item->marca = $item->vehiculo_temporal ?? 'S/N';
                $item->linea = '';
                $item->placas = 'EXTERNO';
                // Si el folio_autorizacion guarda el efectivale/vale, lo usamos si no hay tarjeta vinculada
                $item->efecticard = $item->efecticard ?? $item->folio_autorizacion; 
            }

            //  Validar Obras
            $nombrePrincipal = $item->nombre_obra_principal ?? $item->notas_admin;
            $nombresTodasLasObras = [$nombrePrincipal];

            if (!empty($item->obras_complemento)) {
                $idsExtras = explode(',', $item->obras_complemento);
                
                $nombresExtras = DB::table('obras')
                    ->whereIn('id', $idsExtras)
                    ->pluck('nombre_obra')
                    ->toArray();
                
                $nombresTodasLasObras = array_merge($nombresTodasLasObras, $nombresExtras);
            }

            $item->nombre_obra = implode(', ', $nombresTodasLasObras);
        }

        return response()->json($autorizados);
    }

    //procesamos las peticiones de combustibles extra-ordinarios
    public function autorizarPeticionRetardoCombustible(Request $request){
        
        $insert_botones = DB::table('permiso_botones')->insert(['chofer_id'=>request->id]);
    }

/*
    function autorizarSolicitud(Request $request, $id_solicitud) {

        $request->validate([
            'autorizador_id' => 'required|numeric',
            'monto_final' => 'required|numeric',
            'folio_autorizacion' => 'required|string|max:255',
            'notas_admin'=> 'nullable|string|max:255',
        ]);

        // 1. Actualizamos la solicitud
        DB::table('solicitudes_combustible')
            ->where('id', $id_solicitud)
            ->update([
                'estatus' => 'autorizado',
                'autorizador_id' => $request->autorizador_id,
                'monto_real_cargado' => $request->monto_final,
                'folio_autorizacion' => $request->folio_autorizacion,
                'notas_admin' => $request->notas_admin,
                'updated_at' => now(),
            ]);

        // 2. Recuperamos los datos necesarios para el correo
        $solicitud = DB::table('solicitudes_combustible')->where('id', $id_solicitud)->first();
        $chofer = \App\Models\Chofer::find($solicitud->chofer_id);

        // 3. Enviamos la notificación si el chofer tiene correo
        if ($chofer && $chofer->correo) {
            // Usamos routeNotificationForMail para asegurarnos que use la columna 'correo'
        Notification::route('mail', $chofer->correo)
                        ->notify(new AutCombustibleNotification($solicitud, $chofer));
                        
       
        }

        return response()->json(['message' => 'Solicitud autorizada exitosamente y notificación enviada.']);   
    }
        */

    //vista para las solicitudes de combustible de fuera de plazo
    public function solicitudesFueraPlazo(){

        return view('autorizaciones.combustible.solicitudes_fuera_plazo');
    }

    //guardamos la solicitud de fuera de plazo por chofer
    public function solicitarAutorizacionFueraPlazo(Request $request){
        $chofer = Auth::guard('chofer')->user();

        //dd($vehiculo);
        $request->validate([
            'chofer_id' => 'required|numeric',
            'motivo' => 'required|string|max:255',
        ]);

        //insertamos
        $dataInsert = [
             'chofer_id' => $request->chofer_id,
             'motivo_carga' => $request->motivo,
             'admin_id' => null, 
             'estatus' => 'pendiente',
             'created_at' => now(),
             'updated_at' => now(),
        ];
    
        DB::table('permisos_botones')->insert($dataInsert);
        //CREAMOS NOTIFACIONES PARA LOS ADMINISTRADRES
        $admins = Usuario::where('tipo_rol', 1)->get();

        Notification::send($admins, new SolicitudPlazoVencidoiNotification($dataInsert, $chofer));


        return response()->json(['message' => 'Solicitud enviada, en breve un administrador revisara tu solicitud y te avisaremos por (EMAIL) para que puedas solicitar el combustible.']);
    }

    //recuperamos las solicitudes de fuera de plazo para el admin
    public function recuperarSolicitudesFueraPlazo(){

        $solicitudes = DB::table('permisos_botones as pb')
            ->join('choferes as c', 'pb.chofer_id', '=', 'c.id')
            ->join('vehiculos_panel as vp', 'c.vehiculo_id', '=', 'vp.id')
            ->select('pb.*', 'c.nombre as nombre_chofer', 'c.apellido_p as apellido_p_chofer', 'c.apellido_m as apellido_m_chofer', 'vp.marca as marca_vehiculo', 'vp.linea as linea_vehiculo', 'vp.placas as placas_vehiculo')
            ->orderBy('pb.created_at', 'desc')
            ->get();

        return response()->json($solicitudes);
    }

    //mandamos a habilitar el boton al chofer para que pueda realizar su solicitud de combustible, cambiando el estatus del permiso en la tabla permisos_botones
    public function habilitarBotonChofer(Request $request){

        $request->validate([
            'id_permiso' => 'required|numeric',
            'id_chofer' => 'required|numeric',
        ]);

        DB::table('permisos_botones')
            ->where('id', $request->id_permiso)
            ->update([
                'habilitado' => true,
                'estatus' => 'APROBADO',
                'admin_id' => Auth::id(), 
                'updated_at' => now(),
            ]);
            // Notificamos al chofer que su botón ha sido habilitado
            $chofer = Chofer::find($request->id_chofer);
            if ($chofer && $chofer->correo) {
                Notification::route('mail', $chofer->correo)
                            ->notify(new BotonHabilitadoNotification($chofer));
            }

        return response()->json(['message' => 'Botón habilitado para el chofer, ya puede realizar su solicitud de combustible.']);
    }

    //para rechazar la solicitud
    public function rechazarSolicitud($id_solicitud, $id_chofer, $autorizador_id, Request $request){
        
        DB::table('solicitudes_combustible')
            ->where('id', $id_solicitud)
            ->update([
                'autorizador_id'=> $autorizador_id,
                'estatus' => 'rechazado',
                'notas_admin' => $request->notas_admin,
                'updated_at' => now(),
            ]);

        return response()->json(['message' => 'Solicitud rechazada exitosamente']);
    }

    //funcion para que el administrador pueda crear una solicitud extraordinaria
    public function vistaCrearSolicitud(){
        return view('autorizaciones.combustible.solicitudes_crear');
    }

    public function registrarSolicitudExtraordinaria(Request $request){
        $chofer = Auth::guard('chofer')->user();
        $vehiculo = DB::table('vehiculos_panel')->where('id', $chofer->vehiculo_id)->first();

        if (!$vehiculo) return response()->json(['error' => 'Vehículo no encontrado'], 404);

        $request->validate([
            'monto_solicitado' => 'required|numeric',
            'tipo_combustible' => 'required|string',
            'km_actual' => 'required|numeric',
            'notas_chofer' => 'nullable|string|max:255',
        ]);

        $dataInsert = [
            'chofer_id' => null,
            'vehiculo_id' => null,
            'obra_id' => null, 
            'monto_solicitado' => $request->monto_solicitado,
            'tipo_combustible' => $request->tipo_combustible,
            'km_actual' => null,
            'notas_chofer' => null,
            'estatus_solicitud'=>'extraordinaria',
            'estatus' => 'pendiente',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('solicitudes_combustible')->insert($dataInsert);

        return response()->json(['message' => 'Solicitud extraordinaria registrada exitosamente']);
    }

    public function guardarExtraordinaria(Request $request){
        $request->validate([
            'nombre_beneficiario' => 'required|string|max:150',
            'monto_solicitado'    => 'required|numeric|min:1',
            'efectivale'          => 'required|string|max:100',
            'tipo_combustible'    => 'required|string|in:DIESEL,MAGNA,PREMIUM',
            'notas_admin'         => 'required|string|max:1000',
            'vehiculo_temporal'   => 'nullable|string|max:100',
            'estatus_solicitud'   => 'required|string|in:EXTRA_ORDINARIA,A_TIEMPO',
            'metodo_recargado'    => 'required|string|in:reposicion_caja,regular',
            'fecha_registro'      => 'nullable|date'
        ]);

        try {
            // Determinar la fecha de creación (si especifica una o la fecha/hora actual)
            $fechaRegistro = $request->filled('fecha_registro') 
                ? \Carbon\Carbon::parse($request->fecha_registro)->setTimeFromTimeString(now()->toTimeString()) 
                : now();

            DB::table('solicitudes_combustible')->insert([
                'vehiculo_id'        => null,
                'chofer_id'          => null,
                'obra_id'            => null,
                'obras_complemento'  => null,
                'autorizador_id'     => Auth::id() ?? 1,
                
                'chofer_temporal'    => $request->nombre_beneficiario,
                'vehiculo_temporal'  => $request->vehiculo_temporal,
                
                'monto_solicitado'   => $request->monto_solicitado,
                'monto_real_cargado' => $request->monto_solicitado, 
                'tipo_combustible'   => $request->tipo_combustible,
                'folio_autorizacion' => $request->efectivale, 
                'km_actual'          => 0,
                
                'notas_admin'        => $request->notas_admin,
                'notas_chofer'       => "Registrado manualmente por el departamento de administración.",
                
                // Valores dinámicos según la casilla
                'estatus_solicitud'  => $request->estatus_solicitud,
                'metodo_recargado'   => $request->metodo_recargado, 
                'estatus'            => 'autorizado',
                
                'created_at'         => $fechaRegistro,
                'updated_at'         => now()
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Se ha generado la solicitud exitosamente.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Error en la base de datos: ' . $e->getMessage()
            ], 500);
        }
    }

    //vista solicitudes
    public function VistaConfigurarHorario(){
        return view('autorizaciones.combustible.configurar_horario');
    }

    //
    public function actualizarHorario(Request $request){
        $request->validate([
            'dia_semana'=> 'required|int',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
        ]);

        DB::table('horario_combustible')->update([
            'dia_semana' => $request->dia_semana,
            'horario_inicio' => $request->hora_inicio,
            'horario_finalizar' => $request->hora_fin,
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Horario actualizado exitosamente']);
        
    }

    //recuperar el horario de combustible
    public function recuperarHorario(){
        $horario = DB::table('horario_combustible')->first();
        return response()->json($horario);
    }

    //DATOS PARA RECUPERAR()
    public function datosReportes(){
        $vehiculos = DB::Table('vehiculos_panel as vp')->join('obras as o','vp.obra_id', '=','o.id')->orderBy('vp.id', 'asc')->get();
        return response()->json(['vehiculos'=>$vehiculos]);
    }

    public function updateSolicitud(Request $request){
        $id = $request->id;
        $request->validate([
            'monto_real_cargado'    => 'required|numeric|min:1',
            'tipo_combustible'      => 'required|string|in:diesel,magna,premium',
            'estatus_solicitud'     => 'required|string|in:EXTRA_ORDINARIA,A_TIEMPO',
            'metodo_recargado'      => 'required|string|in:reposicion_caja,regular',
            'nombre_obra'           => 'required|string',
            
        ]);

        DB::table('solicitudes_combustible')->where('id', $id)->update([
            'monto_real_cargado'    => $request->monto_real_cargado,
            'tipo_combustible'      => $request->tipo_combustible,
            'estatus_solicitud'     => $request->estatus_solicitud,
            'metodo_recargado'      => $request->metodo_recargado,
            'obras_complemento'           => $request->nombre_obra,
        ]);

        return response()->json(['message' => 'Datos actualizados exitosamente']);
    }

  

    

}
 