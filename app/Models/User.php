<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use App\Traits\AuditableModel;

class User extends Authenticatable implements AuditableContract
{
     use HasFactory, Notifiable, HasRoles, AuditableModel;

    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'phone',
        'registration_date',
        'state',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
public function sedes() {
    return $this->belongsToMany(Sede::class, 'user_sedes');
}
   
}
