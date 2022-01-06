<?php

namespace Gelaku\Weather;

use Gelaku\Weather\Weather;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    // 延迟注册
    protected $defer = true;

    /**
     * 注入天气类
     */
    public function register()
    {
        $this->app->singleton(Weather::class, function () {
            return new Weather(config('services.weather.key'));
        });

        $this->app->alias(Weather::class, 'weather');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Weather::class, 'weather'];
    }
}