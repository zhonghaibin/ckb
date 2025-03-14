<?php

namespace app\services;


use app\enums\CoinTypes;
use app\enums\HtxMarket;
use Webman\RedisQueue\Redis;

class CoinRateService
{
    const KEY = '344343434';
    const LOCK_SECOND = 20;

    public function getRealTimeRate($from, $to)
    {
        // 定义市场映射
        $marketMapping = [
            CoinTypes::USDT->value . '_' . CoinTypes::ONE->value => HtxMarket::ONEUSDT_TICKER->value,
            CoinTypes::USDT->value . '_' . CoinTypes::CKB->value => HtxMarket::CKBUSDT_TICKER->value,
            CoinTypes::ONE->value . '_' . CoinTypes::USDT->value => HtxMarket::ONEUSDT_TICKER->value,
            CoinTypes::CKB->value . '_' . CoinTypes::USDT->value => HtxMarket::CKBUSDT_TICKER->value,
        ];

        $pairKey = $from . '_' . $to;

        if (!isset($marketMapping[$pairKey])) {
            return $this->prepareResponse(0, 0, ''); // 无效交易对
        }

        // 获取数据
        $data = Redis::get($marketMapping[$pairKey]);
        if (!$data) {
            return $this->prepareResponse(0, 0, ''); // 无数据
        }

        $data = json_decode($data, true);
        $bid = $data['tick']['bid'] ?? 0; // 用户卖出价格（默认 0）

        // 计算汇率
        $rate = ($from === CoinTypes::USDT->value) ? bcdiv(1, $bid, 8) : $bid;

        $lockUntil = time() + self::LOCK_SECOND;

        // 生成签名
        $signature = $this->generateSignature($from, $to, $rate, $lockUntil);

        return $this->prepareResponse($rate, $lockUntil, $signature);
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
