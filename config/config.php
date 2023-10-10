<?php

declare(strict_types=1);

use Psr\Log\LogLevel;

return [
    'is_dev_mode' => false,
    'scan_directories' => [
        'app',
    ],
    'proxy_root_directory' => '.php-accessor',
    'gen_meta' => env('APP_ENV') == 'local' ? 'yes' : 'no',
    'gen_proxy' => 'yes',
    'log_level' => LogLevel::DEBUG,
];
