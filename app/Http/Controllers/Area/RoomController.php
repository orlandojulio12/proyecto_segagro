<?php

namespace App\Http\Controllers\Area;

use App\Http\Controllers\Controller;
use App\Models\Area\Area;
use App\Models\Area\Room;
use App\Models\Centro;
use App\Models\Horario\Horario;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $centros = Centro::all();
        $areas   = Area::with('sede')->where('active', true)->orderBy('name')->get();
        return view('rooms.index', compact('centros', 'areas'));
    }

    public function filter(Request $request)
    {
        $query = Area::with(['sede', 'rooms']);

        // Filtrando por centro y sede
        if ($request->filled('centro_id') || $request->filled('sede_id')) {
            $query->whereHas('sede', function ($q) use ($request) {
                if ($request->filled('centro_id')) {
                    $q->where('centro_id', $request->centro_id);
                }
                if ($request->filled('sede_id')) {
                    $q->where('id', $request->sede_id);
                }
            });
        }

        $areas = $query->get();

        // Depuración temporal
        /*  dd($areas); */

        return view('rooms.partials.rooms-tree', compact('areas'));
    }

    public function create()
    {
        $areas = Area::with('sede')
            ->where('active', true)
            ->orderBy('name')
            ->get();

        return view('rooms.create', compact('areas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'area_id'  => 'required|exists:areas,id',
            'name'     => 'required|string|max:255',
            'code'     => 'nullable|string|max:50',
            'capacity' => 'nullable|integer|min:1',
            'type'     => 'nullable|string|max:100',
            'active'   => 'boolean',
        ]);

        Room::create($validated);

        return redirect()
            ->route('rooms.index')
            ->with('success', 'Room creado correctamente');
    }

    public function edit($id)
    {
        $room  = Room::findOrFail($id);
        $areas = Area::where('active', true)->orderBy('name')->get();

        return view('rooms.edit', compact('room', 'areas'));
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $validated = $request->validate([
            'area_id'  => 'required|exists:areas,id',
            'name'     => 'required|string|max:255',
            'code'     => 'nullable|string|max:50',
            'capacity' => 'nullable|integer|min:1',
            'type'     => 'nullable|string|max:100',
            'active'   => 'boolean',
        ]);

        $room->update($validated);

        return redirect()
            ->route('rooms.index')
            ->with('success', 'Room actualizado correctamente');
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);

        if (Horario::where('room_id', $room->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el salón porque tiene horarios asignados.',
            ], 422);
        }

        $room->delete();

        return response()->json([
            'success' => true,
            'message' => 'Salón eliminado correctamente.',
        ]);
    }

    /**
     * AJAX: Rooms por area
     */
    public function getRoomsByArea($areaId)
    {
        return Room::where('area_id', $areaId)
            ->where('active', true)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
    }
}
