<?php

namespace app\controller\v1;

use app\enums\CoinTypes;
use app\enums\LangTypes;
use app\model\Assets;
use app\model\User;
use app\services\CkbBonusService;
use support\Log;
use support\Redis;
use support\Request;


class IndexController
{
    public function index(Request $request)
    {

        $this->dd();
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

    public function dd(){
//        $marketData = [
//            [
//                "ch" => "market.oneusdt.ticker",
//                "ts" => 1741231648141,
//                "tick" => [
//                    "open" => 0.012964,
//                    "high" => 0.013707,
//                    "low" => 0.012684,
//                    "close" => 0.013604,
//                    "amount" => 314458962.3252614,
//                    "vol" => 4174127.77528359,
//                    "count" => 57986,
//                    "bid" => 0.01359,
//                    "bidSize" => 7931,
//                    "ask" => 0.01362,
//                    "askSize" => 87217.42,
//                    "lastPrice" => 0.013604,
//                    "lastSize" => 6524.51
//                ]
//            ],
//            [
//                "ch" => "market.btcusdt.ticker",
//                "ts" => 1741231649640,
//                "tick" => [
//                    "open" => 87729.22,
//                    "high" => 92021.62,
//                    "low" => 86752.41,
//                    "close" => 91657.55,
//                    "amount" => 5352.808291074833,
//                    "vol" => 478615227.1889375,
//                    "count" => 6264673,
//                    "bid" => 91657.55,
//                    "bidSize" => 0.028751,
//                    "ask" => 91658.05,
//                    "askSize" => 0.000111,
//                    "lastPrice" => 91658.05,
//                    "lastSize" => 0.000989
//                ]
//            ],
//            [
//                "ch" => "market.btcusdt.ticker",
//                "ts" => 1741231649740,
//                "tick" => [
//                    "open" => 87729.22,
//                    "high" => 92021.62,
//                    "low" => 86752.41,
//                    "close" => 91657.55,
//                    "amount" => 5352.808291074833,
//                    "vol" => 478615227.1889375,
//                    "count" => 6264673,
//                    "bid" => 91658.05,
//                    "bidSize" => 0.319456,
//                    "ask" => 91665.18,
//                    "askSize" => 0.000199,
//                    "lastPrice" => 91658.05,
//                    "lastSize" => 0.000989
//                ]
//            ],
//            [
//                "ch" => "market.btcusdt.ticker",
//                "ts" => 1741231649840,
//                "tick" => [
//                    "open" => 87729.22,
//                    "high" => 92021.62,
//                    "low" => 86752.41,
//                    "close" => 91657.55,
//                    "amount" => 5352.808291074833,
//                    "vol" => 478615227.1889375,
//                    "count" => 6264673,
//                    "bid" => 91666.11,
//                    "bidSize" => 0.001543,
//                    "ask" => 91666.12,
//                    "askSize" => 0.227526,
//                    "lastPrice" => 91658.05,
//                    "lastSize" => 0.000989
//                ]
//            ],
//            [
//                "ch" => "market.oneusdt.ticker",
//                "ts" => 1741231649841,
//                "tick" => [
//                    "open" => 0.012964,
//                    "high" => 0.013707,
//                    "low" => 0.012684,
//                    "close" => 0.013604,
//                    "amount" => 314458962.3252614,
//                    "vol" => 4174127.77528359,
//                    "count" => 57986,
//                    "bid" => 0.01359,
//                    "bidSize" => 7931,
//                    "ask" => 0.01362,
//                    "askSize" => 79286.42,
//                    "lastPrice" => 0.013604,
//                    "lastSize" => 6524.51
//                ]
//            ],
//            [
//                "ch" => "market.btcusdt.ticker",
//                "ts" => 1741231649940,
//                "tick" => [
//                    "open" => 87729.22,
//                    "high" => 92021.62,
//                    "low" => 86752.41,
//                    "close" => 91657.55,
//                    "amount" => 5352.808291074833,
//                    "vol" => 478615227.1889375,
//                    "count" => 6264673,
//                    "bid" => 91666.11,
//                    "bidSize" => 0.001543,
//                    "ask" => 91666.12,
//                    "askSize" => 0.009342,
//                    "lastPrice" => 91658.05,
//                    "lastSize" => 0.000989
//                ]
//            ],
//            [
//                "ch" => "market.btcusdt.ticker",
//                "ts" => 1741231650070,
//                "tick" => [
//                    "open" => 87729.22,
//                    "high" => 92021.62,
//                    "low" => 86752.41,
//                    "close" => 91657.55,
//                    "amount" => 5352.808291074833,
//                    "vol" => 478615227.1889375,
//                    "count" => 6264673,
//                    "bid" => 91666.11,
//                    "bidSize" => 0.025,
//                    "ask" => 91666.12,
//                    "askSize" => 0.228079,
//                    "lastPrice" => 91658.05,
//                    "lastSize" => 0.000989
//                ]
//            ],
//            [
//                "ch" => "market.btcusdt.ticker",
//                "ts" => 1741231650340,
//                "tick" => [
//                    "open" => 87729.22,
//                    "high" => 92021.62,
//                    "low" => 86752.41,
//                    "close" => 91657.55,
//                    "amount" => 5352.808291074833,
//                    "vol" => 478615227.1889375,
//                    "count" => 6264673,
//                    "bid" => 91666.11,
//                    "bidSize" => 0.025,
//                    "ask" => 91666.12,
//                    "askSize" => 0.228605,
//                    "lastPrice" => 91658.05,
//                    "lastSize" => 0.000989
//                ]
//            ],
//            [
//                "ch" => "market.btcusdt.ticker",
//                "ts" => 1741231650440,
//                "tick" => [
//                    "open" => 87729.22,
//                    "high" => 92021.62,
//                    "low" => 86752.41,
//                    "close" => 91657.55,
//                    "amount" => 5352.808291074833,
//                    "vol" => 478615227.1889375,
//                    "count" => 6264673,
//                    "bid" => 91666.11,
//                    "bidSize" => 0.025,
//                    "ask" => 91666.12,
//                    "askSize" => 0.228406,
//                    "lastPrice" => 91658.05,
//                    "lastSize" => 0.000989
//                ]
//            ],
//            [
//                "ch" => "market.btcusdt.ticker",
//                "ts" => 1741231650540,
//                "tick" => [
//                    "open" => 87729.22,
//                    "high" => 92021.62,
//                    "low" => 86752.41,
//                    "close" => 91657.55,
//                    "amount" => 5352.808291074833,
//                    "vol" => 478615227.1889375,
//                    "count" => 6264673,
//                    "bid" => 91666.11,
//                    "bidSize" => 0.025,
//                    "ask" => 91666.12,
//                    "askSize" => 0.228605,
//                    "lastPrice" => 91658.05,
//                    "lastSize" => 0.000989
//                ]
//            ],
//            [
//                "ch" => "market.btcusdt.ticker",
//                "ts" => 1741231650640,
//                "tick" => [
//                    "open" => 87729.22,
//                    "high" => 92021.62,
//                    "low" => 86752.41,
//                    "close" => 91657.55,
//                    "amount" => 5352.808291074833,
//                    "vol" => 478615227.1889375,
//                    "count" => 6264673,
//                    "bid" => 91666.11,
//                    "bidSize" => 0.025,
//                    "ask" => 91666.12,
//                    "askSize" => 0.228406,
//                    "lastPrice" => 91658.05,
//                    "lastSize" => 0.000989
//                ]
//            ],
//            [
//                "ch" => "market.oneusdt.ticker",
//                "ts" => 1741231650641,
//                "tick" => [
//                    "open" => 0.012964,
//                    "high" => 0.013707,
//                    "low" => 0.012684,
//                    "close" => 0.013604,
//                    "amount" => 314458962.3252614,
//                    "vol" => 4174127.77528359,
//                    "count" => 57986,
//                    "bid" => 0.01359,
//                    "bidSize" => 7931,
//                    "ask" => 0.013618,
//                    "askSize" => 79286.42,
//                    "lastPrice" => 0.013604,
//                    "lastSize" => 6524.51
//                ]
//            ],
//            [
//                "ch" => "market.btcusdt.ticker",
//                "ts" => 1741231650740,
//                "tick" => [
//                    "open" => 87729.22,
//                    "high" => 92021.62,
//                    "low" => 86752.41,
//                    "close" => 91657.55,
//                    "amount" => 5352.808291074833,
//                    "vol" => 478615227.1889375,
//                    "count" => 6264673,
//                    "bid" => 91666.11,
//                    "bidSize" => 0.025,
//                    "ask" => 91666.12,
//                    "askSize" => 0.228605,
//                    "lastPrice" => 91658.05,
//                    "lastSize" => 0.000989
//                ]
//            ],
//            [
//                "ch" => "market.oneusdt.ticker",
//                "ts" => 1741231650741,
//                "tick" => [
//                    "open" => 0.012964,
//                    "high" => 0.013707,
//                    "low" => 0.012684,
//                    "close" => 0.013604,
//                    "amount" => 314458962.3252614,
//                    "vol" => 4174127.77528359,
//                    "count" => 57986,
//                    "bid" => 0.01359,
//                    "bidSize" => 7931,
//                    "ask" => 0.013617,
//                    "askSize" => 7931,
//                    "lastPrice" => 0.013604,
//                    "lastSize" => 6524.51
//                ]
//            ],
//            [
//                "ch" => "market.oneusdt.ticker",
//                "ts" => 1741231650841,
//                "tick" => [
//                    "open" => 0.012964,
//                    "high" => 0.013707,
//                    "low" => 0.012684,
//                    "close" => 0.013603,
//                    "amount" => 314463594.4552614,
//                    "vol" => 4174190.78614798,
//                    "count" => 57987,
//                    "bid" => 0.01359,
//                    "bidSize" => 7931,
//                    "ask" => 0.013616,
//                    "askSize" => 79286.42,
//                    "lastPrice" => 0.013603,
//                    "lastSize" => 4632.13
//                ]
//            ],
//            [
//                "ch" => "market.oneusdt.ticker",
//                "ts" => 1741231650941,
//                "tick" => [
//                    "open" => 0.012964,
//                    "high" => 0.013707,
//                    "low" => 0.012684,
//                    "close" => 0.013603,
//                    "amount" => 314463594.4552614,
//                    "vol" => 4174190.78614798,
//                    "count" => 57987,
//                    "bid" => 0.01359,
//                    "bidSize" => 7931,
//                    "ask" => 0.013615,
//                    "askSize" => 7931,
//                    "lastPrice" => 0.013603,
//                    "lastSize" => 4632.13
//                ]
//            ],
//            [
//                "ch" => "market.btcusdt.ticker",
//                "ts" => 1741231651040,
//                "tick" => [
//                    "open" => 87729.22,
//                    "high" => 92021.62,
//                    "low" => 86752.41,
//                    "close" => 91657.55,
//                    "amount" => 5352.808291074833,
//                    "vol" => 478615227.1889375,
//                    "count" => 6264673,
//                    "bid" => 91666.11,
//                    "bidSize" => 0.025,
//                    "ask" => 91666.12,
//                    "askSize" => 0.015041,
//                    "lastPrice" => 91658.05,
//                    "lastSize" => 0.000989
//                ]
//            ],
//            [
//                "ch" => "market.oneusdt.ticker",
//                "ts" => 1741231651041,
//                "tick" => [
//                    "open" => 0.012964,
//                    "high" => 0.013707,
//                    "low" => 0.012684,
//                    "close" => 0.013603,
//                    "amount" => 314463594.4552614,
//                    "vol" => 4174190.78614798,
//                    "count" => 57987,
//                    "bid" => 0.01359,
//                    "bidSize" => 7931,
//                    "ask" => 0.013614,
//                    "askSize" => 79286.42,
//                    "lastPrice" => 0.013603,
//                    "lastSize" => 4632.13
//                ]
//            ],
//            [
//                "ch" => "market.btcusdt.ticker",
//                "ts" => 1741231651140,
//                "tick" => [
//                    "open" => 87729.22,
//                    "high" => 92021.62,
//                    "low" => 86752.41,
//                    "close" => 91657.55,
//                    "amount" => 5352.808291074833,
//                    "vol" => 478615227.1889375,
//                    "count" => 6264673,
//                    "bid" => 91666.11,
//                    "bidSize" => 0.025,
//                    "ask" => 91666.12,
//                    "askSize" => 0.0055,
//                    "lastPrice" => 91658.05,
//                    "lastSize" => 0.000989
//                ]
//            ],
//            [
//                "ch" => "market.btcusdt.ticker",
//                "ts" => 1741231651240,
//                "tick" => [
//                    "open" => 87729.22,
//                    "high" => 92021.62,
//                    "low" => 86752.41,
//                    "close" => 91657.55,
//                    "amount" => 5352.808291074833,
//                    "vol" => 478615227.1889375,
//                    "count" => 6264673,
//                    "bid" => 91666.11,
//                    "bidSize" => 0.025,
//                    "ask" => 91666.12,
//                    "askSize" => 0.005699,
//                    "lastPrice" => 91658.05,
//                    "lastSize" => 0.000989
//                ]
//            ],
//            [
//                "ch" => "market.btcusdt.ticker",
//                "ts" => 1741231651540,
//                "tick" => [
//                    "open" => 87729.22,
//                    "high" => 92021.62,
//                    "low" => 86752.41,
//                    "close" => 91657.55,
//                    "amount" => 5352.808291074833,
//                    "vol" => 478615227.1889375,
//                    "count" => 6264673,
//                    "bid" => 91666.11,
//                    "bidSize" => 0.025,
//                    "ask" => 91666.12,
//                    "askSize" => 0.006026,
//                    "lastPrice" => 91658.05,
//                    "lastSize" => 0.000989
//                ]
//            ],
//           ];
//        $i=1;
//        while (true){
//            usleep(500000);
//            if($i%2==0){
//                $data=['ch'=>'market.btcusdt.ticker','ts'=>$i];
//            }else{
//                $data=['ch'=>'market.oneusdt.ticker','ts'=>$i];
//            }
//
//            Redis::set($data['ch'], json_encode($data));
//            $i++;
//            if($i>100){
//                break;
//            }
//        }


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
