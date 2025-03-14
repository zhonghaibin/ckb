<?php

namespace app\controller\v1;


use app\enums\AssetsLogTypes;
use app\enums\CoinTypes;
use app\enums\ExchangeStatus;
use app\enums\HtxMarket;
use app\enums\QueueTask;
use app\enums\RechargeStatus;
use app\enums\WithdrawStatus;
use app\model\Assets;
use app\model\AssetsLog;
use app\model\Exchange;
use app\model\User;
use app\model\Withdraw;
use app\services\AssetsService;
use app\services\CoinRateService;
use app\support\Lang;
use Carbon\Carbon;
use support\Db;
use support\Request;
use Webman\RedisQueue\Redis;


class AssetsController
{

    //充值
    public function recharge(Request $request,AssetsService $assetsService)
    {

        $user_wallet = $request->post('user_wallet', '');
        $signature = $request->post('signature', '');
        $amount = $request->post('amount', '');
        $recharge_id = $assetsService->recharge($request->userId, $amount, CoinTypes::USDT, RechargeStatus::PENDING, $signature, $user_wallet);
        if ($recharge_id) {
            Redis::send(QueueTask::RECHARGE->value, [
                'recharge_id' => $recharge_id
            ], 30);
        }

        return json_success();
    }

    public function rechargeList(Request $request)
    {
        $recharges = Db::table('recharges')->where('user_id', $request->userId)
            ->select(['amount', 'signature', 'status', 'created_at'])
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
        $withdraw_fee = round($withdraw_fee_rate * $amount, 6);
        if ($withdraw_fee != $fee) {
            return json_fail(Lang::get('tips_3'));
        }

        $coin = CoinTypes::USDT;
        $user = User::query()->find($request->userId);
        $assets = Assets::query()->where('user_id', $user->id)->where('coin', $coin)->lockForUpdate()->firstOrFail();
        $new_balance = bcsub($assets->amount, $amount, 6);
        if ($new_balance < 0) {
            return json_fail(Lang::get('tips_4'));
        }
        $new_amount = $amount + $withdraw_fee;

        Db::beginTransaction();
        try {
            if (!$assets->decrement('amount', $new_amount)) {
                throw new \Exception(Lang::get('tips_19'));
            }
            $withdraw = new Withdraw();
            $withdraw->user_id = $user->id;
            $withdraw->coin = $coin;
            $withdraw->amount = $amount;
            $withdraw->status = WithdrawStatus::PENDING->value;
            $withdraw->fee = $fee;
            $withdraw->fee_rate = $withdraw_fee_rate;
            $withdraw->datetime = Carbon::now()->timestamp;
            $withdraw->user_wallet = $user->identity;
            if (!$withdraw->save()) {
                throw new \Exception(Lang::get('tips_19'));
            }

            $assets_log = new AssetsLog;
            $assets_log->user_id = $user->id;
            $assets_log->coin = $coin;
            $assets_log->amount = -$new_amount;
            $assets_log->balance = $new_balance;
            $assets_log->type = AssetsLogTypes::WITHDRAW->value;
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
            ->select(['amount', 'signature', 'status', 'created_at'])
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->appends(request()->get());

        return json_success($recharges);
    }

    //兑换
    public function exchange(Request $request, CoinRateService $coinRateService)
    {
        $from_coin = $request->post('from_coin');
        $to_coin = $request->post('to_coin');
        $amount = $request->post('amount');
        $rate = $request->post('rate', 0);
        $fee = $request->post('fee', 0);
        $lockUntil = $request->input('lock_until');
        $signature = $request->input('signature');

        return json([$signature]);
        // 校验是否过期
        if (time() > $lockUntil) {
            return json_fail(Lang::get('tips_20'));
        }

        if (!$coinRateService->validate($from_coin, $to_coin, $rate, $lockUntil, $signature)) {
            return json_fail(Lang::get('tips_21'));
        }

        return json_success($rate);

        $config = get_system_config();
        $min_number = $config['base_info']['exchange_min_number'] ?? 0;
        if ($amount < $min_number) {
            return json_fail(Lang::get('tips_2', ['min_number' => $min_number]));
        }

        if (!in_array($from_coin, [CoinTypes::ONE->value, CoinTypes::CKB->value, CoinTypes::USDT->value])) {
            return json_fail(Lang::get('tips_5'));
        }

        if (!in_array($to_coin, [CoinTypes::ONE->value, CoinTypes::CKB->value, CoinTypes::USDT->value])) {
            return json_fail(Lang::get('tips_6'));
        }

        // one 和 ckb 相互不可以兑换
        if (in_array($from_coin, [CoinTypes::ONE->value, CoinTypes::CKB->value]) && in_array($to_coin, [CoinTypes::ONE->value, CoinTypes::CKB->value])) {
            return json_fail(Lang::get('tips_7'));
        }

        if ($amount <= 0) {
            return json_fail(Lang::get('tips_8'));
        }
        $user = User::query()->where(['id' => $request->userId])->firstOrFail();
        $from_assets = Assets::query()->where('user_id', $user->id)->where('coin', $from_coin)->lockForUpdate()->firstOrFail();
        $from_new_balance = bcsub($from_assets->amount, $amount, 6);
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

            $to_assets = Assets::query()->where('user_id', $user->id)->where('coin', $to_coin)->lockForUpdate()->firstOrFail();
            $to_new_balance = bcadd($to_assets->amount, $to_amount, 6);

            $to_assets->increment('amount', $to_amount);
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

    public function getRate(Request $request, CoinRateService $coinRateService)
    {
        $from_coin = $request->post('from_coin');
        $to_coin = $request->post('to_coin');
        $data = $coinRateService->getRealTimeRate($from_coin, $to_coin);
        return json_success($data);
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
}
