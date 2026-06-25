<?php

namespace App\Http\Controllers\Ficha;

use App\Http\Controllers\Controller;
use App\Models\Centro;
use App\Models\Ficha\Ficha;
use App\Models\Instructor\Instructor;
use App\Models\Sede;
use Illuminate\Http\Request;

class FichaController extends Controller
{
    public function index()
    {
        $fichas      = Ficha::with(['centro', 'sede', 'instructor'])->latest()->get();
        $centros     = Centro::orderBy('nom_centro')->get();
        $sedes       = Sede::orderBy('nom_sede')->get();
        $instructores = Instructor::where('activo', true)->orderBy('apellido')->orderBy('nombre')->get();

        return view('fichas.index', compact('fichas', 'centros', 'sedes', 'instructores'));
    }

    public function store(Request $request)
    {
        // Map centros-sedes-selector fields (prefix: fica) to standard names
        $request->merge([
            'centro_id' => $request->input('fica_centro_id'),
            'sede_id'   => $request->input('fica_sede_id'),
        ]);

        $validated = $request->validate([
            'numero_ficha'    => 'required|string|max:20|unique:fichas,numero_ficha',
            'nombre_programa' => 'required|string|max:255',
            'nivel_formacion' => 'required|in:tecnico,tecnologo,especializacion_tecnologica,auxiliar,operario,curso_complementario',
            'modalidad'       => 'required|in:presencial,virtual,mixta',
            'estado'          => 'required|in:en_convocatoria,en_formacion,en_etapa_productiva,certificado,cancelado',
            'jornada'         => 'required|in:diurna,nocturna,madrugada,fin_de_semana',
            'centro_id'       => 'required|exists:centros,id',
            'sede_id'         => 'required|exists:sedes,id',
            'instructor_id'   => 'nullable|exists:instructores,id',
            'fecha_inicio'    => 'required|date',
            'fecha_fin'       => 'required|date|after_or_equal:fecha_inicio',
            'numero_aprendices' => 'required|integer|min:0|max:50',
        ]);

        Ficha::create($validated);

        return redirect()->route('fichas.index')
            ->with('success', 'Ficha registrada correctamente');
    }

    public function show(Ficha $ficha)
    {
        $ficha->load(['centro', 'sede', 'instructor', 'horarios.room.area', 'horarios.instructor']);
        return response()->json($ficha);
    }

    public function update(Request $request, Ficha $ficha)
    {
        // Map centros-sedes-selector fields (prefix: fice) to standard names
        $request->merge([
            'centro_id' => $request->input('fice_centro_id'),
            'sede_id'   => $request->input('fice_sede_id'),
        ]);

        $validated = $request->validate([
            'numero_ficha'    => 'required|string|max:20|unique:fichas,numero_ficha,' . $ficha->id,
            'nombre_programa' => 'required|string|max:255',
            'nivel_formacion' => 'required|in:tecnico,tecnologo,especializacion_tecnologica,auxiliar,operario,curso_complementario',
            'modalidad'       => 'required|in:presencial,virtual,mixta',
            'estado'          => 'required|in:en_convocatoria,en_formacion,en_etapa_productiva,certificado,cancelado',
            'jornada'         => 'required|in:diurna,nocturna,madrugada,fin_de_semana',
            'centro_id'       => 'required|exists:centros,id',
            'sede_id'         => 'required|exists:sedes,id',
            'instructor_id'   => 'nullable|exists:instructores,id',
            'fecha_inicio'    => 'required|date',
            'fecha_fin'       => 'required|date|after_or_equal:fecha_inicio',
            'numero_aprendices' => 'required|integer|min:0|max:50',
        ]);

        $ficha->update($validated);

        return redirect()->route('fichas.index')
            ->with('success', 'Ficha actualizada correctamente');
    }

    public function destroy(Ficha $ficha)
    {
        $ficha->delete();
        return redirect()->route('fichas.index')
            ->with('success', 'Ficha eliminada correctamente');
    }
}
