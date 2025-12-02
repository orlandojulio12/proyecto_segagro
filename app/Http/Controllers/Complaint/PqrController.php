<?php

namespace App\Http\Controllers\Complaint;

use App\Http\Controllers\Controller;
use App\Http\Requests\PqrRequest;
use App\Models\Complaint\pqr;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PqrController extends Controller
{
    /**
     * Muestra el listado de todas las PQR
     */
    public function index(Request $request)
    {
        $query = Pqr::query();

        // Filtro por dependencia
        if ($request->filled('dependency')) {
            $query->where('dependency', $request->dependency);
        }

        // Ordenamiento base
        $sortBy = $request->get('sort', 'date_desc');
        
        switch($sortBy) {
            case 'date_asc':
                $query->orderBy('date', 'asc');
                break;
            case 'date_desc':
            default:
                $query->orderBy('date', 'desc');
                break;
        }

        // Obtener todas las PQR
        $allPqr = $query->get();

        // Filtro por estado (basado en días restantes)
        if ($request->filled('status')) {
            $allPqr = $allPqr->filter(function($item) use ($request) {
                return match($request->status) {
                    'verde' => $item->days_remaining >= 6,
                    'amarillo' => $item->days_remaining >= 2 && $item->days_remaining < 6,
                    'rojo' => $item->days_remaining >= 1 && $item->days_remaining < 2,
                    'vencido' => $item->days_remaining === 0,
                    default => true
                };
            });
        }

        // Ordenamiento adicional por días (después del filtro)
        if (in_array($sortBy, ['days_asc', 'days_desc'])) {
            $allPqr = $allPqr->sortBy(function($item) {
                return $item->days_remaining;
            }, SORT_REGULAR, $sortBy === 'days_desc');
        }

        // Obtener lista de dependencias únicas para el filtro
        $dependencies = Pqr::select('dependency')->distinct()->pluck('dependency');

        return view('Complaint.index', [
            'pqr' => $allPqr,
            'dependencies' => $dependencies,
            'selectedDependency' => $request->dependency,
            'selectedStatus' => $request->status
        ]);
    }

    /**
     * Muestra el formulario para crear una nueva PQR
     */
    public function create()
    {
        return view('Complaint.create');
    }

    /**
     * Almacena una nueva PQR en la base de datos
     */
    public function store(PqrRequest $request)
    {
        try {
            $data = $request->validated();

            // Agregar el ID del usuario autenticado
            $data['user_id'] = Auth::id();

            // Si hay un archivo PDF, guardarlo
            if ($request->hasFile('pdf')) {
                $data['pdf_path'] = $request->file('pdf')->store('pqrs', 'public');
            }

            // Crear la PQR
            $pqr = Pqr::create($data);

            return redirect()
                ->route('pqr.index')
                ->with('success', 'PQR registrada exitosamente');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al registrar la PQR: ' . $e->getMessage());
        }
    }

    /**
     * AJAX: Valida los días transcurridos y retorna color de estado
     */
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

    /**
     * Muestra el formulario para editar una PQR
     */
    public function edit($id)
    {
        $pqr = Pqr::findOrFail($id);
        return view('Complaint.edit', compact('pqr'));
    }

    /**
     * Actualiza una PQR existente
     */
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

    /**
     * Elimina una PQR
     */
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
}