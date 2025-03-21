<?php

namespace app\services;


use app\enums\AssetsLogTypes;
use app\enums\QueueTask;
use app\enums\TransactionStatus;
use app\enums\UserIsReal;
use app\model\Assets;
use app\model\AssetsLog;
use app\model\Transaction;
use app\model\User;
use app\support\Lang;
use Carbon\Carbon;
use support\Db;
use Webman\RedisQueue\Redis;

class TransactionService
{

    public function create(User $user, $transaction_type, $coin, $amount, $day, $params, $new_balance)
    {
        Db::beginTransaction();

        try {
            $assets = Assets::query()->where('user_id', $user->id)->where('coin', $coin)->lockForUpdate()->firstOrFail();
            if (!$assets->decrement('amount', $amount)) {
                throw new \Exception(Lang::get('tips_19'));
            }

            $transaction = new Transaction;
            $transaction->user_id = $user->id;
            $transaction->coin = $coin;
            $transaction->amount = $amount;
            $transaction->day = $day;
            $transaction->datetime = Carbon::now()->timestamp;
            $transaction->transaction_type = $transaction_type;
            $transaction->status = TransactionStatus::NORMAL;
            $transaction->rates = json_encode($params);
            $transaction->runtime = time();
            if (!$transaction->save()) {
                throw new \Exception(Lang::get('tips_19'));
            }

            $assets_log = new AssetsLog;
            $assets_log->user_id = $transaction->user_id;
            $assets_log->coin = $transaction->coin;
            $assets_log->amount = -$amount;
            $assets_log->balance = $new_balance;
            $assets_log->transaction_id = $transaction->id;
            $assets_log->type = AssetsLogTypes::EXPENSE;
            $assets_log->remark = AssetsLogTypes::EXPENSE->label();
            $assets_log->datetime = Carbon::now()->timestamp;
            if (!$assets_log->save()) {
                throw new \Exception(Lang::get('tips_19'));
            }

            if ($user->is_real == UserIsReal::DISABLE->value) {
                $user->is_real = UserIsReal::NORMAL->value;
                if (!$user->save()) {
                    throw new \Exception(Lang::get('tips_19'));
                }
            }
            DB::commit();
            Redis::send(QueueTask::USER_UPGRADE->value, ['user_id' => $user->id]);
            Redis::send(QueueTask::BONUS->value, ['transaction_id' => $transaction->id]);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

}
