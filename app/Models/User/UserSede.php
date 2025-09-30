<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSede extends Model
{
    use HasFactory;

    protected $table = 'user_sedes';

    protected $fillable = [
        'user_id',
        'sede_id',
    ];

    /**
     * Relación con User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con Sede
     */
    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }
}