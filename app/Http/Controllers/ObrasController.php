<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ObrasController extends Controller
{
    //vista para visualizar las obras
    public function listarObras(){

        return view('obras.index_obra');
    }
    //recuperar las obras de la base de datos
    public function recuperarObrasListadas(){
        $obras = DB::table('obras')->get();

        return response()->json(['success' => true, 'data' => $obras]);
    }
    //vista para dar de alta las obras
    public function createObra(){

        return view('obras.create_obra');
    }

    //funcion para guardar los datos de las obras
    public function guardarObra(Request $request){
        //validar los datos del formulario
        $request->validate([
            'nombre_obra' => 'required|string|max:255',
            'estado' => 'required|string|max:255',
            'municipio' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:1500',
            'iframe' => 'nullable|string|max:1500',
            'estatus' => 'boolean',
        ]);

        $guardar_obra = DB::table('obras')->insert([
            'nombre_obra' => $request->nombre_obra,
            'estado' => $request->estado,
            'municipio' => $request->municipio,
            'ubicacion' => $request->ubicacion,
            'iframe' => $request->iframe,
            'estatus' => $request->estatus,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Obra guardada correctamente']);
    }

    public function storeObra(Request $request, $id){

        //recuperando los datos para el formulario
        $recuperar_obra = DB::table('obras')->where('id', $id)->first();

        return response()->json(['success' => true, 'data' => $recuperar_obra]);
    
    }

    //actualizar datos de la obra
    public function updateObra(Request $request, $id){
        //validar los datos del formulario
        $request->validate([
            'nombre_obra' => 'required|string|max:255',
            'estado' => 'required|string|max:255',
            'municipio' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:1500',
            'iframe' => 'nullable|string|max:1500',
            'estatus' => 'boolean',
        ]);

        $actualizar_obra = DB::table('obras')->where('id', $id)->update([
            'nombre_obra' => $request->nombre_obra,
            'estado' => $request->estado,
            'municipio' => $request->municipio,
            'ubicacion' => $request->ubicacion,
            'iframe' => $request->iframe,
            'estatus' => $request->estatus,
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Obra actualizada correctamente']);
    }
}
