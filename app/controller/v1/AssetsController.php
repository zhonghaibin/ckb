<?php

namespace app\controller\v1;


use support\Db;
use support\Request;


class AssetsController
{

    //充值
    public function recharge(Request $request)
    {
        $param = [
            "amount" => 0.1,
            "contract" => "0x1161725d019690a3e0de50f6be67b07a86a9fae1",
            "decimal" => 18,
            "desc" => "",
            "from" => "0x12F4900A1fB41f751b8F616832643224606B75B4",
            "memo" => "0xe595a6",
            "precision" => 0,
            "symbol" => "SPT",
            "to" => "0x34018569ee4d68a275909cc2538ff67a742f41c8",
            "action" => "transfer",
            "actionId" => "web-db4c5466-1a03-438c-90c9-2172e8becea5",
            "blockchains" => [
                [
                    "chainId" => "1",
                    "network" => "ethereum"
                ]
            ],
            "dappIcon" => "https://eosknights.io/img/icon.png",
            "dappName" => "Test demo",
            "protocol" => "TokenPocket",
            "callbackUrl" => "http://115.205.0.178:9011/taaBizApi/taaInitData",
            "version" => "2.0"
        ];
        $encodedParam = urlencode(json_encode($param));
        $url = "<a href='tpoutside://pull.activity?param=$encodedParam'>Open TokenPocket to transfer</a>";
        return response($url);
    }

    public function rechargeList(Request $request)
    {
        $recharges = Db::table('recharges')->where('user_id', $request->userId)
            ->select(['identity', 'money', 'created_at'])
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
        $recharges = Db::table('withdraws')->where('user_id', $request->userId)
            ->select(['identity', 'money', 'created_at'])
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

    //资金明细
    public function assetsList(Request $request)
    {
        $assets_logs = Db::table('assets_logs')->where('user_id', $request->userId)
            ->select("*")
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->appends(request()->get());

        return json_success($assets_logs);
    }

    public function tokenPocketSign()
    {
        $txData = [
            "from" => "0x12F4900A1fB41f751b8F616832643224606B75B4",
            "gasPrice" => "0x6c088e200",
            "gas" => "0xea60",
            "chainId" => "1",
            "to" => "0x1d1e7fb353be75669c53c18ded2abcb8c4793d80",
            "data" => "0xa9059cbb000000000000000000000000171a0b081493722a5f22ebe6f0c4adf5fde49bd8000000000000000000000000000000000000000000000000000000000012c4b0"
        ];
        $param = [
            "txData" => json_encode($txData),
            "action" => "pushTransaction",
            "actionId" => "web-db4c5466-1a03-438c-90c9-2172e8becea5",
            "blockchains" => [
                [
                    "chainId" => "1",
                    "network" => "ethereum"
                ]
            ],
            "callbackUrl" => "http://115.205.0.178:9011/taaBizApi/taaInitData",
            "dappIcon" => "https://eosknights.io/img/icon.png",
            "dappName" => "Test demo",
            "protocol" => "TokenPocket",
            "version" => "2.0"
        ];
        $encodedParam = urlencode(json_encode($param));
        $url = "<a href='tpoutside://pull.activity?param=$encodedParam'>Open TokenPocket to sign message</a>";
        return response($url);
    }

}
