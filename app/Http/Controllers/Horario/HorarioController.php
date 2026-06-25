<?php

namespace App\Http\Controllers\Horario;

use App\Http\Controllers\Controller;
use App\Models\Area\Area;
use App\Models\Area\Room;
use App\Models\Centro;
use App\Models\Ficha\Ficha;
use App\Models\Horario\Horario;
use App\Models\Instructor\Instructor;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    public function index()
    {
        $centros = Centro::orderBy('nom_centro')->get();
        $fichas       = Ficha::where('estado', '!=', 'cancelado')->orderBy('numero_ficha')->get();
        $instructores = Instructor::where('activo', true)->orderBy('apellido')->orderBy('nombre')->get();
        $areas        = Area::where('active', true)->orderBy('name')->get();

        return view('horarios.index', compact('centros', 'fichas', 'instructores', 'areas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ficha_id'     => 'required|exists:fichas,id',
            'room_id'      => 'required|exists:rooms,id',
            'dia_semana'   => 'required|in:lunes,martes,miercoles,jueves,viernes,sabado',
            'hora_inicio'  => 'required|date_format:H:i',
            'hora_fin'     => 'required|date_format:H:i|after:hora_inicio',
            'competencia'  => 'nullable|string|max:255',
            'instructor_id'=> 'nullable|exists:instructores,id',
            'color'        => 'nullable|string|max:7',
        ]);

        // Check no conflicts: same room, same day, overlapping time
        $conflict = Horario::where('room_id', $validated['room_id'])
            ->where('dia_semana', $validated['dia_semana'])
            ->where('activo', true)
            ->where(function ($q) use ($validated) {
                $q->whereBetween('hora_inicio', [$validated['hora_inicio'], $validated['hora_fin']])
                  ->orWhereBetween('hora_fin',   [$validated['hora_inicio'], $validated['hora_fin']])
                  ->orWhere(function ($q2) use ($validated) {
                      $q2->where('hora_inicio', '<=', $validated['hora_inicio'])
                         ->where('hora_fin',    '>=', $validated['hora_fin']);
                  });
            })->exists();

        if ($conflict) {
            return back()->withErrors(['conflict' => 'Ya existe un horario para este salón en ese día y rango de hora.'])->withInput();
        }

        Horario::create(array_merge($validated, [
            'color' => $validated['color'] ?? '#16a34a',
            'activo' => true,
        ]));

        return redirect()->route('horarios.index')
            ->with('success', 'Horario registrado correctamente');
    }

    public function show(Horario $horario)
    {
        $horario->load(['ficha', 'room.area', 'instructor']);
        return response()->json($horario);
    }

    public function update(Request $request, Horario $horario)
    {
        $validated = $request->validate([
            'ficha_id'     => 'required|exists:fichas,id',
            'room_id'      => 'required|exists:rooms,id',
            'dia_semana'   => 'required|in:lunes,martes,miercoles,jueves,viernes,sabado',
            'hora_inicio'  => 'required|date_format:H:i',
            'hora_fin'     => 'required|date_format:H:i|after:hora_inicio',
            'competencia'  => 'nullable|string|max:255',
            'instructor_id'=> 'nullable|exists:instructores,id',
            'color'        => 'nullable|string|max:7',
            'activo'       => 'boolean',
        ]);

        $conflict = Horario::where('room_id', $validated['room_id'])
            ->where('dia_semana', $validated['dia_semana'])
            ->where('activo', true)
            ->where('id', '!=', $horario->id)
            ->where(function ($q) use ($validated) {
                $q->whereBetween('hora_inicio', [$validated['hora_inicio'], $validated['hora_fin']])
                  ->orWhereBetween('hora_fin',   [$validated['hora_inicio'], $validated['hora_fin']])
                  ->orWhere(function ($q2) use ($validated) {
                      $q2->where('hora_inicio', '<=', $validated['hora_inicio'])
                         ->where('hora_fin',    '>=', $validated['hora_fin']);
                  });
            })->exists();

        if ($conflict) {
            return back()->withErrors(['conflict' => 'Ya existe un horario para este salón en ese día y rango de hora.'])->withInput();
        }

        $horario->update($validated);

        return redirect()->route('horarios.index')
            ->with('success', 'Horario actualizado correctamente');
    }

    public function destroy(Horario $horario)
    {
        $horario->delete();
        return response()->json(['success' => true]);
    }

    // AJAX: load horarios for a room as a weekly grid
    public function porSalon(Request $request)
    {
        $horarios = Horario::with(['ficha', 'instructor'])
            ->where('room_id', $request->room_id)
            ->where('activo', true)
            ->get();

        return response()->json($horarios);
    }

    public function importar(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt|max:5120']);

        $handle   = fopen($request->file('file')->getRealPath(), 'r');
        fgetcsv($handle); // skip header

        $imported = 0;
        $errors   = [];
        $row      = 1;

        while (($data = fgetcsv($handle)) !== false) {
            $row++;
            $data = array_map('trim', $data);
            if (empty(array_filter($data))) continue;

            [$ficha_id, $room_id, $dia, $hi, $hf, $competencia, $instructor_id, $color] = array_pad($data, 8, '');

            if (!$ficha_id || !$room_id || !$dia || !$hi || !$hf) {
                $errors[] = "Fila {$row}: campos obligatorios faltantes.";
                continue;
            }

            try {
                $conflict = Horario::where('room_id', $room_id)
                    ->where('dia_semana', $dia)->where('activo', true)
                    ->where(fn($q) => $q
                        ->whereBetween('hora_inicio', [$hi, $hf])
                        ->orWhereBetween('hora_fin',  [$hi, $hf])
                        ->orWhere(fn($q2) => $q2->where('hora_inicio', '<=', $hi)->where('hora_fin', '>=', $hf))
                    )->exists();

                if ($conflict) { $errors[] = "Fila {$row}: conflicto de horario."; continue; }

                Horario::create([
                    'ficha_id'      => (int)$ficha_id,
                    'room_id'       => (int)$room_id,
                    'dia_semana'    => $dia,
                    'hora_inicio'   => $hi,
                    'hora_fin'      => $hf,
                    'competencia'   => $competencia ?: null,
                    'instructor_id' => $instructor_id ? (int)$instructor_id : null,
                    'color'         => $color ?: '#16a34a',
                    'activo'        => true,
                ]);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Fila {$row}: " . $e->getMessage();
            }
        }

        fclose($handle);

        if ($imported === 0 && count($errors) > 0) {
            return back()->withErrors(['import' => implode(' | ', array_slice($errors, 0, 5))]);
        }

        $msg = "{$imported} horario(s) importado(s)";
        if (count($errors) > 0) $msg .= '. Advertencias: ' . implode(' | ', array_slice($errors, 0, 3));

        return redirect()->route('horarios.index')->with('success', $msg);
    }

    public function downloadTemplate()
    {
        $headers  = [
            'Content-Type'        => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="plantilla_horarios.csv"',
        ];
        $callback = function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, ['ficha_id', 'room_id', 'dia_semana', 'hora_inicio', 'hora_fin', 'competencia', 'instructor_id', 'color']);
            fputcsv($handle, ['1', '1', 'lunes', '08:00', '10:00', 'Desarrollo de Software', '1', '#16a34a']);
            fputcsv($handle, ['2', '2', 'martes', '14:00', '16:00', 'Agronomía Básica', '', '#2563eb']);
            fclose($handle);
        };
        return response()->stream($callback, 200, $headers);
    }

    // AJAX: rooms by area
    public function salonesDisponibles(Request $request)
    {
        $rooms = Room::where('area_id', $request->area_id)
            ->where('active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'code', 'capacity', 'type']);

        return response()->json($rooms);
    }

    // AJAX: areas by centro (via sedes)
    public function areasByCentro(Request $request)
    {
        $areas = Area::whereHas('sede', fn($q) => $q->where('centro_id', $request->centro_id))
            ->where('active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($areas);
    }
}
