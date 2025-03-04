<?php

namespace app\enums;

enum TransactionTypes: string
{
    case SOL = 'SOL';
    case CKB = 'CKB';

    // 你可以添加自定义方法
    public function label(): string
    {
        return match($this) {
            self::SOL => 'SOL',
            self::CKB => 'CKB',
        };
    }

}