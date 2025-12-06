<?php

namespace App\Models\Budget;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralBudgetAdjustment extends Model
{
    use HasFactory;

    protected $primaryKey = 'adjustment_id';

    protected $fillable = [
        'general_budget_id',
        'user_id',
        'amount',
        'description'
    ];

    public function generalBudget()
    {
        return $this->belongsTo(GeneralBudget::class, 'general_budget_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Cuando se crea un ajuste, actualizar automáticamente el presupuesto general
    protected static function boot()
    {
        parent::boot();

        static::created(function ($adjustment) {
            $general = $adjustment->generalBudget;

            // Ajusta el total del presupuesto general
            $general->total_budget += $adjustment->amount;

            $general->save();
        });
    }
}
