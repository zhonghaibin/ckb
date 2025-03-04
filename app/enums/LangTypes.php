<?php

namespace app\enums;

enum LangTypes: string
{
    case ZH_CN = 'zh_CN';
    case EN = 'en';

    // 你可以添加自定义方法
    public function label(): string
    {
        return match($this) {
            self::ZH_CN => '中文',
            self::EN => '英文',
        };
    }

}