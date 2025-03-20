<?php

namespace app\services;

use app\enums\AssetsLogTypes;
use app\enums\QueueTask;
use app\enums\TransactionStatus;
use app\enums\TransactionTypes;
use app\enums\UserIsReal;
use app\model\Transaction;
use Carbon\Carbon;
use support\Db;
use support\Log;
use Webman\RedisQueue\Redis;

class ReleaseService
{
    protected float $rate = 0;
    protected float $direct_rate = 0;
    protected array $level_diff_rates = [];
    protected float $same_level_rate = 0;


    public function run(): bool
    {
        try {
            $midnightTimestamp = time();

            // 分批处理事务，每批次取 500 条
            Db::table('transaction_logs')
                ->orderBy('id')
                ->where('is_release', 0)
                ->where('runtime', '<', $midnightTimestamp)
                ->chunk(500, function ($transaction_logs) use ($midnightTimestamp) {
                    foreach ($transaction_logs as $transaction_log) {
                        $transaction = Db::table('transactions')->find($transaction_log->transaction_id);

                        $params = json_decode($transaction->rates, true);
                        $this->initializeRates($params);

                        Db::beginTransaction();
                        try {
                            $this->processTransaction($transaction_log, $transaction);
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

    //设置参数
    private function initializeRates(array $params): void
    {

        $this->direct_rate = $params['directRate'] ?? 0;
        $this->level_diff_rates = array_column($params['levelDiffRate'], 'rate', 'level');
        $this->same_level_rate = $params['sameLevelRate'] ?? 0;
    }


    //每日收益
    private function processTransaction($transaction_log, $transaction): void
    {
        $this->rate = $transaction_log->rate;
        Db::table('transaction_logs')->where('id', $transaction_log->id)->update([
            'is_release' => 1,
        ]);
        Db::table('transactions')->where('id', $transaction_log->transaction_id)->update([
            'bonus' => Db::raw("bonus + $transaction_log->bonus"),
            'run_day' => Db::raw('run_day + 1'),
        ]);
        $this->updateAssets($transaction_log->user_id, $transaction_log->coin, $transaction_log->bonus, $this->rate, $transaction_log->transaction_id, $transaction_log->id, AssetsLogTypes::DAILYINCOME->value, AssetsLogTypes::DAILYINCOME->label());

        //返还本金
        if ($transaction->run_day + 1 == $transaction->day) {
            Db::table('transactions')->where('id', $transaction->id)->update(['status' => TransactionStatus::DONE]);
            $this->updateAssets($transaction->user_id, $transaction->coin, $transaction->amount, 0, $transaction->id, $transaction_log->id, AssetsLogTypes::INCOME->value, AssetsLogTypes::INCOME->label());
        }

        $this->processParentBonuses($transaction, $transaction_log->bonus, $transaction_log->id);


    }

    //资金记录
    private function updateAssets(int $user_id, string $coin, float $amount, float $rate, int $transaction_id, int $transactionLogId, int $assets_log_type, string $remark): void
    {
        if ($amount <= 0) {
            return;
        }
        $assets = DB::table('assets')->where('user_id', $user_id)->where('coin', $coin)->first();
        $new_balance = bcadd($assets->amount, $amount, 8);
        // 更新用户资产
        DB::table('assets')->where('user_id', $user_id)->where('coin', $coin)->update(['amount' => DB::raw('amount + ' . $amount), 'bonus' => DB::raw('bonus + ' . $amount)]);

        // 记录资产日志
        DB::table('assets_logs')->insert([
            'user_id' => $user_id,
            'coin' => $coin,
            'amount' => $amount,
            'balance' => $new_balance,
            'rate' => $rate,
            'transaction_id' => $transaction_id,
            'transaction_log_id' => $transactionLogId,
            'type' => $assets_log_type,
            'remark' => $remark,
            'datetime' => Carbon::now()->timestamp,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }


    private function processParentBonuses($transaction, float $bonus, int $transactionLogId): void
    {
        $user = Db::table('users')->where('id', $transaction->user_id)->first();
        if ($user->pid) {
            $parentUser = Db::table('users')->where('id', $user->pid)->first();
            if ($parentUser) {
                $this->shareBonus($parentUser, $bonus, $transaction, $transactionLogId);
                $this->levelDiffBonus($parentUser, $bonus, $transaction, $transactionLogId, $user->level);
            }
            $this->sameLevelBonus($transaction->user_id, $bonus, $transaction, $transactionLogId);
        }
    }


    private function shareBonus($parent, float $bonus, $transaction, int $transactionLogId): void
    {
        if ($parent->is_real == UserIsReal::NORMAL->value) {
            $rate = bcdiv($this->direct_rate, 100, 8);
            $parent_bonus = bcmul($bonus, $rate, 8);


            if ($parent_bonus > 0) {
                $this->updateAssets($parent->id, $transaction->coin, $parent_bonus, $rate, $transaction->id, $transactionLogId, AssetsLogTypes::DIRECTBONUS->value, AssetsLogTypes::DIRECTBONUS->label());
            }
        }
    }

    //极差奖
    private function levelDiffBonus($parent, float $bonus, $transaction, int $transactionLogId, int $user_level): void
    {
        if ($parent->is_real == UserIsReal::NORMAL->value) {
            $user_level_diff_rate = $this->level_diff_rates['Vip' . $user_level] ?? '';
            $parent_level_diff_rate = $this->level_diff_rates['Vip' . $parent->level];
            $level_diff_rate = bcdiv(bcsub($parent_level_diff_rate, $user_level_diff_rate, 8), 100, 8);
            $parent_bonus = bcmul($level_diff_rate, $bonus, 8);

            $this->updateAssets($parent->id, $transaction->coin, $parent_bonus, $level_diff_rate, $transaction->id, $transactionLogId, AssetsLogTypes::LEVELDIFFBONUS->value, AssetsLogTypes::LEVELDIFFBONUS->label());
        }
        if ($parent->pid > 0) {
            $nextParent = Db::table('users')->where('id', $parent->pid)->first();
            if ($nextParent) {
                $this->levelDiffBonus($nextParent, $bonus, $transaction, $transactionLogId, $user_level);
            }
        }
    }

    //平级奖
    private function sameLevelBonus($user_id, $bonus, $transaction, $transactionLogId): void
    {
        // 获取所有上级用户链
        $user = DB::table('users')->where('id', $user_id)->first();
        $pid = $user->pid;
        $parentUsers = [];

        while ($pid) {
            $parent = DB::table('users')->where('id', $pid)->first();
            if (!$parent) {
                break;
            }

            $parentUsers[$parent->id] = $parent;
            $pid = $parent->pid;
        }

        if (empty($parentUsers)) {
            return;
        }

        // 过滤符合条件的用户
        $sameLevelUsers = [];
        $level9Parents = array_filter($parentUsers, fn($parent) => $parent->level == 9);

        if (!empty($level9Parents)) {
            // 获取所有 level = 9 的用户 ID
            $level9ParentIds = array_keys($level9Parents);
            // 查询他们是否有相同 level = 9 的下级
            $subLevelCounts = DB::table('users')
                ->whereIn('pid', $level9ParentIds)
                ->where('level', 9)
                ->select('pid', DB::raw('COUNT(*) as count'))
                ->groupBy('pid')
                ->pluck('count', 'pid')
                ->toArray();

            foreach ($level9Parents as $parentId => $parent) {
                if (!empty($subLevelCounts[$parentId])) {
                    $sameLevelUsers[] = $parentId;
                }
            }
        }

        if (empty($sameLevelUsers)) {
            return;
        }

        // 计算奖励
        $rate = bcdiv($this->same_level_rate, 100, 8);
        $parent_bonus = bcmul($bonus, $rate, 8);


        foreach ($sameLevelUsers as $uid) {
            $this->updateAssets($uid, $transaction->coin, $parent_bonus, $rate, $transaction->id, $transactionLogId, AssetsLogTypes::SAMELEVELBONUS->value, AssetsLogTypes::SAMELEVELBONUS->label());
        }

    }

}