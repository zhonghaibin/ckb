<?php

namespace app\services;

use app\enums\AssetsLogTypes;
use app\enums\CoinTypes;
use app\enums\RechargeStatus;
use app\enums\WithdrawStatus;
use app\model\Assets;
use app\model\AssetsLog;
use app\model\Recharge;
use app\model\User;
use app\model\Withdraw;
use app\support\Lang;
use Carbon\Carbon;
use support\Db;
use support\Log;

class AssetsService
{
    public function recharge($user_id, $amount, $coin = CoinTypes::USDT->value, $status = RechargeStatus::SUCCESS->value, $signature = '', $user_wallet = '', $remark = '')
    {
        return Db::transaction(function () use ($user_id, $amount, $coin, $status, $signature, $user_wallet, $remark) {
            $user = User::query()->where('id', $user_id)->firstOrFail();
            $recharge = new Recharge();
            $recharge->user_id = $user->id;
            $recharge->coin = $coin;
            $recharge->amount = $amount;
            $recharge->remark = $remark;
            $recharge->status = $status;
            $recharge->signature = $signature;
            $recharge->user_wallet = $user_wallet;
            $recharge->datetime = Carbon::now()->timestamp;

            if (!$recharge->save()) {
                throw new \Exception(Lang::get('tips_19'));
            }

            if (RechargeStatus::SUCCESS->value == $status) {
                $this->rechargeLog($recharge->id, $user->id, $coin, $amount);
            }

            return $recharge->id;
        });
    }


    public function rechargeLog($recharge_id, $user_id, $coin, $amount)
    {
        return Db::transaction(function () use ($recharge_id, $user_id, $coin, $amount) {
            $assets = Assets::query()->where('user_id', $user_id)->where('coin', $coin)->lockForUpdate()->firstOrFail();

            $new_balance = bcadd($assets->amount, $amount, 6); // 避免浮点精度问题

            if (!$assets->increment('amount', $amount)) {
                throw new \Exception(Lang::get('tips_19'));
            }

            $assets_log = new AssetsLog();
            $assets_log->user_id = $user_id;
            $assets_log->coin = $coin;
            $assets_log->amount = $amount;
            $assets_log->balance = $new_balance;
            $assets_log->type = AssetsLogTypes::RECHARGE;
            $assets_log->remark = AssetsLogTypes::RECHARGE->label();
            $assets_log->datetime = Carbon::now()->timestamp;
            $assets_log->recharge_id = $recharge_id;

            if (!$assets_log->save()) {
                throw new \Exception(Lang::get('tips_19'));
            }
        });
    }




}
