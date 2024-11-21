<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Pusher\Pusher;

class WebSocketServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('pusher', function ($app) {
            $config = config('broadcasting.connections.pusher');
            
            return new Pusher(
                $config['key'],
                $config['secret'],
                $config['app_id'],
                $config['options'] ?? []
            );
        });
    }

    public function boot()
    {
        
    }
}
