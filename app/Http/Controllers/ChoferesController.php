<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ChoferesController extends Controller
{
    //registrar choferes
    public function vistaRegistrar(){

        return view('choferes.registrar_choferes');
    }
    public function store(Request $request){
        // 1. Validaciones de seguridad
        $request->validate([
            'usuario_sistema' => 'required|unique:choferes',
            'password' => 'required',
            'vehiculo_id' => 'required|unique:choferes',
            'n_licencia' => 'required|unique:choferes'
        ]);

        try {
            DB::beginTransaction();

            // 2. Procesar las fotos (Base64 a Archivo)
            // Creamos una carpeta única por chofer para orden
            $folderPath = public_path('uploads/choferes/' . $request->usuario_sistema);
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            // Función interna para ahorrar código al guardar fotos
            $guardarFoto = function($base64Data, $nombreArchivo) use ($folderPath, $request) {
                if (!$base64Data) return null;
                
                $img = str_replace('data:image/jpeg;base64,', '', $base64Data);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                
                $fileName = $nombreArchivo . '_' . time() . '.jpg';
                $file = $folderPath . '/' . $fileName;
                
                file_put_contents($file, $data);
                // Retornamos la ruta relativa para la DB
                return 'uploads/choferes/' . $request->usuario_sistema . '/' . $fileName;
            };

            // Guardamos cada una
            $foto_chofer = $guardarFoto($request->foto_chofer_blob, 'perfil');
            $foto_ine_f = $guardarFoto($request->foto_ine_frente_blob, 'ine_frente');
            $foto_ine_r = $guardarFoto($request->foto_ine_reverso_blob, 'ine_reverso');
            $foto_lic_f = $guardarFoto($request->foto_licencia_frente_blob, 'licencia_frente');
            $foto_lic_r = $guardarFoto($request->foto_licencia_reverso_blob, 'licencia_reverso');

            // 3. Insertar en la Base de Datos
            DB::table('choferes')->insert([
                'nombre' => $request->nombre,
                'apellido_p' => $request->apellido_p,
                'apellido_m' => $request->apellido_m,
                'usuario_sistema' => $request->usuario_sistema,
                'password' => Hash::make($request->password), 
                'telefono' => $request->telefono ?? 'S/N',
                'n_licencia' => $request->n_licencia,
                'vigencia_licencia' => $request->vigencia_licencia,
                'tipo_sangre' => $request->tipo_sangre,
                'contacto_emergencia' => $request->contacto_emergencia,
                'telefono_emergencia' => $request->telefono_emergencia,
                'foto_chofer' => $foto_chofer,
                'foto_ine' => $foto_ine_f, // Puedes guardar la del frente aquí
                'foto_ine_reverso' => $foto_ine_r,
                'foto_licencia_enfre' => $foto_lic_f,
                'foto_licencia_reverso' => $foto_lic_r,
                'vehiculo_id' => $request->vehiculo_id,
                'estatus' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Chofer dado de alta correctamente.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function index(){
        // Jalamos los choferes y unimos con su vehículo para mostrar marca y placas
        $choferes = DB::table('choferes')
            ->join('vehiculos_panel', 'choferes.vehiculo_id', '=', 'vehiculos_panel.id')
            ->select('choferes.*', 'vehiculos_panel.marca', 'vehiculos_panel.linea', 'vehiculos_panel.placas')
            ->get();
        $vehiculos = DB::table('vehiculos_panel')->get();    

        return view('choferes.index_choferes', compact('choferes', 'vehiculos'));
    }

    //recupera choferes por json
    public function recuperarChoferes(){
        
        $choferes = DB::table('choferes')
            ->join('vehiculos_panel', 'choferes.vehiculo_id', '=', 'vehiculos_panel.id')
            ->select('choferes.*', 'vehiculos_panel.marca', 'vehiculos_panel.linea', 'vehiculos_panel.placas')
            ->get();

        return response()->json(['success' => true, 'data' => $choferes]);
    }

    public function update(Request $request) {
        // Preparamos los datos básicos
        $updateData = [
            'nombre'              => $request->nombre,
            'apellido_p'          => $request->apellido_p,
            'apellido_m'          => $request->apellido_m,
            'usuario_sistema'     => $request->usuario_sistema,
            'telefono'            => $request->telefono,
            'n_licencia'          => $request->n_licencia,
            'vigencia_licencia'   => $request->vigencia_licencia,
            'tipo_sangre'         => $request->tipo_sangre,
            'vehiculo_id'         => $request->vehiculo_id,
            'contacto_emergencia' => $request->contacto_emergencia,
            'telefono_emergencia' => $request->telefono_emergencia,
            'estatus'             => $request->estatus,
            'updated_at'          => now()
        ];

        // Lógica para la contraseña: solo si el usuario escribió una nueva
        if ($request->filled('password')) {
            // Validamos que coincida con la confirmación (opcional pero recomendado)
            if ($request->password !== $request->password_confirmation) {
                return response()->json(['success' => false, 'message' => 'Las contraseñas no coinciden.'], 422);
            }
            $updateData['password'] = Hash::make($request->password);
        }

        try {
            DB::table('choferes')->where('id', $request->chofer_id)->update($updateData);
            return response()->json(['success' => true, 'message' => 'Expediente actualizado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al actualizar: ' . $e->getMessage()], 500);
        }
    }

    

    //vista panel del chofer
    /*
    public function dashobarChofer() {
    
        $choferId = Auth::guard('chofer')->id();
        //dd($choferId); 

        if (!$choferId) {
            
            return redirect()->route('login')->with('error', 'Sesión expirada');
        }

         $ultimoRegistro = DB::table('bitacora_combustible_km')
        ->where('chofer_id', $choferId)
        ->whereDate('created_at', now()->toDateString())
        ->orderBy('id', 'desc')
        ->first();

        $puedoCheckIn = !$ultimoRegistro; 
        $puedoCheckOut = ($ultimoRegistro && $ultimoRegistro->tipo_registro == 'inicio');

        $chofer = DB::table('choferes')
            ->leftJoin('vehiculos_panel', 'choferes.vehiculo_id', '=', 'vehiculos_panel.id')
            ->leftJoin('obras', 'vehiculos_panel.obra_id', '=', 'obras.id')
            ->select(
                'choferes.*', 
                'vehiculos_panel.marca', 
                'vehiculos_panel.linea', 
                'vehiculos_panel.placas',
                'vehiculos_panel.foto_vehiculo', 
                'vehiculos_panel.serie',
                'vehiculos_panel.tarjeta_circulacion',
                'vehiculos_panel.efecticard',
                'vehiculos_panel.estatus_seguro',
                'vehiculos_panel.compania_seguro',
                'vehiculos_panel.fecha_fin_seguro',
                'obras.nombre_obra', 
                'obras.ubicacion',
                'obras.iframe',
                'obras.estado',
                'obras.municipio',
                'obras.nombre_obra'
            )
            ->where('choferes.id', $choferId)
            ->first();
        //dd($chofer); 
        if (!$chofer) return abort(404);

        return view('choferes.vista.panel_choferes', [
            'vehiculo' => $chofer, 
            'obra' => $chofer,
            'chofer' => $chofer,
            'puedoCheckIn' => $puedoCheckIn,
            'puedoCheckOut' => $puedoCheckOut
        ]);
    }
        */
    

    public function dashobarChofer() {
        $choferId = Auth::guard('chofer')->id();

        if (!$choferId) {
            return redirect()->route('login')->with('error', 'Sesión expirada');
        }

        // --- LÓGICA DE BOTÓN DE COMBUSTIBLE ---
         // Array de días (nombre => índice, igual a tu numeración en BD)
        $diasSemana = [
            'lunes' => 0, 'martes' => 1, 'miércoles' => 2,
            'jueves' => 3, 'viernes' => 4, 'sábado' => 5, 'domingo' => 6,
        ];

        $horario = DB::table('horario_combustible')->first();

        $enHorario = false;
        $diaTexto = '';
        $rangoTexto = '';

        if ($horario) {
            $dia_servidor = trim($horario->dia_semana);
            // Buscamos el NOMBRE del día a partir del número guardado en BD
            $nombre_dia = array_search($dia_servidor, $diasSemana);

            // Día actual en español, en minúsculas, para poder compararlo
            $es_ahora = mb_strtolower(Carbon::now()->locale('es')->isoFormat('dddd'));

            if ($nombre_dia !== false && $es_ahora === $nombre_dia) {
                $hora_actual = Carbon::now()->format('H:i');
                $hora_inicio = Carbon::createFromFormat('H:i:s', $horario->horario_inicio)->format('H:i');
                $hora_fin = Carbon::createFromFormat('H:i:s', $horario->horario_finalizar)->format('H:i');

                $enHorario = ($hora_actual >= $hora_inicio && $hora_actual <= $hora_fin);
            }

            $diaTexto = $nombre_dia ?: '';
            $horaIniTexto = Carbon::createFromFormat('H:i:s', $horario->horario_inicio)->format('G');
            $horaFinTexto = Carbon::createFromFormat('H:i:s', $horario->horario_finalizar)->format('G');
            $rangoTexto = "{$horaIniTexto}-{$horaFinTexto}hr";
        }

        $permisoActivo = DB::table('permisos_botones')
            ->where('chofer_id', $choferId)
            ->where('estatus', 'APROBADO')
            ->where('habilitado', 1)
            ->first();

        $botonCombustibleActivo = $enHorario || !empty($permisoActivo);
        $estatusSolicitud = !empty($permisoActivo) ? 'EXTRA_EXPONTANEA' : 'A_TIEMPO';

        $textoBoton = $botonCombustibleActivo ? "Abierto (Solo {$diaTexto} {$rangoTexto})"
            : ($horario ? "Cerrado (Solo {$diaTexto} {$rangoTexto})" : 'Cerrado (Sin horario configurado)');
            //dd($estatusSolicitud);
        // ---------------------------------------


        $ultimoRegistro = DB::table('bitacora_combustible_km')
            ->where('chofer_id', $choferId)
            ->whereDate('created_at', now()->toDateString())
            ->orderBy('id', 'desc')
            ->first();

        $puedoCheckIn = !$ultimoRegistro; 
        $puedoCheckOut = ($ultimoRegistro && $ultimoRegistro->tipo_registro == 'inicio');

        // Consulta de datos del chofer/vehículo (Tu código actual)
        $chofer = DB::table('choferes')
            ->leftJoin('vehiculos_panel', 'choferes.vehiculo_id', '=', 'vehiculos_panel.id')
            ->leftJoin('obras', 'vehiculos_panel.obra_id', '=', 'obras.id')
            ->select('choferes.*', 'vehiculos_panel.marca', 'vehiculos_panel.linea', 'vehiculos_panel.placas',
                    'vehiculos_panel.foto_vehiculo', 'vehiculos_panel.serie', 'vehiculos_panel.tarjeta_circulacion',
                    'vehiculos_panel.efecticard', 'vehiculos_panel.estatus_seguro', 'vehiculos_panel.compania_seguro',
                    'vehiculos_panel.fecha_fin_seguro', 'obras.nombre_obra', 'obras.municipio', 'obras.estado', 'obras.iframe')
            ->where('choferes.id', $choferId)
            ->first();

        if (!$chofer) return abort(404);

        return view('choferes.vista.panel_choferes', [
            'vehiculo' => $chofer, 
            'chofer' => $chofer,
            'obra' => $chofer,
            'puedoCheckIn' => $puedoCheckIn,
            'puedoCheckOut' => $puedoCheckOut,
            'botonCombustibleActivo' => $botonCombustibleActivo,
            'textoBoton' => $textoBoton,
            'estatusSolicitud' => $estatusSolicitud
        ]);
    }

    public function vistaMantenimiento() {
        $choferId = Auth::guard('chofer')->id();
  
        if (!$choferId) {
            return redirect()->route('login');
        }

        $chofer = DB::table('choferes')->where('id', $choferId)->first();

        return view('choferes.vista.mantenimiento', [
            'nombreChofer' => $chofer->nombre . ' ' . $chofer->apellido_p . ' ' . $chofer->apellido_m 
        ]);
    }
    
    //la vista de los choferes para ver su perfil
    public function miPerfil(){
        return view('choferes.vista.miperfilchofer');
    }

    public function recuperarMisDatos(){
        //chofer logueado
        $choferId = Auth::guard('chofer')->id();

         if (!$choferId) {
            return redirect()->route('login');
        }

        $perfil = DB::Table('choferes')->select('nombre','apellido_p','apellido_m', 'usuario_sistema','correo','foto_perfil','telefono','n_licencia','vigencia_licencia','tipo_sangre','contacto_emergencia','telefono_emergencia')->where('id',$choferId)->get();

        return response()->json(['perfil'=>$perfil]);
    }

  
}