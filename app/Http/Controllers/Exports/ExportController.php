<?php

namespace App\Http\Controllers\Exports;

use App\Exports\ContractsExport;
use App\Exports\FichasExport;
use App\Exports\InfraestructuraExport;
use App\Exports\InstructoresExport;
use App\Exports\NeedTransferExport;
use App\Exports\PqrExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function contratos(Request $request)
    {
        return Excel::download(
            new ContractsExport($request->status, $request->sede_id),
            'contratos_' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function pqr(Request $request)
    {
        return Excel::download(
            new PqrExport($request->status, $request->dependency_id),
            'pqr_' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function traslados(Request $request)
    {
        return Excel::download(
            new NeedTransferExport($request->status),
            'traslados_' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function fichas(Request $request)
    {
        return Excel::download(
            new FichasExport($request->estado),
            'fichas_' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function instructores()
    {
        return Excel::download(
            new InstructoresExport(),
            'instructores_' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function infraestructura()
    {
        return Excel::download(
            new InfraestructuraExport(),
            'infraestructura_' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}
