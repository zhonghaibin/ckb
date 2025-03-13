<?php

namespace app\controller\v1;

use app\enums\AssetsLogTypes;
use app\enums\CoinTypes;
use app\enums\TransactionStatus;
use app\enums\TransactionTypes;
use app\enums\UserIsReal;
use app\enums\QueueTask;
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
    public function pledge(Request $request)
    {
        $coin = $request->post('coin', CoinTypes::ONE->value);
        $amount = $request->post('amount', 500);
        $day = $request->post('day', 15);

        if (!in_array($coin, [CoinTypes::ONE->value, CoinTypes::CBK->value])) {
            return json_fail(Lang::get('tips_15'));
        }

        $config = get_system_config();
        $min_number = $config['base_info']['pledge_min_number'];
        if ($amount < $min_number) {
            return json_fail(Lang::get('tips_2', ['min_number' => $min_number]));
        }

        $params = $config['pledge'][strtolower($coin)];

        $days = array_column($params['staticRate'], 'day');

        if (!in_array($day, $days)) {
            return json_fail(Lang::get('tips_16'));
        }

        $user = User::query()->where(['id' => $request->userId])->firstOrFail();

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


            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->coin = $coin;
            $transaction->amount = $amount;
            $transaction->day = $day;
            $transaction->datetime = Carbon::now()->timestamp;
            $transaction->transaction_type = TransactionTypes::PLEDGE;
            $transaction->status = TransactionStatus::NORMAL;
            $transaction->rates = json_encode($params);
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

            if (!$user->is_real == UserIsReal::DISABLE->value) {
                $user->is_real = UserIsReal::NORMAL->value;
                if (!$user->save()) {
                    throw new \Exception(Lang::get('tips_19'));
                }
            }
            DB::commit();
            Redis::send(QueueTask::USER_UPGRADE->value, ['user_id' => $user->id]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return json_fail($e->getMessage());
        }
        return json_success();

    }

    //SOL套利
    public function mev(Request $request)
    {
        $coin = CoinTypes::USDT->value;
        $amount = $request->post('amount', 500);
        $day = $request->post('day', 1);

        $config = get_system_config();
        $min_number = $config['base_info']['mev_min_number'];
        if ($amount < $min_number) {
            return json_fail(Lang::get('tips_2', ['min_number' => $min_number]));
        }
        $params = $config['mev'][strtolower($coin)];

        $days = array_column($params['staticRate'], 'day');

        if (!in_array($day, $days)) {
            return json_fail(Lang::get('tips_16'));
        }

        $user = User::query()->where(['id' => $request->userId])->firstOrFail();
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

            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->coin = $coin;
            $transaction->amount = $amount;
            $transaction->day = $day;
            $transaction->datetime = Carbon::now()->timestamp;
            $transaction->transaction_type = TransactionTypes::MEV;
            $transaction->status = TransactionStatus::NORMAL;
            $transaction->rates = json_encode($params);
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

            if (!$user->is_real == UserIsReal::DISABLE->value) {
                $user->is_real = UserIsReal::NORMAL->value;
                if (!$user->save()) {
                    throw new \Exception(Lang::get('tips_19'));
                }
            }
            DB::commit();
            Redis::send(QueueTask::USER_UPGRADE->value, ['user_id' => $user->id]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return json_fail($e->getMessage());
        }
        return json_success();

    }

    //下单列表
    public function transactionList(Request $request)
    {
        $transactionType = $request->get('transactionType', TransactionTypes::MEV);

        if (!in_array($transactionType, [TransactionTypes::MEV->value, TransactionTypes::PLEDGE->value])) {
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
        $transactionType = $request->get('transactionType', TransactionTypes::MEV);

        if (!in_array($transactionType, [TransactionTypes::MEV->value, TransactionTypes::PLEDGE->value])) {
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
