<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::group(['namespace' => 'User', 'prefix' => 'user'], function()
{
    Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function()
    {
        //1：输入第一个网址，跳转到登录页面
        Route::get('login-form','UserController@loginForm');
        //2：点击提交登录，处理函数（失败，返回登录页面，提示错误信息。成功进入后台首页页面）
        Route::post('login','UserController@login');
        //3：点击登录界面的注册，跳转的注册页面
        Route::get('register-form','UserController@registerForm');
        //4：填写注册信息，提交注册处理函数（成功，返回登录页面，并自动填写注册信息到登录框中。失败返回注册页面）
        Route::post('register','UserController@register');
        //5：点击登录界面的找回密码,跳转的找回密码填写邮箱页面
        Route::get('find-password-form','UserController@findPasswordForm');
        //6：填写邮箱过后，点击找回密码，后台处理函数，并发送邮件链接(成功发送邮件，并提示信息，失败也提示信息)
        Route::post('find-password','UserController@findPassword');
        //7：收到邮件，点击邮件链接的处理函数(成功，跳转到登录界面，失败返5中的填写邮箱页面，并提示信息)
        Route::post('reset-password','UserController@resetPassword');
    });
});

