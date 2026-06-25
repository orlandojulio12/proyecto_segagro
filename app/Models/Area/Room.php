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

    public const TIPOS = [
        'aula_clase'       => 'Aula de Clase',
        'laboratorio'      => 'Laboratorio',
        'taller'           => 'Taller',
        'sala_sistemas'    => 'Sala de Sistemas',
        'auditorio'        => 'Auditorio',
        'biblioteca'       => 'Biblioteca',
        'campo_practicas'  => 'Campo de Prácticas',
        'bienestar'        => 'Bienestar al Aprendiz',
        'sala_conferencias'=> 'Sala de Conferencias',
        'comedor'          => 'Comedor',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
