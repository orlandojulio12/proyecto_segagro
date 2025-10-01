<?php

namespace App\Models\Infraestructura;

use App\Models\Centro;
use App\Models\Dependencia\Dependencia;
use App\Models\Sede;
use App\Models\Traslado\NeedTransfer;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Infraestructura extends Model
{
    protected $fillable = [
        'dependencia_id',
        'user_id',
        'centro_id',
        'sede_id',
        'nivel_riesgo',
        'tipo_necesidad',
        'area_necesidad',
        'nivel_complejidad',
        'descripcion',
        'motivo_necesidad',
        'requiere_traslado',
        'centro_final_id',
        'sede_final_id',
        'fuente_financiacion',
        'personal',
        'ambiente',
        'imagen',
        'fecha_inicio',
        'fecha_fin',
        'nivel_prioridad',
        'presupuesto_solicitado',
        'presupuesto_aceptado',
    ];


    protected $casts = [
        'personal' => 'array',
        'requiere_traslado' => 'boolean',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class);
    }

    public function funcionario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function centro()
    {
        return $this->belongsTo(Centro::class);
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function needTransfers()
    {
        return $this->belongsToMany(
            NeedTransfer::class,
            'infraestructura_need_transfer',
            'infraestructura_id',
            'need_transfer_id'
        )->withTimestamps();
    }
}
