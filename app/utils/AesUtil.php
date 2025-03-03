<?php

namespace app\utils;

use Exception;

class AesUtil
{
    // 加密密钥（32 字节）
    private static $key = 'your-32-byte-secret-key-1234567890';

    // 加密方法
    public static function encrypt($data)
    {
        $iv = random_bytes(16); // 生成随机的初始化向量
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', self::$key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $encrypted); // 返回 base64 编码的字符串
    }

    // 解密方法
    public static function decrypt($data)
    {
        $data = base64_decode($data);
        $iv = substr($data, 0, 16); // 提取初始化向量
        $encrypted = substr($data, 16); // 提取加密数据
        return openssl_decrypt($encrypted, 'AES-256-CBC', self::$key, OPENSSL_RAW_DATA, $iv);
    }
}