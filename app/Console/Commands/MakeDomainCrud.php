<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class MakeDomainCrud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:domain-crud {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create domain base CRUD';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $domainName = ucfirst($this->argument('name'));
        $domainNameLower = strtolower($domainName);
        $baseDir = app_path($domainName);

        if (! File::exists($baseDir)) {
            $this->error("Domain folder does not exist. Please run 'make:domain {$domainName}' first.");

            return 1;
        }

        $files = [
            'Infrastructure/Http/Controllers/'.$domainName.'Controller.php' => 'controller.stub',
            'Infrastructure/Repositories/'.$domainName.'EloquentRepository.php' => 'repository.stub',
            'Infrastructure/Models/'.$domainName.'.php' => 'model.stub',
            'Domain/Repositories/'.$domainName.'RepositoryInterface.php' => 'repository-interface.stub',
            'Infrastructure/Routes/'.$domainName.'Routes.php' => 'routes.stub',
            'Domain/DTO/'.$domainName.'DTO.php' => 'dto.stub',
            'Domain/DTO/'.$domainName.'OutputDTO.php' => 'output-dto.stub',
            'Application/'.$domainName.'CreateUseCase.php' => 'create-use-case.stub',
            'Application/'.$domainName.'DeleteUseCase.php' => 'delete-use-case.stub',
            'Application/'.$domainName.'GetUseCase.php' => 'get-use-case.stub',
        ];

        foreach ($files as $path => $stub) {
            $fullPath = $baseDir.DIRECTORY_SEPARATOR.$path;
            $dir = dirname($fullPath);

            if (! File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }

            $stubContent = File::get(app_path("Console/Commands/Stubs/{$stub}"));
            $content = str_replace(
                ['{{ domainName }}', '{{ domainNameLower }}'],
                [$domainName, $domainNameLower],
                $stubContent
            );

            File::put($fullPath, $content);
        }

        // Create base migration
        Artisan::call('make:migration', ['name' => 'create_'.$domainNameLower.'_table']);

        $this->info('Arquivos do domínio criados com sucesso!');
    }
}
