<?php

namespace App\Http\Controllers\Area;

use App\Http\Controllers\Controller;
use App\Models\Area\Area;
use App\Models\Centro;
use App\Models\Sede;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::with('sede')->latest()->get();
        $centros = Centro::orderBy('nom_centro')->get();
        $sedes = Sede::with(['centro', 'areas'])->get();

        return view('areas.index', compact('areas', 'sedes', 'centros'));
    }

    public function create()
    {
        $sedes = Sede::orderBy('nom_sede')->get();
        $centros = Centro::all();
        return view('areas.create', compact('sedes', 'centros'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'inicial_sede_id' => 'required|exists:sedes,id',
            'name'            => 'required|string|max:255',
            'code'            => 'nullable|string|max:50',
            'description'     => 'nullable|string',
            'active'          => 'boolean',
        ]);

        Area::create([
            'sede_id'     => $validated['inicial_sede_id'],
            'name'        => $validated['name'],
            'code'        => $validated['code'] ?? null,
            'description' => $validated['description'] ?? null,
            'active'      => $validated['active'] ?? true,
        ]);

        return redirect()
            ->route('areas.index')
            ->with('success', 'Área creada correctamente');
    }

    public function edit($id)
    {
        $area  = Area::findOrFail($id);
        $sedes = Sede::orderBy('nom_sede')->get();

        return view('areas.edit', compact('area', 'sedes'));
    }

    public function update(Request $request, $id)
    {
        $area = Area::findOrFail($id);

        $validated = $request->validate([
            'sede_id'     => 'required|exists:sedes,id',
            'name'        => 'required|string|max:255',
            'code'        => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'active'      => 'boolean',
        ]);

        $area->update($validated);

        return redirect()
            ->route('areas.index')
            ->with('success', 'Area actualizada correctamente');
    }

    /**
     * AJAX: Areas por sede
     */
    public function getBySede($sedeId)
    {
        return Area::where('sede_id', $sedeId)
            ->where('active', true)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
    }
}
