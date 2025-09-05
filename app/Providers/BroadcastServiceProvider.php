<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // ⭐ ESTA ES LA CONFIGURACIÓN CORRECTA
        Broadcast::routes(['middleware' => ['web', 'auth']]);

        require base_path('routes/channels.php');
    }
}
