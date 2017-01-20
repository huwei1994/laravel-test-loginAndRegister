<?php

namespace App\Http\Controllers\User\Admin;
use App\Http\Controllers\Controller;
use App\Model\User\UserModel;
use Illuminate\Http\Request;
use Validator;
use Crypt;
use Mail;
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
    public function login(Request $request)
    {
        //email,password//使用数组，规定验证规则，方便维护
        $check = [
            'email' => 'required|email|filled',
            'password' => 'required|string|filled|min:6',
        ];
        $data = [];
        //验证数据
        $validator = Validator::make($request->all(), $check);
        if ($validator->fails())
        {
            $errorInfo = $validator->errors()->all();
            $data['info'] = $errorInfo[0];
            return view('user.login')->with('data',$data);
        }

        //验证成功,接收数据
        $email = $request->input('email');
        $password = $request->input('password');
        //将数据组装，调用模型方法
        $user = new UserModel;
        $where = [
            ['email','=',$email],
        ];
        $checkEmail = $user->get($where);
        $wheres = [
            ['email','=',$email],
            ['password','=',$password],
        ];
        $checkPassword = $user->get($wheres);
        if(!$checkEmail)
        {
            //失败return view('user.login'),并带回提示信息
            $data['info'] = '用户不存在';
            $data['res'] = $checkEmail;
            return view('user.login')->with('data',$data);
        }
        if($checkEmail && !$checkPassword)
        {
            //失败return view('user.login'),并带回提示信息
            $data['info'] = '密码错误，请重新输入';
            $data['res'] = $checkPassword;
            return view('user.login')->with('data',$data);
        }
        //成功return view('user.home'),并带回提示信息;
        $data['info'] = '登录成功';
        $data['res'] = $checkPassword;
        return view('user.home')->with('data',$data);
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
    public function register(Request $request)
    {
        //name,email,password,re_password
        $check = [
            'name' => 'required|string|filled',
            'email' => 'required|email|filled',
            'password' => 'required|string|filled|min:6|confirmed',
        ];
        $data = [];
        //验证数据
        $validator = Validator::make($request->all(), $check);
        if ($validator->fails())
        {
            $errorInfo = $validator->errors()->all();
            $data['info'] = $errorInfo[0];
            return view('user.register')->with('data',$data);
        }
        $user = new UserModel();

        //验证成功,接收数据
        $email = $request->input('email');
        $where = array(
            ['email','=',$email]
        );
        $res = $user->get($where);
        if($res)
        {
            $data['info'] = '邮箱已存在';
            return view('user.register')->with('data',$data);
        }
        $password = $request->input('password');
        $name = $request->input('name');
        //将数据组装，调用模型方法
        $option = [
            'name'=>$name,
            'email'=>$email,
            'password'=>$password,
            'created_at'=>time(),
        ];

        $res = $user->insert($option);
        //成功return view('user.login'),并带回提示信息;
        $data['res'] = $res[0];
        if($res)
        {
            $data['info'] = '注册成功';
            return view('user.login')->with('data',$data);
        }
        //失败return view('user.register'),并带回提示信息
        $data['info'] = '注册异常';
        return view('user.register')->with('data',$data);
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
    public function findPassword(Request $request)
    {
        //email
        $check = [
            'email' => 'required|email|filled',
        ];
        $data = [];
        //验证数据
        $validator = Validator::make($request->all(), $check);
        if ($validator->fails())
        {
            $errorInfo = $validator->errors()->all();
            $data['info'] = $errorInfo[0];
            return view('user.find-password')->with('data',$data);
        }

        //验证成功,接收数据
        $email = $request->input('email');
        $where = array(
            ['email','=',$email],
        );

        $user = new UserModel;
        $users = $user->get($where);
        if(!$users)
        {
            $data['info'] = '用户不存在，请确认所输入的邮箱';
            return view('user.find-password')->with('data',$data);
        }
        $id = $users[0]->id;
        $name = $users[0]->name;
        //发邮件（生成随机token，和有效期并保存token和有效期）
        $token = uniqid(md5(time()),true).mt_rand(0,10000);
        $token_time = time() + 1*60;
        $wheres = array(
            ['id','=',$id],
        );
        $option = array(
            'token' => $token,
            'token_at' => $token_time,
            'modified_at'=>time(),
        );

        $user->update($option,$wheres);

        //构造链接（发送成功，并把数据返回给找回密码页面）
        $data = ['email'=>$email,'token'=>$token,'name'=>$name];
        Mail::send('user.email', ['data' => $data], function ($message) use ($data) {

            $message->to($data['email'])->cc('bar@example.com');
        });
        $data['info'] = '邮件发送成功，请查看';
        $data['res'] = $email;
        return view('user.find-password')->with('data',$data);
    }

    /**
     * 收到邮件，点击邮件链接的处理函数(成功，跳转到重置密码页面，失败返5中的填写邮箱页面，并提示信息)
     */
    public function resetPasswordForm(Request $request)
    {
        //验证token参数
        //验证成功，接收token参数
        //验证token是否正确和过期
        //未过期就跳转到重置密码页面
        //过期就跳转到填写邮箱，找回密码页面
        $check = [
            'token' => 'required|filled',
            'email' => 'required|filled',
        ];
        $data = [];
        //验证数据
        $validator = Validator::make($request->all(), $check);
        if ($validator->fails())
        {
            $errorInfo = $validator->errors()->all();
            $data['info'] = $errorInfo[0];
            return view('user.find-password')->with('data',$data);
        }

        //验证成功,接收数据
        $email = $request->input('email');
        $token = $request->input('token');
        $where = array(
            ['email','=',$email],
        );
        $user = new UserModel;
        $users = $user->get($where);
        if(!$users)
        {
            $data['info'] = '用户不存在，请确认所输入的邮箱';
            return view('user.find-password')->with('data',$data);
        }

        $tokencheck = $users[0]->token;
        $time = $users[0]->token_at;
        if($token == $tokencheck && time() <=$time)
        {
            $data['info'] = '操作成功，请尽快修改密码';
            $data['res'] = $email;
            return view('user.reset-password')->with('data',$data);
        }
        $data['info'] = '认证信息过期，请重新发送邮件';
        return view('user.find-password')->with('data',$data);
    }

    /**
     * 点击重置密码按钮后台处理函数(成功，跳转到登录界面，失败返5中的重置密码页面，并提示信息)
     */
    public function resetPassword(Request $request)
    {
        //验证密码参数
        //验证成功，接收token参数
        //验证token是否正确和过期
        //未过期就
        //name,email,password,re_password
        $check = [
            'password' => 'required|string|filled|min:6|confirmed',
            'email' => 'required|filled',
        ];
        $data = [];
        //验证数据
        $validator = Validator::make($request->all(), $check);
        if ($validator->fails())
        {
            $errorInfo = $validator->errors()->all();
            $data['info'] = $errorInfo[0];
            return view('user.reset-password')->with('data',$data);
        }

        //验证成功,接收数据
        $password = $request->input('password');
        $email = $request->input('email');
        $where = array(
            ['email','=',$email],
        );

        $user = new UserModel;
        $users = $user->get($where);
        if(!$users)
        {
            $data['info'] = '用户不存在，请确认所输入的邮箱';
            return view('user.reset-password')->with('data',$data);
        }
        $id = $users[0]->id;

        $wheres = array(
            ['id','=',$id],
        );
        $option = array(
            'password'=>$password,
            'modified_at'=>time(),
        );
        $res = $user->update($option,$wheres);
        $data['res'] = $res[0];
        if($res)
        {
            $data['info'] = '重置密码成功';
            return view('user.login')->with('data',$data);
        }
        $data['info'] = '重置密码异常';
        return view('user.reset-password')->with('data',$data);
    }
}