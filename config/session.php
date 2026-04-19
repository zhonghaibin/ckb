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

use Webman\Session\FileSessionHandler;
use Webman\Session\RedisClusterSessionHandler;
use Webman\Session\RedisSessionHandler;

$env = static function (string $key, $default = null) {
    static $envData = null;
    if ($envData === null) {
        $envPath = dirname(__DIR__) . '/.env';
        $envData = is_file($envPath) ? parse_ini_file($envPath, false, INI_SCANNER_RAW) : [];
    }
    $value = $envData[$key] ?? $_ENV[$key] ?? getenv($key);
    return $value === false || $value === null ? $default : $value;
};

$sessionType = $env('SESSION_TYPE', 'file');
$sessionHandler = FileSessionHandler::class;
if ($sessionType === 'redis') {
    $sessionHandler = RedisSessionHandler::class;
} elseif ($sessionType === 'redis_cluster') {
    $sessionHandler = RedisClusterSessionHandler::class;
}

return [

    'type' => $sessionType, // or redis or redis_cluster

    'handler' => $sessionHandler,

    'config' => [
        'file' => [
            'save_path' => runtime_path() . '/sessions',
        ],
        'redis' => [
            'host' => $env('SESSION_REDIS_HOST', $env('REDIS_HOST', '127.0.0.1')),
            'port' => (int) $env('SESSION_REDIS_PORT', $env('REDIS_PORT', 6379)),
            'auth' => $env('SESSION_REDIS_PASSWORD', $env('REDIS_PASSWORD', '')),
            'timeout' => (int) $env('SESSION_REDIS_TIMEOUT', 2),
            'database' => (string) $env('SESSION_REDIS_DB', $env('REDIS_DB', 0)),
            'prefix' => $env('SESSION_REDIS_PREFIX', 'redis_session_'),
        ],
        'redis_cluster' => [
            'host' => ['127.0.0.1:7000', '127.0.0.1:7001', '127.0.0.1:7001'],
            'timeout' => 2,
            'auth' => '',
            'prefix' => 'redis_session_',
        ]
    ],

    'session_name' => 'PHPSID',

    'auto_update_timestamp' => false,

    'lifetime' => 7 * 24 * 60 * 60,

    'cookie_lifetime' => 365 * 24 * 60 * 60,

    'cookie_path' => '/',

    'domain' => '',

    'http_only' => true,

    'secure' => false,

    'same_site' => '',

    'gc_probability' => [1, 1000],

];
