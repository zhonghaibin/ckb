<?php

namespace app\middleware;

use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class CorsMiddleware implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {
        // 如果是 OPTIONS 请求，直接返回空响应
        if ($request->method() === 'OPTIONS') {
            $response = response('');
        } else {
            // 继续处理请求
            $response = $handler($request);
        }

        // 设置跨域头
        $response->withHeaders([
            'Access-Control-Allow-Origin' => '*', // 允许所有域名访问
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS', // 允许的请求方法
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With', // 允许的请求头
            'Access-Control-Allow-Credentials' => 'true', // 允许携带凭证（如 Cookie）
        ]);

        return $response;
    }
}