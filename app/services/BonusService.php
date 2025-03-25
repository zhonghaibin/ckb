<?php

namespace app\services;

use app\enums\ChainTypes;
use app\enums\QueueTask;
use app\enums\TransactionStatus;
use app\enums\TransactionTypes;
use app\model\Transaction;
use Carbon\Carbon;
use support\Db;
use support\Log;
use Webman\RedisQueue\Redis;

class BonusService
{
    protected float $rate = 0;
    protected int $end_time = 0;

    public function run(): bool
    {
        try {

            // 分批处理事务，每批次取 500 条
            Db::table('transactions')
                ->orderBy('id')
                ->where('status', TransactionStatus::NORMAL->value)
                ->whereRaw('run_day < day')
                ->where('runtime', '<', time())
                ->chunk(500, function ($transactions) {
                    foreach ($transactions as $transaction) {
                        $params = json_decode($transaction->rates, true);
                        $this->initializeRates($params, $transaction);

                        Db::beginTransaction();
                        try {
                            $this->processTransaction($transaction);
                            Db::commit();
                        } catch (\Throwable $e) {
                            Db::rollBack();
                            Log::error("Error processing transaction ID: {$transaction->id}", ['exception' => $e->getMessage()]);
                        }
                    }
                });

            return true;
        } catch (\Throwable $e) {
            Log::error('Error during daily earnings processing', ['exception' => $e->getMessage()]);
            return false;
        }
    }

    public function runOne(Transaction $transaction)
    {
        try {

            $params = json_decode($transaction->rates, true);
            $this->initializeRates($params, $transaction);

            Db::beginTransaction();
            try {
                $this->processTransaction($transaction);
                Db::commit();
            } catch (\Throwable $e) {
                Db::rollBack();
                Log::error("Exec Error processing transaction ID: {$transaction->id}", ['exception' => $e->getMessage()]);
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('Exec Error during daily earnings processing', ['exception' => $e->getMessage()]);
            return false;
        }
    }

    //设置参数
    private function initializeRates(array $params, $transaction): void
    {
        if ($transaction->transaction_type == TransactionTypes::PLEDGE->value) {
            //质押
            $rates = array_column($params['staticRate'], 'rate', 'day');
            $this->rate = bcdiv(($rates[$transaction->day] ?? 0), 100, 8);
        } elseif ($transaction->transaction_type == TransactionTypes::MEV->value) {
            //套利
            $rates = array_column(array_map(fn($item) => [$item['day'], [(int)$item['rate']['min'], (int)$item['rate']['max']]], $params['staticRate']), 1, 0);
            $rateRange = $rates[$transaction->day] ?? [0, 0];
            $min = $rateRange[0];
            $max = $rateRange[1];
            $randomRate1 = mt_rand($min * 100, $max * 100) / 100;
            $randomRate2 = mt_rand($min * 100, $max * 100) / 100;
            $this->rate = bcdiv(bcadd($randomRate1, $randomRate2, 8), 2, 8);
            $this->rate = bcdiv($this->rate, 100, 8);

        }
        $this->end_time = time() + 86400;
    }


    //每日收益
    private function processTransaction($transaction): void
    {
        $month_day = get_time_in_month($transaction->datetime);

        $bonus = round($transaction->amount * $this->rate / $month_day, 6);
        Db::table('transactions')->where('id', $transaction->id)->update([
            'runtime' => Db::raw("`runtime` + 86400"),
            'run_day' => Db::raw('run_day + 1'),
        ]);

        $transaction_hash = get_transaction_hash();
        $transactionLogId = Db::table('transaction_logs')->insertGetId([
            'user_id' => $transaction->user_id,
            'transaction_id' => $transaction->id,
            'coin' => $transaction->coin,
            'rate' => $this->rate,
            'transaction_type' => $transaction->transaction_type,
            'datetime' => Carbon::now()->timestamp,
            'bonus' => $bonus,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'transaction_hash' => $transaction_hash,
            'runtime' => time() + 86400,
            'chain' => ChainTypes::SOLANA->value
        ]);

        if ($transaction->transaction_type == TransactionTypes::MEV->value) {
            Redis::send(QueueTask::TRANSACTION_LOG_DETAILS->value, [
                'user_id' => $transaction->user_id,
                'amount' => $bonus,
                'start_time' => time(),
                'end_time' => $this->end_time,
                'transaction_id' => $transaction->id,
                'transaction_log_id' => $transactionLogId,
                'from_contract_hash' => $transaction_hash,
            ], 1);
        }


    }
}