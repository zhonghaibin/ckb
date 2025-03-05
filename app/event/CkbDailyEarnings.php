<?php

namespace app\event;

use app\enums\AssetsLogTypes;
use app\enums\UserIsReal;
use app\enums\TransactionStatus;
use app\enums\TransactionTypes;
use app\model\Assets;
use app\model\AssetsLog;
use app\model\User;
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

    protected float $share_rate = 0.2;

    protected array $level_diff_rates = [
        0 => 0,
        1 => 0.02,
        2 => 0.04,
        3 => 0.06,
        4 => 0.08,
        5 => 0.1,
        6 => 0.15,
        7 => 0.2,
        8 => 0.25,
        9 => 0.3,
    ];

    protected float $same_level_rate = 0.05;

    //每日收益
    public function earnings(): bool
    {
        try {
            // 获取所有符合条件的交易记录
            $transactions = Transaction::query()
                ->where('transaction_type', TransactionTypes::CKB)
                ->where('status', TransactionStatus::NORMAL)
                ->whereColumn('run_day', '<', 'day')
                ->get();

            foreach ($transactions as $transaction) {
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
                    $transactionLog->user_id = $transaction->user_id;
                    $transactionLog->identity = $transaction->identity;
                    $transactionLog->transaction_id = $transaction->id;
                    $transactionLog->coin = $transaction->coin;
                    $transactionLog->rate = $rate;
                    $transactionLog->transaction_type = TransactionTypes::CKB;
                    $transactionLog->datetime = Carbon::now()->timestamp;
                    $transactionLog->increment('bonus', $bonus);
                    $transactionLog->save();

                    if ($transaction->run_day == $transaction->day) {
                        $transaction->status = TransactionStatus::DONE;
                        $transaction->save();
                        // 更新用户的资产
                        $assets = Assets::query()->where('user_id', $transaction->user_id)->where('coin', $transaction->coin)->first();
                        $money = $transactionLog->bonus + $transaction->price;
                        $assets->increment('money', $money);

                        //资金日志
                        $assets_log = new AssetsLog;
                        $assets_log->user_id = $transaction->user_id;
                        $assets_log->coin = $transaction->coin;
                        $assets_log->identity = $transaction->identity;
                        $assets_log->money = $money;
                        $assets_log->rate = $rate;
                        $assets_log->transaction_id = $transaction->id;
                        $assets_log->transaction_log_id = $transactionLog->id;
                        $assets_log->type = AssetsLogTypes::DAILYEARNINGS;
                        $assets_log->remark = '每日收益';
                        $assets_log->datetime = Carbon::now()->timestamp;
                        $assets_log->save();

                    }
                    $user = User::query()->find($transaction->id);

                    if ($user->pid > 0) {
                        $parent = User::query()->find($user->pid);

                        if ($parent) {
                            //分享奖
                            $this->shareBonus($parent, $bonus, $transaction,$transactionLog);
                            //极差奖
                            $this->levelDiffBonus($parent, $bonus, $transaction,$transactionLog, $user->level);
                        }

                        //平级奖
                        $this->sameLevelBonus($user, $bonus, $transaction,$transactionLog);
                    }


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

    //分享奖
    private function shareBonus($parent, $bonus,$transaction,$transactionLog)
    {
        if ($parent->is_real == UserIsReal::NORMAL) {
            $parent_bonus = $this->share_rate * $bonus;
            $parent_assets = Assets::query()->where('user_id', $parent->id)->where('coin', $transaction->coin)->first();
            $parent_assets->increment('money', $parent_bonus);

            //资金日志
            $assets_log = new AssetsLog;
            $assets_log->user_id = $parent->user_id;
            $assets_log->coin = $transaction->coin;
            $assets_log->identity = $parent->identity;
            $assets_log->money = $parent_bonus;
            $assets_log->rate = $this->share_rate ;
            $assets_log->transaction_id = $transaction->id;
            $assets_log->transaction_log_id = $transactionLog->id;
            $assets_log->type = AssetsLogTypes::SHAREBONUS;
            $assets_log->remark = '分享收益';
            $assets_log->datetime = Carbon::now()->timestamp;
            $assets_log->save();
        }


    }

    //极差奖
    private function levelDiffBonus($parent, $bonus, $transaction,$transactionLog, $user_level): void
    {

        if ($parent->is_real == UserIsReal::NORMAL) {
            $user_level_diff_rate = $this->level_diff_rates[$user_level];
            $parent_level_diff_rate = $this->level_diff_rates[$parent->level];
            $level_diff_rate = $parent_level_diff_rate - $user_level_diff_rate;


            $parent_bonus = $level_diff_rate * $bonus;
            $parent_assets = Assets::query()->where('user_id', $parent->id)->where('coin', $transaction->coin)->first();
            $parent_assets->increment('money', $parent_bonus);

            //资金日志
            $assets_log = new AssetsLog;
            $assets_log->user_id = $parent->user_id;
            $assets_log->coin = $transaction->coin;
            $assets_log->identity = $parent->identity;
            $assets_log->money = $parent_bonus;
            $assets_log->rate = $this->share_rate ;
            $assets_log->transaction_id = $transaction->id;
            $assets_log->transaction_log_id = $transactionLog->id;
            $assets_log->type = AssetsLogTypes::LEVELDIFFBONUS;
            $assets_log->remark = '极差收益';
            $assets_log->datetime = Carbon::now()->timestamp;
            $assets_log->save();


        }
        if ($parent->pid > 0) {
            $user = User::query()->find($parent->pid);
            $this->levelDiffBonus($user, $bonus,  $transaction,$transactionLog, $user_level);
        }
    }

    //平级奖

    private function sameLevelBonus($user, $bonus, $transaction,$transactionLog,): void
    {

        $pid = $user->pid;
        $userIds = [];

        while ($pid) {
            $parent = User::query()->find($pid);
            if (!$parent) {
                break;
            }

            // 如果上级是 9 级，则检查其是否有 9 级的下级
            if ($parent->level == 9) {
                $hasSameLevelSub = User::query()->where('pid', $parent->id)->where('level', 9)->exists();
                if ($hasSameLevelSub) {
                    $userIds[] = $parent->id; // 记录符合条件的用户
                }
            }

            $pid = $parent->pid; // 继续向上查找
        }

        // 计算奖励
        if (!empty($userIds)) {
            $rate = $bonus * $this->same_level_rate; // 平级奖励是订单金额的 5%
            $parent_bonus = $bonus * $rate;
            foreach ($userIds as $userId) {
                $parent_assets = Assets::query()->where('user_id', $userId)->where('coin', $transaction->coin)->first();
                $parent_assets->increment('money', $parent_bonus);

                //资金日志
                $assets_log = new AssetsLog;
                $assets_log->user_id = $parent->user_id;
                $assets_log->coin = $transaction->coin;
                $assets_log->identity = $parent->identity;
                $assets_log->money = $parent_bonus;
                $assets_log->rate = $this->share_rate ;
                $assets_log->transaction_id = $transaction->id;
                $assets_log->transaction_log_id = $transactionLog->id;
                $assets_log->type = AssetsLogTypes::SAMELEVELBONUS;
                $assets_log->remark = '平级收益';
                $assets_log->datetime = Carbon::now()->timestamp;
                $assets_log->save();

            }
        }


    }

}