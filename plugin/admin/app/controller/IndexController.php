<?php

namespace plugin\admin\app\controller;

use app\model\Recharge;
use app\model\Withdraw;
use plugin\admin\app\common\Util;
use plugin\admin\app\model\Option;
use app\model\User;
use support\exception\BusinessException;
use support\Request;
use support\Response;
use think\db\Where;
use Throwable;
use Workerman\Worker;

class IndexController
{

    /**
     * 无需登录的方法
     * @var string[]
     */
    protected $noNeedLogin = ['index'];

    /**
     * 不需要鉴权的方法
     * @var string[]
     */
    protected $noNeedAuth = ['dashboard'];

    /**
     * 后台主页
     * @param Request $request
     * @return Response
     * @throws BusinessException|Throwable
     */
    public function index(Request $request): Response
    {
        clearstatcache();
        if (!is_file(base_path('plugin/admin/config/database.php'))) {
            return raw_view('index/install');
        }
        $admin = admin();
        if (!$admin) {
            $name = 'system_config';
            $config = Option::where('name', $name)->value('value');
            $config = json_decode($config, true);
            $title = $config['logo']['title'] ?? 'webman admin';
            $logo = $config['logo']['image'] ?? '/app/admin/admin/images/logo.png';
            return raw_view('account/login', ['logo' => $logo, 'title' => $title]);
        }
        return raw_view('index/index');
    }

    /**
     * 仪表板
     * @param Request $request
     * @return Response
     * @throws Throwable
     */
    public function dashboard(Request $request): Response
    {
        // 今日新增用户数
        $today_user_count = User::where('created_at', '>', date('Y-m-d') . ' 00:00:00')->count();
        // 7天内新增用户数
        $day7_user_count = User::where('created_at', '>', date('Y-m-d H:i:s', time() - 7 * 24 * 60 * 60))->count();
        // 30天内新增用户数
        $day30_user_count = User::where('created_at', '>', date('Y-m-d H:i:s', time() - 30 * 24 * 60 * 60))->count();
        // 总用户数
        $user_count = User::count();

        //今日充值数
        $today_recharge_count = Recharge::where('created_at', '>', date('Y-m-d') . ' 00:00:00')->sum('total_amount');
        //今日提现数
        $today_withdraw_count = Withdraw::where('created_at', '>', date('Y-m-d') . ' 00:00:00')->sum('total_amount');

        // mysql版本
        $version = Util::db()->select('select VERSION() as version');
        $mysql_version = $version[0]->version ?? 'unknown';

        $day7_detail = [];
        $now = time();
        for ($i = 0; $i < 7; $i++) {
            $date = date('Y-m-d', $now - 24 * 60 * 60 * $i);
            $day7_detail[substr($date, 5)] = User::where('created_at', '>', "$date 00:00:00")
                ->where('created_at', '<', "$date 23:59:59")->count();
        }

        return raw_view('index/dashboard', [
            'today_user_count' => $today_user_count,
            'day7_user_count' => $day7_user_count,
            'day30_user_count' => $day30_user_count,
            'user_count' => $user_count,
            'php_version' => PHP_VERSION,
            'workerman_version' => Worker::VERSION,
            'webman_version' => Util::getPackageVersion('workerman/webman-framework'),
            'admin_version' => config('plugin.admin.app.version'),
            'mysql_version' => $mysql_version,
            'os' => PHP_OS,
            'day7_detail' => array_reverse($day7_detail),
            'today_recharge_count' => $today_recharge_count,
            'today_withdraw_count' => $today_withdraw_count
        ]);
    }

}
