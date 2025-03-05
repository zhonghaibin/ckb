<?php

namespace app\controller\v1;

use app\enums\AssetsLogTypes;
use app\model\User;
use support\Request;
use app\utils\AesUtil;
use support\Db;

class UserController
{


    public function info(Request $request)
    {
        $userId = $request->userId;
        $user = User::query()->with(['assets' => function ($query) {
            $query->select(
                'user_id', 'coin', 'money', 'bonus'
            );
        }])->findOrFail($userId);

        $direct_count = Db::table('users')
            ->where('pid', $user->id)
            ->count();

        $direct_bonus = Db::table('assets_logs')
            ->where('type', AssetsLogTypes::DIRECTBONUS)
            ->where('user_id', $user->id)
            ->sum('money');
        $data = [
            'avatar' => $user->avatar,
            'identity' => $user->identity,
            'level' => $user->level,
            'direct_count' => $direct_count,//直推人数
            'direct_bonus' => $direct_bonus,//直推收益
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


    public function referralList(Request $request)
    {

        $list = Db::table('users')
            ->select('identity', 'level', 'created_at')
            ->where('pid', $request->userId)
            ->paginate(10)
            ->appends(request()->get());

        return json_success($list);
    }

    //团队人数
    public function teamList(Request $request)
    {
        $totalTeamCount = get_team_count($request->userId);
        $realTeamCount = get_team_count($request->userId, true);

        $team_ids = get_team_user_ids($request->userId);
        $team_money = Db::table('recharges')
            ->whereIn('user_id', $team_ids)
            ->sum('money');

        return json_success([
            [
                'total_team_count' => $totalTeamCount,
                'real_team_count' => $realTeamCount,
                'team_money' => $team_money
            ]
        ]);
    }
}
