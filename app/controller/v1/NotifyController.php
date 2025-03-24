<?php

namespace app\controller\v1;

use app\enums\CoinTypes;
use app\enums\QueueTask;
use app\enums\RechargeStatus;
use app\model\User;
use app\services\AssetsService;
use support\Request;
use Webman\RedisQueue\Redis;

class NotifyController
{

    public function recharge(Request $request)
    {
        $amount = $request->post('amount');
        $identity = $request->post('identity');
        $user = User::query()->where('identity', $identity)->firstOrFail();
        $transactionService = new AssetsService();
        $transactionService->recharge($user->id, $amount,0,0,CoinTypes::USDT->value,RechargeStatus::SUCCESS->value,time());
        return json_success();

    }

    public function pushRecharge(Request $request)
    {
        $recharge_id = $request->post('recharge_id');
        Redis::send(QueueTask::RECHARGE->value, [
            'recharge_id' => $recharge_id,
        ], 1);

        return json_success();
    }
}
