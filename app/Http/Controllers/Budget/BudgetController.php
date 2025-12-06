<?php

namespace App\Http\Controllers\Budget;

use App\Http\Controllers\Controller;
use App\Models\Budget\GeneralBudget;
use App\Models\Budget\DepartmentBudget;
use App\Models\Dependencia\Dependencia;
use App\Models\Dependency\DependencyUnit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        // Get user's sedes (assuming one main assigned sedes)
        $sedes = $user->sedes()->first();

        if (!$sedes) {
            return back()->with('error', 'No sedes assigned to this user.');
        }

        // Load general budgets of that sedes with department budgets
        $budgets = GeneralBudget::with('departmentBudgets.SubUnit')
            ->where('sede_id', $sedes->id)
            ->orderBy('year', 'desc')
            ->get();

        return view('budget.index', compact('budgets', 'sedes'));
    }

    public function create()
    {
        $user = Auth::user();
        $sedes = $user->sedes()->first();

        // Ahora traemos las unidades con sus subunidades asociadas
        $units = DependencyUnit::with('subunits')->get();

        return view('budget.create', compact('sedes', 'units', 'user'));
    }

    public function store(Request $request)
    {

        $request->merge([
            'total_budget' => preg_replace('/[^\d]/', '', $request->total_budget),
        ]);

        $validated = $request->validate([
            'sede_id' => 'required|exists:sedes,id',
            'total_budget' => 'required|numeric|min:0',
            'year' => 'required|integer',
            'resolution' => 'nullable|string',
            'manager_id' => 'required|exists:users,id',

            // Departments
            'departments' => 'required|array',
            'departments.*.id' => 'required|exists:dependency_subunits,subunit_id',
            'departments.*.total_budget' => 'required|numeric|min:0'
        ]);

        // Create general budget
        $general = GeneralBudget::create([
            'sede_id' => $validated['sede_id'],
            'total_budget' => $validated['total_budget'],
            'spent_budget' => 0,
            'year' => $validated['year'],
            'resolution' => $validated['resolution'],
            'manager_id' => $validated['manager_id'],
        ]);

        // Create department budgets
        foreach ($validated['departments'] as $dep) {
            DepartmentBudget::create([
                'general_budget_id' => $general->id,
                'department_id' => $dep['id'],
                'total_budget' => $dep['total_budget'],
                'spent_budget' => 0,
                'year' => $general->year,
                'manager_id' => $general->manager_id
            ]);
        }

        return redirect()->route('budget.index')
            ->with('success', 'Budget created successfully.');
    }

    public function show(GeneralBudget $budget)
    {
        $user = Auth::user();

        $sedes = $user->sedes()->first();

        $budget->load([
            'departmentBudgets.SubUnit.dependencyUnit.subunits'
        ]);
        return view('budget.show', compact('budget', 'sedes', 'user'));
    }

    public function edit(GeneralBudget $budget)
    {
        $units = DependencyUnit::with('subunits')->get();
        $managers = User::all();
    
        $adjustments = $budget->adjustments()
            ->orderBy('created_at', 'desc')
            ->paginate(5);
    
        return view('budget.edit', [
            'budget' => $budget,
            'units' => $units,
            'managers' => $managers,
            'adjustments' => $adjustments,
        ]);
    }
    

    public function adjustments(Request $request, $id)
    {
        $budget = GeneralBudget::findOrFail($id);

        $adjustments = $budget->adjustments()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        // Si es AJAX, devolver SOLO el HTML del card
        if ($request->ajax()) {
            $html = view('budget.partials.adjustments-card', compact('adjustments'))->render();

            return response()->json([
                'html' => $html
            ]);
        }

        // Si alguien accede directo sin AJAX, regresamos al presupuesto
        return redirect()->route('budget.edit', $budget->id);
    }
    public function update(Request $request, GeneralBudget $budget)
    {
        // Limpieza del presupuesto total recibido
        $request->merge([
            'total_budget' => preg_replace('/[^\d]/', '', $request->total_budget),
        ]);

        // Limpieza de spent_budget por cada dependencia
        if ($request->has('departments')) {
            foreach ($request->departments as $key => $dep) {
                if (isset($dep['spent_budget'])) {
                    $request->merge([
                        'departments.' . $key . '.spent_budget' => preg_replace('/[^\d]/', '', $dep['spent_budget'])
                    ]);
                }
            }
        }


        $validated = $request->validate([
            'departments.*.spent_budget' => 'nullable|numeric|min:0',
            'year' => 'required|integer',
            'resolution' => 'nullable|string',

            // Department budgets
            'departments' => 'required|array',
            'departments.*.department_id' => 'required|exists:dependency_subunits,subunit_id',
            'departments.*.total_budget' => 'required|numeric|min:0',
            'departments.*.manager_id' => 'required|exists:users,id',

            // Adjustment
            'adjustment_amount' => 'nullable|string',
            'adjustment_description' => 'nullable|string'
        ]);

        // 1. NO ACTUALIZAR total_budget MANUALMENTE (lo calcula recalculateTotal)
        $budget->update([
            'year' => $validated['year'],
            'resolution' => $validated['resolution'],
        ]);

        // 2. Actualizar dependencias
        foreach ($validated['departments'] as $dep) {
            DepartmentBudget::where('general_budget_id', $budget->id)
                ->where('department_id', $dep['department_id'])
                ->update([
                    'total_budget' => $dep['total_budget'],
                    'spent_budget' => $dep['spent_budget'] ?? 0,
                    'manager_id' => $dep['manager_id']
                ]);
        }

        // 3. Proceso del ajuste
        $cleanAmount = null;

        if ($request->filled('adjustment_amount')) {
            $cleanAmount = (int) preg_replace('/[^\d-]/', '', $request->adjustment_amount);
        }

        // 4. Crear ajuste si existe
        if (!is_null($cleanAmount) && $cleanAmount != 0) {

            if (!$request->filled('adjustment_description')) {
                return back()
                    ->withErrors(['adjustment_description' => 'Debe ingresar una descripción del ajuste.'])
                    ->withInput();
            }

            $budget->adjustments()->create([
                'amount' => $cleanAmount,
                'description' => $request->adjustment_description,
                'user_id' => Auth::id(),
                'created_at' => now(),
            ]);
        }

        // 5. Recalcular SIEMPRE total = dependencias + ajustes
        $budget->recalculateTotal();

        return redirect()->route('budget.index')
            ->with('success', 'Presupuesto actualizado correctamente.');
    }
    public function destroy(GeneralBudget $budget)
    {
        $budget->delete();

        return redirect()->route('budget.index')
            ->with('success', 'Budget deleted successfully.');
    }
}
