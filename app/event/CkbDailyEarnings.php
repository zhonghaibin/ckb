<?php

namespace app\event;

use app\model\Assets;
use app\model\Member;
use app\model\Transaction;
use app\model\TransactionLog;
use Carbon\Carbon;
use support\Db;
use support\Log;

class CkbDailyEarnings
{

    protected array $rates = [
        15 => 0.08,
        30 => 0.12,
        60 => 0.15,
    ];


    //每日收益
    public function earnings(): bool
    {
        try {
            // 获取所有符合条件的交易记录
            $transactions = Transaction::query()->whereColumn('run_day', '<', 'day')->get();

            foreach ($transactions as $transaction) {
                // 开始一个事务，每个循环都是一个独立的事务
                DB::beginTransaction();

                try {
                    // 计算当月的天数
                    $month_day = get_time_in_month($transaction->datetime);
                    // 获取对应的 rate 值
                    $rate = $this->rates[$transaction->day] ?? 0;
                    // 计算 bonus
                    $bonus = $transaction->price * $rate / $month_day;

                    // 自增字段 bonus 和 run_day
                    $transaction->increment('bonus', $bonus);
                    $transaction->increment('run_day');

                    // 创建 transaction log
                    $transactionLog = new TransactionLog;
                    $transactionLog->uid = $transaction->uid;
                    $transactionLog->identity = $transaction->identity;
                    $transactionLog->transaction_id = $transaction->id;
                    $transactionLog->coin = $transaction->coin;
                    $transactionLog->bonus = $bonus;
                    $transactionLog->rate = $rate;
                    $transactionLog->datetime = Carbon::now()->timestamp;
                    $transactionLog->save();

                    // 更新用户的资产
                    $assets = Assets::query()->where('uid', $transaction->uid)->where('coin', $transaction->coin)->first();
                    $assets->increment('money', $bonus);

                    // 提交事务
                    DB::commit();
                } catch (\Exception $e) {
                    // 记录失败的错误日志
                    Log::error('Error processing transaction ID: ' . $transaction->id, [
                        'exception' => $e->getMessage(),
                        'transaction_id' => $transaction->id
                    ]);

                    // 回滚事务，避免部分更新被保存
                    DB::rollBack();

                    // 跳过当前交易记录，继续处理下一个
                    continue;
                }
            }

            return true;
        } catch (\Exception $e) {
            // 记录整个过程的错误日志
            Log::error('Error during daily earnings processing', [
                'exception' => $e->getMessage()
            ]);
            return false;
        }
    }


}