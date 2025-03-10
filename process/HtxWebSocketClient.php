<?php

namespace process;

use app\enums\HtxMarket;
use Ratchet\Client\WebSocket;
use Ratchet\Client\Connector as WebSocketConnector;
use React\EventLoop\Factory;
use React\Socket\Connector as SocketConnector;
use support\Log;
use Workerman\Worker;
use support\Redis;

class HtxWebSocketClient
{
    protected $loop;
    protected $connector;

    public function __construct()
    {
        // 创建事件循环
        $this->loop = Factory::create();


        // 创建 WebSocket Connector
        $socketConnector = new SocketConnector($this->loop, [
            'tcp' => [
                'timeout' => 60, // 增加超时时间
            ],
            'tls' => [
                'verify_peer' => false, // 禁用 SSL 验证（仅用于测试）
                'verify_peer_name' => false,
            ],
        ]);

        $this->connector = new WebSocketConnector($this->loop, $socketConnector);
    }

    public function onWorkerStart(Worker $worker)
    {
        $this->connect();
    }

    public function connect()
    {
        $wsUrl = 'wss://api.huobi.pro/ws';

        // 这里使用 call_user_func 方式调用 WebSocketConnector
        call_user_func($this->connector, $wsUrl)
            ->then(function (WebSocket $conn) {
                Log::info("Connected to Huobi WebSocket API");

                // 订阅 BTC/USDT 的实时行情
                $subscribeBtcMessage = [
                    'sub' => HtxMarket::BTCUSDT_TICKER->value,
                    'id' => 'btcusdt_ticker_' . time(),
                ];
                $conn->send(json_encode($subscribeBtcMessage));

                // 订阅 ONE/USDT 的实时行情
                $subscribeOneMessage = [
                    'sub' => HtxMarket::ONEUSDT_TICKER->value,
                    'id' => 'oneusdt_ticker_' . time(),
                ];
                $conn->send(json_encode($subscribeOneMessage));

                // 监听消息
                $conn->on('message', function ($msg) use ($conn) {
                    $decompressedMsg = gzdecode($msg);
                    if ($decompressedMsg === false) {
                        Log::error("Failed to decompress message");
                        return;
                    }

                    // 解析 JSON 数据
                    $data = json_decode($decompressedMsg, true);
                    if (isset($data['ping'])) {
                        // 响应心跳包
                        $conn->send(json_encode(['pong' => $data['ping']]));
                    } elseif (isset($data['ch'])) {
                        // 存入 Redis
                        Redis::publish($data['ch'], json_encode($data));
//                        Log::info("Saved data to Redis: " . json_encode($data));
                    } else {
                        // 其他消息
                        Log::info("Received: " . json_encode($data));
                    }
                });

                // 关闭连接
                $conn->on('close', function ($code = null, $reason = null) {
                    Log::info("Connection closed ({$code} - {$reason})");
                });
            }, function (\Exception $e) {
                Log::error("Could not connect: {$e->getMessage()}");
            });

        // 启动事件循环
        $this->loop->run();
    }
}
