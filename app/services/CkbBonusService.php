<?php

namespace app\services;

use app\enums\AssetsLogTypes;
use app\enums\TransactionStatus;
use app\enums\TransactionTypes;
use app\enums\UserIsReal;
use Carbon\Carbon;
use support\Db;
use support\Log;


class CkbBonusService
{
    protected array $rates = [
        15 => 0.08,
        30 => 0.12,
        60 => 0.15,
    ];

    protected float $direct_rate = 0.2;

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

    public function run()
    {
        try {
            $midnightTimestamp = Carbon::today()->timestamp;

            // 获取符合条件的交易记录
            $transactions = DB::table('transactions')
                ->where('transaction_type', TransactionTypes::CKB)
                ->where('status', TransactionStatus::NORMAL)
                ->whereRaw('run_day < day')
                ->where('runtime', '<', $midnightTimestamp)
                ->get();

            foreach ($transactions as $transaction) {
                DB::beginTransaction();

                try {
                    $month_day = get_time_in_month($transaction->datetime);
                    $rate = $this->rates[$transaction->day] ?? 0;
                    $bonus = round($transaction->money * $rate / $month_day, 6);



                    DB::table('transactions')->where('id', $transaction->id)->update([
                        'bonus' => DB::raw("bonus + $bonus"),
                        'run_day' => DB::raw('run_day + 1'),
                        'runtime' => $midnightTimestamp,
                    ]);

                    // 创建交易日志
                    $transactionLogId = DB::table('transaction_logs')->insertGetId([
                        'user_id' => $transaction->user_id,
                        'identity' => $transaction->identity,
                        'transaction_id' => $transaction->id,
                        'coin' => $transaction->coin,
                        'rate' => $rate,
                        'transaction_type' => TransactionTypes::CKB,
                        'datetime' => Carbon::now()->timestamp,
                        'bonus' => $bonus,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                    //更新每日收益
                    DB::table('assets')
                        ->where('user_id', $transaction->user_id)
                        ->where('coin', $transaction->coin)
                        ->update([
                            'money' => DB::raw('money + ' . $bonus),
                            'bonus' => DB::raw('bonus + ' . $bonus),
                        ]);

                    // 资金日志
                    DB::table('assets_logs')->insert([
                        'user_id' => $transaction->user_id,
                        'coin' => $transaction->coin,
                        'identity' => $transaction->identity,
                        'money' => $bonus,
                        'rate' => $rate,
                        'transaction_id' => $transaction->id,
                        'transaction_log_id' => $transactionLogId,
                        'type' => AssetsLogTypes::DAILYINCOME,
                        'remark' => AssetsLogTypes::DAILYINCOME->label(),
                        'datetime' => Carbon::now()->timestamp,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);


                    if ($transaction->run_day + 1 == $transaction->day) {
                        //返还本金
                        DB::table('transactions')->where('id', $transaction->id)->update(['status' => TransactionStatus::DONE]);
                        DB::table('assets')
                            ->where('user_id', $transaction->user_id)
                            ->where('coin', $transaction->coin)
                            ->increment('money', $transaction->money);
                        DB::table('assets_logs')->insert([
                            'user_id' => $transaction->user_id,
                            'coin' => $transaction->coin,
                            'identity' => $transaction->identity,
                            'money' => $transaction->money,
                            'rate' => 0,
                            'transaction_id' => $transaction->id,
                            'transaction_log_id' => $transactionLogId,
                            'type' => AssetsLogTypes::INCOME,
                            'remark' => AssetsLogTypes::INCOME->label(),
                            'datetime' => Carbon::now()->timestamp,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                    }

                    // 获取上级用户
                    $parent = DB::table('users')->where('id', $transaction->user_id)->value('pid');

                    if ($parent) {
                        $parentUser = DB::table('users')->where('id', $parent)->first();
                        if ($parentUser) {
                            $this->shareBonus($parentUser, $bonus, $transaction, $transactionLogId);
                            $this->levelDiffBonus($parentUser, $bonus, $transaction, $transactionLogId, $transaction->user_id);
                        }
                        $this->sameLevelBonus($transaction->user_id, $bonus, $transaction, $transactionLogId);
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Error processing transaction ID: ' . $transaction->id, ['exception' => $e->getMessage()]);
                    continue;
                }
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Error during daily earnings processing', ['exception' => $e->getMessage()]);
            return false;
        }
    }

    private function shareBonus($parent, $bonus, $transaction, $transactionLogId)
    {
        if ($parent->is_real == UserIsReal::NORMAL->value) {
            $parent_bonus = round($this->direct_rate * $bonus, 6);
            DB::table('assets')
                ->where('user_id', $parent->id)
                ->where('coin', $transaction->coin)
                ->update([
                    'money' => DB::raw('money + ' . $parent_bonus),
                    'bonus' => DB::raw('bonus + ' . $parent_bonus),
                ]);


            DB::table('assets_logs')->insert([
                'user_id' => $parent->id,
                'coin' => $transaction->coin,
                'identity' => $parent->identity,
                'money' => $parent_bonus,
                'rate' => $this->direct_rate,
                'transaction_id' => $transaction->id,
                'transaction_log_id' => $transactionLogId,
                'type' => AssetsLogTypes::DIRECTBONUS,
                'remark' => AssetsLogTypes::DIRECTBONUS->label(),
                'datetime' => Carbon::now()->timestamp,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }

    private function levelDiffBonus($parent, $bonus, $transaction, $transactionLogId, $user_level)
    {
        if ($parent->is_real == UserIsReal::NORMAL->value) {
            $user_level_diff_rate = $this->level_diff_rates[$user_level] ?? 0;
            $parent_level_diff_rate = $this->level_diff_rates[$parent->level] ?? 0;
            $level_diff_rate = $parent_level_diff_rate - $user_level_diff_rate;
            $parent_bonus = round($level_diff_rate * $bonus, 6);

            DB::table('assets')
                ->where('user_id', $parent->id)
                ->where('coin', $transaction->coin)
                ->update([
                    'money' => DB::raw('money + ' . $parent_bonus),
                    'bonus' => DB::raw('bonus + ' . $parent_bonus),
                ]);

            DB::table('assets_logs')->insert([
                'user_id' => $parent->id,
                'coin' => $transaction->coin,
                'identity' => $parent->identity,
                'money' => $parent_bonus,
                'rate' => $level_diff_rate,
                'transaction_id' => $transaction->id,
                'transaction_log_id' => $transactionLogId,
                'type' => AssetsLogTypes::LEVELDIFFBONUS,
                'remark' => AssetsLogTypes::LEVELDIFFBONUS->label(),
                'datetime' => Carbon::now()->timestamp,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        if ($parent->pid > 0) {
            $nextParent = DB::table('users')->where('id', $parent->pid)->first();
            if ($nextParent) {
                $this->levelDiffBonus($nextParent, $bonus, $transaction, $transactionLogId, $user_level);
            }
        }
    }

    private function sameLevelBonus($user_id, $bonus, $transaction, $transactionLogId)
    {
        $user = DB::table('users')->where('id', $user_id)->first();
        $pid = $user->pid;
        $userIds = [];

        while ($pid) {
            $parent = DB::table('users')->where('id', $pid)->first();
            if (!$parent) {
                break;
            }

            if ($parent->level == 9) {
                $hasSameLevelSub = DB::table('users')->where('pid', $parent->id)->where('level', 9)->exists();
                if ($hasSameLevelSub) {
                    $userIds[$parent->id] = $parent->identity;
                }
            }
            $pid = $parent->pid;
        }

        if (!empty($userIds)) {
            $rate = $this->same_level_rate;
            $parent_bonus = round($bonus * $rate, 6);
            foreach ($userIds as $user_id => $identity) {
                DB::table('assets')
                    ->where('user_id', $user_id)
                    ->where('coin', $transaction->coin)
                    ->update([
                        'money' => DB::raw('money + ' . $parent_bonus),
                        'bonus' => DB::raw('bonus + ' . $parent_bonus),
                    ]);

                DB::table('assets_logs')->insert([
                    'user_id' => $user_id,
                    'identity' => $identity,
                    'coin' => $transaction->coin,
                    'money' => $parent_bonus,
                    'rate' => $this->same_level_rate,
                    'transaction_id' => $transaction->id,
                    'transaction_log_id' => $transactionLogId,
                    'type' => AssetsLogTypes::SAMELEVELBONUS,
                    'remark' => AssetsLogTypes::SAMELEVELBONUS->label(),
                    'datetime' => Carbon::now()->timestamp,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
