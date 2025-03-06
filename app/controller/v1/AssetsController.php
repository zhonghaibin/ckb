<?php

namespace app\controller\v1;


use app\model\Recharge;
use app\model\Withdraw;
use support\Request;


class AssetsController
{

    //充值
    public function recharge(Request $request)
    {
            //充值的按钮
    }

    public function rechargeList(Request $request)
    {
        $recharges = Recharge::query()->where('user_id', $request->userId)
            ->select(['identity','money','created_at'])
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->appends(request()->get());

        return json_success($recharges);
    }

    //提现
    public function withdraw(Request $request)
    {
        //提现的按钮
    }

    public function withdrawList(Request $request)
    {
        $recharges = Withdraw::query()->where('user_id', $request->userId)
            ->select(['identity','money','created_at'])
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->appends(request()->get());

        return json_success($recharges);
    }

    //兑换
    public function exchange(Request $request)
    {

    }

}
