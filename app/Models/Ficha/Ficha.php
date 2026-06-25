<?php

namespace App\Models\Ficha;

use App\Models\Centro;
use App\Models\Instructor\Instructor;
use App\Models\Sede;
use Illuminate\Database\Eloquent\Model;

class Ficha extends Model
{
    protected $fillable = [
        'numero_ficha', 'nombre_programa', 'nivel_formacion', 'modalidad',
        'estado', 'jornada', 'centro_id', 'sede_id', 'instructor_id',
        'fecha_inicio', 'fecha_fin', 'numero_aprendices',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin'    => 'date',
    ];

    public const NIVELES = [
        'tecnico'                     => 'Técnico',
        'tecnologo'                   => 'Tecnólogo',
        'especializacion_tecnologica' => 'Especialización Tecnológica',
        'auxiliar'                    => 'Auxiliar',
        'operario'                    => 'Operario',
        'curso_complementario'        => 'Curso Complementario',
    ];

    public const ESTADOS = [
        'en_convocatoria'    => ['label' => 'En Convocatoria',    'badge' => 'sg-badge-blue'],
        'en_formacion'       => ['label' => 'En Formación',       'badge' => 'sg-badge-green'],
        'en_etapa_productiva'=> ['label' => 'Etapa Productiva',   'badge' => 'sg-badge-yellow'],
        'certificado'        => ['label' => 'Certificado',        'badge' => 'sg-badge-gray'],
        'cancelado'          => ['label' => 'Cancelado',          'badge' => 'sg-badge-red'],
    ];

    public const JORNADAS = [
        'diurna'       => 'Diurna',
        'nocturna'     => 'Nocturna',
        'madrugada'    => 'Madrugada',
        'fin_de_semana'=> 'Fin de Semana',
    ];

    public const MODALIDADES = [
        'presencial' => 'Presencial',
        'virtual'    => 'Virtual',
        'mixta'      => 'Mixta',
    ];

    public function centro()
    {
        return $this->belongsTo(Centro::class);
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }

    public function horarios()
    {
        return $this->hasMany(\App\Models\Horario\Horario::class);
    }
}
