<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
], function($api) {
    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ], function ($api) {
        // 用户注册
        $api->post('users/create', 'UsersController@store')->name('api.users.store');
        // 图片验证码
        $api->get('captchas/get', 'CaptchasController@show')->name('api.captchas.show');
        // 登陆
        $api->post('login', 'AuthorizationsController@store')->name('api.authorizations.store');
        // 刷新token
        $api->post('authorizations/update', 'AuthorizationsController@update')->name('api.authorizations.update');
        // 删除token
        $api->post('authorizations/delete', 'AuthorizationsController@destroy')->name('api.authorizations.destroy');

        // 需要token验证的接口
        $api->group(['middleware' => 'api.auth'], function ($api){
            // 当前登录用户信息
            $api->get('user', 'UsersController@me')->name('api.user.show');
            // 编辑登录用户信息
            $api->post('user/update', 'UsersController@update')->name('api.user.update');
            // 图片上传
            $api->post('images/upload', 'ImagesController@store')->name('api.images.store');
        });
    });
});
