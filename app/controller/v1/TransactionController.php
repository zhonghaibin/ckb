<?php

namespace app\controller\v1;

use app\enums\CoinTypes;
use app\model\Assets;
use app\model\Member;
use app\model\Transaction;
use support\Request;
use support\Db;
use Carbon\Carbon;

class TransactionController
{
    public function create(Request $request)
    {
        $coin = $request->post('coin', CoinTypes::ONE);
        $price = $request->post('price', 10);
        $day = $request->post('day', 15);


        $user_id = 1;
        $member = Member::query()->where(['id' => $user_id])->firstOrFail();

        Db::beginTransaction();

        try {
            $assets = Assets::query()->where('uid', $member->id)->where('coin', CoinTypes::USDT)->firstOrFail();
            if ($assets->money < $price) {
                throw new \Exception('金额不足');
            }
            $assets->decrement('money', $price);
            $transaction = new Transaction();
            $transaction->uid = $member->id;
            $transaction->identity = $member->identity;
            $transaction->coin = $coin;
            $transaction->price = $price;
            $transaction->day = $day;
            $transaction->datetime = Carbon::now()->timestamp;
            $transaction->save();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return json_fail($e->getMessage());
        }
        return json_success();

    }


}
