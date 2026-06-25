<?php

namespace App\Http\Controllers\Traslado;

use App\Http\Controllers\Controller;
use App\Http\Requests\Traslado\StoreNeedTransferRequest;
use App\Http\Requests\Traslado\UpdateNeedTransferRequest;
use App\Models\Centro;
use App\Models\Dependency\DependencyUnit;
use App\Models\InventoryMaterial;
use App\Models\InventorySede;
use App\Models\Sede;
use App\Models\Traslado\NeedTransfer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NeedTransferController extends Controller
{
    public function index(Request $request)
    {
        $query = NeedTransfer::with(['user', 'centroInicial', 'sedeInicial', 'centroFinal', 'sedeFinal'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('centro_id')) {
            $query->where('centro_inicial_id', $request->centro_id);
        }

        if ($request->filled('search')) {
            $query->where('descripcion', 'like', "%{$request->search}%");
        }

        $traslados = $query->paginate(15)->withQueryString();
        $centros = Centro::all();

        return view('traslados.index', compact('traslados', 'centros'));
    }

    public function create()
    {
        $users = User::all();
        $materials = InventoryMaterial::all();
        $centros = Centro::all();
        $sedes = Sede::all();
        $units = DependencyUnit::with('subunits')->get();
        return view('traslados.create', compact('users', 'centros', 'sedes', 'units', 'materials'));
    }

    public function buscarMateriales(Request $request)
    {
        $search = $request->get('search', '');
        $materials = InventoryMaterial::with('inventory.sede')
            ->when($search, function ($query, $search) {
                $query->where('material_name', 'like', "%{$search}%")
                    ->orWhere('material_type', 'like', "%{$search}%");
            })
            ->paginate(10);

        return response()->json($materials);
    }

    public function store(StoreNeedTransferRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['status'] = 'pendiente';
        $traslado = NeedTransfer::create($data);

        if ($request->filled('personal')) {
            $traslado->personal()->sync($request->personal);
        }

        if ($request->filled('materiales')) {
            $syncData = [];
            foreach ($request->materiales as $mat) {
                $syncData[$mat['id']] = [
                    'cantidad' => $mat['cantidad'],
                    'tipo' => $mat['tipo'] ?? null,
                ];
            }
            $traslado->materiales()->sync($syncData);
        }
        // <-- redirige a la ruta con prefijo 'traslados'
        return redirect()->route('traslados.index')->with('success', 'Necesidad de traslado creada correctamente');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $traslado = NeedTransfer::with(['personal', 'materiales'])->findOrFail($id);

        $users = User::all();
        $centros = Centro::all();
        $sedes = Sede::all();
        $units = DependencyUnit::with('subunits')->get();
        $materials = InventoryMaterial::all();

        return view('traslados.edit', compact(
            'traslado',
            'users',
            'centros',
            'sedes',
            'units',
            'materials'
        ));
    }

    public function update(UpdateNeedTransferRequest $request, string $id)
    {
        $traslado = NeedTransfer::findOrFail($id);
        $data = $request->validated();

        $traslado->update($data);

        // Actualizar personal
        if ($request->filled('personal')) {
            $syncData = [];
            foreach ($request->personal as $p) {
                $syncData[$p['id']] = ['cargo' => $p['cargo'] ?? null];
            }
            $traslado->personal()->sync($syncData);
        } else {
            $traslado->personal()->detach();
        }

        // Actualizar materiales
        if ($request->filled('materiales')) {
            $syncData = [];
            foreach ($request->materiales as $m) {
                $syncData[$m['inventory_material_id']] = [
                    'cantidad' => $m['cantidad'],
                    'tipo' => $m['tipo'] ?? null,
                ];
            }
            $traslado->materiales()->sync($syncData);
        } else {
            $traslado->materiales()->detach();
        }

        return redirect()->route('traslados.index')
            ->with('success', 'Traslado actualizado correctamente');
    }

    public function destroy(string $id)
    {
        $traslado = NeedTransfer::findOrFail($id);

        $traslado->personal()->detach();
        $traslado->materiales()->detach();
        $traslado->infraestructuras()->detach();
        $traslado->delete();

        return redirect()->route('traslados.index')
            ->with('success', 'Traslado eliminado correctamente');
    }
}
