<?php

namespace app\controller\v1;


use app\enums\QueueTask;
use app\enums\RechargeStatus;
use app\model\Transaction;
use app\services\BonusService;
use app\services\ReleaseBonusService;
use app\utils\AesUtil;
use support\Request;
use support\Db;
use support\Log;
use Webman\RedisQueue\Redis;
use ParagonIE_Sodium_Compat as Sodium;
use StephenHill\Base58;
use Carbon\Carbon;

class TestController
{

    public function index(Request $request)
    {

//        $dd='73Tf/g8HUJJ2o/B/6uaEtpXKpARQFQlnKdCTZf040To=';
//        $dd='j4eF/waizRHBZuzPgrZa8HDdXRDs6E7mfpuSpf/QW9g=';
//        $dd= AesUtil::decrypt($dd);
//        return json_success($dd);
//        (new BonusService())->run();
//        (new ReleaseBonusService())->run();


//       $dd=new  ReleaseService();
//       $dd->run();
//        $transaction = Transaction::query()->find(1);
//        $dd = (new BonusService())->runOne();
//        $dd->runOne($transaction);

        return response('ok');
    }


}
