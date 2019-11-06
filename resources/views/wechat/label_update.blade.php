<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<form action="{{url('wechat/do_update')}}" method="post">
	@csrf
		<input type="hidden" name="id" value="{{$data['id']}}">
		标签名：<input type="text" name="name" value="{{$data['name']}}"><br>
			<input type="submit" name="" value="修改">
	</form>
</body>
</html>