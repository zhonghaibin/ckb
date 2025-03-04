<?php

namespace app\enums;

enum MemberIsReal: int
{
    case DISABLE = 0;
    case NORMAL = 1;

    // 你可以添加自定义方法
    public function label(): string
    {
        return match ($this) {
            self::DISABLE => '未启用',
            self::NORMAL => '已启用',
        };
    }

}