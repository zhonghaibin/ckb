<?php

namespace app\enums;

enum UserUpgrade: string
{
    case USER_UPGRADE_JOB = 'user-upgrade-job';


    // 你可以添加自定义方法
    public function label(): string
    {
        return match ($this) {
            self::USER_UPGRADE_JOB => '用户升级',
        };
    }

}