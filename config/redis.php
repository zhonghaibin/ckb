<?php
/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

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
    'default' => [
        'password' => $env('REDIS_PASSWORD', ''),
        'host' => $env('REDIS_HOST', '127.0.0.1'),
        'port' => (int) $env('REDIS_PORT', 6379),
        'database' => (int) $env('REDIS_DB', 0),
        // Connection pool, supports only Swoole or Swow drivers.
        'pool' => [
            'max_connections' => 5,
            'min_connections' => 1,
            'wait_timeout' => 3,
            'idle_timeout' => 60,
            'heartbeat_interval' => 50,
        ],
    ]
];
