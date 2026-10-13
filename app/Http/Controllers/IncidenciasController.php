<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
//para notificar incidencias del vehiculo a mantenimiento
use App\Notifications\IncidenciasNotification;
use App\Notifications\SubirCotizacionIncidenciasNotification;
use App\Notifications\AdminCotizacionRecibidaNotification;
use App\Notifications\CotizacionAprobadaChoferNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\Usuario; 

class IncidenciasController extends Controller
{
    public function index() {

        return view('choferes.incidencias.reportar_incidencias');
    }

    /*public function store(Request $request) {
        $chofer = Auth::guard('chofer')->user();

        $request->validate([
            'titulo_reporte' => 'required|string|max:100',
            'descripcion' => 'required|string',
            'prioridad' => 'required|in:baja,media,alta,critica',
            'foto_evidencia' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $path = null;
        if ($request->hasFile('foto_evidencia')) {
            $path = $request->file('foto_evidencia')->store('incidencias', 'public');
        }

        DB::table('incidencias_vehiculares')->insert([
            'vehiculo_id' => $chofer->vehiculo_id,
            'chofer_id' => $chofer->id,
            'titulo_reporte' => $request->titulo_reporte,
            'descripcion' => $request->descripcion,
            'prioridad' => $request->prioridad,
            'foto_evidencia' => $path,
            'estatus_reporte' => 'abierto',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Reporte enviado a mantenimiento']);
    }*/
    public function store(Request $request) {
        $chofer = Auth::guard('chofer')->user();

        //  Procesar la foto comprimida (Base64)
        $fotoPath = null;
        if ($request->foto_base64) {
            $img = $request->foto_base64;
            $img = str_replace('data:image/jpeg;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            
            $fileName = 'incidencia_' . time() . '.jpg';
            $folderPath = public_path('uploads/incidencias/' . date('Y-m-d'));
            
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            file_put_contents($folderPath . '/' . $fileName, $data);
            $fotoPath = 'uploads/incidencias/' . date('Y-m-d') . '/' . $fileName;
        }

        $dataInsert = [
            'vehiculo_id' => $chofer->vehiculo_id,
            'chofer_id' => $chofer->id,
            'titulo_reporte' => $request->titulo_reporte,
            'descripcion' => $request->descripcion,
            'prioridad' => $request->prioridad,
            'foto_evidencia' => $fotoPath,
            'estatus_reporte' => 'abierto',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Insertar en la tabla
        DB::table('incidencias_vehiculares')->insert($dataInsert);

        //logica de notificacion o mantenimiento
        $admins = Usuario::where('tipo_rol', 1)->get();

        Notification::send($admins, new IncidenciasNotification($dataInsert, $chofer));
      

        return response()->json(['message' => 'Reporte recibido']);
    }

    //recuperar incidencias del chofer logueado 116
    public function getIncidencias() {
        $chofer = Auth::guard('chofer')->user();
        $incidencias = DB::table('incidencias_vehiculares')
            ->where('chofer_id', $chofer->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['incidencias' => $incidencias]);
    }

    //vista para que el administrador recupere todo los datos reportados por sus usuarios
    public function vistaIncidencias(){

        return view('autorizaciones.incidencias.fallas_reportadas');
    }

    //recuperar topda las incidencias
    public function solicitudesIncidencias(){

        $incidencias = DB::table('incidencias_vehiculares as iv')
        ->join('vehiculos_panel as vp', 'iv.vehiculo_id', '=', 'vp.id')
        ->join('choferes as ch', 'iv.chofer_id', '=', 'ch.id')
        ->select('iv.*','vp.marca as marca_vehiculo','vp.linea as linea_vehiculo','vp.modelo as modelo_vehiculo','ch.nombre as nombre_chofer', 'ch.apellido_p as apellido_p_chofer', 'ch.apellido_m as apellido_m_chofer', 'ch.foto_chofer as foto_chofer')
        ->orderBy('iv.created_at', 'desc')->get();

        return response()->json(['allincidencias'=>$incidencias]);
    }

    //revisar status
    public function statusGet($id){

        $revision = DB::table('incidencias_vehiculares')->where('id',$id)->get();

        return response()->json(['revision'=>$revision]);
    }

    // Cambiar estatus
    public function cambiarEstatus(Request $request, $id, $chofer_id) {
        DB::table('incidencias_vehiculares')
            ->where('id', $id)
            ->update([
                'estatus_reporte' => 'en_espera_cotizacion',
                'updated_at'      => now() 
            ]);


        $incidencia = DB::table('incidencias_vehiculares')->where('id', $id)->first();
        $chofer = DB::table('choferes')->where('id', $incidencia->chofer_id)->first();
        
        Notification::route('mail', $chofer->correo)->notify(new SubirCotizacionIncidenciasNotification($incidencia,$chofer));

        return response()->json([
            'message' => '¡Bien!, Hemos notificado al chofer para que realice la cotización.'
        ]);
    }


    public function guardarCotizacion(Request $request){
        $request->validate([
            'id_incidencia' => 'required',
            'monto_cotizado' => 'required|numeric',
            'pdf_cotizacion' => 'required|file|mimes:pdf,jpg,jpeg,png|max:4096', 
        ]);

        $id = $request->id_incidencia;
        $archivo = $request->file('pdf_cotizacion');

        $nombreArchivo = 'cotizacion_' . $id . '_' . time() . '.' . $archivo->getClientOriginalExtension();
        // Importante: storeAs devuelve la ruta relativa
        $ruta = $archivo->storeAs('cotizaciones', $nombreArchivo, 'public');

        DB::table('incidencias_vehiculares')->where('id', $id)->update([
            'monto_cotizado' => $request->monto_cotizado,
            'pdf_cotizacion' => $ruta,
            'estatus_reporte' => 'en_revision', 
            'updated_at' => now()
        ]);

        // Traemos los datos
        $incidencia = DB::table('incidencias_vehiculares')->where('id', $id)->first();
        $chofer = DB::table('choferes')->where('id', $incidencia->chofer_id)->first();
        $vehiculo = DB::table('vehiculos_panel')->where('id', $incidencia->vehiculo_id)->first();

        // Filtramos administradores que tengan correo válido
        $admins = Usuario::where('tipo_rol', 1)->whereNotNull('correo')->get();

        // CORRECCIÓN AQUÍ: "Notification" con "i"
        try {
            Notification::send($admins, new AdminCotizacionRecibidaNotification($incidencia, $chofer, $vehiculo, $ruta));
        } catch (\Exception $e) {
            // Si falla el correo, que al menos nos diga por qué en el log pero que no detenga el proceso
            \Log::error("Error enviando correo cotización: " . $e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Cotización enviada correctamente']);
    }

    //LISTO CWERRAMOS EL CIRUCLO Y QUE LO LLEVE EL CHGIOFER
    public function autorizarCotizacion($id)
    {
        try {
            // 1. Actualizar estatus
            DB::table('incidencias_vehiculares')
                ->where('id', $id)
                ->update([
                    'estatus_reporte' => 'cotizacion_aprobada',
                    'updated_at' => now()
                ]);

            // 2. Obtener datos para el correo del chofer
            $incidencia = DB::table('incidencias_vehiculares')->where('id', $id)->first();
            $chofer = DB::table('choferes')->where('id', $incidencia->chofer_id)->first();
            $vehiculo = DB::table('vehiculos_panel')->where('id', $incidencia->vehiculo_id)->first();
            
            $nombre_carro = $vehiculo->marca . ' ' . $vehiculo->linea . ' (' . $vehiculo->modelo . ')';

            //  Enviar notificación al chofer (La vista verde que creamos antes)
            if ($chofer && $chofer->correo) {
                Notification::route('mail', $chofer->correo)
                    ->notify(new CotizacionAprobadaChoferNotification($incidencia, $chofer, $nombre_carro));
            }

            return response()->json([
                'success' => true,
                'message' => 'La reparación ha sido autorizada y el chofer ha sido notificado.'
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /*
    public function cerrarCiclos($id){

         DB::table('incidencias_vehiculares')
                ->where('id', $id)
                ->update([
                    'estatus_reporte' => 'reparado',
                    'updated_at' => now()
        ]);

        return response()->json([
                'success' => true,
                'message' => '¡Bien! Gracias por confirmar que el vehiculo ha sido reparado con exito.'
            ]);
    }
            */
    public function cerrarCiclos(Request $request){
        $id = $request->id;
        $inc = DB::table('incidencias_vehiculares')->where('id', $id)->first();

        // 1. OBTENER EL ÚLTIMO KM DEL GPS DESDE TU TABLA DE VIAJES
        $ultimoViaje = DB::table('viajes')
            ->where('vehiculo_id', $inc->vehiculo_id)
            ->orderBy('fecha', 'desc')
            ->orderBy('id', 'desc')
            ->first();
        
        // Si no hay viajes registrados, usamos el que mande el request por si acaso
        $km_gps_real = $ultimoViaje ? $ultimoViaje->km_final : $request->km_actual;

        DB::beginTransaction();
        try {
            // 2. INSERTAR EN HISTORIAL CON EL KM DEL GPS
            DB::table('historial_de_servicios')->insert([
                'fecha_servicio'  => now(),
                'km_en_servicio'  => $km_gps_real, // <--- KM automático de la tabla viajes
                'costo'           => $inc->monto_cotizado,
                'taller_nombre'   => 'Taller Autorizado',
                'comprobante_pago'=> $request->ruta_archivo,
                'vehiculo_id'     => $inc->vehiculo_id,
                'servicio_id'     => $inc->tipo_servicio_id ?? 1, 
                'observaciones'   => "CIERRE AUTOMÁTICO: " . $inc->titulo_reporte,
                'created_at'      => now(),
                'updated_at'      => now()
            ]);

            // 3. ACTUALIZAR INCIDENCIA
            DB::table('incidencias_vehiculares')->where('id', $id)->update([
                'estatus_reporte' => 'reparado',
                'updated_at'      => now()
            ]);

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
