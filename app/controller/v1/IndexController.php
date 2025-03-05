<?php

namespace app\controller\v1;

use app\enums\CoinTypes;
use app\enums\LangTypes;
use app\model\Assets;
use app\model\User;
use app\services\CkbBonusService;
use support\Request;


class IndexController
{
    public function index(Request $request)
    {


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
