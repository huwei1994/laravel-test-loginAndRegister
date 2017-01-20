<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<body>
<a href="{{ URL('/user/admin/reset-password-form/?token='.$data['token'].'&email='.$data['email'])}}" target="_blank">
    {{$data['name']}},有效期一分钟，请尽快点击链接找回密码</a>
<input type="hidden" name="_token" value="{{ csrf_token() }}">
</body>
</html>