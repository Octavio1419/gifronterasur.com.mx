<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ControlInventarioController extends Controller
{
    public function vistaMayorFamilia(){

        return view('reportes_inventario.costo_familia');
    }

    public function ProducNoVendidos(Request $request, $nombre_interno, $fecha_inicio, $fecha_final, $idSucursal){
    // Nombre dinámico de la tabla de remisiones
    $tabla_remisiones = 'remisiones_' . $nombre_interno;

    try {
        // Subconsulta: calcula las ventas por producto dentro del rango de fechas
        $subquery = DB::table($tabla_remisiones . ' as r')
            ->select(
                'r.producto_id',
                DB::raw('SUM(r.cantidad_producto) AS total_vendido')
            )
            ->whereBetween('r.fecha', [$fecha_inicio, $fecha_final])
            ->groupBy('r.producto_id');

        // Consulta principal: inventario + subconsulta
        $productos = DB::table('inventario_temporal as i')
            ->leftJoinSub($subquery, 'ventas', function ($join) {
                $join->on('i.producto_id', '=', 'ventas.producto_id');
            })
            ->select(
                'i.*',
                DB::raw('COALESCE(ventas.total_vendido, 0) AS total_vendido'),
                DB::raw("
                    CASE 
                        WHEN COALESCE(ventas.total_vendido, 0) = 0 THEN 'NO SE HA VENDIDO'
                        ELSE 'SE HA VENDIDO'
                    END AS estado_venta
                ")
            )
            ->where('i.sucursal_id', $idSucursal)
            // Filtra cuando el total vendido es 0
            ->whereNull('ventas.total_vendido')
            ->orderBy('i.producto_id', 'asc')
            ->get();

                return response()->json([
                    'success' => true,
                    'data' => $productos
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'error' => $e->getMessage()
                ], 500);
            }
    }

    public function AnalisisMovimientoProductos(Request $request, $nombre_interno, $fecha_inicio, $fecha_final, $idSucursal){
    $tabla_remisiones = 'remisiones_' . $nombre_interno;

    try {
        // Subconsulta: total vendido por producto en el rango de fechas
        $subquery = DB::table($tabla_remisiones . ' as r')
            ->select(
                'r.producto_id',
                DB::raw('SUM(r.cantidad_producto) AS total_vendido')
            )
            ->whereBetween('r.fecha', [$fecha_inicio, $fecha_final])
            ->groupBy('r.producto_id');

        // Consulta principal: inventario + subconsulta de ventas
        $productos = DB::table('inventario_temporal as i')
            ->leftJoinSub($subquery, 'ventas', function ($join) {
                $join->on('i.producto_id', '=', 'ventas.producto_id');
            })
            ->select(
                'i.id',
                'i.producto_id',
                'i.descripcion_producto',
                'i.existencia',
                'i.ultimo_costo',
                DB::raw('COALESCE(ventas.total_vendido, 0) AS total_vendido'),
                DB::raw('COALESCE(ROUND(COALESCE(ventas.total_vendido, 0) / GREATEST(TIMESTAMPDIFF(MONTH, ?, ?), 1), 2), 0) AS promedio_mensual'),
                DB::raw('(i.existencia * i.ultimo_costo) AS valor_inventario')
            )
            ->addBinding([$fecha_inicio, $fecha_final], 'select')
            ->where('i.sucursal_id', $idSucursal)
            ->orderByDesc('total_vendido')
            ->get();

        // Calcular promedio general
            $promedioGeneral = $productos->avg('total_vendido');

            // Clasificar rotación
            $productos->transform(function ($p) use ($promedioGeneral) {
                if ($p->existencia <= 0) {
                    // Si la existencia es negativa o cero → Alta rotación
                    $p->estado_movimiento = 'Alta rotación';
                } elseif ($p->total_vendido >= $promedioGeneral) {
                    $p->estado_movimiento = 'Alta rotación';
                } elseif ($p->total_vendido >= $promedioGeneral / 2) {
                    $p->estado_movimiento = 'Media rotación';
                } else {
                    $p->estado_movimiento = 'Baja rotación';
                }
                return $p;
            });

            return response()->json([
                'success' => true,
                'data' => $productos,
                'promedio_general' => $promedioGeneral
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function ProductosMasVendidosPorFamilia(Request $request, $nombre_interno, $fecha_inicio, $fecha_final, $idSucursal){
        $tabla_remisiones = 'remisiones_' . $nombre_interno;
        try {
            $productos = DB::table('inventario_temporal as i')
                ->join($tabla_remisiones . ' as r', function($join) {
                    $join->on('i.producto_id', '=', 'r.producto_id')
                        ->on('i.linea', '=', 'r.producto_linea');
                })
                ->select(
                    'i.producto_id',
                    'i.descripcion_producto',
                    'i.linea',
                    DB::raw('SUM(r.cantidad_producto * i.ultimo_costo) AS total_generado')
                )
                ->whereBetween('r.fecha', [$fecha_inicio, $fecha_final])
                ->where('i.sucursal_id', $idSucursal)
                ->groupBy('i.producto_id', 'i.descripcion_producto', 'i.linea')
                ->orderByDesc('total_generado')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $productos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Metodo para obtener lo que menor se vende pero lo que mas nos genera en costo, para identificar productos que aunque no se vendan mucho el ingreso de ganancia es alto (mas dicho en volumne)
    
 




    
    
    
}
