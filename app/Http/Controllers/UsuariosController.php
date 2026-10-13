<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chofer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class UsuariosController extends Controller
{
    public function vistalogin(){

        return view('login.vista_login');
    }
    /*
    public function validarlogin(Request $request){
        //VARIABLE PARA USAR CON AMBOS

        $request->validate([
            'nombre_sistema' => 'required|string|max:50',
            'password'  => 'required|min:6',
        ],[
            'nombre_sistema.required' => 'Nombre de usuario obligatorio',
            'password.required'  => 'Password obligatoria'
        ]);

        $credentials = $request->only('nombre_sistema', 'password');

        $recuperando_usuario = DB::table('usuarios')->join('roles', 'usuarios.tipo_rol', '=', 'roles.id')->join('sucursales', 'usuarios.tipo_sucursal', '=', 'sucursales.id')
        ->select('usuarios.*', 'roles.nombre_rol','sucursales.nombre_interno')
        ->where('usuarios.nombre_sistema', $request->input('nombre_sistema'))
        ->first();

        //dd($recuperando_usuario);

        if (!$recuperando_usuario) {
            return response()->json(['usuario_no_encontrado' => 'Usuario no esta registrado en nuestra base de datos'], 400);
        }

        if($recuperando_usuario->estatus == 0){
            return response()->json(['usuario_inactivo' => 'Usuario ha sido de baja, contacte al administrador',
            
        ], 400);
        }

        if(Hash::check($request->input('password'), $recuperando_usuario->password)){

            Auth::loginUsingId($recuperando_usuario->id);
            

            if($recuperando_usuario->nombre_rol == "Administrador"){

                return response()->json(['redirect' => route('vista.panel'),'message' => 'Inicio de sesión exitoso. Serás redirigido en breve.'], 200);

            }else if($recuperando_usuario->tipo_rol == 2){

                return response()->json(['redirect' => route('vista.reportes'),
                'message' => 'Inicio de sesión exitoso. Serás redirigido en breve.'], 200);
        
            }
            
        } else {
            return response()->json(['password_incorrecta' => 'Password incorrecta'], 400);
        }

        
    }
    */

    public function validarlogin(Request $request) {
        $request->validate([
            'nombre_sistema' => 'required|string|max:50',
            'password'  => 'required|min:6',
        ],[
            'nombre_sistema.required' => 'Usuario obligatorio',
            'password.required'  => 'Password obligatoria'
        ]);

        $nombre = $request->input('nombre_sistema');
        $pass = $request->input('password');

        // Buscamos primero en USUARIOS
        $usuario = DB::table('usuarios')
            ->join('roles', 'usuarios.tipo_rol', '=', 'roles.id')
            ->select('usuarios.*', 'roles.nombre_rol', DB::raw("'staff' as procedencia"))
            ->where('usuarios.nombre_sistema', $nombre)
            ->first();

        // Si no, buscamos en CHOFERES
        if (!$usuario) {
            $usuario = DB::table('choferes')
                ->join('roles', 'choferes.rol_id', '=', 'roles.id')
                ->select('choferes.*', 'roles.nombre_rol', DB::raw("'chofer' as procedencia"))
                ->where('choferes.usuario_sistema', $nombre) 
                ->first();
        }

        if (!$usuario || !Hash::check($pass, $usuario->password)) {
            return response()->json(['usuario_no_encontrado' => 'Usuario o contraseña incorrecta'], 400);
        } 

        if ($usuario->estatus == 0) {
            return response()->json(['usuario_inactivo' => 'Usuario inactivo'], 400);
        }

        // --- PROCESO DE LOGIN ÚNICO ---
        if ($usuario->procedencia == 'chofer') {
            // Logueamos EXCLUSIVAMENTE en el guard de chofer
            Auth::guard('chofer')->loginUsingId($usuario->id);
            $request->session()->regenerate(); // Crucial para que la cookie se cree bien
            
            return response()->json([
                'redirect' => route('choferes.dashboard'), 
                'message' => 'Bienvenido Operador'
            ], 200);

        } else {
            // Logueamos en el guard por defecto (web) para Admins y otros
            Auth::loginUsingId($usuario->id);
            $request->session()->regenerate();

            $ruta = ($usuario->nombre_rol == "Administrador") ? route('vista.panel') : route('vista.reportes');
            
            return response()->json([
                'redirect' => $ruta,
                'message' => 'Inicio de sesión exitoso. Serás redirigido en breve'
            ], 200);
        }
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate(); 
        $request->session()->regenerateToken(); 
        return redirect()->route('login');
    }

    //funcion para la vista de suarios
    public function vistaUsuarios(){

        return view('usuarios.usuarios');
    }

    //funcion para listar los usuarios
    public function listarUsuarios(){
        $usuario = DB::table('usuarios as u')->join('roles as r', 'u.tipo_rol', '=', 'r.id')->join('sucursales as s', 'u.tipo_sucursal', '=', 's.id')->select('u.*', 'r.nombre_rol', 's.nombre_interno','s.nombre_real')->get();

        //recuperando los roles y las sucursales
        $roles = DB::table('roles')->get();
        $sucursales = DB::table('sucursales')->get();

        return response()->json(['usuarios'=>$usuario, 'roles'=>$roles,'sucursales'=>$sucursales]);
    }

    //cambiar estatus
    public function cambiarEstatus($id,$estatus){

        $estatus_nuevo = DB::table('usuarios')->where('id', $id)->update(['estatus'=> $estatus]);

        return response()->json(['message'=>'El estatus se actualizado']);
    }

    public function update(Request $request){
        try {
            // Buscamos al usuario
            $usuario = User::find($request->id);
            
            if (!$usuario) {
                return response()->json(['status' => 'error', 'message' => 'Usuario no encontrado']);
            }

            // Llenado de datos básicos
            $usuario->nombre = $request->nombre;
            $usuario->apellido_p = $request->apellido_p;
            $usuario->apellido_m = $request->apellido_m;
            $usuario->correo = $request->correo;
            $usuario->nombre_sistema = $request->nombre_sistema;
            $usuario->curp = strtoupper($request->curp);
            $usuario->telefono = $request->telefono;
            $usuario->domicilio = $request->domicilio;
            $usuario->nombre_interno = $request->nombre_interno;
            $usuario->descripcion_usuario = $request->descripcion_usuario;
            $usuario->tipo_rol = $request->tipo_rol;
            $usuario->tipo_sucursal = $request->tipo_sucursal;
            $usuario->estatus = $request->estatus;

            // Lógica de Contraseña: Solo si se escribió algo
            if ($request->filled('password')) {
                $usuario->password = Hash::make($request->password);
            }

            // Lógica de Foto de Perfil
            if ($request->hasFile('foto_perfil')) {
                // Eliminar foto anterior si existe (opcional)
                if ($usuario->foto_perfil && file_exists(public_path($usuario->foto_perfil))) {
                    // Storage::delete(...) o unlink(...)
                }
                
                $file = $request->file('foto_perfil');
                $path = $file->store('usuarios', 'public');
                $usuario->foto_perfil = '/storage/' . $path;

                
            }

            $usuario->save();

            return response()->json([
                'status' => 'success', 
                'message' => 'El perfil de ' . $usuario->nombre . ' ha sido actualizado correctamente.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ]);
        }
    }
    
// 1. Guardar Nuevo Usuario
    public function guardar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido_p' => 'required|string|max:255',
            'correo' => 'required|email|unique:usuarios,correo',
            'nombre_sistema' => 'required|string|unique:usuarios,nombre_sistema',
            'password' => 'required|min:6',
            'tipo_rol' => 'required',
            'tipo_sucursal' => 'required'
        ]);

        // Preparar el array de datos básicos
        $datos = [
            'nombre' => $request->nombre,
            'apellido_p' => $request->apellido_p,
            'apellido_m' => $request->apellido_m,
            'curp' => $request->curp ? strtoupper($request->curp) : null,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'nombre_sistema' => $request->nombre_sistema,
            'domicilio' => $request->domicilio,
            'descripcion_usuario' => $request->descripcion_usuario,
            'tipo_rol' => $request->tipo_rol,
            'tipo_sucursal' => $request->tipo_sucursal,
            'estatus' => 1, // Activo por defecto
            'password' => Hash::make($request->password),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Procesar foto de perfil si se envía
        if ($request->hasFile('foto_perfil')) {
            $path = $request->file('foto_perfil')->store('public_html/perfiles');
            $datos['foto_perfil'] = Storage::url($path);
        }


        DB::table('usuarios')->insert($datos);

        return response()->json(['message' => 'Usuario creado exitosamente.']);
    }

    // 2. Actualizar Usuario Existente
    public function actualizar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:usuarios,id',
            'nombre' => 'required|string|max:255',
            'apellido_p' => 'required|string|max:255',
            'correo' => 'required|email|unique:usuarios,correo,' . $request->id,
            'nombre_sistema' => 'required|string|unique:usuarios,nombre_sistema,' . $request->id,
        ]);

        // Preparar el array con los datos a actualizar
        $datos = [
            'nombre' => $request->nombre,
            'apellido_p' => $request->apellido_p,
            'apellido_m' => $request->apellido_m,
            'curp' => $request->curp ? strtoupper($request->curp) : null,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'nombre_sistema' => $request->nombre_sistema,
            'domicilio' => $request->domicilio,
            'descripcion_usuario' => $request->descripcion_usuario,
            'tipo_rol' => $request->tipo_rol,
            'tipo_sucursal' => $request->tipo_sucursal,
            'estatus' => $request->estatus,
            'updated_at' => now(),
        ];

        // Si escribió algo en el campo password, se agrega al array
        if ($request->filled('password')) {
            $datos['password'] = Hash::make($request->password);
        }

        // Manejo de la nueva foto de perfil
        if ($request->hasFile('foto_perfil')) {
            $path = $request->file('foto_perfil')->store('public_html/perfiles');
            $datos['foto_perfil'] = '/storage/' . $path;
        }

        /*
        // Lógica de Foto de Perfil
            if ($request->hasFile('foto_perfil')) {
                // Eliminar foto anterior si existe (opcional)
                if ($usuario->foto_perfil && file_exists(public_path($usuario->foto_perfil))) {
                    // Storage::delete(...) o unlink(...)
                }
                
                $file = $request->file('foto_perfil');
                $path = $file->store('usuarios', 'public');
                $usuario->foto_perfil = '/storage/' . $path;
            }
        
        */

        // Ejecutar la actualización filtrando por el ID recibido
        DB::table('usuarios')
            ->where('id', $request->id)
            ->update($datos);

        return response()->json(['message' => 'Los cambios se guardaron correctamente.']);
    }
}
