<?php

namespace app\controller\v1;

use app\model\Assets;
use app\model\Member;
use app\model\Transaction;
use support\Request;
use support\Db;

class TransactionController
{
    public function create(Request $request)
    {

        $coin = $request->post('coin', 'USDT');
        $pay_coin = $request->post('pay_coin', 'ONE');
        $price = $request->post('price', 10);
        $total_day = $request->post('total_day', 15);
        $trade_sn = md5(uniqid() . mt_rand(100, 999));

        $user_id = 1;
        $member = Member::query()->where(['id' => $user_id])->firstOrFail();

        Db::beginTransaction();

        try {
            $assets = Assets::query()->where('uid', $member->id)->where('coin', 'USDT')->firstOrFail();
            if ($assets->money < $price) {
                throw new \Exception('金额不足');
            }
            $assets->decrement('money', $price);
            $transaction = new Transaction();
            $transaction->uid = $member->id;
            $transaction->identity = $member->identity;
            $transaction->coin = $coin;
            $transaction->pay_coin = $pay_coin;
            $transaction->price = $price;
            $transaction->total_day = $total_day;
            $transaction->trade_sn = $trade_sn;
            $transaction->datetime = time();
            $transaction->save();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return json_fail($e->getMessage());
        }
        return  json_success();

    }


}
