<?php

namespace app\queue\redis;

use app\enums\CoinTypes;
use app\enums\QueueTask;
use app\enums\RechargeStatus;
use app\services\AssetsService;
use support\Db;
use support\Log;
use Webman\RedisQueue\Consumer;
use Webman\RedisQueue\Redis;

class RechargeJob implements Consumer
{
    public $queue = QueueTask::RECHARGE->value;

    public function consume($data)
    {
        $recharge_id = $data['recharge_id'] ?? 0;
        $retry_count = $data['retry_count'] ?? 0; // 获取失败重试次数

        try {
            $recharge = DB::table('recharges')->find($recharge_id);
            if (!$recharge || $recharge->status == RechargeStatus::SUCCESS) {
                return;
            }

            $userWallet = $recharge->user_wallet;
            $signature = $recharge->signature;
            $response = get_transaction_by_signature($signature);

            if (!isset($response['result'])) {
                throw new \Exception('交易未找到');
            }

            $transaction = $response['result'];
            if (!$transaction || isset($transaction['meta']['err'])) {
                throw new \Exception('交易失败');
            }

            $config = get_system_config();
            $platformWallet = $config['base_info']['wallet_address'] ?? '';

            $amountReceived = 0;
            foreach ($transaction['transaction']['message']['instructions'] as $instruction) {
//                if (isset($instruction['parsed']['info']['destination']) &&
//                    $instruction['parsed']['info']['destination'] === $platformWallet) {
                    $amountReceived += (int)$instruction['parsed']['info']['lamports'];
//                }
            }

            if ($amountReceived <= 0) {
                throw new \Exception('付款地址不正确');
            }

            $payerAddress = $transaction['transaction']['message']['accountKeys'][0]['pubkey'];
            if ($payerAddress !== $userWallet) {
                throw new \Exception('付款地址错误');
            }

            $amount = ($amountReceived / 1e9);
            $transactionService = new AssetsService();
            $transactionService->rechargeLog($recharge->id, $recharge->user_id, CoinTypes::USDT, $amount);


        } catch (\Throwable $e) {
            if ($retry_count < 2) {
                Log::error("retry_count：{$retry_count}" . $e->getMessage());
                // 重新放入队列，增加重试次数
                Redis::send($this->queue, [
                    'recharge_id' => $recharge_id,
                    'retry_count' => $retry_count + 1
                ], 10); // 延迟 10 秒重试
            } else {
                // 记录日志 & 上报错误
                Log::error("充值任务失败，recharge_id: {$recharge_id}, 错误: " . $e->getMessage());
                $this->reportFailure($recharge_id, $e->getMessage());
            }
        }
    }

    public function onConsumeFailure(\Throwable $e, $package)
    {
        Log::error("队列消费失败: " . $e->getMessage());
    }

    private function reportFailure($recharge_id, $message)
    {
        // 可以添加邮件、Webhook 或其他通知方式
        Log::error("充值任务最终失败，recharge_id: {$recharge_id}, 失败原因: {$message}");

    }
}
