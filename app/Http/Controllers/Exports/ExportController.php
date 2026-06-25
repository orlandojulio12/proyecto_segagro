<?php

namespace App\Http\Controllers\Exports;

use App\Exports\ContractsExport;
use App\Exports\NeedTransferExport;
use App\Exports\PqrExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function contratos(Request $request)
    {
        $filename = 'contratos_' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(
            new ContractsExport($request->status, $request->sede_id),
            $filename
        );
    }

    public function pqr(Request $request)
    {
        $filename = 'pqr_' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(
            new PqrExport($request->status, $request->dependency_id),
            $filename
        );
    }

    public function traslados(Request $request)
    {
        $filename = 'traslados_' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(
            new NeedTransferExport($request->status),
            $filename
        );
    }
}
