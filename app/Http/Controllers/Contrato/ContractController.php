<?php

namespace App\Http\Controllers\Contrato;
use App\Http\Controllers\Controller;

use App\Models\Contract\Contract;
use App\Models\Contract\ContractType;
use App\Models\Contract\HiringModality;
use App\Models\Centro;
use App\Models\Sede;
use App\Models\Dependencia\Dependencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    /**
     * Mostrar listado de contratos
     */
    public function index()
    {
        $contracts = Contract::with([
            'hiringModality',
            'contractType.dependencia',
            'sede.centro'
        ])->latest()->get();

        return view('contracts.index', compact('contracts'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $hiringModalities = HiringModality::all();
        $contractTypes = ContractType::with('dependencia')->get();
        $centros = Centro::all();
        $sedes = Sede::with('centro')->get();
        $dependencias = Dependencia::all();

        return view('contracts.create', compact(
            'hiringModalities',
            'contractTypes',
            'centros',
            'sedes',
            'dependencias'
        ));
    }

    /**
     * Guardar nuevo contrato
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_number' => 'required|string|max:255|unique:contracts',
            'hiring_modality_id' => 'required|exists:hiring_modalities,id',
            'contractor_name' => 'required|string|max:255',
            'contractor_nit' => 'required|string|max:255',
            'contract_object' => 'required|string',
            'contract_type_id' => 'required|exists:contract_types,id',
            'sede_id' => 'required|exists:sedes,id',
            'start_date' => 'required|date',
            'initial_end_date' => 'required|date|after_or_equal:start_date',
            'extension_date' => 'nullable|date|after:initial_end_date',
            'initial_value' => 'required|numeric|min:0',
            'addition_value' => 'nullable|numeric|min:0',
        ], [
            'contract_number.required' => 'El número de contrato es obligatorio',
            'contract_number.unique' => 'Este número de contrato ya existe',
            'hiring_modality_id.required' => 'La modalidad de contratación es obligatoria',
            'contractor_name.required' => 'El nombre del contratista es obligatorio',
            'contractor_nit.required' => 'El NIT del contratista es obligatorio',
            'contract_object.required' => 'El objeto del contrato es obligatorio',
            'contract_type_id.required' => 'El tipo de contrato es obligatorio',
            'sede_id.required' => 'La sede es obligatoria',
            'start_date.required' => 'La fecha de inicio es obligatoria',
            'initial_end_date.required' => 'La fecha de terminación es obligatoria',
            'initial_end_date.after_or_equal' => 'La fecha de terminación debe ser posterior a la fecha de inicio',
            'extension_date.after' => 'La fecha de prórroga debe ser posterior a la fecha de terminación inicial',
            'initial_value.required' => 'El valor inicial es obligatorio',
            'initial_value.min' => 'El valor inicial debe ser mayor o igual a 0',
        ]);

        DB::beginTransaction();
        try {
            $contract = Contract::create($validated);

            DB::commit();

            return redirect()
                ->route('contracts.show', $contract)
                ->with('success', '✅ Contrato registrado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ Error al registrar el contrato: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalle de un contrato
     */
    public function show(Contract $contract)
    {
        $contract->load([
            'hiringModality',
            'contractType.dependencia',
            'sede.centro'
        ]);

        return view('contracts.show', compact('contract'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Contract $contract)
    {
        $hiringModalities = HiringModality::all();
        $contractTypes = ContractType::with('dependencia')->get();
        $centros = Centro::all();
        $sedes = Sede::with('centro')->get();
        $dependencias = Dependencia::all();

        $contract->load([
            'hiringModality',
            'contractType.dependencia',
            'sede.centro'
        ]);

        return view('contracts.edit', compact(
            'contract',
            'hiringModalities',
            'contractTypes',
            'centros',
            'sedes',
            'dependencias'
        ));
    }

    /**
     * Actualizar contrato
     */
    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'contract_number' => 'required|string|max:255|unique:contracts,contract_number,' . $contract->id,
            'hiring_modality_id' => 'required|exists:hiring_modalities,id',
            'contractor_name' => 'required|string|max:255',
            'contractor_nit' => 'required|string|max:255',
            'contract_object' => 'required|string',
            'contract_type_id' => 'required|exists:contract_types,id',
            'sede_id' => 'required|exists:sedes,id',
            'start_date' => 'required|date',
            'initial_end_date' => 'required|date|after_or_equal:start_date',
            'extension_date' => 'nullable|date|after:initial_end_date',
            'initial_value' => 'required|numeric|min:0',
            'addition_value' => 'nullable|numeric|min:0',
        ], [
            'contract_number.required' => 'El número de contrato es obligatorio',
            'contract_number.unique' => 'Este número de contrato ya existe',
            'initial_end_date.after_or_equal' => 'La fecha de terminación debe ser posterior a la fecha de inicio',
            'extension_date.after' => 'La fecha de prórroga debe ser posterior a la fecha de terminación inicial',
        ]);

        DB::beginTransaction();
        try {
            $contract->update($validated);

            DB::commit();

            return redirect()
                ->route('contracts.show', $contract)
                ->with('success', '✅ Contrato actualizado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ Error al actualizar el contrato: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar contrato
     */
    public function destroy(Contract $contract)
    {
        DB::beginTransaction();
        try {
            $contractNumber = $contract->contract_number;
            $contract->delete();

            DB::commit();

            return redirect()
                ->route('contracts.index')
                ->with('success', "✅ Contrato {$contractNumber} eliminado exitosamente");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', '❌ Error al eliminar el contrato: ' . $e->getMessage());
        }
    }

    /**
     * Obtener sedes por centro (AJAX)
     */
    public function getSedesByCentro($centroId)
    {
        $sedes = Sede::where('centro_id', $centroId)
            ->select('id', 'nom_sede')
            ->get();

        return response()->json($sedes);
    }

    /**
     * Obtener tipos de contrato por dependencia (AJAX)
     */
    public function getTypesByDependencia($dependenciaId)
    {
        $types = ContractType::where('dependencia_id', $dependenciaId)
            ->select('id', 'type_name', 'description')
            ->get();

        return response()->json($types);
    }

    /**
     * Estadísticas de contratos (para dashboard)
     */
    public function statistics()
    {
        $stats = [
            'total' => Contract::count(),
            'active' => Contract::active()->count(),
            'expired' => Contract::expired()->count(),
            'pending' => Contract::pending()->count(),
            'total_value' => Contract::sum('initial_value'),
            'total_with_additions' => Contract::sum(DB::raw('initial_value + COALESCE(addition_value, 0)')),
        ];

        return response()->json($stats);
    }

    /**
     * Reporte de contratos (exportable)
     */
    public function report(Request $request)
    {
        $query = Contract::with([
            'hiringModality',
            'contractType.dependencia',
            'sede.centro'
        ]);

        // Filtros
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->active();
                    break;
                case 'expired':
                    $query->expired();
                    break;
                case 'pending':
                    $query->pending();
                    break;
            }
        }

        if ($request->filled('sede_id')) {
            $query->where('sede_id', $request->sede_id);
        }

        if ($request->filled('modality_id')) {
            $query->where('hiring_modality_id', $request->modality_id);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $contracts = $query->get();

        return view('contracts.report', compact('contracts'));
    }
}