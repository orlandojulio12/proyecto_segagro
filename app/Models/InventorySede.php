<?php
// app/Models/InventorySede.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventorySede extends Model
{
    use HasFactory;

    protected $table = 'inventory_sede';
    public $timestamps = false;

    protected $fillable = [
        'sede_id',
        'responsible_department',
        'staff_name',
        'image_inventory',
        'inventory_description',
        'record_date'
    ];

    protected $casts = [
        'record_date' => 'datetime'
    ];

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_name', 'id');
    }

    public function materials()
    {
        return $this->hasMany(InventoryMaterial::class, 'inventory_id');
    }
}