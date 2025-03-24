<?php

namespace app\utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JwtUtil
{
    // 密钥（建议从配置文件中读取）
    private static $secretKey = 'qwe123/**-2132132';

    // 生成 JWT Token
    public static function generateToken($userId, $expire = 31536000)
    {
        $payload = [
            'iss' => 'webman', // 签发者
            'aud' => 'user',   // 接收者
            'iat' => time(),   // 签发时间
            'exp' => time() + $expire, // 过期时间
            'sub' => $userId, // 用户ID
        ];

        return JWT::encode($payload, self::$secretKey, 'HS256');
    }

    // 验证 JWT Token
    public static function validateToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key(self::$secretKey, 'HS256'));
            return (array) $decoded;
        } catch (Exception $e) {
            return false; // Token 无效
        }
    }
}