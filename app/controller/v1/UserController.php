<?php

namespace app\controller\v1;

use app\enums\AssetsLogTypes;
use app\enums\CoinTypes;
use app\enums\TransactionStatus;
use app\enums\TransactionTypes;
use app\model\Assets;
use app\model\AssetsLog;
use app\model\Recharge;
use app\model\Transaction;
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


        $assetsList = Assets::query()
            ->where('user_id', $userId)
            ->select('coin', 'amount', 'bonus')
            ->get();

        $direct_count = User::query()
            ->where('pid', $user->id)
            ->count();


        $direct_bonus = AssetsLog::query()
            ->where('type', AssetsLogTypes::DIRECTBONUS)
            ->where('user_id', $userId)
            ->sum('amount');

        $pledge_ckb_amount = Transaction::query()->where('user_id', $userId)
            ->where('transaction_type', TransactionTypes::PLEDGE)
            ->where('coin', CoinTypes::CKB)
            ->where('status', TransactionStatus::NORMAL)
            ->sum('amount');

        $pledge_one_amount = Transaction::query()->where('user_id', $userId)
            ->where('transaction_type', TransactionTypes::PLEDGE)
            ->where('coin', CoinTypes::ONE)
            ->where('status', TransactionStatus::NORMAL)
            ->sum('amount');
        $data = [
            'avatar' => $user->avatar,
            'identity' => $user->identity,
            'level' => $user->level,
            'direct_count' => $direct_count, // 直推人数
            'direct_bonus' => $direct_bonus, // 直推收益
            'assets' => $assetsList[0], // 用户资产
            'assets_list' => $assetsList,
            'share_link' => $user->share_link,
            'share_code' => AesUtil::encrypt($userId),
            'pledge' => [
                'ckb' => [
                    'amount' => $assetsList[2]['amount'],
                    'pledge_amount' => $pledge_ckb_amount,
                ],
                'one' => [
                    'amount' => $assetsList[1]['amount'],
                    'pledge_amount' => $pledge_one_amount,
                ]
            ]

        ];
        return json_success($data);
    }


    public function referralList(Request $request)
    {

        $list = User::query()
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
        $team_amount = Recharge::query()
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
