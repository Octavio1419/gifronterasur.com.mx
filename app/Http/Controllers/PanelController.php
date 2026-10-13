<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class PanelController extends Controller
{
    /**
     * FIX: valida el formato "YYYY-MM" con regex antes de usarlo.
     * Antes: se partía el string por '-' sin validar, y $mes/$anio
     * terminaban interpolados directamente en DB::raw() dentro de
     * getReporteDirectivo() -> inyección SQL si alguien manda un
     * valor manipulado en ?periodo=.
     */
    private function getPeriodo(Request $request) {
        $periodo = $request->query('periodo'); // Recibe "2025-05"

        if ($periodo && preg_match('/^(\d{4})-(0[1-9]|1[0-2])$/', $periodo, $m)) {
            return [
                'mes'  => $m[2],
                'anio' => $m[1],
            ];
        }

        return [
            'mes' => date('m'),
            'anio' => date('Y')
        ];
    }

    /**
     * FIX: whitelist de sucursales. $nombre viene de la URL
     * (/recupera-top5/{nombre}, /grafica-.../{nombre},
     * /remisiones-pendientes/{nombre}) y se usaba para armar el
     * nombre de la tabla sin validar -> se llama al inicio de
     * cualquier método que arme "remisiones_{$nombre}".
     */
    private function resolverSucursal(string $nombre): string {
        if (!in_array($nombre, ['teran', '5ta'], true)) {
            abort(404, 'Sucursal no válida');
        }
        return $nombre;
    }

    public function metodopago(Request $request) {
        $p = $this->getPeriodo($request);
        $mesActual = $p['mes'];
        $añoActual = $p['anio'];

        $datosTeran = DB::table('cortecaja_teran')
            ->select('tipo_pago', DB::raw('ROUND(SUM(importe_total), 2) as importe_total_por_tipo'))
            ->where('vigente', 1)
            ->whereMonth('fecha_pago', $mesActual)
            ->whereYear('fecha_pago', $añoActual)
            ->groupBy('tipo_pago')->get();

        $datos5ta = DB::table('cortecaja_5ta')
            ->select('tipo_pago', DB::raw('ROUND(SUM(importe_total), 2) as importe_total_por_tipo'))
            ->where('vigente', 1)
            ->whereMonth('fecha_pago', $mesActual)
            ->whereYear('fecha_pago', $añoActual)
            ->groupBy('tipo_pago')->get();

        $remisiones_teran = DB::table('remisiones_teran')
            ->select('forma_pago', DB::raw('ROUND(SUM(total), 2) as importe_total'))
            ->where('forma_pago', 'Apartado')->where('vigente', 1)
            ->whereMonth('fecha', $mesActual)->whereYear('fecha', $añoActual)
            ->groupBy('forma_pago')->get();

        $remisiones_5ta = DB::table('remisiones_5ta')
            ->select('forma_pago', DB::raw('ROUND(SUM(total), 2) as importe_total'))
            ->where('forma_pago', 'Apartado')->where('vigente', 1)
            ->whereMonth('fecha', $mesActual)->whereYear('fecha', $añoActual)
            ->groupBy('forma_pago')->get();

        $descuentosAplicadosTeran = DB::table('remisiones_teran')
            ->select('num_remision', 'fecha', 'cliente', 'vendedor', DB::raw('ROUND(SUM(total), 2) as total'), DB::raw('ROUND(SUM(descuento_apli), 2) as descuento_aplicado'))
            ->where('vigente', 1)
            ->whereMonth('fecha', $mesActual)->whereYear('fecha', $añoActual)
            ->groupBy('num_remision', 'fecha', 'cliente', 'vendedor')
            ->having('descuento_aplicado', '>', 0)->get();

        $descuentosAplicados5ta = DB::table('remisiones_5ta')
            ->select('num_remision', 'fecha', 'cliente', 'vendedor', DB::raw('ROUND(SUM(total), 2) as total'), DB::raw('ROUND(SUM(descuento_apli), 2) as descuento_aplicado'))
            ->where('vigente', 1)
            ->whereMonth('fecha', $mesActual)->whereYear('fecha', $añoActual)
            ->groupBy('num_remision', 'fecha', 'cliente', 'vendedor')
            ->having('descuento_aplicado', '>', 0)->get();

        return response()->json([
            'tabla_teran' => $datosTeran,
            'tabla_5ta' => $datos5ta,
            'union_teran' => $remisiones_teran,
            'union_5ta' => $remisiones_5ta,
            'descuentos_teran' => $descuentosAplicadosTeran,
            'descuentos_5ta' => $descuentosAplicados5ta
        ]);
    }

    public function vendedorMes(Request $request){
        $p = $this->getPeriodo($request);

        $vendedores_teran = DB::table('remisiones_teran')
            ->select('vendedor',DB::raw('ROUND(SUM(total),2) as total_vendedor'))
            ->where('vigente', 1)->whereMonth('fecha', $p['mes'])->whereYear('fecha', $p['anio'])
            ->groupBy('vendedor')->get();

        $vendedores_5ta = DB::table('remisiones_5ta')
            ->select('vendedor',DB::raw('ROUND(SUM(total),2) as total_vendedor'))
            ->where('vigente', 1)->whereMonth('fecha', $p['mes'])->whereYear('fecha', $p['anio'])
            ->groupBy('vendedor')->get();

        $comisiones_teran = DB::select('SELECT id, tipo_empleado, porcentaje_actual FROM comisiones_teran ORDER BY id');

        return response()->json([
            'vendedores_teran'=>$vendedores_teran,
            'vendedores_5ta'=>$vendedores_5ta,
            'comisiones_teran'=>$comisiones_teran
        ]);
    }

    public function ventasdiashabiles(Request $request, $nombre){
        $nombre = $this->resolverSucursal($nombre); // <-- agregado
        $p = $this->getPeriodo($request);
        $queryTienda = 'remisiones_'.$nombre;

        $totales = DB::table($queryTienda)
            ->select(DB::raw('DATE(fecha) as fecha'), DB::raw('ROUND(SUM(total), 2) as total_vendido'))
            ->where('vigente', true)->whereMonth('fecha', $p['mes'])->whereYear('fecha', $p['anio'])
            ->groupBy(DB::raw('DATE(fecha)'))->orderBy('fecha', 'asc')->get();

        return response()->json($totales);
    }

    public function ventasdiashabilesdefault(Request $request){
        $p = $this->getPeriodo($request);

        $totales_teran = DB::table('cortecaja_teran')
            ->select(DB::raw('DATE(fecha_pago) as fecha_pago'), DB::raw('ROUND(SUM(importe_total), 2) as total_vendido'))
            ->where('vigente', 1)->whereMonth('fecha_pago', $p['mes'])->whereYear('fecha_pago', $p['anio'])
            ->groupBy(DB::raw('DATE(fecha_pago)'))->orderBy('fecha_pago', 'asc')->get();

        $totales_5ta = DB::table('cortecaja_5ta')
            ->select(DB::raw('DATE(fecha_pago) as fecha_pago'), DB::raw('ROUND(SUM(importe_total), 2) as total_vendido'))
            ->where('vigente', 1)->whereMonth('fecha_pago', $p['mes'])->whereYear('fecha_pago', $p['anio'])
            ->groupBy(DB::raw('DATE(fecha_pago)'))->orderBy('fecha_pago', 'asc')->get();

        return response()->json(['totales_teran'=>$totales_teran,'totales_5ta'=>$totales_5ta]);
    }

    public function top5mes(Request $request, $nombre){
        $nombre = $this->resolverSucursal($nombre); // <-- agregado
        $p = $this->getPeriodo($request);
        $queryproductos = 'remisiones_'.$nombre;

        $producto = DB::table($queryproductos)
            ->select('producto_descripcion', DB::raw('SUM(cantidad_producto) AS total'))
            ->where('vigente', true)->whereMonth('fecha', $p['mes'])->whereYear('fecha', $p['anio'])
            ->where('producto_descripcion', 'NOT LIKE', '%MT%')
            ->groupBy('producto_descripcion')->orderByDesc('total')->limit(5)->get();

        return response()->json($producto);
    }

    public function top5mesDefault(Request $request){
        $p = $this->getPeriodo($request);

        $producto = DB::table('remisiones_teran')
            ->select('producto_descripcion', DB::raw('SUM(cantidad_producto) AS total'))
            ->where('vigente', true)->whereMonth('fecha', $p['mes'])->whereYear('fecha', $p['anio'])
            ->where('producto_descripcion', 'NOT LIKE', '%MT%')
            ->groupBy('producto_descripcion')->orderByDesc('total')->limit(5)->get();

        return response()->json($producto);
    }

    public function metadeventateran(Request $request){
        $p = $this->getPeriodo($request);

        $meta_venta_tera = DB::table('meta_tienda_teran')
            ->select('maximo_ventas', 'fecha')
            ->whereMonth('fecha', $p['mes'])->whereYear('fecha', $p['anio'])->get();

        $meta_venta_5ta = DB::table('meta_tienda_5ta')
            ->select('maximo_ventas', 'fecha')
            ->whereMonth('fecha', $p['mes'])->whereYear('fecha', $p['anio'])->get();

        return response()->json(['meta_venta_teran'=>$meta_venta_tera, 'meta_venta_5ta'=>$meta_venta_5ta]);
    }

    public function remisionesconsaldopendiente(Request $request, $nombre){
        $nombre = $this->resolverSucursal($nombre); // <-- agregado
        $p = $this->getPeriodo($request);
        $queryConsulta = 'remisiones_'.$nombre;

        $buscaremisionesconsaldo = DB::table($queryConsulta)
            ->select('num_remision','fecha','cliente','vendedor', DB::raw('ROUND(SUM(total), 2) as importe_total'))
            ->where('forma_pago', 'Apartado')->where('vigente', 1)
            ->whereMonth('fecha', $p['mes'])->whereYear('fecha', $p['anio'])
            ->groupBy('num_remision','fecha', 'cliente','vendedor')->get();

        return response()->json($buscaremisionesconsaldo);
    }

    /**
     * FIX: simplificado. DB::select() nunca regresa null (regresa []
     * si no hay filas), así que el "else" original era código muerto
     * en la práctica -- y con la comparación floja "!= null" de PHP,
     * si sucursales llegaba a estar legítimamente vacía se reportaba
     * un "error" sin que lo hubiera. También se cambió json_encode()
     * por response()->json() para ser consistente con el resto del
     * controlador (header Content-Type correcto).
     */
    public function recuperarsucursal(Request $request){
        $sucursales = DB::select('SELECT id, nombre_interno, nombre_real FROM sucursales ORDER BY id');
        return response()->json($sucursales);
    }

    //COTIZACIONES
    public function getReporteDirectivo(Request $request) {
        $p = $this->getPeriodo($request);
        $mes  = (int) $p['mes'];
        $anio = (int) $p['anio'];

        $kpis = DB::table('cotizaciones as c')
        ->join('cotizaciones_foliadas as cf', 'c.id', '=', 'cf.id_cotizacion')
        ->whereMonth('cf.fecha_de_autorizacion', $mes)
        ->whereYear('cf.fecha_de_autorizacion', $anio)
        ->select([

            DB::raw("SUM(c.total_cotizacion) as inversion_autorizada"),

            DB::raw("(SELECT SUM((dc.precio_lista - dc.precio_promocion) * dc.cant_a_pedir)
                    FROM datos_cruzados dc
                    JOIN cotizaciones c2 ON dc.cotizacion_id = c2.id
                    JOIN cotizaciones_foliadas cf2 ON c2.id = cf2.id_cotizacion
                    WHERE MONTH(cf2.fecha_de_autorizacion) = $mes
                    AND YEAR(cf2.fecha_de_autorizacion) = $anio) as ahorro_total"),

            DB::raw("COUNT(DISTINCT c.id) as total_folios"),

            // FIX: se agregó "AND YEAR(fecha_cotizacion) = $anio" -- antes
            // mezclaba pendientes del mismo mes de CUALQUIER año.
            DB::raw("(SELECT SUM(total_cotizacion) FROM cotizaciones
                    WHERE folio_autorizado = 0 AND vigente = 1
                    AND MONTH(fecha_cotizacion) = $mes
                    AND YEAR(fecha_cotizacion) = $anio) as pipeline_pendiente")
        ])
        ->first();

        $proveedores = DB::table('cotizaciones as c')
            ->join('cotizaciones_foliadas as cf', 'c.id', '=', 'cf.id_cotizacion')
            ->select('c.proveedor', DB::raw('SUM(c.total_cotizacion) as total'))
            ->where('c.folio_autorizado', 1)
            ->whereMonth('cf.fecha_de_autorizacion', $mes)
            ->whereYear('cf.fecha_de_autorizacion', $anio)
            ->groupBy('c.proveedor')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $tiposPedido = DB::table('cotizaciones_foliadas')
            ->select('tipo_pedido', DB::raw('count(*) as cantidad'))
            ->whereMonth('fecha_de_autorizacion', $mes)
            ->whereYear('fecha_de_autorizacion', $anio)
            ->groupBy('tipo_pedido')
            ->get();

        $ventaPerdida = DB::table('datos_cruzados as dc')
            ->where('dc.existencia_en_sucur', '<=', 0)
            ->where('dc.promedio_mensual_ventas', '>', 0)
            ->whereMonth('dc.created_at', $mes)
            ->whereYear('dc.created_at', $anio)
            ->select(DB::raw('SUM(dc.promedio_mensual_ventas * dc.precio_lista) as monto_perdido_estimado'))
            ->first();

        $sucursales = DB::table('cotizaciones as c')
            ->join('cotizaciones_foliadas as cf', 'c.id', '=', 'cf.id_cotizacion')
            ->select('c.sucursal_id', DB::raw('SUM(c.total_cotizacion) as total'))
            ->where('c.folio_autorizado', 1)
            ->whereMonth('cf.fecha_de_autorizacion', $mes)
            ->whereYear('cf.fecha_de_autorizacion', $anio)
            ->groupBy('c.sucursal_id')
            ->get();

        $tablaDetallada = DB::table('cotizaciones as c')
            ->leftJoin('cotizaciones_foliadas as cf', 'c.id', '=', 'cf.id_cotizacion')
            ->select([
                'c.id',
                'c.fecha_cotizacion as fecha',
                'c.sucursal_id',
                'c.proveedor',
                'c.total_cotizacion as total',
                'c.folio_autorizado',
                'cf.folio',
                'cf.fecha_de_autorizacion',
                DB::raw("(SELECT SUM((dc2.precio_lista - dc2.precio_promocion) * dc2.cant_a_pedir)
                        FROM datos_cruzados dc2
                        WHERE dc2.cotizacion_id = c.id) as ahorro")
            ])
            ->where(function($query) use ($mes, $anio) {
                $query->whereMonth('c.fecha_cotizacion', $mes)->whereYear('c.fecha_cotizacion', $anio)
                    ->orWhereMonth('cf.fecha_de_autorizacion', $mes)->whereYear('cf.fecha_de_autorizacion', $anio);
            })
            ->orderBy('c.id', 'desc')
            ->get();

        return response()->json([
            'kpis' => $kpis,
            'grafica_proveedores' => $proveedores,
            'grafica_tipos' => $tiposPedido,
            'venta_perdida' => $ventaPerdida->monto_perdido_estimado ?? 0,
            'sucursales' => $sucursales,
            'tabla_detallada' => $tablaDetallada,
            'periodo' => "$anio-$mes"
        ]);
    }

    /**
     * NUEVO: proyección de cierre de mes (run-rate).
     * Con el ritmo de venta diario que llevas en lo que va del mes,
     * calcula en cuánto vas a cerrar si sigues así, y cuánto tienes
     * que vender por día en lo que resta del mes para llegar a la meta.
     *
     * Si el periodo consultado NO es el mes actual (el gerente
     * seleccionó un mes ya cerrado), no tiene sentido "proyectar" --
     * ese mes ya terminó -- así que se regresa 'es_mes_actual: false'
     * y el frontend muestra el resultado final en vez de una proyección.
     */
    public function proyeccionCierreMes(Request $request) {
        $p = $this->getPeriodo($request);
        $mes  = (int) $p['mes'];
        $anio = (int) $p['anio'];
 
        $hoy = now();
        $esMesActual = ($mes === (int) $hoy->format('m')) && ($anio === (int) $hoy->format('Y'));
 
        $diasEnMes = (int) date('t', mktime(0, 0, 0, $mes, 1, $anio));
        $diasTranscurridos = $esMesActual ? (int) $hoy->format('d') : $diasEnMes;
        $diasTranscurridos = max($diasTranscurridos, 1); // evita división por cero
 
        $ventaTeran = (float) DB::table('cortecaja_teran')
            ->where('vigente', 1)
            ->whereMonth('fecha_pago', $mes)->whereYear('fecha_pago', $anio)
            ->sum('importe_total');
 
        $venta5ta = (float) DB::table('cortecaja_5ta')
            ->where('vigente', 1)
            ->whereMonth('fecha_pago', $mes)->whereYear('fecha_pago', $anio)
            ->sum('importe_total');
 
        $ventaActual = round($ventaTeran + $venta5ta, 2);
 
        $metaTeran = (float) DB::table('meta_tienda_teran')
            ->whereMonth('fecha', $mes)->whereYear('fecha', $anio)
            ->sum('maximo_ventas');
 
        $meta5ta = (float) DB::table('meta_tienda_5ta')
            ->whereMonth('fecha', $mes)->whereYear('fecha', $anio)
            ->sum('maximo_ventas');
 
        $metaTotal = round($metaTeran + $meta5ta, 2);
 
        $ritmoDiarioActual = round($ventaActual / $diasTranscurridos, 2);
        $proyeccionCierre = round($ritmoDiarioActual * $diasEnMes, 2);
 
        $diasRestantes = max($diasEnMes - $diasTranscurridos, 0);
        $faltanteParaMeta = max($metaTotal - $ventaActual, 0);
        $ritmoNecesarioDiario = $diasRestantes > 0
            ? round($faltanteParaMeta / $diasRestantes, 2)
            : $faltanteParaMeta;
 
        $vaAlcanzarMeta = $metaTotal > 0 ? ($proyeccionCierre >= $metaTotal) : null;
 
        return response()->json([
            'es_mes_actual'          => $esMesActual,
            'dias_en_mes'            => $diasEnMes,
            'dias_transcurridos'     => $diasTranscurridos,
            'dias_restantes'         => $diasRestantes,
            'venta_actual'           => $ventaActual,
            'meta_total'             => $metaTotal,
            'ritmo_diario_actual'    => $ritmoDiarioActual,
            'proyeccion_cierre'      => $proyeccionCierre,
            'ritmo_necesario_diario' => $ritmoNecesarioDiario,
            'va_alcanzar_meta'       => $vaAlcanzarMeta,
        ]);
    }
}