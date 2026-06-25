<?php

namespace App\Models\Horario;

use App\Models\Area\Room;
use App\Models\Ficha\Ficha;
use App\Models\Instructor\Instructor;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $fillable = [
        'ficha_id', 'room_id', 'dia_semana', 'hora_inicio', 'hora_fin',
        'competencia', 'instructor_id', 'color', 'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public const DIAS = [
        'lunes'     => 'Lunes',
        'martes'    => 'Martes',
        'miercoles' => 'Miércoles',
        'jueves'    => 'Jueves',
        'viernes'   => 'Viernes',
        'sabado'    => 'Sábado',
    ];

    public const COLORES = [
        '#16a34a', '#2563eb', '#dc2626', '#d97706',
        '#7c3aed', '#0891b2', '#be185d', '#65a30d',
    ];

    public function ficha()
    {
        return $this->belongsTo(Ficha::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }
}
