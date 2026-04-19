<?php

$env = static function (string $key, $default = null) {
    static $envData = null;
    if ($envData === null) {
        $envPath = dirname(__DIR__) . '/.env';
        $envData = is_file($envPath) ? parse_ini_file($envPath, false, INI_SCANNER_RAW) : [];
    }
    $value = $envData[$key] ?? $_ENV[$key] ?? getenv($key);
    return $value === false || $value === null ? $default : $value;
};

return [
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'driver'      => 'mysql',
            'host'        => $env('DB_HOST', '127.0.0.1'),
            'port'        => $env('DB_PORT', '3306'),
            'database'    => $env('DB_DATABASE', 'ckb'),
            'username'    => $env('DB_USERNAME', 'root'),
            'password'    => $env('DB_PASSWORD', ''),
            'charset'     => $env('DB_CHARSET', 'utf8mb4'),
            'collation'   => $env('DB_COLLATION', 'utf8mb4_general_ci'),
            'prefix'      => $env('DB_PREFIX', ''),
            'strict'      => true,
            'engine'      => null,
            'options'   => [
                PDO::ATTR_EMULATE_PREPARES => false, // Must be false for Swoole and Swow drivers.
            ],
            // Connection pool, supports only Swoole or Swow drivers.
            'pool' => [
                'max_connections' => 5,
                'min_connections' => 1,
                'wait_timeout' => 3,
                'idle_timeout' => 60,
                'heartbeat_interval' => 50,
            ],
        ],
    ],
];
