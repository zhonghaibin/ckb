<?php

namespace app\enums;

enum TransactionStatus: int
{
    case NORMAL = 1;
    case DONE = 2;

    // 你可以添加自定义方法
    public function label(): string
    {
        return match($this) {
            self::NORMAL => '进行中',
            self::DONE => '已结束',
        };
    }

}