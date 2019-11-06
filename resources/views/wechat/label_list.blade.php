<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>

	<table border="1">
		<a href="{{url('wechat/list_add')}}">添加标签</a>
		<tr>
			<td>id</td>
			<td>标签名称</td>
			<td>粉丝数量</td>
			<td>操作</td>
		</tr>
		@foreach ($data as $v)
		<tr>
			<td>{{$v['id']}}</td>
			<td>{{$v['name']}}</td>
			<td>{{$v['count']}}</td>
			<td>
				<a href="{{url('wechat/label_del')}}?id={{$v['id']}}">删除</a>|
				<a href="{{url('wechat/label_update')}}?id={{$v['id']}}&name={{$v['name']}}">编辑</a>
				<a href="{{url('wechat/index')}}?id={{$v['id']}}">为用户打个标签</a>|
				<a href="{{url('wechat/label_xido')}}?id={{$v['id']}}">根据标签为用户推送消息</a>|
			</td>
		</tr>
		@endforeach
	</table>
</body>
</html>