<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener estadísticas del dashboard
        $totalSolicitudes = 30; // Reemplazar con lógica real
        $totalContratos = 20;
        $totalQuejas = 5;
        $nuevosUsuarios = User::whereDate('created_at', today())->count();
        
        return view('dashboard', compact(
            'totalSolicitudes', 
            'totalContratos', 
            'totalQuejas', 
            'nuevosUsuarios'
        ));
    }
}