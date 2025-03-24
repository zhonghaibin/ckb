<?php

namespace app\controller\v1;

use app\enums\CoinTypes;
use app\enums\QueueTask;
use app\enums\RechargeStatus;
use app\model\User;
use app\services\AssetsService;
use app\support\Lang;
use support\Request;
use Webman\RedisQueue\Redis;

class NotifyController
{

    public function recharge(Request $request)
    {
        $amount = $request->post('amount');
        $identity = $request->post('identity');
        $user = User::query()->where('identity', $identity)->firstOrFail();

        $config = get_system_config();
        $min_number = $config['base_info']['recharge_min_number'];
        if ($amount < $min_number) {
            return json_fail(Lang::get('tips_2', ['min_number' => $min_number]));
        }
        $recharge_fee_rate = ($config['base_info']['recharge_fee_rate'] ?? 0) / 100;
        $recharge_fee = bcmul($recharge_fee_rate, $amount, 8);
        $transactionService = new AssetsService();
        $transactionService->recharge($user->id, $amount,$recharge_fee,$recharge_fee_rate,CoinTypes::USDT->value,RechargeStatus::SUCCESS->value,time());
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
