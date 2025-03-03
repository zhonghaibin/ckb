<?php

namespace app\controller\v1;

use app\model\Member;
use app\service\MemberUpgradeService;
use support\Request;
use support\Db;


class IndexController
{
    public function index(Request $request)
    {


        $param = [
            "callbackUrl" => "http://115.205.0.178:9011/taaBizApi/taaInitData",
            "action" => "login",
            "actionId" => "1648522106711",
            "blockchains" => [
                [
                    "chainId" => "1",
                    "network" => "ethereum"
                ]
            ],
            "dappIcon" => "https://eosknights.io/img/icon.png",
            "dappName" => "zs",
            "protocol" => "TokenPocket",
            "version" => "2.0"
        ];

        $encodedParam = urlencode(json_encode($param));
        $str= "<a href='tpoutside://pull.activity?param=$encodedParam'>Open TokenPocket to authorize</a>";

        return response($str);

//        $member = Member::query()->first();
//        $dd = new MemberUpgradeService();
//        $ddd= $dd->setMember($member)->updateLevel();
//        return json($ddd);

    }


}
