<?php

namespace app\enums;

enum ChainTypes: string
{
    case SOLANA = 'SOLANA';

    // 你可以添加自定义方法
    public function label(): string
    {
        return match ($this) {
            self::SOLANA => 'SOLANA',
        };
    }

    // 静态方法，返回所有枚举的值和标签
    public static function list(): array
    {
        return [
            self::SOLANA->value => self::SOLANA->label(),
        ];
    }
}