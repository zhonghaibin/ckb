<?php

namespace app\controller\v1;

use app\enums\QueueTask;
use app\services\AssetsService;
use support\Request;
use Webman\RedisQueue\Redis;

class NotifyController
{

    public function recharge(Request $request)
    {
        $amount = $request->post('amount');
        $identity = $request->post('identity');
        $transactionService = new AssetsService();
        $transactionService->recharge($identity, $amount);
        return json_success();

    }

    public function push(Request $request)
    {
        $recharge_id = $request->post('recharge_id');
        Redis::send(QueueTask::RECHARGE->value, [
            'recharge_id' => $recharge_id,
        ], 30);
    }
}
