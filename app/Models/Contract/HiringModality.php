<?php

namespace App\Models\Contract;

use App\Models\Contract\Contract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class HiringModality extends Model
{
    use HasFactory;

    protected $fillable = [
        'modality_name',
        'description',
    ];

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
        return $query->where('modality_name', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%");
    }
}