<?php

namespace app\exception;

use support\exception\Handler;
use Throwable;
use support\Response;

class ExceptionHandler extends Handler
{
    public function render($request, Throwable $exception): Response
    {
        // 返回统一的 JSON 错误响应
        return json_fail($exception->getMessage(), $exception->getCode() ?: 500);
    }
}