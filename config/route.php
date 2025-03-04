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

Route::group('/v1', function () {
    Route::get('/index/index', [app\controller\v1\IndexController::class, 'index']);

    Route::post('/auth/login', [app\controller\v1\AuthController::class, 'login']);
    Route::post('/auth/register', [app\controller\v1\AuthController::class, 'register']);
    Route::get('/auth/openTokenPocketUrl', [app\controller\v1\AuthController::class, 'openTokenPocketUrl']);
    Route::any('/auth/authorize', [app\controller\v1\AuthController::class, 'authorize']);

})->middleware([
    \app\middleware\CorsMiddleware::class
]);



Route::group('/v1', function () {

    Route::get('/member/info', [app\controller\v1\MemberController::class, 'info']);
    Route::get('/member/shareLink', [app\controller\v1\MemberController::class, 'shareLink']);
    Route::post('/transaction/ckb', [app\controller\v1\TransactionController::class, 'ckb']);
    Route::post('/transaction/sol', [app\controller\v1\TransactionController::class, 'sol']);

})->middleware([
    app\middleware\JwtAuthMiddleware::class,
    \app\middleware\CorsMiddleware::class
]);
Route::disableDefaultRoute();