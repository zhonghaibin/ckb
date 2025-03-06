<?php

namespace process;

use Workerman\Connection\TcpConnection;
use Workerman\Worker;
use Workerman\Timer; // 引入 Timer 类
use support\Log;
use support\Redis;

class HtxWebSocketService
{
    public function onWorkerStart(Worker $worker)
    {
        $worker->onWebSocketConnect = function (TcpConnection $connection, $http_header) {
            // 初始化订阅频道和定时器
            $connection->subscribedChannels = [];
            $connection->redisTimers = [];
        };

        $worker->onMessage = function (TcpConnection $connection, $data) {
            Log::info("Received message: {$data}");
            $data = json_decode($data, true);

            // 订阅消息处理
            if (isset($data['sub'])) {
                $channel = $data['sub'];
                // 检查是否是有效的订阅
                if ($channel === 'market.btcusdt.ticker' || $channel === 'market.oneusdt.ticker') {
                    // 记录订阅频道
                    $connection->subscribedChannels[$channel] = true;

                    // 启动定时器，定期从 Redis 获取数据并发送给客户端
                    $timerId = $this->startRedisTimer($connection, $channel);
                    $connection->redisTimers[$channel] = $timerId;

                    $connection->send(json_encode(['status' => 'subscribed', 'channel' => $channel]));
                } else {
                    $connection->send(json_encode(['error' => 'Invalid subscription']));
                }
            } elseif (isset($data['unsub'])) {
                $channel = $data['unsub'];
                // 取消订阅
                if (isset($connection->subscribedChannels[$channel])) {
                    unset($connection->subscribedChannels[$channel]);

                    // 停止定时器
                    if (isset($connection->redisTimers[$channel])) {
                        Timer::del($connection->redisTimers[$channel]);
                        unset($connection->redisTimers[$channel]);
                    }

                    $connection->send(json_encode(['status' => 'unsubscribed', 'channel' => $channel]));
                } else {
                    $connection->send(json_encode(['error' => 'Not subscribed to channel']));
                }
            } else {
                $connection->send(json_encode(['error' => 'No subscription parameter']));
            }
        };

        $worker->onClose = function (TcpConnection $connection) {
            echo "Connection closed\n";
            // 清理订阅频道和定时器
            foreach ($connection->redisTimers as $timerId) {
                Timer::del($timerId);
            }
            $connection->subscribedChannels = [];
            $connection->redisTimers = [];
        };
    }

    /**
     * 启动 Redis 定时器，定期获取数据并发送给客户端
     *
     * @param TcpConnection $connection
     * @param string $channel
     * @return int
     */
    protected function startRedisTimer(TcpConnection $connection, $channel)
    {
        return Timer::add(  0.5 , function () use ($connection, $channel) {
            $data = Redis::get($channel);
            if ($data) {
                $connection->send(json_encode(['channel' => $channel, 'data' => $data]));
            }
        });
    }
}