<?php

namespace app\command;

use app\enums\ChainTypes;
use app\enums\CoinTypes;
use app\enums\LangTypes;
use app\model\Assets;
use app\model\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;


class CreateData extends Command
{
    protected static $defaultName = 'create:data';
    protected static $defaultDescription = '创建数据';

    protected function configure()
    {
        $this->addArgument('run', InputArgument::OPTIONAL, '是否执行');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $run = $input->getArgument('run');
        if (!$run) {
            $output->writeln('<error>操作失败</error>');
            return self::FAILURE;
        }
        $this->createUser();
        $output->writeln('<info>操作成功</info>');
        return self::SUCCESS;
    }

    public function createUser(): void
    {

        for ($i = 1; $i <= 10; $i++) {
            $user = new User;
            $user->identity = $i;
            $user->remark = '';
            $user->avatar = '/images/avatars/avatar.png';
            $user->save();
            $user->lang = LangTypes::ZH_CN;
            $user->pid = $i - 1;
            $user->is_real = 1;
            $user->save();
            $assetsList = CoinTypes::list();
            foreach ($assetsList as $value) {
                $assets = new Assets;
                $assets->user_id = $user->id;
                $assets->coin = $value;
                $assets->amount = 1000;
                $assets->chain = ChainTypes::SOLANA->value;
                $assets->save();
            }
        }


    }
}
