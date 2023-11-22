<?php

namespace VnsAgency\FrontAppMerger;

use Illuminate\Support\ServiceProvider;
use VnsAgency\FrontAppMerger\Console\FrontAppMergerCleanUpCommand;
use VnsAgency\FrontAppMerger\Console\FrontAppMergerCommand;

class FrontAppMergerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            // Registering package commands.
             $this->commands([
                 FrontAppMergerCommand::class,
                 FrontAppMergerCleanUpCommand::class
             ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Register the main class to use with the facade
        $this->app->singleton('FrontAppMerger', function () {
            return new FrontAppMerger;
        });
    }
}
