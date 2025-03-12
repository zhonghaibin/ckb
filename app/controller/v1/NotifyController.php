<?php

namespace app\controller\v1;

use app\enums\AssetsLogTypes;
use app\enums\CoinTypes;
use app\enums\LangTypes;
use app\enums\RechargeStatus;
use app\enums\UserStatus;
use app\model\Assets;
use app\model\AssetsLog;
use app\model\Recharge;
use app\model\User;
use app\services\TransactionService;
use app\support\Lang;
use Carbon\Carbon;
use support\Request;
use support\Db;

class NotifyController
{


    public function register(Request $request)
    {
        $rawData = $request->rawBody();
        $data = json_decode($rawData, true);
        if (isset($data['action']) && $data['action'] == 'login') {
            $identity = $data['account'];
            try {
                $user = User::query()->where(['identity' => $identity])->exists();
                if (!$user) {
                    $user = new User;
                    $user->identity = $identity;
                    $user->remark = '';
                    $user->save();
                    $user->lang = LangTypes::ZH_CN;
                    $user->status = UserStatus::NORMAL;
                    $user->avatar = '/images/avatars/avatar.png';
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


            return json_success('ok');
        }

        return json_fail(Lang::get('tips_14'));

    }


    public function recharge(Request $request)
    {
        $amount = $request->post('amount');
        $identity = $request->post('identity');
        $transactionService = new TransactionService();
        $transactionService->recharge($identity, $amount);
        return json_success();

    }

    public function withdraw(Request $request)
    {

    }

}
