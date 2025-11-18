<?php

namespace App\Models\Economy;

use App\Models\Centro;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Economy extends Model
{
    use HasFactory;

    protected $fillable = [
        'centro_id',
        'start_date',
        'initial_budget',
        'used_budget',
        'final_budget',
        'end_date',
    ];

    // Relación con el centro (tabla "centros")
    public function centro()
    {
        return $this->belongsTo(Centro::class, 'centro_id');
    }

    // Relación con los presupuestos por dependencia
    public function dependencyBudgets()
    {
        return $this->hasMany(DependencyBudget::class, 'economy_id');
    }
}