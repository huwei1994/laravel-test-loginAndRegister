<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>注册界面</title>
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
<form method="post" action="{{url('/user/admin/register')}}">
    <span>注册</span>
    @if(isset($data['res']) && count($data['res']) !== 0)
    <p>昵称：</p><input type="text" name="name" value="{{$data['res']->name}}">
    <p>邮箱：</p><input type="text" name="email" value="{{$data['res']->email}}">
    <p>密码：</p><input type="text" name="password" value="{{$data['res']->password}}">
    @else
    <p>昵称：</p><input type="text" name="name" value="">
    <p>邮箱：</p><input type="text" name="email" value="">
    <p>密码：</p><input type="text" name="password" value="">
    @endif
    <p>确认密码：</p><input type="text" name="password_confirmation" value="">
    <input type="submit" name="" value="注册">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    @if(isset($data['info']))
    <div style="display:none" id="error_message">{{$data['info']}}</div>
    @else
    <div style="display:none" id="error_message"></div>
    @endif
</form>
</body>
</html>