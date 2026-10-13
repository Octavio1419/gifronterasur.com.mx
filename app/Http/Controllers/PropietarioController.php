<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PropietarioController extends Controller
{
    //RUTA PARA LISTA DE PROPIETARIOS
    public function listaPropietarios(){

        return view('propietarios.index_propietarios');
    }

    //RECUPERANDO LOS PROPIETARIO
    public function recuperaPropietarios(){

        $propietarios = DB::table('propietarios')->get();

        return response()->json(['success'=> true, 'data'=>$propietarios]);
    }

    //vista para crear propietarios x
    public function createPropietario(){

        return view('propietarios.create_propietarios');
    }
    public function crear(Request $request)
    {
        //  Validando solo lo estrictamente necesario
        $request->validate([
            'nombre'     => 'required|string|max:255',
            'estatus'    => 'required',
        ]);
        try {
            DB::table('propietarios')->insert([
                'nombre'      => $request->nombre,
                'apellido_p'  => $request->apellido_p,
                'apellido_m'  => $request->apellido_m,
                'telefono'    => $request->telefono ?? null,
                'rfc'         => $request->rfc ? strtoupper($request->rfc) : null, 
                'estatus'     => $request->estatus,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Propietario guardado correctamente']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Error en base de datos: ' . $e->getMessage()
            ], 500);
        }
    }
}
