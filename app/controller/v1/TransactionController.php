<?php

namespace app\controller\v1;

use app\enums\AssetsLogTypes;
use app\enums\CoinTypes;
use app\enums\TransactionStatus;
use app\enums\TransactionTypes;
use app\enums\UserIsReal;
use app\enums\UserJob;
use app\model\Assets;
use app\model\AssetsLog;
use app\model\User;
use app\model\Transaction;
use app\support\Lang;
use Webman\RedisQueue\Redis;
use support\Request;
use support\Db;
use Carbon\Carbon;

class TransactionController
{


    //CKB质押
    public function ckb(Request $request)
    {
        $coin = $request->post('coin', CoinTypes::ONE);
        $amount = $request->post('amount', 500);
        $day = $request->post('day', 15);

        if (!in_array($coin, [CoinTypes::ONE->value, CoinTypes::CBK->value])) {
            return json_fail(Lang::get('tips_15'));
        }

        $config = get_system_config();
        $min_number = $config['base_info']['ckb_min_number'];
        if ($amount < $min_number) {
            return json_fail(Lang::get('tips_2',['min_number'=>$min_number]));
        }

        $params = $config['ckb'];
        $days = array_column($params['staticRate'], 'day');

        if (!in_array($day, $days)) {
            return json_fail(Lang::get('tips_16'));
        }

        $user = User::query()->where(['id' => $request->userId])->firstOrFail();

        Db::beginTransaction();

        try {
            $assets = Assets::query()->where('user_id', $user->id)->where('coin', $coin)->firstOrFail();
            $new_balance = $assets->amount - $amount;
            if ($new_balance < 0) {
                throw new \Exception(Lang::get('tips_4'));
            }

            $assets->decrement('amount', $amount);
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->coin = $coin;
            $transaction->amount = $amount;
            $transaction->day = $day;
            $transaction->datetime = Carbon::now()->timestamp;
            $transaction->transaction_type = TransactionTypes::CKB;
            $transaction->status = TransactionStatus::NORMAL;
            $transaction->rates = json_encode($params);
            $transaction->save();

            $assets_log = new AssetsLog;
            $assets_log->user_id = $transaction->user_id;
            $assets_log->coin = $transaction->coin;
            $assets_log->amount = -$amount;
            $assets_log->balance = $new_balance;
            $assets_log->transaction_id = $transaction->id;
            $assets_log->type = AssetsLogTypes::EXPENSE;
            $assets_log->remark = AssetsLogTypes::EXPENSE->label();
            $assets_log->datetime = Carbon::now()->timestamp;
            $assets_log->save();

            if (!$user->is_real == UserIsReal::DISABLE->value) {
                $user->is_real = UserIsReal::NORMAL->value;
                $user->save();
            }
            DB::commit();
            Redis::send(UserJob::USER_UPGRADE->value, ['user_id' => $user->id]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return json_fail($e->getMessage());
        }
        return json_success();

    }

    //SOL套利
    public function sol(Request $request)
    {
        $coin = CoinTypes::USDT;
        $amount = $request->post('amount', 500);
        $day = $request->post('day', 1);

        $config = get_system_config();
        $min_number = $config['base_info']['sol_min_number'];
        if ($amount < $min_number) {
            return json_fail(Lang::get('tips_2',['min_number'=>$min_number]));
        }

        $params = $config['sol'];
        $days = array_column($params['staticRate'], 'day');

        if (!in_array($day, $days)) {
            return json_fail(Lang::get('tips_16'));
        }

        $user = User::query()->where(['id' => $request->userId])->firstOrFail();

        Db::beginTransaction();

        try {
            $assets = Assets::query()->where('user_id', $user->id)->where('coin', $coin)->firstOrFail();
            $new_balance = $assets->amount - $amount;
            if ($new_balance < 0) {
                throw new \Exception(Lang::get('tips_4'));
            }

            $assets->decrement('amount', $amount);
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->coin = $coin;
            $transaction->amount = $amount;
            $transaction->day = $day;
            $transaction->datetime = Carbon::now()->timestamp;
            $transaction->transaction_type = TransactionTypes::SOL;
            $transaction->status = TransactionStatus::NORMAL;
            $transaction->rates = json_encode($params);
            $transaction->save();

            $assets_log = new AssetsLog;
            $assets_log->user_id = $transaction->user_id;
            $assets_log->coin = $transaction->coin;
            $assets_log->amount = -$amount;
            $assets_log->balance = $new_balance;
            $assets_log->transaction_id = $transaction->id;
            $assets_log->type = AssetsLogTypes::EXPENSE;
            $assets_log->remark = AssetsLogTypes::EXPENSE->label();
            $assets_log->datetime = Carbon::now()->timestamp;
            $assets_log->save();

            if (!$user->is_real == UserIsReal::DISABLE->value) {
                $user->is_real = UserIsReal::NORMAL->value;
                $user->save();
            }
            DB::commit();
            Redis::send(UserJob::USER_UPGRADE->value, ['user_id' => $user->id]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return json_fail($e->getMessage());
        }
        return json_success();

    }

    //下单列表
    public function transactionList(Request $request)
    {
        $transactionType = $request->get('transactionType', TransactionTypes::SOL);

        if (!in_array($transactionType, [TransactionTypes::SOL->value, TransactionTypes::CKB->value])) {
            return json_fail(Lang::get('tips_17'));
        }
        $transactions = Db::table('transactions')->where('user_id', $request->userId)
            ->where('transaction_type', $transactionType)
            ->select(['coin', 'amount', 'bonus', 'day', 'status', 'datetime', 'created_at'])
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->appends(request()->get());

        return json_success($transactions);
    }

    //收益列表
    public function transactionLogList(Request $request)
    {
        $transactionType = $request->get('transactionType', TransactionTypes::SOL);

        if (!in_array($transactionType, [TransactionTypes::SOL->value, TransactionTypes::CKB->value])) {
            return json_fail(Lang::get('tips_17'));
        }
        $transactionLogs = Db::table('transaction_logs')->where('user_id', $request->userId)
            ->where('transaction_type', $transactionType)
            ->select('coin', 'bonus', 'rate', 'datetime', 'created_at')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->appends(request()->get());

        return json_success($transactionLogs);
    }
}
