<?php

namespace app\services;

use app\enums\UserIsReal;
use support\Db;

class UserUpgradeService
{
    const LEVEL = 3;

    private object $user;
    private array $directRealChildIds = [];

    public function setUser($user): static
    {
        $this->user = $user;
        $this->directRealChildIds = [];

        return $this;
    }

    public function updateLevel()
    {
        if (!$this->directRealChildIds) {
            $this->findRealDirectChildIds();
        }
        if (!$this->directRealChildIds) {
            return false;
        }

        $users = Db::table('users')
            ->where('level', '>=', $this->user->level)
            ->whereIn('id', $this->directRealChildIds)
            ->select(['id', 'is_real', 'level'])
            ->get()
            ->toArray();

        if (count($users) < self::LEVEL) {
            return false;
        }

        Db::table('users')->where('id', $this->user->id)->update([
            'level' => $this->user->level + 1
        ]);

        return true;
    }

    private function findRealDirectChildIds()
    {
        if (!$this->directRealChildIds) {
            $this->directRealChildIds = Db::table('users')
                ->where([
                    'pid' => $this->user->id,
                    'is_real' => UserIsReal::NORMAL->value
                ])
                ->pluck('id')
                ->toArray();
        }
    }
}
