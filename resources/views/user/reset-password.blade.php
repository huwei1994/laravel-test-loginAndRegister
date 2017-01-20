<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>重置密码界面</title>
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
<form method="post" action="{{url('/user/admin/reset-password')}}">
    <span>重置密码</span>
    <p>新密码：</p><input type="text" name="password" value="">
    <p>确认密码：</p><input type="text" name="password_confirmation" value="">
    @if(isset($data['res']))
    <input type="hidden" name="email" value="{{$data['res']}}">
    @else
        <input type="hidden" name="email" value="">
    @endif
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="submit" name="" value="重置密码">
    @if(isset($data['info']))
        <div style="display:none" id="error_message">{{$data['info']}}</div>
    @else
        <div style="display:none" id="error_message"></div>
    @endif
</form>
</body>
</html>