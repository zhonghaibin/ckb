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

        // **第一条数据，时间固定为 $startTime**
        $date = date('Y-m-d H:i:s', $startTime);
        $amount = round(min($remainingAmount, max(0.00000001, mt_rand(1, (int)($maxAmount * 100000000)) / 100000000)), 8);

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
            'rate' => 0, // 占位
            'endtime' => null, // 占位
        ];

        $remainingAmount -= $amount;

        while ($startTime < $endTime && $remainingAmount > 0) {
            // **随机间隔时间（仅从第二条开始）**
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
                'rate' => 0,
                'endtime' => null,
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


        // **设置 endtime**
        $count = count($records);
        for ($i = 0; $i < $count - 1; $i++) {
            $endTime = $records[$i + 1]['datetime'];
            $endTime = $endTime + mt_rand(90, 200);
            $lastTimestamp = strtotime('today 23:59:59');
            if ($endTime > $lastTimestamp) {
                $endTime = $lastTimestamp;
            }
            $records[$i]['endtime'] = $endTime;
        }

        // **最后一条记录的 endtime 设为当天 23:59:59**
        if ($count > 0) {
            $lastRecordTime = $records[$count - 1]['datetime'];
            $records[$count - 1]['endtime'] = strtotime(date('Y-m-d 23:59:59', $lastRecordTime));
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
