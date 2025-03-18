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
    Route::add(['GET', 'OPTIONS'],'/test/index', [app\controller\v1\TestController::class, 'index']);

    Route::add(['GET', 'OPTIONS'],'/index/baseInfo', [app\controller\v1\IndexController::class, 'baseInfo']);
    Route::add(['GET', 'OPTIONS'],'/index/banner', [app\controller\v1\IndexController::class, 'banner']);
    Route::add(['GET', 'OPTIONS'],'/index/notice', [app\controller\v1\IndexController::class, 'notice']);
    Route::add(['GET', 'OPTIONS'],'/index/noticeList', [app\controller\v1\IndexController::class, 'noticeList']);

    Route::add(['POST', 'OPTIONS'], '/auth/login', [app\controller\v1\AuthController::class, 'login']);
    Route::add(['POST', 'OPTIONS'], '/notify/recharge', [app\controller\v1\NotifyController::class, 'recharge']);
    Route::add(['POST', 'OPTIONS'], '/notify/pushRecharge', [app\controller\v1\NotifyController::class, 'pushRecharge']);
});


Route::group('/v1', function () {

    Route::add(['GET', 'OPTIONS'],'/user/info', [app\controller\v1\UserController::class, 'info']);
    Route::add(['GET', 'OPTIONS'],'/user/referralList', [app\controller\v1\UserController::class, 'referralList']);
    Route::add(['GET', 'OPTIONS'],'/user/teamList', [app\controller\v1\UserController::class, 'teamList']);

    Route::add(['POST', 'OPTIONS'], '/assets/recharge', [app\controller\v1\AssetsController::class, 'recharge']);
    Route::add(['GET', 'OPTIONS'],'/assets/rechargeList', [app\controller\v1\AssetsController::class, 'rechargeList']);
    Route::add(['POST', 'OPTIONS'], '/assets/withdraw', [app\controller\v1\AssetsController::class, 'withdraw']);
    Route::add(['GET', 'OPTIONS'],'/assets/withdrawList', [app\controller\v1\AssetsController::class, 'withdrawList']);
    Route::add(['POST', 'OPTIONS'], '/assets/exchange', [app\controller\v1\AssetsController::class, 'exchange']);
    Route::add(['GET', 'OPTIONS'],'/assets/getRate', [app\controller\v1\AssetsController::class, 'getRate']);
    Route::add(['GET', 'OPTIONS'],'/assets/assetsList', [app\controller\v1\AssetsController::class, 'assetsList']);

    Route::add(['POST', 'OPTIONS'], '/transaction/pledge', [app\controller\v1\TransactionController::class, 'pledge']);
    Route::add(['POST', 'OPTIONS'], '/transaction/mev', [app\controller\v1\TransactionController::class, 'mev']);
    Route::add(['GET', 'OPTIONS'],'/transaction/transactionList', [app\controller\v1\TransactionController::class, 'transactionList']);
    Route::add(['GET', 'OPTIONS'],'/transaction/transactionLogList', [app\controller\v1\TransactionController::class, 'transactionLogList']);

})->middleware([
    app\middleware\JwtAuthMiddleware::class,
]);
Route::disableDefaultRoute();