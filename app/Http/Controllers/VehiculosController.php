<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehiculosController extends Controller
{
    //visualizar los vehiculos
    public function listarVehiculos(){
        return view('vehiculos.index_vehiculos');
    }

    //vista para dar de alta los vehiculos
    public function createVehiculo(){
        return view('vehiculos.create_vehiculos');
    }

    public function guardar(Request $request){
        $request->validate([
            'placas' => 'required|unique:vehiculos_panel,placas',
            'serie' => 'required|unique:vehiculos_panel,serie',
            'foto_vehiculo' => 'nullable|image|max:5120', 
        ]);

        try {
            $path = null;
            if ($request->hasFile('foto_vehiculo')) {
                // Se guarda en storage/app/public/vehiculos
                $path = $request->file('foto_vehiculo')->store('vehiculos', 'public');
            }

            DB::table('vehiculos_panel')->insert([
                'marca' => $request->marca,
                'linea' => $request->linea,
                'modelo' => $request->modelo,
                'serie' => strtoupper($request->serie),
                'placas' => strtoupper($request->placas),
                'estado_placa' => $request->estado_placa,
                'ultima_tenencia' => $request->ultima_tenencia,
                'efecticard' => $request->efecticard,
                'tarjeta_circulacion' => $request->tarjeta_circulacion,
                'estatus_seguro' => $request->estatus_seguro, 
                'compania_seguro' => $request->compania_seguro,
                'fecha_inicio_seguro' => $request->fecha_inicio_seguro,
                'fecha_fin_seguro' => $request->fecha_fin_seguro,
                'km_actual' => $request->km_actual ?? 0,
                'foto_vehiculo' => $path, 
                'propietario_id' => $request->propietario_id,
                'obra_id' => $request->obra_id,
                'estatus' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Vehículo registrado en el sistema correctamente.']);

            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
    }

    public function actualizarAsignacion(Request $request) {
        try {
            DB::beginTransaction();
            
            $vehiculoId = $request->input('vehiculo_id');
            $obraId = $request->input('obra_id');
            $choferId = $request->input('chofer_id');

            DB::table('vehiculos_panel')
                ->where('id', $vehiculoId)
                ->update(['obra_id' => $obraId ? $obraId : null]);

            DB::table('choferes')
                ->where('vehiculo_id', $vehiculoId)
                ->update(['vehiculo_id' => null]);

            if (!empty($choferId)) {
                DB::table('choferes')
                    ->where('id', $choferId)
                    ->update(['vehiculo_id' => $vehiculoId]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Unidad actualizada correctamente.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function vehiculosRegistrados(){
        $vehiculos = DB::table('vehiculos_panel')
            ->leftJoin('propietarios', 'vehiculos_panel.propietario_id', '=', 'propietarios.id')
            ->leftJoin('obras', 'vehiculos_panel.obra_id', '=', 'obras.id')
            ->leftJoin('choferes', 'choferes.vehiculo_id', '=', 'vehiculos_panel.id')
            ->select(
                'vehiculos_panel.*',
                DB::raw("CONCAT(propietarios.nombre, ' ', propietarios.apellido_p) as nombre_propietario"),
                'obras.nombre_obra as nombre_obra',
                'choferes.id as chofer_id',
                DB::raw("CONCAT(choferes.nombre, ' ', choferes.apellido_p) as nombre_chofer")
            )
            ->get();
    
        return response()->json([
            'success' => true,
            'data' => $vehiculos
        ]);
    }
    
    // ---------------------------------------------------------------
    // 2) Actualizar / editar un vehículo
    //    El chofer se resuelve aparte porque su FK vive en `choferes`.
    // ---------------------------------------------------------------
    public function actualizar(Request $request, $id)
    {
        $request->validate([
            'placas' => 'required|unique:vehiculos_panel,placas,' . $id,
            'serie'  => 'required|unique:vehiculos_panel,serie,' . $id,
        ]);
    
        try {
            DB::table('vehiculos_panel')->where('id', $id)->update([
                'marca'                => $request->marca,
                'linea'                => $request->linea,
                'modelo'               => $request->modelo,
                'serie'                => strtoupper($request->serie),
                'placas'               => strtoupper($request->placas),
                'tarjeta_circulacion'  => $request->tarjeta_circulacion,
                'km_actual'            => $request->km_actual ?? 0,
                'efecticard'           => $request->efecticard,
                'obra_id'              => $request->obra_id ?: null,
                'propietario_id'       => $request->propietario_id ?: null,
                'estatus_seguro'       => $request->estatus_seguro,
                'compania_seguro'      => $request->compania_seguro,
                'fecha_inicio_seguro'  => $request->fecha_inicio_seguro,
                'fecha_fin_seguro'     => $request->fecha_fin_seguro,
                'updated_at'           => now(),
            ]);
    
            if ($request->has('chofer_id')) {
                DB::table('choferes')->where('vehiculo_id', $id)->update(['vehiculo_id' => null]);
    
                if (!empty($request->chofer_id)) {
                    DB::table('choferes')->where('id', $request->chofer_id)->update(['vehiculo_id' => $id]);
                }
            }
    
            return response()->json(['success' => true, 'message' => 'Vehículo actualizado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function eliminar($id)
    {
        try {
            
            DB::table('choferes')->where('vehiculo_id', $id)->update(['vehiculo_id' => null]);
    
            // Baja lógica (recomendado, conserva historial):
            DB::table('vehiculos_panel')->where('id', $id)->update([
                'estatus' => false,
                'updated_at' => now(),
            ]);
    
            return response()->json(['success' => true, 'message' => 'Vehículo eliminado.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    // ---------------------------------------------------------------
    // 4) Quitar la obra asignada (FK vive en vehiculos_panel, sin cambios)
    // ---------------------------------------------------------------
    public function quitarObra($id)
    {
        try {
            DB::table('vehiculos_panel')->where('id', $id)->update([
                'obra_id' => null,
                'updated_at' => now(),
            ]);
            return response()->json(['success' => true, 'message' => 'Obra removida del vehículo.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    // ---------------------------------------------------------------
    // 5) Quitar el chofer asignado
    //    Se actualiza la fila del chofer (choferes.vehiculo_id = null),
    //    NO la del vehículo.
    // ---------------------------------------------------------------
    public function quitarChofer($id)
    {
        try {
            DB::table('choferes')->where('vehiculo_id', $id)->update(['vehiculo_id' => null]);
            return response()->json(['success' => true, 'message' => 'Chofer removido del vehículo.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
}
