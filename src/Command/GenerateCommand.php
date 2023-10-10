<?php

declare(strict_types=1);

namespace Laravel\PhpAccessor\Command;

use Illuminate\Console\Command;
use Laravel\PhpAccessor\AccessorGenerator;

class GenerateCommand extends Command
{
    protected $signature = 'php-accessor:gen';

    protected $description = '';

    public function handle(): void
    {
        $generator = new AccessorGenerator();
        $genFiles = $generator->genProxyFile();
        foreach ($genFiles as $genFile) {
            $this->info($genFile);
        }

        $this->comment('Ok!');
    }
}
