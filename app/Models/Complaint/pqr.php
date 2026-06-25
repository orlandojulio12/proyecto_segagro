<?php

namespace App\Models\Complaint;

use App\Models\Dependency\DependencySubunit;
use App\Models\Pqr\ConceptoPqr;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Pqr extends Model
{
    use HasFactory, SoftDeletes;

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

        return max(0, 10 - $this->days_passed);
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

        return Carbon::parse($this->date)->addDays(10);
    }

    // Scopes — usan SQL para permitir paginación y encadenamiento
    public function scopeByStatus($query, $status)
    {
        return match($status) {
            'en_tiempo' => $query->whereRaw("
                (is_tutela = 0 AND DATEDIFF(NOW(), date) <= 4)
                OR (is_tutela = 1 AND TIMESTAMPDIFF(HOUR, date, NOW()) < 24)
            "),
            'por_vencer' => $query->whereRaw("
                (is_tutela = 0 AND DATEDIFF(NOW(), date) BETWEEN 5 AND 8)
                OR (is_tutela = 1 AND TIMESTAMPDIFF(HOUR, date, NOW()) BETWEEN 24 AND 48)
            "),
            'urgente' => $query->whereRaw("
                (is_tutela = 0 AND DATEDIFF(NOW(), date) = 9)
                OR (is_tutela = 1 AND TIMESTAMPDIFF(HOUR, date, NOW()) BETWEEN 48 AND 72)
            "),
            'vencido' => $query->whereRaw("
                (is_tutela = 0 AND DATEDIFF(NOW(), date) >= 10)
                OR (is_tutela = 1 AND TIMESTAMPDIFF(HOUR, date, NOW()) >= 72)
            "),
            default => $query,
        };
    }

    public function scopeExpired($query)
    {
        return $query->whereRaw("
            (is_tutela = 0 AND DATEDIFF(NOW(), date) >= 10)
            OR (is_tutela = 1 AND TIMESTAMPDIFF(HOUR, date, NOW()) >= 72)
        ");
    }

    public function scopeTutelas($query)
    {
        return $query->where('is_tutela', true);
    }

    public function scopeByUrgency($query)
    {
        return $query->orderByRaw("
            CASE
                WHEN is_tutela = 1
                    THEN (COALESCE(horas_tutela, 72) - TIMESTAMPDIFF(HOUR, date, NOW()))
                ELSE (10 - DATEDIFF(NOW(), date))
            END ASC
        ");
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
