<?php

declare(strict_types=1);

namespace Laravel\PhpAccessor;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Laravel\PhpAccessor\Command\GenerateCommand;

class PhpAccessorProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->booted(function () {
            $generator = new AccessorGenerator();
            $generator->gen();
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/config.php' => App::configPath('php-accessor.php'),
        ], 'php-accessor');

        $this->commands(GenerateCommand::class);
    }
}
