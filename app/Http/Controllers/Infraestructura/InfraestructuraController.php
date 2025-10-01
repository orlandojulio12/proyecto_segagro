<?php

namespace App\Http\Controllers\Infraestructura;

use App\Http\Controllers\Controller;
use App\Models\Centro;
use App\Models\Dependencia\Dependencia;
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
        $infraestructuras = Infraestructura::with(['dependencia', 'funcionario', 'centro', 'sede'])
            ->latest()->get();
        return view('infraestructura.index', compact('infraestructuras'));
    }

    public function create()
    {
        $dependencias = Dependencia::all();
        $users = User::all();
        $centros = Centro::all();
        $sedes = Sede::all();

        return view('infraestructura.create', compact('dependencias', 'users', 'centros', 'sedes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dependencia_id' => 'required|exists:dependencias,id',
            'user_id' => 'required|exists:users,id',
            'centro_id' => 'required|exists:centros,id',
            'sede_id' => 'required|exists:sedes,id',
            'nivel_riesgo' => 'required',
            'tipo_necesidad' => 'required',
            'area_necesidad' => 'required',
            'nivel_complejidad' => 'required',
            'descripcion' => 'required',
            'motivo_necesidad' => 'required',
            'imagen' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Subir imagen si existe
            $path = null;
            if ($request->hasFile('imagen')) {
                $path = $request->file('imagen')->store('infraestructuras', 'public');
            }

            // 📦 Datos listos para crear
            $data = [
                ...$validated,
                'ambiente' => $request->ambiente,
                'requiere_traslado' => $request->boolean('requiere_traslado'),
                'personal' => $request->personal ?? [],
                'fuente_financiacion' => $request->fuente_financiacion,
                'centro_final_id' => $request->centro_final_id,
                'sede_final_id' => $request->sede_final_id,
                'imagen' => $path,
                'fecha_inicio' => null,
                'fecha_fin' => null,
                'nivel_prioridad' => null,
                'presupuesto_solicitado' => null,
                'presupuesto_aceptado' => null,
            ];

            // 🚀 Aquí lo retornamos en JSON para que veas qué se envía
            /* return response()->json($data); */

            // Si quisieras guardar después, lo descomentas
            Infraestructura::create($data);
            DB::commit();
            return redirect()->route('infraestructura.index')
                ->with('success', 'Infraestructura creada correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }


    public function edit(Infraestructura $infraestructura)
    {
        $dependencias = Dependencia::all();
        $users = User::all();
        $centros = Centro::all();

        // Solo las sedes del centro relacionado a esta infraestructura
        $sedes = Sede::where('centro_id', $infraestructura->centro_id)->get();

        return view('infraestructura.edit', compact(
            'infraestructura',
            'dependencias',
            'users',
            'centros',
            'sedes'
        ));
    }

    public function update(Request $request, Infraestructura $infraestructura)
    {
        $validated = $request->validate([
            'dependencia_id' => 'required|exists:dependencias,id',
            'user_id' => 'required|exists:users,id',
            'centro_id' => 'required|exists:centros,id',
            'sede_id' => 'required|exists:sedes,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'nivel_riesgo' => 'required',
            'nivel_prioridad' => 'required',
            'tipo_necesidad' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $infraestructura->update([
                ...$validated,
                'ambiente' => $request->ambiente,
                'descripcion' => $request->descripcion,
                'motivo_necesidad' => $request->motivo_necesidad,
                'requiere_traslado' => $request->requiere_traslado ? true : false,
                'personal' => $request->personal ?? [],
                'fuente_financiacion' => $request->fuente_financiacion,
                'presupuesto_solicitado' => $request->presupuesto_solicitado,
                'presupuesto_aceptado' => $request->presupuesto_aceptado,
            ]);

            // ✅ Manejo de traslado
            if ($infraestructura->requiere_traslado) {

                // 1️⃣ Buscar si ya existe relación en la pivote
                $existingRelation = DB::table('infraestructura_need_transfer')
                    ->where('infraestructura_id', $infraestructura->id)
                    ->first();

                if ($existingRelation) {
                    // 2️⃣ Ya existe → actualizamos el need_transfer
                    $needTransfer = NeedTransfer::find($existingRelation->need_transfer_id);
                    if ($needTransfer) {
                        $needTransfer->update([
                            'user_id' => $infraestructura->user_id,
                            'dependencia_id' => $infraestructura->dependencia_id,
                            'centro_inicial_id' => $infraestructura->centro_id,
                            'sede_inicial_id' => $infraestructura->sede_id,
                            'centro_final_id' => $request->centro_final_id,
                            'sede_final_id' => $request->sede_final_id,
                            'fecha_inicio' => $infraestructura->fecha_inicio,
                            'fecha_fin' => $infraestructura->fecha_fin,
                            'descripcion' => $infraestructura->descripcion,
                            'nivel_riesgo' => $infraestructura->nivel_riesgo,
                            'nivel_complejidad' => $request->nivel_complejidad ?? 'baja',
                            'presupuesto_solicitado' => $infraestructura->presupuesto_solicitado,
                            'requiere_personal' => $infraestructura->personal ? true : false,
                            'requiere_materiales' => $request->has('materiales'),
                        ]);
                    }
                } else {
                    // 3️⃣ No existe → creamos nuevo need_transfer y la relación
                    $needTransfer = NeedTransfer::create([
                        'user_id' => $infraestructura->user_id,
                        'dependencia_id' => $infraestructura->dependencia_id,
                        'centro_inicial_id' => $infraestructura->centro_id,
                        'sede_inicial_id' => $infraestructura->sede_id,
                        'centro_final_id' => $request->centro_final_id,
                        'sede_final_id' => $request->sede_final_id,
                        'fecha_inicio' => $infraestructura->fecha_inicio,
                        'fecha_fin' => $infraestructura->fecha_fin,
                        'descripcion' => $infraestructura->descripcion,
                        'nivel_riesgo' => $infraestructura->nivel_riesgo,
                        'nivel_complejidad' => $request->nivel_complejidad ?? 'baja',
                        'presupuesto_solicitado' => $infraestructura->presupuesto_solicitado,
                        'requiere_personal' => $infraestructura->personal ? true : false,
                        'requiere_materiales' => $request->has('materiales'),
                    ]);

                    DB::table('infraestructura_need_transfer')->insert([
                        'infraestructura_id' => $infraestructura->id,
                        'need_transfer_id' => $needTransfer->id,
                        'estado' => 'pendiente',
                        'created_at' => now(),
                        'updated_at' => now(),
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
