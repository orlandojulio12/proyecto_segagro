<?php

namespace App\Http\Controllers\Infraestructura;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InfraestructuraController extends Controller
{
     public function index()
    {
        return view('infraestructura.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Estas variables deberían venir de tus modelos
        $users = \App\Models\User::all();
        $centros = \App\Models\Centro::all();
        
        return view('infraestructura.create', compact('users', 'centros'));
    }
}
