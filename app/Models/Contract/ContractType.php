<?php

namespace App\Models\Contract;

use App\Models\Dependencia\Dependencia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_name',
        'description',
        'dependencia_id',
    ];

    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class, 'dependencia_id');
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'contract_type_id');
    }
}
