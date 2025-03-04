<?php

namespace app\controller\v1;

use app\model\Member;
use support\Request;
use app\utils\AesUtil;

class MemberController
{
    public function info(Request $request)
    {
        $userId = $request->userId;
        $member = Member::query()->with(['assets' => function ($query) {
            $query->select(
                'uid', 'coin', 'money'
            );
        }])->findOrFail($userId);

        $data = [
            'avatar' => $member->avatar,
            'identity' => $member->identity,
            'level' => $member->level,
            'direct_num' => 0,//直推人数
            'earnings' => 0,//收益
            'assets' => $member->assets,
            'web_url' => 'http://xx.com',
        ];

        return json_success($data);
    }

    public function shareLink(Request $request)
    {
        $userId = $request->userId;
        $code = AesUtil::encrypt($userId);
        return 'http://xxx.com?code=' . $code;
    }

    /**
     * 兑换
     * @return void
     */
    public function exchange(){

    }

    /**
     * 充值
     * @param Request $request
     * @return void
     */
    public function recharge(Request $request)
    {

    }

    /**
     * 提现
     * @param Request $request
     * @return void
     */
    public function withdraw(Request $request)
    {

    }
}
