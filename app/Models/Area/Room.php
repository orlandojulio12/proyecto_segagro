<?php

namespace App\Models\Area;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'area_id',
        'name',
        'code',
        'capacity',
        'type',
        'active',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
