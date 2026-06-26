<?php

namespace App\Http\Controllers;

use App\Models\Complaint\Pqr;
use App\Models\Contract\Contract;
use App\Models\Ficha\Ficha;
use App\Models\Infraestructura\Infraestructura;
use App\Models\Instructor\Instructor;
use App\Models\Traslado\NeedTransfer;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        // Conteos y presupuestos — cacheados 5 minutos
        $stats = Cache::remember('dashboard_stats', 300, function () {
            $totalInfra     = Infraestructura::count();
            $totalTraslados = NeedTransfer::count();

            return [
                'totalContratos'          => Contract::count(),
                'totalUsuarios'           => User::count(),
                'totalNecesidades'        => $totalInfra + $totalTraslados,
                'totalPqr'                => Pqr::count(),
                'totalFichasActivas'      => Ficha::where('estado', 'en_formacion')->count(),
                'totalInstructoresActivos'=> Instructor::where('activo', true)->count(),
                'presupuestoSolicitado'   => Infraestructura::sum('presupuesto_solicitado')
                                            + NeedTransfer::sum('presupuesto_solicitado'),
                'presupuestoAceptado'     => Infraestructura::sum('presupuesto_aceptado')
                                            + NeedTransfer::sum('presupuesto_aceptado'),
                'contratosPorEstado' => [
                    'activos'    => Contract::active()->count(),
                    'vencidos'   => Contract::expired()->count(),
                    'pendientes' => Contract::pending()->count(),
                ],
                'pqrPorEstado' => [
                    'en_tiempo'  => Pqr::where('state', 0)->whereRaw("is_tutela = 0 AND DATEDIFF(NOW(), date) <= 4")->count()
                                  + Pqr::where('state', 0)->whereRaw("is_tutela = 1 AND TIMESTAMPDIFF(HOUR, date, NOW()) < 24")->count(),
                    'por_vencer' => Pqr::where('state', 0)->whereRaw("is_tutela = 0 AND DATEDIFF(NOW(), date) BETWEEN 5 AND 8")->count()
                                  + Pqr::where('state', 0)->whereRaw("is_tutela = 1 AND TIMESTAMPDIFF(HOUR, date, NOW()) BETWEEN 24 AND 48")->count(),
                    'urgente'    => Pqr::where('state', 0)->whereRaw("is_tutela = 0 AND DATEDIFF(NOW(), date) = 9")->count()
                                  + Pqr::where('state', 0)->whereRaw("is_tutela = 1 AND TIMESTAMPDIFF(HOUR, date, NOW()) BETWEEN 48 AND 72")->count(),
                    'vencido'    => Pqr::where('state', 0)->whereRaw("is_tutela = 0 AND DATEDIFF(NOW(), date) >= 10")->count()
                                  + Pqr::where('state', 0)->whereRaw("is_tutela = 1 AND TIMESTAMPDIFF(HOUR, date, NOW()) >= 72")->count(),
                    'finalizada' => Pqr::where('state', 1)->count(),
                ],
            ];
        });

        $balance = $stats['presupuestoAceptado'] - $stats['presupuestoSolicitado'];

        // Gráfica presupuesto por mes — cacheada 15 minutos
        $presupuestoPorMes = Cache::remember('dashboard_presupuesto_mes', 900, function () {
            $meses = collect(range(5, 0))->map(fn($i) => Carbon::now()->subMonths($i));
            return $meses->map(fn($mes) => [
                'label'      => $mes->translatedFormat('M Y'),
                'solicitado' => Infraestructura::whereYear('created_at', $mes->year)->whereMonth('created_at', $mes->month)->sum('presupuesto_solicitado')
                               + NeedTransfer::whereYear('created_at', $mes->year)->whereMonth('created_at', $mes->month)->sum('presupuesto_solicitado'),
                'aceptado'   => Infraestructura::whereYear('created_at', $mes->year)->whereMonth('created_at', $mes->month)->sum('presupuesto_aceptado')
                               + NeedTransfer::whereYear('created_at', $mes->year)->whereMonth('created_at', $mes->month)->sum('presupuesto_aceptado'),
            ]);
        });

        // Contratos próximos a vencer (≤30 días) — tiempo real
        $contratosProximos = Contract::active()
            ->whereRaw('COALESCE(extension_date, initial_end_date) <= ?', [now()->addDays(30)->toDateString()])
            ->with('sede')
            ->orderByRaw('COALESCE(extension_date, initial_end_date) ASC')
            ->limit(8)
            ->get();

        // Eventos del calendario (60 días) — tiempo real
        $limite = Carbon::now()->addDays(60)->toDateString();
        $hoy    = Carbon::now()->toDateString();

        $infraEventos = Infraestructura::whereNotNull('fecha_inicio')
            ->whereBetween('fecha_inicio', [$hoy, $limite])
            ->select('id', 'descripcion', 'fecha_inicio')
            ->get()
            ->map(fn($i) => [
                'type'  => 'infra',
                'title' => 'Infraestructura: ' . str($i->descripcion)->limit(30),
                'date'  => $i->fecha_inicio->format('Y-m-d'),
                'color' => '#2563eb',
            ]);

        $trasladoEventos = NeedTransfer::whereNotNull('fecha_inicio')
            ->whereBetween('fecha_inicio', [$hoy, $limite])
            ->select('id', 'descripcion', 'fecha_inicio')
            ->get()
            ->map(fn($t) => [
                'type'  => 'traslado',
                'title' => 'Traslado: ' . str($t->descripcion)->limit(30),
                'date'  => $t->fecha_inicio->format('Y-m-d'),
                'color' => '#16a34a',
            ]);

        $pqrEventos = Pqr::where('state', 0)
            ->whereNotNull('date')
            ->select('id', 'title', 'date', 'is_tutela', 'horas_tutela')
            ->get()
            ->map(function ($p) use ($limite) {
                $fechaLimite = $p->is_tutela
                    ? Carbon::parse($p->date)->addHours($p->horas_tutela ?? 72)
                    : Carbon::parse($p->date)->addDays(12);

                if ($fechaLimite->toDateString() > $limite) return null;

                $isExpired = now()->gt($fechaLimite);
                return [
                    'type'    => $p->is_tutela ? 'tutela' : 'pqr',
                    'title'   => ($p->is_tutela ? 'Tutela: ' : 'PQR: ') . str($p->title)->limit(30),
                    'date'    => $fechaLimite->format('Y-m-d'),
                    'color'   => $isExpired ? '#dc2626' : '#f59e0b',
                    'expired' => $isExpired,
                ];
            })
            ->filter();

        $eventosCalendario = collect()
            ->merge($infraEventos)
            ->merge($trasladoEventos)
            ->merge($pqrEventos)
            ->values();

        return view('dashboard', [
            'totalContratos'           => $stats['totalContratos'],
            'totalUsuarios'            => $stats['totalUsuarios'],
            'totalNecesidades'         => $stats['totalNecesidades'],
            'totalPqr'                 => $stats['totalPqr'],
            'totalFichasActivas'       => $stats['totalFichasActivas'],
            'totalInstructoresActivos' => $stats['totalInstructoresActivos'],
            'presupuestoSolicitado'    => $stats['presupuestoSolicitado'],
            'presupuestoAceptado'      => $stats['presupuestoAceptado'],
            'balance'                  => $balance,
            'contratosProximos'        => $contratosProximos,
            'eventosCalendario'        => $eventosCalendario,
            'presupuestoPorMes'        => $presupuestoPorMes,
            'pqrPorEstado'             => $stats['pqrPorEstado'],
            'contratosPorEstado'       => $stats['contratosPorEstado'],
        ]);
    }
}
