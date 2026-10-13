<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Smalot\PdfParser\Parser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Mime\Part\TextPart;
use Carbon\Carbon;
use Symfony\Component\Process\Process;


class CotizacionesHerrajesController extends Controller
{
    public function vista_cotizaciones_herrajes(){

        return view('cotizaciones_herrajes.subir_cotizaciones');
    }

    public function extraer(Request $request){
        $file = $request->file('cotizacion_pdf');
        if (!$file) {
            return response()->json(['error' => 'No se subió ningún archivo.'], 400);
        }
        $user = Auth::user();
        $sucursal = DB::table('sucursales')
            ->where('id', $user->tipo_sucursal)
            ->select('id', 'nombre_interno', 'nombre_real')
            ->first();

        $rutaPdf = $file->getPathname();
        //$process = new Process(['python', base_path('scripts\extract_cotizacion.py'), $rutaPdf]);
        //$process = new Process(['python', base_path('app/scripts/extract_cotizacion.py'), $rutaPdf]);
        //dd($process);
        //$process = new Process(['python', base_path('scripts\extract_cotizacion.py'), $rutaPdf]);
       $pythonPath = base_path('app/scripts/venv/Scripts/python.exe');
        $scriptPath = base_path('app/scripts/venv\Scripts\extract_cotizacion.py'); // <-- Quitamos 'venv/Scripts/' de aquí

        $process = new Process([$pythonPath, $scriptPath, $rutaPdf]);
        $process->run();

        // dd($process->getOutput(), $process->getErrorOutput());

       $output = trim($process->getOutput());
        $errorOutput = trim($process->getErrorOutput());

        $data = json_decode($output, true);

        if (is_null($data)) {
            dd([
                'error_json' => 'El script no devolvió un JSON válido.',
                'stdout_recibido' => $output,
                'stderr_recibido' => $errorOutput
            ]);
        }

        $data['sucursal'] = $sucursal;

        return response()->json($data);
    }

    public function reprocesarProductos(Request $request){
        
        $lineas = $request->input('productos_no_reconocidos', []);

        $normalizarBloque = function ($linea) {
            $linea = preg_replace('/([0-9])MXN/', '$1 MXN', $linea);
            $linea = preg_replace('/\s{2,}/', ' ', $linea);
            return trim($linea);
        };

        $productos = [];
        $noReconocidos = [];

        foreach ($lineas as $linea) {
            $linea = $normalizarBloque($linea);

            if (preg_match('/^([\d.,]+)\s+([A-Z0-9]+)\s+(.+?)\s+(PZ|M2|ML)\s+MXN\s*([\d.,]+)\s+MXN\s*([\d.,]+)\s+([\d.,]+)\s+MXN\s*([\d.,]+)\s+MXN\s*([\d.,]+)/i', $linea, $m)) {
                $productos[] = [
                    "cantidad"         => (float) str_replace(',', '', $m[1]),
                    "codigo"           => $m[2],
                    "nombre"           => trim($m[3]),
                    "um"               => $m[4],
                    "precio_lista"     => (float) str_replace(',', '', $m[5]),
                    "precio_promocion" => (float) str_replace(',', '', $m[6]),
                    "descuento"        => (float) str_replace(',', '', $m[7]),
                    "precio_cliente"   => (float) str_replace(',', '', $m[8]),
                    "importe"          => (float) str_replace(',', '', $m[9]),
                ];
            } else {
                $noReconocidos[] = $linea;
            }
        }

        return response()->json([
            "productos_reprocesados" => $productos,
            "productos_fallidos"     => $noReconocidos
        ]);
    }

    public function guardarDatosGenerales(Request $request){
        $datosGenerales = $request->only([
            'presupuesto',
            'fecha_cotizacion',
            'lista_precio',
            'proveedor',
            'total_cotizacion',
            'vendedor',
            'sucursal_id',
            'vigente'
        ]);

        $existe = DB::table('cotizaciones')->where('presupuesto', $datosGenerales['presupuesto'])->exists();

        if ($existe) {
            return response()->json([
                'status' => 'exists',
                'mensaje' => 'Ya existe una cotización con este número de presupuesto, puedes proceder a guardar la cotización.',
            ]);
        }

        DB::table('cotizaciones')->insert($datosGenerales);

        return response()->json([
            'status' => 'success',
            'message' => 'Datos generales guardados correctamente.'
        ]);
    }

    public function verificarPresupuesto(Request $request){
        $presupuesto = $request->input('presupuesto');

        // Verificar si existe en cotizaciones
        $existeCotizacion = DB::table('cotizaciones')
            ->where('presupuesto', $presupuesto)
            ->exists();

        //sacar el id de la cotizacion si es que existe
        $existeCotizacion = DB::table('cotizaciones')
            ->where('presupuesto', $presupuesto)
            ->first();
    
       
        $tieneProductos = DB::table('cotizacion_productos')
            ->where('cotizacion_id', $existeCotizacion->id ?? 0)
            ->exists();

        return response()->json([
            'presupuesto_existe' => $existeCotizacion,
            'productos_registrados' => $tieneProductos,
            'id_cotizacion' => $existeCotizacion
            
        ]);
    }

    public function guardarProductos(Request $request){
        $productos = $request->input('productos', []);
        $cotizacion_id = $request->input('presupuesto');

        foreach ($productos as $producto) {
            $producto['cotizacion_id'] = $cotizacion_id;
            DB::table('cotizacion_productos')->insert($producto);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Productos guardados correctamente.'
        ]);
    }

    //vista para subir inventario
    public function vista_subir_inventario(){
        return view('cotizaciones_herrajes.subir_inventario');
    }

    //esto nos borra el inventario de la tabla, con la finalidad de subir uno nuevo
    public function eliminarInventarioTemporal(){
        DB::table('inventario_temporal')->truncate(); // elimina todos los registros
        return response()->json(['success' => true, 'message' => 'Inventario temporal eliminado.']);
    }

    //funcion para subir inventario a las tiendas
    public function subirInventarioTiendas(Request $request){
        $datos_excel = $request->json()->all();

        $rowInventario = [];
        foreach ($datos_excel as $dato) {
            $validator = Validator::make($dato, [
                'producto_id' => 'nullable|numeric',
                'clave_producto' => 'nullable|string|max:255',
                'descripcion_producto' => 'nullable|string|max:500',
                'existencia' => 'nullable|numeric',
                'color' => 'nullable|string|max:255',
                'sub_linea' => 'nullable|string|max:255',
                'linea' => 'nullable|string|max:255',
                'ultimo_costo' => 'nullable|numeric',
                'costo_inventario' => 'nullable|numeric',
            ]);

            // Validar y guardar cada dato en la base de datos
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json([
                    'status' => 'error',
                    'errors' => $errors
                ], 422);
            }

            $rowInventario[] = [
                'sucursal_id' => $dato['sucursal_id'] ?? $request->input('sucursal_id'),
                'producto_id' => $dato['producto_id'] ?? 0,
                'clave_producto' => $dato['clave_producto'] ?? 'N/A',
                'descripcion_producto' => $dato['descripcion_producto'] ?? 'N/A',
                'existencia' => $dato['existencia'] ?? 0,
                'color' => $dato['color'] ?? 'N/A',
                'sub_linea' => $dato['sub_linea'] ?? 'N/A',
                'linea' => $dato['linea'] ?? 'N/A',
                'ultimo_costo' => $dato['ultimo_costo'] ?? 0,
                'costo_inventario' => $dato['costo_inventario'] ?? 0,
            ];

        }

        DB::table('inventario_temporal')->insert($rowInventario);

        return response()->json([
            'status' => 'success',
            'message' => 'Inventario subido correctamente.'
        ]);
    }

    public function vistaPreAutorizacionCotizacion(){
        return view('cotizaciones_herrajes.pre_autorizacion_cotizacion');
    }

    public function vistaAutorizacionCotizacion(){
        return view('cotizaciones_herrajes.autorizar_cotizacion');
    }

    public function recuperaTodasCotizacion(Request $request){

        //mandamos a recuperar todas las cotizaciones que hay vigente 
        $cotizaciones_vigente = DB::table('cotizaciones')->where('vigente', true)->get();

        return response()->json(['cotizacion_vigente'=>$cotizaciones_vigente]);

    }

    //funcion para sacar el producto que ya esta autorizado
    public function cotizacionAutorizada(Request $request){
        // Recuperar cotizaciones que ya fueron foliadas
        $cotizacion_autorizada = DB::table('cotizaciones')
            ->where('vigente', false)
            ->where('folio_autorizado', false)
            ->orderBy('id', 'desc')->get();

        // Recuperar usuario logeado
        $id_usuario = auth()->id();
        $usuario_logeado = DB::table('usuarios')->where('id', $id_usuario)->first();

        return response()->json([
            'cotizacion_autorizada' => $cotizacion_autorizada,
            'usuario' => $usuario_logeado
        ]);
    }


    public function buscaProductos(Request $request, $id_presupuesto){
        $id = $id_presupuesto;

        //consulta para recuperar los productos relacionados a esa cotizacion
        $productos = DB::table('cotizacion_productos')->where('cotizacion_id', $id)->get();

        $resultado = [];

        foreach ($productos as $p) {
            // Inventario por sucursal
            $inventarios = DB::table('inventario_temporal')->select('sucursal_id', 'existencia')->where('clave_producto', $p->codigo)->get();

            $existencias_por_sucursal = [];
        foreach ($inventarios as $inv) {
            $existencias_por_sucursal[$inv->sucursal_id] = $inv->existencia;
        }

        $stock_total = array_sum($existencias_por_sucursal);

        // Ventas historicas
        $ventas_historicas = DB::table('remisiones_teran')
            ->where('producto_clave', $p->codigo)
            ->where('fecha', '>=', now()->subMonths(6))
            ->sum('cantidad_producto');

        $promedio_mensual = $ventas_historicas > 0 ? round($ventas_historicas / 6, 2) : 0;

        //recuperando sucursal del usuario y el nombre real de la sucursal
        $id_usuario = auth()->id();
        $sucursal_usuario = DB::table('usuarios')->where('id', $id_usuario)->value('tipo_sucursal');

        $sucursal = DB::table('sucursales')->where('id', $sucursal_usuario)->select('nombre_real')->first();

        // Logica de decision
        $accion = "No comprar";
        $sugerencia = 0;
     
        //si maxSucursal es igual al id del usuario entonces no mostrar surtir desde la sucursal, porque es la misma si no poner suficiente stock, si el maxSucursal es diferente entonces poner surtir desde sucursal
            if ($p->cant_a_pedir > $stock_total) {
                $accion = "Comprar";
                $sugerencia = max(($promedio_mensual - $stock_total), 0);
            } else {
                $maxSucursal = array_keys($existencias_por_sucursal, max($existencias_por_sucursal))[0] ?? null;
                if ($maxSucursal && $maxSucursal != $sucursal_usuario) {
                    $accion = "Surtir desde sucursal $maxSucursal";
                } else {
                    $accion = "Suficiente stock";
                }
            }
            $resultado[] = [
                'id'=>$p->id,
                'producto' => $p->nombre,
                'codigo' => $p->codigo,
                'um' => $p->um,
                'precio_lista' => $p->precio_lista,
                'precio_promocion' => $p->precio_promocion,
                'cantidad_cotizada' => $p->cant_a_pedir,
                'existencias' => $existencias_por_sucursal,
                'stock_total' => $stock_total,
                'ventas_6m' => $ventas_historicas,
                'promedio_mensual' => $promedio_mensual,
                'sugerencia_compra' => $sugerencia,
                'promocion'=>$p->precio_promocion,
                'descuento'=>$p->descuento,
                'precio_cliente'=>$p->precio_cliente,
                'importe'=>$p->importe,
                'accion' => $accion,
                'sucursal_usuario' => $sucursal_usuario,
                'sucursal' => $sucursal
            
            ];
        }

        return response()->json($resultado);

        
    }

   

    //actualizar producto y volve a recalcular inventario y stock y todo lo que se hace

    public function actualizarProductoPreAutorizacion(Request $request, $id_producto){
        $codigo = $request->input('codigo');
        $cantidad = $request->input('cantidad_cotizada');
        $id_presupuesto = $request->input('id_presupuesto');

        // recuperamos de DB el producto original
        $p = DB::table('cotizacion_productos')->where('id', $id_producto)->firs();

        if (!$p) {
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }

        //  actualizar el producto en la tabla cotizacion_productos
        DB::table('cotizacion_productos')->where('id', $id_producto)->update([
            'codigo'            => $codigo,
            'nombre'          => $request->input('producto'),
            'um'                => $request->input('um'),
            'precio_lista'      => $request->input('precio_lista'),
            'precio_promocion'  => $request->input('precio_promocion'),
            'cant_a_pedir' => $cantidad,
            'descuento'         => $request->input('descuento'),
            'precio_cliente'    => $request->input('precio_cliente'),
            'importe'           => $request->input('importe')
            
        ]);

        // ecuperar existencias del inventario
        $inventarios = DB::table('inventario_temporal')
            ->select('sucursal_id', 'existencia')
            ->where('clave_producto', $codigo)
            ->get();

        $existencias_por_sucursal = [];
        foreach ($inventarios as $inv) {
            $existencias_por_sucursal[$inv->sucursal_id] = $inv->existencia;
        }

        $stock_total = array_sum($existencias_por_sucursal);    

        //  ventas historicas (ultimos 6 meses)
        $ventas_historicas = DB::table('remisiones_teran')
            ->where('producto_clave', $codigo)
            ->where('fecha', '>=', now()->subMonths(6))
            ->sum('cantidad_producto');

        $promedio_mensual = $ventas_historicas > 0 ? round($ventas_historicas / 6, 2) : 0;

        $accion = "No comprar";
        $sugerencia = 0;

        if ($cantidad > $stock_total) {
            $accion = "Comprar";
            $sugerencia = max(($promedio_mensual - $stock_total), 0);
        } else {
            $maxSucursal = array_keys($existencias_por_sucursal, max($existencias_por_sucursal))[0] ?? null;
            $accion = $maxSucursal ? "Surtir desde sucursal $maxSucursal" : "Suficiente stock";
        }

        // enviando respuesta JSON para la tabla de anilsisi  
        return response()->json([
            'id'                => $id_producto,
            'producto'          => $request->input('producto'),
            'codigo'            => $codigo,
            'um'                => $request->input('um'),
            'precio_lista'      => $request->input('precio_lista'),
            'precio_promocion'  => $request->input('precio_promocion'),
            'cantidad_cotizada' => $cantidad,
            'existencias'       => $existencias_por_sucursal,
            'stock_total'       => $stock_total,
            'ventas_6m'         => $ventas_historicas,
            'promedio_mensual'  => $promedio_mensual,
            'sugerencia_compra' => $sugerencia,
            'descuento'         => $request->input('descuento'),
            'precio_cliente'    => $request->input('precio_cliente'),
            'importe'           => $request->input('importe'),
            'accion'            => $accion
        ]);
    }

    public function cruzarDatos(Request $request, $id_cotizacion){
        //recuperando los datos 
        $id = $id_cotizacion;
        $productos = $request->input('productos', []);
        $vigente = $request->input('vigente', 0);
        foreach ($productos as $p) {
            DB::table('datos_cruzados')->insert([
                'codigo' => $p['codigo'] ?? null,
                'nombre' => $p['nombre'] ?? null,
                'um' => $p['um'] ?? null,
                'precio_lista' => $p['precio_lista'] ?? null,
                'precio_promocion' => $p['precio_promocion'] ?? null,
                'cant_a_pedir' => $p['cant_a_pedir'] ?? null,
                'existencia_en_sucur' => json_encode($p['existencia_en_sucur'] ?? []),
                'stock_total' => $p['stock_total'] ?? 0,
                'vendido_anteriormente' => $p['vendido_anteriormente'] ?? 0,
                'promedio_mensual_ventas' => $p['promedio_mensual_ventas'] ?? 0,
                'sugerencia_pedido' => $p['sugerencia_pedido'] ?? 0,
                'analisi_obtenido' => $p['analisi_obtenido'] ?? '',
                'cotizacion_id' => $id_cotizacion,
                'importe' => $p['importe'] ?? 0,
                'created_at' => now()
            ]);
        }
        
        //mandando a actualizar el campo vigente en la tabla cotizaciones
        DB::table('cotizaciones')->where('id', $id)->update(['vigente' => $vigente]);

        return response()->json([
            'status' => 'success',
            'message' => 'Datos cruzados guardados correctamente, para autorizar dirígete a la sección de (Autorización) y busca la cotización.'
        ]);
    }

    public function enviarPDF(Request $request){
        if (!$request->hasFile('pdf')) {
        Log::warning('No se recibió archivo PDF');
        return response()->json(['status' => 'error', 'message' => 'No se recibió archivo PDF'], 400);
        }
        try {
            if ($request->hasFile('pdf')) {
                $pdf = $request->file('pdf');

                Mail::send([], [], function ($message) use ($pdf) {
                    $message->to(['acerinox.gerencia5ta@outlook.com'])
                        ->subject('PRE AUTORIZACION DE COTIZACION DE HERRAJES')
                        ->html('Adjunto encontrarás el reporte de productos en PDF.')
                        ->attach($pdf->getRealPath(), [
                            'as' => 'reporte_productos.pdf',
                            'mime' => 'application/pdf',
                        ]);
                });

                return response()->json(['status' => 'success']);
            }

            return response()->json(['status' => 'error', 'message' => 'No se recibió archivo PDF'], 400);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    //funcion para retomar la ultima vez que se cargo el inventario de la columna created_at y verificar si hay datos en el tabla temporal del inventario
    public function ultimaVezInventario(Request $request){
    // Verificamos si hay registros en la tabla
        $hay_datos = DB::table('inventario_temporal')->exists();

        if (!$hay_datos) {
        return response()->json([
            'status' => 'error',
            'mensaje' => 'No hay datos en el inventario temporal.'
            ], 404);
        }   

        // Si hay datos, obtenemos la ultima fecha de carga
        $ultima_vez_in = DB::table('inventario_temporal')
        ->max('created_at');

        return response()->json([
            'status' => 'success',
            'ultima_carga' => $ultima_vez_in
        ]);
    }


    //eliminado productos de la cotizacion
    public function deleteProductoCotizacion(Request $request, $id_prod_cotizacion){

        $delete_produc = DB::Table('cotizacion_productos')->where('id', $id_prod_cotizacion)->delete();

        return response()->json(['message'=>'Producto eliminado correctamente de la cotizacion']);
    }


    //aca foliamos la cotizacion
    
    public static function generarFolio(){
        $mesActual = now()->format('Ym'); 

        // Buscamos el ltimo registro que coincida con el mes actual
        $ultimoRegistro = DB::table('cotizaciones_foliadas')->whereRaw("DATE_FORMAT(fecha_de_autorizacion, '%Y%m') = ?", [$mesActual])->orderBy('id', 'desc')->first();

        if ($ultimoRegistro) {
            $partes = explode('-', $ultimoRegistro->folio);
            $ultimoNumero = (int) end($partes);
            $conteo = $ultimoNumero + 1;
        } else {
            // Si no hay registros en el mes, empezamos en 1
            $conteo = 1;
        }

        $folio = 'COT-' . $mesActual . '-' . str_pad($conteo, 4, '0', STR_PAD_LEFT);
        return $folio;
    }

    public function foliarCotizacion(Request $request){

        $folio_autorizado = $request->input('folio_autorizado', 1);

        $request->validate([
            'id_cotizacion' => 'required|exists:cotizaciones,id',
            'motivo' => 'required|string|max:255',
            'tipo_pedido' => 'required|in:Urgente,Normal,Programado,Otro',
            'id_usuario' => 'required|exists:usuarios,id',    
        ]);

        try {
            $folio = $this->generarFolio();

            $registro_id = DB::table('cotizaciones_foliadas')->insertGetId([
                'id_cotizacion' => $request->id_cotizacion,
                'motivo' => $request->motivo,
                'tipo_pedido' => $request->tipo_pedido,
                'id_usuario' => $request->id_usuario,
                'folio' => $folio,
                'comentarios_adicionales' => $request->comentarios_adicionales,
                'fecha_de_autorizacion' => now(),
            ]);

            DB::table('cotizaciones')->where('id', $request->id_cotizacion)->update(['folio_autorizado' => 1]);

            return response()->json([
                'success' => true,
                'folio' => $folio,
                'registro_id' => $registro_id
            ]);

        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'El folio generado ya existe. Por favor, intente de nuevo.'
            ], 409);
        }
    }

    //verificando si esta foliada la cotizacion
    public function verificaFoliacion(Request $request, $id_cotizacion){
        $existe_folio = DB::table('cotizaciones_foliadas')
            ->where('id_cotizacion', $id_cotizacion)
            ->exists();

       if ($existe_folio) {
           return response()->json([
               'status' => 'success',
               'message' => 'La cotización ya está foliada, puedes volver a descargar tu PDF.'
           ]);
       }

       return response()->json([
           'status' => 'error',
           'message' => 'La cotización no está foliada.'
       ]);
   }   

    public function quitarFolioCotizacion(Request $request, $id_cotizacion){
        // Eliminar el registro de foliación
        DB::table('cotizaciones_foliadas')->where('id_cotizacion', $id_cotizacion)->delete();
      
        DB::table('cotizaciones')->where('id', $id_cotizacion)->update(['folio_autorizado' => false, 'vigente' => true]);

        return response()->json([
            'status' => 'success',
            'message' => 'Folio eliminado y cotización actualizada correctamente.'
        ]);
    }

    //fucion pARA ver el PDF Qque se arma 
    public function verPDFCotizacion(Request $request, $id_cotizacion){
        // Recuperar cotización
        //mandamos a recuperar todas las cotizaciones que hay vigente, y su sucursal destino y el nombre real de la sucursal 
        $cotizaciones_vigente = DB::table('cotizaciones as c')
            ->join('sucursales as s', 'c.sucursal_id', '=', 's.id')
            ->where('c.id', $id_cotizacion)
            ->select('c.*', 's.nombre_real')
            ->first();

        if (!$cotizaciones_vigente) {
            return response()->json(['error' => 'Cotización no encontrada'], 404);
        }

        // Recuperar productos asociados
        $tabla_foliados = DB::table('datos_cruzados')->where('cotizacion_id', $id_cotizacion)->get();

        return response()->json(['cotizacion' => $cotizaciones_vigente,'productos' => $tabla_foliados]);
    }
   
   //vista para descargar el pdf de la cotizacion foliada
   //
    public function vistaDescargarPDFFoliado(){
        return view('cotizaciones_herrajes.dowloandPDFAutorizacion');
    }

    public function recuperarCotizacionesFoliadas(Request $request){
        
        // Alias para las tablas: c=cotizaciones, s=sucursales, cf=cotizaciones_foliadas, u=users
        $cotizaciones_foliadas = DB::table('cotizaciones as c')->join('sucursales as s', 'c.sucursal_id', '=', 's.id')->join('cotizaciones_foliadas as cf', 'c.id', '=', 'cf.id_cotizacion')->join('usuarios as u', 'cf.id_usuario', '=', 'u.id') ->where('c.folio_autorizado', true) ->select( 
                'c.*', 
                's.nombre_real', // Nombre de la sucursal
                'u.nombre', // Nombre del usuario que folió
                'u.apellido_p', // Apellido paterno del usuario que folió
                'u.apellido_m', // Apellido materno del usuario que folió
                'cf.folio', // Fecha de foliado
        )->orderBy('c.id', 'desc')
            ->get();

        return response()->json([
            'foliadas' => $cotizaciones_foliadas
        ]);
    }

    //recuperar los datos del pd
    public function generarPDFAutorizados($ids){
        // Convertir "6,8,9" en [6,8,9]
        $idArray = explode(',', $ids);

        if (empty($idArray)) {
            return response()->json(['error' => 'No se recibieron cotizaciones válidas'], 400);
        }

        try {
            // Crear placeholders seguros
            $placeholders = implode(',', array_fill(0, count($idArray), '?'));

            // Consulta completa con JOINs a usuarios y cotizaciones
            $query = "
                SELECT 
                    cf.id AS id_foliado,
                    cf.folio,
                    cf.motivo,
                    cf.tipo_pedido,
                    cf.fecha_de_autorizacion,
                    cf.comentarios_adicionales,
                    u.id AS id_usuario,
                    CONCAT(u.nombre, ' ', u.apellido_p, ' ', u.apellido_m) AS nombre_completo,
                    u.correo,
                    u.telefono,
                    u.curp,
                    u.domicilio,
                    c.id AS id_cotizacion,
                    c.presupuesto,
                    c.fecha_cotizacion,
                    c.vendedor,
                    c.proveedor,
                    c.lista_precio,
                    c.total_cotizacion,
                    c.sucursal_id,
                    s.nombre_real
                FROM cotizaciones_foliadas cf
                INNER JOIN usuarios u ON cf.id_usuario = u.id
                INNER JOIN cotizaciones c ON cf.id_cotizacion = c.id
                INNER JOIN sucursales s ON c.sucursal_id = s.id
                WHERE cf.id_cotizacion IN ($placeholders)
                ORDER BY cf.fecha_de_autorizacion DESC
            ";

            $resultado = DB::select($query, $idArray);

            if (empty($resultado)) {
                return response()->json(['mensaje' => 'No se encontraron registros para los IDs proporcionados.']);
            }

            return response()->json(['pdf' => $resultado]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al consultar datos: ' . $e->getMessage()], 500);
        }
    }

    public function sat(){
        return view('layout.sat');
    }

    public function vistaCotizacionesFacturadas(){
        return view('cotizaciones_herrajes.cotizaciones_facturadas');
    }

    
    public function comparar(Request $request, $presupuesto){

         $existe_presupuesto = DB::table('cotizaciones')->where('presupuesto', $presupuesto)->exists();

        //dd($existe_presupuesto);
        if (!$existe_presupuesto) {

            return response()->json(['error' => 'No se encontró ninguna cotización con el presupuesto: ' . $presupuesto], 404);
        }
        $request->validate([
            'codigo_presupuesto' => 'required|string',
            'xml_file' => 'required|file|mimes:xml',
        ]);

        $presupuestoCodigo = $request->input('codigo_presupuesto');
        $file = $request->file('xml_file');

        $cotizacion = DB::table('cotizaciones')->where('presupuesto', $presupuestoCodigo)->first();


        //  Leer y parsear el XML (Estándar SAT CFDI 4.0)
        $xmlData = simplexml_load_string(file_get_contents($file));
        $namespaces = $xmlData->getNamespaces(true);
        $xmlData->registerXPathNamespace('cfdi', $namespaces['cfdi']);

        // Extraer datos del Timbre 
        $tfd = $xmlData->xpath('//tfd:TimbreFiscalDigital');
        $folioSat = isset($tfd[0]) ? (string)$tfd[0]['UUID'] : null;
        $fechaTimbrado = isset($tfd[0]) ? (string)$tfd[0]['FechaTimbrado'] : null;

        $conceptosFacturados = [];
        foreach ($xmlData->xpath('//cfdi:Concepto') as $concepto) {
            $cantFacturada = (float)$concepto['Cantidad'];
            $precioUnitario = (float)$concepto['ValorUnitario'];
            $importe = (float)$concepto['Importe'];
            $descuento = isset($concepto['Descuento']) ? (float)$concepto['Descuento'] : 0.00;
            
            // Calcular porcentaje de descuento simulado si existe
            $porcentajeDesc = $importe > 0 ? ($descuento / $importe) * 100 : 0;
            $imporConDes = $importe - $descuento;

            // Guardar en la nueva tabla
            DB::table('cotizaciones_facturadas')->insert([
                'codigo' => (string)$concepto['NoIdentificacion'], 
                'nombre' => (string)$concepto['Descripcion'],
                'um' => (string)$concepto['ClaveUnidad'],
                'cant_facturada' => $cantFacturada,
                'precio_unitario' => $precioUnitario,
                'importe' => $importe,
                'descuento' => $descuento,
                'porcentaje_descuento' => $porcentajeDesc,
                'impor_con_des' => $imporConDes,
                'iva' => 0.00,
                'folio_sat' => $folioSat,
                'codigo_presupuesto' => $presupuestoCodigo,
                'fecha_timbrado' => $fechaTimbrado ? date('Y-m-d H:i:s', strtotime($fechaTimbrado)) : null,
                'analizado_por' => auth()->user()->name ?? 'Sistema',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $conceptosFacturados[(string)$concepto['NoIdentificacion']] = [
                'nombre' => (string)$concepto['Descripcion'],
                'cantidad' => $cantFacturada,
                'precio' => $precioUnitario,
                'descuento_porc' => $porcentajeDesc,
                'importe' => $imporConDes
            ];
        }

        // Traer los productos originalmente cotizados
        $productosCotizados = DB::table('cotizaciones_productos')
            ->where('cotizacion_id', $cotizacion->id)
            ->get();

        $reporte = [];

        foreach ($productosCotizados as $prodCot) {
            $codigo = $prodCot->codigo;
            
            $facturado = $conceptosFacturados[$codigo] ?? null;

            if ($facturado) {
                $diffCant = $prodCot->cant_a_pedir != $facturado['cantidad'];
                $diffPrecio = $prodCot->precio_lista != $facturado['precio'];
                $diffDesc = abs($prodCot->descuento - $facturado['descuento_porc']) > 0.1; 

                $detallesError = [];
                if ($diffCant) $detallesError[] = "Cantidad difiere";
                if ($diffPrecio) $detallesError[] = "Precio alterado";
                if ($diffDesc) $detallesError[] = "Descuento no respetado";

                $status = empty($detallesError) ? 'OK' : 'ERROR';

                $reporte[] = [
                    'codigo' => $codigo,
                    'nombre' => $prodCot->nombre,
                    'cant_cotizada' => $prodCot->cant_a_pedir,
                    'cant_facturada' => $facturado['cantidad'],
                    'precio_cotizado' => $prodCot->precio_lista,
                    'precio_facturado' => $facturado['precio'],
                    'desc_cotizado' => $prodCot->descuento,
                    'desc_facturado' => round($facturado['descuento_porc'], 2),
                    'importe_cotizado' => $prodCot->importe,
                    'importe_facturado' => $facturado['importe'],
                    'diff_cant' => $diffCant,
                    'diff_precio' => $diffPrecio,
                    'diff_desc' => $diffDesc,
                    'status' => $status,
                    'detalles_error' => $detallesError
                ];

                // Quitar del array temporal para saber si hubo productos en el XML que NO estaban cotizados
                unset($conceptosFacturados[$codigo]);
            } else {
                // Producto cotizado que NO llego en la factura XML
                $reporte[] = [
                    'codigo' => $codigo,
                    'nombre' => $prodCot->nombre,
                    'cant_cotizada' => $prodCot->cant_a_pedir,
                    'cant_facturada' => 0,
                    'precio_cotizado' => $prodCot->precio_lista,
                    'precio_facturado' => 0,
                    'desc_cotizado' => $prodCot->descuento,
                    'desc_facturado' => 0,
                    'importe_cotizado' => $prodCot->importe,
                    'importe_facturado' => 0,
                    'diff_cant' => true,
                    'diff_precio' => false,
                    'diff_desc' => false,
                    'status' => 'ERROR',
                    'detalles_error' => ['Producto no facturado']
                ];
            }
        }

        // Agregar al reporte productos que vinieron en el XML pero NO estaban cotizados
        foreach ($conceptosFacturados as $codigoxml => $fact) {
            $reporte[] = [
                'codigo' => $codigoxml,
                'nombre' => $fact['nombre'],
                'cant_cotizada' => 0,
                'cant_facturada' => $fact['cantidad'],
                'precio_cotizado' => 0,
                'precio_facturado' => $fact['precio'],
                'desc_cotizado' => 0,
                'desc_facturado' => $fact['descuento_porc'],
                'importe_cotizado' => 0,
                'importe_facturado' => $fact['importe'],
                'diff_cant' => true,
                'diff_precio' => true,
                'diff_desc' => false,
                'status' => 'ERROR',
                'detalles_error' => ['Producto extra no cotizado']
            ];
        }

        return view('cotizaciones.comparador', [
            'reporte' => $reporte,
            'presupuesto' => $presupuestoCodigo
        ]);
    }

    public function verificarPresupuestoExis(Request $request, $presupuesto){
        
        $existe_presupuesto = DB::table('cotizaciones')->where('presupuesto', $presupuesto)->exists();

        //dd($existe_presupuesto);

        if (!$existe_presupuesto) {

            return response()->json(['error' => 'No se encontró ninguna cotización con el presupuesto: ' . $presupuesto], 404);
        }

        return response()->json(['success' => 'Presupuesto encontrado, puedes habilitar la opción de subir XML para comparar.']);
    }


    public function guardarDatosGeneralesFacturas(Request $request){
        try {
            $validate = $request->validate([
                'presupuesto'    => 'required|string',
                'rfc'            => 'required|string',
                'nombre'         => 'required|string',
                'fecha_timbrado' => 'required|date',
                'uuid'           => 'required|string',
                'serie_folio'    => 'required|string',
                'subtotal'       => 'required|numeric',
                'descuento'      => 'required|numeric',
                'neto'           => 'required|numeric',
                'iva'            => 'required|numeric',
                'total'          => 'required|numeric',
            ], [
                'presupuesto.required'    => 'El campo presupuesto es obligatorio.',
                'rfc.required'            => 'El campo RFC es obligatorio.',
                'nombre.required'         => 'El campo nombre es obligatorio.',
                'fecha_timbrado.required' => 'El campo fecha de timbrado es obligatorio.',
                'uuid.required'           => 'El campo UUID es obligatorio.',
                'serie_folio'             => 'El campo de serie y folio es obligatorio',
                'subtotal.required'       => 'El campo subtotal es obligatorio.',
                'descuento.required'      => 'El campo descuento es obligatorio.',
                'neto.required'           => 'El campo neto es obligatorio.',
                'iva.required'            => 'El campo IVA es obligatorio.',
                'total.required'          => 'El campo total es obligatorio.',
            ]);

            // Verificar si el UUID ya existe
            $facturaExistente = DB::table('cotizaciones_facturadas')
            ->where('uuid', $validate['uuid'])
            ->first();

            if ($facturaExistente) {
                // Evaluamos si el usuario intenta subirlo al mismo presupuesto o a otro diferente
                if ($facturaExistente->presupuesto === $validate['presupuesto']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Esta factura ya se encuentra registrada en este presupuesto (' . $validate['presupuesto'] . ').'
                    ], 400);
                } else {
                    // Bloqueo total: Intentan meter un XML fiscal que ya pertenece a otra cotizaci
                    return response()->json([
                        'success' => false,
                        'message' => '⚠️ Movimiento no permitido: El folio fiscal (UUID) ya fue dado de alta previamente en el Presupuesto: ' . $facturaExistente->presupuesto . '. No es posible asignarlo a dos presupuestos distintos.'
                    ], 400);
                }
            }

            //verificar si essa factura ya cerror su ciclo
            $cerraCiclo = DB::Table('cotizaciones')->where('presupuesto',$validate['presupuesto'])->where('emision_completa', 1)->first();

            if($cerraCiclo){
                return response()->json([
                        'success' => false,
                        'message' => 'Este presupuesto (' . $validate['presupuesto'] . '). ya se encuentra conciliado en su totalidad. Ya no puedes cargarle mas facturas (XML)'
                    ], 410);
            }

            // Insertar obteniendo el ID asignado por la base de datos
            $idGenerado = DB::table('cotizaciones_facturadas')->insertGetId([
                'presupuesto'        => $validate['presupuesto'],
                'rfc'                => $validate['rfc'],
                'nombre'             => $validate['nombre'],
                'fecha_timbrado'     => $validate['fecha_timbrado'],
                'uuid'               => $validate['uuid'],
                'serie_folio'        => $validate['serie_folio'],
                'subtotal'           => $validate['subtotal'],
                'descuento'          => $validate['descuento'],
                'neto'               => $validate['neto'],
                'iva'                => $validate['iva'],
                'total'              => $validate['total'],
                'usuario_analizador' => Auth::user()->id,
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Datos guardados exitosamente, procede a revisar tus productos para esta factura.',
                'id_cotizacion_facturada' => $idGenerado
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación en los datos enviados.',
                'errors'  => $e->errors()
            ], 422); 

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error inesperado en el servidor.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    //guardamos los prductos de la factura 
    public function guardarProductosFacturas(Request $reques, $id_factura){
        try {
            $productos = $reques->input('productos', []);

            foreach ($productos as $prod) {
                DB::table('cotizaciones_productos_facturados')->insert([
                    'codigo' => $prod['codigo'] ?? null,
                    'nombre' => $prod['nombre'] ?? null,
                    'um' => $prod['um'] ?? null,
                    'cant_facturada' => $prod['cant_facturada'] ?? null,
                    'precio_unitario' => $prod['precio_unitario'] ?? null,
                    'importe' => $prod['importe'] ?? null,
                    'descuento' => $prod['descuento'] ?? null,
                    'porcentaje_descuento' => $prod['porcentaje_descuento'] ?? null,
                    'impor_con_des' => $prod['impor_con_des'] ?? null,
                    'iva' => $prod['iva'] ?? null,
                    'id_facturada'=>$id_factura,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Productos de la factura guardados correctamente.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al guardar los productos de la factura.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    //en esta vista veremos los presupuestos con que tiene sus facturas
    public function vistaPresupuestoConciliacion(){
        // traemos los datos planos haciendo los cruces correspondientes
       return view('cotizaciones_herrajes.conciliar_factura');
    }
    
    public function recuperaCotizacionConXml(){
        $lineasAuditoria = DB::table('cotizaciones_facturadas')
            ->leftJoin('cotizaciones', function($join) {
                $join->on(DB::raw('cotizaciones.presupuesto COLLATE utf8mb4_unicode_ci'), '=', 'cotizaciones_facturadas.presupuesto');
            })
            ->leftJoin('sucursales', 'cotizaciones.sucursal_id', '=', 'sucursales.id')
            ->select(
                'cotizaciones.id as cotizacion_id',
                DB::raw("COALESCE(cotizaciones.presupuesto, 'SIN_PRESUPUESTO') as presupuesto_folio"),
                'cotizaciones.fecha_cotizacion',
                DB::raw("COALESCE(cotizaciones.proveedor, 'SIN PROVEEDOR ASIGNADO') as proveedor"),
                'cotizaciones.total_cotizacion',
                'cotizaciones.sucursal_id',
                'cotizaciones.folio_autorizado',
                'cotizaciones.emision_completa as emision_completa_cotizaciones',
                DB::raw("COALESCE(sucursales.nombre_real, 'SIN SUCURSAL') as nombre_sucursal"),
                'cotizaciones_facturadas.id as factura_id',
                'cotizaciones_facturadas.fecha_timbrado',
                'cotizaciones_facturadas.uuid',
                'cotizaciones_facturadas.serie_folio',
                'cotizaciones_facturadas.subtotal',
                'cotizaciones_facturadas.descuento',
                'cotizaciones_facturadas.neto',
                'cotizaciones_facturadas.iva',
                'cotizaciones_facturadas.retencion',
                'cotizaciones_facturadas.total as total_factura',
                'cotizaciones_facturadas.emision_completa' 
            )
            ->orderBy('presupuesto_folio', 'asc')
            ->orderBy('cotizaciones_facturadas.fecha_timbrado', 'asc')
            ->get();

        $presupuestosAgrupados = $lineasAuditoria->groupBy('presupuesto_folio');

        return response()->json(['presupuestos' => $presupuestosAgrupados]);
    }
    
    public function analizarPartidasFacturas(Request $request){
        try {
            $facturaId = $request->input('factura_id');
            $folioPresupuesto = $request->input('presupuesto');

            //  Obtener los datos generales de la cotizacion
            $cotizacionFila = DB::table('cotizaciones')
                ->where('presupuesto', $folioPresupuesto)
                ->select('id', 'total_cotizacion') 
                ->first();

            if (!$cotizacionFila) {
                return response()->json(['success' => false, 'message' => 'No se encontró la cotización especificada.'], 404);
            }

            $totalCotizacionReal = (float) $cotizacionFila->total_cotizacion;

            // Partidas originales de la cotizacion
            $partidasCotizacion = DB::table('cotizacion_productos')
                ->where('cotizacion_id', $cotizacionFila->id)
                ->select(
                    'codigo',
                    'nombre',
                    'cant_a_pedir as cant_cotizada',
                    'precio_lista as precio_cotizado',
                    'importe as subtotal_cotizado'
                )
                ->get()
                ->keyBy('codigo'); 

            //Historico acumulado por producto
            $historicoConciliado = DB::table('analizis_facturado as af')
                ->join('cotizaciones_facturadas as cf', 'af.id_facturada', '=', 'cf.id')
                ->where('af.cotizacion_id', $cotizacionFila->id)
                ->select(
                    'af.codigo',
                    DB::raw('SUM(af.cant_facturada) as total_cant_facturada')
                )
                ->groupBy('af.codigo')
                ->get()
                ->keyBy('codigo');

            // Datos de la factura actual que se abre
            $facturaFila = DB::table('cotizaciones_facturadas') 
                ->where('id', $facturaId)
                ->select('total','uuid','serie_folio') 
                ->first();

            $totalFacturaReal = $facturaFila ? (float) $facturaFila->total : 0.0;

            // Monto total acumulado de las facturas del presupuesto
            $totalDineroFacturadoAcumulado = DB::table('cotizaciones_facturadas')
                ->where('presupuesto', $folioPresupuesto) 
                ->sum('total'); 
            $facturasDelPresupuesto = DB::table('cotizaciones_facturadas')
                ->where('presupuesto', $folioPresupuesto)
                ->select('serie_folio', 'uuid', 'total', 'id')
                ->get();

            // Partidas del XML actual
            $partidasXml = DB::table('cotizaciones_productos_facturados')
                ->where('id_facturada', $facturaId)
                ->select(
                    'codigo',
                    'nombre as descripcion_xml',
                    'cant_facturada',
                    'precio_unitario as precio_facturado',
                    'impor_con_des as subtotal_facturado'
                )
                ->get();

            $analisisCompleto = [];

            // Cruzar lo Facturado actual contra lo Cotizado
            foreach ($partidasXml as $xml) {
                $clave = $xml->codigo;
                $cotizado = $partidasCotizacion->get($clave);
                $hist = $historicoConciliado->get($clave);
                
                $cantConciliadaAnterior = $hist ? (float)$hist->total_cant_facturada : 0;

                $cantCot = $cotizado ? $cotizado->cant_cotizada : 0;
                $precioCot = $cotizado ? $cotizado->precio_cotizado : 0;
                $totalCotizadoCalculado = $cotizado ? $cotizado->subtotal_cotizado : 0;

                $cantidadAcumuladaTotal = $cantConciliadaAnterior + (float)$xml->cant_facturada;

                $difCantidad = $cantidadAcumuladaTotal - $cantCot;
                $difPrecio    = $xml->precio_facturado - $precioCot;
                $difSubtotal  = ($cantidadAcumuladaTotal * $xml->precio_facturado) - $totalCotizadoCalculado;
                    
                $estatus = 'OK';
                if (!$cotizado) {
                    $estatus = 'EXCEDENTE_PRODUCTO'; 
                } elseif ($difPrecio > 0.01) {
                    $estatus = 'PRECIO_INFLADO';      
                } elseif ($cantidadAcumuladaTotal < $cantCot) {
                    $estatus = 'SURTIDO_INCOMPLETO'; 
                } elseif ($cantidadAcumuladaTotal == $cantCot) {
                    $estatus = 'OK'; 
                } elseif ($difCantidad > 0) {
                    $estatus = 'CANTIDAD_EXCEDIDA';  
                }

                $analisisCompleto[] = [
                    'clave'            => $clave,
                    'descripcion'      => $xml->descripcion_xml,
                    'cant_cotizada'    => $cantCot,
                    'precio_cotizado'  => $precioCot,
                    'cant_facturada'   => $xml->cant_facturada,
                    'precio_facturado' => $xml->precio_facturado,
                    'total_cotizado'   => $totalCotizadoCalculado,
                    'total_facturado'  => $xml->subtotal_facturado,
                    'dif_cantidad'     => $difCantidad,
                    'dif_precio'       => $difPrecio,
                    'dif_total'        => $difSubtotal,
                    'estatus'          => $estatus
                ];

                if ($cotizado) {
                    $partidasCotizacion->forget($clave);
                }
            }
            foreach ($partidasCotizacion as $restante) {
                $clave = $restante->codigo;
                $hist = $historicoConciliado->get($clave);
                
                $cantConciliadaAnterior = $hist ? (float)$hist->total_cant_facturada : 0;

                if ($cantConciliadaAnterior >= $restante->cant_cotizada) {
                    $analisisCompleto[] = [
                        'clave'            => $clave,
                        'descripcion'      => $restante->nombre,
                        'cant_cotizada'    => $restante->cant_cotizada,
                        'precio_cotizado'  => $restante->precio_cotizado,
                        'cant_facturada'   => $cantConciliadaAnterior, 
                        'precio_facturado' => $restante->precio_cotizado,
                        'total_cotizado'   => $restante->subtotal_cotizado,
                        'total_facturado'  => $cantConciliadaAnterior * $restante->precio_cotizado,
                        'dif_cantidad'     => 0,
                        'dif_precio'       => 0,
                        'dif_total'        => 0,
                        'estatus'          => 'OK'
                    ];
                } else {
                    $analisisCompleto[] = [
                        'clave'            => $clave,
                        'descripcion'      => $restante->nombre,
                        'cant_cotizada'    => $restante->cant_cotizada,
                        'precio_cotizado'  => $restante->precio_cotizado,
                        'cant_facturada'   => 0,
                        'precio_facturado' => 0,
                        'total_cotizado'   => $restante->subtotal_cotizado,
                        'total_facturado'  => 0,
                        'dif_cantidad'     => $cantConciliadaAnterior - $restante->cant_cotizada,
                        'dif_precio'       => -$restante->precio_cotizado,
                        'dif_total'        => ($cantConciliadaAnterior * $restante->precio_cotizado) - $restante->subtotal_cotizado,
                        'estatus'          => $cantConciliadaAnterior > 0 ? 'SURTIDO_INCOMPLETO' : 'NO_FACTURADO'
                    ];
                }
            }

            $desviacionGlobalReal = $totalDineroFacturadoAcumulado - $totalCotizacionReal;

            return response()->json([
                'success' => true,
                'resumen' => [
                    'total_cotizado'    => $totalCotizacionReal,
                    'total_facturado'   => $totalDineroFacturadoAcumulado, 
                    'desviacion_global' => $desviacionGlobalReal,
                    'total_factura_actual' => $totalFacturaReal,
                ],
                'partidas' => $analisisCompleto,
                'facturas_presupuesto' => $facturasDelPresupuesto, 
                'factura_id' => $facturaId,
                'uuid' => $facturaFila ? $facturaFila->uuid : null,
                'serie_folio' => $facturaFila ? $facturaFila->serie_folio : null
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno en el cálculo de auditoría.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }   

    public function guardarConciliacionFactura(Request $request){
        DB::beginTransaction();
        try {
            $facturaId = $request->input('factura_id');
            
            //  Obtener los datos del XML y del presupuesto involucrado
            $factura = DB::table('cotizaciones_facturadas')->where('id', $facturaId)->first();
            if (!$factura) {
                return response()->json(['success' => false, 'message' => 'Factura no encontrada.'], 404);
            }

            $cotizacion = DB::table('cotizaciones')->where('presupuesto', $factura->presupuesto)->first();
            if (!$cotizacion) {
                return response()->json(['success' => false, 'message' => 'Cotización no encontrada.'], 404);
            }
            
            // Traer los conceptos cargados desde este XML específico
            $partidasXml = DB::table('cotizaciones_productos_facturados')
                ->where('id_facturada', $facturaId)
                ->get();

            foreach ($partidasXml as $xml) {
                // Buscar si este producto ya tiene un historial acumulado en esta cotizacion
                $acumuladoExistente = DB::table('analizis_facturado')
                    ->where('cotizacion_id', $cotizacion->id)
                    ->where('codigo', $xml->codigo)
                    ->first();

                // Historicos acumulados previos
                $cantFacturadaPrevia = $acumuladoExistente ? (float)$acumuladoExistente->cant_facturada : 0;
                $montoFacturadoPrevio = $acumuladoExistente ? (float)$acumuladoExistente->monto_total_facturado_acumulado : 0;

                $nuevaCantFacturada = $cantFacturadaPrevia + (float)$xml->cant_facturada;
                $nuevoMontoFacturado = $montoFacturadoPrevio + (float)$xml->impor_con_des;

                // Obtener los datos originales cargados en la cotizacion base
                $prodCotizado = DB::table('cotizacion_productos')
                    ->where('cotizacion_id', $cotizacion->id)
                    ->where('codigo', $xml->codigo)
                    ->first();

                $cantCotizada = $prodCotizado ? (float)$prodCotizado->cant_a_pedir : 0;
                $montoCotizado = $prodCotizado ? (float)$prodCotizado->importe : 0;

                // Calcular Precios Unitarios (Evitando divisiones entre cero si no se cotizo o no llegó)
                $precioCotizadoUnitario = $cantCotizada > 0 ? ($montoCotizado / $cantCotizada) : 0;
                $precioFacturadoUnitario = (float)$xml->cant_facturada > 0 ? ((float)$xml->impor_con_des / (float)$xml->cant_facturada) : 0;


                $cantDesviacion = $nuevaCantFacturada - $cantCotizada;
                $precioDesviacion = $precioFacturadoUnitario - $precioCotizadoUnitario;
                
                $pendiente = $cantCotizada - $nuevaCantFacturada;
                $cantPendienteFinal = $pendiente > 0 ? $pendiente : 0; 
                $diferenciaMontoTotal = $nuevoMontoFacturado - $montoCotizado;
                // DETERMINAR DICTAMEN DE AUDITORiA AUTOmaTICO
                $dictamen = 'CORRECTO';
                if ($cantCotizada > 0 && $nuevaCantFacturada == 0) {
                    $dictamen = 'NO SURTIDO';
                } elseif ($nuevaCantFacturada > $cantCotizada) {
                    $dictamen = 'PIEZAS DE MAS';
                } elseif ($nuevaCantFacturada < $cantCotizada) {
                    $dictamen = 'PIEZAS DE MENOS';
                } elseif (abs($precioDesviacion) > 0.05) { // Tolerancia de 5 centavos por redondeos
                    $dictamen = 'PRECIO ALTERADO';
                }
                // DETERMINAR ESTADO OPERATIVO (Para mantener compatibilidad con tus pantallas)
                $estadoPartida = 'PENDIENTE';
                if ($nuevaCantFacturada == $cantCotizada) {
                    $estadoPartida = 'COMPLETADO';
                } elseif ($nuevaCantFacturada > $cantCotizada) {
                    $estadoPartida = 'EXCESO';
                }
                // Mapeo exacto alineado a tu nueva estructura de base de datos
                $datosPartida = [
                    'id_facturada'                    => $facturaId,
                    'usuario_nota_id'                 => auth()->id() ?? null, // Captura quién audita
                    'presupuesto'                     => $factura->presupuesto,
                    'nombre'                          => $xml->nombre,
                    
                    // Cantidades
                    'cant_cotizada'                   => $cantCotizada,
                    'cant_facturada'                  => $nuevaCantFacturada,
                    'cant_desviacion'                 => $cantDesviacion,
                    'cant_pendiente'                  => $cantPendienteFinal,
                    
                    // Precios Unitarios
                    'precio_cotizado'                 => $precioCotizadoUnitario,
                    'precio_facturado'                => $precioFacturadoUnitario,
                    'precio_desviacion'               => $precioDesviacion,
                    
                    // Totales Económicos
                    'monto_total_cotizado'            => $montoCotizado,
                    'monto_total_facturado_acumulado' => $nuevoMontoFacturado,
                    'diferencia_monto_total'          => $diferenciaMontoTotal,
                    
                    // Estados y Dictámenes
                    'dictamen'                        => $dictamen,
                    'estado_partida'                  => $estadoPartida,
                    'updated_at'                      => now()
                ];

                $existeRegistro = DB::table('analizis_facturado')
                    ->where('cotizacion_id', $cotizacion->id)
                    ->where('codigo', $xml->codigo)
                    ->exists();

                if ($existeRegistro) {
                    DB::table('analizis_facturado')
                        ->where('cotizacion_id', $cotizacion->id)
                        ->where('codigo', $xml->codigo)
                        ->update($datosPartida);
                } else {
                    $datosPartida['cotizacion_id'] = $cotizacion->id;
                    $datosPartida['codigo']        = $xml->codigo;
                    $datosPartida['created_at']    = now();
                    DB::table('analizis_facturado')->insert($datosPartida);
                }
            }

            // Marcar este XML individual como procesado/conciliado
            DB::table('cotizaciones_facturadas')->where('id', $facturaId)->update(['emision_completa' => 1]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Factura conciliada y análisis de desviaciones guardado con éxito.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => 'Error interno en el cálculo de auditoría.', 
                'error' => $e->getMessage()
            ], 500);
        }
    }
        
    //aca matamos todo el puto desmadre
    public function cerrarAuditoria(Request $request){
        $validator = Validator::make($request->all(), [
            'folio_presupuesto'          => 'required|string|max:50',
            'total_cotizado'             => 'required|numeric',
            'total_facturado'            => 'required|numeric',
            'monto_diferencia'           => 'required|numeric',
            'cuadro_perfecto'            => 'required|in:0,1',
            'cantidad_facturas_ligadas'  => 'required|integer|min:0',
            'nota_auditoria'             => $request->cuadro_perfecto == 0 ? 'required|string|min:10' : 'nullable|string',
        ], [
            'nota_auditoria.required'    => 'La justificación es obligatoria cuando existe un desfase en los montos.',
            'nota_auditoria.min'         => 'Por favor, escribe una justificación más detallada (mínimo 10 caracteres).'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

    
        DB::beginTransaction();

        try {
    
            $cotizacion = DB::table('cotizaciones')
                ->where('presupuesto', $request->folio_presupuesto) 
                ->first();

            if (!$cotizacion) {
                return response()->json([
                    'success' => false,
                    'message' => "No se encontró la cotización con el folio: {$request->folio_presupuesto}"
                ], 404);
            }

            $yaCerrado = DB::table('cotizaciones_facturas_cierre')
                ->where('id_factura', $cotizacion->id)
                ->exists();

            if ($yaCerrado) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este presupuesto ya se encuentra auditado y cerrado previamente.'
                ], 400);
            }

            DB::table('cotizaciones_facturas_cierre')->insert([
                'id_factura'                => $cotizacion->id,
                'id_usuario'                => Auth::id() ?? 1, 
                'total_cotizado'            => $request->total_cotizado,
                'total_facturado'           => $request->total_facturado,
                'monto_diferencia'          => $request->monto_diferencia,
                'cuadro_perfecto'           => $request->cuadro_perfecto,
                'cantidad_facturas_ligadas' => $request->cantidad_facturas_ligadas,
                'nota_auditoria'            => $request->nota_auditoria,
                'fecha_cierre'              => now(),
                'created_at'                => now(),
                'updated_at'                => now(),
            ]);

            DB::table('cotizaciones')
                ->where('id', $cotizacion->id)
                ->update([
                    'emision_completa' => 1    
                ]);

            DB::commit();

            $mensajeExito = $request->cuadro_perfecto == 1 
                ? 'Auditoría guardada exitosamente. El ciclo cerró en cuadro perfecto.' 
                : 'Cierre forzado registrado correctamente. Se ha archivado la justificación del desfase.';

            return response()->json([
                'success' => true,
                'message' => $mensajeExito
            ], 200);

        } catch (\Exception $e) {
           
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error interno al procesar el cierre: ' . $e->getMessage()
            ], 500);
        }
    }

    public function guardarMatch(Request $request){
        // Ajustamos la validación para permitir arrays vacíos
        $request->validate([
            'folio_presupuesto' => 'required',
            'monto_presupuesto' => 'required|numeric',
            'monto_factura'     => 'required|numeric',
            'diferencia'        => 'required|numeric',
            'comentarios'       => 'required|string',
            'facturas'          => 'required|array' 
        ]);

        $presupuesto = DB::table('cotizaciones')
            ->where('presupuesto', $request->folio_presupuesto)
            ->first();

        if (!$presupuesto) {
            return response()->json(['success' => false, 'message' => 'No se encontró el presupuesto base en el sistema.']);
        }

        DB::beginTransaction();

        try {
            $añoActual = Carbon::now()->year;
            $ultimoId = DB::table('cotizaciones_conciliadas')->max('id') ?? 0;
            $nuevoConsecutivo = str_pad($ultimoId + 1, 5, '0', STR_PAD_LEFT);
            $folioConciliacion = "CONC-{$añoActual}-{$nuevoConsecutivo}";

            // INSERCIÓN EN LA TABLA MAESTRO
            $idMaestroConciliacion = DB::table('cotizaciones_conciliadas')->insertGetId([
                'id_presupuesto'     => $presupuesto->id,
                'folio_conciliacion' => $folioConciliacion,
                'monto_presupuesto'  => $request->monto_presupuesto,
                'monto_factura'      => $request->monto_factura,
                'diferencia'         => $request->diferencia,
                'fecha_presupuesto'  => $presupuesto->created_at ?? Carbon::now(),
                'fecha_conciliado'   => Carbon::now(),
                'comentarios'        => $request->comentarios,
                'created_at'         => Carbon::now(),
                'updated_at'         => Carbon::now()
            ]);

            if (is_array($request->facturas) && count($request->facturas) > 0) {
                foreach ($request->facturas as $fact) {
                    DB::table('detalle_conciliacion_facturas')->insert([
                        'id_cotizacion_conciliada' => $idMaestroConciliacion,
                        'id_cotizacion_facturada'  => $fact['id_factura'], 
                        'monto_individual_factura' => $fact['monto'],
                        'created_at'                => Carbon::now(),
                        'updated_at'               => Carbon::now()
                    ]);  
                }
            }

            $update_estatus = DB::table('cotizaciones')->where('presupuesto', $request->folio_presupuesto)->update(['emision_completa'=>1]);

            DB::commit();

            return response()->json([
                'success' => true,
                'folio_conciliacion' => $folioConciliacion
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Falla interna en transacción: ' . $e->getMessage()
            ], 500);
        }
    }

    public function vistaReporteFactura() {
        return view('cotizaciones_herrajes.reportes_facturas');
    }

    // Recuperar datos reportes de auditoria de forma segura y compatible
    public function auditoriaConciliaciones() {
        $registrosPlanos = DB::table('cotizaciones_conciliadas as cc')
            ->leftJoin('detalle_conciliacion_facturas as dcf', 'cc.id', '=', 'dcf.id_cotizacion_conciliada')
            ->leftJoin('cotizaciones_facturadas as cf', 'dcf.id_cotizacion_facturada', '=', 'cf.id')
            ->leftJoin('cotizaciones as c', 'cc.id_presupuesto', '=', 'c.id')
            ->leftJoin('sucursales as s', 'c.sucursal_id', '=', 's.id')
            ->select([
                'cc.id',
                'cc.id_presupuesto',
                'cc.folio_conciliacion',
                'cc.monto_presupuesto',
                'cc.monto_factura',
                'cc.diferencia',
                'cc.fecha_presupuesto',
                'cc.fecha_conciliado',
                'cc.comentarios',
                'dcf.id as detalle_id',
                'dcf.monto_individual_factura',
                'cf.serie_folio',
                'cf.uuid',
                'c.presupuesto as folio_presupuesto',
                's.nombre_real as nombre_sucursal'
            ])
            ->orderBy('cc.fecha_conciliado', 'desc')
            ->get();

        $conciliaciones = $registrosPlanos->groupBy('id')->map(function ($grupo) {
            $cabecera = $grupo->first();
            $detalles = $grupo->filter(function ($item) {
                return !is_null($item->detalle_id);
            })->map(function ($item) {
                return [
                    'id' => $item->detalle_id,
                    'monto_individual_factura' => $item->monto_individual_factura,
                    'serie_folio' => $item->serie_folio ?? 'S/F',
                    'uuid' => $item->uuid ?? 'SIN COMPROBANTE DIGITAL'
                ];
            })->values()->all(); 
            return [
                'id'                  => $cabecera->id,
                'id_presupuesto'      => $cabecera->id_presupuesto,
                'folio_conciliacion'  => $cabecera->folio_conciliacion,
                'monto_presupuesto'   => $cabecera->monto_presupuesto,
                'monto_factura'       => $cabecera->monto_factura,
                'diferencia'          => $cabecera->diferencia,
                'fecha_presupuesto'   => $cabecera->fecha_presupuesto,
                'fecha_conciliado'    => $cabecera->fecha_conciliado,
                'comentarios'         => $cabecera->comentarios,
                'detalles_facturas'   => $detalles,
                'folio_presupuesto'     => $cabecera->folio_presupuesto,
                'nombre_sucursal'      => $cabecera->nombre_sucursal
                
            ];
        })->values(); 

           //scar los faltante
        $lineasAuditoria = DB::table('cotizaciones')
            ->join('sucursales', 'cotizaciones.sucursal_id', '=', 'sucursales.id')
            
            ->join('cotizaciones_facturadas', function($join) {
                $join->on(DB::raw('cotizaciones.presupuesto COLLATE utf8mb4_unicode_ci'), '=', 'cotizaciones_facturadas.presupuesto');
            })->select(
            'cotizaciones.id as cotizacion_id',
            'cotizaciones.presupuesto as presupuesto_folio',
            'cotizaciones.fecha_cotizacion',
            'cotizaciones.proveedor',
            'cotizaciones.total_cotizacion',
            'cotizaciones.sucursal_id',
            'cotizaciones.folio_autorizado',
            'cotizaciones.emision_completa as emision_completa_cotizaciones',
            'sucursales.nombre_real as nombre_sucursal',
            'cotizaciones_facturadas.id as factura_id',
            'cotizaciones_facturadas.fecha_timbrado',
            'cotizaciones_facturadas.uuid',
            'cotizaciones_facturadas.serie_folio',
            'cotizaciones_facturadas.subtotal',
            'cotizaciones_facturadas.descuento',
            'cotizaciones_facturadas.neto',
            'cotizaciones_facturadas.iva',
            'cotizaciones_facturadas.retencion',
            'cotizaciones_facturadas.total as total_factura',
            'cotizaciones_facturadas.emision_completa' 
        )
        ->orderBy('cotizaciones.presupuesto', 'asc')
        ->orderBy('cotizaciones_facturadas.fecha_timbrado', 'asc')
        ->where('cotizaciones.emision_completa', 0)
        ->get();

      
        $presupuestosAgrupados = $lineasAuditoria->groupBy('presupuesto_folio');

        return response()->json(['conciliaciones' => $conciliaciones, 'presupuestos'=>$presupuestosAgrupados]);
    }

    public function obtenerDictamenInteligente(Request $request){
        $idConciliacion = $request->query('id_conciliacion');
        $idsDetallesRaw = $request->query('ids_detalles');
        $idPresupuesto = $request->query('id_presupuesto');

        if (!$idConciliacion || !$idsDetallesRaw) {
            return response()->json(['error' => 'Parámetros insuficientes para procesar la auditoría.'], 400);
        }

        $idsDetalles = explode(',', $idsDetallesRaw);

        // Recuperar la cabecera de la conciliación y su id_presupuesto
        $conciliacion = DB::table('cotizaciones_conciliadas')
            ->where('id', $idConciliacion)
            ->first();

        if (!$conciliacion) {
            return response()->json(['error' => 'No se encontró la conciliación principal.'], 404);
        }

        // Obtener los productos cotizados originalmente (Base de comparación)
        $productosCotizados = DB::table('cotizacion_productos')
            ->where('cotizacion_id', $conciliacion->id_presupuesto)
            ->get()
            ->keyBy('codigo');
        //dd($productosCotizados);    
        $relacionesFacturas = DB::table('detalle_conciliacion_facturas')
            ->whereIn('id', $idsDetalles)
            ->pluck('id_cotizacion_facturada') 
            ->toArray();

        if (empty($relacionesFacturas)) {
            return response()->json(['error' => 'No se encontraron facturas vinculadas a los detalles provistos.'], 404);
        }

        $cabecerasFacturas = DB::table('cotizaciones_facturadas')
            ->whereIn('id', $relacionesFacturas)
            ->get()
            ->keyBy('id');

        $productosFacturados = DB::table('cotizaciones_productos_facturados')
            ->whereIn('id_facturada', $relacionesFacturas)
            ->get();
        //recuperamos la sucursal de la cotizacion y el numero de presupuesto
        $sucursalCotizacion = DB::table('cotizaciones as c')->join('sucursales as s', 'c.sucursal_id', '=', 's.id')
            ->where('c.id', $conciliacion->id_presupuesto)
            ->select('s.nombre_real as nombre_sucursal', 'c.presupuesto as folio_presupuesto')
            ->first();

        $facturasConDictamen = [];

        foreach ($productosFacturados as $prodFact) {
            $idFactura = $prodFact->id_facturada; 
            $codigo = $prodFact->codigo;
            $prodCotizado = $productosCotizados->get($codigo);

            $cantCotizada = 0;
            $precioAcordado = 0;
            $dictamenText = 'CORRECTO';
            $desviacionMonetaria = 0;

            if (!$prodCotizado) {
                $dictamenText = 'PRODUCTO NO COTIZADO';
                $desviacionMonetaria = floatval($prodFact->impor_con_des);
            } else {
                $cantCotizada = floatval($prodCotizado->cant_a_pedir);
             
                //$precioAcordado = floatval($prodCotizado->importe); 
                $precioAcordado = floatval($prodCotizado->importe) / intval($cantCotizada);
                $cantFacturada = floatval($prodFact->cant_facturada);
                $precioFacturado = floatval($prodFact->impor_con_des);

                if ($cantFacturada > $cantCotizada) {
                    $dictamenText = 'PIEZAS DE MAS';
                } elseif ($cantFacturada < $cantCotizada) {
                    $dictamenText = 'PIEZAS DE MENOS';
                } elseif (abs($precioFacturado - ($cantFacturada * $precioAcordado)) > 100) { 
                    $dictamenText = 'PRECIO ALTERADO';
                } else {
                    $dictamenText = 'CORRECTO';
                }

                $desviacionMonetaria = floatval($prodFact->impor_con_des) - (floatval($cantFacturada) * $precioAcordado);
            }

            $prodFact->cant_cotizada = $cantCotizada;
            $prodFact->precio_cotizado = $precioAcordado;
            $prodFact->dictamen_tecnico = $dictamenText;
            $prodFact->desviacion_monetaria = $desviacionMonetaria;

            if (!isset($facturasConDictamen[$idFactura])) {
                $facturaInfo = $cabecerasFacturas->get($idFactura);
                $facturasConDictamen[$idFactura] = [
                    'folio_factura' => $facturaInfo->uuid ?? $facturaInfo->presupuesto ?? 'S/F',
                    'fecha_factura' => $facturaInfo->fecha_timbrado ?? '',
                    'serie_folio' => $facturaInfo->serie_folio ?? '',
                    'productos' => []
                ];
            }

            $facturasConDictamen[$idFactura]['productos'][] = $prodFact;
        }

        return response()->json([
            'conciliacion' => $conciliacion,
            'facturas' => array_values($facturasConDictamen),
            'sucursal' => $sucursalCotizacion->nombre_sucursal ?? 'DESCONOCIDA',
            'folio_presupuesto' => $sucursalCotizacion->folio_presupuesto ?? 'S/F'
        ]);
    }


    public function reportePagos(Request $request, $fecha_i, $fecha_f){
        if (!$fecha_i || !$fecha_f) {
            return response()->json(['error' => 'Rango de fechas requerido'], 400);
        }

        // Consultar facturas dentro del rango de fecha_timbrado
        $facturas = DB::table('cotizaciones_facturadas as cf')
            ->leftJoin('cotizaciones as c', function($join) {
                $join->on(DB::raw('c.presupuesto COLLATE utf8mb4_unicode_ci'), '=', 'cf.presupuesto');
            })
            ->leftJoin('sucursales as s', 'c.sucursal_id', '=', 's.id')
            ->select(
                'cf.id as factura_id',
                'cf.presupuesto',
                'cf.rfc',
                'cf.nombre as proveedor_factura',
                'cf.fecha_timbrado',
                'cf.uuid',
                'cf.serie_folio',
                'cf.subtotal',
                'cf.descuento',
                'cf.neto',
                'cf.iva',
                'cf.retencion',
                'cf.total as total_factura',
                'cf.emision_completa',
                'cf.usuario_analizador',
                'c.id as cotizacion_id',
                'c.fecha_cotizacion',
                'c.total_cotizacion',
                'c.sucursal_id',
                DB::raw("COALESCE(s.nombre_real, 'SIN SUCURSAL') as nombre_sucursal")
            )
            ->whereBetween(DB::raw('DATE(cf.fecha_timbrado)'), [$fecha_i, $fecha_f])
            ->orderBy('s.nombre_real', 'asc')
            ->orderBy('cf.presupuesto', 'asc')
            ->orderBy('cf.fecha_timbrado', 'asc')
            ->get();

        // Procesar y clasificar los datos
        $facturasClasificadas = $facturas->map(function ($item) use ($fecha_i) {
            $mesTimbrado = Carbon::parse($item->fecha_timbrado)->format('Y-m');
            $mesCotizacion = $item->fecha_cotizacion ? Carbon::parse($item->fecha_cotizacion)->format('Y-m') : null;

            // Determinar si la cotización es del mes actual o rezagada de meses anteriores
            $esRezagado = ($mesCotizacion && $mesCotizacion < $mesTimbrado);

            $item->origen_presupuesto = $esRezagado ? 'REZAGADO / MES ANTERIOR' : 'MES ACTUAL';
            return $item;
        });

        //dd($facturas);

        // Agrupar por Sucursal y luego por Presupuesto
        $agrupadoPorSucursal = $facturasClasificadas->groupBy('nombre_sucursal')->map(function ($itemsSucursal) {
            return [
                'total_sucursal' => $itemsSucursal->sum('total_factura'),
                'total_rezagado' => $itemsSucursal->where('origen_presupuesto', 'REZAGADO / MES ANTERIOR')->sum('total_factura'),
                'total_mes_actual' => $itemsSucursal->where('origen_presupuesto', 'MES ACTUAL')->sum('total_factura'),
                'presupuestos' => $itemsSucursal->groupBy('presupuesto')
            ];
        });

        // Totales Generales
        $grandTotal = $facturasClasificadas->sum('total_factura');
        $totalRezagado = $facturasClasificadas->where('origen_presupuesto', 'REZAGADO / MES ANTERIOR')->sum('total_factura');
        $totalMesActual = $facturasClasificadas->where('origen_presupuesto', 'MES ACTUAL')->sum('total_factura');

        $user = Auth::user();

        return response()->json([
            'success' => true,
            'rango' => ['inicio' => $fecha_i, 'fin' => $fecha_f],
            'resumen' => [
                'monto_total_pagar' => $grandTotal,
                'monto_mes_actual' => $totalMesActual,
                'monto_rezagado' => $totalRezagado,
                'total_facturas' => $facturasClasificadas->count()
            ],
            'data' => $agrupadoPorSucursal,
            'usuario'=>$user,
        ]);
    }


}