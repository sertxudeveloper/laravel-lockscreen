<?php

namespace SertxuDeveloper\LockScreen\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lockscreen:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the sertxudeveloper/laravel-lockscreen package';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void {
        // Controllers...
        $this->comment('Publishing controllers...');
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers/Auth'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/App/Http/Controllers/Auth', app_path('Http/Controllers/Auth'));

        // Views...
        $this->comment('Publishing views...');
        $this->publishResource('views/auth');

        // Routes...
        $this->comment('Publishing routes...');
        copy(__DIR__.'/../../stubs/routes/lockscreen.php', base_path('routes/lockscreen.php'));
        $this->appendToFile(base_path('routes/web.php'), file_get_contents(__DIR__.'/../../stubs/routes/web.stub'));

        $this->line('');
        $this->info('The sertxudeveloper/laravel-lockscreen package installed successfully.');
    }

    /**
     * Appends the content to the original file if it has not been already appended.
     *
     * @param  string  $originalFile The path of the original file.
     * @param  string  $content The content to be appended.
     * @return void
     */
    protected function appendToFile(string $originalFile, string $content): void {
        $original = file_get_contents($originalFile);

        if (!str_contains($original, $content)) {
            file_put_contents($originalFile, $original.$content);
        }
    }

    /**
     * Publish the provided resource path.
     *
     * @param  string  $path
     * @return void
     */
    protected function publishResource(string $path): void {
        (new Filesystem)->ensureDirectoryExists(resource_path($path));
        (new Filesystem)->copyDirectory(__DIR__."/../../stubs/resources/$path", resource_path($path));
    }
}
