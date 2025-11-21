<?php
// app/Http/Controllers/CentroController.php

namespace App\Http\Controllers;

use App\Models\Centro;
use Illuminate\Http\Request;

class CentroController extends Controller
{
    public function index()
    {
        $centros = Centro::latest()->get();
        return view('Centros.index', compact('centros'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_centro' => 'required|string|max:70',
            'id_municipio' => 'required|string|max:50',
            'barrio_centro' => 'nullable|string|max:50',
            'direc_centro' => 'nullable|string|max:100',
            'extension' => 'nullable|string|max:50',
            'id_regional' => 'nullable|string|max:50',
            'departamento' => 'nullable|string|max:50'
        ]);

        Centro::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Centro creado exitosamente'
        ]);
    }

    public function show(Centro $centro)
    {
        return response()->json($centro);
    }

    public function update(Request $request, Centro $centro)
    {
        $request->validate([
            'nom_centro' => 'required|string|max:70',
            'id_municipio' => 'required|string|max:50',
            'barrio_centro' => 'nullable|string|max:50',
            'direc_centro' => 'nullable|string|max:100',
            'extension' => 'nullable|string|max:50',
            'id_regional' => 'nullable|string|max:50',
            'departamento' => 'nullable|string|max:50'
        ]);

        $centro->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Centro actualizado exitosamente'
        ]);
    }

    public function destroy(Centro $centro)
    {
        $centro->delete();

        return response()->json([
            'success' => true,
            'message' => 'Centro eliminado exitosamente'
        ]);
    }
}