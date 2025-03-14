<?php
/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Webman\Route;

Route::get('/', [app\controller\IndexController::class, 'index']);

Route::group('/v1', function () {
    Route::any('/test/index', [app\controller\v1\TestController::class, 'index']);

    Route::any('/index/baseInfo', [app\controller\v1\IndexController::class, 'baseInfo']);
    Route::any('/index/banner', [app\controller\v1\IndexController::class, 'banner']);
    Route::any('/index/notice', [app\controller\v1\IndexController::class, 'notice']);
    Route::any('/index/noticeList', [app\controller\v1\IndexController::class, 'noticeList']);

    Route::any('/auth/login', [app\controller\v1\AuthController::class, 'login']);
    Route::any('/notify/recharge', [app\controller\v1\NotifyController::class, 'recharge']);
});


Route::group('/v1', function () {

    Route::any('/user/info', [app\controller\v1\UserController::class, 'info']);
    Route::any('/user/referralList', [app\controller\v1\UserController::class, 'referralList']);
    Route::any('/user/teamList', [app\controller\v1\UserController::class, 'teamList']);

    Route::any('/assets/recharge', [app\controller\v1\AssetsController::class, 'recharge']);
    Route::any('/assets/rechargeList', [app\controller\v1\AssetsController::class, 'rechargeList']);
    Route::any('/assets/withdraw', [app\controller\v1\AssetsController::class, 'withdraw']);
    Route::any('/assets/withdrawList', [app\controller\v1\AssetsController::class, 'withdrawList']);
    Route::any('/assets/exchange', [app\controller\v1\AssetsController::class, 'exchange']);
    Route::any('/assets/getRate', [app\controller\v1\AssetsController::class, 'getRate']);
    Route::any('/assets/assetsList', [app\controller\v1\AssetsController::class, 'assetsList']);

    Route::any('/transaction/pledge', [app\controller\v1\TransactionController::class, 'pledge']);
    Route::any('/transaction/mev', [app\controller\v1\TransactionController::class, 'mev']);
    Route::any('/transaction/transactionList', [app\controller\v1\TransactionController::class, 'transactionList']);
    Route::any('/transaction/transactionLogList', [app\controller\v1\TransactionController::class, 'transactionLogList']);

})->middleware([
    app\middleware\JwtAuthMiddleware::class,
]);
Route::disableDefaultRoute();