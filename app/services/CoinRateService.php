<?php

namespace app\services;


use app\enums\HtxMarket;
use app\support\Lang;
use Webman\RedisQueue\Redis;

class CoinRateService
{
    const KEY = '344343434';

    public function getRealTimeRate($from, $to)
    {

        $data = Redis::get(HtxMarket::ONEUSDT_TICKER->value);
        if ($data) {
            $data = json_decode($data,true);
//            $bid = $data['tick']['bid']; // 0.01359 (用户卖出价格)
//            $ask = $data['tick']['ask']; // 0.01362 (用户买入价格)
            $rate = 0.003;
            $lockUntil = time() + 200;
            // 生成签名，防止篡改
            $signature = hash_hmac('sha256', "{$from}|{$to}|{$rate}|{$lockUntil}", self::KEY);
            $data = [
                'rate' => $rate,
                'lock_until' => $lockUntil,
                'signature' => $signature,
            ];
        } else {
            $data = [
                'rate' => 0,
                'lock_until' => 0,
                'signature' => '',
            ];
        }

        return $data;
    }

    public function validate($from, $to, $rate, $lockUntil, $signature): bool
    {
        // 校验签名，防止前端篡改价格
        $expectedSignature = hash_hmac('sha256', "{$from}|{$to}|{$rate}|{$lockUntil}", self::KEY);
        if ($signature !== $expectedSignature) {
            return false;
        }
        return true;
    }


}
