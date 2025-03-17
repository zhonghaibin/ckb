<?php

namespace app\command;


use support\Redis;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class Test extends Command
{
    protected static $defaultName = 'test';
    protected static $defaultDescription = '推送数据';


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // 每秒钟执行一次
        while (true) {
            $this->publishData();
            sleep(1);
        }

    }

    public function publishData(): void
    {
        $marketData = [
            [
                "ch" => "market.oneusdt.ticker",
                "ts" => 1741231648141,
                "tick" => [
                    "open" => 0.012964,
                    "high" => 0.013707,
                    "low" => 0.012684,
                    "close" => 0.013604,
                    "amount" => 314458962.3252614,
                    "vol" => 4174127.77528359,
                    "count" => 57986,
                    "bid" => 0.01359,
                    "bidSize" => 7931,
                    "ask" => 0.01362,
                    "askSize" => 87217.42,
                    "lastPrice" => 0.013604,
                    "lastSize" => 6524.51
                ]
            ],
            [
                "ch" => "market.ckbusdt.ticker",
                "ts" => 1741231649640,
                "tick" => [
                    "open" => 87729.22,
                    "high" => 92021.62,
                    "low" => 86752.41,
                    "close" => 91657.55,
                    "amount" => 5352.808291074833,
                    "vol" => 478615227.1889375,
                    "count" => 6264673,
                    "bid" => 91657.55,
                    "bidSize" => 0.028751,
                    "ask" => 91658.05,
                    "askSize" => 0.000111,
                    "lastPrice" => 91658.05,
                    "lastSize" => 0.000989
                ]
            ],
        ];
        foreach ($marketData as $data) {
            Redis::publish($data['ch'], json_encode($data));
            Redis::set($data['ch'], json_encode($data));
        }
    }
}
