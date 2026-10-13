<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Tarea;
use App\Notifications\TareaAsignadaNotification;

class MiPerfilController extends Controller
{
    //función para mostrar la vista del perfil del usuario
    public function vistaMiPerfil(){

        return view('mi_perfil.vista_perfil');
    }

    //recuperar el usuario logueado
    public function datosUsuarioLogueado(Request $request){
        $usuario = DB::table('usuarios')
            ->join('roles', 'usuarios.tipo_rol', '=', 'roles.id')
            ->join('sucursales', 'usuarios.tipo_sucursal', '=', 'sucursales.id')
            ->select('usuarios.*', 'roles.nombre_rol', 'sucursales.nombre_real','sucursales.ubicacion','sucursales.telefono_tienda')
            ->where('usuarios.id', Auth::id())
            ->first();
        $roles = DB::table('roles')->get();
        $sucursales = DB::table('sucursales')->get();
        $usuarios_sistema = DB::table('usuarios')->get();
        $listado_tareas = DB::table('tareas')
            ->where('creado_por', Auth::id())
            ->get();
        
        $raw = DB::table('tareas')
            ->join('tareas_usuarios', 'tareas.id', '=', 'tareas_usuarios.tarea_id')
            ->join('usuarios', 'usuarios.id', '=', 'tareas_usuarios.usuario_id')
            ->where('tareas.creado_por',  Auth::id())
            ->where('tareas.es_activa', true)
            ->select(
                'tareas.id as tarea_id',
                'tareas.nombre_tarea',
                'tareas.descripcion_tarea',
                'tareas.fecha_vencimiento',
                
                'usuarios.id as usuario_id',
                'usuarios.nombre',
                'usuarios.apellido_p',
                'usuarios.apellido_m',
                'usuarios.foto_perfil'
            )->get();
        /*trae mis tareas unicamente*/
        $mis_tareas = DB::table('tareas_usuarios')
            ->join('tareas', 'tareas_usuarios.tarea_id', '=', 'tareas.id')
            ->join('usuarios as asignador', 'asignador.id', '=', 'tareas_usuarios.realizado')
            ->where('tareas_usuarios.usuario_id', Auth::id())
            ->where('tareas.es_activa', true)
            ->select(
                'tareas_usuarios.id as asignacion_id',
                'tareas.id as tarea_id',
                'tareas.nombre_tarea',
                'tareas.descripcion_tarea',
                'tareas.fecha_vencimiento',
                'tareas.repeticion_tipo',
                'tareas.repeticion_dia',
                'tareas.repeticion_dia_mes',
                'tareas.repeticion_anticipacion',
                'asignador.id as asignador_id',
                'asignador.nombre as asignador_nombre',
                'asignador.apellido_p as asignador_apellido_p',
                'asignador.apellido_m as asignador_apellido_m',
                'asignador.foto_perfil'
            )->get();

            $archivos_por_tarea = DB::table('tarea_archivos')
                ->select('id', 'tarea_id_archivo', 'nombre_original', 'ruta_archivo', 'tipo_archivo')
                ->get()
                ->groupBy('tarea_id_archivo');
      

            $hoy = Carbon::today();

            $mis_tareas_enriquecidas = collect($mis_tareas)->map(function ($tarea) use ($hoy) {
                $anticipacion = intval($tarea->repeticion_anticipacion ?? 0);
                $fechaHabilitacion = $hoy;

                if ($tarea->repeticion_tipo === 'semanal' && $tarea->repeticion_dia) {
                    $dias = [
                        'domingo' => 0, 'lunes' => 1, 'martes' => 2,
                        'miercoles' => 3, 'jueves' => 4, 'viernes' => 5, 'sabado' => 6
                    ];
                    $diaObjetivo = Carbon::now()->next($dias[strtolower($tarea->repeticion_dia)]);
                    $fechaHabilitacion = $diaObjetivo->copy()->subDays($anticipacion);
                }

                if ($tarea->repeticion_tipo === 'mensual' && $tarea->repeticion_dia_mes) {
                    $diaObjetivo = Carbon::create($hoy->year, $hoy->month, $tarea->repeticion_dia_mes);
                    if ($diaObjetivo->lt($hoy)) {
                        $diaObjetivo = $diaObjetivo->addMonth();
                    }
                    $fechaHabilitacion = $diaObjetivo->copy()->subDays($anticipacion);
                }

                $tarea->fecha_habilitacion = $fechaHabilitacion->toDateString();
                $tarea->visible = $hoy->greaterThanOrEqualTo($fechaHabilitacion);

                return $tarea;
            });                
        
            $tareasAgrupadas = [];

            foreach ($raw as $fila) {
                $id = $fila->tarea_id;

                if (!isset($tareasAgrupadas[$id])) {
                    $tareasAgrupadas[$id] = [
                        'id' => $id,
                        'nombre_tarea' => $fila->nombre_tarea,
                        'descripcion_tarea' => $fila->descripcion_tarea,
                        'fecha_vencimiento' => $fila->fecha_vencimiento,
                        'usuarios_asignados' => [],
                        'total_usuarios' => 0,
                    ];
                }

                $tareasAgrupadas[$id]['usuarios_asignados'][] = [
                    'id' => $fila->usuario_id,
                    'nombre' => $fila->nombre,
                    'apellido_p' => $fila->apellido_p,
                    'apellido_m' => $fila->apellido_m,
                    'foto_perfil' => $fila->foto_perfil,
                ];

                $tareasAgrupadas[$id]['total_usuarios']++;
        }
        $tareas_con_archivos = DB::table('tarea_archivos')
        ->select('tarea_id_archivo', 'nombre_original', 'ruta_archivo', 'tipo_archivo')
        ->get()
        ->groupBy('tarea_id_archivo');
        
        
        if ($usuario) {
            return response()->json([
                'usuario' => $usuario,
                'roles' => $roles,
                'sucursales' => $sucursales,
                'usuarios_sistema' => $usuarios_sistema,
                'listado_tareas' => $listado_tareas,
                'tareas' => array_values($tareasAgrupadas),
                'mis_tareas' => $mis_tareas_enriquecidas->values(),
                'tareas_archivos' => $tareas_con_archivos,
                'archivos_tareas' => $archivos_por_tarea,
                'message' => 'Usuario recuperado exitosamente.'
            ]);
        } else {
            return response()->json(['error' => 'Usuario no logueado'], 404);
        }    
    }
 
    /*actualizar mis datos demi perfil */

    public function updateMiPerfil(Request $request)
    {
        $id = Auth::id();

        // Validar campos
        $request->validate([
            'nombre' => 'required|string',
            'correo' => 'required|email',
            'apellido_p' => 'required|string',
            'apellido_m' => 'nullable|string',
            'curp' => 'required|string',
            'domicilio' => 'required|string',
            'telefono' => 'required|string',
            'tipo_rol' => 'required|integer',
            'tipo_sucursal' => 'required|integer',
            'descripcion_usuario' => 'nullable|string',
            'password' => 'nullable|string|min:6',
            'foto' => 'nullable|image|max:2048', // Validación para la
        ]);

        // Obtener usuario actual
        $user = DB::table('usuarios')->where('id', $id)->first();

        // Preparar arreglo de actualización
        $data = [
            'nombre' => $request->nombre, 
            'apellido_p' => $request->apellido_p,
            'apellido_m' => $request->apellido_m,
            'correo' => $request->correo,
            'curp' => $request->curp,
            'domicilio' => $request->domicilio,
            'telefono' => $request->telefono,
            'tipo_rol' => $request->tipo_rol,
            'tipo_sucursal' => $request->tipo_sucursal,
            'descripcion_usuario' => $request->descripcion_usuario,
        ];

        // Si se envía nueva contraseña
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

    if ($request->hasFile('foto')) {
        $foto = $request->file('foto');

        // Guarda en disco 'public' dentro del folder 'usuarios'
        $path = $foto->store('usuarios', 'public');

        // Genera URL pública (ej. /storage/usuarios/infonavit-seeklogo.png)
        $url = Storage::url($path);

        // Guarda directamente la URL completa en el array
        $data['foto_perfil'] = $url;
    }

        // Actualizar usuario
        $consulta = DB::table('usuarios')->where('id', $id)->update($data);

        return response()->json(['message' => 'Perfil actualizado correctamente']);
    }

    //Guardar foto de galeria
    public function guardarFotoGaleria(Request $request){
        $request->validate([
            'titulo_imagen' => 'required|string|max:255',
            'ruta_imagen' => 'required|image|max:9048', // Validación para la imagen
            'descripcion' => 'nullable|string|max:500',
            'activo' => 'nullable|boolean',
            
        ]);

        $user = Auth::user();

        $dataGaleria = [
            'titulo_imagen' => $request->titulo_imagen,
            'descripcion' => $request->descripcion,
            'img_activo' => $request->img_activo ? 1 : 0, // Convertir a entero
            'usuario_id' => $request->usuario_id, // Asignar el ID del usuario autenticado
        ];

        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        $foto = $request->file('ruta_imagen');
        $path = $foto->store('galeria', 'public');
        $url = Storage::url($path);

        $dataGaleria['ruta_imagen'] = $url;

        DB::table('galeria')->insert($dataGaleria);

        return response()->json(['message' => 'Foto de galería guardada exitosamente']);
    }

    //function para recuperar las fotos de la galeria del usuario logueado
    public function recuperarFotosGaleria(Request $request){
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        $fotosGaleria = DB::table('galeria')
            ->where('usuario_id', $user->id)
            ->where('img_activo', 1) // Filtrar solo las imágenes activas
            ->get();

        return response()->json(['fotos' => $fotosGaleria]);
    }

   public function crearTarea(Request $request){
        $request->validate([
            'nombre_tarea' => 'required|string|max:255',
            'descripcion_tarea' => 'nullable|string|max:500',
            'es_activa' => 'boolean',
            'fecha_vencimiento' => 'nullable|date',
            'repeticion_tipo' => 'nullable|in:diario,semanal,mensual',
            'repeticion_dia' => 'nullable|string|max:10',
            'repeticion_dia_mes' => 'nullable|integer|min:1|max:31',
            'repeticion_anticipacion' => 'nullable|integer|min:0|max:10',
            'repeticion_hasta' => 'nullable|date',
            'archivos_tarea.*' => 'file|max:140240'
        ]);
    

        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }
        if ($request->repeticion_hasta && !$request->repeticion_tipo) {
            return response()->json([
                'error' => 'No puedes definir una fecha de repeticion sin seleccionar el tipo de repetición.'
            ], 422);
        }
        $dataTarea = [
            'nombre_tarea' => $request->nombre_tarea,
            'descripcion_tarea' => $request->descripcion_tarea,
            'creado_por' => $user->id,
            'fecha_vencimiento' => $request->fecha_vencimiento ?? null,
            'repeticion_tipo' => $request->repeticion_tipo,
            'repeticion_dia' => $request->repeticion_dia,
            'repeticion_dia_mes' => $request->repeticion_dia_mes,
            'repeticion_anticipacion' => $request->repeticion_anticipacion,
            'repeticion_hasta' => $request->repeticion_hasta,
            'created_at' => now()
        ];

        if ($request->repeticion_tipo) {
           
            $dataTarea['es_activa'] = 0;
        } else {
            
            $dataTarea['es_activa'] = $request->es_activa ? 1 : 0;
        }

        $tareaId = DB::table('tareas')->insertGetId($dataTarea);

        if ($request->hasFile('archivos_tarea')) {
            foreach ($request->file('archivos_tarea') as $archivo) {
                $ruta = $archivo->store('tareas_archivos', 'public');

                DB::table('tarea_archivos')->insert([
                    'tarea_id_archivo' => $tareaId,
                    'nombre_original' => $archivo->getClientOriginalName(),
                    'ruta_archivo' => $ruta,
                    'tipo_archivo' => $archivo->getClientMimeType(),
                    'created_at' => now()
                ]);
            }
        }

        if ($tareaId) {
            
          return response()->json(['message' => 'Tarea creada exitosamente']);
        } else {
            return response()->json(['error' => 'Error al intentar crear la tarea']);
        }
    }

    function eliminarTarea(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        DB::table('tareas')->where('id', $id)->where('creado_por', $user->id)->delete();

        return response()->json(['message' => 'Tarea eliminada exitosamente']);
    }

    public function quitarUsuarioTarea(Request $request, $id_task, $id){
        $user = Auth::user();

        if(!$user){
            return response()->json(['error' => 'Usuario no autenticado'],401);
        }

        DB::table('tareas_usuarios')->where('usuario_id', $id_task)->where('realizado', $user->id)->where('tarea_id', $id)->delete();

        /*Notificando al usuario que ha sido eliminado de la tarea*/


        return response()->json(['message'=>'Usuario eliminado de la tarea con exito!']);
        
    }

    function cambiarEstadoTarea(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        $accion = $request->input('accion');

        $message_text = $accion ? 'Tarea activada exitosamente' : 'Tarea desactivada exitosamente';

        DB::table('tareas')->where('id', $id)->where('creado_por', $user->id)->update(['es_activa' => $accion]);

        return response()->json(['message' => $message_text]);
    }

    function updateTarea(Request $request, $id){
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        $request->validate([
            'nombre_tarea' => 'required|string|max:255',
            'descripcion_tarea' => 'nullable|string|max:500',
            'fecha_vencimiento' => 'nullable|date',
        ]);

        $dataTarea = [
            'nombre_tarea' => $request->nombre_tarea,
            'descripcion_tarea' => $request->descripcion_tarea,
            'fecha_vencimiento' => $request->fecha_vencimiento ? $request->fecha_vencimiento : null,
        ];

        DB::table('tareas')->where('id', $id)->where('creado_por', $user->id)->update($dataTarea);

        if ($request->filled('archivos_eliminar')) {
            $ids = json_decode($request->archivos_eliminar, true);
            DB::table('tarea_archivos')->whereIn('id', $ids)->delete();
        }
        if ($request->hasFile('archivos_tarea_edit')) {
            foreach ($request->file('archivos_tarea_edit') as $archivo) {
                $ruta = $archivo->store('tareas_archivos', 'public');
                DB::table('tarea_archivos')->insert([
                    'tarea_id_archivo' => $request->id,
                    'nombre_original' => $archivo->getClientOriginalName(),
                    'ruta_archivo' => $ruta,
                    'tipo_archivo' => $archivo->getClientMimeType(),
                    'created_at' => now()
                ]);
            }
        }

        return response()->json(['message' => 'Tarea actualizada exitosamente']);
    }



    public function asignarUsuariosATarea(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }  

        $request->validate([
            'tarea_id' => 'required|integer|exists:tareas,id',
            'usuarios_ids' => 'required|array|min:1',
            'usuarios_ids.*' => 'integer|exists:usuarios,id', // <- CORREGIDO
        ]);

        $tareaId = $request->input('tarea_id');
        $usuariosIds = $request->input('usuarios_ids');

        /*consulta para recuperar el usuario quien esta asiganando la tarea */
        $usuario_asignador = DB::table('usuarios')->where('id',$user->id)->first();

        /*recuperando el nombre de la tarea asignada */
        $name_task_asing = DB::table('tareas')->where('id', $tareaId)->first();
        $tipo = $name_task_asing->repeticion_tipo;
        //dd($tipo);  

        foreach ($usuariosIds as $usuarioId) {
            // Insertar en tabla pivote
            DB::table('tareas_usuarios')->updateOrInsert([
                'tarea_id'   => $tareaId,
                'usuario_id' => $usuarioId,
                'realizado'  => $user->id, // quien asigna la tarea
            ]);

            //  Notificar SOLO si la tarea es normal
            if (is_null($tipo)) { 
                $usuario = Usuario::find($usuarioId);
                if ($usuario) {
                    $usuario->notify(new TareaAsignadaNotification(
                        $tareaId,
                        $usuario_asignador->nombre,
                        $usuario_asignador->apellido_p,
                        $name_task_asing->nombre_tarea,
                        $usuario_asignador->foto_perfil
                    ));
                }
            }
        }



        return response()->json([
            'message' => 'Usuarios asignados correctamente a la tarea.'
        ]);
    }


    

    
 

}
