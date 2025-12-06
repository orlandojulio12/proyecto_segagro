<?php

namespace App\Models\Dependency;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DependencySubunit extends Model
{
    use HasFactory;

    protected $primaryKey = 'subunit_id';

    protected $fillable = [
        'dependency_unit_id',
        'subunit_code',
        'name',
        'description'
    ];

    public function dependencyUnit()
    {
        return $this->belongsTo(DependencyUnit::class, 'dependency_unit_id');
    }
}