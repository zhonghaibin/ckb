<?php

namespace app\controller\v1;

use app\enums\CoinTypes;
use app\enums\LangTypes;
use app\enums\UserStatus;
use app\model\Assets;
use app\model\User;
use Carbon\Carbon;
use support\Request;
use support\Db;
use app\utils\JwtUtil;
use app\utils\AesUtil;

class NotifyController
{


    public function receive(Request $request)
    {
        $rawData = $request->rawBody();
        $data = json_decode($rawData, true);
        if (isset($data['action']) && $data['action'] == 'login') {
            $identity = $data['account'];
            try {
                $user = User::query()->where(['identity' => $identity])->first();
                if (!$user) {
                    $user = new User;
                    $user->identity = $identity;
                    $user->remark = '';
                    $user->save();
                    $user->lang = LangTypes::ZH_CN;
                    $user->status = UserStatus::NORMAL;
                    $user->avatar = '/images/avatars/avatar' . mt_rand(0, 5) . '.png';
                    $user->save();
                    $assetsList = CoinTypes::list();
                    foreach ($assetsList as $value) {
                        $assets = new Assets;
                        $assets->user_id = $user->id;
                        $assets->coin = $value;
                        $assets->save();
                    }
                }
                Db::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                return json_fail($e->getMessage());
            }
            $token = JwtUtil::generateToken($user->id);

            return json_success($token);
        }

        return json_fail('出错了');

    }


}
