<?php
// app/Models/Centro.php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Centro extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_centro',
        'id_municipio',
        'barrio_centro',
        'direc_centro',
        'img_centro',
        'fecha_reg_centro',
        'extension',
        'id_regional',
        'departamento'
    ];

    protected $dates = ['fecha_reg_centro'];

    public function sedes()
    {
        return $this->hasMany(Sede::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_centros', 'centro_id', 'user_id');
    }
}
