<?php

namespace App\Jobs;

use App\Models\Complaint\Pqr;
use App\Notifications\PqrExpirationNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPqrExpirationAlerts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        // PQRs normales con 2 o menos días restantes y no finalizadas
        $pqrsProximas = Pqr::where('state', 0)
            ->where('is_tutela', 0)
            ->whereRaw('DATEDIFF(NOW(), date) >= 8')
            ->whereRaw('DATEDIFF(NOW(), date) < 10')
            ->with('user')
            ->get();

        // Tutelas próximas a vencer (24 horas o menos)
        $tutelasProximas = Pqr::where('state', 0)
            ->where('is_tutela', 1)
            ->whereRaw('TIMESTAMPDIFF(HOUR, date, NOW()) >= (COALESCE(horas_tutela, 72) - 24)')
            ->whereRaw('TIMESTAMPDIFF(HOUR, date, NOW()) < COALESCE(horas_tutela, 72)')
            ->with('user')
            ->get();

        $todasProximas = $pqrsProximas->merge($tutelasProximas);

        foreach ($todasProximas as $pqr) {
            if (!$pqr->user) {
                continue;
            }

            // Notificación in-app (base de datos)
            $pqr->user->notify(new PqrExpirationNotification($pqr));

            // Correo electrónico
            if (!$pqr->user->email) {
                continue;
            }

            $tipo = $pqr->is_tutela ? 'Tutela' : 'PQR';
            $tiempoRestante = $pqr->is_tutela
                ? $pqr->days_remaining . ' horas'
                : $pqr->days_remaining . ' días';

            Mail::send([], [], function ($message) use ($pqr, $tipo, $tiempoRestante) {
                $message
                    ->to($pqr->user->email, $pqr->user->name)
                    ->subject("[SEGAGRO] {$tipo} por vencer: {$pqr->title}")
                    ->html(
                        "<h3>Alerta de vencimiento</h3>" .
                        "<p>La {$tipo} <strong>{$pqr->title}</strong> vence en <strong>{$tiempoRestante}</strong>.</p>" .
                        "<p>Fecha límite: <strong>{$pqr->deadline_date->format('d/m/Y H:i')}</strong></p>" .
                        "<p>Por favor gestione a la brevedad.</p>"
                    );
            });
        }
    }
}
