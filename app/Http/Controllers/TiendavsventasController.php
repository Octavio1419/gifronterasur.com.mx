<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class TiendavsventasController extends Controller
{
    public function tiendasvsventas()
    {
        return view('tiendasvsventas.tiendas_vs_ventas');
    }  
    
    /*funcion que recupera todas las tiendas */
    public function recuperaTiendas(Request $request){

        $sucursales = DB::table('sucursales')->orderBy('id','asc')->get();

        if($sucursales != null){

            return response()->json(['sucursales' => $sucursales]);
        }else{
            return response()->json(['error' => 'No se encontraron sucursales']);
        }

    }

    public function recuperaDiasVentas(Request $request,$fecha_inicio, $fecha_final, $teran,$quinta){
        $query_caja_teran = 'cortecaja_'.$teran;
        $query_caja_quinta = 'cortecaja_'.$quinta;

        //RECUPERANDO LOS DIAS HABILES DE VENTAS DE LA TIENDA TERAN
        $dias_teran = DB::table($query_caja_teran)
            ->select('fecha_pago', DB::raw('ROUND(SUM(importe_total), 2) as total_dia'))
            ->where('vigente', 1)
            ->whereBetween('fecha_pago', [$fecha_inicio, $fecha_final])
            ->groupBy('fecha_pago')
            ->orderBy('fecha_pago', 'asc')
            ->get();

        //RECUPERANDO LOS DIAS HABILES DE VENTAS DE LA TIENDA QUINTA
        $dias_quinta = DB::table($query_caja_quinta)
            ->select('fecha_pago', DB::raw('ROUND(SUM(importe_total), 2) as total_dia'))
            ->where('vigente', 1)
            ->whereBetween('fecha_pago', [$fecha_inicio, $fecha_final])
            ->groupBy('fecha_pago')
            ->orderBy('fecha_pago', 'asc')
            ->get();

        if($dias_teran != null && $dias_quinta != null){

            return response()->json(['dias_teran' => $dias_teran, 'dias_quinta' => $dias_quinta]);

        }else{
            
            return response()->json(['error' => 'No se encontraron dias de ventas en las tiendas seleccionadas']);
        }
    }
}
