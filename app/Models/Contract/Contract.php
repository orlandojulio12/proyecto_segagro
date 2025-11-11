<?php

namespace App\Models\Contract;

use App\Models\Dependencia\Dependencia;
use App\Models\Sede;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_number',
        'hiring_modality_id',
        'contractor_name',
        'contractor_nit',
        'contract_object',
        'contract_type_id',
        'sede_id',
        'start_date',
        'initial_end_date',
        'extension_date',
        'initial_value',
        'addition_value',
    ];

    public function hiringModality()
    {
        return $this->belongsTo(HiringModality::class, 'hiring_modality_id');
    }

    public function contractType()
    {
        return $this->belongsTo(ContractType::class, 'contract_type_id');
    }

    // Acceso indirecto a dependencia
    public function dependencia()
    {
        return $this->hasOneThrough(
            Dependencia::class,
            ContractType::class,
            'id', // ContractType -> id
            'id', // Dependencia -> id
            'contract_type_id', // Contract -> contract_type_id
            'dependencia_id' // ContractType -> dependencia_id
        );
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }
}
