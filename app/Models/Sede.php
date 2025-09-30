<?php
// app/Models/Sede.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_sede',
        'centro_id',
        'matricula_inmobiliario',
        'barrio_sede',
        'direc_sede',
        'localidad',
        'img_sede',
        'fecha_reg_sede',
        'descripcion'
    ];

    protected $casts = [
        'fecha_reg_sede' => 'datetime'
    ];

    public function centro()
    {
        return $this->belongsTo(Centro::class);
    }
    public function users() {
        return $this->belongsToMany(User::class, 'user_sedes');
    }
}