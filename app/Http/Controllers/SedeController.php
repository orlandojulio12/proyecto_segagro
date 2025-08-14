<?php
// app/Http/Controllers/SedeController.php

namespace App\Http\Controllers;

use App\Models\Sede;
use App\Models\Centro;
use Illuminate\Http\Request;

class SedeController extends Controller
{
    public function index()
    {
        $sedes = Sede::with('centro')->latest()->get();
        $centros = Centro::all();
        return view('sedes.index', compact('sedes', 'centros'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_sede' => 'required|string|max:50',
            'centro_id' => 'required|exists:centros,id',
            'matricula_inmobiliario' => 'nullable|string|max:50',
            'barrio_sede' => 'nullable|string|max:50',
            'direc_sede' => 'nullable|string|max:50',
            'localidad' => 'nullable|string|max:50',
            'descripcion' => 'nullable|string'
        ]);

        Sede::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Sede creada exitosamente'
        ]);
    }

    public function show(Sede $sede)
    {
        return response()->json($sede->load('centro'));
    }

    public function update(Request $request, Sede $sede)
    {
        $request->validate([
            'nom_sede' => 'required|string|max:50',
            'centro_id' => 'required|exists:centros,id',
            'matricula_inmobiliario' => 'nullable|string|max:50',
            'barrio_sede' => 'nullable|string|max:50',
            'direc_sede' => 'nullable|string|max:50',
            'localidad' => 'nullable|string|max:50',
            'descripcion' => 'nullable|string'
        ]);

        $sede->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Sede actualizada exitosamente'
        ]);
    }

    public function destroy(Sede $sede)
    {
        $sede->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sede eliminada exitosamente'
        ]);
    }
}