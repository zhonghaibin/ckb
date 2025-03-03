<?php

namespace app\controller\v1;

use app\model\Assets;
use app\model\Member;
use support\Request;
use support\Db;
use app\utils\JwtUtil;
use app\utils\AesUtil;

class AuthController
{

    public function login(Request $request)
    {
        $identity = $request->post('identity', '');
        if (empty($identity)) {
            return json_fail('用户凭证不能为空');
        }
        $member = Member::query()->where(['identity' => $identity, 'status' => 0])->first();
        if (empty($member)) {
            return json_fail('凭证错误,登录失败');
        }
        $token = JwtUtil::generateToken($member->id);
        return json_success([
            'token' => $token,
        ], '登录成功');

    }

    public function logout(Request $request)
    {

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

            $member = new Member();
            $member->identity = $identity;
            $member->reg_datetime = time();
            $member->remark = '';
            $member->save();
            $member_id = $member->id;
            $member->relation = '-' . $member_id . '-';
            if (!empty($code)) {
                $pid = AesUtil::decrypt($code);
                if (is_numeric($pid)) {
                    $relation = '-' . $pid . '-' . $member_id . '-';
                    $member->pid = $pid;
                    $member->relation = $relation;
                }

            }
            $member->save();
            $assetsList = ['USDT', 'ONE', 'CKB'];
            foreach ($assetsList as $key => $value) {
                $assets = new Assets();
                $assets->uid = $member->id;
                $assets->category = $key + 1;
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
