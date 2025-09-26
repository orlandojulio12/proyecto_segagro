<?php

namespace App\Models\Inventario;

use App\Models\InventorySede;
use App\Models\User;
use App\Models\Centro;
use App\Models\Sede;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semoviente extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_sede_id',
        'responsible_department',
        'staff_id',
        'centro_id',
        'sede_id',
        'birth_date',
        'birth_time',
        'image',
        'birth_area',
        'training_environment',
        'gender',
        'birth_type',
        'animal_type',
        'breed',
        'weight',
        'color',
        'mother_package',
        'approx_value',
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'birth_time' => 'datetime:H:i',
        'approx_value' => 'decimal:2',
    ];

    // Relaciones
    public function inventorySede()
    {
        return $this->belongsTo(InventorySede::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function centro()
    {
        return $this->belongsTo(Centro::class, 'centro_id');
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }

    public function getEstadoTextoAttribute()
{
    return match ($this->estado) {
        1 => 'En venta',
        2 => 'Vivo',
        3 => 'Muerto',
        4 => 'Sacrificio',
        default => 'Desconocido',
    };
}

public function getEstadoColorAttribute()
{
    return match ($this->estado) {
        1 => 'success',   // verde
        2 => 'primary',   // azul
        3 => 'danger',    // rojo
        4 => 'warning',   // amarillo
        default => 'secondary',
    };
}
}
