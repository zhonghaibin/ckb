<?php

namespace process;

use Ratchet\Client\WebSocket;
use Ratchet\Client\Connector as WebSocketConnector;
use React\EventLoop\Factory;
use React\Socket\Connector as SocketConnector;
use support\Log;
use Workerman\Worker;

class HtxWebSocket
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

                $subscribeMessage = [
                    'sub' => 'market.btcusdt.ticker',
                    'id'  => 'btcusdt_ticker_' . time(),
                ];
                $conn->send(json_encode($subscribeMessage));

                $conn->on('message', function ($msg) use ($conn) {
                    $decompressedMsg = gzdecode($msg);
                    if ($decompressedMsg === false) {
                        Log::error("Failed to decompress message");
                        return;
                    }

                    $data = json_decode($decompressedMsg, true);
                    if (isset($data['ping'])) {
                        $conn->send(json_encode(['pong' => $data['ping']]));
                    } elseif (isset($data['ch'])) {
                        Log::info("Received market data: " . json_encode($data));
                    } else {
                        Log::info("Received: " . json_encode($data));
                    }
                });

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
