<?php

namespace App\Models\Pqr;

use App\Models\Complaint\Pqr;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConceptoPqr extends Model
{
    use HasFactory;

    protected $table = 'concepto_pqr';
    protected $primaryKey = 'id_concepto';
    protected $fillable = ['name', 'dependencia_id'];

    public function dependencia()
    {
        return $this->belongsTo(DependenciaPqr::class, 'dependencia_id', 'id_dependencia');
    }

    public function pqrs()
    {
        return $this->hasMany(Pqr::class, 'concepto_id', 'id_concepto');
    }
}
