<?php

namespace app\queue\redis\slow;

use app\enums\ChainTypes;
use app\enums\QueueTask;
use app\services\UserUpgradeService;
use Carbon\Carbon;
use support\Db;
use support\Log;
use Webman\RedisQueue\Consumer;

class TransactionLogDetailsJob implements Consumer
{


    public $queue = QueueTask::TRANSACTION_LOG_DETAILS->value;

    public function consume($data)
    {
        $amount = $data['amount'] ?? 0;
        $startTime = $data['start_time'] ?? 0;
        $endTime = $data['end_time'] ?? 0;
        $transaction_log_id = $data['transaction_log_id'] ?? 0;
        $from_contract_hash = $data['from_contract_hash'] ?? 0;
        $transaction_id = $data['transaction_id'] ?? 0;
        $user_id = $data['user_id'] ?? 0;

        //生成记录
        $this->generatePriceRecords($user_id, $amount, $startTime, $endTime, $transaction_id, $transaction_log_id, $from_contract_hash);

    }

    private function generatePriceRecords($user_id, $totalPrice, $startTime, $endTime, $transaction_id = 0, $transaction_log_id = 0, $from_contract_hash = 0): void
    {
        $records = [];
        $remainingAmount = $totalPrice;
        $solanaTokens = get_solana_tokens();
        $randomKey = array_rand($solanaTokens);
        $randomToken = $solanaTokens[$randomKey];

        // 计算时间槽
        $remainingTimeSlots = max(1, ($endTime - $startTime) / 90);
        $maxAmount = max(0.00000001, ($remainingAmount / $remainingTimeSlots) * 1.5);

        while ($startTime < $endTime && $remainingAmount > 0) {
            $interval = mt_rand(60, 180);
            $startTime += $interval;
            if ($startTime > $endTime) {
                $startTime = $endTime;
            }

            $amount = round(min($remainingAmount, max(0.00000001, mt_rand(1, (int)($maxAmount * 100000000)) / 100000000)), 8);
            $date = date('Y-m-d H:i:s', $startTime);

            $records[] = [
                'user_id' => $user_id,
                'amount' => $amount,
                'chain' => ChainTypes::SOLANA->value,
                'transaction_id' => $transaction_id,
                'transaction_hash' => get_transaction_hash(),
                'transaction_log_id' => $transaction_log_id,
                'from_contract_hash' => $from_contract_hash,
                'symbol' => $randomToken['symbol'],
                'contract' => $randomToken['contract'],
                'datetime' => $startTime,
                'created_at' => $date,
                'updated_at' => $date,
                'rate' => 0, // 先占位
            ];

            $remainingAmount -= $amount;

            if ($remainingAmount < 0.00000001) {
                if ($remainingAmount > 0) {
                    $records[count($records) - 1]['amount'] += $remainingAmount;
                }
                break;
            }
        }

        // **计算每个金额的占比**
        foreach ($records as &$record) {
            $record['rate'] = round($record['amount'] / $totalPrice, 8);
        }

        // **批量插入**
        if (!empty($records)) {
            DB::table('transaction_log_details')->insert($records);
        }
    }



    public function onConsumeFailure(\Throwable $e, $package)
    {
        //每次消费失败时触发
    }
}
