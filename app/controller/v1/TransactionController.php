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
use app\services\TransactionService;
use app\support\Lang;
use Webman\RedisQueue\Redis;
use support\Request;
use support\Db;
use Carbon\Carbon;

class TransactionController
{


    //质押
    public function pledge(Request $request)
    {
        $coin = $request->post('coin', CoinTypes::ONE->value);
        $amount = $request->post('amount', 500);
        $day = $request->post('day', 15);

        if (!in_array($coin, [CoinTypes::ONE->value, CoinTypes::CKB->value])) {
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
        $new_balance = bcsub($assets->amount, $amount, 6);
        if ($new_balance < 0) {
            return json_fail(Lang::get('tips_4'));
        }
        try {
            $transactionService = new TransactionService();
            $transactionService->create($user, TransactionTypes::PLEDGE, $coin, $amount, $day, $params, $new_balance);
            return json_success();
        } catch (\Throwable $e) {
            return json_fail($e->getMessage());
        }
    }

    //套利
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
        $new_balance = bcsub($assets->amount, $amount, 6);
        if ($new_balance < 0) {
            return json_fail(Lang::get('tips_4'));
        }
        try {
            $transactionService = new TransactionService();
            $transactionService->create($user, TransactionTypes::MEV, $coin, $amount, $day, $params, $new_balance);
            return json_success();
        } catch (\Throwable $e) {
            return json_fail($e->getMessage());
        }


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
