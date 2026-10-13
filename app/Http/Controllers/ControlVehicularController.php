<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use SimpleXMLElement;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Collection;

class ControlVehicularController extends Controller
{
    /*nos servira para subir el control vehicular de kml */
    public function cargar_rutas(){
        return view('control_vehicular.cargar_rutas_de_viajes');
    }

    /*recuperando el vehiculo para el selector */

    public function selectorVehiculo(Request $request){
        $recu_vehiculo = DB::table('vehiculos')->orderBy('id','asc')->get();

        if($recu_vehiculo != null){
            return response()->json(['vehiculo'=>$recu_vehiculo]);
        }else{
            return response()->json(['error'=>'Error al intentar recuperar vehiculo'],404);
        }
    }

    public function vistaVerificar(){
        return view('control_vehicular.verificar_viaje');
    }

    public function guardarRutasVehiculos(Request $request, $vehiculo) {
        // Aumentar el tiempo de ejecución solo para este proceso si es muy pesado
        set_time_limit(120); 

        $datos_excel_rutas = $request->json()->all();
        $carro = $vehiculo;
        $dataToInsert = [];

        // Validar la estructura general primero
        if (empty($datos_excel_rutas)) {
            return response()->json(['error' => 'No hay datos para procesar'], 400);
        }

        foreach ($datos_excel_rutas as $row) {
            if (stripos(strtoupper($row['nombre_vehiculo'] ?? ''), strtoupper($carro)) === false) {
                return response()->json(['error' => "El archivo contiene datos del vehículo {$row['nombre_vehiculo']}, pero se esperaba {$carro}"], 400);
            }
            $dataToInsert[] = [
                'nombre_vehiculo' => substr($row['nombre_vehiculo'] ?? '-', 0, 250),
                'imei'            => substr($row['imei'] ?? '-', 0, 250),
                'model'           => substr($row['model'] ?? '-', 0, 200),
                'on_off'          => substr($row['on_off'] ?? '-', 0, 100),
                'fecha_recorrido' => $row['fecha_recorrido'] ?? now()->format('Y-m-d'),
                'hora_recorrido'  => $row['hora_recorrido'] ?? '00:00:00',
                'velocidad'       => is_numeric($row['velocidad'] ?? null) ? $row['velocidad'] : 0,
                'tipo_posicion'   => substr($row['tipo_posicion'] ?? '-', 0, 150),
                'n_satelite'      => is_numeric($row['n_satelite'] ?? null) ? $row['n_satelite'] : 0,
                'tipo_de_datos'   => substr($row['tipo_de_datos'] ?? '-', 0, 100),
                'coordenadas'     => substr($row['coordenadas'] ?? '-', 0, 250),
                'direccion'       => substr($row['direccion'] ?? '-', 0, 500),
            
            ];
        }

        // Inserción Masiva en bloques de 500 para no saturar la memoria de SQL
        try {
            DB::beginTransaction();
            $chunks = array_chunk($dataToInsert, 500);
            foreach ($chunks as $chunk) {
                DB::table($carro)->insert($chunk);
            }
            DB::commit();
            return response()->json(['message' => '¡Datos guardados exitosamente! Total: ' . count($dataToInsert)]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al insertar en la base de datos', 'details' => $e->getMessage()], 500);
        }
    }

    public function vistaReporteRutas(){

        return view('control_vehicular.reporte_rutas');
    }

    /*funcion donde se hara todo el desmadre para recuperar por viajes */
   public function generaReporteRuta(Request $request, $vehiculo, $fecha_i, $fecha_f, $id_vehiculo) {
        // Configuración de Vehículos
        $configVehiculos = [
            1 => ['tipo' => 'Diesel',   'rend' => 10.5, 'precio' => 28.25, 'km_base' => 155803],
            2 => ['tipo' => 'Gasolina', 'rend' => 9.0,  'precio' => 24.70, 'km_base' => 282987.23],
            3 => ['tipo' => 'Diesel',   'rend' => 13.2, 'precio' => 28.25, 'km_base' => 121322],
            4 => ['tipo' => 'Diesel',   'rend' => 3.8,  'precio' => 28.25, 'km_base' => 278143],
            5 => ['tipo' => 'Gasolina', 'rend' => 5.6,  'precio' => 24.70, 'km_base' => 216282.2],
            6 => ['tipo' => 'Diesel',   'rend' => 6.9,  'precio' => 28.25, 'km_base' => 139767.41],
            7 => ['tipo' => 'Gasolina', 'rend' => 6.4,  'precio' => 24.70, 'km_base' => 111137.12],
        ];

        $conf = $configVehiculos[$id_vehiculo] ?? ['tipo' => 'Gasolina', 'rend' => 4.0, 'precio' => 24.70, 'km_base' => 0];

        
        // recuperamos el numero de efecticard del vehiculo y lo devolvemos como un valor
        $efecticard = DB::table('vehiculos_panel')
            ->where('nombre_gps', $vehiculo)
            ->value('efecticard'); 

        // Validar si el vehiculo tiene una tarjeta asignada antes de continuar
        if (!$efecticard) {
            // Manejar el caso de que no tenga tarjeta (ej. retornar vacío o un error)
            return response()->json(['message' => 'El vehículo no tiene una tarjeta asignada'], 404);
        }

        //con el valor recuperado de efecticard podemods traer sus consumos por el rango de la fecha
        $tiene_consumo = DB::table('disperciones_efe_vehiculos')
            ->where('efecticard', $efecticard)
            ->whereBetween('fecha', [$fecha_i, $fecha_f])
            ->get();

        //dd($tiene_consumo);

        // Carga de Datos
        $registros = DB::table($vehiculo)
            ->whereBetween('fecha_recorrido', [$fecha_i, $fecha_f])
            ->orderBy('fecha_recorrido')
            ->orderBy('hora_recorrido')
            ->get();

        $clientes = DB::table('clientes_locales')->select('nombre_negocio','coordenadas')->get()
            ->merge(DB::table('clientes_foraneos')->select('nombre_negocio','coordenadas')->get());

        // Variables de Control
        $viajes = [];
        $detenciones = [];
        $distanciaTotalRango = 0;
        $segundosTotalesRango = 0;
        
        // Variables para el procesamiento por día
        $datosPorDia = [];
        $inicioViaje = null;
        $puntoAnterior = null;
        $horaAnterior = null;

        foreach ($registros as $reg) {
            if (empty($reg->coordenadas)) continue;

            $fechaActual = $reg->fecha_recorrido;
            if (!isset($datosPorDia[$fechaActual])) {
                $datosPorDia[$fechaActual] = ['distancia' => 0, 'segundos' => 0];
            }

            [$lat, $lon] = explode(',', $reg->coordenadas);
            $timestampActual = strtotime($reg->hora_recorrido);

            // --- CALCULO DE DISTANCIA Y DETENCIONES ---
            if ($puntoAnterior) {
                $distSeg = $this->haversine($puntoAnterior['lat'], $puntoAnterior['lon'], $lat, $lon);
                
                if ($distSeg < 2) {
                    $datosPorDia[$fechaActual]['distancia'] += $distSeg;
                    $distanciaTotalRango += $distSeg;
                }
                // Detenciones (5 min y 15 metros)
                $diffTiempo = $timestampActual - $horaAnterior;
                if ($distSeg < 0.015 && $diffTiempo >= 300) {
                    $detenciones[] = [
                        'fecha' => $fechaActual,
                        'hora' => $reg->hora_recorrido,
                        'duracion_min' => round($diffTiempo / 60, 2),
                        'direccion' => $reg->direccion ?? 'N/A'
                    ];
                }
            }

        // --- LOGICA DE VIAJES (ON/OFF) ---
            if ($reg->on_off === 'ON' && !$inicioViaje) {
                $inicioViaje = $reg;
            } elseif ($reg->on_off === 'OFF' && $inicioViaje) {
                $duracionViaje = $timestampActual - strtotime($inicioViaje->hora_recorrido);
                $datosPorDia[$fechaActual]['segundos'] += $duracionViaje;
                $segundosTotalesRango += $duracionViaje;

                $cliente = $this->buscarCliente($lat, $lon, $clientes);

                [$lat_i, $lon_i] = explode(',', $inicioViaje->coordenadas);

                $viajes[] = [
                    'fecha' => $fechaActual,
                    'hora_inicio' => $inicioViaje->hora_recorrido,
                    'hora_fin' => $reg->hora_recorrido,
                    'destino_cliente' => $cliente ?? "No registrado",
                    'duracion' => $this->formatearSegundos($duracionViaje),
                    'distancia_km' => round($this->haversine($lat_i, $lon_i, $lat, $lon), 2),
                    'coordenadas_salida' => $inicioViaje->coordenadas, 
                    'coordenadas_llegada' => $reg->coordenadas,
                    'direccion_salida' => $inicioViaje->direccion,
                    'direccion_llegada' => $reg->direccion,
                    'link_ruta' => "https://www.google.com/maps/dir/{$lat_i},{$lon_i}/{$lat},{$lon}"
                ];
                $inicioViaje = null;
            }

            $puntoAnterior = ['lat' => $lat, 'lon' => $lon];
            $horaAnterior = $timestampActual;

            $puntoAnterior = ['lat' => $lat, 'lon' => $lon];
            $horaAnterior = $timestampActual;
        }

        // PERSISTENCIA DE KILOMETRAJE
        foreach ($datosPorDia as $fecha => $data) {
            $ultimoViaje = DB::table('viajes')
                ->where('vehiculo_id', $id_vehiculo)
                ->where('fecha', '<', $fecha)
                ->orderByDesc('fecha')
                ->first();

            $km_i = $ultimoViaje ? $ultimoViaje->km_final : $conf['km_base'];
            $km_f = $km_i + $data['distancia'];

            DB::table('viajes')->updateOrInsert(
                ['vehiculo_id' => $id_vehiculo, 'fecha' => $fecha],
                [
                    'km_inicial' => $km_i,
                    'km_final' => $km_f,
                    'tipo_combustible' => $conf['tipo'],
                    'precio_combustible' => $conf['precio'],
                    'rendimiento_x_litro' => $conf['rend'],
                    'updated_at' => now()
                ]
            );
        }

        return response()->json([
            'vehiculo' => $vehiculo,
            'fecha_inicio' => $fecha_i,
            'fecha_fin' => $fecha_f,
            'total_viajes' => count($viajes),
            'distancia_total_km' => round($distanciaTotalRango, 2),
            'duracion_total' => $this->formatearSegundos($segundosTotalesRango), 
            'viajes' => $viajes,
            'detenciones' => $detenciones,
            'ultimo_viaje' => [
                'km_recorridos' => round($distanciaTotalRango, 2),
                'rendimiento_x_litro' => $conf['rend'],
                'precio_combustible' => $conf['precio'],
                'costo_total' => round(($distanciaTotalRango / $conf['rend']) * $conf['precio'], 2),
                'litros_totales' => round($distanciaTotalRango / $conf['rend'], 2)
            ],
            'consumos' => $tiene_consumo
        ]);
    }

    private function haversine($lat1, $lon1, $lat2, $lon2) {
        $rad = pi() / 180;
        $dLat = ($lat2 - $lat1) * $rad;
        $dLon = ($lon2 - $lon1) * $rad;
        $a = sin($dLat/2)**2 + cos($lat1*$rad) * cos($lat2*$rad) * sin($dLon/2)**2;
        return 6371 * (2 * atan2(sqrt($a), sqrt(1-$a)));
    }

    private function buscarCliente($lat, $lon, $clientes) {
        foreach ($clientes as $c) {
            [$cLat, $cLon] = explode(',', $c->coordenadas);
            if ($this->haversine($lat, $lon, $cLat, $cLon) < 0.04) return $c->nombre_negocio;
        }
        return null;
    }

    private function formatearSegundos($segundos) {
        $horas = floor($segundos / 3600);
        $minutos = floor(($segundos % 3600) / 60);
        $segs = $segundos % 60;
        
        return sprintf('%02d:%02d:%02d', $horas, $minutos, $segs);
    }

    /*FUNCION DONDE MANDARA A LA VISTA AL SCANEAR EL CODIGO QR DE CADA VIAJE */
    public function vistaRecorrido(){
        
        return view('control_vehicular.viewsMapsRutas');
    }
    //  Renderiza la vista base de forma instantánea pasando solo las variables de control
    public function vistaRecorridoCodificado(Request $request, $encodedParams){
        try {
            $decoded = base64_decode($encodedParams); 
            $partes = explode('/', $decoded);

            if (count($partes) !== 4) {
                return redirect()->back()->with('error', 'Parámetros del QR inválidos.');
            }

            return view('control_vehicular.viewsMapsRutas', [
                'vehiculo'   => $partes[0],
                'fecha_i'    => $partes[1],
                'fecha_f'    => $partes[2],
                'id_vehiculo'=> $partes[3]
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al decodificar parámetros.');
        }
    }

  
    public function recuperaDatosQr(Request $request){
        $vehiculo    = $request->input('vehiculo');
        $fecha_i     = $request->input('fecha_i');
        $fecha_f     = $request->input('fecha_f');
        $id_vehiculo = $request->input('id_vehiculo');

        if (empty($vehiculo) || empty($fecha_i) || empty($fecha_f) || empty($id_vehiculo)) {
            return response()->json(['error' => 'Parámetros inválidos.'], 400);
        }

        //recupera los datos del carro 
        $datos_vehiculo = DB::table('vehiculos_panel')->where('nombre_gps', $vehiculo)->get();

        //sacamos el id del coarro ´para scar
        $id_carro = DB::table('vehiculos_panel')->where('nombre_gps', $vehiculo)->value('id');

        //sacvamos al choifer
        $chofer_del_vehiculo = DB::table('choferes')->where('vehiculo_id', $id_carro)->get();

        //historial de cargas autorizadas de este vehiculo, nos ayudara para saber cuanta gasolina va utilizando
        $hist_combustible = DB::Table('solicitudes_combustible')->where('vehiculo_id',$id_carro)->where('estatus', 'autorizado')->get();

   
        $configVehiculos = [
            1 => ['tipo' => 'Diesel',   'rend' => 10.5, 'precio' => 28.25, 'km_base' => 155803],
            2 => ['tipo' => 'Gasolina', 'rend' => 9.0,  'precio' => 24.70, 'km_base' => 282987.23],
            3 => ['tipo' => 'Diesel',   'rend' => 13.2, 'precio' => 28.25, 'km_base' => 121322],
            4 => ['tipo' => 'Diesel',   'rend' => 3.8,  'precio' => 28.25, 'km_base' => 278143],
            5 => ['tipo' => 'Gasolina', 'rend' => 5.6,  'precio' => 24.70, 'km_base' => 216282.2],
            6 => ['tipo' => 'Diesel',   'rend' => 6.9,  'precio' => 28.25, 'km_base' => 139767.41],
            7 => ['tipo' => 'Gasolina', 'rend' => 6.4,  'precio' => 24.70, 'km_base' => 111137.12],
        ];
        $conf = $configVehiculos[$id_vehiculo] ?? ['tipo' => 'Gasolina', 'rend' => 4.0, 'precio' => 24.70, 'km_base' => 0];

        // Recuperar Consumos Reales desde Efectivale
        $efecticard = DB::table('vehiculos_panel')->where('nombre_gps', $vehiculo)->value('efecticard'); 
        $consumos_efectivale = [];
        if ($efecticard) {
            $consumos_efectivale = DB::table('disperciones_efe_vehiculos')
                ->where('efecticard', $efecticard)
                ->whereBetween('fecha', [$fecha_i, $fecha_f])
                ->orderBy('fecha')
                ->orderBy('hora')
                ->get();
        }

        // Carga de Registros del Historial del GPS
        $registros = DB::table($vehiculo)
            ->whereBetween('fecha_recorrido', [$fecha_i, $fecha_f])
            ->orderBy('fecha_recorrido')
            ->orderBy('hora_recorrido')
            ->get();

        if ($registros->isEmpty()) {
            return response()->json(['error' => 'No se encontraron registros de GPS para este periodo.'], 404);
        }

        // Carga y Combinación del Universo de Clientes
        $clientes = DB::table('clientes_locales')->select('nombre_negocio', 'coordenadas')->get()
            ->merge(DB::table('clientes_foraneos')->select('nombre_negocio', 'coordenadas')->get());
        $clientes_locales = DB::table('clientes_locales')->get();
        $clientes_foraneos = DB::table('clientes_foraneos')->get();

        // Procesamiento del Recorrido (Distancias, Viajes y Paradas)
        $coordenadas = [];
        $distanciaTotalKm = 0;
        $puntoAnterior = null;
        $horaAnterior = null;
        $paradas = [];
        $viajes = [];
        $inicioViaje = null;
        $umbralParada = 600;

        foreach ($registros as $reg) {
            if (empty($reg->coordenadas)) continue;

            [$lat, $lon] = explode(',', $reg->coordenadas);
            $timestampActual = strtotime($reg->hora_recorrido);
            $coordenadas[] = [floatval($lat), floatval($lon)];

            if ($puntoAnterior) {
                $distSeg = $this->haversine($puntoAnterior['lat'], $puntoAnterior['lon'], $lat, $lon);
                if ($distSeg < 2) { 
                    $distanciaTotalKm += $distSeg;
                }

                $diffTiempo = $timestampActual - $horaAnterior;
                if ($distSeg < 0.015 && $diffTiempo >= $umbralParada) {
                    $paradas[] = [
                        'lat' => floatval($lat),
                        'lon' => floatval($lon),
                        'direccion' => $reg->direccion ?? 'N/A',
                        'hora' => $reg->hora_recorrido,
                        'duracion' => round($diffTiempo / 60)
                    ];
                }
            }

            if ($reg->on_off === 'ON' && !$inicioViaje) {
                $inicioViaje = $reg;
            } elseif ($reg->on_off === 'OFF' && $inicioViaje) {
                $duracionViaje = $timestampActual - strtotime($inicioViaje->hora_recorrido);
                $clienteMatch = $this->buscarCliente($lat, $lon, $clientes);
                [$lat_i, $lon_i] = explode(',', $inicioViaje->coordenadas);

                $viajes[] = [
                    'destino_cliente' => $clienteMatch ?? "Ruta Local / Zona Operativa",
                    'hora_inicio' => $inicioViaje->hora_recorrido,
                    'hora_fin' => $reg->hora_recorrido,
                    'duracion' => $this->formatearSegundos($duracionViaje),
                    'distancia_km' => round($this->haversine($lat_i, $lon_i, $lat, $lon), 2),
                    'direccion_salida' => $inicioViaje->direccion,
                    'direccion_llegada' => $reg->direccion,
                ];
                $inicioViaje = null;
            }

            $puntoAnterior = ['lat' => $lat, 'lon' => $lon];
            $horaAnterior = $timestampActual;
        }

        $inicioRecorrido = $registros->first();
        $finRecorrido = $registros->last();
        $horaInicioGlobal = strtotime($inicioRecorrido->fecha_recorrido . ' ' . $inicioRecorrido->hora_recorrido);
        $horaFinGlobal = strtotime($finRecorrido->fecha_recorrido . ' ' . $finRecorrido->hora_recorrido);
        
        $duracionSegundos = $horaFinGlobal - $horaInicioGlobal;
        $horas = floor($duracionSegundos / 3600);
        $minutos = floor(($duracionSegundos % 3600) / 60);
        $duracion_recorrido = "{$horas} h {$minutos} min";

        $ultimoViajeMock = [
            'km_recorridos' => round($distanciaTotalKm, 2),
            'rendimiento_x_litro' => $conf['rend'],
            'precio_combustible' => $conf['precio']
        ];

        return response()->json([
            'success' => true,
            'coordenadas' => $coordenadas,
            'clientes' => $clientes_foraneos,
            'clientes_locales' => $clientes_locales,
            'distanciaTotalKm' => round($distanciaTotalKm, 2),
            'paradas' => $paradas,
            'viajes' => $viajes,
            'duracionRecorrido' => $duracion_recorrido,
            'ultimoViaje' => $ultimoViajeMock,
            'consumos_efectivale' => $consumos_efectivale,
            'datos_vehiculo'=>$datos_vehiculo,
            'datos_chofer'=>$chofer_del_vehiculo,
            'hist_recargas_combustible'=> $hist_combustible
        ]);
    }


    public function dashboardVehiculos(){

        return view('control_vehicular.dashboard_vehiculos');
    }

    //VISTA PARA CARGAR LOS CONSUMPOS
    public function cargarConsumos(){

        return view('control_vehicular.cargar_consumos');
    }

    //vamos aguardar los consumos
    public function guardarConsumos(Request $request)
    {
        $request->validate([
            'transacciones' => 'required|array',
            'transacciones.*.efecticard' => 'required' 
        ]);

        $transacciones = $request->input('transacciones');

        DB::beginTransaction();

        try {
            $loteInsertar = [];
            $tamanoLote = 200; 
            $fechaRegistro = now(); 

            foreach ($transacciones as $transaccion) {
               
                $loteInsertar[] = [
                    'efecticard'         => $transaccion['efecticard'],
                    'fecha'              => $transaccion['fecha'],
                    'hora'               => $transaccion['hora'],
                    'cargado'            => $transaccion['cargado'],
                    'dis_a_cuenta'       => $transaccion['dis_a_cuenta'],
                    'saldo_cuenta'       => $transaccion['saldo_cuenta'],
                    'tipo'               => $transaccion['tipo'],
                    'usuario'            => $transaccion['usuario'],
                    'concepto'           => $transaccion['concepto'],
                    'tipo_combustible'   => $transaccion['tipo_combustible'],
                    'precio_combustible' => $transaccion['precio_combustible'],
                    'km_inicial'         => $transaccion['km_inicial'],
                    'km_final'           => $transaccion['km_final'],
                    'litros_cargados'    => $transaccion['litros_cargados'],
                    'rendimiento'        => $transaccion['rendimiento'],
                    'estacion_rfc'       => $transaccion['estacion_rfc'],
                    'created_at'         => $fechaRegistro,
                    'updated_at'         => $fechaRegistro,
                ];

                if (count($loteInsertar) >= $tamanoLote) {
                    DB::table('disperciones_efe_vehiculos')->insert($loteInsertar);
                    $loteInsertar = [];
                }
            }

            if (count($loteInsertar) > 0) {
                DB::table('disperciones_efe_vehiculos')->insert($loteInsertar);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Consumos registrados correctamente en el sistema.'
            ], 200);

        } catch (\Exception $e) {
           
            DB::rollBack();

            Log::error("Error al cargar consumos generales de Efectivale: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Sucedió un error interno al guardar las transacciones.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

}
