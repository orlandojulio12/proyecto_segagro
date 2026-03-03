<?php

namespace App\Http\Controllers\Complaint;

use App\Http\Controllers\Controller;
use App\Http\Requests\PqrRequest;
use App\Models\Complaint\pqr;
use App\Models\Dependency\DependencySubunit;
use App\Models\Dependency\DependencyUnit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PqrController extends Controller
{
    public function index(Request $request)
    {
        $query = Pqr::query();

        // Filtro por dependencia
        if ($request->filled('dependency')) {
            $query->where('dependency', $request->dependency);
        }

        // Obtener todas las PQR
        $allPqr = $query->get();

        // Filtrar por estado de días si se envía
        if ($request->filled('status')) {
            $allPqr = $allPqr->filter(function ($item) use ($request) {
                return match ($request->status) {
                    'verde' => $item->days_remaining >= 6,
                    'amarillo' => $item->days_remaining >= 2 && $item->days_remaining < 6,
                    'rojo' => $item->days_remaining >= 1 && $item->days_remaining < 2,
                    'vencido' => $item->days_remaining === 0,
                    default => true
                };
            });
        }

        // Ordenamiento por estado (pendientes primero)
        $allPqr = $allPqr->sortBy(fn($item) => $item->state);

        // Ordenamiento adicional según modal de orden
        $orderColor = $request->get('order_color');
        if ($orderColor == 1) {
            // Orden por color/días restantes (urgente primero)
            $allPqr = $allPqr->sortBy(fn($item) => $item->days_remaining);
        } else {
            // Orden por fecha descendente por defecto
            $allPqr = $allPqr->sortByDesc('date');
        }

        // Obtener lista de dependencias únicas
        $dependencies = DependencySubunit::select('subunit_id', 'name', 'subunit_code')->get();

        return view('Complaint.index', [
            'pqr' => $allPqr,
            'dependencies' => $dependencies,
            'selectedDependency' => $request->dependency,
            'selectedStatus' => $request->status,
            'orderColor' => $orderColor
        ]);
    }

    public function create()
    {
        $units = DependencyUnit::with('subunits')->get();

        return view('Complaint.create', compact('units'));
    }

    public function store(PqrRequest $request)
    {
        try {

            $data = $request->validated();
            $data['dependency'] = $request->dependency; // subunit_id seleccionado
            $data['user_id'] = Auth::id();

            if ($request->hasFile('pdf')) {
                $data['pdf_path'] = $request->file('pdf')->store('pqrs', 'public');
            }

            Pqr::create($data);

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

        // Traer las unidades y subunidades
        $units = DependencyUnit::with('subunits')->get();

        return view('Complaint.edit', compact('pqr', 'units'));
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
            $pqr->state = !$pqr->state;
            $pqr->save();

            return back()->with('success', 'Estado de la PQR actualizado correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el estado: ' . $e->getMessage());
        }
    }
}
