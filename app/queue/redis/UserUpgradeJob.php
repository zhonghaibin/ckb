<?php

namespace app\queue\redis;

use app\enums\QueueTask;
use app\services\UserUpgradeService;
use support\Db;
use Webman\RedisQueue\Consumer;

class UserUpgradeJob implements Consumer
{

    /**
     * php start.php queue:listen
     * 如果要以 守护进程 方式运行：
     * php start.php queue:restart
     * @var string
     */
    public $queue = QueueTask::USER_UPGRADE->value;

    public function consume($data)
    {
        $user_id = $data['user_id'] ?? 0;
        $this->executeForParentUsers($user_id);//查找该链上的用户是否要升级

    }


    public function executeForParentUsers($user_id): void
    {
        $user =Db::table('users')->find($user_id);
        if ($user) {
            $userUpgradeService = new UserUpgradeService();
            $userUpgradeService->setUser($user)->updateLevel();
            if ($user->pid != 0) {
                $this->executeForParentUsers($user->pid);
            }

        }


    }


    public function onConsumeFailure(\Throwable $e, $package)
    {
        //每次消费失败时触发
    }
}
