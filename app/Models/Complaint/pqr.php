<?php

namespace App\Models\Complaint;

use App\Models\Dependency\DependencySubunit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class pqr extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'date',
        'description',
        'responsible',
        'dependency',
        'pdf_path',
        'user_id',
        'state'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * Relación con el usuario que creó la PQR
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDaysPassedAttribute()
    {
        return Carbon::parse($this->date)->diffInDays(Carbon::now());
    }

    public function getDaysRemainingAttribute()
    {
        $maxDias = 12;
        $diasTranscurridos = $this->days_passed;

        return max(0, $maxDias - $diasTranscurridos);
    }

    public function getColorStatusAttribute()
    {
        $dias = $this->days_remaining;

        if ($dias >= 6) {
            return '#4CAF50';      // Verde → más de 6 días disponibles
        } elseif ($dias >= 2) {
            return '#FFC107';      // Amarillo → entre 2 y 5 días restantes
        } elseif ($dias >= 1) {
            return '#F44336';      // Rojo → 1 día restante
        } else {
            return '#B71C1C';      // Rojo oscuro → vencido
        }
    }
    public function getStatusTextAttribute()
    {
        $dias = $this->days_remaining;

        if ($dias >= 6) {
            return 'En tiempo';
        } elseif ($dias >= 2) {
            return 'Por vencer';
        } elseif ($dias >= 1) {
            return 'Urgente';
        } else {
            return 'Vencido';
        }
    }

    public function getIsExpiredAttribute()
    {
        return $this->days_remaining === 0;
    }

    public function getDeadlineDateAttribute()
    {
        return Carbon::parse($this->date)->addDays(12);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->get()->filter(function ($pqr) use ($status) {
            return match ($status) {
                'en_tiempo' => $pqr->days_remaining >= 6,
                'por_vencer' => $pqr->days_remaining >= 2 && $pqr->days_remaining < 6,
                'urgente' => $pqr->days_remaining >= 1 && $pqr->days_remaining < 2,
                'vencido' => $pqr->days_remaining === 0,
                default => true
            };
        });
    }

    public function scopeExpired($query)
    {
        return $query->get()->filter(function ($pqr) {
            return $pqr->is_expired;
        });
    }

    public function subunit()
    {
        return $this->belongsTo(DependencySubunit::class, 'dependency', 'subunit_id');
    }

    public function getStateTextAttribute()
    {
        return $this->state ? 'Completada' : 'Pendiente';
    }
}
