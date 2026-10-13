<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class GenerarReportesController extends Controller
{
    /* vista principal para el apartado de reportes*/
    public function vistaprincipalreportes(){

        return view('reportes.vista_reporte');
    }

    /*vista principal para los reportes de comisiones por vendedores */
    public function vistaprincipalcomisiones(){

        return view('reportes.vista_reporte_comisiones');
    }

    /*recuperando sucursales para el selector */
    public function selectorTienda(Request $request){

        $recu_sucu = DB::table('sucursales')->orderBy('id','asc')->get();

        if($recu_sucu != null){
            return response()->json(['sucursal'=>$recu_sucu]);
        }else{
            return response()->json(['error'=>'Error al intentar recuperar sucursal'],404);
        }
    }

    public function recuperadatospdf(Request $request, $nombre_interno, $fecha_i, $fecha_f) {

    $query_caja = 'cortecaja_'.$nombre_interno;
    $query_remisiones = 'remisiones_'.$nombre_interno;
    $query_meta_tienda = 'meta_tienda_'.$nombre_interno;

    if (!$nombre_interno || !$fecha_i || !$fecha_f) {
        return response()->json(['error' => 'Parámetros insuficientes'], 400);
    }
    
    //SACA LOS METODOS DE PAGO
    $metodo_pago = DB::table($query_caja)
        ->select('tipo_pago', DB::raw('ROUND(SUM(importe_total), 2) as importe_total_por_tipo'))
        ->where('vigente', 1)
        ->whereBetween('fecha_pago', [$fecha_i, $fecha_f])
        ->groupBy('tipo_pago')
        ->get();
    //SACA DE LA TABLA REMISIONES SI HAY ALGUNA CON SALDO PENDIENTE 
    //dd($metodo_pago);
     

    $apartados = DB::table($query_remisiones)
        ->select('forma_pago', DB::raw('ROUND(SUM(total), 2) as importe_total'))
        ->where('forma_pago', 'Apartado')
        ->where('vigente', 1)
        ->whereBetween('fecha', [$fecha_i, $fecha_f])
        ->groupBy('forma_pago')
        ->get();
    // aca se recupera todos los vendedores y se suma cada cuanto vendio cada uno, pero ahora se tiene una condicion que sume de la columna descuento_apli y la suma de cada uno se le multiplique el 0.16 que es el iva y se reste al total antes calculado de venta por vendedor

    //$vendedor = DB::table($query_remisiones)->select('vendedor',DB::raw('ROUND(SUM(total),2) as total_vendedor'))->where('vigente', 1)->whereBetween('fecha', [$fecha_i, $fecha_f])->groupBy('vendedor')->get();

    $vendedor = DB::table($query_remisiones)
    ->select(
        'vendedor',
        //HACIENDO LA SUMA POR CADA VENDEDOR DEL TOTAL
        DB::raw('ROUND(SUM(total),2) as total_vendedor'),
        //HACIENDO LA SUMAN DE DESCUENTO_APLI POR EL DESCUENTO APLICADO POR CADA VENDEDOR, PERO ACA EN LA SUMA SE LE MULTIPLICA EL 0.16 QUE ES EL IVA
        DB::raw('ROUND(SUM(descuento_apli), 2) as total_descuento_iva'),
        //MULTIPLICANDO EL TOTAL_DESCUENTIO_IVA POR 0.16 QUE ES EL IVA
        DB::raw('ROUND(SUM(descuento_apli * 0.16), 2) as total_descuento_aplicado'),
        //SUMANDO EL TOTAL_DESCUENTO_APLICADO + EL TOTAL_DESCUENTO_IVA ACA  NO SE PUEDE SUMAR EL TOTAL_DESCUENTO_IVA CON EL TOTAL_dESCUENTO_APLICADO?
        DB::raw('ROUND(SUM(descuento_apli) + SUM(descuento_apli * 0.16), 2) as suma_total_descuento'),

        //YA POR ULTIMO SE HACE LA RESTA DE TOTAL MENOS EL DESCUENTO APLICADO POR CADA VENDEDOR
        //ACA VAMOS A RESTAR EL SUMA_TOTAL_DESCUENTO AL TOTAL_VENDEDOR
        DB::raw('ROUND(SUM(total) - (SUM(descuento_apli) + SUM(descuento_apli * 0.16)), 2) as total_final_vendedor')
    )
    ->where('vigente', 1)
    ->whereBetween('fecha', [$fecha_i, $fecha_f])
    ->groupBy('vendedor')
    ->get();

    //generar otra tabla, donde guarde el total de cada usuario que aplico el descuento, guarda el num_remision (agrupar la remision sin son varios), fecha(tiene que ser la fecha que se aplica los descuentos),cliente, vendedor, total y el desc_aplicado con la condicion que esta tabla solo va aparecer cuando se detecte que tiene descuento, si no hay no pone nada

    $descuentosAplicados = DB::table($query_remisiones)
        ->select('num_remision', 'fecha', 'cliente', 'vendedor', DB::raw('ROUND(SUM(total), 2) as total'), DB::raw('ROUND(SUM(descuento_apli), 2) as descuento_aplicado'))
        ->where('vigente', 1)
        ->whereBetween('fecha', [$fecha_i, $fecha_f])
        ->groupBy('num_remision', 'fecha', 'cliente', 'vendedor')
        ->having('descuento_aplicado', '>', 0)
        ->get();


        $sql = "
            SELECT 
                vendedor,
                cliente,
                colonia,
                municipio,
                total_comprado
            FROM (
                SELECT 
                    vendedor,
                    cliente,
                    colonia,
                    municipio,
                    total_comprado,
                    ROW_NUMBER() OVER (
                        PARTITION BY vendedor 
                        ORDER BY total_comprado DESC
                    ) AS rn
                FROM (
                    SELECT 
                        vendedor,
                        cliente,
                        colonia,
                        municipio,
                        SUM(total) AS total_comprado
                    FROM {$query_remisiones}
                    WHERE vigente = 0
                    AND fecha BETWEEN ? AND ?
                    GROUP BY vendedor, cliente, colonia, municipio
                ) AS sumado
            ) AS rankeado
            WHERE rn <= 5
            ORDER BY vendedor, total_comprado DESC;";

        $topClientes = DB::select($sql, [$fecha_i, $fecha_f]);

        $remisiones_con_saldo = DB::table($query_remisiones)->select('num_remision','fecha','cliente','vendedor', DB::raw('ROUND(SUM(total), 2) as importe_total'))->where('forma_pago', 'Apartado')->where('vigente', 1)->whereBetween('fecha',[$fecha_i, $fecha_f])->groupBy('num_remision','fecha', 'cliente','vendedor')->get();

        $remisiones_canceladas = DB::table($query_remisiones)->select('num_remision','cliente','vendedor','fecha', DB::raw('ROUND(SUM(total), 2) as importe_total'))->where('vigente', 0)->whereBetween('fecha', [$fecha_i, $fecha_f])->groupBy('num_remision', 'fecha', 'cliente', 'vendedor')->get();
        

        $ventashabiles = DB::table($query_caja)
        ->select(
            DB::raw('DATE(fecha_pago) as fecha'), // Extraer solo la parte de la fecha completa
            DB::raw('ROUND(SUM(importe_total), 2) as total_vendido') 
        )
        ->where('vigente', true) 
        ->whereBetween('fecha_pago', [$fecha_i, $fecha_f]) 
        ->groupBy(DB::raw('DATE(fecha_pago)')) 
        ->orderBy('fecha_pago', 'asc') 
        ->get();
        
        //cambiando rango de fecha para que acepte siempore el dia del mes
        $fecha_i_cambiada = date('Y-m-01', strtotime($fecha_i));
        $fecha_f_cambiada = date('Y-m-01', strtotime($fecha_f));

        $meta_tiendas = DB::table($query_meta_tienda)
            ->select('maximo_ventas', 'fecha')
            ->whereBetween('fecha', [$fecha_i_cambiada, $fecha_f_cambiada])
            ->get();
        
        /*METODO PARA RECUPERAR EL COSTO TOTAL DE LAS REMISIONES */
        $coste_total = DB::table($query_remisiones)
        ->select(
            'producto_id',
            'producto_descripcion',
            DB::raw('SUM(cantidad_producto) as total_cantidad'),
            DB::raw('MAX(ultimo_costo) as ultimo_costo'), 
            DB::raw('ROUND(SUM(cantidad_producto * ultimo_costo), 2) as total_costo_venta')
        )
        ->where('vigente', true)
        ->whereBetween('fecha', [$fecha_i, $fecha_f])
        ->groupBy('producto_id', 'producto_descripcion')
        ->orderBy('producto_id', 'asc')
        ->get();

        /*recuperar el usuario logueado */   
        $user = Auth::user();
        // Retornar los datos en formato JSON

        //vamos a retornar las cotizaciones que han hecho en el mismo rango de fecha
        return response()->json([
            'metodo_pago'=>$metodo_pago,   
            'apartados'=>$apartados, 
            'vendedor'=>$vendedor,
            'ventashabiles'=>$ventashabiles,  'remisiones_con_saldo'=>$remisiones_con_saldo,   'remisiones_canceladas'=>$remisiones_canceladas,          'meta_tiendas'=>$meta_tiendas,
            'usuario'=>$user,
            'coste_total'=>$coste_total,
            'topClientes'=>$topClientes,
           'descuentosAplicados'=>$descuentosAplicados
        ],200);   
    }


    //vista para el reporte de cotizaciones vs ventas
    public function vistaCotizacionesvsVentas(){

        return view('reportes.cotizacionesvsventas');
    }

        public function generarAnalisisCotizacionesvsVentas($id, $nombre_interno, $fecha_i, $fecha_f) {
    $sucursal_id_para_filtro = $id;
    $query_remisiones = 'remisiones_' . $nombre_interno;
    $query_caja = 'cortecaja_' . $nombre_interno;

    // 1. PRODUCTOS COTIZADOS (COMPRA)
    $productos_cotizados = DB::table('cotizacion_productos AS cp')
        ->join('cotizaciones AS c', 'cp.cotizacion_id', '=', 'c.id')
        ->where('c.sucursal_id', $sucursal_id_para_filtro)
        ->where('c.folio_autorizado', 1) 
        ->whereBetween('c.fecha_cotizacion', [$fecha_i . ' 00:00:00', $fecha_f . ' 23:59:59'])
        ->select(
            'cp.codigo',
            DB::raw('MIN(cp.nombre) AS nombre_producto_referencia'),
            DB::raw('MIN(cp.um) AS unidad_medida'),
            DB::raw('ROUND(SUM(cp.cant_a_pedir), 2) AS total_cotizado'),
            DB::raw('ROUND(SUM(cp.importe), 2) AS importe_cotizado_sin_iva')
        )
        ->groupBy('cp.codigo')->get();

    // 2. PRODUCTOS VENDIDOS
    $productos_vendidos = DB::table($query_remisiones)
        ->where('vigente', 1)
        ->whereBetween('fecha', [$fecha_i . ' 00:00:00', $fecha_f . ' 23:59:59'])
        ->select(
            'producto_clave',
            DB::raw('ROUND(SUM(cantidad_producto), 2) AS total_vendido'),
            DB::raw('ROUND(SUM(total), 2) AS importe_vendido_total')
        )
        ->groupBy('producto_clave')->get()->keyBy('producto_clave');

    // 3. EXISTENCIA AL CIERRE
    $inventario_real = DB::table('inventario_temporal')
        ->where('sucursal_id', $sucursal_id_para_filtro)
        ->select('clave_producto', DB::raw('SUM(existencia) AS existencia_real'))
        ->groupBy('clave_producto')->get()->keyBy('clave_producto');

    // 4. MAPEADO Y RENDIMIENTO
    $reporte_comparativa = $productos_cotizados->map(function ($cotizado) use ($productos_vendidos, $inventario_real) { 
        $codigo = $cotizado->codigo;
        $vendido = $productos_vendidos->get($codigo);
        $stock_raw = $inventario_real->get($codigo);
        
        $existencia_al_cierre = $stock_raw ? (float)$stock_raw->existencia_real : 0;
        $unidades_cotizadas = (float)$cotizado->total_cotizado;
        $unidades_vendidas = $vendido ? (float)$vendido->total_vendido : 0;
        
        $rendimiento = $unidades_cotizadas > 0 ? ($unidades_vendidas / $unidades_cotizadas) * 100 : 0;

        return [
            'codigo' => $codigo,
            'nombre' => $cotizado->nombre_producto_referencia,
            'um' => $cotizado->unidad_medida,
            'comprado_u' => $unidades_cotizadas,
            'vendido_u' => $unidades_vendidas,
            'rendimiento' => round($rendimiento, 2),
            'stock_actual' => $existencia_al_cierre,
            'importe_compra' => round($cotizado->importe_cotizado_sin_iva * 1.16, 2),
            'importe_venta' => $vendido ? (float)$vendido->importe_vendido_total : 0
        ];
    });

    // 5. CLASIFICACIÓN CON REGLA DE NEGATIVOS
    // Si el stock es negativo, va a exitosos sin importar el rendimiento porcentual
    $exitosos = $reporte_comparativa->filter(function($i) {
        return $i['rendimiento'] >= 100 || $i['stock_actual'] < 0;
    })->values();

    $parciales = $reporte_comparativa->filter(function($i) {
        return $i['rendimiento'] > 0 && $i['rendimiento'] < 100 && $i['stock_actual'] >= 0;
    })->values();

    $sin_venta = $reporte_comparativa->filter(function($i) {
        return $i['rendimiento'] <= 0 && $i['stock_actual'] >= 0;
    })->values();

    return response()->json([
        'sucursal' => $nombre_interno,
        'periodo' => ['inicio' => $fecha_i, 'fin' => $fecha_f],
        'totales' => [
            'compra_total' => round($reporte_comparativa->sum('importe_compra'), 2),
            'venta_total_real' => round($reporte_comparativa->sum('importe_venta'), 2),
            'compra_exitosos' => round($exitosos->sum('importe_compra'), 2),
            'compra_parciales' => round($parciales->sum('importe_compra'), 2),
            'compra_almacenado' => round($sin_venta->sum('importe_compra'), 2),
            'venta_exitosos' => round($exitosos->sum('importe_venta'), 2),
            'venta_parciales' => round($parciales->sum('importe_venta'), 2),
        ],
        'data' => [
            'exitosos' => $exitosos,
            'parciales' => $parciales,
            'sin_venta' => $sin_venta
        ]
    ], 200);
}

    public function productosMasVendidos(Request $request, $nombre_interno) {
    $sucursal = $nombre_interno;
    
    if (!$sucursal) {
        return response()->json(['error' => 'Parámetros insuficientes'], 400);
    }

    $tabla = 'remisiones_' . $sucursal;

    // 1. Calculamos el Monto Total General de todas las ventas en ese periodo
    // Esto sirve como base para sacar el porcentaje después
    $montoTotalVentas = DB::table($tabla)
        ->where('vigente', true)
        ->whereBetween('fecha', ['2025-01-01', DB::raw('CURDATE()')])
        ->sum('total'); // Cambia 'total_precio' por el nombre de tu columna de dinero

    // 2. Consulta principal con cálculos de porcentaje
    $productosMasVendidos = DB::table($tabla)
        ->select(
            'producto_descripcion',
            'producto_clave',
            DB::raw('SUM(cantidad_producto) as total_piezas'),
            DB::raw('SUM(total) as volumen_dinero'),
            // Calculamos el %: (Suma producto / Gran Total) * 100
            DB::raw("ROUND((SUM(total) / $montoTotalVentas) * 100, 2) as porcentaje_ventas")
        )
        ->where('vigente', true)
        ->whereBetween('fecha', ['2025-01-01', DB::raw('CURDATE()')])
        ->groupBy('producto_descripcion', 'producto_clave')
        ->orderBy('volumen_dinero', 'desc') 
        
        ->get();

    return response()->json([
        'monto_total_periodo' => $montoTotalVentas,
        'productos_mas_vendidos' => $productosMasVendidos
    ], 200);
}    
}
