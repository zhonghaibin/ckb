<?php

namespace app\middleware;

use app\utils\JwtUtil;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class JwtAuthMiddleware implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return json(['code' => 401, 'msg' => \app\support\Lang::get('token_not_provided')]);
        }

        // 去掉 Bearer 前缀
        if (strpos($token, 'Bearer ') === 0) {
            $token = substr($token, 7);
        }

        $payload = JwtUtil::validateToken($token);

        if (!$payload) {
            return json(['code' => 401, 'msg' => \app\support\Lang::get('token_invalid')]);
        }

        // 将用户信息存储到请求中，方便后续使用
        $request->userId = $payload['sub'];

        return $handler($request);
    }
}