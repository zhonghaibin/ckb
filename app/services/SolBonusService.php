<?php

namespace app\services;

use app\enums\AssetsLogTypes;
use app\enums\TransactionStatus;
use app\enums\TransactionTypes;
use app\enums\UserIsReal;
use Carbon\Carbon;
use support\Db;
use support\Log;


class SolBonusService
{
    protected array $rates = [];

    protected float $direct_rate = 0;

    protected array $level_diff_rates = [];

    protected float $same_level_rate = 0;

    private static $instance = null; // 存储唯一实例

    private function __construct() {
        // 私有构造函数，防止外部实例化
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __clone() {
        // 禁止克隆
    }



    public function run()
    {
        try {
            $midnightTimestamp = Carbon::today()->timestamp;

            // 获取符合条件的交易记录
            $transactions = DB::table('transactions')
                ->where('transaction_type', TransactionTypes::SOL)
                ->where('status', TransactionStatus::NORMAL)
                ->whereRaw('run_day < day')
                ->where('runtime', '<', $midnightTimestamp)
                ->get();

            foreach ($transactions as $transaction) {
                $params = json_decode($transaction->rates, true);
                $this->rates = array_column(array_map(fn($item) => [$item['day'], [(int)$item['rate']['min'], (int)$item['rate']['max']]], $params['staticRate']), 1, 0);
                $this->direct_rate = $params['directRate'];
                $this->level_diff_rates = array_column($params['levelDiffRate'], 'rate', 'level');
                $this->same_level_rate = $params['sameLevelRate'];
                DB::beginTransaction();

                try {
                    $month_day = get_time_in_month($transaction->datetime);

                    $rateRange = $this->rates[$transaction->day] ?? [0, 0];
                    $min = $rateRange[0];
                    $max = $rateRange[1];
                    $randomRate1 = mt_rand($min * 100, $max * 100) / 100;
                    $randomRate2 = mt_rand($min * 100, $max * 100) / 100;

                    $rate = round(($randomRate1 + $randomRate2) / 2, 4) / 100;
                    // 计算 bonus
                    $bonus = round($transaction->amount * $rate / $month_day, 6);


                    DB::table('transactions')->where('id', $transaction->id)->update([
                        'bonus' => DB::raw("bonus + $bonus"),
                        'run_day' => DB::raw('run_day + 1'),
                        'runtime' => $midnightTimestamp,
                    ]);

                    // 创建交易日志
                    $transactionLogId = DB::table('transaction_logs')->insertGetId([
                        'user_id' => $transaction->user_id,
                        'transaction_id' => $transaction->id,
                        'coin' => $transaction->coin,
                        'rate' => $rate,
                        'transaction_type' => TransactionTypes::SOL,
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
                            'amount' => DB::raw('amount + ' . $bonus),
                            'bonus' => DB::raw('bonus + ' . $bonus),
                        ]);

                    // 资金日志
                    DB::table('assets_logs')->insert([
                        'user_id' => $transaction->user_id,
                        'coin' => $transaction->coin,
                        'amount' => $bonus,
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
                            ->increment('amount', $transaction->amount);
                        DB::table('assets_logs')->insert([
                            'user_id' => $transaction->user_id,
                            'coin' => $transaction->coin,
                            'amount' => $transaction->amount,
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
            $rate = round( $this->direct_rate / 100,4);
            $parent_bonus = round($rate * $bonus, 6);
            DB::table('assets')
                ->where('user_id', $parent->id)
                ->where('coin', $transaction->coin)
                ->update([
                    'amount' => DB::raw('amount + ' . $parent_bonus),
                    'bonus' => DB::raw('bonus + ' . $parent_bonus),
                ]);


            DB::table('assets_logs')->insert([
                'user_id' => $parent->id,
                'coin' => $transaction->coin,
                'amount' => $parent_bonus,
                'rate' => $rate,
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
            $user_level_diff_rate = round(($this->level_diff_rates[$user_level] ?? 0) / 100, 4);
            $parent_level_diff_rate = round(($this->level_diff_rates[$parent->level] ?? 0) / 100, 4);
            $level_diff_rate = round($parent_level_diff_rate - $user_level_diff_rate, 4);

            $parent_bonus = round($level_diff_rate * $bonus, 6);

            DB::table('assets')
                ->where('user_id', $parent->id)
                ->where('coin', $transaction->coin)
                ->update([
                    'amount' => DB::raw('amount + ' . $parent_bonus),
                    'bonus' => DB::raw('bonus + ' . $parent_bonus),
                ]);


            DB::table('assets_logs')->insert([
                'user_id' => $parent->id,
                'coin' => $transaction->coin,
                'amount' => $parent_bonus,
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
                    $userIds[] = $parent->id;
                }
            }
            $pid = $parent->pid;
        }

        if (!empty($userIds)) {
            $rate = round($this->same_level_rate / 100, 4);
            $parent_bonus = round($bonus * $rate, 6);
            foreach ($userIds as $user_id) {
                DB::table('assets')
                    ->where('user_id', $user_id)
                    ->where('coin', $transaction->coin)
                    ->update([
                        'amount' => DB::raw('amount + ' . $parent_bonus),
                        'bonus' => DB::raw('bonus + ' . $parent_bonus),
                    ]);

                DB::table('assets_logs')->insert([
                    'user_id' => $user_id,
                    'coin' => $transaction->coin,
                    'amount' => $parent_bonus,
                    'rate' => $rate,
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
