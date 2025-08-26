<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
   
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
/*  public function index()
{
    return view('dashboard'); // o el nombre de la vista que usas como página principal
}*/

  public function index()
    {
        return view('inicio');  // El nombre debe coincidir con el archivo en resources/views/inicio.blade.php
    }



}
