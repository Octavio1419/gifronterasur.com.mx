<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InicioController extends Controller
{
    /*FUNCION PARA VISTA PRINCIPAL  */
    public function vistageneral(){

        return view('vista_principal.index');
    }

    public function vistapanel(){

        return view('vista_principal.panel');
    }
}
