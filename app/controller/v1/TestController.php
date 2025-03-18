<?php

namespace app\controller\v1;


use app\enums\QueueTask;
use app\enums\RechargeStatus;
use app\services\BonusService;
use support\Request;
use support\Db;
use support\Log;
use Webman\RedisQueue\Redis;
use ParagonIE_Sodium_Compat as Sodium;
use StephenHill\Base58;

class TestController
{


    public function index(Request $request)
    {

        return response('ok');
    }



}
