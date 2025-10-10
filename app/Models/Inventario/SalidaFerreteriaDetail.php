<?php

namespace App\Models\Inventario;

use App\Models\InventoryMaterial;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalidaFerreteriaDetail extends Model
{
    use HasFactory;

    protected $table = 'salida_ferreteria_details';

    protected $fillable = [
        'salida_ferreteria_id',
        'inventory_material_id',
        'cantidad',
        'observacion',
    ];

    public function salida()
    {
        return $this->belongsTo(SalidaFerreteria::class, 'salida_ferreteria_id');
    }

    public function material()
    {
        return $this->belongsTo(InventoryMaterial::class, 'inventory_material_id');
    }
}
