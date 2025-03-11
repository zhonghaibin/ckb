<?php

namespace app\controller\v1;

use app\enums\CoinTypes;
use app\enums\LangTypes;
use app\enums\TransactionStatus;
use app\enums\TransactionTypes;
use app\enums\UserUpgrade;
use app\model\Assets;
use app\model\User;
use app\services\CkbBonusService;
use app\services\UserUpgradeService;
use Carbon\Carbon;
use support\Db;
use support\Log;

//use Webman\RedisQueue\Redis;
use support\Request;
use Webman\Event\Event;
use Webman\RedisQueue\Client;
use support\Redis;

class TestController
{
    protected array $rates = [];

    protected float $direct_rate = 0;

    protected array $level_diff_rates = [];

    protected float $same_level_rate = 0;

    public function index(Request $request)
    {
        $midnightTimestamp = Carbon::today()->timestamp;

        // 获取符合条件的交易记录
        $transactions = DB::table('transactions')
            ->where('transaction_type', TransactionTypes::SOL)
            ->where('status', TransactionStatus::NORMAL)
            ->whereRaw('run_day < day')
            ->where('runtime', '<', $midnightTimestamp)
            ->get();

        return  json_success($transactions);
        foreach ($transactions as $transaction) {
            $params = json_decode($transaction->rates, true);
            $this->rates = array_column(array_map(fn($item) => [$item['day'], [(int)$item['rate']['min'], (int)$item['rate']['max']]], $params['staticRate']), 1, 0);
            $this->direct_rate = $params['directRate'];
            $this->level_diff_rates = array_column($params['levelDiffRate'], 'rate', 'level');
            $this->same_level_rate = $params['sameLevelRate'];
            $data = [$this->rates, $this->direct_rate, $this->level_diff_rates, $this->same_level_rate];

            return json_success($data);
        }


//        $min = 6;
//        $max = 8;
//
//// 生成符合范围的随机数
//        $randomRate1 = mt_rand($min * 100, $max * 100) / 100;
//        $randomRate2 = mt_rand($min * 100, $max * 100) / 100;
//
//// 计算平均值并四舍五入到 2 位小数
//        $rate = round(($randomRate1 + $randomRate2) / 2, 2)/100;


//        return  response($rate);
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
