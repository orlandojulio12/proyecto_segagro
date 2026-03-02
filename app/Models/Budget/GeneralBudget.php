<?php

namespace App\Models\Budget;

use App\Models\Centro;
use App\Models\Sede;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralBudget extends Model
{
    use HasFactory;

    protected $fillable = [
        'sede_id',
        'total_budget',
        'spent_budget',
        'year',
        'resolution',
        'manager_id'
    ];

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }


    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function departmentBudgets()
    {
        return $this->hasMany(DepartmentBudget::class);
    }

    // Automatically sum all department budgets
    public function recalculateTotal()
    {
        $sumDepartments = $this->departmentBudgets()->sum('total_budget');
        $sumSpent = $this->departmentBudgets()->sum('spent_budget');
        $sumAdjustments = $this->adjustments()->sum('amount');

        $this->total_budget = $sumDepartments + $sumAdjustments;
        $this->spent_budget = $sumSpent;
        $this->save();
    }


    public function adjustments()
    {
        return $this->hasMany(GeneralBudgetAdjustment::class, 'general_budget_id');
    }
}
