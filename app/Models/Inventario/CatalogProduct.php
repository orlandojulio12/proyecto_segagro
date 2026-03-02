<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogProduct extends Model
{
    use HasFactory;

    protected $table = 'catalog_products';

    protected $fillable = [
        'type_code',
        'type_name',
        'segment',
        'segment_description',
        'family_code',
        'family_name',
        'class_code',
        'class_name',
        'useful_life',
        'sku',
        'sku_description',
        'consecutive',
        'element_description',
        'type_catalogo', // nuevo
    ];
    
}
