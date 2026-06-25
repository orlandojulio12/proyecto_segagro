<?php

namespace App\Models\Instructor;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    protected $table = 'instructores';

    protected $fillable = [
        'nombre', 'apellido', 'documento', 'email',
        'telefono', 'especialidad', 'tipo_contrato', 'activo',
    ];

    public const TIPOS_CONTRATO = [
        'planta'       => 'Planta',
        'contrato'     => 'Contrato',
        'hora_catedra' => 'Hora Cátedra',
    ];

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    public function fichas()
    {
        return $this->hasMany(\App\Models\Ficha\Ficha::class, 'instructor_id');
    }

    public function horarios()
    {
        return $this->hasMany(\App\Models\Horario\Horario::class, 'instructor_id');
    }
}
