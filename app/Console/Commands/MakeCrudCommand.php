<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class MakeCrudCommand extends Command
{
    protected $signature = 'make:crud
                            {name : The base name, e.g. Post}
                            {--api : Generate API controller}
                            {--force : Overwrite existing files when possible}';

    protected $description = 'Generate tenant model, tenant migration, controller, request, and resource for a CRUD module';

    public function handle(): int
    {
        $name = Str::studly($this->argument('name'));

        $modelName = $name;
        $tenantModelPath = 'Tenant/' . $modelName;

        $requestName = $name . 'Request';
        $resourceName = $name . 'Resource';
        $controllerName = $name . 'Controller';

        $this->info("Generating CRUD scaffold for tenant model: {$name}");

        $this->callMakeModel($tenantModelPath);
        $this->callMakeTenantMigration($name);
        $this->callMakeController($controllerName, $tenantModelPath);
        $this->callMakeRequest($requestName);
        $this->callMakeResource($resourceName);

        $this->newLine();
        $this->info('CRUD scaffold generated successfully.');

        $this->newLine();
        $this->line('Generated:');
        $this->line("- Model: App\\Models\\Tenant\\{$modelName}");
        $this->line("- Migration: database/migrations/tenant/create_" . Str::snake(Str::pluralStudly($name)) . "_table.php");
        $this->line("- Controller: {$controllerName}");
        $this->line("- Request: {$requestName}");
        $this->line("- Resource: {$resourceName}");

        return self::SUCCESS;
    }

    protected function callMakeModel(string $modelPath): void
    {
        Artisan::call('make:model', array_filter([
            'name' => $modelPath,
            '--force' => $this->option('force') ? true : null,
            '--no-interaction' => true,
        ]));

        $this->line(Artisan::output());
    }

    protected function callMakeTenantMigration(string $name): void
    {
        $table = Str::snake(Str::pluralStudly($name));
        $migrationName = 'create_' . $table . '_table';

        Artisan::call('make:migration', [
            'name' => $migrationName,
            '--create' => $table,
            '--path' => 'database/migrations/tenant',
            '--no-interaction' => true,
        ]);

        $this->line(Artisan::output());
    }

    protected function callMakeController(string $controllerName, string $modelPath): void
    {
        Artisan::call('make:controller', array_filter([
            'name' => $controllerName,
            '--model' => $modelPath,
            '--resource' => false,
            '--api' => $this->option('api') ? true : null,
            '--force' => $this->option('force') ? true : null,
            '--no-interaction' => true,
        ]));

        $this->line(Artisan::output());
    }

    protected function callMakeRequest(string $requestName): void
    {
        Artisan::call('make:request', array_filter([
            'name' => $requestName,
            '--force' => $this->option('force') ? true : null,
            '--no-interaction' => true,
        ]));

        $this->line(Artisan::output());
    }

    protected function callMakeResource(string $resourceName): void
    {
        Artisan::call('make:resource', array_filter([
            'name' => $resourceName,
            '--force' => $this->option('force') ? true : null,
            '--no-interaction' => true,
        ]));

        $this->line(Artisan::output());
    }
}
