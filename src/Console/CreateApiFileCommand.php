<?php

namespace Soysaltan\ApiSplitter\Console;

use App\Constants\Roles;
use App\Helpers\FileHelper;
use App\Helpers\StringHelper;
use App\User;
use Carbon\Carbon;
use Hash;
use Illuminate\Console\Command;
use Validator;

class CreateApiFileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spl:it';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make splitted api file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->ask("Please enter a api file name (the filename will be saved with '.api' suffix) : ");
        $endpoint = $this->ask('Please enter an endpoint name : ');

        $validator = Validator::make([
            'name' => $name,
            'endpoint' => $endpoint
        ], [
            'name' => ['required'],
            'endpoint' => ['required'],
        ]);

        if ($validator->fails()) {
            $this->error('Please enter a valid values !');
            $this->handle();
        }

        $apiFileRealPath = base_path("routes/$name.api.php");

        if (file_exists($apiFileRealPath)) {
            $this->error("A file named '$name' already exists in the '" . base_path('routes') . "' folder");
            $this->handle();
        }

        if (!file_exists($apiFileRealPath)) {
            touch($apiFileRealPath);
        }

        $nameUcFirst = ucfirst($name);
        $providerFileRealPath = base_path("app/Providers/SplitApi{$nameUcFirst}ServiceProvider.php");

        if (!file_exists($providerFileRealPath)) {
            touch($providerFileRealPath);
        }

        $namespace = app()->getNamespace();

        $stub = "<?php
namespace {$namespace}Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class SplitApi{$nameUcFirst}ServiceProvider extends ServiceProvider
{

    protected ^namespace = 'App\Http\Controllers';

    public function boot()
    {
        parent::boot();
    }

    public function map()
    {
        ^this->mapSplitApi{$nameUcFirst}Routes();
    }

    protected function mapSplitApi{$nameUcFirst}Routes()
    {
        Route::prefix('api/{$endpoint}')
            ->middleware(['api'])
            ->namespace(^this->namespace)
            ->group(base_path('routes/{$name}.api.php'));
    }
}";

        $stub = str_replace('^', '$', $stub);
        file_put_contents($apiFileRealPath, ' <?php ' . PHP_EOL . '//');
        file_put_contents($providerFileRealPath, $stub);

        // - Now, you should register your class(\App\Providers\SplitApi{$nameUcFirst}ServiceProvider::class) at 'providers' array located at '" . config_path('app.php') . "'

        $message = " Success...
        - You can find your 'SplitApi{$nameUcFirst}ServiceProvider' class at '" . base_path('app/Providers') . "'
        - Your '$name.api.php' file has located at '" . base_path('routes') . "'";

        $this->info($message);
        return true;
    }
}
