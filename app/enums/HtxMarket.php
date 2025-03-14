<?php

namespace app\enums;

enum HtxMarket: string
{

    case BTCUSDT_TICKER = 'market.btcusdt.ticker';
    case ONEUSDT_TICKER = 'market.oneusdt.ticker';
    case CKBUSDT_TICKER = 'market.ckbusdt.ticker';


    // 你可以添加自定义方法
    public function label(): string
    {
        return match ($this) {
            self::BTCUSDT_TICKER => 'BTC/USDT',
            self::ONEUSDT_TICKER => 'ONE/USDT',
            self::CKBUSDT_TICKER => 'CKB/USDT',
        };
    }

}