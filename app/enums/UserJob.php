<?php

namespace app\enums;

enum UserJob: string
{
    case USER_UPGRADE = 'user-upgrade-job';
    case UPDATE_INVITE_COUNT = 'update-invite-count-job';

    // 你可以添加自定义方法
    public function label(): string
    {
        return match ($this) {
            self::USER_UPGRADE => '用户升级',
            self::UPDATE_INVITE_COUNT => '直推人数和团队人数递增',
        };
    }

}