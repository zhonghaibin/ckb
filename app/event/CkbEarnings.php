<?php

namespace app\event;

use app\model\Member;
use app\model\Transaction;

class CkbEarnings
{

    protected $ratios=[
        15=>0.08,
        30=>0.12,
        60=>0.15,
    ];

    public function getMonthDays(){

    }

    //每日收益
    public function dailyEarnings()
    {


        $transactions = Transaction::query()->where(['exec_day','<','total_day'])->get();
        foreach ($transactions as $transaction){
//            $transaction->bonus
        }


    }

    //直推收益
    public function directEarnings()
    {

    }

    //平级收益
    public function equalGradeEarnings()
    {

    }
}