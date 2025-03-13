<?php

namespace app\controller\v1;

use app\services\AssetsService;
use support\Request;

class NotifyController
{

    public function recharge(Request $request)
    {
        $amount = $request->post('amount');
        $identity = $request->post('identity');
        $transactionService = new AssetsService();
        $transactionService->recharge($identity, $amount);
        return json_success();

    }

}
