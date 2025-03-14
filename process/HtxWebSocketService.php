<?php

namespace process;

use app\enums\HtxMarket;
use Workerman\Connection\TcpConnection;
use Workerman\Worker;
use Workerman\Redis\Client as AsyncRedis;
use Workerman\Timer;
use support\Log;

class HtxWebSocketService
{
    protected $redis;

    public function onWorkerStart(Worker $worker)
    {
        $redisConfig = config('redis.default');
        $redisUri = "redis://{$redisConfig['host']}:{$redisConfig['port']}";

        if (!empty($redisConfig['password'])) {
            $redisUri = "redis://:{$redisConfig['password']}@{$redisConfig['host']}:{$redisConfig['port']}";
        }

        // 订阅 Redis 频道
        $this->redis = new AsyncRedis($redisUri);

        $worker->onWebSocketConnect = function (TcpConnection $connection, $http_header) {
            $connection->subscribedChannels = [];
            $connection->lastMessageTime = time(); // 记录连接时间
        };

        $worker->onMessage = function (TcpConnection $connection, $data) {
            $connection->lastMessageTime = time(); // 记录最近一次收到数据的时间
            $data = json_decode($data, true);

            if (isset($data['ping'])) {
                $connection->send(json_encode(['pong' => 'pong']));
                return;
            }

            if (isset($data['sub'])) {
                $channel = $data['sub'];
                if ($channel === HtxMarket::CKBUSDT_TICKER->value || $channel === HtxMarket::ONEUSDT_TICKER->value) {
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

        // **心跳检测**：定期检查 WebSocket 连接是否存活
        Timer::add(60, function () use ($worker) {
            $now = time();
            foreach ($worker->connections as $connection) {
                if ($now - $connection->lastMessageTime > 90) {
                    Log::info("Connection timeout, closing...");
                    $connection->close();
                }
            }
        });
    }

    protected function subscribeRedis(Worker $worker)
    {
        $this->redis->subscribe([HtxMarket::CKBUSDT_TICKER->value, HtxMarket::ONEUSDT_TICKER->value], function ($channel, $message) use ($worker) {
            foreach ($worker->connections as $connection) {
                if (isset($connection->subscribedChannels[$channel])) {
                    $connection->send(json_encode(['channel' => $channel, 'data' => $message]));
                }
            }
        });
    }
}
