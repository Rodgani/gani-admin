<?php

namespace App\Console\Commands;
use Illuminate\Foundation\Console\ModelMakeCommand;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Str;

class MakeModuleModelCommand extends ModelMakeCommand
{
    // protected $name = 'module:model';

    protected $description = 'Create a new model inside the Modules directory';

    protected $type = 'Model';

    private $module;

    /**
     * Handle the command execution.
     */
    public function handle()
    {
        // ✅ Correctly retrieve the --module option
        $this->module = $this->option('module');

        if (!$this->module) {
            $this->error('You must specify the --module option like --module=Admin');
            return;
        }

        // Optional: Normalize the class name (replace `/` with `\`)
        $name = str_replace('/', '\\', $this->argument('name'));
        $this->input->setArgument('name', $name);

        parent::handle();
    }

    /**
     * Get the path where the controller should be generated.
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return base_path('modules') . '/' . $this->module . '/Models' . str_replace('\\', '/', $name) . '.php';
    }

    /**
     * Override the root namespace to use Modules.
     */
    protected function rootNamespace()
    {
        return 'Modules\\' . $this->module . '\\Models';
    }

    protected function getOptions()
    {
        return array_merge(parent::getOptions(), [
            ['module', null, InputOption::VALUE_REQUIRED, 'The module the class belongs to'],
        ]);
    }
}
