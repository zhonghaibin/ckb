<?php

namespace app\enums;

enum HtxMarket: string
{

    case BTCUSDT_TICKER = 'market.btcusdt.ticker';
    case ONEUSDT_TICKER = 'market.oneusdt.ticker';


    // 你可以添加自定义方法
    public function label(): string
    {
        return match ($this) {
            self::BTCUSDT_TICKER => 'BTCUSDT',
            self::ONEUSDT_TICKER => 'ONEUSDT',
        };
    }

}