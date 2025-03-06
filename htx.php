<?php

require 'vendor/autoload.php';

use Ratchet\Client\WebSocket;
use Ratchet\Client\Connector;
use React\EventLoop\Factory;
use React\Socket\Connector as SocketConnector;

// 创建事件循环
$loop = Factory::create();

// 创建 WebSocket Connector
$socketConnector = new SocketConnector($loop, [
    'tcp' => [
        'timeout' => 60, // 增加超时时间
    ],
    'tls' => [
        'verify_peer' => false, // 禁用 SSL 验证（仅用于测试）
        'verify_peer_name' => false,
    ],
]);

$connector = new Connector($loop, $socketConnector);

// 火币 WebSocket API 地址
$wsUrl = 'wss://api.huobi.pro/ws';

// 连接火币 WebSocket
$connector($wsUrl)
    ->then(function (WebSocket $conn) {
        echo "Connected to Huobi WebSocket API\n";

        // 订阅 BTC/USDT 的实时行情
        $subscribeMessage = [
            'sub' => 'market.btcusdt.ticker',
            'id'  => 'btcusdt_ticker_' . time(),
        ];
        $conn->send(json_encode($subscribeMessage));

        // 监听消息
        $conn->on('message', function ($msg) use ($conn) {
            // 解压 GZIP 数据
            $decompressedMsg = gzdecode($msg);
            if ($decompressedMsg === false) {
                echo "Failed to decompress message\n";
                return;
            }

            // 解析 JSON 数据
            $data = json_decode($decompressedMsg, true);
            if (isset($data['ping'])) {
                // 响应心跳包
                $conn->send(json_encode(['pong' => $data['ping']]));
            } elseif (isset($data['ch'])) {
                // 处理行情数据
                echo "Received market data: " . json_encode($data) . "\n";
            } else {
                // 其他消息
                echo "Received: " . json_encode($data) . "\n";
            }
        });

        // 关闭连接
        $conn->on('close', function ($code = null, $reason = null) {
            echo "Connection closed ({$code} - {$reason})\n";
        });
    }, function (\Exception $e) use ($loop) {
        echo "Could not connect: {$e->getMessage()}\n";
        $loop->stop();
    });

// 运行事件循环
$loop->run();