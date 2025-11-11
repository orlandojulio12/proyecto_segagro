<?php
// app/Models/Ferreteria/SalidaFerreteriaDetail.php

namespace App\Models\Ferreteria;
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

    // Relaciones
    public function salidaFerreteria()
    {
        return $this->belongsTo(SalidaFerreteria::class);
    }

    public function material()
    {
        return $this->belongsTo(InventoryMaterial::class, 'inventory_material_id');
    }
}