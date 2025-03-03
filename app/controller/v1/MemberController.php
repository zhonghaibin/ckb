<?php

namespace app\controller\v1;

use support\Request;
use app\utils\AesUtil;

class MemberController
{
    public function info(Request $request)
    {
        $userId = $request->userId;

        return json([
            'code' => 200,
            'msg' => '获取用户信息成功',
            'data' => [
                'user_id' => $userId,
            ],
        ]);
    }

    public function shareLink(Request $request){
        $userId = $request->userId;
        $code = AesUtil::encrypt($userId);
        return 'http://xxx.com?code='.$code;
    }
}
