<?php

namespace process;

use app\enums\HtxMarket;
use Workerman\Connection\TcpConnection;
use Workerman\Worker;
use Workerman\Redis\Client as AsyncRedis;
use support\Log;

class HtxWebSocketService
{
    protected $redis;

    public function onWorkerStart(Worker $worker)
    {
        $redisConfig = config('redis.default'); // 读取 'cache' 配置
        $redisUri = "redis://{$redisConfig['host']}:{$redisConfig['port']}";

        if (!empty($redisConfig['password'])) {
            $redisUri = "redis://:{$redisConfig['password']}@{$redisConfig['host']}:{$redisConfig['port']}";
        }


        // 订阅 Redis 频道
        $this->redis = new AsyncRedis($redisUri);

        $worker->onWebSocketConnect = function (TcpConnection $connection, $http_header) {
            $connection->subscribedChannels = [];
        };

        $worker->onMessage = function (TcpConnection $connection, $data) {
            Log::info("Received message: {$data}");
            $data = json_decode($data, true);

            if (isset($data['sub'])) {
                $channel = $data['sub'];

                if ($channel === HtxMarket::BTCUSDT_TICKER->value || $channel === HtxMarket::ONEUSDT_TICKER->value) {
                    $connection->subscribedChannels[$channel] = true;
                    $connection->send(json_encode(['status' => 'subscribed', 'channel' => $channel]));
                } else {
                    $connection->send(json_encode(['error' => 'Invalid subscription']));
                }
            } elseif (isset($data['unsub'])) {
                $channel = $data['unsub'];
                unset($connection->subscribedChannels[$channel]);
                $connection->send(json_encode(['status' => 'unsubscribed', 'channel' => $channel]));
            } else {
                $connection->send(json_encode(['error' => 'No subscription parameter']));
            }
        };

        $worker->onClose = function (TcpConnection $connection) {
            Log::info("Connection closed");
            $connection->subscribedChannels = [];
        };

        // 使用异步 Redis 订阅
        $this->subscribeRedis($worker);
    }

    protected function subscribeRedis(Worker $worker)
    {
        $this->redis->subscribe([HtxMarket::BTCUSDT_TICKER->value, HtxMarket::ONEUSDT_TICKER->value], function ($channel, $message) use ($worker) {
            Log::info("Received Redis message on channel {$channel}: {$message}");

            foreach ($worker->connections as $connection) {
                if (isset($connection->subscribedChannels[$channel])) {
                    $connection->send(json_encode(['channel' => $channel, 'data' => $message]));
                }
            }
        });
    }
}
