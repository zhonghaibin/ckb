<?php

namespace app\enums;

enum AssetsLogTypes: int
{

    case RECHARGE = 1;
    case WITHDRAW = 2;
    case EXCHANGE = 3;
    case EXPENSE = 4;
    case INCOME = 5;
    case DAILYINCOME = 6;
    case DIRECTBONUS = 7;

    case LEVELDIFFBONUS = 8;

    case SAMELEVELBONUS = 9;


    // 你可以添加自定义方法
    public function label(): string
    {
        return match ($this) {
            self::RECHARGE => '充值',
            self::WITHDRAW => '提现',
            self::EXCHANGE => '兑换',
            self::EXPENSE => '下单',
            self::INCOME => '结算',
            self::DAILYINCOME => '每日收益',
            self::DIRECTBONUS => '直推收益',
            self::LEVELDIFFBONUS => '极差奖',
            self::SAMELEVELBONUS => '平级奖',
        };
    }

}