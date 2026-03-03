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
        $totalContratos = Contract::count() ?? 0;
        $totalUsuarios  = User::count() ?? 0;

        $totalInfra     = Infraestructura::count() ?? 0;
        $totalTraslados = NeedTransfer::count() ?? 0;

        $totalNecesidades = $totalInfra + $totalTraslados;
        $totalPqr = Pqr::count() ?? 0;

        /* ================= PRESUPUESTO ================= */
        $presupuestoSolicitado =
            (Infraestructura::sum('presupuesto_solicitado') ?? 0)
            + (NeedTransfer::sum('presupuesto_solicitado') ?? 0);

        $presupuestoAceptado =
            (Infraestructura::sum('presupuesto_aceptado') ?? 0)
            + (NeedTransfer::sum('presupuesto_aceptado') ?? 0);

        $balance = $presupuestoAceptado - $presupuestoSolicitado;

        /* ================= EVENTOS ================= */

        $infraEventos = Infraestructura::get()->map(fn ($i) => [
            'type'  => 'infra',
            'title' => 'Infraestructura: ' . str($i->descripcion)->limit(30),
            'date'  => optional($i->fecha_inicio)->format('Y-m-d'),
            'color' => '#2563eb',
        ])->filter(fn ($e) => $e['date']);

        $trasladoEventos = NeedTransfer::get()->map(fn ($t) => [
            'type'  => 'traslado',
            'title' => 'Traslado: ' . str($t->descripcion)->limit(30),
            'date'  => optional($t->fecha_inicio)->format('Y-m-d'),
            'color' => '#16a34a',
        ])->filter(fn ($e) => $e['date']);

        // 🔴 PQR: vencido si hoy > created_at + 12 días
        $pqrEventos = Pqr::get()->map(function ($p) {
            $fechaLimite = Carbon::parse($p->created_at)->addDays(12);
            $isExpired   = now()->gt($fechaLimite);

            return [
                'type'      => 'pqr',
                'title'     => 'PQR: ' . str($p->title)->limit(30),
                'date'      => $fechaLimite->format('Y-m-d'),
                'color'     => $isExpired ? '#dc2626' : '#f59e0b',
                'expired'   => $isExpired,
            ];
        });

        $eventosCalendario = collect()
            ->merge($infraEventos)
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