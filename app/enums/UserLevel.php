<?php

namespace app\enums;

enum UserLevel: int
{
    case VIP0 = 0;
    case VIP1 = 1;
    case VIP2 = 2;
    case VIP3 = 3;
    case VIP4 = 4;
    case VIP5 = 5;
    case VIP6 = 6;
    case VIP7 = 7;
    case VIP8 = 8;
    case VIP9 = 9;

    // 你可以添加自定义方法
    public function label(): string
    {
        return match ($this) {
            self::VIP0 => 'VIP0',
            self::VIP1 => 'VIP1',
            self::VIP2 => 'VIP2',
            self::VIP3 => 'VIP3',
            self::VIP4 => 'VIP4',
            self::VIP5 => 'VIP5',
            self::VIP6 => 'VIP6',
            self::VIP7 => 'VIP7',
            self::VIP8 => 'VIP8',
            self::VIP9 => 'VIP9',
        };
    }

}