<?php

namespace app\service;

use app\model\User;

class UserUpgradeService
{

    const LEVEL = 3;

    private User $user;

    private array $directRealChildIds = [];

    public function setUser(User $user): static
    {

        $this->user = $user;
        $this->directRealChildIds = [];

        return $this;
    }

    public function updateLevel(): bool
    {
        if($this->user->level==0){
            return false;

        }

        if (!$this->directRealChildIds) {
            $this->findRealDirectChildIds();
        }
        if (!$this->directRealChildIds) {
            return false;
        }
        $users = User::query()
            ->where('level', '>=', $this->user->level)
            ->whereIn('id', $this->directRealChildIds)
            ->select(['id', 'is_real', 'level'])
            ->get()->toArray();
        if (count($users) < UserUpgradeService::LEVEL) {
            return false;
        }

        $this->user->level += 1;
        $this->user->save();
        return true;

    }

    private function findRealDirectChildIds(): void
    {
        if (!$this->directRealChildIds) {
            $this->directRealChildIds = User::query()->where(['pid' => $this->user->id, 'is_real' => 1])->pluck('id')->toArray();
        }
    }

}