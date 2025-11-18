<?php

namespace App\Models\Contract;

use App\Models\Dependencia\Dependencia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Contract\Contract;

class ContractType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_name',
        'description',
        'dependencia_id',
    ];

    /**
     * Relación con la dependencia
     */
    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class);
    }

    /**
     * Relación con los contratos
     */
    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * Scope para búsqueda
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('type_name', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%");
    }

    /**
     * Scope por dependencia
     */
    public function scopeByDependencia($query, $dependenciaId)
    {
        return $query->where('dependencia_id', $dependenciaId);
    }
}