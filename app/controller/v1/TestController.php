<?php

namespace app\controller\v1;


use app\enums\QueueTask;
use app\enums\RechargeStatus;
use app\services\BonusService;
use support\Request;
use support\Db;
use support\Log;
use Webman\RedisQueue\Redis;

class TestController
{


    public function index(Request $request)
    {
//        $signature='2yc7eBfqRjS6Kfa6qJXRyjo6hybY7QB2BY4Xn7x9JfJYbkWEBGrF8CF4eAngpdmFdiUresZRzerLWVs6TvHvyoKj';
//        $response = get_transaction_by_signature($signature);
//        $result=parse_solana_transaction($response['result']['meta']);
//        return json($result);
//        $recharge_id = 8;
//        Redis::send(QueueTask::RECHARGE->value, [
//            'recharge_id' => 0
//        ]);

//        BonusService::getInstance()->run();
//        Log::info('This is a test log');
//        Log::error('Something went wrong!');
//        Log::debug('Something went wrong!');
        return response('ok');
    }


}
