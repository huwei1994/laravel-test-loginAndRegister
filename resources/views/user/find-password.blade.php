<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>找回密码界面</title>
</head>
<body>
<form method="post" action="{{url('/user/admin/find-password')}}">
    <span>找回密码</span>
    @if(isset($data['res']))
    <p>邮箱：</p><input type="text" name="email" value="{{$data['res']}}">
    @else
    <p>邮箱：</p><input type="text" name="email" value="">
    @endif
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="submit" name="" value="找回密码">
</form>
</body>
</html>