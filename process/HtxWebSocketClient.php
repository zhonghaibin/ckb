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

        // 使用 call_user_func 调用 WebSocketConnector
        call_user_func($this->connector, $wsUrl)
            ->then(function (WebSocket $conn) {
                Log::info("Connected to Huobi WebSocket API");

                // 订阅 ONE/USDT 和 CKB/USDT 实时行情
                $this->subscribeToMarkets($conn);

                // 监听消息
                $conn->on('message', function ($msg) use ($conn) {
                    $this->handleMessage($msg, $conn);
                });

                // 关闭连接时的处理
                $conn->on('close', function ($code = null, $reason = null) {
                    Log::info("Connection closed ({$code} - {$reason})");
                    // 你可以在此实现重连逻辑
                    $this->reconnect();
                });
            }, function (\Exception $e) {
                Log::error("Could not connect: {$e->getMessage()}");
                // 如果连接失败，尝试重新连接
                $this->reconnect();
            });

        // 启动事件循环
        $this->loop->run();
    }

    // 订阅市场数据
    protected function subscribeToMarkets(WebSocket $conn)
    {
        $subscribeOneMessage = [
            'sub' => HtxMarket::ONEUSDT_TICKER->value,
            'id' => 'oneusdt_ticker_' . time(),
        ];
        $conn->send(json_encode($subscribeOneMessage));

        $subscribeCkbMessage = [
            'sub' => HtxMarket::CKBUSDT_TICKER->value,
            'id' => 'ckbusdt_ticker_' . time(),
        ];
        $conn->send(json_encode($subscribeCkbMessage));

        $subscribeBtcMessage = [
            'sub' => HtxMarket::BTCUSDT_TICKER->value,
            'id' => 'btcusdt_ticker_' . time(),
        ];
        $conn->send(json_encode($subscribeBtcMessage));

        $subscribeEtcMessage = [
            'sub' => HtxMarket::ETCUSDT_TICKER->value,
            'id' => 'ectusdt_ticker_' . time(),
        ];
        $conn->send(json_encode($subscribeEtcMessage));

        $subscribeBnbMessage = [
            'sub' => HtxMarket::BNBUSDT_TICKER->value,
            'id' => 'bnbusdt_ticker_' . time(),
        ];
        $conn->send(json_encode($subscribeBnbMessage));

    }

    // 处理收到的消息
    protected function handleMessage($msg, WebSocket $conn)
    {
        $decompressedMsg = @gzdecode($msg);
        if ($decompressedMsg === false) {
            Log::error("Failed to decompress message");
            $conn->close();  // 触发重连逻辑
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
            Redis::setex($data['ch'], 60, json_encode($data)); // 设置 60 秒后过期
//            Log::info("Saved data to Redis: " . json_encode($data));
        } else {
            // 其他消息
            Log::info("Received: " . json_encode($data));
        }

        // 释放内存
        unset($data);
        gc_collect_cycles();
    }

    // 断开连接后的重连逻辑
    protected function reconnect()
    {
        Log::info("Attempting to reconnect...");
        // 避免过快重连，随机等待 5-10 秒
        $this->loop->addTimer(mt_rand(5, 10), function () {
            if ($this->connector) {
                $this->connect();
            }
        });
    }
}
