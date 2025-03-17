<?php

namespace app\enums;

enum TransactionTypes: string
{
    case MEV = '1';
    case PLEDGE = '2';

    // 你可以添加自定义方法
    public function label(): string
    {
        return match ($this) {
            self::MEV => '套利',
            self::PLEDGE => '质押',
        };
    }

}