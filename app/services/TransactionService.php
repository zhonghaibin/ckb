<?php

namespace app\services;

use app\enums\AssetsLogTypes;
use app\enums\CoinTypes;
use app\enums\RechargeStatus;
use app\enums\UserIsReal;
use app\model\Assets;
use app\model\AssetsLog;
use app\model\Recharge;
use app\model\User;
use app\support\Lang;
use Carbon\Carbon;
use support\Db;
use support\Log;

class TransactionService
{
   public function recharge($identity,$amount){
       $coin = CoinTypes::USDT;
       Db::beginTransaction();
       try {
           $user = User::query()->where('identity', $identity)->first();
           $assets = Assets::query()->where('user_id', $user->id)->where('coin', $coin)->firstOrFail();
           $new_balance = $assets->amount + $amount;

           if (!$assets->increment('amount', $amount)) {
               throw new \Exception(Lang::get('tips_19'));
           }

           $recharge = new Recharge();
           $recharge->user_id = $user->id;
           $recharge->coin = $coin;
           $recharge->amount = $amount;
           $recharge->remark = '';
           $recharge->status = RechargeStatus::SUCCESS;
           $recharge->fee = 0;
           $recharge->datetime = Carbon::now()->timestamp;
           if (!$recharge->save()) {
               throw new \Exception(Lang::get('tips_19'));
           }
           $assets_log = new AssetsLog;
           $assets_log->user_id = $user->id;
           $assets_log->coin = $coin;
           $assets_log->amount = $amount;
           $assets_log->balance = $new_balance;
           $assets_log->type = AssetsLogTypes::RECHARGE;
           $assets_log->remark = AssetsLogTypes::RECHARGE->label();
           $assets_log->datetime = Carbon::now()->timestamp;
           $assets_log->recharge_id = $recharge->id;
           if (!$assets_log->save()) {
               throw new \Exception(Lang::get('tips_19'));
           }
           DB::commit();
           return true;
       } catch (\Throwable $e) {
           DB::rollBack();
           Log::error($e->getMessage());
           return false;
       }
   }
}
