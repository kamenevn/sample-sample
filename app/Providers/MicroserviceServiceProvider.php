<?php

namespace App\Providers;

use App\Services\Microservices\HttpClient;

use App\Services\Microservices\Sample\SampleApiService;
use App\Services\Microservices\Sample\SampleServiceInterface;

use Illuminate\Support\ServiceProvider;

class MicroserviceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SampleServiceInterface::class, static function () {
            return new SampleApiService(
                new HttpClient(config('smarthttp')),
                config('microservices.sample')
            );
        });
    }
}
