<?php

namespace app\service;

use app\model\Member;

class MemberUpgradeService
{

    const LEVEL = 3;

    private Member $member;

    private array $directRealChildIds = [];

    public function setMember(Member $member): static
    {

        $this->member = $member;
        $this->directRealChildIds = [];

        return $this;
    }

    public function updateLevel(): bool
    {
        if($this->member->level==0){
            return false;

        }

        if (!$this->directRealChildIds) {
            $this->findRealDirectChildIds();
        }
        if (!$this->directRealChildIds) {
            return false;
        }
        $members = Member::query()
            ->where('level', '>=', $this->member->level)
            ->whereIn('id', $this->directRealChildIds)
            ->select(['id', 'is_real', 'level'])
            ->get()->toArray();
        if (count($members) < MemberUpgradeService::LEVEL) {
            return false;
        }

        $this->member->level += 1;
        $this->member->save();
        return true;

    }

    private function findRealDirectChildIds(): void
    {
        if (!$this->directRealChildIds) {
            $this->directRealChildIds = Member::query()->where(['pid' => $this->member->id, 'is_real' => 1])->pluck('id')->toArray();
        }
    }

}