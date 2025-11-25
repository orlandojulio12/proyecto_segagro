<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Contract\Contract;
use App\Models\Infraestructura\Infraestructura;
use App\Models\Traslado\NeedTransfer;

class DashboardController extends Controller
{
    public function index()
    {
        $totalContratos = Contract::count();
        $totalSolicitudes = 30; 
        $totalQuejas = 5;
        $nuevosUsuarios = User::whereDate('created_at', today())->count();

        $infraestructuraCount = Infraestructura::count();
        $usuariosCount = User::count();
        $contratosCount = Contract::count();
        $trasladosCount = NeedTransfer::count();

        $max = max([
            $infraestructuraCount,
            $usuariosCount,
            $contratosCount,
            $trasladosCount,
            1
        ]);

        $percentInfraestructura = round(($infraestructuraCount / $max) * 100);
        $percentUsuarios        = round(($usuariosCount / $max) * 100);
        $percentContratos       = round(($contratosCount / $max) * 100);
        $percentTraslados       = round(($trasladosCount / $max) * 100);

        return view('dashboard', compact(
            'totalSolicitudes',
            'totalContratos',
            'totalQuejas',
            'nuevosUsuarios',

            'percentInfraestructura',
            'percentUsuarios',       
            'percentContratos',
            'percentTraslados',
        ));
    }
}
