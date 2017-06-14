<?php 
	
	// 导入下载好的第三方类（composer下载的）
	require('../vendor/autoload.php');

	/*$phpexcel = new PHPExcel;
	var_dump($phpexcel);*/

	// 引入模型和控制器
	$user = new \app\Model\User;
	$user->index();
	$user = new \app\Controller\User;
	$user->index();

	$good = new app\Controller\Good;
	$good->index();

	$api = new app\Api\Api();


