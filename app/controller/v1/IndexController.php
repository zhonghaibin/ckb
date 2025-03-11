<?php

namespace app\controller\v1;

use support\Db;


class IndexController
{

    public function baseInfo()
    {
        $config = get_system_config();
        $params = $config['ckb'];
        $days=array_column($params['staticRate'],'day');
        $day=15;
        if(!in_array($day,$days)){
            return json_fail('天数不存在');
        }
        return json_success($days);
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
