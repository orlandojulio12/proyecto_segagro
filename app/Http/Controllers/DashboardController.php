<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Contract\Contract;
use App\Models\Infraestructura\Infraestructura;
use App\Models\Traslado\NeedTransfer;
use App\Models\Complaint\Pqr;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        /* ================= CARDS ================= */
        $totalContratos = Contract::count();
        $totalUsuarios  = User::count();

        $totalInfra     = Infraestructura::count();
        $totalTraslados = NeedTransfer::count();

        // 🔹 Combinado
        $totalNecesidades = $totalInfra + $totalTraslados;

        // 🔹 PQR
        $totalPqr = Pqr::count();

        /* ================= PRESUPUESTO ================= */
        $presupuestoSolicitado =
            Infraestructura::sum('presupuesto_solicitado')
            + NeedTransfer::sum('presupuesto_solicitado');

        $presupuestoAceptado =
            Infraestructura::sum('presupuesto_aceptado')
            + NeedTransfer::sum('presupuesto_aceptado');

        $balance = $presupuestoAceptado - $presupuestoSolicitado;

        /* ================= EVENTOS ================= */
        $infraEventos = collect(Infraestructura::get()->map(fn($i) => [
            'type'  => 'infra',
            'title' => 'Infraestructura: ' . str($i->descripcion)->limit(30),
            'date'  => $i->fecha_inicio->format('Y-m-d'),
            'color' => '#2563eb'
        ]));

        $trasladoEventos = collect(NeedTransfer::get()->map(fn($t) => [
            'type'  => 'traslado',
            'title' => 'Traslado: ' . str($t->descripcion)->limit(30),
            'date'  => $t->fecha_inicio->format('Y-m-d'),
            'color' => '#16a34a'
        ]));

        // 🔹 PQR: la fecha es 12 días después de la creación
        $pqrEventos = collect(Pqr::get()->map(fn($p) => [
            'type'  => 'pqr',
            'title' => 'PQR: ' . str($p->title)->limit(30),
            'date'  => Carbon::parse($p->created_at)->addDays(12)->format('Y-m-d'),
            'color' => $p->is_expired ? '#dc2626' : '#f59e0b'
        ]));

        $eventosCalendario = $infraEventos
            ->merge($trasladoEventos)
            ->merge($pqrEventos)
            ->values();

        return view('dashboard', compact(
            'totalContratos',
            'totalUsuarios',
            'totalNecesidades',
            'totalPqr',
            'presupuestoSolicitado',
            'presupuestoAceptado',
            'balance',
            'eventosCalendario'
        ));
    }
}