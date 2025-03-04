<?php

namespace app\enums;

enum CoinTypes: string
{
    case USDT = 'USDT';
    case ONE = 'ONE';
    case CBK = 'CBK';

    // 你可以添加自定义方法
    public function label(): string
    {
        return match($this) {
            self::USDT => 'USDT',
            self::ONE => 'ONE',
            self::CBK => 'CBK',
        };
    }

    // 静态方法，返回所有枚举的值和标签
    public static function list(): array
    {
        return [
            self::USDT->value => self::USDT->label(),
            self::ONE->value => self::ONE->label(),
            self::CBK->value => self::CBK->label(),
        ];
    }
}