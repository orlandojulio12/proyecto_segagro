<?php

namespace App\Models\Contract;
use App\Models\Centro;
use App\Models\Contract\ContractType;
use App\Models\Dependencia;
use App\Models\Contract\HiringModality;
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

    protected $casts = [
        'start_date' => 'date',
        'initial_end_date' => 'date',
        'extension_date' => 'date',
        'initial_value' => 'decimal:2',
        'addition_value' => 'decimal:2',
    ];

    /**
     * Relación con la modalidad de contratación
     */
    public function hiringModality()
    {
        return $this->belongsTo(HiringModality::class);
    }

    /**
     * Relación con el tipo de contrato
     */
    public function contractType()
    {
        return $this->belongsTo(ContractType::class);
    }

    /**
     * Relación con la sede
     */
    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    /**
     * Relación con el centro (a través de sede)
     */
    public function centro()
    {
        return $this->hasOneThrough(
            Centro::class,
            Sede::class,
            'id',
            'id',
            'sede_id',
            'centro_id'
        );
    }

    /**
     * Relación con la dependencia (a través de contract_type)
     */
    public function dependencia()
    {
        return $this->hasOneThrough(
            Dependencia\Dependencia::class,
            ContractType::class,
            'id',
            'id',
            'contract_type_id',
            'dependencia_id'
        );
    }

    /**
     * Calcular el valor total del contrato
     */
    public function getTotalValueAttribute()
    {
        return $this->initial_value + ($this->addition_value ?? 0);
    }

    /**
     * Obtener la fecha final del contrato
     */
    public function getFinalEndDateAttribute()
    {
        return $this->extension_date ?? $this->initial_end_date;
    }

    /**
     * Verificar si el contrato está activo
     */
    public function getIsActiveAttribute()
    {
        $today = now();
        $endDate = $this->extension_date ?? $this->initial_end_date;
        
        return $this->start_date <= $today && $endDate >= $today;
    }

    /**
     * Verificar si el contrato está vencido
     */
    public function getIsExpiredAttribute()
    {
        $endDate = $this->extension_date ?? $this->initial_end_date;
        return $endDate < now();
    }

    /**
     * Verificar si está pendiente de iniciar
     */
    public function getIsPendingAttribute()
    {
        return $this->start_date > now();
    }

    /**
     * Obtener el estado del contrato
     */
    public function getStatusAttribute()
    {
        if ($this->is_pending) return 'Pendiente';
        if ($this->is_active) return 'Activo';
        if ($this->is_expired) return 'Vencido';
        return 'Desconocido';
    }

    /**
     * Clase de badge según estado
     */
    public function getStatusBadgeClassAttribute()
    {
        if ($this->is_pending) return 'bg-warning';
        if ($this->is_active) return 'bg-success';
        if ($this->is_expired) return 'bg-danger';
        return 'bg-secondary';
    }

    /**
     * Días restantes del contrato
     */
    public function getDaysRemainingAttribute()
    {
        $endDate = $this->extension_date ?? $this->initial_end_date;
        $today = now();
        
        if ($endDate < $today) return 0;
        
        return $today->diffInDays($endDate);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('start_date', '<=', now())
            ->where(function ($q) {
                $q->whereNull('extension_date')
                    ->where('initial_end_date', '>=', now())
                    ->orWhere('extension_date', '>=', now());
            });
    }

    public function scopeExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('extension_date')
                ->where('initial_end_date', '<', now())
                ->orWhere('extension_date', '<', now());
        });
    }

    public function scopePending($query)
    {
        return $query->where('start_date', '>', now());
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('contract_number', 'like', "%{$search}%")
                ->orWhere('contractor_name', 'like', "%{$search}%")
                ->orWhere('contractor_nit', 'like', "%{$search}%")
                ->orWhere('contract_object', 'like', "%{$search}%");
        });
    }
}