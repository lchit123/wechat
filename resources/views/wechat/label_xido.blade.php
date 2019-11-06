<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<form action="{{url('wechat/label_xi')}}" method="post">
	@csrf
	<input type="hidden" name="id" value="{{$data['id']}}">
	推送的消息：<input type="text" name="content"><br>
	<input type="submit" name="" value="推送消息">
	</form>
</body>
</html>