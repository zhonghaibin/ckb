<?php

namespace app\controller\v1;


use app\services\BonusService;
use support\Request;
use support\Db;
use support\Log;

class TestController
{


    public function index(Request $request)
    {
//        BonusService::getInstance()->run();
        return response('ok');
    }





}
