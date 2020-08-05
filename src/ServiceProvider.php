<?php

namespace Johnnestebann\Langravel;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\Routing\UrlGenerator as LaravelUrlGenerator;

class ServiceProvider extends LaravelServiceProvider
{
    public function boot()
    {
        $this->app['router']->aliasMiddleware('langravel', Middleware::class);

        $this->publishes([
            __DIR__ . '/../config/langravel.php' => config_path('langravel.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../routes/langravel.web.php' => base_path('routes/langravel.web.php'),
        ], 'route');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/langravel.php',
            'langravel'
        );

        $this->app->extend(LaravelUrlGenerator::class, function ($generator) {
            return new UrlGenerator($this->app['router']->getRoutes(), $generator->getRequest());
        });
    }
}
