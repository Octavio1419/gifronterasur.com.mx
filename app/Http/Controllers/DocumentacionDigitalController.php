<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentacionDigitalController extends Controller
{
    //vista documentacion digital panel de control
    public function vistaDocumentacion(){

        return view('choferes.documentacion.documentacion_digital');
    }

    //vista para cargar documentos
    public function vistaCargaDocumentos(){

        return view('choferes.documentacion.cargar_documentos');
    }

     public function subirDocumentos(Request $request, $id)
    {
        // Validación de archivos
        $request->validate([
            'vehiculo_id' => 'required',
            'documentos' => 'required',
            'documentos.*' => 'mimes:pdf|max:80480', 
        ]);

        try {
            DB::beginTransaction();

            $files = $request->file('documentos');
            $vehiculoId = $request->vehiculo_id;
            $id_usuario = $id;
           
            $count = 0;

            //dd($usuarioId);

            foreach ($files as $file) {
                $nombreOriginal = $file->getClientOriginalName();
                $peso = number_format($file->getSize() / 1048576, 2) . ' MB';
                
               
                $nombreArchivo = 'doc_' . time() . '_' . uniqid() . '.pdf';
                
                $rutaDestino = 'expedientes/vehiculo_' . $vehiculoId;
                //$file->storeAs('public/' . $rutaDestino, $nombreArchivo);
                $file->storeAs($rutaDestino, $nombreArchivo, 'public');

                // Crear registro en DB 
                DB::table('documentacion_vehicular')->insert([
                    'vehiculo_id' => $vehiculoId,
                    'nombre_original' => $nombreOriginal,
                    'nombre_archivo' => $nombreArchivo,
                    'ruta_archivo' => $rutaDestino . '/' . $nombreArchivo,
                    'peso_archivo' => $peso,
                    'subido_por_id' => $id_usuario,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $count++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Se han cargado {$count} documentos correctamente.",
                'count' => $count
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error en el servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    //recuperar documentos dfe verhiculo por chofer logueado
    public function recuperarDocumentos(){
        $chofer = Auth::guard('chofer')->user();
    
        $id_vehiculo = DB::table('choferes')
            ->where('id', $chofer->id)
            ->value('vehiculo_id');
        //sacando los datos del vehiculo
        $documentos = DB::table('documentacion_vehicular as dv')->join('usuarios as u', 'dv.subido_por_id', '=', 'u.id')->select('dv.*', 'u.nombre as nombre_usuario', 'u.apellido_p as apellido_usuario_pa')->where('dv.vehiculo_id', $id_vehiculo)->get();
        return response()->json(['documentos' => $documentos]);
    }

    //vista para que elñ admin mire todo los docvumentos
    public function vistaDocumentacionVisualizar(){

        return view('choferes.documentacion.visualizardocumentacion');
    }


    //funcion para recuperar todos los documentos 
    public function recuperaAllDocumentos(){

        $documentos = DB::table('documentacion_vehicular as dv')->join('usuarios as u', 'dv.subido_por_id', '=', 'u.id')->join('vehiculos_panel as vp', 'dv.vehiculo_id', '=', 'vp.id')->select('dv.*', 'u.nombre as nombre_usuario', 'u.apellido_p as apellido_usuario_pa', 'vp.marca','vp.linea', 'vp.modelo')->get();

        return response()->json(['documentacion' => $documentos]);
    
    }
}
