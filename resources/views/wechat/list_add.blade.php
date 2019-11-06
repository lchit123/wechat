<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<form action="{{url('wechat/label_add')}}" method="post">
		@csrf
		标签名<input type="text" name="name"><br>
			<input type="submit" value="提交">

	</form>
</body>
</html>