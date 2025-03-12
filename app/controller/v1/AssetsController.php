<?php

namespace app\controller\v1;


use app\enums\AssetsLogTypes;
use app\enums\CoinTypes;
use app\enums\ExchangeStatus;
use app\enums\WithdrawStatus;
use app\model\Assets;
use app\model\AssetsLog;
use app\model\Exchange;
use app\model\User;
use app\model\Withdraw;
use app\support\Lang;
use Carbon\Carbon;
use support\Db;
use support\Log;
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
            ->select(['amount', 'created_at'])
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->appends(request()->get());

        return json_success($recharges);
    }

    //提现
    public function withdraw(Request $request)
    {
        $amount = $request->post('amount');
        $fee = $request->post('fee', 0);
        if ($amount <= 0) {
            return json_fail(Lang::get('tips_1'));
        }

        $config = get_system_config();
        $min_number = $config['base_info']['withdraw_min_number'] ?? 0;
        if ($amount < $min_number) {
            return json_fail(Lang::get('tips_2', ['min_number' => $min_number]));
        }

        $withdraw_fee_rate = ($config['base_info']['withdraw_fee_rate'] ?? 0) / 100;
        $withdraw_fee = $withdraw_fee_rate * $amount;
        if ($withdraw_fee != $fee) {
            return json_fail(Lang::get('tips_3'));
        }

        $coin = CoinTypes::USDT;
        $user = User::query()->find($request->userId);
        $assets = Assets::query()->where('user_id', $user->id)->where('coin', $coin)->firstOrFail();
        $new_balance = $assets->amount - $amount;
        if ($new_balance < 0) {
            return json_fail(Lang::get('tips_4'));
        }

        Db::beginTransaction();
        try {
            if (!$assets->decrement('amount', $amount)) {
                throw new \Exception(Lang::get('tips_19'));
            }
            $withdraw = new Withdraw();
            $withdraw->user_id = $user->id;
            $withdraw->coin = $coin;
            $withdraw->amount = $amount;
            $withdraw->status = WithdrawStatus::PENDING;
            $withdraw->fee = $fee;
            $withdraw->fee_rate = $withdraw_fee_rate;
            $withdraw->datetime = Carbon::now()->timestamp;
            if (!$withdraw->save()) {
                throw new \Exception(Lang::get('tips_19'));
            }

            $assets_log = new AssetsLog;
            $assets_log->user_id = $user->id;
            $assets_log->coin = $coin;
            $assets_log->amount = -$amount;
            $assets_log->balance = $new_balance;
            $assets_log->type = AssetsLogTypes::WITHDRAW;
            $assets_log->remark = AssetsLogTypes::WITHDRAW->label();
            $assets_log->datetime = Carbon::now()->timestamp;
            $assets_log->withdraw_id = $withdraw->id;
            if (!$assets_log->save()) {
                throw new \Exception(Lang::get('tips_19'));
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return json_fail($e->getMessage());
        }
        return json_success();
    }

    public function withdrawList(Request $request)
    {
        $recharges = Db::table('withdraws')->where('user_id', $request->userId)
            ->select(['amount', 'created_at'])
            ->where('status', WithdrawStatus::SUCCESS)
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->appends(request()->get());

        return json_success($recharges);
    }

    //兑换
    public function exchange(Request $request)
    {
        $from_coin = $request->post('from_coin');
        $to_coin = $request->post('to_coin');
        $amount = $request->post('amount');
        $rate = $request->post('rate', 0);
        $fee = $request->post('fee', 0);

        $config = get_system_config();
        $min_number = $config['base_info']['exchange_min_number'] ?? 0;
        if ($amount < $min_number) {
            return json_fail(Lang::get('tips_2', ['min_number' => $min_number]));
        }

        if (!in_array($from_coin, [CoinTypes::ONE->value, CoinTypes::CBK->value, CoinTypes::USDT->value])) {
            return json_fail(Lang::get('tips_5'));
        }

        if (!in_array($to_coin, [CoinTypes::ONE->value, CoinTypes::CBK->value, CoinTypes::USDT->value])) {
            return json_fail(Lang::get('tips_6'));
        }

        if (in_array($from_coin, [CoinTypes::ONE->value, CoinTypes::CBK->value]) && in_array($to_coin, [CoinTypes::ONE->value, CoinTypes::CBK->value])) {
            return json_fail(Lang::get('tips_7'));
        }

        if ($amount <= 0) {
            return json_fail(Lang::get('tips_8'));
        }
        $user = User::query()->where(['id' => $request->userId])->firstOrFail();
        $from_assets = Assets::query()->where('user_id', $user->id)->where('coin', $from_coin)->firstOrFail();
        $from_new_balance = $from_assets->amount - $amount;
        if ($from_new_balance < 0) {
            return json_fail(Lang::get('tips_4'));
        }

        Db::beginTransaction();
        try {


            $to_amount = $amount;
            if ($rate != 0) {
                $to_amount = round($amount * $rate, 6);
            }

            $exchange = new Exchange;
            $exchange->user_id = $user->id;
            $exchange->from_coin = $from_coin;
            $exchange->to_coin = $to_coin;
            $exchange->rate = $rate;
            $exchange->from_amount = $amount;
            $exchange->to_amount = $to_amount;
            $exchange->fee = $fee;
            $exchange->status = ExchangeStatus::SUCCESS;
            $exchange->datetime = Carbon::now()->timestamp;
            if (!$exchange->save()) {
                throw new \Exception(Lang::get('tips_19'));
            }

            if (!$from_assets->decrement('amount', $amount)) {
                throw new \Exception(Lang::get('tips_19'));
            }


            $from_assets_log = new AssetsLog;
            $from_assets_log->user_id = $user->id;
            $from_assets_log->coin = $from_coin;
            $from_assets_log->amount = -$amount;
            $from_assets_log->balance = $from_new_balance;
            $from_assets_log->rate = $rate;
            $from_assets_log->type = AssetsLogTypes::EXCHANGE;
            $from_assets_log->remark = AssetsLogTypes::EXCHANGE->label();
            $from_assets_log->datetime = Carbon::now()->timestamp;
            $from_assets_log->exchange_id = $exchange->id;
            if (!$from_assets_log->save()) {
                throw new \Exception(Lang::get('tips_19'));
            }

            $to_assets = Assets::query()->where('user_id', $user->id)->where('coin', $to_coin)->firstOrFail();
            $to_new_balance = $to_assets->amount + $to_amount;

            $to_assets->increment('amount', $amount);
            $to_assets_log = new AssetsLog;
            $to_assets_log->user_id = $user->id;
            $to_assets_log->coin = $to_coin;
            $to_assets_log->amount = $to_amount;
            $to_assets_log->balance = $to_new_balance;
            $to_assets_log->rate = $rate;
            $to_assets_log->type = AssetsLogTypes::EXCHANGE;
            $to_assets_log->remark = AssetsLogTypes::EXCHANGE->label();
            $to_assets_log->datetime = Carbon::now()->timestamp;
            $to_assets_log->exchange_id = $exchange->id;
            if (!$to_assets_log->save()) {
                throw new \Exception(Lang::get('tips_19'));
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return json_fail($e->getMessage());
        }
        return json_success();

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
