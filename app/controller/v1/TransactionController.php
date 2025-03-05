<?php

namespace app\controller\v1;

use app\enums\AssetsLogTypes;
use app\enums\CoinTypes;
use app\enums\TransactionStatus;
use app\enums\TransactionTypes;
use app\model\Assets;
use app\model\AssetsLog;
use app\model\User;
use app\model\Transaction;
use support\Request;
use support\Db;
use Carbon\Carbon;

class TransactionController
{


    public function ckb(Request $request)
    {
        $coin = $request->post('coin', CoinTypes::ONE);
        $price = $request->post('price', 500);
        $day = $request->post('day', 15);

        if (in_array($coin, [CoinTypes::ONE, CoinTypes::CBK])) {
            return json_fail('币种错误');
        }

        if ($price < 500) {
            return json_fail('最低500起');
        }

        if (!in_array($day, [15, 30, 60])) {
            return json_fail('时间错误');
        }
        $user = User::query()->where(['id' => $request->userId])->firstOrFail();

        Db::beginTransaction();

        try {
            $assets = Assets::query()->where('user_id', $user->id)->where('coin', $coin)->firstOrFail();
            if ($assets->money < $price) {
                throw new \Exception('钱包金额不足');
            }
            $assets->decrement('money', $price);
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->identity = $user->identity;
            $transaction->coin = $coin;
            $transaction->price = $price;
            $transaction->day = $day;
            $transaction->datetime = Carbon::now()->timestamp;
            $transaction->transaction_type = TransactionTypes::CKB;
            $transaction->status = TransactionStatus::NORMAL;
            $transaction->save();

            $assets_log = new AssetsLog;
            $assets_log->user_id = $transaction->user_id;
            $assets_log->coin = $transaction->coin;
            $assets_log->identity = $transaction->identity;
            $assets_log->money = -$price;
            $assets_log->transaction_id = $transaction->id;
            $assets_log->type = AssetsLogTypes::EXPENSE;
            $assets_log->remark = AssetsLogTypes::EXPENSE->label();
            $assets_log->datetime = Carbon::now()->timestamp;
            $assets_log->save();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return json_fail($e->getMessage());
        }
        return json_success();

    }

    public function sol(Request $request)
    {
        $coin = CoinTypes::USDT;
        $price = $request->post('price', 500);
        $day = $request->post('day', 1);

        if ($price < 500) {
            return json_fail('最低500起');
        }

        if (!in_array($day, [1, 15, 30])) {
            return json_fail('时间错误');
        }
        $user = User::query()->where(['id' => $request->userId])->firstOrFail();

        Db::beginTransaction();

        try {
            $assets = Assets::query()->where('user_id', $user->id)->where('coin', $coin)->firstOrFail();
            if ($assets->money < $price) {
                throw new \Exception('钱包金额不足');
            }
            $assets->decrement('money', $price);
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->identity = $user->identity;
            $transaction->coin = $coin;
            $transaction->price = $price;
            $transaction->day = $day;
            $transaction->datetime = Carbon::now()->timestamp;
            $transaction->transaction_type = TransactionTypes::SOL;
            $transaction->status = TransactionStatus::NORMAL;
            $transaction->save();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return json_fail($e->getMessage());
        }
        return json_success();

    }

}
