<?php
// app/Models/Budget/DepartmentBudget.php

namespace App\Models\Budget;

use App\Models\Dependencia\Dependencia;
use App\Models\Dependency\DependencySubunit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentBudget extends Model
{
    use HasFactory;

    protected $fillable = [
        'general_budget_id',
        'department_id',
        'total_budget',
        'spent_budget',
        'year',
        'manager_id'
    ];

    public function generalBudget()
    {
        return $this->belongsTo(GeneralBudget::class, 'general_budget_id');
    }

        public function department()
    {
        return $this->belongsTo(Dependencia::class, 'department_id', 'subunit_id');
    }
    

    public function SubUnit()
    {
        return $this->belongsTo(DependencySubunit::class, 'department_id', 'subunit_id');
    }
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($budget) {
            $budget->generalBudget->recalculateTotal();
        });

        static::deleted(function ($budget) {
            $budget->generalBudget->recalculateTotal();
        });
    }
}
