<?php

namespace App\Providers;

use App\Models\Complaint\Pqr;
use App\Models\Infraestructura\Infraestructura;
use App\Policies\InfraestructuraPolicy;
use App\Policies\PqrPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        Gate::policy(Pqr::class, PqrPolicy::class);
        Gate::policy(Infraestructura::class, InfraestructuraPolicy::class);
    }
}
