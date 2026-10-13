<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class CorteCajeController extends Controller
{
    public function vista_corte_caja(){

        return view('corte_caja.vista_caja');
    }

    /*obteniendo datos del usuario logueado */

    public function datosusuariologueado(Request $request){
        $user = Auth::user();

        $usuario_login = DB::table('sucursales')
        ->where('id', $user->tipo_sucursal)
        ->select('nombre_interno', 'nombre_real', 'id')
        ->first();

       return response()->json(['usuario_login'=>$usuario_login]);
    }

    /*funcion para subir los corte de caja a la tabla */
    public function subirdatos(Request $request, $nombre){
    $datos_excel = $request->json()->all();

    foreach ($datos_excel as $row) {
        
        $row['vigente'] = filter_var($row['vigente'], FILTER_VALIDATE_BOOLEAN);

        $validator = Validator::make($row, [
            'remision' => 'required|string|max:255',
            'fecha_pago' => 'required|date',
            'importe_total' => 'required|numeric',
            'tipo_pago' => 'required|string|max:255',
            'vigente' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 400);
        }

        $consulta_usuario_logueado = 'cortecaja_'.$nombre;
       

        DB::table($consulta_usuario_logueado)->insert([
            'remision' => $row['remision'],
            'fecha_pago' => $row['fecha_pago'],
            'importe_total' => $row['importe_total'],
            'tipo_pago' => $row['tipo_pago'],
            'vigente' => $row['vigente'] ? 1 : 0,
        ]);
        
    }

    return response()->json(['message' => 'Datos guardados exitosamente.'], 200);
}

    
}



