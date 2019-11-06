<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
	<script src="{{asset('js/jquery-1.7.2.min.js')}}"></script>
</head>
<body>
	<form action="{{url('wechat/add')}}" method="post">
		账号：<input type="text" name="name"><br>
		密码：<input type="password" name="pwd"><br>
			<input type="submit" name="" value="登陆">

	</form>
	<button id="butt">微信授权登陆</button>
	<script type="text/javascript">
		$(function(){
			$('#butt').click(function(){
				window.location.href="{{url('wechat/event')}}";
			});
		});
	</script>
</body>
</html>