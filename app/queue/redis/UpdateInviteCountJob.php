<?php

namespace app\queue\redis;


use app\enums\UserJob;
use support\Db;
use Webman\RedisQueue\Consumer;

class UpdateInviteCountJob implements Consumer
{

    /**
     * php start.php queue:listen
     * 如果要以 守护进程 方式运行：
     * php start.php queue:restart
     * @var string
     */
    public $queue = UserJob::UPDATE_INVITE_COUNT->value;

    public function consume($data)
    {
        $user_id = $data['user_id'] ?? 0;
        $this->updateInviteCounts($user_id);

    }

    public function updateInviteCounts($parentId)
    {
        if (!$parentId) {
            return; // 如果没有上级，直接返回
        }

        // 1. 更新直推人数（direct_count +1）
        DB::table('users')->where('id', $parentId)->increment('direct_count');

        // 2. 更新团队人数（递归向上更新 team_count）
        while ($parentId) {
            DB::table('users')->where('id', $parentId)->increment('team_count');

            // 获取上一级的 `pid`
            $parentId = DB::table('users')->where('id', $parentId)->value('pid');
        }
    }

    public function onConsumeFailure(\Throwable $e, $package)
    {
        //每次消费失败时触发
    }
}
