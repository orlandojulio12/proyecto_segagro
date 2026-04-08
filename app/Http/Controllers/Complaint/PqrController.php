<?php

namespace App\Http\Controllers\Complaint;

use App\Http\Controllers\Controller;
use App\Http\Requests\PqrRequest;
use App\Models\Complaint\pqr;
use App\Models\Dependency\DependencySubunit;
use App\Models\Dependency\DependencyUnit;
use App\Models\Pqr\ConceptoPqr;
use App\Models\Pqr\DependenciaPqr;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PqrController extends Controller
{
    public function index(Request $request)
    {
        $query = Pqr::with(['concepto.dependencia', 'user']);

        // =========================
        // 🔥 FILTRO POR DEPENDENCIA
        // =========================
        if ($request->filled('dependency')) {
            $query->whereHas('concepto', function ($q) use ($request) {
                $q->where('dependencia_id', $request->dependency);
            });
        }

        // =========================
        // 🔥 FILTRO POR ESTADO (ANTES DEL GET)
        // =========================
        if ($request->filled('status')) {

            $status = $request->status;

            $query->where(function ($q) use ($status) {

                if ($status === 'verde') {
                    $q->whereRaw("
                    CASE 
                        WHEN is_tutela = 1 THEN TIMESTAMPDIFF(HOUR, date, NOW()) <= 24
                        ELSE DATEDIFF(NOW(), date) <= 6
                    END
                ");
                }

                if ($status === 'amarillo') {
                    $q->whereRaw("
                    CASE 
                        WHEN is_tutela = 1 THEN TIMESTAMPDIFF(HOUR, date, NOW()) BETWEEN 24 AND 72
                        ELSE DATEDIFF(NOW(), date) BETWEEN 6 AND 12
                    END
                ");
                }

                if ($status === 'rojo') {
                    $q->whereRaw("
                    CASE 
                        WHEN is_tutela = 1 THEN TIMESTAMPDIFF(HOUR, date, NOW()) BETWEEN 48 AND 72
                        ELSE DATEDIFF(NOW(), date) BETWEEN 10 AND 12
                    END
                ");
                }

                if ($status === 'vencido') {
                    $q->whereRaw("
                    CASE 
                        WHEN is_tutela = 1 THEN TIMESTAMPDIFF(HOUR, date, NOW()) > 72
                        ELSE DATEDIFF(NOW(), date) > 12
                    END
                ");
                }
            });
        }

        // =========================
        // 🔥 GET FINAL (AQUÍ SÍ)
        // =========================
        $pqr = $query->get();

        // =========================
        // 🔥 ORDEN BASE
        // =========================
        $pqr = $pqr->sortBy(fn($item) => $item->state);

        // =========================
        // 🔥 ORDEN DINÁMICO
        // =========================
        $orderColor = $request->get('order_color');

        if ($orderColor == 1) {
            $pqr = $pqr->sortBy(fn($item) => $item->days_remaining);
        } else {
            $pqr = $pqr->sortByDesc('date');
        }

        // =========================
        // 🔥 DEPENDENCIAS
        // =========================
        $dependencies = \App\Models\Pqr\DependenciaPqr::select('id_dependencia', 'name')->get();

        // =========================
        // 🔥 RESPUESTA AJAX (CLAVE 🔥)
        // =========================
        if ($request->ajax()) {
            return view('Complaint.partials.cards', compact('pqr'))->render();
        }

        // =========================
        // 🔥 VIEW NORMAL
        // =========================
        return view('Complaint.index', [
            'pqr' => $pqr,
            'dependencies' => $dependencies,
            'selectedDependency' => $request->dependency,
            'selectedStatus' => $request->status,
            'orderColor' => $orderColor
        ]);
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

            // 🔥 Manejo de tutela
            if ($request->is_tutela) {

                $data['is_tutela'] = true;

                $subdireccion = DependenciaPqr::where('name', 'Subdirección')->first();

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

            // 🔥 PDF (CORRECTO Y SEGURO)
            if ($request->hasFile('pdf')) {

                $file = $request->file('pdf');

                if (!$file->isValid()) {
                    throw new \Exception('El archivo PDF es inválido');
                }

                $path = $file->store('pqrs', 'public');

                $data['pdf_path'] = $path;
            }

            Pqr::create(array_merge($data, [
                'date' => now(), // <- hora y fecha exacta
            ]));

            return redirect()
                ->route('pqr.index')
                ->with('success', 'PQR registrada exitosamente');
        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with('error', 'Error al registrar la PQR: ' . $e->getMessage());
        }
    }

    public function validarDias(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date'
        ]);

        $fecha = Carbon::parse($request->fecha);
        $diasTranscurridos = $fecha->diffInDays(Carbon::now());
        $diasRestantes = max(0, 12 - $diasTranscurridos);

        // Determinar color según días restantes
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
            'fecha_limite' => $fecha->addDays(12)->format('Y-m-d')
        ]);
    }

    public function edit($id)
    {
        $pqr = Pqr::findOrFail($id);

        // Traer dependencias con sus conceptos (igual que en create)
        $dependencias = DependenciaPqr::with('conceptos')->get();

        return view('Complaint.edit', compact('pqr', 'dependencias'));
    }

    public function update(PqrRequest $request, $id)
    {
        try {
            $pqr = Pqr::findOrFail($id);
            $data = $request->validated();

            // Si hay un nuevo archivo PDF
            if ($request->hasFile('pdf')) {
                // Eliminar el archivo anterior si existe
                if ($pqr->pdf_path) {
                    \Storage::disk('public')->delete($pqr->pdf_path);
                }
                $data['pdf_path'] = $request->file('pdf')->store('pqrs', 'public');
            }

            $pqr->update($data);

            return redirect()
                ->route('pqr.index')
                ->with('success', 'PQR actualizada exitosamente');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar la PQR: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $pqr = Pqr::findOrFail($id);

            // Eliminar el archivo PDF si existe
            if ($pqr->pdf_path) {
                \Storage::disk('public')->delete($pqr->pdf_path);
            }

            $pqr->delete();

            return redirect()
                ->route('pqr.index')
                ->with('success', 'PQR eliminada exitosamente');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al eliminar la PQR: ' . $e->getMessage());
        }
    }

    public function toggleState(Pqr $pqr)
    {
        try {

            // 🔥 Si está activo → finalizar
            if ($pqr->state == 0) {
                $pqr->state = 1; // FINALIZADO
            } else {
                $pqr->state = 0; // REACTIVAR (opcional)
            }

            $pqr->save();

            return response()->json([
                'success' => true,
                'state' => $pqr->state,
                'message' => $pqr->state
                    ? 'PQR finalizada correctamente'
                    : 'PQR reactivada'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar'
            ], 500);
        }
    }
}
