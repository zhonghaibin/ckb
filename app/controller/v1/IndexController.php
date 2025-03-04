<?php

namespace app\controller\v1;

use app\enums\CoinTypes;
use app\event\CkbDailyEarnings;
use app\event\SolDailyEarnings;
use app\model\Member;
use app\service\MemberUpgradeService;
use support\Request;
use support\Db;


class IndexController
{
    public function index(Request $request)
    {



//        $ckb=new CkbDailyEarnings();
//        $dd= $ckb->earnings();
//        return json(CoinTypes::list());

//        $sol= new SolDailyEarnings();
//        $dd= $sol->earnings();
//        return  json($dd);
//
//        $hello = trans('hello'); // hello world!
//        return response($hello);
        return response('ok');
    }


}
