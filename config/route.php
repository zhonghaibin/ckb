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
    Route::post('/auth/login', [app\controller\v1\AuthController::class, 'login']);
    Route::post('/auth/register', [app\controller\v1\AuthController::class, 'register']);
})->middleware([
    \app\middleware\CorsMiddleware::class
]);
Route::group('/v1', function () {

    Route::get('/member/info', [app\controller\v1\MemberController::class, 'info']);
    Route::get('/member/shareLink', [app\controller\v1\MemberController::class, 'shareLink']);

})->middleware([
    app\middleware\JwtAuthMiddleware::class,
    \app\middleware\CorsMiddleware::class
]);
Route::disableDefaultRoute();