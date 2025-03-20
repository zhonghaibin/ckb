<?php

namespace app\queue\redis\fast;

use app\enums\QueueTask;
use app\model\Transaction;
use app\services\BonusService;
use Webman\RedisQueue\Consumer;

class BonusJob implements Consumer
{


    public $queue = QueueTask::BONUS->value;

    public function consume($data)
    {
        $transaction_id = $data['transaction_id'] ?? 0;
        $transaction = Transaction::query()->find($transaction_id);
        if ($transaction) {
            (new BonusService())->runOne($transaction);
        }

    }


    public function onConsumeFailure(\Throwable $e, $package)
    {
        //每次消费失败时触发
    }
}
