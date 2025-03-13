<?php

namespace process;

use app\services\BonusService;
use Workerman\Crontab\Crontab;

class TimersTask
{
    public function onWorkerStart()
    {


        // 每秒钟执行一次
//        new Crontab('*/1 * * * * *', function(){
//            echo date('Y-m-d H:i:s')."\n";
//        });

        // 每5秒执行一次
//        new Crontab('*/5 * * * * *', function () {
//
//        });

        // 每天的00点00执行，注意这里省略了秒位
        new Crontab('0 0 * * *', function () {
            BonusService::getInstance()->run();
        });

    }
}