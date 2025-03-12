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
        $coin = CoinTypes::USDT;
        Db::beginTransaction();
        try {
            $user = User::query()->where('identity', $identity)->first();
            $assets = Assets::query()->where('user_id', $user->id)->where('coin', $coin)->firstOrFail();
            $new_balance = $assets->amount + $amount;
            
            if (!$assets->increment('amount', $amount)) {
                throw new \Exception(Lang::get('tips_19'));
            }

            $recharge = new Recharge();
            $recharge->user_id = $user->id;
            $recharge->coin = $coin;
            $recharge->amount = $amount;
            $recharge->remark = '';
            $recharge->status = RechargeStatus::SUCCESS;
            $recharge->fee = 0;
            $recharge->datetime = Carbon::now()->timestamp;
            if (!$recharge->save()) {
                throw new \Exception(Lang::get('tips_19'));
            }
            $assets_log = new AssetsLog;
            $assets_log->user_id = $user->id;
            $assets_log->coin = $coin;
            $assets_log->amount = $amount;
            $assets_log->balance = $new_balance;
            $assets_log->type = AssetsLogTypes::RECHARGE;
            $assets_log->remark = AssetsLogTypes::RECHARGE->label();
            $assets_log->datetime = Carbon::now()->timestamp;
            $assets_log->recharge_id = $recharge->id;
            if (!$assets_log->save()) {
                throw new \Exception(Lang::get('tips_19'));
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return json_fail($e->getMessage());
        }
        return json_success();

    }

    public function withdraw(Request $request)
    {

    }

}
