<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Models\Centro;
use App\Models\Inventario\Semoviente;
use App\Models\InventorySede;
use App\Models\Sede;
use App\Models\User;
use Illuminate\Http\Request;

class SemovienteController extends Controller
{
    public function index()
    {
        $semovientes = Semoviente::with('inventorySede', 'staff', 'centro', 'sede',)->paginate(10);
        return view('semoviente.index', compact('semovientes'));
    }

    public function create()
    {
        $inventorySedes = InventorySede::all();
        $centros = Centro::all();
        $sedes = Sede::all();
        $staff = User::all();
        return view('semoviente.create', compact('inventorySedes', 'centros', 'sedes', 'staff'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'responsible_department' => 'required|string',
            'staff_id' => 'required|exists:users,id',
            'centro_id' => 'required|exists:centros,id',
            'sede_id' => 'required|exists:sedes,id',
            'birth_date' => 'required|date',
            'birth_time' => 'required',
            'birth_area' => 'required|string',
            'training_environment' => 'required|string',
            'gender' => 'required|string',
            'birth_type' => 'required|string',
            'animal_type' => 'required|string',
            'breed' => 'required|string',
            'weight' => 'required|string',
            'color' => 'required|string',
            'mother_package' => 'required|string',
            'estimated_value' => 'required|numeric',
            'status' => 'required|string',
        ]);

        // Buscar el inventory_sede_id según el sede_id seleccionado
        $inventorySede = InventorySede::where('sede_id', $validated['sede_id'])->first();

        if (!$inventorySede) {
            return back()->withErrors(['sede_id' => 'No existe un inventario asociado a esta sede.'])->withInput();
        }

        $validated['inventory_sede_id'] = $inventorySede->id;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('semovientes', 'public');
        }

        Semoviente::create($validated);

        return redirect()->route('semoviente.index')->with('success', 'Semoviente creado correctamente');
    }


    public function show(Semoviente $semoviente)
    {
        return view('semoviente.show', compact('semoviente'));
    }

    public function edit(Semoviente $semoviente)
    {
        $inventorySedes = InventorySede::all();
        return view('semoviente.edit', compact('semoviente', 'inventorySedes'));
    }

    public function update(Request $request, Semoviente $semoviente)
    {
        $validated = $request->validate([
            'responsible_department' => 'required|string',
            'staff_id' => 'required|exists:users,id',
            'centro_id' => 'required|exists:centros,id',
            'sede_id' => 'required|exists:sedes,id',
            'birth_date' => 'required|date',
            'birth_time' => 'required',
            'birth_area' => 'required|string',
            'training_environment' => 'required|string',
            'gender' => 'required|string',
            'birth_type' => 'required|string',
            'animal_type' => 'required|string',
            'breed' => 'required|string',
            'weight' => 'required|string',
            'color' => 'required|string',
            'mother_package' => 'required|string',
            'estimated_value' => 'required|numeric',
            'status' => 'required|string',
        ]);

        // Buscar el inventory_sede_id según el sede_id seleccionado
        $inventorySede = InventorySede::where('sede_id', $validated['sede_id'])->first();

        if (!$inventorySede) {
            return back()->withErrors(['sede_id' => 'No existe un inventario asociado a esta sede.'])->withInput();
        }

        $validated['inventory_sede_id'] = $inventorySede->id;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('semovientes', 'public');
        }

        $semoviente->update($validated);

        return redirect()->route('semoviente.index')->with('success', 'Semoviente actualizado correctamente');
    }


    public function destroy(Semoviente $semoviente)
    {
        $semoviente->delete();
        return redirect()->route('semoviente.index')->with('success', 'Semoviente eliminado correctamente');
    }
}
