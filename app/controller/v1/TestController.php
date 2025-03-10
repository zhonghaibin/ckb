<?php

namespace app\controller\v1;

use app\enums\CoinTypes;
use app\enums\LangTypes;
use app\enums\UserUpgrade;
use app\model\Assets;
use app\model\User;
use app\services\CkbBonusService;
use app\services\UserUpgradeService;
use support\Db;
use support\Log;
//use Webman\RedisQueue\Redis;
use support\Request;
use Webman\Event\Event;
use Webman\RedisQueue\Client;
use support\Redis;

class TestController
{
    public function index(Request $request)
    {
//         Redis::publish('market.btcusdt.ticker',json_encode([
//             "ch" => "market.oneusdt.ticker",
//             "ts" => 1741231648141,
//             "tick" => [
//                 "open" => 0.012964,
//                 "high" => 0.013707,
//                 "low" => 0.012684,
//                 "close" => 0.013604,
//                 "amount" => 314458962.3252614,
//                 "vol" => 4174127.77528359,
//                 "count" => 57986,
//                 "bid" => 0.01359,
//                 "bidSize" => 7931,
//                 "ask" => 0.01362,
//                 "askSize" => 87217.42,
//                 "lastPrice" => 0.013604,
//                 "lastSize" => 6524.51
//             ]
//         ]));

//        Redis::send(UserUpgrade::USER_UPGRADE_JOB, ['user_id'=>2]);


//        $user =Db::table('users')->find(1);
//
////        return json($user);
//        $dd = new UserUpgradeService();
//
//        $ddd = $dd->setUser($user)->updateLevel();
//        return json_success($ddd);

//        $this->setData();
//        $this->createUser();

//        $ckb = new CkbBonusService();
//        $dd= $ckb->run();
//        return json($dd);
//        return json(CoinTypes::list());

//        $sol= new SolDailyEarnings();
//        $dd= $sol->earnings();
//        return  json($dd);
//
//        $hello = trans('hello'); // hello world!
//        return response($hello);
        return response('ok');
    }

    public function setData()
    {
        $marketData = [
            [
                "ch" => "market.oneusdt.ticker",
                "ts" => 1741231648141,
                "tick" => [
                    "open" => 0.012964,
                    "high" => 0.013707,
                    "low" => 0.012684,
                    "close" => 0.013604,
                    "amount" => 314458962.3252614,
                    "vol" => 4174127.77528359,
                    "count" => 57986,
                    "bid" => 0.01359,
                    "bidSize" => 7931,
                    "ask" => 0.01362,
                    "askSize" => 87217.42,
                    "lastPrice" => 0.013604,
                    "lastSize" => 6524.51
                ]
            ],
            [
                "ch" => "market.btcusdt.ticker",
                "ts" => 1741231649640,
                "tick" => [
                    "open" => 87729.22,
                    "high" => 92021.62,
                    "low" => 86752.41,
                    "close" => 91657.55,
                    "amount" => 5352.808291074833,
                    "vol" => 478615227.1889375,
                    "count" => 6264673,
                    "bid" => 91657.55,
                    "bidSize" => 0.028751,
                    "ask" => 91658.05,
                    "askSize" => 0.000111,
                    "lastPrice" => 91658.05,
                    "lastSize" => 0.000989
                ]
            ],
        ];
        foreach ($marketData as $data) {
            Redis::set($data['ch'], json_encode($data));
        }
    }


    public function createUser()
    {

        for ($i = 1; $i <= 10; $i++) {
            $user = new User;
            $user->identity = $i;
            $user->remark = '';
            $user->avatar = '/images/avatars/avatar' . mt_rand(0, 5) . '.png';
            $user->save();
            $user->lang = LangTypes::ZH_CN;
            $user->pid = $i - 1;
            $user->is_real = 1;
            $user->save();
            $assetsList = CoinTypes::list();
            foreach ($assetsList as $value) {
                $assets = new Assets;
                $assets->user_id = $user->id;
                $assets->coin = $value;

                $assets->save();
            }
        }


    }

}
