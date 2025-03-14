<?php

namespace app\controller\v1;


use app\enums\QueueTask;
use app\services\BonusService;
use support\Request;
use support\Db;
use support\Log;
use Webman\RedisQueue\Redis;

class TestController
{


    public function index(Request $request)
    {
//        $signature='3ErvhTz5ADnf2869DfXejmDTEug1qz6eE3DVSiG3WPthDf97vfYCe7KE8gNx3WK3JdRg3Q17LChHDAJFUmN2PAjU';
//        $response = get_transaction_by_signature($signature);
//        return json($response);
//        $recharge_id = 8;
//        Redis::send(QueueTask::RECHARGE->value, [
//            'recharge_id' => 0
//        ]);

//        BonusService::getInstance()->run();
        Log::info('This is a test log');
        Log::error('Something went wrong!');
        Log::debug('Something went wrong!');
        return response('ok');
    }


}
