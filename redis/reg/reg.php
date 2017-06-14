<?php
	
	// 得到redis对象
	$redis = new Redis();

	// 连接
	$redis->connect('localhost' , 6379);

	// 接收注册信息
	$name = $_PSOT['name'];
	$pass = password_hash($_POST['pass'] , PASSWORD_DEFAULT);

	// 使用incr创建自增的用户ID
	$uid = $redis->incr('uid');

	// 将数据转换为字符串存进redis
	$data = json_encode(array(
		'uid'  => $uid,
		'name' => $name,
		'pass' => $pass
	));
	var_dump($data);

