<?php

namespace app\controller\v1;


use app\services\BonusService;
use support\Request;
use support\Db;
use support\Log;

class TestController
{


    public function index(Request $request)
    {

        $data = [
            "ch" => "market.oneusdt.ticker",
            "ts" => 1741231648141,
            "tick" => [
                "open" => 0.012964,
                "high" => 0.013707,
                "low" => 0.012684,
                "close" => 0.013604,
                "amount" => 314458962.3252614,
                "vol" => 4174127.77528359,
                "count" => 57986,
                "bid" => 0.01359,  // 买入价（用户卖出）
                "bidSize" => 7931,
                "ask" => 0.01362,  // 卖出价（用户买入）
                "askSize" => 87217.42,
                "lastPrice" => 0.013604,
                "lastSize" => 6524.51
            ]
        ];

        $bid = $data['tick']['bid']; // 0.01359 (用户卖出价格)
        $ask = $data['tick']['ask']; // 0.01362 (用户买入价格)

        // 计算转换比例
        $usdtToOneRate = bcdiv(1, $ask, 6);  // 1 USDT 可兑换多少 ONE
        $oneToUsdtRate = $bid;               // 1 ONE 兑换多少 USDT

        return json([
            "USDT → ONE 兑换比例: 1 USDT = {$usdtToOneRate} ONE\n",
            "ONE → USDT 兑换比例: 1 ONE = {$oneToUsdtRate} USDT\n"
        ]);


//        BonusService::getInstance()->run();
        return response('ok');
    }





}
