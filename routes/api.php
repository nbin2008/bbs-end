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
        // 话题分类列表
        $api->get('categories', 'CategoriesController@index')->name('api.categories.index');
        // 话题列表
        $api->get('topics', 'TopicsController@index')->name('api.topics.index');
        // 某个用户的话题列表
        $api->get('topics/user', 'TopicsController@userIndex')->name('api.topics.user.index');
        // 话题详情
        $api->get('topics/show', 'TOpicsController@show')->name('api.topics.show');
        // 话题回复列表
        $api->get('topics/reply/index', 'RepliesController@index')->name('api.topics.reply.index');
        // 某个用户的回复列表
        $api->get('users/reply/index', 'RepliesController@userIndex')->name('api.users.reply.index');

        // 需要token验证的接口
        $api->group(['middleware' => 'api.auth'], function ($api){
            // 当前登录用户信息
            $api->get('user', 'UsersController@me')->name('api.user.show');
            // 编辑登录用户信息
            $api->post('user/update', 'UsersController@update')->name('api.user.update');
            // 图片上传
            $api->post('images/upload', 'ImagesController@store')->name('api.images.store');
            // 发布话题
            $api->post('topics/store', 'TopicsController@store')->name('api.topics.store');
            // 修改话题
            $api->post('topics/update', 'TopicsController@update')->name('api.topics.update');
            // 删除话题
            $api->post('topics/delete', 'TopicsController@destroy')->name('api.topics.destroy');
            // 发布回复
            $api->post('topics/reply/store', 'RepliesController@store')->name('api.topics.reply.store');
            // 删除回复
            $api->post('topics/reply/destroy', 'RepliesController@destroy')->name('api.topics.reply.destroy');
            // 通知解表
            $api->get('user/notifications', 'NotificationsController@index')->name('api.user.notifications.index');
            // 通知统计
            $api->get('user/notifications/stats', 'NotificationsController@stats')->name('api.user.notifications.stats');
            // 编辑消息通知为已读
            $api->post('user/notifications/read', 'NotificationsController@read')->name('api.user.notifications.read');
        });
    });
});
