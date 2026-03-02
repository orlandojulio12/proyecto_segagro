<?php

namespace App\Models\Dependency;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DependencyUnit extends Model
{
    use HasFactory;

    protected $primaryKey = 'dependency_unit_id';

    protected $fillable = [
        'short_name',
        'full_name',
        'description'
    ];

    public function subunits()
    {
        return $this->hasMany(DependencySubunit::class, 'dependency_unit_id');
    }
}
