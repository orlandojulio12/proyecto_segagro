<?php

namespace App\Models\Contract;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HiringModality extends Model
{
    use HasFactory;

    protected $fillable = [
        'modality_name',
        'description',
    ];

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }
}
