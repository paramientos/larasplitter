<?php

namespace Soysaltan\LaraSplitter;

use Illuminate\Support\ServiceProvider;
use Soysaltan\LaraSplitter\Console\CreateApiFileCommand;

class Provider extends ServiceProvider
{
    const VERSION = '1.0.1';
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/Config/larasplitter.php' => config_path('larasplitter.php'),
        ], 'money');

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateApiFileCommand::class
            ]);
        }

        $this->registerProviders();
        $this->mergeConfigFrom(__DIR__ . '/Config/larasplitter.php', 'money');
    }

    private function registerProviders()
    {
        foreach (glob(base_path('app/Providers/SplitApi*')) as $file) {
            $className = basename($file, '.php');
            $this->app->register("\App\Providers\\$className");
        }
    }
}
