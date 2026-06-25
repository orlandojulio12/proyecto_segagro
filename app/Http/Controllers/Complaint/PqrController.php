<?php

namespace App\Http\Controllers\Complaint;

use App\Http\Controllers\Controller;
use App\Http\Requests\PqrRequest;
use App\Models\Complaint\pqr;
use App\Models\Pqr\ConceptoPqr;
use App\Models\Pqr\DependenciaPqr;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PqrController extends Controller
{
    public function index(Request $request)
    {
        $query = Pqr::query()->with(['concepto.dependencia', 'user']);

        if ($request->filled('dependency')) {
            $query->whereHas('concepto', function ($q) use ($request) {
                $q->where('dependencia_id', $request->dependency);
            });
        }

        if ($request->filled('status')) {
            $status = $request->status;

            $query->where(function ($q) use ($status) {
                if ($status === 'verde') {
                    $q->whereRaw("
                        (is_tutela = 1 AND TIMESTAMPDIFF(HOUR, date, NOW()) < 24)
                        OR (is_tutela = 0 AND DATEDIFF(NOW(), date) <= 4)
                    ");
                }
                if ($status === 'amarillo') {
                    $q->whereRaw("
                        (is_tutela = 1 AND TIMESTAMPDIFF(HOUR, date, NOW()) BETWEEN 24 AND 48)
                        OR (is_tutela = 0 AND DATEDIFF(NOW(), date) BETWEEN 5 AND 8)
                    ");
                }
                if ($status === 'rojo') {
                    $q->whereRaw("
                        (is_tutela = 1 AND TIMESTAMPDIFF(HOUR, date, NOW()) BETWEEN 48 AND 72)
                        OR (is_tutela = 0 AND DATEDIFF(NOW(), date) = 9)
                    ");
                }
                if ($status === 'vencido') {
                    $q->whereRaw("
                        (is_tutela = 1 AND TIMESTAMPDIFF(HOUR, date, NOW()) >= 72)
                        OR (is_tutela = 0 AND DATEDIFF(NOW(), date) >= 10)
                    ");
                }
            });
        }

        $orderColor = $request->get('order_color');

        if ($orderColor == 1) {
            $query->orderByRaw("
                CASE
                    WHEN is_tutela = 1
                        THEN (COALESCE(horas_tutela, 72) - TIMESTAMPDIFF(HOUR, date, NOW()))
                    ELSE (10 - DATEDIFF(NOW(), date))
                END ASC
            ");
        } else {
            $query->orderByDesc('date');
        }

        $perPage = $request->filled('table') ? 10 : 8;
        $pqr = $query->paginate($perPage)->withQueryString();
        $dependencies = DependenciaPqr::select('id_dependencia', 'name')->get();

        if ($request->ajax()) {
            if ($request->filled('table')) {
                return view('Complaint.partials.table', compact('pqr'))->render();
            }
            return view('Complaint.partials.cards', compact('pqr'))->render();
        }

        return view('Complaint.index', compact('pqr', 'dependencies', 'orderColor'));
    }

    public function create()
    {
        $dependencias = DependenciaPqr::with('conceptos')->get();
        return view('Complaint.create', compact('dependencias'));
    }

    public function store(PqrRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->id();

            if ($request->is_tutela) {
                $data['is_tutela'] = true;

                $subdireccion = DependenciaPqr::where('name', 'Subdirección')->firstOrFail();

                $concepto = ConceptoPqr::where('id_concepto', $request->concepto_id)
                    ->where('dependencia_id', $subdireccion->id_dependencia)
                    ->first();

                if (!$concepto) {
                    throw new \Exception('Concepto inválido para tutela');
                }

                $data['concepto_id'] = $concepto->id_concepto;
            } else {
                $data['is_tutela'] = false;
            }

            if ($request->hasFile('pdf')) {
                $file = $request->file('pdf');
                if (!$file->isValid()) {
                    throw new \Exception('El archivo PDF es inválido');
                }
                $data['pdf_path'] = $file->store('pqrs', 'public');
            }

            $data['date'] = $request->filled('date')
                ? Carbon::createFromFormat('Y-m-d\TH:i', $request->date)->format('Y-m-d H:i:s')
                : now()->format('Y-m-d H:i:s');

            Pqr::create($data);

            return redirect()->route('pqr.index')->with('success', 'PQR registrada exitosamente');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al registrar la PQR: ' . $e->getMessage());
        }
    }

    public function validarDias(Request $request)
    {
        $request->validate(['fecha' => 'required|date']);

        $fecha = Carbon::parse($request->fecha);
        $diasTranscurridos = $fecha->diffInDays(Carbon::now());
        $diasRestantes = max(0, 10 - $diasTranscurridos);

        $color = match (true) {
            $diasRestantes >= 6 => 'green',
            $diasRestantes >= 2 => 'yellow',
            $diasRestantes >= 1 => 'red',
            default => 'alert',
        };

        $estado = match (true) {
            $diasRestantes >= 6 => 'En tiempo',
            $diasRestantes >= 2 => 'Por vencer',
            $diasRestantes >= 1 => 'Urgente',
            default => 'Vencido',
        };

        return response()->json([
            'dias_transcurridos' => $diasTranscurridos,
            'dias_restantes' => $diasRestantes,
            'color' => $color,
            'estado' => $estado,
            'fecha_limite' => $fecha->addDays(10)->format('Y-m-d'),
        ]);
    }

    public function edit($id)
    {
        $pqr = Pqr::findOrFail($id);
        $dependencias = DependenciaPqr::with('conceptos')->get();
        return view('Complaint.edit', compact('pqr', 'dependencias'));
    }

    public function update(PqrRequest $request, $id)
    {
        try {
            $pqr = Pqr::findOrFail($id);
            $data = $request->validated();

            if ($request->hasFile('pdf')) {
                if ($pqr->pdf_path && \Storage::disk('public')->exists($pqr->pdf_path)) {
                    \Storage::disk('public')->delete($pqr->pdf_path);
                }
                $data['pdf_path'] = $request->file('pdf')->store('pqrs', 'public');
            }

            $pqr->update($data);

            return redirect()->route('pqr.index')->with('success', 'PQR actualizada exitosamente');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al actualizar la PQR: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $pqr = Pqr::findOrFail($id);

            if ($pqr->pdf_path && \Storage::disk('public')->exists($pqr->pdf_path)) {
                \Storage::disk('public')->delete($pqr->pdf_path);
            }

            $pqr->delete();

            return redirect()->route('pqr.index')->with('success', 'PQR eliminada exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la PQR: ' . $e->getMessage());
        }
    }

    public function toggleState(Pqr $pqr)
    {
        try {
            $pqr->state = !$pqr->state;
            $pqr->save();

            return response()->json([
                'success' => true,
                'state' => $pqr->state,
                'message' => $pqr->state ? 'PQR finalizada correctamente' : 'PQR reactivada',
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al actualizar'], 500);
        }
    }
}
