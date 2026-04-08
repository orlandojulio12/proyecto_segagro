<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Contract\Contract;
use App\Models\Infraestructura\Infraestructura;
use App\Models\Traslado\NeedTransfer;
use App\Models\Complaint\pqr;
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
        $totalPqr = pqr::count() ?? 0;

        /* ================= PRESUPUESTO ================= */
        $presupuestoSolicitado =
            (Infraestructura::sum('presupuesto_solicitado') ?? 0)
            + (NeedTransfer::sum('presupuesto_solicitado') ?? 0);

        $presupuestoAceptado =
            (Infraestructura::sum('presupuesto_aceptado') ?? 0)
            + (NeedTransfer::sum('presupuesto_aceptado') ?? 0);

        $balance = $presupuestoAceptado - $presupuestoSolicitado;

        /* ================= EVENTOS ================= */

        $infraEventos = Infraestructura::get()->map(fn($i) => [
            'type'  => 'infra',
            'title' => 'Infraestructura: ' . str($i->descripcion)->limit(30),
            'date'  => optional($i->fecha_inicio)->format('Y-m-d'),
            'color' => '#2563eb',
        ])->filter(fn($e) => $e['date']);

        $trasladoEventos = NeedTransfer::get()->map(fn($t) => [
            'type'  => 'traslado',
            'title' => 'Traslado: ' . str($t->descripcion)->limit(30),
            'date'  => optional($t->fecha_inicio)->format('Y-m-d'),
            'color' => '#16a34a',
        ])->filter(fn($e) => $e['date']);

        // 🔴 PQR / Tutela
        $pqrEventos = Pqr::get()->map(function ($p) {
            if ($p->is_tutela && $p->horas_tutela) {
                $fechaInicio = Carbon::parse($p->date); // fecha inicial de la tutela
                $fechaLimite = (clone $fechaInicio)->addHours($p->horas_tutela);

                $label = 'Tutela: ' . \Str::limit($p->title, 30);
                $isExpired = now()->gt($fechaLimite);

                return [
                    'type'       => 'tutela',
                    'title'      => $label,
                    'date'       => $fechaLimite->format('Y-m-d'), // se usa la fecha límite para ubicar en el calendario
                    'hora_inicio' => $fechaInicio->format('H:i'),
                    'hora_fin'   => $fechaLimite->format('H:i'),
                    'color'      => $isExpired ? '#dc2626' : '#f97316',
                    'expired'    => $isExpired,
                ];
            } else {
                $fechaLimite = Carbon::parse($p->date)->addDays(12);
                $label = 'PQR: ' . \Str::limit($p->title, 30);
                $isExpired = now()->gt($fechaLimite);

                return [
                    'type'    => 'pqr',
                    'title'   => $label,
                    'date'    => $fechaLimite->format('Y-m-d'),
                    'color'   => $isExpired ? '#dc2626' : '#f59e0b',
                    'expired' => $isExpired,
                ];
            }
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
