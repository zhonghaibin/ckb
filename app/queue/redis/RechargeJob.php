<?php

namespace app\queue\redis;

use app\enums\AssetsLogTypes;
use app\enums\CoinTypes;
use app\enums\RechargeStatus;
use app\enums\UserJob;
use app\model\Assets;
use app\model\AssetsLog;
use app\model\Recharge;
use app\model\User;
use app\services\TransactionService;
use app\services\UserUpgradeService;
use app\support\Lang;
use Carbon\Carbon;
use support\Db;
use support\Log;
use Webman\RedisQueue\Consumer;

class RechargeJob implements Consumer
{

    /**
     * php start.php queue:listen
     * 如果要以 守护进程 方式运行：
     * php start.php queue:restart
     * @var string
     */
    public $queue = '';

    public function consume($data)
    {

        $userWallet = $data['user_wallet'] ?? '';
        $signature = $data['signature'] ?? '';  // 填入你要查询的交易签名
        $response = get_transaction_by_signature($signature);
        if (!isset($response['result'])) {
            Log::error('交易未找到');
        }
        $transaction = $response['result'];
        if (!$transaction || isset($transaction['meta']['err'])) {
            Log::error('交易失败');
        }
        //验证交易的收款地址是否是平台钱包
        $config = get_system_config();
        $platformWallet = $config['base_info']['wallet_address'] ?? '';   // 请替换为你的收款地址
        $amountReceived = 0;
        foreach ($transaction['transaction']['message']['instructions'] as $instruction) {
            if (isset($instruction['parsed']['info']['destination']) &&
                $instruction['parsed']['info']['destination'] === $platformWallet) {
                $amountReceived += (int)$instruction['parsed']['info']['lamports']; // 以 lamports 计算
            }
        }
        if ($amountReceived <= 0) {
            Log::error('付款地址不正确');
        }
        // 验证付款人是否是用户
        $payerAddress = $transaction['transaction']['message']['accountKeys'][0]['pubkey']; // 交易发起人
        if ($payerAddress !== $userWallet) {
            Log::error('付款地址错误');
        }
        $amount = ($amountReceived / 1e9);
        $transactionService = new TransactionService();
        $transactionService->recharge($userWallet, $amount);
    }


    public function onConsumeFailure(\Throwable $e, $package)
    {
        //每次消费失败时触发
    }
}
