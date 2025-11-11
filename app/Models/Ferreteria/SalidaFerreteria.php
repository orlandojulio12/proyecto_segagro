<?php
// app/Models/SalidaFerreteria.php


namespace App\Models\Ferreteria;
use App\Models\User;
use App\Models\Centro;
use App\Models\Sede;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SalidaFerreteria extends Model
{
    use HasFactory;

    protected $table = 'salida_ferreteria';

    protected $fillable = [
        'user_id',
        'centro_id',
        'sede_id',
        'observaciones',
        'fecha_salida',
        'f14',
    ];

    protected $casts = [
        'fecha_salida' => 'date',
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function centro()
    {
        return $this->belongsTo(Centro::class);
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function detalles()
    {
        return $this->hasMany(SalidaFerreteriaDetail::class);
    }
}