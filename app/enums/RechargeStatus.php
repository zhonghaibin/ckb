<?php

namespace app\enums;

enum RechargeStatus: int
{
    case PENDING = 0;
    case SUCCESS = 1;
    case FAILED = 2;

    // 你可以添加自定义方法
    public function label(): string
    {
        return match ($this) {
            self::PENDING => '待处理',
            self::SUCCESS => '成功',
            self::FAILED => '失败',
        };
    }

    // 静态方法，返回所有枚举的值和标签
    public static function list(): array
    {
        return [
            self::PENDING->value => self::PENDING->label(),
            self::SUCCESS->value => self::SUCCESS->label(),
            self::FAILED->value => self::FAILED->label(),
        ];
    }
}