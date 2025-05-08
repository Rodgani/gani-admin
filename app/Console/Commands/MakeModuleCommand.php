<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

class MakeModuleCommand extends Command
{
    protected $signature = 'make:module {type} {name} {module}';
    protected $description = 'Generate Laravel class and move it to Modules/{Module}';

    private const CONTROLLER = "controller";
    private $type;
    public function handle()
    {
        $type = strtolower($this->argument('type'));
        $this->type = $type;
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
        $unlinkPath = base_path("app/$relativePath");

        if (file_exists($targetPath)) {

            $this->unlinkPath($unlinkPath, $relativePath);

            $this->warn("⚠️ File already exists at: Modules/$module/$relativePath");
            return; // Exit early or handle conflict as needed
        }

        if (!is_dir(dirname($targetPath))) {
            mkdir(dirname($targetPath), 0755, true);
        }

        rename($newFile, $targetPath);
        $this->updateNamespace($targetPath, 'App\\', "Modules\\$module\\");
        $this->removeEmptyDirectories(base_path('app'));

        $this->info("✅ " . ucfirst($type) . " move to Modules/$module/$relativePath");
    }

    private function unlinkPath($unlinkPath, $relativePath): void
    {
        if (file_exists($unlinkPath)) {
            // 🗑️ Delete the new file
            unlink($unlinkPath);

            // 🧹 Remove empty parent directories based on relative path only
            $dir = dirname($unlinkPath);
            $segments = explode(DIRECTORY_SEPARATOR, $relativePath);

            // Limit cleanup only within the relative path structure
            foreach (array_reverse($segments) as $_) {
                if (is_dir($dir) && count(scandir($dir)) === 2) {
                    rmdir($dir);
                    $dir = dirname($dir);
                } else {
                    break;
                }
            }
        }
    }

    private function removeEmptyDirectories(string $path)
    {
        foreach (scandir($path) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $fullPath = $path . DIRECTORY_SEPARATOR . $item;

            if (is_dir($fullPath)) {
                // Recursively clean subdirectories first
                $this->removeEmptyDirectories($fullPath);

                // After recursion, check if directory is now empty
                if (count(scandir($fullPath)) === 2) {
                    rmdir($fullPath);
                }
            }
        }
    }


    private function listPhpFiles($path): array
    {
        $finder = Finder::create()->files()->in($path)->name('*.php')->sortByName();
        return array_map(fn($file) => $file->getRealPath(), iterator_to_array($finder));
    }

    private function updateNamespace(string $filePath, string $from, string $to): void
    {
        $contents = file_get_contents($filePath);

        if($this->type===self::CONTROLLER){
            $search = ["namespace $from","use App\\Http\\Controllers\\Controller;"];
            $replace = ["namespace $to","use Modules\\Controller;"];
        }else{
            $search = ["namespace $from"];
            $replace = ["namespace $to"];
        }

        $updated = str_replace($search, $replace, $contents);

        file_put_contents($filePath, $updated);
    }
}
