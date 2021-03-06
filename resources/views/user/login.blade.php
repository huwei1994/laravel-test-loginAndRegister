<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>登录界面</title>
    <script>
        window.onload=function()
        {
            if(document.getElementById("error_message").innerHTML !="")
            {
                alert(document.getElementById("error_message").innerHTML);
            }
        };
    </script>
</head>
<body>
    <form method="post" action="{{url('/user/admin/login')}}">
        <span>登录</span>
        @if(isset($data['res']) && count($data['res']) !== 0)
        <p>邮箱：</p><input type="text" name="email" value="{{$data['res']->email}}">
        <p>密码：</p><input type="text" name="password" value="{{$data['res']->password}}">
        @else
        <p>邮箱：</p><input type="text" name="email" value="">
        <p>密码：</p><input type="text" name="password" value="">
        @endif
        <input type="submit" name="" value="登录">
        <p><button><a href="{{url('/user/admin/register-form')}}">注册</a></button></p>
        <p><button><a href="{{url('/user/admin/find-password-form')}}">找回密码</a></button></p>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        @if(isset($data['info']))
            <div style="display:none" id="error_message">{{$data['info']}}</div>
        @else
            <div style="display:none" id="error_message"></div>
        @endif
    </form>
</body>
</html>