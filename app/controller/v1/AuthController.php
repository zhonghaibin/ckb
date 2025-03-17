<?php

namespace app\controller\v1;

use app\enums\CoinTypes;
use app\enums\LangTypes;
use app\enums\QueueTask;
use app\enums\UserStatus;
use app\model\Assets;
use app\model\LoginLog;
use app\model\User;
use app\support\Lang;
use Carbon\Carbon;
use support\Request;
use support\Db;
use app\utils\JwtUtil;
use app\utils\AesUtil;
use Webman\RedisQueue\Redis;
use Tuupola\Base58;
use ParagonIE_Sodium_Compat as Sodium;

class AuthController
{


    public function login(Request $request)
    {
        $identity = $request->post('identity', '');
        $code = $request->post('code', '');

        // 验证输入
        if (empty($identity)) {
            return json_fail(Lang::get('tips_9'));
        }

//        $publicKey = $request->post("publicKey") ?? "";
//        $signature = $request->post("signature") ?? "";
//        $nonce = $request->post("nonce") ?? "";
//        if (!$publicKey || !$signature || !$nonce) {
//            return json_fail(Lang::get('tips_24'));
//        }

//        if (!$this->verifySignature($publicKey, $signature, $nonce)) {
//            return json_fail(Lang::get('tips_23'));
//        }


        // 查找用户
        $user = User::query()->where(['identity' => $identity])->first();
        if ($user && $user->status != UserStatus::NORMAL->value) {
            return json_fail(Lang::get('tips_22'));
        }

        DB::beginTransaction();

        try {
            // 如果用户不存在，进行注册
            if (empty($user)) {
                $user = new User;
                $user->identity = $identity;
                $user->remark = '';
                $user->avatar = '/images/avatars/avatar.png';
                $user->lang = LangTypes::ZH_CN->value;

                // 解析邀请码
                if (!empty($code)) {
                    $pid = $this->decryptInviteCode($code);
                    if (is_numeric($pid)) {
                        $user->pid = $pid;
                    }
                }

                if (!$user->save()) {
                    throw new \Exception(Lang::get('tips_19'));
                }

                // 初始化用户资产
                $this->initializeUserAssets($user);

                // 更新邀请码邀请人数
                if ($user->pid) {
                    Redis::send(QueueTask::UPDATE_INVITE_COUNT->value, ['user_id' => $user->pid]);
                }
            }

            // 登录日志
            $this->createLoginLog($user->id, $request);

            // 更新用户最后登录信息
            $this->updateLastLogin($user, $request);

            // 提交事务
            DB::commit();

            // 生成并返回token
            $token = JwtUtil::generateToken($user->id);
            return json_success([
                'token' => $token,
            ], Lang::get('tips_11'));

        } catch (\Throwable $e) {
            // 事务回滚
            DB::rollBack();
            return json_fail($e->getMessage());
        }
    }

    // 验证 TokenPocket 签名
    private function verifySignature($publicKeyBase58, $signatureBase58, $message)
    {
        $base58 = new Base58();

        // 解码公钥和签名
        try {
            $publicKey = $base58->decode($publicKeyBase58);
            $signature = $base58->decode($signatureBase58);
        } catch (\Exception $e) {
            // 如果 Base58 解码失败，捕获异常并返回错误
            return false;
        }

        // 计算消息哈希
        $messageBytes = mb_convert_encoding($message, 'UTF-8', 'auto');

        // 使用 Sodium 进行验证
        try {
            return Sodium::crypto_sign_verify_detached($signature, $messageBytes, $publicKey);
        } catch (\Exception $e) {
            // 验证失败时捕获异常
            return false;
        }
    }

    // 解密邀请码
    private function decryptInviteCode($code)
    {
        try {
            return AesUtil::decrypt($code);
        } catch (\Throwable $e) {
            throw new \Exception(Lang::get('invite_code'));
        }
    }

    // 初始化用户资产
    private function initializeUserAssets($user)
    {
        $assetsList = CoinTypes::list();
        foreach ($assetsList as $value) {
            $assets = new Assets;
            $assets->user_id = $user->id;
            $assets->coin = $value;
            if (!$assets->save()) {
                throw new \Exception(Lang::get('tips_19'));
            }
        }
    }

    // 创建登录日志
    private function createLoginLog($userId, $request)
    {
        $login_logs = new LoginLog;
        $login_logs->user_id = $userId;
        $login_logs->ip = $request->getRealIp();
        $login_logs->user_agent = $request->header('User-Agent');
        $login_logs->datetime = Carbon::now()->timestamp;

        if (!$login_logs->save()) {
            throw new \Exception(Lang::get('tips_19'));
        }
    }

    // 更新用户最后登录信息
    private function updateLastLogin($user, $request)
    {
        $user->last_login_ip = $request->getRealIp();
        $user->last_login_at = Carbon::now();
        if (!$user->save()) {
            throw new \Exception(Lang::get('tips_19'));
        }
    }


}
