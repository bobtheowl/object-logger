<?php

namespace ObjectLogger\Laravel;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use Monolog\Logger as MonologLogger;
use Psr\Log\LoggerInterface;

/**
 * Class ObjectLoggerServiceProvider
 * @package ObjectLogger\Laravel
 */
class ObjectLoggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }//end boot()

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(LoggerInterface::class, function ($app) {
            return new Writer(
                $app->make(MonologLogger::class),
                $app->make(Dispatcher::class)
            );
        });
    }//end register()
}//end class ObjectLoggerServiceProvider
