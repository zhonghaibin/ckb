<?php

namespace app\controller\v1;

use app\enums\CoinTypes;
use app\enums\LangTypes;
use app\enums\UserJob;
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

class AuthController
{


    public function openTokenPocketUrl()
    {
        $param = [
            "callbackUrl" => "https://agent.xsztest.top/v1/notify/receive",
            "action" => "login",
            "actionId" => "1648522106711",
            "blockchains" => [
                [
                    "chainId" => "1",
                    "network" => "ethereum"
                ]
            ],
            "dappIcon" => "https://eosknights.io/img/icon.png",
            "dappName" => "zs",
            "protocol" => "TokenPocket",
            "version" => "2.0",
            "callbackSchema" => "mySchema://myHost"
        ];
        $encodedParam = urlencode(json_encode($param));
        $url = "<a href='tpoutside://pull.activity?param=$encodedParam'>Open TokenPocket to authorize</a>";
        return response($url);
    }


    public function login(Request $request)
    {
        $identity = $request->post('identity', '');
        if (empty($identity)) {
            return json_fail(Lang::get('tips_9'));
        }
        $user = User::query()->where(['identity' => $identity, 'status' => UserStatus::NORMAL])->first();

        if (empty($user)) {
            return json_fail(Lang::get('tips_10'));
        }

        Db::beginTransaction();

        try {
            //登录日志
            $login_logs = new LoginLog;
            $login_logs->user_id = $user->id;
            $login_logs->ip = $request->getRealIp();
            $login_logs->user_agent = $request->header('User-Agent');
            $login_logs->datetime = Carbon::now()->timestamp;
            if (!$login_logs->save()) {
                throw new \Exception(Lang::get('tips_19'));
            }
            $user->last_login_ip = $request->getRealIp();
            $user->last_login_at = Carbon::now();
            $user->save();
            if (!$user->save()) {
                throw new \Exception(Lang::get('tips_19'));
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return json_fail($e->getMessage());
        }
        $token = JwtUtil::generateToken($user->id);

        return json_success([
            'token' => $token,
        ], Lang::get('tips_11'));

    }


    public function register(Request $request)
    {


        $identity = $request->post('identity', '');
        $code = $request->post('code', '');
        if (empty($identity)) {
            return json_fail(Lang::get('tips_12'));
        }
        $user = User::query()->where(['identity' => $identity])->exists();
        if ($user) {
            return json_fail(Lang::get('tips_13'));
        }


        Db::beginTransaction();
        try {
            $user = new User;
            $user->identity = $identity;
            $user->remark = '';
            $user->avatar = '/images/avatars/avatar.png';
            $user->save();
            $user->lang = LangTypes::ZH_CN;
            $pid = 0;
            if (!empty($code)) {
                try {
                    $pid = AesUtil::decrypt($code);
                    if (is_numeric($pid)) {
                        $user->pid = $pid;
                    }
                } catch (\Throwable $e) {
                    throw new \Exception(Lang::get('invite_code'));
                }
            }
            if (!$user->save()) {
                throw new \Exception(Lang::get('tips_19'));
            }

            $assetsList = CoinTypes::list();
            foreach ($assetsList as $value) {
                $assets = new Assets;
                $assets->user_id = $user->id;
                $assets->coin = $value;
                if (!$assets->save()) {
                    throw new \Exception(Lang::get('tips_19'));
                }
            }

            DB::commit();
            if ($pid) {
                Redis::send(UserJob::UPDATE_INVITE_COUNT->value, ['user_id' => $pid]);
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            return json_fail($e->getMessage());
        }
        return json_success();

    }


}
