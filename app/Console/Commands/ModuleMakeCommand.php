<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Artisan;

class ModuleMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make {type} {name} {--module=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reuse make command to change default create location';

    /**
     * Execute the console command.
     */


    public function handle()
    {

        $makeCommand = $this->argument('type');
        $name = $this->argument('name');
        $module = $this->option('module');

        $map = [
            'controller' => 'make:controller',
            'model' => 'make:model',
            'request' => 'make:request',
            'seeder' => 'make:seeder',
            'factory' => 'make:factory',
            'migration' => 'make:migration',
            'resource' => 'make:resource',
        ];

        if (!isset($map[$makeCommand])) {
            $this->error("Unsupported make type: $makeCommand");
            return;
        }

        Artisan::call($map[$makeCommand], [
            'name' => $module . "/" . $name
        ]);

        $this->info(Artisan::output());
    }
}
