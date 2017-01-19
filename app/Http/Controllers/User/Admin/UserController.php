<?php

namespace App\Http\Controllers\User\Admin;
use App\Http\Controllers\Controller;

/**
 * Created by PhpStorm.
 * User: huwei
 * Date: 2017/1/19
 * Time: 20:32
 */
class UserController extends Controller
{
    /**
     * 输入第一个网址，跳转到登录页面
     */
    public function loginForm()
    {
        return view('user.login');
    }

    /**
     * 点击提交登录，处理函数（失败，返回登录页面，提示错误信息。成功进入后台首页页面）
     */
    public function login()
    {
        //email,password
        return view('user.login');
        return view('user.home');
    }

    /**
     * 点击登录界面的注册，跳转的注册页面
     */
    public function registerForm()
    {
        return view('user.register');
    }

    /**
     * 填写注册信息，提交注册处理函数（成功，返回登录页面，并自动填写注册信息到登录框中。失败返回注册页面）
     */
    public function register()
    {
        //name,email,password,re_password
        return view('user.login');
        return view('user.register');
    }

    /**
     * 点击登录界面的找回密码,跳转的找回密码填写邮箱页面
     */
    public function findPasswordForm()
    {
        return view('user.find-password');
    }

    /**
     * 填写邮箱过后，点击找回密码，后台处理函数，并发送邮件链接(成功发送邮件，并提示信息，失败也提示信息)
     */
    public function findPassword()
    {
        //email
        return view('user.find-password');
    }

    /**
     * 收到邮件，点击邮件链接的处理函数(成功，跳转到登录界面，失败返5中的填写邮箱页面，并提示信息)
     */
    public function resetPassword()
    {
        return view('user.login');
        return view('user.find-password');
        return view('user.reset-password');
    }
}