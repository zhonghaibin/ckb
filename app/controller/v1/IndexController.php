<?php

namespace app\controller\v1;

use app\enums\CoinTypes;
use app\enums\LangTypes;
use app\model\Assets;
use app\model\User;
use app\services\CkbBonusService;
use support\Db;
use support\Log;
use support\Redis;
use support\Request;


class IndexController
{


    public function banner()
    {
        $banners = Db::table('banners')->get();
        return json_success($banners);
    }

    public function notice()
    {
        $notice = Db::table('notices')
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->first();
        return json_success($notice);
    }

    public function noticeList()
    {
        $notices = Db::table('notices')
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->appends(request()->get());
        return json_success($notices);
    }
}
