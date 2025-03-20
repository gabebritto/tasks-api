<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class MakeDomain extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:domain {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new domain with Application, Domain, and Infrastructure folders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $domainName = ucfirst($this->argument('name'));
        $baseDir = app_path($domainName);

        // Create the main domain directory
        if (! File::exists($baseDir)) {
            File::makeDirectory($baseDir);
        }

        // Create the subdirectories
        $subDirs = [
            'Application',
            'Domain' => ['DTO', 'Repositories'],
            'Infrastructure' => [
                'Http' => ['Controllers', 'Requests'],
                'Models',
                'Repositories',
                'Routes',
            ],
        ];
        $this->createDirectories($baseDir, $subDirs);

        $this->info('Estrutura de domínio criada com sucesso!');

        if (! $this->confirmToProceed('Deseja criar os arquivos base do Domínio?')) {
            return 0;
        }

        $this->info('Criando arquivos do domínio!');
        Artisan::call('make:domain-crud', ['name' => $domainName]);
        $this->info('Implementação de domínio adicionada com sucesso!');
    }

    private function createDirectories($basePath, $directories): void
    {
        foreach ($directories as $dir => $subDirs) {
            if (is_array($subDirs)) {
                $dirPath = $basePath.DIRECTORY_SEPARATOR.$dir;

                if (! File::exists($dirPath)) {
                    File::makeDirectory($dirPath);
                }

                $this->createDirectories($dirPath, $subDirs);
            } else {
                $dirPath = $basePath.DIRECTORY_SEPARATOR.$subDirs;

                if (! File::exists($dirPath)) {
                    File::makeDirectory($dirPath);
                }
            }
        }
    }
}
