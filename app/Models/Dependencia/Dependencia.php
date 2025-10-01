<?php

namespace App\Models\Dependencia;

use App\Models\Infraestructura\Infraestructura;
use App\Models\Traslado\NeedTransfer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dependencia extends Model
{
    use HasFactory;

    protected $table = 'dependencias';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    // Relaciones
    public function infraestructuras()
    {
        return $this->hasMany(Infraestructura::class);
    }

    public function traslados()
    {
        return $this->hasMany(NeedTransfer::class);
    }
}
