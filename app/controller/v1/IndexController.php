<?php

namespace app\controller\v1;

use support\Db;


class IndexController
{

    public function baseInfo(){
        $config = Db::table('options')->where('name', 'config')->value('value');
        $config = json_decode($config, true);
        return json_success($config);
    }

    public function banner()
    {
        $banners = Db::table('banners')->where('status', 1)->get();
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
