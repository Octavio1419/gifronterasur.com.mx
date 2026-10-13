<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class BitacoraController extends Controller
{

    public function index($tipo) {
        $chofer = Auth::guard('chofer')->user();
            // Verificar el último registro del día para determinar si es check-in o check-out
            $tipo = $tipo == 1 ? 'inicio' : 'fin'; // 1 para check-in, 0 para check-out
        $vehiculo = DB::table('vehiculos_panel')->where('id', $chofer->vehiculo_id)->first();

        return view('choferes.bitacora.checkin', compact('vehiculo', 'tipo'));
    }

    public function storeCheckIn(Request $request, $tipoId) {
        // 1. Recuperamos al chofer logueado
        $chofer = Auth::guard('chofer')->user();

        $labelRegistro = ($tipoId == 1) ? 'inicio' : 'fin';
        //dd($labelRegistro);

        $validator = Validator::make($request->all(), [
            'km_lectura' => 'required|numeric',
            'foto_blob'  => 'required',       
            'notas'      => 'nullable|string|max:255', 
            'vehiculo_id'=> 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // PROCESAR LA IMAGEN (Base64 a archivo físico)
            $fotoPath = null;
            if ($request->foto_blob) {
                $img = $request->foto_blob;
                $img = str_replace('data:image/jpeg;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                
                $fileName = 'odo_' . time() . '.jpg';
           
                $folderPath = base_path('public_html/uploads/odometros/' . date('Y-m-d'));
                
                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0777, true);
                }

                file_put_contents($folderPath . '/' . $fileName, $data);
                $fotoPath = 'uploads/odometros/' . date('Y-m-d') . '/' . $fileName;
            }

            //  INSERTAR EN BITÁCORA
            DB::table('bitacora_combustible_km')->insert([
                'chofer_id'     => $chofer->id,
                'vehiculo_id'   => $request->vehiculo_id,
                'km_lectura'    => $request->km_lectura,
                'foto_odometro' => $fotoPath,
                'notas_estado'         => $request->notas,
                'tipo_registro' => $labelRegistro, 
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            // ACTUALIZAR EL KM ACTUAL DEL VEHÍCULO (Para las alertas de servicio)
            DB::table('vehiculos_panel')
                ->where('id', $request->vehiculo_id)
                ->update(['km_actual' => $request->km_lectura]);

            DB::commit();
            return response()->json(['message' => 'Check-in registrado exitosamente.'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error en servidor: ' . $e->getMessage()], 500);
        }
    }
}
