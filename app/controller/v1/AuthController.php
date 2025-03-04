<?php

namespace app\controller\v1;

use app\enums\CoinTypes;
use app\enums\LangTypes;
use app\enums\MemberStatus;
use app\model\Assets;
use app\model\Member;
use Carbon\Carbon;
use support\Request;
use support\Db;
use app\utils\JwtUtil;
use app\utils\AesUtil;

class AuthController
{


    public function openTokenPocketUrl()
    {
        $param = [
            "callbackUrl" => "https://agent.xsztest.top/v1/auth/authorize",
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


    public function authorize(Request $request)
    {
        $rawData = $request->rawBody();
        $data = json_decode($rawData, true);
        if (isset($data['action']) && $data['action'] == 'login') {
            $identity = $data['account'];
            try {
                $member = Member::query()->where(['identity' => $identity])->first();
                if (!$member) {
                    $member = new Member;
                    $member->identity = $identity;
                    $member->reg_datetime = Carbon::now()->timestamp;
                    $member->remark = '';
                    $member->save();
                    $member_id = $member->id;
                    $member->lang = LangTypes::ZH_CN;
                    $member->status = MemberStatus::NORMAL;
                    $member->avatar = '/images/avatars/avatar'. mt_rand(0, 5).'.png';
                    $member->save();
                    $assetsList = CoinTypes::list();
                    foreach ($assetsList as $value) {
                        $assets = new Assets;
                        $assets->uid = $member->id;
                        $assets->coin = $value;
                        $assets->save();
                    }
                }
                Db::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                return json_fail($e->getMessage());
            }
            $token = JwtUtil::generateToken($member->id);

            return json_success($token);
        }

        return json_fail('出错了');

    }


    public function login(Request $request)
    {
        $identity = $request->post('identity', '');
        if (empty($identity)) {
            return json_fail('用户凭证不能为空');
        }
        $member = Member::query()->where(['identity' => $identity, 'status' => MemberStatus::NORMAL])->first();
        if (empty($member)) {
            return json_fail('凭证错误,登录失败');
        }
        $token = JwtUtil::generateToken($member->id);
        return json_success([
            'token' => $token,
        ], '登录成功');

    }


    public function register(Request $request)
    {

        Db::beginTransaction();

        try {
            $identity = $request->post('identity', '');
            $code = $request->post('code', '');
            if (empty($identity)) {
                return json_fail('用户Id不能为空');
            }
            $member = Member::query()->where(['identity' => $identity])->first();

            if ($member) {
                return json_fail('用户已存在');
            }

            $member = new Member;
            $member->identity = $identity;
            $member->reg_datetime = Carbon::now()->timestamp;
            $member->remark = '';
            $member->avatar = '/images/avatars/avatar'. mt_rand(0, 5).'.png';
            $member->save();
            $member_id = $member->id;
            $member->lang = LangTypes::ZH_CN;
            if (!empty($code)) {
                $pid = AesUtil::decrypt($code);
                if (is_numeric($pid)) {
                    $member->pid = $pid;
                }

            }
            $member->save();
            $assetsList = CoinTypes::list();
            foreach ($assetsList as $value) {
                $assets = new Assets;
                $assets->uid = $member->id;
                $assets->coin = $value;
                $assets->save();
            }

            Db::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return json_fail($e->getMessage());
        }
        return json_success();

    }


}
