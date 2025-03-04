<?php

namespace app\event;

use app\model\Assets;
use app\model\Member;
use app\model\Transaction;
use app\model\TransactionLog;
use Carbon\Carbon;
use support\Db;
use support\Log;

class CkbDirectEarnings
{

    protected array $rates = [
        15 => 0.08,
        30 => 0.12,
        60 => 0.15,
    ];


    //直推收益
    public function earnings(): bool
    {

    }



}