<?php

namespace app\support;

use support\Log;

class Lang
{
    protected static $lang = 'zh_CN';

    public static function setLang($lang)
    {
        if (in_array($lang, config('lang.available'))) {
            static::$lang = $lang;
        }
    }

    public static function get($key, $replace = [])
    {
        $langFile = base_path("resource/translations/" . static::$lang . "/messages.php");
        if (!file_exists($langFile)) {
            return $key;
        }

        $messages = include $langFile;
        $message = $messages[$key] ?? $key;
        // 变量占位符替换
        foreach ($replace as $search => $value) {
            $message = str_replace(":$search", $value, $message);
        }

        return $message;
    }

    public static function getLang(): string
    {
        return static::$lang;
    }
}
