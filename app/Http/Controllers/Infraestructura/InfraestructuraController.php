<?php

namespace App\Http\Controllers\Infraestructura;

use App\Http\Controllers\Controller;
use App\Models\Area\Area;
use App\Models\Centro;
use App\Models\Dependencia\Dependencia;
use App\Models\Dependency\DependencySubunit;
use App\Models\Dependency\DependencyUnit;
use App\Models\Infraestructura\Infraestructura;
use App\Models\Sede;
use App\Models\Traslado\NeedTransfer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InfraestructuraController extends Controller
{
    public function index()
    {
        $units = DependencyUnit::with('subunits')->get();
        $infraestructuras = Infraestructura::with(['dependencia', 'funcionario', 'centro', 'sede'])
            ->latest()->get();
        return view('Infraestructura.index', compact('infraestructuras', 'units'));
    }

    public function create()
    {
        $units = DependencyUnit::with('subunits')->get();
        $users = User::all();
        $centros = Centro::all();
        $areas   = Area::where('active', true)->get();
        $sedes = Sede::all();

        return view('Infraestructura.create', compact('units', 'users', 'centros', 'sedes', 'areas'));
    }

    public function store(Request $request)
    {
        // ================== VALIDACIÓN ==================
        $validated = $request->validate([
            'user_id'           => 'required|exists:users,id',
            'unidad_id'         => 'required|exists:dependency_units,dependency_unit_id',
            'subunidad_id'      => 'required|exists:dependency_subunits,subunit_id',

            // CENTRO / SEDE INICIAL (NOMBRES REALES DEL FORM)
            'inicial_centro_id' => 'required|exists:centros,id',
            'inicial_sede_id'   => 'required|exists:sedes,id',

            'nivel_riesgo'      => 'required|in:1,2,3',
            'tipo_necesidad'    => 'required|string',

            'area_id' => 'required|exists:areas,id',
            'ambiente' => 'required|exists:rooms,id',

            'nivel_complejidad' => 'required|in:1,2,3',
            'descripcion'       => 'required|string',
            'motivo_necesidad'  => 'required|string',

            'imagen'            => 'nullable|image|max:2048',
            'requiere_traslado' => 'boolean',

            // SOLO SI requiere traslado
            'final_centro_id'   => 'required_if:requiere_traslado,1|nullable|exists:centros,id',
            'final_sede_id'     => 'required_if:requiere_traslado,1|nullable|exists:sedes,id',
        ]);

        DB::beginTransaction();

        try {
            // ================== IMAGEN ==================
            $path = null;
            if ($request->hasFile('imagen')) {
                $path = $request->file('imagen')->store('infraestructuras', 'public');
            }

            // ================== SUBUNIDAD ==================
            $subunidad = DependencySubunit::findOrFail($validated['subunidad_id']);

            // ================== INFRAESTRUCTURA ==================
            $infraestructura = Infraestructura::create([
                'unidad_id'         => $validated['unidad_id'],
                'subunidad_id'      => $validated['subunidad_id'],
                'user_id'           => $validated['user_id'],

                'centro_id'         => $validated['inicial_centro_id'],
                'sede_id'           => $validated['inicial_sede_id'],

                'nivel_riesgo'      => $validated['nivel_riesgo'],
                'tipo_necesidad'    => $validated['tipo_necesidad'],

                'area_necesidad' => $validated['area_id'],
                'ambiente'       => $validated['ambiente'],
                
                'nivel_complejidad' => $validated['nivel_complejidad'],
                'descripcion'       => $validated['descripcion'],
                'motivo_necesidad'  => $validated['motivo_necesidad'],
                'requiere_traslado' => $request->boolean('requiere_traslado'),

                'centro_final_id'   => $validated['final_centro_id'] ?? null,
                'sede_final_id'     => $validated['final_sede_id'] ?? null,

                'imagen'            => $path,
            ]);

            // ================== NEED TRANSFER ==================
            if ($infraestructura->requiere_traslado) {
                $needTransfer = NeedTransfer::create([
                    'user_id'           => $infraestructura->user_id,
                    'unidad_id'         => $infraestructura->unidad_id,
                    'subunidad_id'      => $infraestructura->subunidad_id,

                    'centro_inicial_id' => $infraestructura->centro_id,
                    'sede_inicial_id'   => $infraestructura->sede_id,

                    'centro_final_id'   => $infraestructura->centro_final_id,
                    'sede_final_id'     => $infraestructura->sede_final_id,

                    'fecha_inicio'      => now(),
                    'fecha_fin'         => now()->addDays(12),

                    'descripcion'       => $infraestructura->descripcion,
                    'nivel_riesgo'      => $infraestructura->nivel_riesgo,
                    'nivel_complejidad' => $infraestructura->nivel_complejidad,
                    'status'            => 'pendiente',
                ]);

                // RELACIÓN PIVOT
                $infraestructura->needTransfers()->attach($needTransfer->id);
            }

            DB::commit();

            return redirect()
                ->route('infraestructura.index')
                ->with('success', 'Infraestructura creada correctamente');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e; // para que lo veas en debug/log
        }
    }

    public function show(Infraestructura $infraestructura)
    {
        $infraestructura->load([
            'dependencia',
            'funcionario',
            'centro',
            'sede',
            'room',
            'needTransfers.centroInicial',
            'needTransfers.sedeInicial',
            'needTransfers.centroFinal',
            'needTransfers.sedeFinal',
        ]);

        return view('Infraestructura.show', compact('infraestructura'));
    }

    public function destroy(Infraestructura $infraestructura)
    {
        if ($infraestructura->imagen && \Storage::disk('public')->exists($infraestructura->imagen)) {
            \Storage::disk('public')->delete($infraestructura->imagen);
        }

        $infraestructura->needTransfers()->detach();
        $infraestructura->delete();

        return redirect()->route('infraestructura.index')
            ->with('success', 'Infraestructura eliminada correctamente');
    }

    public function edit(Infraestructura $infraestructura)
    {
        $units   = DependencyUnit::with('subunits')->get();
        $users   = User::all();
        $centros = Centro::all();
        $areas   = Area::where('active', true)->get();
        $sedes   = Sede::all();

        return view('Infraestructura.edit', compact(
            'infraestructura', 'units', 'users', 'centros', 'sedes', 'areas'
        ));
    }

    public function update(Request $request, Infraestructura $infraestructura)
    {
        $validated = $request->validate([
            'user_id'           => 'required|exists:users,id',
            'unidad_id'         => 'required|exists:dependency_units,dependency_unit_id',
            'subunidad_id'      => 'required|exists:dependency_subunits,subunit_id',
            'inicial_centro_id' => 'required|exists:centros,id',
            'inicial_sede_id'   => 'required|exists:sedes,id',
            'nivel_riesgo'      => 'required|in:1,2,3',
            'tipo_necesidad'    => 'required|string',
            'area_id'           => 'required|exists:areas,id',
            'ambiente'          => 'required|exists:rooms,id',
            'nivel_complejidad' => 'required|in:1,2,3',
            'descripcion'       => 'required|string',
            'motivo_necesidad'  => 'required|string',
            'imagen'            => 'nullable|image|max:2048',
            'requiere_traslado' => 'boolean',
            'final_centro_id'   => 'required_if:requiere_traslado,1|nullable|exists:centros,id',
            'final_sede_id'     => 'required_if:requiere_traslado,1|nullable|exists:sedes,id',
        ]);

        DB::beginTransaction();
        try {
            $path = $infraestructura->imagen;
            if ($request->hasFile('imagen')) {
                if ($path && \Storage::disk('public')->exists($path)) {
                    \Storage::disk('public')->delete($path);
                }
                $path = $request->file('imagen')->store('infraestructuras', 'public');
            }

            $infraestructura->update([
                'unidad_id'         => $validated['unidad_id'],
                'subunidad_id'      => $validated['subunidad_id'],
                'user_id'           => $validated['user_id'],
                'centro_id'         => $validated['inicial_centro_id'],
                'sede_id'           => $validated['inicial_sede_id'],
                'nivel_riesgo'      => $validated['nivel_riesgo'],
                'tipo_necesidad'    => $validated['tipo_necesidad'],
                'area_necesidad'    => $validated['area_id'],
                'ambiente'          => $validated['ambiente'],
                'nivel_complejidad' => $validated['nivel_complejidad'],
                'descripcion'       => $validated['descripcion'],
                'motivo_necesidad'  => $validated['motivo_necesidad'],
                'requiere_traslado' => $request->boolean('requiere_traslado'),
                'centro_final_id'   => $validated['final_centro_id'] ?? null,
                'sede_final_id'     => $validated['final_sede_id'] ?? null,
                'imagen'            => $path,
            ]);

            // Sync need transfer if required
            if ($infraestructura->requiere_traslado) {
                $existingRelation = DB::table('infraestructura_need_transfer')
                    ->where('infraestructura_id', $infraestructura->id)->first();

                $ntData = [
                    'user_id'           => $infraestructura->user_id,
                    'unidad_id'         => $infraestructura->unidad_id,
                    'subunidad_id'      => $infraestructura->subunidad_id,
                    'centro_inicial_id' => $infraestructura->centro_id,
                    'sede_inicial_id'   => $infraestructura->sede_id,
                    'centro_final_id'   => $infraestructura->centro_final_id,
                    'sede_final_id'     => $infraestructura->sede_final_id,
                    'descripcion'       => $infraestructura->descripcion,
                    'nivel_riesgo'      => $infraestructura->nivel_riesgo,
                    'nivel_complejidad' => $infraestructura->nivel_complejidad,
                    'status'            => 'pendiente',
                ];

                if ($existingRelation) {
                    NeedTransfer::find($existingRelation->need_transfer_id)?->update($ntData);
                } else {
                    $nt = NeedTransfer::create(array_merge($ntData, [
                        'fecha_inicio' => now(),
                        'fecha_fin'    => now()->addDays(12),
                    ]));
                    DB::table('infraestructura_need_transfer')->insert([
                        'infraestructura_id' => $infraestructura->id,
                        'need_transfer_id'   => $nt->id,
                        'estado'             => 'pendiente',
                        'created_at'         => now(),
                        'updated_at'         => now(),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('infraestructura.index')
                ->with('success', 'Infraestructura actualizada correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}
