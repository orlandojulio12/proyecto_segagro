<?php

namespace App\Models\Area;

use App\Models\Sede;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = [
        'name',
        'sede_id',
        'code',
        'description',
        'active'
    ];

     public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}