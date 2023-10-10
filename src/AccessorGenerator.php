<?php

declare(strict_types=1);

namespace Laravel\PhpAccessor;

use ArrayIterator;
use Composer\Autoload\ClassLoader;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use PhpAccessor\Runner;
use Psr\Log\LogLevel;

class AccessorGenerator
{
    private array $config = [
        'is_dev_mode' => false,
        'scan_directories' => [
            'app',
        ],
        'proxy_root_directory' => '.php-accessor',
        'gen_meta' => 'yes',
        'gen_proxy' => 'yes',
        'log_level' => LogLevel::INFO,
    ];

    private bool $configMerged = false;

    public function gen(): void
    {
        $proxyDir = App::basePath($this->getConfig('proxy_root_directory') . DIRECTORY_SEPARATOR . 'proxy');
        if ($this->getConfig('is_dev_mode') || ! is_dir($proxyDir)) {
            $generatedFiles = $this->genProxyFile();
            $generatedFiles && $this->log($generatedFiles);
        }

        $proxies = File::files($proxyDir);
        if (empty($proxies)) {
            return;
        }

        $classLoader = new ClassLoader();
        $classMap = [];
        foreach ($proxies as $proxy) {
            $classname = str_replace('@', '\\', $proxy->getBasename('.' . $proxy->getExtension()));
            $classname = substr($classname, 1);
            $classMap[$classname] = $proxy->getRealPath();
        }
        $classLoader->addClassMap($classMap);
        $classLoader->register(true);
    }

    public function genProxyFile(): array
    {
        $generatedFiles = [];
        foreach ($this->getConfig('scan_directories') as $scanDirectory) {
            $files = File::allFiles(App::basePath($scanDirectory));
            if (empty($files)) {
                continue;
            }

            $finder = new ArrayIterator($files);
            $runner = new Runner(
                finder: $finder,
                dir: App::basePath($this->getConfig('proxy_root_directory')),
                genMeta: $this->getConfig('gen_meta') == 'yes',
                genProxy: $this->getConfig('gen_proxy') == 'yes',
            );
            $runner->generate();
            $generatedFiles = array_merge($generatedFiles, $runner->getGeneratedFiles());
        }

        return $generatedFiles;
    }

    private function getConfig(string $key): mixed
    {
        if (! $this->configMerged) {
            $this->config = array_merge($this->config, Config::get('php-accessor', []));
            $this->configMerged = true;
        }

        return $this->config[$key];
    }

    private function log(array $messages): void
    {
        $logLevel = $this->getConfig('log_level');
        foreach ($messages as $message) {
            Log::log($logLevel, '[php-accessor]: ' . $message);
        }
    }
}
