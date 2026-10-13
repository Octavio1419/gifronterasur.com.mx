<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RemisionesController extends Controller
{
    // FUNCION PARA LA VISTA DE SUBIR LAS REMISIONES

    public function subiremisiones(){

        return view('remisiones.vista_remisiones');
    }

    /*FUNCION QUE MANDA A GUARDAR LOS DATOS A LA TABLA */
    public function cargarremisiones(Request $request, $nombre) {
        $datos_excel_remisiones = $request->json()->all();
        //dd($datos_excel_remisiones);
        $consulta_remision = 'remisiones_'.$nombre;
        $consulta_caja = 'cortecaja_'.$nombre;

        $rowsToInsert = [];

        foreach ($datos_excel_remisiones as $row) {
            $row['vigente'] = filter_var($row['vigente'], FILTER_VALIDATE_BOOLEAN);

            $validator = Validator::make($row, [
                'cantidad_producto' => 'required|string|max:250',
                'cliente' => 'required|string|max:500',
                'colonia' => 'required|string|max:500',
                'fecha' => 'required|date',
                'iva' => 'required|numeric',
                'municipio' => 'required|string|max:500',
                'num_remision' => 'required|string|max:250',
                'precio' => 'required|numeric',
                'producto_clave' => 'required|string|max:500',
                'producto_descripcion' => 'required|string|max:500',
                'producto_id' => 'required|numeric',
                'producto_linea' => 'required|string|max:500',
                'subtotal' => 'required|numeric',
                'total' => 'required|numeric',
                'vendedor' => 'required|string|max:500',
                'vigente' => 'required|boolean',
                'costo_promedio'=> 'required|numeric',
                'ultimo_costo'=> 'required|numeric',
                'descuento_apli'=> 'nullable|numeric',
                
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validación fallida',
                    'message' => $validator->errors()
                ], 400);
            }

            $rowsToInsert[] = [
                'num_remision'      => $row['num_remision'],
                'fecha'             => $row['fecha'],
                'cliente'           => $row['cliente'],
                'colonia'           => $row['colonia'],
                'municipio'         => $row['municipio'],
                'vendedor'          => $row['vendedor'],
                'producto_id'       => $row['producto_id'],
                'producto_clave'    => $row['producto_clave'],
                'producto_descripcion' => $row['producto_descripcion'],
                'producto_linea'    => $row['producto_linea'],
                'cantidad_producto' => $row['cantidad_producto'],
                'precio'            => $row['precio'],
                'subtotal'          => $row['subtotal'],
                'iva'               => $row['iva'],
                'total'             => $row['total'],
                'vigente'           => $row['vigente'] ? 1 : 0,
                'costo_promedio'    => $row['costo_promedio'],
                'ultimo_costo'      => $row['ultimo_costo'],
                'descuento_apli'    => $row['descuento_apli'] 
                
            ];
        }
    
        if (!empty($rowsToInsert)) {
            DB::table($consulta_remision)->insert($rowsToInsert);
        }

        $remisionesInsertadas = array_column($rowsToInsert, 'num_remision');

        DB::table($consulta_remision . ' as rt')
            ->leftJoin($consulta_caja . ' as cc', 'rt.num_remision', '=', 'cc.remision')
            ->whereIn('rt.num_remision', $remisionesInsertadas)
            ->update([
                'rt.forma_pago' => DB::raw("CASE 
                    WHEN cc.tipo_pago IS NOT NULL THEN cc.tipo_pago 
                    ELSE 'Apartado' END")
            ]);


        return response()->json(['message' => 'Datos guardados exitosamente.'], 200);
    }

}

