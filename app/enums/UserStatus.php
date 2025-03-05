<?php

namespace app\enums;

enum UserStatus: int
{
    case DISABLE = -1;
    case NORMAL = 0;
    case ABNORMAL = 1;

    // 你可以添加自定义方法
    public function label(): string
    {
        return match($this) {
            self::DISABLE => '禁用',
            self::NORMAL => '正常',
            self::ABNORMAL => '异常',
        };
    }

}