<?php

namespace process;

use app\services\BonusService;
use Workerman\Crontab\Crontab;

class TimersTask
{
    //crontab并不是异步的，例如一个task进程里设置了A和B两个定时器，都是每秒执行一次任务，但是A任务耗时10秒，那么B需要等待A执行完才能被执行，导致B执行会有延迟
    public function onWorkerStart()
    {
        //注意：定时任务不会马上执行，所有定时任务进入下一分钟才会开始计时执行

        // 每天的00点00执行，注意这里省略了秒位
        new Crontab('0 0 * * *', function () {
            $bonusService = new BonusService();
            $bonusService->run();
        });

    }
}