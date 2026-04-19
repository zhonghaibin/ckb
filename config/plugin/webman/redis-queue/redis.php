<?php

$env = static function (string $key, $default = null) {
    static $envData = null;
    if ($envData === null) {
        $envPath = dirname(__DIR__, 4) . '/.env';
        $envData = is_file($envPath) ? parse_ini_file($envPath, false, INI_SCANNER_RAW) : [];
    }
    $value = $envData[$key] ?? $_ENV[$key] ?? getenv($key);
    return $value === false || $value === null ? $default : $value;
};

return [
    'default' => [
        'host' => sprintf(
            'redis://%s:%s',
            $env('REDIS_QUEUE_HOST', $env('REDIS_HOST', '127.0.0.1')),
            $env('REDIS_QUEUE_PORT', $env('REDIS_PORT', 6379))
        ),
        'options' => [
            'auth' => $env('REDIS_QUEUE_PASSWORD', $env('REDIS_PASSWORD', '')),
            'db' => (int) $env('REDIS_QUEUE_DB', $env('REDIS_DB', 0)),
            'prefix' => $env('REDIS_QUEUE_PREFIX', ''),
            'max_attempts'  => 5,
            'retry_seconds' => 5,
        ]
    ],
];
