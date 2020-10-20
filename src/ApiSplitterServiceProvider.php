<?php

namespace Soysaltan\ApiSplitter;

use Illuminate\Support\ServiceProvider;
use Soysaltan\ApiSplitter\Console\CreateApiFileCommand;

class ApiSplitterServiceProvider extends ServiceProvider
{
    const VERSION = '1.0.7';
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
            __DIR__ . '/Config/money.php' => config_path('money.php'),
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

        $this->registerSplittedApiServiceProviders();
    }

    private function registerSplittedApiServiceProviders()
    {
        foreach (glob(base_path('app/Providers/SplitApi*')) as $file) {
            $className = basename($file, '.php');
            $this->app->register("\App\Providers\\$className");
        }
    }
}
