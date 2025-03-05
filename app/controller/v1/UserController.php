<?php

namespace app\controller\v1;

use app\model\User;
use support\Request;
use app\utils\AesUtil;
use support\Db;

class UserController
{

    //团队人数
    function getTeamCount($userId)
    {
        $ids = [$userId]; // 记录所有下级用户ID
        $team_count = 0;

        do {
            // 查询当前层级的下级用户
            $subUsers = Db::table('users')
                ->whereIn('pid', $ids)
                ->pluck('id')
                ->toArray();

            $team_count += count($subUsers);
            $ids = $subUsers; // 继续查询下一层级
        } while (!empty($ids));

        return $team_count;
    }


    public function info(Request $request)
    {
        $userId = $request->userId;
        $user = User::query()->with(['assets' => function ($query) {
            $query->select(
                'user_id', 'coin', 'money'
            );
        }])->findOrFail($userId);
        $direct_count = Db::table('users')
            ->where('pid', $user->id)
            ->count();
        $data = [
            'avatar' => $user->avatar,
            'identity' => $user->identity,
            'level' => $user->level,
            'direct_count' => $direct_count,//直推人数
            'earnings' => 0,//收益
            'assets' => $user->assets,
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


    public function referralList(){

    }

    public function teamList(){

    }
}
