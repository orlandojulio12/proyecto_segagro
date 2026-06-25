<?php

use App\Jobs\SendPqrExpirationAlerts;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Alerta diaria a las 8am: PQRs y tutelas próximas a vencer
Schedule::job(new SendPqrExpirationAlerts)->dailyAt('08:00');
