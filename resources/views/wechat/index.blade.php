<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
		<script src="{{asset('js/jquery-1.7.2.min.js')}}"></script>
</head>
<body>
	<form action="{{url('wechat/label_index')}}" method="post">
		@csrf
		<input type="hidden" name="id" value="{{$req['id']}}">
	<table border="1">
	<tr>
		<td>多选</td>
		<td>图片</td>
		<td>名称</td>
		<td>城市</td>
		<td>opid</td>
		<td>操作</td>
	</tr>
	@foreach ($info as $v)
	<tr>

		<td><input type="checkbox" name="openid_list[]" value="{{$v['openid']}}"></td>
		<td><img src="{{$v['headimgurl']}}"></td>
		<td>{{$v['nickname']}}</td>
		<td>{{$v['city']}}</td>
		<td>{{$v['openid']}}</td>
		<td><a href="{{url('wechat/label_user')}}?openid={{$v['openid']}}">查看用户所在的标签</a></td>
	</tr>
	@endforeach
	<input type="submit" name="" value="提交">
	</form>
	</table>
</body>
</html>