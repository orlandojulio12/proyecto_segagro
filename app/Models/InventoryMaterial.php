<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMaterial extends Model
{
    use HasFactory;

    protected $table = 'inventory_materials';

    protected $fillable = [
        'inventory_id',
        'material_name',
        'material_quantity',
        'material_type',
        'material_brand',     // NUEVO
        'material_model',     // NUEVO
        'material_serial',    // NUEVO
        'material_price',
        'iva_percentage',
        'total_without_tax',
        'total_with_tax',
        'observations',
    ];

    public function inventory()
    {
        return $this->belongsTo(InventorySede::class, 'inventory_id');
    }
}
