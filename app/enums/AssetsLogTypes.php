<?php

namespace app\enums;

enum AssetsLogTypes: int
{
    case DAILYEARNINGS = 1;
    case SHAREBONUS = 2;
    case LEVELDIFFBONUS = 3;
    case SAMELEVELBONUS = 4;

    // 你可以添加自定义方法
    public function label(): string
    {
        return match ($this) {
            self::DAILYEARNINGS => '每日收益',
            self::SHAREBONUS => '分享奖',
            self::LEVELDIFFBONUS => '极差奖',
            self::SAMELEVELBONUS => '平级奖',
        };
    }

}