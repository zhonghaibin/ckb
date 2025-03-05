<?php

namespace process;

use Workerman\Worker;
use Workerman\Connection\AsyncTcpConnection;
use support\Db; // 使用 Webman 的 DB 组件
use Workerman\Timer;
use Illuminate\Support\Facades\Log;

class HtxWebSocket
{
    public function onWorkerStart()
    {
        $wsUrl = "wss://api.huobi.pro/ws"; // HTX WebSocket 地址
        $ws = new AsyncTcpConnection($wsUrl);

        // 连接成功时，订阅 BTC/USDT 行情
        $ws->onConnect = function ($connection) {
            echo "Connected to HTX WebSocket\n";

            $subscribe = [
                "sub" => "market.btcusdt.ticker",
                "id"  => "btcusdt_ticker"
            ];
            $connection->send(json_encode($subscribe));
        };

        // 监听 WebSocket 消息
        $ws->onMessage = function ($connection, $data) {
            $decoded = json_decode(gzdecode($data), true);

            if (isset($decoded['ping'])) {
                $connection->send(json_encode(['pong' => $decoded['ping']]));
            }

            if (isset($decoded['tick'])) {
                $price = $decoded['tick']['close'] ?? 0;
                echo "BTC/USDT 最新价格: $price\n";

                // 存入数据库
                Db::table('market_prices')->insert([
                    'symbol'    => 'BTC/USDT',
                    'price'     => $price,
                    'timestamp' => date('Y-m-d H:i:s'),
                ]);
            }
        };

        // 监听连接关闭
        $ws->onClose = function ($connection) {
            echo "WebSocket connection closed\n";
        };

        // 监听连接错误
        $ws->onError = function ($connection, $errorCode, $errorMessage) {
            echo "Error: $errorMessage\n";
        };

        $ws->connect();
    }
}
