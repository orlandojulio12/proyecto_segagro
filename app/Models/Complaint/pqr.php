<?php

namespace App\Models\Complaint;

use App\Models\Dependency\DependencySubunit;
use App\Models\Pqr\ConceptoPqr;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Pqr extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'date',
        'description',
        'responsible',
        'concepto_id',
        'pdf_path',
        'user_id',
        'state',
        'is_tutela',
        'horas_tutela',
    ];

    protected $casts = [
        'date' => 'datetime',
        'is_tutela' => 'boolean',
        'state' => 'boolean',
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function concepto()
    {
        return $this->belongsTo(ConceptoPqr::class, 'concepto_id', 'id_concepto');
    }

    public function dependencia()
    {
        return $this->concepto ? $this->concepto->dependencia() : null;
    }

    /* public function subunit()
    {
        return $this->belongsTo(DependencySubunit::class, 'dependency', 'subunit_id');
    } */

    // Accesores para días y estado
    public function getDaysPassedAttribute()
    {
        return Carbon::parse($this->date)->diffInDays(Carbon::now());
    }

    public function getDaysRemainingAttribute()
    {
        if ($this->is_tutela) {
            $maxHoras = $this->horas_tutela ?? 72; // si no hay dato, usar 72 por defecto
            $horasTranscurridas = Carbon::parse($this->date)
                ->setTimezone(config('app.timezone'))
                ->diffInHours(now());

            return max(0, $maxHoras - $horasTranscurridas);
        }

        return max(0, 12 - $this->days_passed);
    }

    public function getColorStatusAttribute()
    {
        if ($this->is_tutela) {

            $horas = $this->days_remaining;

            if ($horas >= 48) return '#4CAF50';   // verde
            if ($horas >= 24) return '#FFC107';   // amarillo
            if ($horas >= 1) return '#F44336';    // rojo
            return '#B71C1C';                     // vencido
        }

        // normal
        $dias = $this->days_remaining;

        if ($dias >= 6) return '#4CAF50';
        if ($dias >= 2) return '#FFC107';
        if ($dias >= 1) return '#F44336';
        return '#B71C1C';
    }

    public function getStatusTextAttribute()
    {
        if ($this->is_tutela) {

            $horas = $this->days_remaining;

            if ($horas >= 48) return 'En tiempo';
            if ($horas >= 24) return 'Por vencer';
            if ($horas >= 1) return 'Urgente';
            return 'Vencido';
        }

        $dias = $this->days_remaining;

        if ($dias >= 6) return 'En tiempo';
        if ($dias >= 2) return 'Por vencer';
        if ($dias >= 1) return 'Urgente';
        return 'Vencido';
    }

    public function getIsExpiredAttribute()
    {
        return $this->days_remaining === 0;
    }

    public function getDeadlineDateAttribute()
    {
        if ($this->is_tutela) {
            $horas = $this->horas_tutela ?? 72;
            return Carbon::parse($this->date)->addHours($horas);
        }

        return Carbon::parse($this->date)->addDays(12);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->get()->filter(fn($pqr) => match ($status) {
            'en_tiempo' => $pqr->days_remaining >= 6,
            'por_vencer' => $pqr->days_remaining >= 2 && $pqr->days_remaining < 6,
            'urgente' => $pqr->days_remaining >= 1 && $pqr->days_remaining < 2,
            'vencido' => $pqr->days_remaining === 0,
            default => true
        });
    }

    public function scopeExpired($query)
    {
        return $query->get()->filter(fn($pqr) => $pqr->is_expired);
    }

    public function scopeTutelas($query)
    {
        return $query->where('is_tutela', true);
    }

    public function scopeByUrgency($query)
    {
        return $query->get()->sortBy(fn($pqr) => $pqr->days_remaining);
    }

    public function getTimeRemainingAttribute()
    {
        if ($this->is_tutela) {
            return $this->days_remaining; // horas
        }

        return $this->days_remaining * 24; // convertir días a horas
    }

    public function getTimeUnitAttribute()
    {
        return $this->is_tutela ? 'horas' : 'días';
    }

    public function getTimeFormattedAttribute()
    {
        if ($this->is_tutela) {
            return intval($this->days_remaining) . 'h';
        }

        return intval($this->days_remaining) . 'd';
    }
}
