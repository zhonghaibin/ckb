<?php

namespace app\enums;

enum QueueTask: string
{
    case USER_UPGRADE = 'user-upgrade-job';
    case UPDATE_INVITE_COUNT = 'update-invite-count-job';
    case RECHARGE = 'recharge-job';

    case TRANSACTION_LOG_DETAILS = 'transaction-log-details-job';
    case BONUS = 'bonus-job';

    // 你可以添加自定义方法
    public function label(): string
    {
        return match ($this) {
            self::USER_UPGRADE => '用户升级',
            self::UPDATE_INVITE_COUNT => '直推人数和团队人数递增',
            self::RECHARGE => '充值',
            self::TRANSACTION_LOG_DETAILS => '生成交易数据',
            self::BONUS => '计算收益',
        };
    }

}