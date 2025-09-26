<?php

namespace App\Models\User;

use App\Models\Centro;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCentro extends Model
{
    use HasFactory;

    protected $table = 'user_centros';
    protected $primaryKey = 'user_centro_id';

    protected $fillable = [
        'user_id',
        'centro_id',
    ];

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con el centro
    public function centro()
    {
        return $this->belongsTo(Centro::class, 'centro_id');
    }
}
