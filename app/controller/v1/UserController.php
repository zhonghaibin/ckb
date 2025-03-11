<?php

namespace app\controller\v1;

use app\enums\AssetsLogTypes;
use app\model\User;
use app\support\Lang;
use support\Request;
use app\utils\AesUtil;
use support\Db;

class UserController
{


    public function info(Request $request)
    {
        $userId = $request->userId;


        $user = User::query()
            ->where('id', $userId)
            ->first();

        if (!$user) {
            return json_fail(Lang::get('tips_18'));
        }


        $assets = Db::table('assets')
            ->where('user_id', $userId)
            ->select('user_id', 'coin', 'amount', 'bonus')
            ->first();


        $direct_count = Db::table('users')
            ->where('pid', $user->id)
            ->count();


        $direct_bonus = Db::table('assets_logs')
            ->where('type', AssetsLogTypes::DIRECTBONUS)
            ->where('user_id', $userId)
            ->sum('amount');


        $data = [
            'avatar' => $user->avatar,
            'identity' => $user->identity,
            'level' => $user->level,
            'direct_count' => $direct_count, // 直推人数
            'direct_bonus' => $direct_bonus, // 直推收益
            'assets' => $assets, // 用户资产
            'share_link'=>$user->share_link,
            'share_code'=>AesUtil::encrypt($userId)
        ];
        return json_success($data);
    }




    public function referralList(Request $request)
    {

        $list = Db::table('users')
            ->select('identity', 'level', 'created_at')
            ->where('pid', $request->userId)
            ->orderBy('id', 'desc')
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
        $team_amount = Db::table('recharges')
            ->whereIn('user_id', $team_ids)
            ->sum('amount');

        return json_success([
            [
                'total_team_count' => $totalTeamCount,
                'real_team_count' => $realTeamCount,
                'team_amount' => $team_amount
            ]
        ]);
    }
}
