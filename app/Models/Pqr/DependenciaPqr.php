<?php

namespace App\Models\Pqr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DependenciaPqr extends Model
{
    use HasFactory;

    protected $table = 'dependencia_pqr';
    protected $primaryKey = 'id_dependencia';
    protected $fillable = ['name'];

    public function conceptos()
    {
        return $this->hasMany(ConceptoPqr::class, 'dependencia_id', 'id_dependencia');
    }
}