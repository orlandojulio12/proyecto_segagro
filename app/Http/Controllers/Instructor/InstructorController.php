<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Instructor\Instructor;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    public function index()
    {
        $instructores = Instructor::orderBy('apellido')->orderBy('nombre')->get();
        return view('instructores.index', compact('instructores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'         => 'required|string|max:100',
            'apellido'       => 'required|string|max:100',
            'documento'      => 'required|string|max:20|unique:instructores,documento',
            'email'          => 'nullable|email|max:150|unique:instructores,email',
            'telefono'       => 'nullable|string|max:20',
            'especialidad'   => 'nullable|string|max:200',
            'tipo_contrato'  => 'required|in:planta,contrato,hora_catedra',
            'activo'         => 'boolean',
        ]);

        $validated['activo'] = $request->boolean('activo', true);
        Instructor::create($validated);

        return redirect()->route('instructores.index')
            ->with('success', 'Instructor registrado correctamente.');
    }

    public function show(Instructor $instructor)
    {
        return response()->json($instructor);
    }

    public function update(Request $request, Instructor $instructor)
    {
        $validated = $request->validate([
            'nombre'         => 'required|string|max:100',
            'apellido'       => 'required|string|max:100',
            'documento'      => 'required|string|max:20|unique:instructores,documento,' . $instructor->id,
            'email'          => 'nullable|email|max:150|unique:instructores,email,' . $instructor->id,
            'telefono'       => 'nullable|string|max:20',
            'especialidad'   => 'nullable|string|max:200',
            'tipo_contrato'  => 'required|in:planta,contrato,hora_catedra',
            'activo'         => 'boolean',
        ]);

        $validated['activo'] = $request->boolean('activo');
        $instructor->update($validated);

        return redirect()->route('instructores.index')
            ->with('success', 'Instructor actualizado correctamente.');
    }

    public function destroy(Instructor $instructor)
    {
        $instructor->delete();
        return redirect()->route('instructores.index')
            ->with('success', 'Instructor eliminado correctamente.');
    }

    public function importar(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt|max:5120']);

        $handle  = fopen($request->file('file')->getRealPath(), 'r');
        fgetcsv($handle); // skip header row

        $imported = 0;
        $errors   = [];
        $row      = 1;

        while (($data = fgetcsv($handle)) !== false) {
            $row++;
            $data = array_map('trim', $data);
            if (empty(array_filter($data))) continue;

            [$nombre, $apellido, $documento, $email, $telefono, $especialidad, $tipo] = array_pad($data, 7, '');

            if (!$nombre || !$apellido || !$documento) {
                $errors[] = "Fila {$row}: nombre, apellido y documento son obligatorios.";
                continue;
            }

            try {
                Instructor::updateOrCreate(
                    ['documento' => $documento],
                    [
                        'nombre'        => $nombre,
                        'apellido'      => $apellido,
                        'email'         => $email ?: null,
                        'telefono'      => $telefono ?: null,
                        'especialidad'  => $especialidad ?: null,
                        'tipo_contrato' => in_array($tipo, ['planta', 'contrato', 'hora_catedra']) ? $tipo : 'contrato',
                        'activo'        => true,
                    ]
                );
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Fila {$row}: {$e->getMessage()}";
            }
        }

        fclose($handle);

        if ($imported === 0 && count($errors) > 0) {
            return back()->withErrors(['import' => implode(' | ', array_slice($errors, 0, 5))]);
        }

        $msg = "{$imported} instructor(es) importado(s)";
        if (count($errors) > 0) {
            $msg .= '. Advertencias: ' . implode(' | ', array_slice($errors, 0, 3));
        }

        return redirect()->route('instructores.index')->with('success', $msg);
    }

    public function downloadTemplate()
    {
        $headers  = [
            'Content-Type'        => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="plantilla_instructores.csv"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM
            fputcsv($handle, ['nombre', 'apellido', 'documento', 'email', 'telefono', 'especialidad', 'tipo_contrato']);
            fputcsv($handle, ['Juan', 'Pérez', '12345678', 'juan@sena.edu.co', '3001234567', 'Tecnología de Software', 'planta']);
            fputcsv($handle, ['María', 'González', '87654321', 'maria@sena.edu.co', '3007654321', 'Agronomía', 'contrato']);
            fputcsv($handle, ['Carlos', 'Rodríguez', '11223344', '', '3115556677', 'Administración', 'hora_catedra']);
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
