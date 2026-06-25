<?php

namespace App\Notifications;

use App\Models\Complaint\Pqr;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PqrExpirationNotification extends Notification
{
    use Queueable;

    public function __construct(public Pqr $pqr) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $tipo = $this->pqr->is_tutela ? 'Tutela' : 'PQR';
        $tiempoRestante = $this->pqr->is_tutela
            ? $this->pqr->days_remaining . ' horas'
            : $this->pqr->days_remaining . ' días';

        return [
            'pqr_id'          => $this->pqr->id,
            'title'           => $this->pqr->title,
            'tipo'            => $tipo,
            'tiempo_restante' => $tiempoRestante,
            'color'           => $this->pqr->color_status,
            'deadline'        => $this->pqr->deadline_date->format('d/m/Y H:i'),
            'mensaje'         => "La {$tipo} \"{$this->pqr->title}\" vence en {$tiempoRestante}.",
        ];
    }
}
