<?php

namespace app\services;


use app\enums\HtxMarket;
use Webman\RedisQueue\Redis;

class CoinRateService
{
    const KEY = '344343434';
    const LOCK_SECOND = 200;

    public function getRealTimeRate($from, $to)
    {
        // 从 Redis 获取实时价格数据
        $data = Redis::get(HtxMarket::ONEUSDT_TICKER->value);

        // 如果 Redis 中有缓存数据
        if ($data) {
            $data = json_decode($data, true);

            // 获取买卖价格，暂时不使用，可以根据需要添加
            $bid = $data['tick']['bid'] ?? 0; // 用户卖出价格（默认 0）
            $ask = $data['tick']['ask'] ?? 0; // 用户买入价格（默认 0）

            // 计算兑换率，这里用一个固定值，实际情况可以根据 bid 或 ask 来调整
            $rate = 0.003;

            // 锁定期时间
            $lockUntil = time() + self::LOCK_SECOND;

            // 生成签名
            $signature = $this->generateSignature($from, $to, $rate, $lockUntil);

            // 返回数据
            return $this->prepareResponse($rate, $lockUntil, $signature);
        }

        // 如果没有缓存数据，返回默认值
        return $this->prepareResponse(0, 0, '');
    }

    // 生成签名的私有方法
    private function generateSignature($from, $to, $rate, $lockUntil): string
    {
        return hash_hmac('sha256', "{$from}|{$to}|{$rate}|{$lockUntil}", self::KEY);
    }

    // 统一的返回格式
    private function prepareResponse($rate, $lockUntil, $signature): array
    {
        return [
            'rate' => $rate,
            'lock_until' => $lockUntil,
            'signature' => $signature,
        ];
    }

    // 验证签名
    public function validate($from, $to, $rate, $lockUntil, $signature): bool
    {
        // 校验签名，防止前端篡改价格
        $expectedSignature = $this->generateSignature($from, $to, $rate, $lockUntil);
        if ($signature !== $expectedSignature) {
            return false;
        }
        return true;
    }


}
