<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

class MakeModule extends Command
{
    protected $signature = 'make:module {type} {name} {module}';
    protected $description = 'Generate Laravel class and move it to Modules/{Module}';

    public function handle()
    {
        $type = strtolower($this->argument('type'));
        $name = $this->argument('name');
        $module = $this->argument('module');

        // ✅ Snapshot app and migration directories
        $appPath = base_path('app');
        $migrationPath = database_path('migrations');
        
        if (!is_dir($migrationPath)) {
            mkdir($migrationPath, 0755, true);
        }

        $beforeApp = collect($this->listPhpFiles($appPath));
        $beforeMigrations = collect(scandir($migrationPath));

        // 🛠 Run native make:* command
        Artisan::call("make:$type", ['name' => $name]);
        $this->info(trim(Artisan::output()));

        // ✅ Handle migrations separately
        if ($type === 'migration') {
            $afterMigrations = collect(scandir($migrationPath));
            $newMigrations = $afterMigrations->diff($beforeMigrations);

            foreach ($newMigrations as $file) {
                if (!str_ends_with($file, '.php')) continue;

                $from = $migrationPath . DIRECTORY_SEPARATOR . $file;
                $to = base_path("Modules/$module/Database/Migrations/$file");

                if (!is_dir(dirname($to))) {
                    mkdir(dirname($to), 0755, true);
                }

                rename($from, $to);
                $this->info("✅ Migration moved to Modules/$module/Database/Migrations/$file");
            }

            return;
        }

        // 📦 Detect newly created file in app/
        $afterApp = collect($this->listPhpFiles($appPath));
        $newFile = $afterApp->diff($beforeApp)->first();

        if (!$newFile || !file_exists($newFile)) {
            $this->error("❌ Could not detect new file.");
            return;
        }

        // 📂 Move file into Modules/{Module}/<relative_path>
        $relativePath = Str::after($newFile, $appPath . DIRECTORY_SEPARATOR);
        $targetPath = base_path("Modules/$module/$relativePath");

        if (!is_dir(dirname($targetPath))) {
            mkdir(dirname($targetPath), 0755, true);
        }

        rename($newFile, $targetPath);
        $this->updateNamespace($targetPath, 'App\\', "Modules\\$module\\");

        $this->info("✅ $type created at: Modules/$module/$relativePath");
    }

    protected function listPhpFiles($path): array
    {
        $finder = Finder::create()->files()->in($path)->name('*.php')->sortByName();
        return array_map(fn($file) => $file->getRealPath(), iterator_to_array($finder));
    }

    protected function updateNamespace(string $filePath, string $from, string $to): void
    {
        $contents = file_get_contents($filePath);
        $updated = str_replace("namespace $from", "namespace $to", $contents);
        $updated = str_replace("$from", "$to", $updated);
        file_put_contents($filePath, $updated);
    }
}
