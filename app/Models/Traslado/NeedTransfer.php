<?php

namespace App\Models\Traslado;

use App\Models\Centro;
use App\Models\InventoryMaterial;
use App\Models\InventorySede;
use App\Models\Sede;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class NeedTransfer extends Model
{
    protected $fillable = [
        'user_id',
        'dependencia_id',
        'centro_inicial_id',
        'sede_inicial_id',
        'centro_final_id',
        'sede_final_id',
        'fecha_inicio',
        'fecha_fin',
        'descripcion',
        'nivel_riesgo',
        'nivel_complejidad',
        'presupuesto_solicitado',
        'presupuesto_aceptado',
        'requiere_personal',
        'requiere_materiales'
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dependencia()
    {
        return $this->belongsTo(InventorySede::class, 'dependencia_id');
    }

    public function centroInicial()
    {
        return $this->belongsTo(Centro::class, 'centro_inicial_id');
    }

    public function sedeInicial()
    {
        return $this->belongsTo(Sede::class, 'sede_inicial_id');
    }

    public function centroFinal()
    {
        return $this->belongsTo(Centro::class, 'centro_final_id');
    }

    public function sedeFinal()
    {
        return $this->belongsTo(Sede::class, 'sede_final_id');
    }

    public function personal()
    {
        return $this->belongsToMany(User::class, 'need_transfer_user')
            ->withPivot('cargo');
    }

    public function materiales()
    {
        return $this->belongsToMany(InventoryMaterial::class, 'need_transfer_material')
            ->withPivot('cantidad', 'tipo');
    }

      protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin'    => 'date',
    ];
}
