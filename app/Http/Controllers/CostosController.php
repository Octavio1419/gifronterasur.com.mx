<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CostosController extends Controller
{
    /*VISTA PARA PODER SUBIR EL EXCEL DE LOS COSTOS AL SERVIDOR */
    public function cargarUtilidades(){
        

        return view('utilidades.cargar_utilidades');
    }

    public function subirUtilidades(Request $request, $sucursal){
        $datos_excel_utilidades = $request->json()->all();
        $consulta_utilidades = 'transpaso_tiendas';
        dd($consulta_utilidades);
        foreach($datos_excel_utilidades as $row){
            $row['vigente'] = filter_var($row['vigente'], FILTER_VALIDATE_BOOLEAN);

            $validator = Validator::make($row,[
                'num_transpaso' => 'required|numeric',
                'sucursal_origen' => 'required|string|max:500',
                'sucursal_destino' => 'required|string|max:500',
                'producto_clave' => 'required|string|max:500',
                'producto_descripcion' => 'required|string|max:500',
                'producto_id' => 'required|numeric',
                'proovedor' => 'required|string|max:500',
                'fecha_transpaso' => 'required|date',
                'vigente' => 'required|boolean',
                'tipo_movimiento' => 'required|string|max:500',
                'cantidad_transpaso'=> 'required|numeric',
                'costo_total' => 'required|numeric',
                'linea' => 'required|string|max:500',
                'color' => 'required|string|max:500',
                'sucursal_id'=> 'required|numeric',
            
                
            ]);

            if($validator->fails()){
                return response()->json([
                    'error' => 'Validación fallida',
                    'message' => $validator->errors()
                ], 400);
            }

            DB::table($consulta_utilidades)->insert([
                'num_transpaso' => $row['num_transpaso'],
                'sucursal_origen' => $row['sucursal_origen'],
                'sucursal_destino' => $row['sucursal_destino'],
                'producto_clave' => $row['producto_clave'],
                'producto_descripcion' => $row['producto_descripcion'],
                'producto_id' => $row['producto_id'],
                'proovedor' => $row['proovedor'],
                'fecha_transpaso' => $row['fecha_transpaso'],
                'vigente' => $row['vigente'],
                'tipo_movimiento' => $row['tipo_movimiento'],
                'cantidad_transpaso' => $row['cantidad_transpaso'],
                'costo_total' => $row['costo_total'],
                'linea' => $row['linea'],
                'color' => $row['color'],
                'sucursal_id' => $row['sucursal_id'],
            ]);
          
        }

        return response()->json([
            'message' => 'Datos de utilidades cargados correctamente.'
        ], 200);


    }

    public function VistacalculaUtilidades(){
        
        return view('utilidades.vista_genera_utilidades');
    }
    /*
    public function recuperaUtilidades(Request $request, $sucursal, $fecha_inicio, $fecha_final){

        $tablaRemisiones = 'remisiones_'.$sucursal;
        $tablaCorteCaja  = 'cortecaja_'.$sucursal;

        $pagosPeriodo = DB::table($tablaCorteCaja)
            ->select('remision', DB::raw('SUM(importe_total) as importe_pagado'))
            ->where('vigente', 1)
            ->whereBetween('fecha_pago', [$fecha_inicio, $fecha_final])
            ->groupBy('remision')
            ->get()
            ->keyBy('remision');

        if ($pagosPeriodo->isEmpty()) {
            return response()->json([
                'remisiones' => [],
                'totales' => [
                    'total_ventas' => 0, 'total_costo_iva' => 0, 'utilidad_neta' => 0,
                    'sin_costo' => 0, 'estimados' => 0, 'sospechosos' => 0,
                    'remisiones_pago_parcial' => 0,
                ],
            ]);
        }

        $numRemisiones = $pagosPeriodo->keys()->all();

        $totalesPorRemision = DB::table($tablaRemisiones)
            ->select('num_remision', DB::raw('SUM(total) as total_remision'))
            ->whereIn('num_remision', $numRemisiones)
            ->groupBy('num_remision')
            ->get()
            ->keyBy('num_remision');

        $remisiones = DB::table($tablaRemisiones . ' as r')
            ->select('r.*')
            ->addSelect([
                'ajuste_manual' => DB::table('ajustes_costo_remision as aj')
                    ->select('aj.costo_real_unitario')
                    ->where('aj.sucursal', $sucursal)
                    ->whereColumn('aj.remision_id', 'r.id')
                    ->orderByDesc('aj.created_at')
                    ->limit(1),

                'precio_unitario_vigente' => DB::table('cotizaciones_productos_facturados as f')
                    ->select('f.precio_unitario')
                    ->whereColumn('f.codigo', 'r.producto_clave')
                    ->whereColumn('f.created_at', '<=', 'r.fecha')
                    ->orderByDesc('f.created_at')->orderByDesc('f.id')
                    ->limit(1),
                'importe_con_des_vigente' => DB::table('cotizaciones_productos_facturados as f')
                    ->select('f.impor_con_des')
                    ->whereColumn('f.codigo', 'r.producto_clave')
                    ->whereColumn('f.created_at', '<=', 'r.fecha')
                    ->orderByDesc('f.created_at')->orderByDesc('f.id')
                    ->limit(1),
                'iva_vigente' => DB::table('cotizaciones_productos_facturados as f')
                    ->select('f.iva')
                    ->whereColumn('f.codigo', 'r.producto_clave')
                    ->whereColumn('f.created_at', '<=', 'r.fecha')
                    ->orderByDesc('f.created_at')->orderByDesc('f.id')
                    ->limit(1),
                'cant_facturada_vigente' => DB::table('cotizaciones_productos_facturados as f')
                    ->select('f.cant_facturada')
                    ->whereColumn('f.codigo', 'r.producto_clave')
                    ->whereColumn('f.created_at', '<=', 'r.fecha')
                    ->orderByDesc('f.created_at')->orderByDesc('f.id')
                    ->limit(1),

                'precio_unitario_fallback' => DB::table('cotizaciones_productos_facturados as f2')
                    ->select('f2.precio_unitario')
                    ->whereColumn('f2.codigo', 'r.producto_clave')
                    ->orderBy('f2.created_at')->orderBy('f2.id')
                    ->limit(1),
                'importe_con_des_fallback' => DB::table('cotizaciones_productos_facturados as f2')
                    ->select('f2.impor_con_des')
                    ->whereColumn('f2.codigo', 'r.producto_clave')
                    ->orderBy('f2.created_at')->orderBy('f2.id')
                    ->limit(1),
                'iva_fallback' => DB::table('cotizaciones_productos_facturados as f2')
                    ->select('f2.iva')
                    ->whereColumn('f2.codigo', 'r.producto_clave')
                    ->orderBy('f2.created_at')->orderBy('f2.id')
                    ->limit(1),
                'cant_facturada_fallback' => DB::table('cotizaciones_productos_facturados as f2')
                    ->select('f2.cant_facturada')
                    ->whereColumn('f2.codigo', 'r.producto_clave')
                    ->orderBy('f2.created_at')->orderBy('f2.id')
                    ->limit(1),
            ])
            ->whereIn('r.num_remision', $numRemisiones) 
            ->get();

        $remisionesPagoParcial = 0;

        $remisiones = $remisiones->map(function ($row) use ($pagosPeriodo, $totalesPorRemision, &$remisionesPagoParcial) {
            $cantidad    = (float) $row->cantidad_producto;
            $ultimoCosto = (float) $row->ultimo_costo;
            $totalLinea  = (float) $row->total;

            $totalRemisionCompleta = (float) ($totalesPorRemision[$row->num_remision]->total_remision ?? $totalLinea);
            $importePagadoPeriodo  = (float) ($pagosPeriodo[$row->num_remision]->importe_pagado ?? 0);
            $factorCobro = $totalRemisionCompleta > 0
                ? min(1, $importePagadoPeriodo / $totalRemisionCompleta)
                : 1;

            if ($factorCobro < 0.999) {
                $remisionesPagoParcial++;
            }
            $row->factor_cobro = round($factorCobro, 4);

            $row->ajustado_manual = !is_null($row->ajuste_manual);

            $costoVentaBase  = $cantidad * $ultimoCosto;
            $costoConIvaBase = $costoVentaBase * 1.16;
            $sospechosoBase  = ($ultimoCosto <= 0) || ($costoConIvaBase > $totalLinea);

            if ($row->ajustado_manual) {
                $costoUnitarioNeto   = (float) $row->ajuste_manual;
                $costoUnitarioConIva = $costoUnitarioNeto * 1.16;
                $row->tiene_costo    = true;
                $row->costo_estimado = false;
                $row->fuente_costo   = 'manual';
            } elseif (!$sospechosoBase) {
                $costoUnitarioNeto   = $ultimoCosto;
                $costoUnitarioConIva = $ultimoCosto * 1.16;
                $row->tiene_costo    = true;
                $row->costo_estimado = false;
                $row->fuente_costo   = 'ultimo_costo';
            } else {
                $usoFallback = is_null($row->precio_unitario_vigente);
                $cantFacturada  = $usoFallback ? $row->cant_facturada_fallback   : $row->cant_facturada_vigente;
                $importeConDes  = $usoFallback ? $row->importe_con_des_fallback : $row->importe_con_des_vigente;
                $ivaFactura     = $usoFallback ? $row->iva_fallback             : $row->iva_vigente;
                $precioUnitario = $usoFallback ? $row->precio_unitario_fallback : $row->precio_unitario_vigente;

                $row->tiene_costo    = !is_null($precioUnitario);
                $row->costo_estimado = $row->tiene_costo;
                $row->fuente_costo   = $row->tiene_costo ? ($usoFallback ? 'cotizacion_historica' : 'cotizacion_vigente') : null;

                if ($row->tiene_costo && $cantFacturada > 0) {
                    $costoUnitarioNeto   = $importeConDes / $cantFacturada;
                    $costoUnitarioConIva = ($importeConDes + $ivaFactura) / $cantFacturada;
                } elseif ($row->tiene_costo) {
                    $costoUnitarioNeto   = $precioUnitario;
                    $costoUnitarioConIva = $precioUnitario * 1.16;
                } else {
                    $costoUnitarioNeto = $costoUnitarioConIva = null;
                }
            }

            if ($row->tiene_costo) {
                $row->costo_real_unitario = round($costoUnitarioNeto, 2);
                $row->costo_venta = round($cantidad * $costoUnitarioNeto * $factorCobro, 2);
                $row->costo_con_iva = round($cantidad * $costoUnitarioConIva * $factorCobro, 2);
                $row->venta_reconocida_periodo = round($totalLinea * $factorCobro, 2);
                $row->utilidad_linea = round($row->venta_reconocida_periodo - $row->costo_con_iva, 2);
                $row->sospechoso = $costoConIvaBase > $totalLinea;
            } else {
                $row->costo_real_unitario = null;
                $row->costo_venta = $row->costo_con_iva = $row->utilidad_linea = null;
                $row->venta_reconocida_periodo = round($totalLinea * $factorCobro, 2);
                $row->sospechoso = false;
            }

            unset(
                $row->ajuste_manual,
                $row->precio_unitario_vigente, $row->importe_con_des_vigente, $row->iva_vigente, $row->cant_facturada_vigente,
                $row->precio_unitario_fallback, $row->importe_con_des_fallback, $row->iva_fallback, $row->cant_facturada_fallback
            );

            return $row;
        });

        $totales = [
            'total_ventas'    => round($pagosPeriodo->sum('importe_pagado'), 2),
            'total_costo_iva' => round($remisiones->sum('costo_con_iva'), 2),
            'utilidad_neta'   => round($pagosPeriodo->sum('importe_pagado') - $remisiones->sum('costo_con_iva'), 2),
            'sin_costo'       => $remisiones->where('tiene_costo', false)->count(),
            'estimados'       => $remisiones->where('costo_estimado', true)->count(),
            'sospechosos'     => $remisiones->where('sospechoso', true)->count(),
            'remisiones_pago_parcial' => $remisionesPagoParcial,
        ];

        return response()->json([
            'remisiones' => $remisiones,
            'totales'    => $totales,
        ]);
    }
        */
    public function recuperaUtilidades(Request $request, $sucursal, $fecha_inicio, $fecha_final){

        $tablaRemisiones = 'remisiones_'.$sucursal;
        $tablaCorteCaja  = 'cortecaja_'.$sucursal;

        $pagosPeriodo = DB::table($tablaCorteCaja)
            ->select('remision', DB::raw('SUM(importe_total) as importe_pagado'))
            ->where('vigente', 1)
            ->whereBetween('fecha_pago', [$fecha_inicio, $fecha_final])
            ->groupBy('remision')
            ->get()
            ->keyBy('remision');

        if ($pagosPeriodo->isEmpty()) {
            return response()->json([
                'remisiones' => [],
                'totales' => [
                    'total_ventas' => 0, 'total_costo_iva' => 0, 'utilidad_neta' => 0,
                    'sin_costo' => 0, 'estimados' => 0, 'sospechosos' => 0,
                    'remisiones_pago_parcial' => 0,
                ],
            ]);
        }

        $numRemisiones = $pagosPeriodo->keys()->all();

        $totalesPorRemision = DB::table($tablaRemisiones)
            ->select('num_remision', DB::raw('SUM(total) as total_remision'))
            ->whereIn('num_remision', $numRemisiones)
            ->groupBy('num_remision')
            ->get()
            ->keyBy('num_remision');

        $remisiones = DB::table($tablaRemisiones . ' as r')
            ->select('r.*')
            ->addSelect([
                'ajuste_manual' => DB::table('ajustes_costo_remision as aj')
                    ->select('aj.costo_real_unitario')
                    ->where('aj.sucursal', $sucursal)
                    ->whereColumn('aj.remision_id', 'r.id')
                    ->orderByDesc('aj.created_at')
                    ->limit(1),

                'precio_unitario_vigente' => DB::table('cotizaciones_productos_facturados as f')
                    ->select('f.precio_unitario')
                    ->whereColumn('f.codigo', 'r.producto_clave')
                    ->whereColumn('f.created_at', '<=', 'r.fecha')
                    ->orderByDesc('f.created_at')->orderByDesc('f.id')
                    ->limit(1),
                'importe_con_des_vigente' => DB::table('cotizaciones_productos_facturados as f')
                    ->select('f.impor_con_des')
                    ->whereColumn('f.codigo', 'r.producto_clave')
                    ->whereColumn('f.created_at', '<=', 'r.fecha')
                    ->orderByDesc('f.created_at')->orderByDesc('f.id')
                    ->limit(1),
                'iva_vigente' => DB::table('cotizaciones_productos_facturados as f')
                    ->select('f.iva')
                    ->whereColumn('f.codigo', 'r.producto_clave')
                    ->whereColumn('f.created_at', '<=', 'r.fecha')
                    ->orderByDesc('f.created_at')->orderByDesc('f.id')
                    ->limit(1),
                'cant_facturada_vigente' => DB::table('cotizaciones_productos_facturados as f')
                    ->select('f.cant_facturada')
                    ->whereColumn('f.codigo', 'r.producto_clave')
                    ->whereColumn('f.created_at', '<=', 'r.fecha')
                    ->orderByDesc('f.created_at')->orderByDesc('f.id')
                    ->limit(1),

                'precio_unitario_fallback' => DB::table('cotizaciones_productos_facturados as f2')
                    ->select('f2.precio_unitario')
                    ->whereColumn('f2.codigo', 'r.producto_clave')
                    ->orderBy('f2.created_at')->orderBy('f2.id')
                    ->limit(1),
                'importe_con_des_fallback' => DB::table('cotizaciones_productos_facturados as f2')
                    ->select('f2.impor_con_des')
                    ->whereColumn('f2.codigo', 'r.producto_clave')
                    ->orderBy('f2.created_at')->orderBy('f2.id')
                    ->limit(1),
                'iva_fallback' => DB::table('cotizaciones_productos_facturados as f2')
                    ->select('f2.iva')
                    ->whereColumn('f2.codigo', 'r.producto_clave')
                    ->orderBy('f2.created_at')->orderBy('f2.id')
                    ->limit(1),
                'cant_facturada_fallback' => DB::table('cotizaciones_productos_facturados as f2')
                    ->select('f2.cant_facturada')
                    ->whereColumn('f2.codigo', 'r.producto_clave')
                    ->orderBy('f2.created_at')->orderBy('f2.id')
                    ->limit(1),
            ])
            ->whereIn('r.num_remision', $numRemisiones) 
            ->get();

        $remisionesPagoParcial = 0;

        $remisiones = $remisiones->map(function ($row) use ($pagosPeriodo, $totalesPorRemision, &$remisionesPagoParcial) {
            $cantidad    = (float) $row->cantidad_producto;
            $ultimoCosto = (float) $row->ultimo_costo;
            $totalLinea  = (float) $row->total;

            $totalRemisionCompleta = (float) ($totalesPorRemision[$row->num_remision]->total_remision ?? $totalLinea);
            $importePagadoPeriodo  = (float) ($pagosPeriodo[$row->num_remision]->importe_pagado ?? 0);
            $factorCobro = $totalRemisionCompleta > 0
                ? min(1, $importePagadoPeriodo / $totalRemisionCompleta)
                : 1;

            if ($factorCobro < 0.999) {
                $remisionesPagoParcial++;
            }
            $row->factor_cobro = round($factorCobro, 4);

            $row->ajustado_manual = !is_null($row->ajuste_manual);

            $costoVentaBase  = $cantidad * $ultimoCosto;
            $costoConIvaBase = $costoVentaBase * 1.16;
            $sospechosoBase  = ($ultimoCosto <= 0) || ($costoConIvaBase > $totalLinea);

            if ($row->ajustado_manual) {
                $costoUnitarioNeto   = (float) $row->ajuste_manual;
                $costoUnitarioConIva = $costoUnitarioNeto * 1.16;
                $row->tiene_costo    = true;
                $row->costo_estimado = false;
                $row->fuente_costo   = 'manual';
            } elseif (!$sospechosoBase) {
                $costoUnitarioNeto   = $ultimoCosto;
                $costoUnitarioConIva = $ultimoCosto * 1.16;
                $row->tiene_costo    = true;
                $row->costo_estimado = false;
                $row->fuente_costo   = 'ultimo_costo';
            } else {
                $usoFallback = is_null($row->precio_unitario_vigente);
                $cantFacturada  = $usoFallback ? $row->cant_facturada_fallback   : $row->cant_facturada_vigente;
                $importeConDes  = $usoFallback ? $row->importe_con_des_fallback : $row->importe_con_des_vigente;
                $ivaFactura     = $usoFallback ? $row->iva_fallback             : $row->iva_vigente;
                $precioUnitario = $usoFallback ? $row->precio_unitario_fallback : $row->precio_unitario_vigente;

                $row->tiene_costo    = !is_null($precioUnitario);
                $row->costo_estimado = $row->tiene_costo;
                $row->fuente_costo   = $row->tiene_costo ? ($usoFallback ? 'cotizacion_historica' : 'cotizacion_vigente') : null;

                if ($row->tiene_costo && $cantFacturada > 0) {
                    $costoUnitarioNeto   = $importeConDes / $cantFacturada;
                    $costoUnitarioConIva = ($importeConDes + $ivaFactura) / $cantFacturada;
                } elseif ($row->tiene_costo) {
                    $costoUnitarioNeto   = $precioUnitario;
                    $costoUnitarioConIva = $precioUnitario * 1.16;
                } else {
                    $costoUnitarioNeto = $costoUnitarioConIva = null;
                }
            }

            if ($row->tiene_costo) {
                $row->costo_real_unitario = round($costoUnitarioNeto, 2);
                $row->costo_venta = round($cantidad * $costoUnitarioNeto * $factorCobro, 2);
                $row->costo_con_iva = round($cantidad * $costoUnitarioConIva * $factorCobro, 2);
                $row->venta_reconocida_periodo = round($totalLinea * $factorCobro, 2);
                $row->utilidad_linea = round($row->venta_reconocida_periodo - $row->costo_con_iva, 2);
                $row->sospechoso = $costoConIvaBase > $totalLinea;
            } else {
                $row->costo_real_unitario = null;
                $row->costo_venta = $row->costo_con_iva = $row->utilidad_linea = null;
                $row->venta_reconocida_periodo = round($totalLinea * $factorCobro, 2);
                $row->sospechoso = false;
            }

            unset(
                $row->ajuste_manual,
                $row->precio_unitario_vigente, $row->importe_con_des_vigente, $row->iva_vigente, $row->cant_facturada_vigente,
                $row->precio_unitario_fallback, $row->importe_con_des_fallback, $row->iva_fallback, $row->cant_facturada_fallback
            );

            return $row;
        });

        $remisiones = $remisiones->map(function ($row) use ($pagosPeriodo, $totalesPorRemision, &$remisionesPagoParcial) {

            if ($row->tiene_costo && $row->costo_real_unitario > 0 && $row->precio > 0) {
                $margenReal = (($row->precio - $row->costo_real_unitario) / $row->precio) * 100;
                $row->margen_real_pct = round($margenReal, 1);
                $row->clasificacion_lista = $this->clasificarListaPrecio($margenReal);
            } else {
                $row->margen_real_pct = null;
                $row->clasificacion_lista = 'SIN DATO';
            }

            return $row;
        });

        $resumenListas = $remisiones
            ->groupBy('clasificacion_lista')
            ->map(function ($grupo, $clasificacion) {
                $ventaGrupo = $grupo->sum('venta_reconocida_periodo');
                $costoGrupo = $grupo->sum('costo_con_iva');
                return [
                    'clasificacion'    => $clasificacion,
                    'num_lineas'       => $grupo->count(),
                    'venta_total'      => round($ventaGrupo, 2),
                    'margen_ponderado' => $ventaGrupo > 0 ? round((($ventaGrupo - $costoGrupo) / $ventaGrupo) * 100, 1) : null,
                ];
            })->values();

        $ventaTotalConDato = $remisiones->whereNotNull('margen_real_pct')->sum('venta_reconocida_periodo');
        $resumenListas = $resumenListas->map(function ($item) use ($ventaTotalConDato) {
            $item['pct_del_total'] = ($ventaTotalConDato > 0 && $item['clasificacion'] !== 'SIN DATO')
                ? round(($item['venta_total'] / $ventaTotalConDato) * 100, 1)
                : null;
            return $item;
        });

        $fueraDePoliticaPorVendedor = $remisiones
            ->where('clasificacion_lista', 'FUERA DE POLITICA')
            ->groupBy('vendedor')
            ->map(function ($grupo, $vendedor) {
                return [
                    'vendedor'    => $vendedor,
                    'num_lineas'  => $grupo->count(),
                    'venta_total' => round($grupo->sum('venta_reconocida_periodo'), 2),
                ];
            })
            ->sortByDesc('venta_total')
            ->values();

        $totales = [
            'total_ventas'    => round($pagosPeriodo->sum('importe_pagado'), 2),
            'total_costo_iva' => round($remisiones->sum('costo_con_iva'), 2),
            'utilidad_neta'   => round($pagosPeriodo->sum('importe_pagado') - $remisiones->sum('costo_con_iva'), 2),
            'sin_costo'       => $remisiones->where('tiene_costo', false)->count(),
            'estimados'       => $remisiones->where('costo_estimado', true)->count(),
            'sospechosos'     => $remisiones->where('sospechoso', true)->count(),
            'remisiones_pago_parcial' => $remisionesPagoParcial,
            'resumen_listas_precio'      => $resumenListas,
            'fuera_de_politica_vendedor' => $fueraDePoliticaPorVendedor,
        ];

        return response()->json([
            'remisiones' => $remisiones,
            'totales'    => $totales,
        ]);
    }


    private function clasificarListaPrecio(float $margenReal): string{
        $listas = [
            'LISTA 1' => 31.0,
            'LISTA 2' => 26.0,
            'LISTA 3' => 22.0,
            'LISTA 4' => 18.0,
        ];
        $colchon = 2.5;

        foreach ($listas as $nombre => $margenObjetivo) {
            if ($margenReal >= ($margenObjetivo - $colchon)) {
                return $nombre;
            }
        }

        return 'FUERA DE POLITICA';
    }

    public function corregirCostoRemision(Request $request, $sucursal){
        $request->validate([
            'remision_id'          => 'required|numeric',
            'producto_id'          => 'nullable|numeric',
            'costo_real_unitario'  => 'required|numeric|min:0',
            'motivo'               => 'nullable|string|max:255',
        ]);

        DB::table('ajustes_costo_remision')->insert([
            'sucursal'             => $sucursal,
            'remision_id'          => $request->remision_id,
            'producto_id'          => $request->producto_id,
            'costo_real_unitario'  => $request->costo_real_unitario,
            'motivo'               => $request->motivo,
            'usuario'              => auth()->user()->name ?? 'admin',
            'created_at'           => now(),
            'updated_at'           => now(),
        ]);

        return response()->json(['message' => 'Costo corregido y guardado correctamente para esta remisión.']);
    }
}
