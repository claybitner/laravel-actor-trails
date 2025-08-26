<?php

namespace DigitalIndoorsmen\LaravelActorTrails\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class LaravelActorTrailsCommand extends Command
{
    protected $signature = 'actor-trails:add {table : The table to add actor trail columns to}';

    protected $description = 'Generate a migration to add created_by, modified_by, and deleted_by JSON columns to a table';

    public function handle(): int
    {
        $table = $this->argument('table');
        $filesystem = new Filesystem;

        // Migration filename
        $timestamp = date('Y_m_d_His');
        $fileName = "{$timestamp}_add_actor_trails_columns_to_{$table}_table.php";
        $path = database_path("migrations/{$fileName}");

        // Stub path
        $stubPath = __DIR__.'/../../database/migrations/add_actor_trails_columns.php.stub';

        if (! $filesystem->exists($stubPath)) {
            $this->error("Migration stub not found at: {$stubPath}");

            return self::FAILURE;
        }

        // Replace placeholder with table name
        $stub = $filesystem->get($stubPath);
        $stub = str_replace('TABLE_NAME_HERE', $table, $stub);

        // Write migration file
        $filesystem->put($path, $stub);

        $this->info("Migration created: {$path}");

        return self::SUCCESS;
    }
}
