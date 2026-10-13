<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ListaPrecioController extends Controller
{
    //vista principal
    public function index(){
        return view('lista_precios.index');
    }
}
