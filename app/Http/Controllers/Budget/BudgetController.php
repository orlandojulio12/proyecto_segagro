<?php

namespace App\Http\Controllers\Budget;

use App\Http\Controllers\Controller;
use App\Models\Budget\GeneralBudget;
use App\Models\Budget\DepartmentBudget;
use App\Models\Dependencia\Dependencia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    /**
     * Show budgets of the logged user and their assigned sedes
     */
    public function index()
    {
        $user = Auth::user();

        // Get user's sedes (assuming one main assigned sedes)
        $sedes = $user->sedes()->first();

        if (!$sedes) {
            return back()->with('error', 'No sedes assigned to this user.');
        }

        // Load general budgets of that sedes with department budgets
        $budgets = GeneralBudget::with('departmentBudgets.department')
            ->where('sede_id', $sedes->id)
            ->orderBy('year', 'desc')
            ->get();

        return view('budget.index', compact('budgets', 'sedes'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $user = Auth::user();
        $sedes = $user->sedes()->first();
        $departments = Dependencia::all();

        return view('budget.create', compact('sedes', 'departments', 'user'));
    }

    /**
     * Store new general budget and its department budgets
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sede_id' => 'required|exists:sedes,id',
            'total_budget' => 'required|numeric|min:0',
            'year' => 'required|integer',
            'resolution' => 'nullable|string',
            'manager_id' => 'required|exists:users,id',

            // Departments
            'departments' => 'required|array',
            'departments.*.id' => 'required|exists:dependencias,id',
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

    /**
     * Show one budget
     */
    public function show(GeneralBudget $budget)
    {
        $budget->load('departmentBudgets.department');
        return view('budget.show', compact('budget'));
    }

    /**
     * Edit form
     */
    public function edit(GeneralBudget $budget)
    {
        $budget->load('departmentBudgets');
        $departments = Dependencia::all();
    
        // Obtener todos los usuarios que podrían ser responsables
        $managers = User::all(); // O filtra por rol si quieres solo gestores
    
        return view('budget.edit', compact('budget', 'departments', 'managers'));
    }

    /**
     * Update general and department budgets
     */
    public function update(Request $request, GeneralBudget $budget)
    {
        $validated = $request->validate([
            'total_budget' => 'required|numeric|min:0',
            'year' => 'required|integer',
            'resolution' => 'nullable|string',

            'departments' => 'required|array',
            'departments.*.id' => 'required|exists:dependencias,id',
            'departments.*.total_budget' => 'required|numeric|min:0'
        ]);

        // Update general budget
        $budget->update([
            'total_budget' => $validated['total_budget'],
            'year' => $validated['year'],
            'resolution' => $validated['resolution'],
        ]);

        // Update each department budget
        foreach ($validated['departments'] as $dep) {
            DepartmentBudget::where('general_budget_id', $budget->id)
                ->where('department_id', $dep['id'])
                ->update([
                    'total_budget' => $dep['total_budget']
                ]);
        }

        // Recalculate automatically using model event
        $budget->recalculateTotal();

        return redirect()->route('budget.index')
            ->with('success', 'Budget updated successfully.');
    }

    /**
     * Delete general budget
     */
    public function destroy(GeneralBudget $budget)
    {
        $budget->delete();

        return redirect()->route('budget.index')
            ->with('success', 'Budget deleted successfully.');
    }
}
